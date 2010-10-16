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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.request.php');
require_once(WB_PATH.'/include/captcha/captcha.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

global $tools;
global $dbRegister;
global $dbContact;

if (!is_object($tools)) $tools = new kitTools();
if (!is_object($dbRegister)) $dbRegister = new dbKITregister();
if (!is_object($dbContact)) $dbContact = new dbKITcontact();
		
/**
 * ABSTRACT class kitDialogFrame
 * 
 * This class is the frame for userdefined dialogs within KIT
 * Please look at /kit/dialogs/dlgsample for an example
 */
abstract class kitDialogFrame {
	
	private $dlgID = -1;
	private $error = '';
	private $use_captcha;
	private $message = '';
	public  $silent = false;
	
	protected $templatePath 			= '';
	protected $languagePath 			= '';
	protected $newsletter_id			= '';
	protected $provider_id				= '';
	
	const		default_language = 'DE';
	
	function __construct($silent=false) {
		$this->silent = $silent;
		$config = new dbKITcfg();
		$this->use_captcha = $config->getValue(dbKITcfg::cfgUseCaptcha);
		// check if KIT response page exists
		$request = new kitRequest();
		$request->getResponseUrl();		
	}
	
	/* Return the Captcha Usage
	 * 
	 * @return BOOL
	 */
	public function useCaptcha() {
		return $this->use_captcha;
	}
	
	/**
	 * Return the CAPTCHA Input field
	 * 
	 * @return STR
	 */
	public function getCaptcha() {
		ob_start();
			call_captcha();
			$call_captcha = ob_get_contents();
		ob_end_clean();
		return $call_captcha;
	} // getCaptcha()
	
	/**
	 * Set the Dialog ID, will be done by kitDialog
	 * 
	 * @param STR $id
	 */
	public function setDlgID($id) {
		$this->dlgID = $id;
	} // setDlgID()

	/**
	 * Return the Dialog ID of this class
	 * 
	 * @return STR
	 */
	public function getDlgID() {
		return $this->dlgID;
	} // getDlgID()
	
	/**
	 * Return the link address of your dialog. Using this link in the "action"
	 * attribute of your form enable your dialog to interact with the users.
	 */
	public function getDlgLink() {
		$request = new kitRequest();
		$request_link = $request->getRequestLink();
		
		return sprintf(	'%s&%s=%s&%s=%s&%s=%s', 
										$request_link, 
										kitRequest::request_action,
										kitRequest::action_dialog,
										kitRequest::request_dialog,
										$this->getDlgID(),
										kitRequest::request_language,
										strtolower(LANGUAGE));
	} // getDlgLink()
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError
  
  /** Set $this->message to $message
    * 
    * @param STR $message
    */
  public function setMessage($message) {
    $this->message = $message;
  } // setMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMessage() {
    return $this->message;
  } // getMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMessage() {
    return (bool) !empty($this->message);
  } // isMessage
  
	/**
   * Prevents XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
	
  /**
   * This function will be called with all parameters in $params by 
   * kitDialog. You must overwrite this function and return your resulting
   * dialog as STRING.
   */
	abstract function action();
	
	/**
	 * Validate the E-Mail $email by logical check
	 * 
	 * @param STR $email
	 * @return BOOL
	 */
	public function checkEMailAddress($email) {
		global $tools;
		return $tools->validateEMail($email);
	}
	
	/**
	 * Check if the $email ist registered and create an registration entry
	 * 
	 * @param STR $email
	 * @param BOOL $create
	 * @param REFERENCE ARRAY &$data
	 * @return BOOL
	 */
	public function isEMailRegistered($email, $create=false, &$data) {
		global $dbRegister;
		global $dbContact;
		
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s != '%s'",
										$dbRegister->getTableName(),
										dbKITregister::field_email,
										strtolower($email),
										dbKITregister::field_status,
										dbKITregister::status_deleted);
		$result = array();
		if (!$dbRegister->sqlExec($SQL, $result)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
			return false;
		}
		if (count($result) < 1) { 
			// kein Eintrag in der dbKITregister
			$SQL = sprintf(	"SELECT %s, %s FROM %s WHERE %s LIKE '%%%s%%' AND %s='%s'", 
											dbKITcontact::field_id,
											dbKITcontact::field_email,
											$dbContact->getTableName(),
											dbKITcontact::field_email,
											strtolower($email),
											dbKITcontact::field_status,
											dbKITcontact::status_active);
			$result = array();
			if (!$dbContact->sqlExec($SQL, $result)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			if (count($result) < 1) { 
				// kein Eintrag gefunden
				if ($create) {
					// es soll ein neuer Eintrag angelegt werden
					$contact_id = -1;
					if ($this->createRegisterRecord($email, $contact_id, $data)) { 
						return true;
					}
					else {
						return false;
					}
				}
				return false;
			}
			foreach ($result as $contact) {
				$email_array = explode(';', $contact[dbKITcontact::field_email]);
				foreach ($email_array as $email_item) {
					list($e_type, $e_address) = explode('|', $email_item);
					if ($e_address == strtolower($email)) {
						// eintrag gefunden
						$contact_id = $contact[dbKITcontact::field_id];
						if ($create) {
							// es soll ein neuer Eintrag angelegt werden
							if ($this->createRegisterRecord($email, $contact_id, $data)) {
								return true;
							}
							else {
								return false;
							}
						}
						else {
							return false;
						}
					}
				}
			} // foreach
			// kein Treffer
			return false;
		}
		else {
			// Eintrag existiert
			$data = $result[0];
			return true;
		}
	} // isEMailRegistered()
	
	/**
	 * Create a new record in dbKITregister for $email address and connect it with
	 * $contact_id. Gives the record back in $data
	 * 
	 * @param STR $email
	 * @param INT $contact_id
	 * @param REFERENCE ARRAY $data
	 * @return BOOL
	 */
	public function createRegisterRecord($email, $contact_id=-1, &$data=array()) {
		global $tools;
		global $dbRegister;
		
		$data = array();
		$data[dbKITregister::field_email] = strtolower($email);
		$data[dbKITregister::field_contact_id] = $contact_id;
		$data[dbKITregister::field_login_failures] = 0;
		$data[dbKITregister::field_login_locked] = 1; // Login sperren
		if (isset($_REQUEST[dbKITcontact::field_newsletter])) {
			// Newsletter Auswahl festhalten
			if (is_array($_REQUEST[dbKITcontact::field_newsletter])) {
				$newsletter = implode(',', $_REQUEST[dbKITcontact::field_newsletter]);
			}
			else { $newsletter = $_REQUEST[dbKITcontact::field_newsletter]; }
		}
		else { $newsletter = ''; }
		$data[dbKITregister::field_newsletter] = $newsletter; 
		$data[dbKITregister::field_password] = ''; // Passwort wird erst bei der Aktivierung festgelegt
		$data[dbKITregister::field_register_date] = date('Y-m-d H:i:s');
		$data[dbKITregister::field_register_key] = $tools->createGUID();
		$data[dbKITregister::field_status] = dbKITregister::status_key_created;
		$data[dbKITregister::field_update_by] = 'SYSTEM';
		$data[dbKITregister::field_update_when] = date('Y-m-d H:i:s');
		$data[dbKITregister::field_username] = strtolower($email);
		$register_id = -1;
		if (!$dbRegister->sqlInsertRecord($data, $register_id)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
			return false; 
		}
		$data[dbKITregister::field_id] = $register_id;
		return true;
	} // createRegisterRecord()
	
	public function getTemplateFile($template_file) {
		return getTemplateFile($template_file, $this->templatePath, $this->languagePath, self::default_language);
	}
		
} // kitDialogFrame

/**
 * data class for registering the dialogs with an unique id
 *
 */
class dbKITdialogsRegister extends dbConnectLE {
	
	const field_id				= 'dlgreg_id';
	const field_name			= 'dlgreg_name';
	
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_dlg_register');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}
	} // __construct()
	
	
} // class dbKITdialogsRegister

class kitDialog {
	
	public $prompt;
	private $error = '';
	
	function __construct($silent=false) {
		$this->prompt = !$silent;
	}
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError
	
  /**
   * Return the Dialog ID of $dialog.
   * If the Dialog ID not exists it will be created and saved
   *
   * @param STR $dialog
   * @param REFERENCE STR $id
   * @return BOOL
   */
	function getDialogID($dialog, &$id) { 
		$register = new dbKITdialogsRegister();
		$where = array();
		$where[dbKITdialogsRegister::field_name] = $dialog;
		$dialogs = array();
		if (!$register->sqlSelectRecord($where, $dialogs)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $register->getError()));
			return false;	
		}
		if (count($dialogs) > 0) {
			$id = $dialogs[0][dbKITdialogsRegister::field_id];
		}
		else {
			$data = array(); 
			$data[dbKITdialogsRegister::field_name] = $dialog;
			$new_id = -1;
			if (!$register->sqlInsertRecord($data, $new_id)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $register->getError()));
				return false;	
			}
			$id = $new_id;
		}
		return true;
	} // getDialogID
	
	/**
	 * Call the requested dialog
	 *
	 * @param STR $dialog
	 * @param ARRAY $params
	 * @return STR
	 */
	function __call($dialog, $params) { 
		$lowerDialog = strtolower($dialog);
		if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.$lowerDialog.'/'.$lowerDialog.'.php')) {
			require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.$lowerDialog.'/'.$lowerDialog.'.php');
			$id = -1;
			if ($this->getDialogID($dialog, $id)) { 
				//(isset($params[0])) ? $silent = (bool) $params[0] : $silent = false;
				$silent = !$this->prompt; 
				$callDialog = new $dialog($silent);
				$callDialog->setDlgID($id);
				$result = $callDialog->action();
			}
			else {
				$result = $this->getError();
			}
		}
		else {
			$result = sprintf(kit_error_dlg_missing, $dialog);
		}
		// return $result
		if ($this->prompt) {
			echo $result;
		}
		else {
			return $result;
		}
	} // __call()
	
} // kitDialogs

?>