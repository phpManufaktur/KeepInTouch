<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2010, Ralf Hertsch
  Contact me: ralf.hertsch@phpManufaktur.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id$
  
**/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/include/phpmailer/class.phpmailer.php');

/**
 * KIT Mail class extending PHPMailer
 * PHPMailer class is renamed to xPHPMailer class to avoid conflicts with the
 * PHPMailer used by WebsiteBaker
 *
 */
class kitMail extends xPHPMailer {
	
	private $mailError = '';
	private $mailMessage = '';
	
	function __construct() {
		
		// set mailer defaults (PHP mail function)
		$db_wbmailer_routine = "phpmail";
		$db_wbmailer_smtp_host = "";
		$db_wbmailer_default_sendername = "WB Mailer";
		$db_server_email = SERVER_EMAIL;
		
		$tools = new kitTools();
		// get settings from WB database
		$settings = array();
		if ($tools->getWBSettings($settings)) {
			// get the settings
			$db_wbmailer_routine 						= $settings['wbmailer_routine'];
			$db_wbmailer_smtp_host 					= $settings['wbmailer_smtp_host'];
			$db_wbmailer_smtp_auth 					= (bool) $settings['wbmailer_smtp_auth'];
			$db_wbmailer_smtp_username 			= $settings['wbmailer_smtp_username'];
			$db_wbmailer_smtp_password 			= $settings['wbmailer_smtp_password'];
			$db_wbmailer_default_sendername = $settings['wbmailer_default_sendername'];
			$db_server_email								= $settings['server_email'];
				
			// set method to send out emails
			if ($db_wbmailer_routine == "smtp" && strlen($db_wbmailer_smtp_host) > 5) {
				// use SMTP for all outgoing mails send by Website Baker
				$this->IsSMTP();                                            
				$this->Host = $db_wbmailer_smtp_host;
				// check if SMTP authentification is required
				if ($db_wbmailer_smtp_auth && (strlen($db_wbmailer_smtp_username) > 1) && (strlen($db_wbmailer_smtp_password) > 1)) {
					// use SMTP authentification
					$this->SMTPAuth = true;     	  								// enable SMTP authentification
					$this->Username = $db_wbmailer_smtp_username;  	// set SMTP username
					$this->Password = $db_wbmailer_smtp_password;	  // set SMTP password
				}
			} 
			else {
				// use PHP mail() function for outgoing mails send by Website Baker
				$this->IsMail();
			}
			// set language file for PHPMailer error messages
			if (defined("LANGUAGE")) {
				$this->SetLanguage(strtolower(LANGUAGE));    // english default (also used if file is missing)
			}
	
			// set default charset
			if (defined('DEFAULT_CHARSET')) { 
				$this->CharSet = DEFAULT_CHARSET; 
			} 
			else {
				$this->CharSet='utf-8';
			}
	
			// set default sender name
			if ($this->FromName == 'Root User') {
				if (isset($_SESSION['DISPLAY_NAME'])) {
					$this->FromName = $_SESSION['DISPLAY_NAME'];            // FROM NAME: display name of user logged in
				} 
				else {
					$this->FromName = $db_wbmailer_default_sendername;			// FROM NAME: set default name
				}
			}
	
			/* 
				some mail provider (lets say mail.com) reject mails send out by foreign mail 
				relays but using the providers domain in the from mail address (e.g. myname@mail.com)
			*/
			$this->From = $db_server_email;                           // FROM MAIL: (server mail)
	
			// set default mail formats
			$this->IsHTML(false);                                        
			$this->WordWrap = 80;                                       
			$this->Timeout = 30;			
		}
		else  {
			// Fehler beim Einlesen der Einstellungen
			$this->setMailError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_mail_init_settings));
			return false;
		}				
	} // __construct()
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setMailError($error) {
    $this->mailError = $error;
  } // setMailError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getMailError() {
    return $this->mailError;
  } // getMailError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isMailError() {
    return (bool) !empty($this->mailError);
  } // isMailError

  /**
   * Reset Error to empty String
   */
  public function clearMailError() {
  	$this->mailError = '';
  } // clearMailError()

  /** 
    * Set $this->message to $message
    * 
    * @param STR $message
    */
  public function setMailMessage($message) {
    $this->mailMessage = $message;
  } // setMailMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMailMessage() {
    return $this->mailMessage;
  } // getMailMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMailMessage() {
    return (bool) !empty($this->mailMessage);
  } // isMailMessage
	
  /**
   * Mailroutine fuer KIT
   * 
   * @param STR $subject
   * @param STR $message
   * @param STR $from_email
   * @param STR $from_name
   * @param ARRAY $to_array
   * @param BOOL $is_html
   * @param ARRAY $cc_array
   * @param ARRAY $bcc_array
   * @param Array $attach_array
   * 
   * @return bool
   *  
   */
  public function mail($subject, $message, $from_email, $from_name, $to_array, $is_html=false, $cc_array=array(), $bcc_array=array(), $attach_array=array()) {
  	// Absender
  	$this->FromName = $from_name;
  	$this->From = $from_email;
  	$this->AddReplyTo($from_email);
  	
  	// offene TO Empfaenger
  	foreach ($to_array as $email => $name) {
  		$this->AddAddress($email, $name);
  	}
  	
  	// offene CC Empfaenger
  	foreach ($cc_array as $email => $name) {
  		$this->AddCC($email, $name);
  	}
  	
  	// VERSTECKTE BCC Empfaenger
  	foreach ($bcc_array as $email => $name) {
  		$this->AddBCC($email);
  	}
  	
  	// Attachments
  	foreach ($attach_array as $attachment) {
    	if (!$this->AddAttachment($attachment)) {
    	  $this->setMailError($this->ErrorInfo);
    	  return false;
    	}
    }

    $this->Subject = $subject;                     
    if ($is_html) {
    	$this->IsHTML(true);
    	$this->Body = $message;                      
    	$this->AltBody = strip_tags($message);				
    }
    else {
    	$this->IsHTML(false);
    	$this->Body = $message;                       
    }
		
    // check if there are any send mail errors, otherwise say successful
    if (!$this->Send()) {
    	$this->setMailError($this->ErrorInfo);
      return false;
    } 
    else {
      return true;
    }  	
  } // mail()  
  
  /**
   * Mailroutine fuer KIT
   * 
   * @param STR $subject
   * @param STR $html_message
   * @param STR $text_message
   * @param STR $from_email
   * @param STR $from_name
   * @param STR $to_email
   * @param STR $to_name
   * 
   * @return bool
   *  
   */
  public function sendNewsletter($subject, $html_message, $text_message, $from_email, $from_name, $to_email, $to_name, $is_html=true) {
  	// Absender
  	$this->FromName = $from_name;
  	$this->From = $from_email;
  	$this->AddReplyTo($from_email);
  	
 		$this->AddAddress($to_email, $to_name);

    $this->Subject = $subject;                     

    if ($is_html) {
    	$this->IsHTML(true);
    	$this->Body = $html_message;                      
    	$this->AltBody = $text_message;
    }
    else {
    	$this->IsHTML(false);
    	$this->Body = $text_message;				
    }
		
    // check if there are any send mail errors, otherwise say successful
    if (!$this->Send()) {
    	$this->setMailError($this->ErrorInfo);
      return false;
    } 
    else {
      return true;
    }  	
  } // sendNewsletter()  
  
  
} // class kitMail

class dbKITmail extends dbConnectLE {
	
	const field_id					= 'mail_id';
	const field_is_html			= 'mail_is_html';
	const field_subject			= 'mail_subject';
	const field_from_name		= 'mail_from_name';
	const field_from_email	= 'mail_from_email';
	const field_to_array		= 'mail_to_array';
	const field_cc_array		= 'mail_cc_array';
	const field_bcc_array		= 'mail_bcc_array';
	const field_html				= 'mail_html';
	const field_text				= 'mail_text';
	const field_error				= 'mail_error';
	const field_status			= 'mail_status';
	const field_update_by		= 'mail_update_by';
	const field_update_when	= 'mail_update_when';
	
	const status_active			= 'statusActive';
	const status_locked			= 'statusLocked';
	const status_deleted		= 'statusDeleted'; 
	
	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);
	
	private $create_tables = false;
	
	function __construct($create_tables = false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_mail');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_is_html, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_subject, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_from_name, "VARCHAR(60) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_from_email, "VARCHAR(60) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_to_array, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_cc_array, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_bcc_array, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_html, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_text, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_error, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}		
	} // __construct()
	
	public function __destruct() {
 		@$this->close();
  } // __destruct()
  
  private function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
	} // initTables()
	
} // class dbKITmail

?>