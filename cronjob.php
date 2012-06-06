<?php

/**
 * @author       Ralf Hertsch
 * @copyright    2010-today by phpManufaktur   
 * @link         http://phpManufaktur.de
 * @license      http://www.gnu.org/licenses/gpl.html
 * @version      $Id$
 */

// WebsiteBaker config.php einbinden
require_once('../../config.php');

require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');

global $dbCronjobData;
global $dbCronjobNewsLog;
global $dbNewsProcess;
global $dbCronjobErrorLog;

if (!is_object($dbCronjobData)) $dbCronjobData = new dbCronjobData();
if (!is_object($dbCronjobNewsLog)) $dbCronjobNewsLog = new dbCronjobNewsletterLog();
if (!is_object($dbNewsProcess)) $dbNewsProcess = new dbKITnewsletterProcess();
if (!is_object($dbCronjobErrorLog)) $dbCronjobErrorLog = new dbCronjobErrorLog();

$cronjob = new cronjob();
$cronjob->action();

class cronjob {
	
	const request_key			= 'key';
	
	private $error;
	private $start_script;
	
	public function __construct() {
		$this->start_script = time(true);
	} // __construct()
	
	private function setError($error) {
		global $dbCronjobErrorLog;
		$this->error = $error;
		// write simply to database - here is no chance to trigger additional errors...
		$dbCronjobErrorLog->sqlInsertRecord(array(dbCronjobErrorLog::field_error => $error));
	} // setError()
	
	private function getError() {
		return $this->error;
	} // getError()
	
	private function isError() {
	    return (bool) !empty($this->error);
	} // isError
	
	/**
	 * Action Handler
	 * 
	 */
	public function action() {
		global $dbCronjobData;
		global $dbNewsProcess;
		global $dbCfg;
		global $tools;
		
		// Log access to cronjob...
		$where = array(dbCronjobData::field_item => dbCronjobData::item_last_call);
		$data = array();
		if (!$dbCronjobData->sqlSelectRecord($where, $data)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCronjobData->getError()));
			exit($this->getError());
		}
		if (count($data) < 1) {
			// entry does not exists, create default entries...
			$datas = array(
			        array(dbCronjobData::field_item => dbCronjobData::item_last_call, dbCronjobData::field_value => ''),
			        array(dbCronjobData::field_item => dbCronjobData::item_last_job, dbCronjobData::field_value => ''),
			        array(dbCronjobData::field_item => dbCronjobData::item_last_nl_id, dbCronjobData::field_value => '')
			        );
			foreach ($datas as $data) {
				if (!$dbCronjobData->sqlInsertRecord($data)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCronjobData->getError()));
					exit($this->getError());
				}
			}
		}
		// log this access...
		$data = array(dbCronjobData::field_value => date('Y-m-d H:i:s'));
		if (!$dbCronjobData->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCronjobData->getError()));
			exit($this->getError());
		} 
		
		// check if the access is allowed
		$cronjob_key = $dbCfg->getValue(dbKITcfg::cfgCronjobKey);
		if (strlen($cronjob_key) < 3) {
			// Cronjob Key does not exist, so create one...
			$cronjob_key = $tools->generatePassword();
			$dbCfg->setValueByName($cronjob_key, dbKITcfg::cfgCronjobKey); 
		}
		if (!isset($_REQUEST[self::request_key]) || ($_REQUEST[self::request_key] !== $cronjob_key)) {
			// Cronjob key does not match, log denied access...
			$ip = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '000.000.000.000';
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf('Access denied from IP %s:  invalid or missing cronjob key!', $ip)));
			// dont give attacker any hint, so exit with regular code...
			exit(0);
		}
		
		// check if there are jobs waiting...
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='0' ORDER BY %s ASC LIMIT 1",
										$dbNewsProcess->getTableName(),
										dbKITnewsletterProcess::field_is_done,
										dbKITnewsletterProcess::field_update_when);
		$cronjob = array();
		if (!$dbNewsProcess->sqlExec($SQL, $cronjob)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsProcess->getError()));
			exit($this->getError());
		}
		
		$process_newsletter = false;
		
		if (count($cronjob) == 1) {
		    // processing newsletters has priority!
			$cronjob = $cronjob[0];				
			// jobs to do...
			if (!empty($cronjob[dbKITnewsletterProcess::field_register_ids])) {
				// process newsletter
				$this->processNewsletter($cronjob);
				$process_newsletter = true;
			}
			elseif (!empty($cronjob[dbKITnewsletterProcess::field_distribution_ids])) {
				// process distribution
				$this->processDistribution($cronjob);
				$process_newsletter = true;
			}
			else {
				// Error, nothing to do - kill job and prompt error
				$where = array(dbKITnewsletterProcess::field_id => $cronjob[dbKITnewsletterProcess::field_id]);
				$cronjob[dbKITnewsletterProcess::field_job_done_dt] = date('Y-m-d H:i:s');
				$cronjob[dbKITnewsletterProcess::field_is_done] = 1;
				$cronjob[dbKITnewsletterProcess::field_send] = 0;
				$cronjob[dbKITnewsletterProcess::field_job_process_time] = 0;
				if (!$dbNewsProcess->sqlUpdateRecord($cronjob, $where)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsProcess->getError()));
					exit($this->getError());
				}
				// log error		
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, 
												sprintf("Neither Newsletter ID's nor Distribution ID's to process in record <b>%d</b>, job killed!", $cronjob[dbKITnewsletterProcess::field_id])));
				exit($this->getError());
			}
		}
		
		if (!$process_newsletter) {
		    // there was no newsletter to process, so look what else is to do...
		    if (file_exists(WB_PATH.'/modules/kit_idea/class.cronjob.php')) {
		        require_once WB_PATH.'/modules/kit_idea/class.cronjob.php';
		        $ideaCronjob = new ideaCronjob();
		        $result = $ideaCronjob->action();
		        if ($ideaCronjob->isError()) {
		            // ideaCronjob logs all errors by itself, so just leave cronjob...
		            exit($ideaCronjob->getError());
		        }
		        exit($result);
		    } 
		}
		exit(0);
	} // action()
	
	/**
	 * Write Log Entry for processing of each Newsletter Mail
	 * @param INT $process_id
	 * @param STR $email
	 * @param INT $kit_id
	 * @param INT $status
	 * @param STR $error
	 * @return BOOL 
	 */
	private function writeNewsletterLog($process_id, $email, $kit_id, $status, $error) {
		global $dbCronjobNewsLog;
		$data = array(
			dbCronjobNewsletterLog::field_email 				=> $email,
			dbCronjobNewsletterLog::field_error 				=> $error,
			dbCronjobNewsletterLog::field_kit_id 				=> $kit_id,
			dbCronjobNewsletterLog::field_nl_process_id => $process_id,
			dbCronjobNewsletterLog::field_status				=> $status
		);
		if (!$dbCronjobNewsLog->sqlInsertRecord($data)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCronjobNewsLog->getError()));
			exit($this->getError());
		}		
		return true;
	} // writeNewsletterLog()
	
	private function processNewsletter($cronjob=array()) {
		global $dbNewsletterArchive;
  	global $dbProvider;
  	global $dbContact;
  	global $dbNewsletterTemplates;
  	global $newsletterCommands;
  	global $dbRegister;
    global $dbNewsletterCfg;
    global $dbNewsProcess;
		
    if (!isset($cronjob[dbKITnewsletterProcess::field_archiv_id])) {
    	// Fehler, Aufruf ohne Archiv ID
    	$this->setError(sprintf('[%s -%s] %s', __METHOD__, __LINE__, 'call function without valid Newsletter Archive ID!'));
    	exit($this->getError());
    }
    
		// Newsletter auslesen
  	$where = array();
  	$where[dbKITnewsletterArchive::field_id] = $cronjob[dbKITnewsletterProcess::field_archiv_id];
  	$newsletter = array();
  	if (!$dbNewsletterArchive->sqlSelectRecord($where, $newsletter)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
  		exit($this->getError());
  	}
  	if (count($newsletter) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $cronjob[dbKITnewsletterProcess::field_archiv_id])));
  		exit($this->getError());
  	}
  	$newsletter = $newsletter[0];

  	// Provider
  	$where = array();
		$where[dbKITprovider::field_id] = intval($newsletter[dbKITnewsletterArchive::field_provider]);
		$provider = array();
		if (!$dbProvider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			exit($this->getError());
		}
		if (count($provider) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $newsletter[dbKITnewsletterArchive::field_provider])));
  		exit($this->getError());
		}
		$provider = $provider[0];
		
		// Template
		$where = array();
		$where[dbKITnewsletterTemplates::field_id] = $newsletter[dbKITnewsletterArchive::field_template];
		$template = array();
		if (!$dbNewsletterTemplates->sqlSelectRecord($where, $template)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
			exit($this->getError());
		}
		if (count($template) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $newsletter[dbKITnewsletterArchive::field_template])));
			exit($this->getError());
		}
		$template = $template[0];
		
		$worker = explode(',', $cronjob[dbKITnewsletterProcess::field_register_ids]);
		$in_ids = '';
		foreach ($worker as $id) {
			$in_ids .= (strlen($in_ids) > 0) ? sprintf(",'%d'", $id) : sprintf("'%d'", $id);
		}
		// Empfaenger Adressen zusammenstellen
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s IN (%s)",
										$dbRegister->getTableName(),
										dbKITregister::field_contact_id,
										$in_ids);
		$addresses = array();
		if (!$dbRegister->sqlExec($SQL, $addresses)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
			exit($this->getError());
		}
		
		$transmitted = 0;
		
		foreach ($addresses as $address) {
			// E-Mail Programm starten
    	$kitMail = new kitMail($provider[dbKITprovider::field_id]);
    	// HTML body generieren
			$html = utf8_encode($newsletter[dbKITnewsletterArchive::field_html]);
			if ($newsletterCommands->parseCommands($html, '', $address[dbKITregister::field_contact_id], $newsletter)) {
				$html_content = $template[dbKITnewsletterTemplates::field_html];
				if (!$newsletterCommands->parseCommands($html_content, $html, $address[dbKITregister::field_contact_id], $newsletter)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
					exit($this->getError());
				}
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
				exit($this->getError()); 
			}

			// TEXT body generieren
			$text = utf8_encode($newsletter[dbKITnewsletterArchive::field_text]);
			if ($newsletterCommands->parseCommands($text, '', $address[dbKITregister::field_contact_id], $newsletter)) {
				$text_content = $template[dbKITnewsletterTemplates::field_text];
				if (!$newsletterCommands->parseCommands($text_content, $text, $address[dbKITregister::field_contact_id], $newsletter)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
					exit($this->getError());
				}
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
				exit($this->getError());
			}

      if ($cronjob[dbKITnewsletterProcess::field_simulate] == 1) {
        // Versand wird nur simuliert!
        $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
        													$address[dbKITregister::field_email], 
        													$address[dbKITregister::field_contact_id], 
        													dbCronjobNewsletterLog::status_simulation, 
        													'');
      }
      else {
        // send Newsletter!
        if ($kitMail->sendNewsletter(	$newsletter[dbKITnewsletterArchive::field_subject],
                                      $html_content,
                                      $text_content,
                                      $provider[dbKITprovider::field_email],
                                      $provider[dbKITprovider::field_name],
                                      $address[dbKITregister::field_email],
                                      '',
                                      true)) {
          $transmitted++;
          $protocol = sprintf( kit_protocol_send_newsletter_success,
                                                            $newsletter[dbKITnewsletterArchive::field_subject],
                                                            date('H:i:s'),
                                                            $address[dbKITregister::field_email]);
          if (!$dbContact->addSystemNotice($address[dbKITregister::field_contact_id], $protocol)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE, $dbContact->getError()));
            exit($this->getError());
          }
          $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
          													$address[dbKITregister::field_email], 
          													$address[dbKITregister::field_contact_id], 
        														dbCronjobNewsletterLog::status_ok,
        														'');
        }
        else {
          if (!$dbContact->addSystemNotice($address[dbKITregister::field_contact_id], sprintf( kit_protocol_send_newsletter_fail,
                                                            $newsletter[dbKITnewsletterArchive::field_subject],
                                                            date('H:i:s'),
                                                            $address[dbKITregister::field_email],
                                                            $kitMail->getMailError()))) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE, $dbContact->getError()));
            exit($this->getError());
          }
          $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
          													$address[dbKITregister::field_email], 
          													$address[dbKITregister::field_contact_id], 
        														dbCronjobNewsletterLog::status_error,
        														$kitMail->getMailError());
        }
      } // send Newsletter
			$kitMail->__destruct();
		}
		
		// Protocoll schreiben
		$where = array(dbKITnewsletterProcess::field_id => $cronjob[dbKITnewsletterProcess::field_id]);
		$cronjob[dbKITnewsletterProcess::field_job_done_dt] = date('Y-m-d H:i:s');
		$cronjob[dbKITnewsletterProcess::field_is_done] = 1;
		$cronjob[dbKITnewsletterProcess::field_send] = $transmitted;
		$cronjob[dbKITnewsletterProcess::field_job_process_time] = (microtime(true) - $this->start_script);
		if (!$dbNewsProcess->sqlUpdateRecord($cronjob, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsProcess->getError()));
			exit($this->getError());
		}
		exit('OK');
	} // processNewsletter()
	
	private function processDistribution($cronjob=array()) {
		global $dbNewsletterArchive;
  	global $dbProvider;
  	global $dbContact;
  	global $dbNewsletterTemplates;
  	global $newsletterCommands;
  	global $dbNewsletterCfg;
    global $dbNewsProcess;
		
    if (!isset($cronjob[dbKITnewsletterProcess::field_archiv_id])) {
    	// Fehler, Aufruf ohne Archiv ID
    	$this->setError(sprintf('[%s -%s] %s', __METHOD__, __LINE__, 'call function without valid Newsletter Archive ID!'));
    	exit($this->getError());
    }
    
		// Newsletter auslesen
  	$where = array();
  	$where[dbKITnewsletterArchive::field_id] = $cronjob[dbKITnewsletterProcess::field_archiv_id];
  	$newsletter = array();
  	if (!$dbNewsletterArchive->sqlSelectRecord($where, $newsletter)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
  		exit($this->getError());
  	}
  	if (count($newsletter) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $cronjob[dbKITnewsletterProcess::field_archiv_id])));
  		exit($this->getError());
  	}
  	$newsletter = $newsletter[0];

  	// Provider
  	$where = array();
		$where[dbKITprovider::field_id] = intval($newsletter[dbKITnewsletterArchive::field_provider]);
		$provider = array();
		if (!$dbProvider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			exit($this->getError());
		}
		if (count($provider) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $newsletter[dbKITnewsletterArchive::field_provider])));
  		exit($this->getError());
		}
		$provider = $provider[0];
		
		// Template
		$where = array();
		$where[dbKITnewsletterTemplates::field_id] = $newsletter[dbKITnewsletterArchive::field_template];
		$template = array();
		if (!$dbNewsletterTemplates->sqlSelectRecord($where, $template)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
			exit($this->getError());
		}
		if (count($template) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $newsletter[dbKITnewsletterArchive::field_template])));
			exit($this->getError());
		}
		$template = $template[0];
		
		$worker = explode(',', $cronjob[dbKITnewsletterProcess::field_distribution_ids]);
		$in_ids = '';
		foreach ($worker as $id) {
			$in_ids .= (strlen($in_ids) > 0) ? sprintf(",'%d'", $id) : sprintf("'%d'", $id);
		}
		// Empfaenger Adressen zusammenstellen
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s IN (%s)",
										$dbContact->getTableName(),
										dbKITcontact::field_id,
										$in_ids);
		$addresses = array();
		if (!$dbContact->sqlExec($SQL, $addresses)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			exit($this->getError());
		}
		
		$transmitted = 0;
		
		foreach ($addresses as $address) {
			// E-Mail Programm starten
    	$kitMail = new kitMail($provider[dbKITprovider::field_id]);
    	// HTML body generieren
			$html = utf8_encode($newsletter[dbKITnewsletterArchive::field_html]);
			if ($newsletterCommands->parseCommands($html, '', $address[dbKITcontact::field_id], $newsletter)) {
				$html_content = $template[dbKITnewsletterTemplates::field_html];
				if (!$newsletterCommands->parseCommands($html_content, $html, $address[dbKITcontact::field_id], $newsletter)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
					exit($this->getError());
				}
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
				exit($this->getError());
			}

			// TEXT body generieren
			$text = utf8_encode($newsletter[dbKITnewsletterArchive::field_text]);
			if ($newsletterCommands->parseCommands($text, '', $address[dbKITcontact::field_id], $newsletter)) {
				$text_content = $template[dbKITnewsletterTemplates::field_text];
				if (!$newsletterCommands->parseCommands($text_content, $text, $address[dbKITcontact::field_id], $newsletter)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
					exit($this->getError());
				}
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $newsletterCommands->getError()));
				exit($this->getError());
			}
			
			// use standard email address
			$email_address = $dbContact->getStandardEMailByID($address[dbKITcontact::field_id]);
        
      if ($cronjob[dbKITnewsletterProcess::field_simulate] == 1) {
        // Versand wird nur simuliert!
        $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
        													$email_address, 
        													$address[dbKITcontact::field_id], 
        													dbCronjobNewsletterLog::status_simulation,
        													'');
      }
      else {
        // send Newsletter!
        if ($kitMail->sendNewsletter(	$newsletter[dbKITnewsletterArchive::field_subject],
                                      $html_content,
                                      $text_content,
                                      $provider[dbKITprovider::field_email],
                                      $provider[dbKITprovider::field_name],
                                      $email_address,
                                      '',
                                      true)) {
          $transmitted++;
          $protocol = sprintf( kit_protocol_send_newsletter_success,
                                                            $newsletter[dbKITnewsletterArchive::field_subject],
                                                            date('H:i:s'),
                                                            $email_address);
          if (!$dbContact->addSystemNotice($address[dbKITcontact::field_id], $protocol)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE, $dbContact->getError()));
            exit($this->getError());
          }
          $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
          													$email_address, 
          													$address[dbKITcontact::field_id], 
        														dbCronjobNewsletterLog::status_ok,
        														'');
        }
        else {
          if (!$dbContact->addSystemNotice($address[dbKITcontact::field_id], sprintf( kit_protocol_send_newsletter_fail,
                                                            $newsletter[dbKITnewsletterArchive::field_subject],
                                                            date('H:i:s'),
                                                            $email_address,
                                                            $kitMail->getMailError()))) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE, $dbContact->getError()));
            exit($this->getError());
          }
          $this->writeNewsletterLog($cronjob[dbKITnewsletterProcess::field_id], 
          													$email_address, 
          													$address[dbKITcontact::field_id], 
        														dbCronjobNewsletterLog::status_error,
        														$kitMail->getMailError());
        }
      } // send Newsletter
			$kitMail->__destruct();
		}
		
		// Protocoll schreiben
		$where = array(dbKITnewsletterProcess::field_id => $cronjob[dbKITnewsletterProcess::field_id]);
		$cronjob[dbKITnewsletterProcess::field_job_done_dt] = date('Y-m-d H:i:s');
		$cronjob[dbKITnewsletterProcess::field_is_done] = 1;
		$cronjob[dbKITnewsletterProcess::field_send] = $transmitted;
		$cronjob[dbKITnewsletterProcess::field_job_process_time] = (microtime(true) - $this->start_script);
		if (!$dbNewsProcess->sqlUpdateRecord($cronjob, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsProcess->getError()));
			exit($this->getError());
		}
		exit('OK');
		
	} // processDistribution()
	
} // class cronjob

?>