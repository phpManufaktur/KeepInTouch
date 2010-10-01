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

class dialog_account extends kitDialogFrame {
	
	const request_action 				= 'acc_act';
	
	const action_check_login		= 'cl';
	const action_default				= 'def';
	const action_need_password	= 'np';
	const action_send_password	= 'sp';
	const action_check_account	= 'ca';
	const action_logout					= 'out';
	
	const session_kit_aid			= 'kit_aid';
	const session_kit_key			= 'kit_key';
	
	private $password					= '';
	
	function __construct($silent=false) {
		parent::__construct($silent);		
		// set template and language path
		$this->templatePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/htt/';
		$this->languagePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/';
	} // __construct()
	
	function action() { 
		$html_allowed = array();
		// prevent XSS Cross Site Scripting
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
  	// wurde ueber KIT eine Aktion angefordert?
  	isset($_REQUEST[kitRequest::request_action]) ? $action = $_REQUEST[kitRequest::request_action] : $action = kitRequest::action_none;
  	
  	if (($action == kitRequest::action_error) ||
  			($action == kitRequest::action_activate_key) ||
  			($action == kitRequest::action_unsubscribe) )
  	{
  		// KEIN LOGIN ERFORDERLICH
  		unset($_SESSION[self::session_kit_aid]);
  		unset($_SESSION[self::session_kit_key]);	
				
  		switch ($action):
  		case kitRequest::action_activate_key:
  			if ($this->checkActivationKey()) {
					$result = $this->dlgAccount();
				}
				elseif ($this->isMessage()) {
					$result = $this->dlgLogin();
				}
				break;		
  		case kitRequest::action_unsubscribe:
  			if ($this->checkUnsubscribeKey()) {
  				$result = $this->dlgLogin();
  			}
  			break;
  		default:
  			$this->setError(sprintf(kit_dialog_acc_error_no_action_defined, $action));
  			break;
  		endswitch;		
  	}
  	else {
  		// LOGIN ERFORDERLICH
  		$authenticated = false;
  		isset ($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default; 
  		// $_SESSION gesetzt?
  		if (isset($_SESSION[self::session_kit_aid]) && isset($_SESSION[self::session_kit_key])) $authenticated = true;
  		
  		if ($action == self::action_check_login && !$authenticated) {
  			// LOGIN PRUEFEN
  			if ($this->checkLogin()) $authenticated = true;
  		}
  		
  		if (!$authenticated) {
  			// Benutzer ist NICHT EINGELOGGT
  			switch ($action):
  			case self::action_need_password:
  				// Neues Passwort anfordern
  				$result = $this->dlgNeedPassword();
  				break;
  			case self::action_send_password:
  				// Passwort versenden
  				if (!$this->sendPassword()) {
  					// Fehler, Dialog noch mal anzeigen
  					if (!$this->isError()) $result = $this->dlgNeedPassword();
  				}
  				else {
  					// Passwort verschickt, Login Dialog anzeigen
  					$result = $this->dlgLogin();
  				}
  				break;
  			default:
  				// LOGIN DIALOG ANZEIGEN
  				$result = $this->dlgLogin();
  			endswitch;  			
  		}
  		else {
  			// USER IST EINGELOGGT
  			switch ($action):
  			case self::action_logout:
  				unset($_SESSION[self::session_kit_aid]);
  				unset($_SESSION[self::session_kit_key]);
  				$result = $this->dlgLogin();
  				break;
  			case self::action_check_account:
  				$this->checkAccount();
  				if (!$this->isError()) $result = $this->dlgAccount();
  				break;
  			default:
  				$result = $this->dlgAccount();
  				break;
  			endswitch;
  		}	
  	}		  		
  	
		// AUSGABE
  	if ($this->isError()) $result = sprintf('<div class="kit_acc_error"><h1>%s</h1><p>%s</p></div>', kit_header_error, $this->getError());
  	if ($this->silent) {
  		// stille Ausgabe fuer Droplet etc. ...
  		return $result;
  	}
  	else {
  		echo $result;
  	}
	} // action()
	
	
	public function checkUnsubscribeKey() {
		if (!isset($_REQUEST[kitRequest::request_key]) || !isset($_REQUEST[kitRequest::request_newsletter])) {
			$this->setMessage(kit_dialog_acc_unsubscribe_invalid);
			return false;
		}
		$dbAccount = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_register_key] = $_REQUEST[kitRequest::request_key];
		$account = array();
		if (!$dbAccount->sqlSelectRecord($where, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		if (count($account) < 1) {
			$this->setMessage(kit_dialog_acc_key_invalid);
			return false;
		}
		$account = $account[0];
		
		// aktuell registrierte Newsletter in Array einlesen
		$old_newsletter_array = explode(',', $account[dbKITregister::field_newsletter]);
		$new_newsletter_array = array();
		$unsub_newsletter_array = explode(',', $_REQUEST[kitRequest::request_newsletter]);
		foreach ($old_newsletter_array as $item) {
			if (!in_array($item, $unsub_newsletter_array)) $new_newsletter_array[] = $item;
		}
		$new_newsletter = implode(',', $new_newsletter_array);
		$data = array();
		$data[dbKITregister::field_newsletter] = $new_newsletter;
		$data[dbKITregister::field_update_by] = $account[dbKITregister::field_email];
		$data[dbKITregister::field_update_when] = date('y-m-d H:i:s');
		if (!$dbAccount->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		
		$dbContact = new dbKITcontact();
		$where = array();
		$where[dbKITcontact::field_id] = $account[dbKITregister::field_contact_id];
		$data = array();
		$data[dbKITcontact::field_newsletter] = $new_newsletter;
		$data[dbKITcontact::field_update_by] = $account[dbKITregister::field_email];
		$data[dbKITcontact::field_update_when] = date('y-m-d H:i:s');
		if (!$dbContact->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		$dbContact->addSystemNotice($account[dbKITregister::field_contact_id], kit_dialog_acc_log_account_update);
		
		$newsletter = '';
		foreach ($unsub_newsletter_array as $item) {
			if ($newsletter != '') $newsletter .= ', ';
			$newsletter .= $dbContact->newsletter_array[$item];
		}
		
		$this->setMessage(sprintf(kit_dialog_acc_unsubscribe_success, $newsletter));
		return true;
	} // checkUnsubscribeKey()
	
	/**
	 * Prueft Eingaben und aktualisiert die Datensaetze
	 * 
	 * @return BOOL
	 */
	public function checkAccount() { 
		$tools = new kitTools();
		if (empty($_REQUEST[dbKITregister::field_email]) || 
				!$tools->validateEMail($_REQUEST[dbKITregister::field_email])) {
			$this->setMessage(kit_dialog_acc_mail_invalid);
			return false;
		}
		if (!isset($_REQUEST[dbKITregister::field_newsletter])) {
			$newsletter = '';
		}
		else {
			$newsletter = implode(',', $_REQUEST[dbKITregister::field_newsletter]);
		}
		$dbAccount = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_id] = $_SESSION[self::session_kit_aid];
		$account = array();
		if (!$dbAccount->sqlSelectRecord($where, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		$account = $account[0];
		
		$dbContact = new dbKITcontact();
		$where = array();
		$where[dbKITcontact::field_id] = $account[dbKITregister::field_contact_id];
		$contact = array();
		if (!$dbContact->sqlSelectRecord($where, $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		if (count($contact) < 1) {
			$this->setError(sprintf(kit_dialog_acc_error_contact_id, $account[dbKITregister::field_id], $account[dbKITregister::field_contact_id]));
			return false;
		}
		$contact = $contact[0];
		
		$data_account = array();
		$data_contact = array();
		if ($account[dbKitregister::field_email] != $_REQUEST[dbKITregister::field_email]) {
			$data_account[dbKITregister::field_email] = $_REQUEST[dbKITregister::field_email];
		}
		if ($account[dbKITregister::field_newsletter] != $newsletter) {
			$data_account[dbKITregister::field_newsletter] = $newsletter;
			$data_contact[dbKITcontact::field_newsletter] = $newsletter;
		}
		if ($contact[dbKITcontact::field_person_title] != $_REQUEST[dbKITcontact::field_person_title]) {
			$data_contact[dbKITcontact::field_person_title] = $_REQUEST[dbKITcontact::field_person_title];
		}
		if ($contact[dbKITcontact::field_person_first_name] != $_REQUEST[dbKITcontact::field_person_first_name]) {
			$data_contact[dbKITcontact::field_person_first_name] = $_REQUEST[dbKITcontact::field_person_first_name];
		}
		if ($contact[dbKITcontact::field_person_last_name] != $_REQUEST[dbKITcontact::field_person_last_name]) {
			$data_contact[dbKITcontact::field_person_last_name] = $_REQUEST[dbKITcontact::field_person_last_name];
		}
		$update = false;
		if (count($data_account) > 0) {
			$where = array();
			$where[dbKITregister::field_id] = $account[dbKITregister::field_id];
			if (!$dbAccount->sqlUpdateRecord($data_account, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
			}
			$update = true;
		}
		if (count($data_contact) > 0) {
			$where = array();
			$where[dbKITcontact::field_id] = $account[dbKITregister::field_contact_id];
			if (!$dbContact->sqlUpdateRecord($data_contact, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			$update = true;
		}
		if ($update) {
			$dbContact->addSystemNotice($account[dbKITregister::field_contact_id], kit_dialog_acc_log_account_update);
			$this->setMessage(kit_dialog_acc_account_update_success);
		}
		else {
			$this->setMessage(kit_dialog_acc_account_update_skipped);
		}
		return true;
	} // checkAccount()
	
	public function dlgAccount() {
		// Account auslesen
		$dbAccount = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_id] = $_SESSION[self::session_kit_aid];
		$account = array();
		if (!$dbAccount->sqlSelectRecord($where, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		if (count($account) < 1) {
			$this->setError(sprintf(kit_dialog_acc_error_account_id, $_SESSION[self::session_kit_aid]));
			return false;
		}
		$account = $account[0];
		// Kontaktdaten auslesen
		$dbContact = new dbKITcontact();
		$where = array();
		$where[dbKITcontact::field_id] = $account[dbKITregister::field_contact_id];
		$contact = array();
		if (!$dbContact->sqlSelectRecord($where, $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		if (count($contact) < 1) {
			$this->setError(sprintf(kit_dialog_acc_error_contact_id, $account[dbKITregister::field_id], $account[dbKITregister::field_contact_id]));
			return false;
		}
		$contact = $contact[0];
		// person title
  	$select = '';
  	foreach ($dbContact->person_title_array as $value => $name) {
  		($value == $contact[dbKITcontact::field_person_title]) ? $selected = ' selected="selected"' : $selected = '';
			$select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
  	}
  	$title_value = sprintf('<select name="%s">%s</select>', dbKITcontact::field_person_title, $select);
		
  	// newsletter
  	$newsletter = '';
  	$news_array = explode(',', $account[dbKITregister::field_newsletter]);
  	foreach ($dbContact->newsletter_array as $key => $value) {
			(in_array($key, $news_array)) ? $checked=' checked="checked"' : $checked = '';
			$newsletter .= sprintf(	'<input type="checkbox" name="%s[]" value="%s"%s />&nbsp;%s<br />',
															dbKITregister::field_newsletter,
															$key,
															$checked,
															$value);
		}
		
		
		$template = new Dwoo();
		$data = array(
			'form_action'				=> $this->getDlgLink(),
			'action_name'				=> self::request_action,
			'action_value'			=> self::action_check_account,
			'logout'						=> sprintf(	'<a href="%s&%s=%s">%s</a>', 
																			$this->getDlgLink(), 
																			self::request_action, 
																			self::action_logout,
																			kit_dialog_acc_label_logout),
			'intro'							=> $this->isMessage() ? $this->getMessage() : kit_dialog_acc_intro_account,
			'title_label'				=> kit_label_person_title,
			'title_value'				=> $title_value,
			'firstname_label'		=> kit_label_person_first_name,
			'firstname_name'		=> dbKITcontact::field_person_first_name,
			'firstname_value'		=> $contact[dbKITcontact::field_person_first_name],
			'lastname_label'		=> kit_label_person_last_name,
			'lastname_name'			=> dbKITcontact::field_person_last_name,
			'lastname_value'		=> $contact[dbKITcontact::field_person_last_name],
			'email_label'				=> kit_label_contact_email,
			'email_name'				=> dbKITregister::field_email,
			'email_value'				=> $account[dbKITregister::field_email],
			'newsletter_label'	=> kit_label_newsletter,
			'newsletter_value'	=> $newsletter,
			'btn_submit'				=> kit_btn_ok
		);
		return $template->get($this->getTemplateFile('account.data.htt'), $data);
	} // dlgAccount()
	
	/**
	 * E-Mail Adresse pruefen, neues Passwort erzeugen und zusenden
	 */
	public function sendPassword() {
		if (empty($_REQUEST[dbKITregister::field_email])) {
			$this->setMessage(kit_dialog_acc_mail_invalid);
			return false;
		}
		$tools = new kitTools();
		if (!$tools->validateEMail($_REQUEST[dbKITregister::field_email])) {
			$this->setMessage(kit_dialog_acc_mail_invalid); 
			return false;
		}
		// Captcha pruefen
		if (!isset($_REQUEST['captcha'])) {
			$this->setMessage(kit_dialog_acc_captcha_invalid);
			return false;
		}
		if ($this->useCaptcha()) {
			if ($_REQUEST['captcha'] != $_SESSION['captcha']) {
				$this->setMessage(kit_dialog_acc_captcha_invalid);
				return false;
			}
		}
		$dbAccount = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_email] = $_REQUEST[dbKITregister::field_email];
		$where[dbKITregister::field_status] = dbKITregister::status_active;
		$account = array();
		if (!$dbAccount->sqlSelectRecord($where, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		if (count($account) < 1) {
			// Adresse nicht gefunden...
			$this->setMessage(sprintf(kit_dialog_acc_mail_not_exists, $_REQUEST[dbKITregister::field_email]));
			return false;
		}
		else {
			// Passwort zusenden...
			$account = $account[0];
			$to_array = array($account[dbKITregister::field_email] => '');
			// load PHPMailer
			require_once(WB_PATH.'/modules/kit/class.mail.php');
		
			// Provider auslesen
			$dbKITprovider = new dbKITprovider();
			$where = array();
			$where[dbKITprovider::field_status] = dbKITprovider::status_active;
			$provider = array();
			if (!$dbKITprovider->sqlSelectRecord($where, $provider)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITprovider->getError()));
				return false;  	
			}
			if (count($provider) < 1) {
				$this->setError(kit_dialog_nl_error_no_provider);
				return false;
			}
			// ersten Datensatz auswaehlen
			$provider = $provider[0];
		
			// neues Passwort erstellen
			$this->password = $tools->generatePassword();

			$request = new kitRequest();
			$request_link = $request->getRequestLink();
						
			// Nachricht erstellen
			$template = new Dwoo();
			$data = array(
				'website'						=> WEBSITE_TITLE,
				'login_url'					=> $request_link.'&'.kitRequest::request_action.'='.kitRequest::action_login,
				'email'							=> $account[dbKITregister::field_email],
				'password'					=> $this->password
			);
			$message = $template->get($this->getTemplateFile(LANGUAGE.'.mail.password.htt'), $data);
			$mail = new kitMail();
			if (!$mail->mail(kit_dialog_acc_mail_subject_password, $message, $provider[dbKITprovider::field_email], $provider[dbKITprovider::field_name], $to_array, true)) {
				// Fehler beim Versenden der E-Mail
				$this->setError(sprintf(kit_dialog_acc_error_mail, $account[dbKITregister::field_email], $mail->getMailError()));
				return false;
			}
			// Account aktualisieren
			$where = array();
			$where[dbKITregister::field_id] = $account[dbKITregister::field_id];
			$data = array();
			$data[dbKITregister::field_password] = md5($this->password);
			$data[dbKITregister::field_update_by] = $account[dbKITregister::field_email];
			$data[dbKITregister::field_update_when] = date('y-m-d H:i:s');
			if (!$dbAccount->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
				return false;
			}
			// Systemlog aktualisieren
			$dbContact = new dbKITcontact();
			$dbContact->addSystemNotice($account[dbKITregister::field_contact_id], kit_dialog_acc_log_password_send);
			// Meldung ausgeben
			$this->setMessage(sprintf(kit_dialog_acc_password_send, $account[dbKITregister::field_email]));
			return true;
		}
	} // sendPassword()
	
	/**
	 * Dialog zum Anfordern eines neuen Passworts
	 */
	public function dlgNeedPassword() {
		if ($this->useCaptcha()) {
			$captcha = sprintf('<div class="kit_captcha">%s&nbsp;%s</div>', kit_label_checksum, $this->getCaptcha());
		}
		else {
			$captcha = '';
		}
		$template = new Dwoo();
		$data = array(
			'form_action'					=> $this->getDlgLink(),
			'action_name'					=> self::request_action,
			'action_value'				=> self::action_send_password,
			'intro'								=> $this->isMessage() ? $this->getMessage() : kit_dialog_acc_intro_password_needed,
			'label_username'			=> kit_label_contact_email,
			'username_name'				=> dbKITregister::field_email,
			'username_value'			=> isset($_REQUEST[dbKITregister::field_email]) ? $_REQUEST[dbKITregister::field_email] : '',
			'captcha'							=> $captcha,
			'label_submit'				=> '',
			'btn_submit'					=> kit_btn_send
		);
		return $template->get($this->getTemplateFile('account.password.needed.htt'), $data);
	} // dlgNeedPassword()
	
	/**
	 * Ueberprueft den LOGIN und schaltet den Zugang frei
	 */
	public function checkLogin() {
		if (empty($_REQUEST[dbKITregister::field_email]) || empty($_REQUEST[dbKITregister::field_password])) {
			$this->setMessage(kit_dialog_acc_login_incomplete);
			return false;
		}
		$dbAccount = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_email] = $_REQUEST[dbKITregister::field_email];
		$where[dbKITregister::field_status] = dbKITregister::status_active;
		$account = array();
		if (!$dbAccount->sqlSelectRecord($where, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		if (count($account) > 0) {
			// Benutzer existiert
			$account = $account[0];
			$config = new dbKITcfg();
			$max_login = $config->getValue(dbKITcfg::cfgMaxInvalidLogin);
			if ($account[dbKITregister::field_login_locked] == 1) {
				// zuviele Fehlversuche, Konto ist gesperrt
				$this->setMessage(kit_dialog_acc_login_locked);
				return false;
			}
			if (md5($_REQUEST[dbKITregister::field_password]) == $account[dbKITregister::field_password]) {
				// OK - LOGIN erfolgreich
				$_SESSION[self::session_kit_aid] = $account[dbKITregister::field_id];
				$_SESSION[self::session_kit_key] = $account[dbKITregister::field_register_key];
				return true;
			}
			else {
				// Passwort stimmt nicht, Zaehler fuer Fehlversuche hochsetzen...
				$where = array();
				$where[dbKITregister::field_id] = $account[dbKITregister::field_id];
				$data = array();
				$data[dbKITregister::field_login_failures] = $account[dbKITregister::field_login_failures]+1;
				if ($data[dbKITregister::field_login_failures] > $max_login) {
					// Konto sperren
					$data[dbKITregister::field_login_locked] = 1;
					// Systemlog aktualisieren
					$dbContact = new dbKITcontact();
					$dbContact->addSystemNotice($account[dbKITregister::field_contact_id], kit_dialog_acc_log_login_locked);
				}
				$data[dbKITregister::field_update_by] = 'SYSTEM';
				$data[dbKITregister::field_update_when] = date('y-m-d H:i:s');
				if (!$dbAccount->sqlUpdateRecord($data, $where)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
					return false;
				}
				$this->setMessage(kit_dialog_acc_login_invalid);
				return false;
			}
		}
		else {
			// Benutzer existiert nicht
			$this->setMessage(kit_dialog_acc_login_invalid);
			return false;
		}
	} // checkLogin()
	
	/**
	 * Prueft den Aktivierungskey und schaltet das Benutzerkonto frei
	 */
	public function checkActivationKey() {
		if (!isset($_REQUEST[kitRequest::request_key]) || (empty($_REQUEST[kitRequest::request_key]))) {
			$this->setMessage(kit_dialog_acc_key_invalid);
			return false;
		}
		$dbAccount = new dbKITregister();
		$SQL = sprintf(	"SELECT UNIX_TIMESTAMP(%s), %s, %s, %s, %s, %s, %s FROM %s WHERE %s='%s'",
										dbKITregister::field_register_confirmed,
										dbKITregister::field_status,
										dbKITregister::field_id,
										dbKITregister::field_email,
										dbKITregister::field_contact_id,
										dbKITregister::field_newsletter,
										dbKITregister::field_register_key,
										$dbAccount->getTableName(),
										dbKITregister::field_register_key,
										$_REQUEST[kitRequest::request_key]);
		$account = array();
		if (!$dbAccount->sqlExec($SQL, $account)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		if (count($account) < 1) {
			$this->setError(kit_reg_data_error_invalid_key);
			return false;
		}
		$account = $account[0]; 
		/*
		if (//($account['UNIX_TIMESTAMP('.dbKITregister::field_register_confirmed.')'] > 0) ||
				($account[dbKITregister::field_status] != dbKITregister::status_key_send)) {
			// Schluessel ist abgelaufen, Login Dialog anzeigen
			$this->setMessage(kit_dialog_acc_key_no_longer_valid, $account[dbKITregister::field_email]);
			return false;		
		}
		// ok - Benutzerkonto freischalten
		*/
		// Pruefen, ob ein neuer KONTAKT angelegt werden muss
		$dbContact = new dbKITcontact();
		if ($account[dbKITregister::field_contact_id] < 1) {
			// neues Benutzerkonto anlegen
			$contact = array();
			$contact[dbKITcontact::field_email] = sprintf('%s|%s', dbKITcontact::email_private, $account[dbKITregister::field_email]);
			$contact[dbKITcontact::field_email_standard] = 0;
			$contact[dbKITcontact::field_newsletter] = $account[dbKITregister::field_newsletter];
			$contact[dbKITcontact::field_contact_identifier] = $account[dbKITregister::field_email];
			$contact[dbKITcontact::field_status] = dbKITcontact::status_active;
			$contact[dbKITcontact::field_access] = dbKITcontact::access_internal;
			$contact[dbKITcontact::field_type] = dbKITcontact::type_person;
			$contact[dbKITcontact::field_update_by] = $account[dbKITregister::field_email];
			$contact[dbKITcontact::field_update_when] = date('y-m-d H:i:s');
			$contact_id = -1;
			if (!$dbContact->sqlInsertRecord($contact, $contact_id)) {
				$this->setError(sprintf('{%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			// 
			$dbContact->addSystemNotice($contact_id, kit_dialog_acc_log_contact_created);
			$dbContact->addSystemNotice($contact_id, sprintf(kit_dialog_acc_log_newsletter_registered, $account[dbKITregister::field_newsletter]));
		}
		else {
			// Benutzerkonto aktualisieren
			$contact_id = $account[dbKITregister::field_contact_id];
			$where = array();
			$where[dbKITcontact::field_id] = $contact_id;
			$contact = array();
			$contact[dbKITcontact::field_newsletter] = $account[dbKITregister::field_newsletter];
			$contact[dbKITcontact::field_update_by] = $account[dbKITregister::field_email];
			$contact[dbKITcontact::field_update_when] = date('y-m-d H:i:s');
			if (!$dbContact->sqlUpdateRecord($contact, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			$dbContact->addSystemNotice($contact_id, sprintf(kit_dialog_acc_log_newsletter_registered, $account[dbKITregister::field_newsletter]));
		}
		
		$tools = new kitTools();
		$this->password = $tools->generatePassword();
		
		$where = array();
		$where[dbKITregister::field_id] = $account[dbKITregister::field_id];
		$data = array();
		$data[dbKITregister::field_register_confirmed] = date('y-m-d H:i:s');
		$data[dbKITregister::field_password] = md5($this->password);
		$data[dbKITregister::field_status] = dbKITregister::status_active; 
		$data[dbKITregister::field_login_locked] = 0;
		$data[dbKITregister::field_contact_id] = $contact_id;
		if (!$dbAccount->sqlUpdateRecord($data, $where)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
			return false;
		}
		
		// Freischaltung bestaetigen und Passwort verschicken
		
		// load PHPMailer
		require_once(WB_PATH.'/modules/kit/class.mail.php');
		
		// Provider auslesen
		$dbKITprovider = new dbKITprovider();
		$where = array();
		$where[dbKITprovider::field_status] = dbKITprovider::status_active;
		$provider = array();
		if (!$dbKITprovider->sqlSelectRecord($where, $provider)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITprovider->getError()));
			return false;  	
		}
		if (count($provider) < 1) {
			$this->setError(kit_reg_data_provider_missing);
			return false;
		}
		$provider = $provider[0];
		$to_array = array($account[dbKITregister::field_email] => '');
		
		$request = new kitRequest();
		$request_link = $request->getRequestLink();
		
		// Nachricht erstellen
		$template = new Dwoo();
		$data = array(
			'website'						=> WEBSITE_TITLE,
			'login_url'					=> $request_link.'&'.kitRequest::request_action.'='.kitRequest::action_login,
			'email'							=> $account[dbKITregister::field_email],
			'password'					=> $this->password
		);
		$message = $template->get($this->getTemplateFile(LANGUAGE.'.mail.welcome.htt'), $data);
		$mail = new kitMail();
		if (!$mail->mail(kit_dialog_acc_mail_subject_welcome, $message, $provider[dbKITprovider::field_email], $provider[dbKITprovider::field_name], $to_array, true)) {
			// Fehler beim Versenden der E-Mail
			$this->setError(sprintf(kit_dialog_acc_error_mail, $account[dbKITregister::field_email], $mail->getMailError()));
			return false;
		}
		// Benutzer anmelden
		$_SESSION[self::session_kit_aid] = $account[dbKITregister::field_id];
		$_SESSION[self::session_kit_key] = $account[dbKITregister::field_register_key];
		return true;
	} // checkActivationKey()
	
	public function dlgLogin() {
		if (isset($_SESSION[self::session_kit_aid]) && isset($_SESSION[self::session_kit_key]))  {
			// User ist bereits eingeloggt
			return $this->dlgAccount();
		}
		else {
			// ggf. $_SESSION zuruecksetzen
			unset($_SESSION[self::session_kit_key]);
			unset($_SESSION[self::session_kit_aid]);
		}
		isset($_REQUEST[kitRequest::request_key]) ? $account_key = $_REQUEST[kitRequest::request_key] : $account_key = '';
		isset($_REQUEST[kitRequest::request_account_id]) ? $account_id = $_REQUEST[kitRequest::request_account_id] : $account_id = -1;
		isset($_REQUEST[kitRequest::request_email]) ? $account_username = $_REQUEST[kitRequest::request_email] : $account_username = '';
		
		if (!empty($account_key) || $account_id > 0) {
			// Benutzerdaten auslesen
			$dbAccount = new dbKITregister();
			$where = array();
			if (!empty($account_key)) {
				$where[dbKITregister::field_register_key] = $account_key;
			}
			if ($account_id > 0) {
				$where[dbKITregister::field_id] = $account_id;
			}
			$account = array();
			if (!$dbAccount->sqlSelectRecord($where, $account)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbAccount->getError()));
				return false;
			}
			if (count($account) > 0) {
				$account_key = $account[0][dbKITregister::field_register_key];
				$account_id = $account[0][dbKITregister::field_id];
				$account_username = $account[0][dbKITregister::field_email];
			}
		}
		
		$template = new Dwoo();
		$data = array(
			'form_action'						=> $this->getDlgLink(),
			'action_name'						=> self::request_action,
			'action_value'					=> self::action_check_login,
			'account_key_name'			=> dbKITregister::field_register_key,
			'account_key_value'			=> $account_key,
			'account_id_name'				=> dbKITregister::field_id,
			'account_id_value'			=> $account_id,
			'intro'									=> ($this->isMessage()) ? $this->getMessage() : kit_dialog_acc_intro_login,
			'label_username'				=> kit_dialog_acc_label_username,
			'username_name'					=> dbKITregister::field_email,
			'username_value'				=> $account_username,
			'label_userpass'				=> kit_dialog_acc_label_password,
			'userpass_name'					=> dbKITregister::field_password,
			'userpass_value'				=> '',
			'label_submit'					=> '',
			'btn_submit'						=> kit_btn_ok,
			'password_hint'					=> sprintf(	kit_dialog_acc_password_hint,
																					sprintf('%s&%s=%s',
																									$this->getDlgLink(),
																									self::request_action,
																									self::action_need_password))
		);
		return $template->get($this->getTemplateFile('account.login.htt'), $data);
	} // dlgLogin()
	
} // class dlgRegisterData

?>