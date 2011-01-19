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

// load basic files
require_once(WB_PATH.'/modules/kit/initialize.php');
require_once(WB_PATH.'/modules/kit/class.request.php');
require_once(WB_PATH.'/modules/kit/class.newsletter.link.php');
require_once(WB_PATH.'/modules/kit/class.newsletter.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// include language file for the dialog
if(!file_exists(WB_PATH .'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH .'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); 
}

global $dbNewsletterLinks;
global $dbNewsletterArchive;
global $dbProvider;

if (!is_object($dbNewsletterLinks)) $dbNewsletterLinks = new dbKITnewsletterLinks();
if (!is_object($dbNewsletterArchive)) $dbNewsletterArchive = new dbKITnewsletterArchive();
if (!is_object($dbProvider)) $dbProvider = new dbKITprovider();

class dialog_unsubscribe extends kitDialogFrame  {

	const request_action		= 'unsub_act';
	
	const action_default		= 'def';
	const action_check_dist	= 'chk_dist';
	const action_check_nl		= 'chk_nl';
	const action_abort			= 'abort';
	
	function __construct($silent=false) {
		parent::__construct($silent);
		// set template and language path
		$this->templatePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/htt/';
		$this->languagePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/';
	} // __construct()
	
	public function action() {
		// Important: get $_REQUEST vars from $_SESSION...
  	foreach ($_SESSION as $key => $value) {
  		if (strpos($key, KIT_SESSION_ID) !== false) {
  			$new_key = str_replace(KIT_SESSION_ID, '', $key);
  			$_REQUEST[$new_key] = $value;
  			unset($_SESSION[$key]);
  		}
  	}
		
		$html_allowed = array();
		// prevent XSS Cross Site Scripting
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
		isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
		$result = '';
		
		switch ($action):
		case self::action_check_dist:
			// Keine weiteren E-Mails ueber den Verteiler mehr erwuenscht
			$result = $this->checkDistribution();
			break;
		case self::action_check_nl:
			// Abmeldung aus dem Newsletter Verteiler
			$result = $this->checkNewsletter();
			break;
		case self::action_abort:
			// Abmeldung abgebrochen
			$result = sprintf('<div class="kit_unsub_dlg">%s</div>', kit_dialog_unsub_text_abort);
			break;
		case self::action_default:
		default:		
			// Abmeldedialog anzeigen
			$result = $this->showDlg();
			break;
		endswitch;
		
		// AUSGABE
  	if ($this->isError()) $result = sprintf('<div class="kit_unsub_error"><h1>%s</h1><p>%s</p></div>', kit_header_error, $this->getError());
  	if ($this->silent) {
  		// stille Ausgabe fuer Droplet etc. ...
  		return $result;
  	}
  	else {
  		echo $result;
  	}
	} // action()
	
	/**
	 * Abmeldung aus dem Verteiler erwuenscht
	 */
	public function checkDistribution() {
		global $dbNewsletterLinks;
		global $dbContact;
		global $dbNewsletterArchive;
		global $dbProvider;
		
		// E-Mail an den Provider, damit dieser den Abonnenten aus dem Verteiler entfernt
		$link_value = $_REQUEST[dbKITnewsletterLinks::field_link_value];
		$where = array(
			dbKITnewsletterLinks::field_link_value => $link_value
		);
		$link = array();
		if (!$dbNewsletterLinks->sqlSelectRecord($where, $link)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		$link = $link[0];
		// Newsletter Archiv auslesen
		$where = array(
			dbKITnewsletterArchive::field_id => $link[dbKITnewsletterLinks::field_archive_id]
		);
		$archiv = array();
		if (!$dbNewsletterArchive->sqlSelectRecord($where, $archiv)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
			return false;
		}
		$archiv = $archiv[0];
		// provider ermitteln
		$where = array(
			dbKITprovider::field_id => $archiv[dbKITnewsletterArchive::field_provider]
		);
		$provider = array();
		if (!$dbProvider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			return false;
		}
		$provider = $provider[0];
		// Kontaktdaten
		$contact = array();
		if (!$dbContact->getContactByID($link[dbKITnewsletterLinks::field_kit_id], $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		// E-Mail Kontakt
		if (false == ($mail_kontakt = $dbContact->getStandardEMailByID($link[dbKITnewsletterLinks::field_kit_id]))) {
			if ($dbContact->isError()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError));
				return false;
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_dialog_unsub_error_no_email, $link[dbKITnewsletterLinks::field_kit_id])));
				return false;
			}
		}
		
		// Kontakt Protokoll aktualisieren
		$dbContact->addSystemNotice($contact[dbKITcontact::field_id], kit_dialog_unsub_log_distribution);
		
		$subject = kit_dialog_unsub_mail_subject_dist;
		$name = '';
		if (!empty($contact[dbKITcontact::field_person_first_name])) $name = $contact[dbKITcontact::field_person_first_name].' ';
		if (!empty($contact[dbKITcontact::field_person_last_name])) $name .= $contact[dbKITcontact::field_person_last_name];
		if (empty($name)) $name = kit_dialog_unsub_text_unknown_user;
		$message = sprintf(kit_dialog_unsub_mail_text_dist, $name, $contact[dbKITcontact::field_id], $mail_kontakt);
		$from_mail = $mail_kontakt;
		$from_name = ($name !== kit_dialog_unsub_text_unknown_user) ? $name : $mail_kontakt;
		$to_array = array($provider[dbKITprovider::field_email] => $provider[dbKITprovider::field_name]);
		$mail = new kitMail($provider[dbKITprovider::field_id]);
		if (!$mail->mail($subject, $message, $from_mail, $from_name, $to_array, true)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $mail->getMailError()));
			return false;
		}
		return kit_dialog_unsub_text_dist_confirm;
	} // checkDistribution()
	
	public function checkNewsletter() {
		global $dbNewsletterLinks;
		global $dbContact;
		global $dbNewsletterArchive;
		global $dbProvider;
		
		// E-Mail an den Provider, damit dieser den Abonnenten aus dem Verteiler entfernt
		$link_value = $_REQUEST[dbKITnewsletterLinks::field_link_value];
		$where = array(
			dbKITnewsletterLinks::field_link_value => $link_value
		);
		$link = array();
		if (!$dbNewsletterLinks->sqlSelectRecord($where, $link)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		$link = $link[0];
		// Newsletter Archiv auslesen
		$where = array(
			dbKITnewsletterArchive::field_id => $link[dbKITnewsletterLinks::field_archive_id]
		);
		$archiv = array();
		if (!$dbNewsletterArchive->sqlSelectRecord($where, $archiv)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
			return false;
		}
		$archiv = $archiv[0];
		// provider ermitteln
		$where = array(
			dbKITprovider::field_id => $archiv[dbKITnewsletterArchive::field_provider]
		);
		$provider = array();
		if (!$dbProvider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			return false;
		}
		$provider = $provider[0];
		// Kontaktdaten
		$contact = array();
		if (!$dbContact->getContactByID($link[dbKITnewsletterLinks::field_kit_id], $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		// E-Mail Kontakt
		if (false == ($mail_kontakt = $dbContact->getStandardEMailByID($link[dbKITnewsletterLinks::field_kit_id]))) {
			if ($dbContact->isError()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError));
				return false;
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_dialog_unsub_error_no_email, $link[dbKITnewsletterLinks::field_kit_id])));
				return false;
			}
		}
		
		// aus Newsletter austragen
		$contact_newsletter = explode(',', $contact[dbKITcontact::field_newsletter]);
		$mail_newsletter = explode(',', $link[dbKITnewsletterLinks::field_newsletter_grps]);
		$abonnement = array();
		$nl_array = array();
		foreach ($contact_newsletter as $news) {
			if (in_array($news, $mail_newsletter)) {
				$nl_array[] = $news;
				$abonnement[] = $dbContact->newsletter_array[$news];
			}
		}
		$letters = implode(', ', $abonnement);
		foreach ($mail_newsletter as $news) {
			if (in_array($news, $contact_newsletter)) unset($contact_newsletter[$news]);
		}
		
		// Kontakt Datenbank aktualisieren und Newsletter austragen
		$where = array(
			dbKITcontact::field_id => $contact[dbKITcontact::field_id]
		);
		$data = array(
			dbKITcontact::field_newsletter 	=> implode(',', $contact_newsletter),
			dbKITcontact::field_update_by		=> 'SYSTEM',
			dbKITcontact::field_update_when	=> date('Y-m-d H:i:s')
		);
		if (!$dbContact->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		// Kontakt Protokoll aktualisieren
		$dbContact->addSystemNotice($contact[dbKITcontact::field_id], sprintf(kit_dialog_unsub_log_newsletter, $letters));
		
		$subject = kit_dialog_unsub_mail_subject_nl;
		$name = '';
		if (!empty($contact[dbKITcontact::field_person_first_name])) $name = $contact[dbKITcontact::field_person_first_name].' ';
		if (!empty($contact[dbKITcontact::field_person_last_name])) $name .= $contact[dbKITcontact::field_person_last_name];
		if (empty($name)) $name = kit_dialog_unsub_text_unknown_user;
		$message = sprintf(kit_dialog_unsub_mail_text_nl, $name, $contact[dbKITcontact::field_id], $mail_kontakt, $letters);
		$from_mail = $mail_kontakt;
		$from_name = ($name !== kit_dialog_unsub_text_unknown_user) ? $name : $mail_kontakt;
		$to_array = array($provider[dbKITprovider::field_email] => $provider[dbKITprovider::field_name]);
		$mail = new kitMail($provider[dbKITprovider::field_id]);
		if (!$mail->mail($subject, $message, $from_mail, $from_name, $to_array, true)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $mail->getMailError()));
			return false;
		}
		return sprintf(kit_dialog_unsub_text_nl_confirm, $letters);
	} // checkNewsletter()
	
	public function showDlg() {
		global $dbNewsletterLinks;
		global $dbContact;
		global $parser;
		global $dbNewsletterArchive;
		global $dbCfg;
		global $dbProvider;
		
		if (!isset($_REQUEST[kitRequest::request_link])) {
			$this->setError(kit_error_request_link_invalid);
			return false;
		}
		$link_value = $_REQUEST[kitRequest::request_link];
		$where = array(
			dbKITnewsletterLinks::field_link_value => $link_value
		);
		$link = array();
		if (!$dbNewsletterLinks->sqlSelectRecord($where, $link)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		if (count($link) < 1) {
			$this->setError(sprintf(kit_error_request_link_unknown, $link_value));
			return false;
		}
		$link = $link[0];
		if ($link[dbKITnewsletterLinks::field_type] != dbKITnewsletterLinks::type_link_unsubscribe) {
			// falsche Anforderung
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_request_link_type, $dbNewsletterLinks->type_array[$link[dbKITnewsletterLinks::field_type]])));
			return false;
		}
		// Newsletter Archiv auslesen
		$where = array(
			dbKITnewsletterArchive::field_id => $link[dbKITnewsletterLinks::field_archive_id]
		);
		$archiv = array();
		if (!$dbNewsletterArchive->sqlSelectRecord($where, $archiv)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
			return false;
		}
		if (count($archiv) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $link[dbKITnewsletterLinks::field_archive_id])));
			return false;
		}
		$archiv = $archiv[0];
		// provider ermitteln
		$where = array(
			dbKITprovider::field_id => $archiv[dbKITnewsletterArchive::field_provider]
		);
		$provider = array();
		if (!$dbProvider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			return false;
		}
		if (count($provider) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $archiv[dbKITnewsletterArchive::field_id])));
			return false;
		}
		$provider = $provider[0];
		
		$contact = array();
		if (!$dbContact->getContactByID($link[dbKITnewsletterLinks::field_kit_id], $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		
		if (false == ($mail = $dbContact->getStandardEMailByID($link[dbKITnewsletterLinks::field_kit_id]))) {
			if ($dbContact->isError()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError));
				return false;
			}
			else {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_dialog_unsub_error_no_email, $link[dbKITnewsletterLinks::field_kit_id])));
				return false;
			}
		}
		
		if (empty($link[dbKITnewsletterLinks::field_newsletter_grps]) || empty($contact[dbKITcontact::field_newsletter])) {
			// Mailing ging NICHT an Newsletter Empfaenger
			$text = sprintf(kit_dialog_unsub_text_distribution, $mail, $provider[dbKITprovider::field_email]);
			$action = self::action_check_dist;
		}
		else {
			// aus Newsletter austragen
			$contact_newsletter = explode(',', $contact[dbKITcontact::field_newsletter]);
			$mail_newsletter = explode(',', $link[dbKITnewsletterLinks::field_newsletter_grps]);
			$abonnement = array();
			foreach ($contact_newsletter as $news) {
				if (in_array($news, $mail_newsletter)) {
					$abonnement[] = $dbContact->newsletter_array[$news];
				}
			}
			$letters = implode(', ', $abonnement);
			if (empty($letters)) {
				$text = sprintf(kit_dialog_unsub_text_distribution, $mail, $provider[dbKITprovider::field_email]);
				$action = self::action_check_dist;
			}
			else {
				(count($abonnement) > 1) ? $str = kit_dialog_unsub_text_newsletter_multi : $str = kit_dialog_unsub_text_newsletter_single; 
				$text = sprintf($str, $mail, $letters);
				$action = self::action_check_nl;
			}	 
		}
		
		// Zaehler hochsetzen
		$where = array(
			dbKITnewsletterLinks::field_id => $link[dbKITnewsletterLinks::field_id]
		);
		$data = array(
			dbKITnewsletterLinks::field_count => $link[dbKITnewsletterLinks::field_count]+1
		);
		if (!$dbNewsletterLinks->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		
		// Template
		$data = array(
			'form_action'			=> $this->getDlgLink(),
			'action_name'			=> self::request_action,
			'action_value'		=> $action,
			'link_name'				=> dbKITnewsletterLinks::field_link_value,
			'link_value'			=> $link_value,
			'text'						=> $text,
			'btn_yes'					=> kit_btn_yes,
			'btn_no'					=> kit_btn_no,
			'abort_location'	=> sprintf('%s&%s=%s', $this->getDlgLink(), self::request_action, self::action_abort)
		);
		return $parser->get($this->templatePath.'unsubscribe.htt', $data);
	} // showDlg()
	
} // class dialog_unsubscribe

?>