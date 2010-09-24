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
  
  $Id: dialog_newsletter.php 42 2010-06-05 03:30:58Z ralf $
  
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



class dialog_newsletter extends kitDialogFrame  {
	
	const request_action 				= 'nl_act';
	const request_subscribe			= 'nl_subscribe';
	const request_email					= 'nl_mail';
	
	const action_check					= 'check';
	const action_default				= 'default';
	const action_unsubscribe		= 'unsub';
	
	function __construct($silent=false) {
		parent::__construct($silent);
		// set template and language path
		$this->templatePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/htt/';
		$this->languagePath = WB_PATH.'/modules/kit/dialogs/'.basename(dirname(__FILE__)).'/languages/';
	}
	
	function action() {
		
		$html_allowed = array();
		// prevent XSS Cross Site Scripting
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
		isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;

  	// Newsletter auslesen
  	$dbKITcontact = new dbKITcontact();
  	$newsletter_array = $dbKITcontact->newsletter_array;
  	if (count($newsletter_array) < 1) {
  		$this->setError(kit_dialog_nl_no_newsletter);
  	}

  	// $provider_id pruefen
  	if (!$this->isError()) {
	  	$dbKITprovider = new dbKITprovider();
	  	$where = array();
	  	$where[dbKITprovider::field_status] = dbKITprovider::status_active;
	  	$provider = array();
	  	if (!$dbKITprovider->sqlSelectRecord($where, $provider)) {
	  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITprovider->getError()));
	  	}
	  	elseif (count($provider) < 1) {
	  		$this->setError(kit_dialog_nl_error_no_provider);
	  	}	
  	}
  	
  	$result = '';
  	
  	if (!$this->isError()) {
	  	switch ($action):
			case self::action_check:
				// Eingaben pruefen und Aktivierung verschicken
				$action = '';
				if (!$this->checkSubmission()) {
					// Fehler bei der Pruefung, Dialog wieder anzeigen
					$result = $this->showDlg();
					break;
				}
				elseif ($_REQUEST[self::request_subscribe] == 1) {
					// zum Newsletter anmelden
					$data = array();
					if ($this->isEMailRegistered($_REQUEST[self::request_email], true, $data)) {
						switch ($data[dbKITregister::field_status]):
						case dbKITregister::status_key_created:
							// Schluessel wurde erzeugt, Aktiverungsmail versenden
							$result = $this->sendActivationKey($data[dbKITregister::field_id]);
							break;
						case dbKITregister::status_key_send:
							// Schluessel wurde bereits erzeugt und per E-Mail versendet
							$result = $this->sendActivationKey($data[dbKITregister::field_id]);
							if (!$this->isError()) {
								$result = $this->dlgMessage(dbKITregister::status_key_send);
							}
							break;
						case dbKITregister::status_locked:
							// Account gesperrt
							$result = $this->dlgMessage(dbKITregister::status_locked);
							break;
						case dbKITregister::status_active:
							// Account existiert bereits
							$result = $this->dlgMessage(dbKITregister::status_active, $data);
							break;
						default:
							// nicht naeher spezifizierter Aufruf...
							$result = $this->dlgMessage($data[dbKITregister::field_status]);
							break;
						endswitch;																		
					}
					else {
						// es ist ein Fehler aufgetreten
						if (!$this->isError()) $this->setError(kit_error_undefined);
						break;
					}
				}
				else {
					// Newsletter abmelden
					$data = array();
					if ($this->isEMailRegistered($_REQUEST[self::request_email], false, $data)) {
						if (empty($data[dbKITregister::field_newsletter])) {
							// kein Newsletter aktiv..
							$this->setMessage(sprintf(kit_dialog_nl_no_newsletter_for_unsubscribe, $_REQUEST[self::request_email]));
							$result = $this->showDlg();
						}
						else {
							// Abmeldung veranlassen
							$result = $this->unsubscribeDlg($data[dbKITregister::field_id]);
						}
					}
					else {
						// es existiert kein Account...
						$this->setMessage(sprintf(kit_dialog_nl_no_account_for_unsubscribe, $_REQUEST[self::request_email]));
						$result = $this->showDlg();
					}
				}
				break;
			case self::action_unsubscribe:
				if (!isset($_REQUEST[dbKITcontact::field_newsletter])) {
					// kein Newsletter zum Abbestellen ausgewaehlt
					$this->setMessage(kit_dialog_nl_unsubscribe_no_selection);
					$result = $this->unsubscribeDlg($_REQUEST[dbKITregister::field_id]);
				}
				else {
					// Bestaetigungslink versenden
					if ($this->sendUnsubscribeKey()) {
						$result = $this->showDlg();
					}
				}
				break;
			case self::action_default:
			default:
				$result = $this->showDlg();
				break;
	  	endswitch;
  	}
  	
  	// AUSGABE
  	if ($this->isError()) $result = sprintf('<div class="kit_nl_error"><h1>%s</h1><p>%s</p></div>', kit_header_error, $this->getError());
  	if ($this->silent) {
  		// stille Ausgabe fuer Droplet etc. ...
  		return $result;
  	}
  	else {
  		echo $result;
  	}
	} // action()
	
	/**
	 * Prueft, ob alle erforderlichen Eingaben im Dialog erfolgt sind
	 * 
	 * @return BOOL 
	 */
	public function checkSubmission() { 
		// Newsletter bestellt? Kein Eintrag erforderlich bei Abmeldung...
		if ((isset($_REQUEST[self::request_subscribe]) && 
				($_REQUEST[self::request_subscribe] == 1)) && 
				!isset($_REQUEST[dbKITcontact::field_newsletter])) {
			$this->setMessage(kit_dialog_nl_no_newsletter_selected);
			return false;
		}
		
		// E-Mail Adresse pruefen
		if (!isset($_REQUEST[self::request_email])) {
			$this->setMessage(kit_dialog_nl_email_empty);
			return false;
		}
		if (!$this->checkEMailAddress($_REQUEST[self::request_email])) { 
			$this->setMessage(sprintf(kit_dialog_nl_email_invalid, $_REQUEST[self::request_email]));
			return false;
		}
		
		// Captcha pruefen
		if (!isset($_REQUEST['captcha'])) {
			$this->setMessage(kit_dialog_nl_captcha_invalid);
			return false;
		}
		if ($this->useCaptcha()) {
			if (!isset($_REQUEST['captcha']) || ($_REQUEST['captcha'] != $_SESSION['captcha'])) {
				$this->setMessage(kit_dialog_nl_captcha_invalid);
				return false;
			}
		}
		return true;
	} // checkSubmisstion()
	
	
	public function sendUnsubscribeKey() {
		// Empfaenger auslesen
		$dbKITRegister = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_id] = $_REQUEST[dbKITregister::field_id];
		$register = array();
		if (!$dbKITRegister->sqlSelectRecord($where, $register)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
			return false;
		}
		if (count($register) < 0) {
			$this->setError(sprintf(kit_dialog_nl_invalid_account, $_REQUEST[dbKITregister::field_id]));
			return false;
		}
		$register = $register[0];
		$to_array = array($register[dbKITregister::field_email] => '');
		
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

		$request = new kitRequest();
		$request_link = $request->getRequestLink();
				
		// Newsletter auslesen
		$newsletter_unsubscribe_value = implode(',', $_REQUEST[dbKITcontact::field_newsletter]);
		$newsletter_unsubscribe = '';
		$dbContact = new dbKITcontact();
		foreach ($_REQUEST[dbKITcontact::field_newsletter] as $item) {
			if ($newsletter_unsubscribe != '') $newsletter_unsubscribe .= ', ';
			$newsletter_unsubscribe .= $dbContact->newsletter_array[$item];
		}
				
		// Nachricht erstellen
		$template = new Dwoo();
		$data = array(
			'website'						=> WEBSITE_TITLE,
			'activation_link'		=> sprintf(	'%s&%s=%s&%s=%s&%s=%s', 
																			$request_link,
																			kitRequest::request_action,
																			kitRequest::action_unsubscribe,
																			kitRequest::request_key,
																			$register[dbKITregister::field_register_key],
																			kitRequest::request_newsletter,
																			rawurlencode($newsletter_unsubscribe_value)),
			'newsletter'				=> $newsletter_unsubscribe
		);
		$message = $template->get($this->getTemplateFile(LANGUAGE.'.mail.unsubscribe.htt'), $data);
		$mail = new kitMail();
		if ($mail->mail(kit_dialog_nl_mail_subject_unsubscribe, $message, $provider[dbKITprovider::field_email], $provider[dbKITprovider::field_name], $to_array, true)) {
			// Datensatz aktualisieren
			$where = array();
			$where[dbKITregister::field_id] = $register[dbKITregister::field_id];
			unset($register[dbKITregister::field_id]);
			$register[dbKITregister::field_update_by] = $register[dbKITregister::field_email];
			$register[dbKITregister::field_update_when] = date('y-m-d H:i:s');
			if (!$dbKITRegister->sqlUpdateRecord($register, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
				return false;
			}
			$this->setMessage(sprintf(kit_dialog_nl_unsubscribe_key_send, $register[dbKITregister::field_email]));
			return true;
		}
		else {
			// Fehler beim Versenden der E-Mail
			$this->setError(sprintf(kit_dialog_nl_error_mail_send, $register[dbKITregister::field_email]));
			return false;
		}
	} // sendUnsubscribeKey
	
	public function unsubscribeDlg($register_id) {
		// Empfaenger auslesen
		$dbKITRegister = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_id] = $register_id;
		$register = array();
		if (!$dbKITRegister->sqlSelectRecord($where, $register)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
			return false;
		}
		$register = $register[0];
		
		// Newsletter auslesen
  	$dbKITcontact = new dbKITcontact();
  	$newsletter_array = $dbKITcontact->newsletter_array;
  	if (count($newsletter_array) < 1) {
  		$this->setError(kit_dialog_nl_no_newsletter);
  		return false;
  	}
  	$newsletter = '';
  	$newsletter_subscribed = explode(',', $register[dbKITregister::field_newsletter]);
  	foreach ($newsletter_array as $key => $value) {
			(in_array($key, $newsletter_subscribed)) ? $checked=' checked="checked"' : $checked = '';
			$newsletter .= sprintf(	'<input type="checkbox" name="%s[]" value="%s"%s />&nbsp;%s</br>',
															dbKITcontact::field_newsletter,
															$key,
															$checked,
															$value);
		}
		// Captcha
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
			'action_value'				=> self::action_unsubscribe,
			'register_name'				=> dbKITregister::field_id,
			'register_value'			=> $register[dbKITregister::field_id],
			'intro_text'					=> ($this->isMessage()) ? $this->getMessage() : kit_dialog_nl_unsubscribe_intro,
			'label_newsletter'		=> kit_label_newsletter,
			'newsletter'					=> $newsletter,
			'btn_submit'					=> kit_btn_send,
			'label_submit'				=> '',
			'captcha'							=> $captcha
		);
		return $template->get($this->getTemplateFile('newsletter.unsubscribe.htt'), $data);
	} // unsubscribeDlg()
	
	/**
	 * Zeigt den Dialog zum An- und Abmelden fuer die Newsletter an
	 */
	public function showDlg() {
		// Newsletter auslesen
  	$dbKITcontact = new dbKITcontact();
  	$newsletter_array = $dbKITcontact->newsletter_array;
  	if (count($newsletter_array) < 1) {
  		$this->setError(kit_dialog_nl_no_newsletter);
  		return false;
  	}
  	$newsletter = '';
  	foreach ($newsletter_array as $key => $value) {
			(isset($_REQUEST[dbKITcontact::field_newsletter]) && in_array($key, $_REQUEST[dbKITcontact::field_newsletter])) ? $checked=' checked="checked"' : $checked = '';
			$newsletter .= sprintf(	'<input type="checkbox" name="%s[]" value="%s"%s />&nbsp;%s<br />',
															dbKITcontact::field_newsletter,
															$key,
															$checked,
															$value);
		}
		
  	if ($this->useCaptcha()) {
			$captcha = sprintf('<div class="kit_captcha">%s&nbsp;%s</div>', kit_label_checksum, $this->getCaptcha());
		}
		else {
			$captcha = '';
		}
		$parser = new Dwoo(); 
		$data = array(
			'form_action'				=> $this->getDlgLink(),
			'action_name'				=> self::request_action,
			'action_value'			=> self::action_check,
			'intro_text'				=> ($this->isMessage()) ? $this->getMessage() : kit_dialog_nl_newsletter_intro,
			'label_newsletter'	=> kit_label_newsletter,
			'newsletter'				=> $newsletter,
			'email_name'				=> self::request_email,
			'email_value'				=> isset($_REQUEST[self::request_email]) ? $_REQUEST[self::request_email] : '',
			'newsletter_subscribe' => self::request_subscribe,
			'text_subscribe'		=> kit_label_subscribe,
			'text_unsubscribe'	=> kit_label_unsubscribe,
			'btn_submit'				=> kit_btn_send,
			'captcha'						=> $captcha,
			'label_email'				=> kit_label_contact_email,
			'label_action'			=> kit_label_newsletter,
			'label_submit'			=> ''
		);
		return $parser->get($this->getTemplateFile('newsletter.subscribe.htt'), $data);
	} // showDlg()
	
	/**
	 * Gibt eine Meldung aus
	 * 
	 * @param STR $action
	 * @param ARRAY $account
	 * @return STR
	 */
	public function dlgMessage($action='', $account=array()) {
		$template = new Dwoo();
		$data = array(
			'header'		=> kit_dialog_nl_message,
			'service'		=> kit_dialog_nl_service
		);	
		switch ($action):
		case dbKITregister::status_active:
			// Konto ist bereits aktiv

			$request = new kitRequest();
			$request_link = $request->getRequestLink();
			
			$link = sprintf('%s&%s=%s&%s=%s',
											$request_link,
											kitRequest::request_action,
											kitRequest::action_login,
											kitRequest::request_key,
											$account[dbKITregister::field_register_key]);
			$data['message'] = sprintf(kit_dialog_nl_account_active, $link);
			break;
		case dbKITregister::status_locked:
			// Account ist gesperrt
			$data['message'] = kit_dialog_nl_message_account_locked; 
			break;
		case dbKITregister::status_key_send:
			// Schluessel bereits versendet
			$data['message'] = kit_dialog_nl_message_key_send;		
			break;
		default:
			$data['message'] = sprintf(kit_dialog_nl_no_action, $action);
			break;
		endswitch;
		return $template->get($this->getTemplateFile('message.htt'), $data);
	} // dlgMessage()
	
	/**
	 * Legt den Datensatz an, aktualisiert ihn und versendet eine E-Mail
	 * mit dem Aktivierungslink an den Abonnenten
	 * 
	 * @return STR
	 */
	public function sendActivationKey($register_id) {
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
		
		// Empfaenger auslesen
		$dbKITRegister = new dbKITregister();
		$where = array();
		$where[dbKITregister::field_id] = $register_id;
		$register = array();
		if (!$dbKITRegister->sqlSelectRecord($where, $register)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
			return false;
		}
		$register = $register[0];
		$to_array = array($register[dbKITregister::field_email] => '');
		
		$request = new kitRequest();
		$request_link = $request->getRequestLink();
				
		// Nachricht erstellen
		$template = new Dwoo();
		$data = array(
			'website'						=> WEBSITE_TITLE,
			'activation_link'		=> sprintf(	'%s&%s=%s&%s=%s', 
																			$request_link,
																			kitRequest::request_action,
																			kitRequest::action_activate_key,
																			kitRequest::request_key,
																			$register[dbKITregister::field_register_key])
		);
		$message = $template->get($this->getTemplateFile(LANGUAGE.'.mail.activation.htt'), $data);
		$mail = new kitMail();
		if ($mail->mail(kit_dialog_nl_mail_subject, $message, $provider[dbKITprovider::field_email], $provider[dbKITprovider::field_name], $to_array, true)) {
			// Datensatz aktualisieren
			$where = array();
			$where[dbKITregister::field_id] = $register[dbKITregister::field_id];
			unset($register[dbKITregister::field_id]);
			$register[dbKITregister::field_status] = dbKITregister::status_key_send;
			$register[dbKITregister::field_update_by] = $register[dbKITregister::field_email];
			$register[dbKITregister::field_update_when] = date('y-m-d H:i:s');
			if (!$dbKITRegister->sqlUpdateRecord($register, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
				return false; 
			}
			// Ergebnis anzeigen
			$data = array(
				'email' 			=> $register[dbKITregister::field_email],
				'website'			=> WEBSITE_TITLE
			);
			return $template->get($this->getTemplateFile(LANGUAGE.'.dlg.activation.htt'), $data);
		}
		else {
			// Fehler beim Versenden der E-Mail
			$where = array();
			$where[dbKITregister::field_id] = $register[dbKITregister::field_id];
			unset($register[dbKITregister::field_id]);
			$register[dbKITregister::field_status] = dbKITregister::status_deleted;
			$register[dbKITregister::field_update_by] = $tools->getDisplayName();
			$register[dbKITregister::field_update_when] = date('y-m-d H:i:s');
			if (!$dbKITRegister->sqlUpdateRecord($register, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITRegister->getError()));
				return false; 
			}
			$this->setError(sprintf(kit_dialog_nl_error_mail, $register[dbKITregister::field_email], $mail->getMailError()));
			return false;
		}
	} // sendActivationKey()
	
} // class dlgNewsletterOrder

?>