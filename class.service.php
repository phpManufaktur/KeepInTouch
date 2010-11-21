<?php

/**
 * @author       Ralf Hertsch
 * @copyright    2010 - today by phpManufaktur   
 * @link         http://phpManufaktur.de
 * @license      http://www.gnu.org/licenses/gpl.html
 * @version      $Id$
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

global $parser;

if (!is_object($parser)) $parser = new Dwoo(); 

class kitService {
	
	const request_action							= 'stact';
	const request_email								= 'em';
	const request_title								= 'tit';
	const request_first_name					= 'fn';
	const request_last_name						= 'ln';
	const request_company							= 'com';
	const request_newsletter					= 'nl';
	const request_message							= 'message';
	const request_status							= 'status';
	const request_install_guid				= 'key';
	const request_license_type				= 'type';
	const request_license_expire			= 'exp';
	const request_language						= 'lang';
	const request_url									= 'url';
	const request_ip									= 'ip';
	const request_module_guid					= 'mod_guid';
	
	const action_default							= 'def';
	const action_register							= 'register';
	const action_register_check				= 'regc';
	const action_get_guid							= 'get_guid';
	const action_get_license					= 'get_license';
	
	const license_locked							= -1;
	const license_invalid							= -2;
	const license_undefined						= 0;
	const license_evaluate						= 1;
	const license_registered					= 2;
	const license_private							= 3;
	const license_small_business			= 4;
	const license_business						= 5;
	const license_unlimited						= 6;
	const license_beta_limited				= 7;		

	const status_ok										= 'OK';
	const status_message							= 'MESSAGE';
	const status_error								= 'ERROR';
	
	private $page_link 								= '';
	private $img_url									= '';
	private $template_path						= '';
	private $help_path								= '';
	private $error										= '';
	private $message									= '';
	private $module_guid							= '';
	private $update_server						= '';
	private $license_key							= '';
	
	const person_title_mister					= 'titleMister'; // Definitionen aus KIT;
	const person_title_lady						= 'titleLady'; // Definition aus KIT;
	
	public $person_title_array = array(
		self::person_title_mister		=> kit_contact_person_title_mister,
		self::person_title_lady			=> kit_contact_person_title_lady
	);
	
	public function __construct() {
		global $dbCfg;
		$this->page_link = ADMIN_URL.'/admintools/tool.php?tool=kit';
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->help_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/languages/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';
		$this->module_guid = strtolower('B8AF0EA2-26BD-4512-91D4-07B97A2E8DCA');
		$this->update_server = 'http://test.ralf-hertsch.de/modules/service/check.php';
		$this->license_key = $dbCfg->getValue(dbKITcfg::cfgLicenseKey);	
	} // __construct()
	
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
   * Reset Error to empty String
   */
  public function clearError() {
  	$this->error = '';
  }

  /** Set $this->message to $message
    * 
    * @param STR $message
    */
  public function setMessage($message='') {
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
   * Verhindert XSS Cross Site Scripting
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
  
  
  public function action() {
  	// ACHTUNG: erlaubte HTML Felder muessen auch in $kitBackend->action() angegeben werden !!!
  	$html_allowed = array();
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
  	isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
  	switch ($action):
  	case self::action_register_check:
  		return $this->checkRegister();
  		break;
  	case self::action_register:
  		return $this->dlgRegister();
  		break;
  	default:
  		return $this->dlgStart();
  		break;
  	endswitch;
  	return true;
  } // action();
  
  /**
   * Return Release of Module
   *
   * @return FLOAT
   */
  private function getRelease() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1; 
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = explode('=', $item);
        // return floatval
        return floatval(preg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1]));
      } 
    }
    return -1;
  } // getRelease()
  
  private function explodeAnswer($answer_str, &$answer_array=array()) {
  	$work_array = explode('|', $answer_str);
  	$answer_array = array();
  	foreach ($work_array as $worker) {
  		if (strpos($worker, '=') !== false) {
  			list($key, $value) = explode('=', $worker);
  			$answer_array[$key] = ($key == self::request_message) ? urldecode($value) : $value;
  		}
  	}
  	return (count($answer_array) > 0) ? true : false;	
  } // explodeAnswer()
  
  /**
   * Prueft on KIT registriert ist und gibt die Lizenz zurueck
   * @return INT license
   */
  private function getActualLicense(&$response=array()) {
  	global $dbCfg; 
  	if (ini_get('allow_url_fopen') == '1') {
  		ini_set('allow_url_include', '1');
  	
	  	$result = '-no result-';
	  	// 1. Schritt, gibt es einen Lizenzschluessel?
	  	if (empty($this->license_key)) { 
	  		// kein Lizenzschluessel vorhanden
	  		$url = sprintf(	'%s?%s=%s&%s=%s&%s=%s&%s=%s',
	  										$this->update_server,
	  										self::request_action, self::action_get_guid,
	  										self::request_url, WB_URL,
	  										self::request_ip, $_SERVER['SERVER_ADDR'],
	  										self::request_module_guid, $this->module_guid);
	  		if (false == ($result = strip_tags(file_get_contents($url)))) {
	  			$this->setMessage(kit_msg_service_no_connect);
	  			return false;
	  		} 
	  		$response = array();
	  		if ($this->explodeAnswer($result, $response)) {
	  			if (isset($response[self::request_status]) && ($response[self::request_status] == self::status_ok) && isset($response[self::request_install_guid])) {
	  				// Lizenzschluessel sichern
	  				$dbCfg->setValueByName($response[self::request_install_guid], dbKITcfg::cfgLicenseKey); 	
	  			}
	  			elseif (isset($response[self::request_status]) && ($response[self::request_status] !== self::status_ok)) {
	  				// Fehler
	  				if (isset($response[self::request_message])) {
	  					$this->setMessage($response[self::request_message]);
	  					return false;
	  				}
	  				else {
	  					$this->setMessage(kit_error_undefined);
	  					return false;
	  				}
	  			}
	  			else {
	  				$error = (strlen($result) > 0) ? sprintf('<p>ERROR: %s</p>', $result) : kit_error_undefined;
	  				$this->setMessage($error);
	  				return false;
	  			}
	  		}
	  		else {
	  			$error = (strlen($result) > 0) ? sprintf('<p>ERROR: %s</p>', $result) : kit_error_undefined;
	  			$this->setMessage($error);
	  			return false;
	  		}
	  	} 
	  	// 2. Schritt Lizenz pruefen
	  	$url = sprintf(	'%s?%s=%s&%s=%s&%s=%s&%s=%s&%s=%s',
	  									$this->update_server,
	  									self::request_action, self::action_get_license,
	  									self::request_url, WB_URL,
	  									self::request_ip, $_SERVER['SERVER_ADDR'],
	  									self::request_module_guid, $this->module_guid,
	  									self::request_install_guid, $this->license_key );
	  	if (false == ($result = strip_tags(file_get_contents($url)))) {
	  		$this->setMessage(kit_msg_service_no_connect);
	  		return false;
	  	}
	  	if ($this->explodeAnswer($result, $response)) {
	  		if (isset($response[self::request_status]) && ($response[self::request_status] == self::status_ok) && isset($response[self::request_license_type])) {
	  			// if (isset($response[self::request_message])) $this->setMessage($response[self::request_message]);
	  			return $response[self::request_license_type];
	  		}
	  		elseif(isset($response[self::request_status]) && ($response[self::request_status] !== self::status_ok) && isset($response[self::request_message])) {
	  			$this->setMessage($response[self::request_message]);
	  			return false;
	  		}
	  		else {
	  			$this->setMessage(kit_error_undefined);
	  			return false;
	  		}
	  	}
	  	else {
	  		$error = (strlen($result) > 0) ? sprintf('<p>ERROR: %s</p>', $result) : kit_error_undefined;
	  		$this->setMessage($error);
	  		return false;
	  	}
  	}
  	else {
  		return self::license_beta_limited;
  	}
  } // getActualLicense()
  
  /**
   * Show Informations about KeepInTouch
   * @return STR Information
   */
  private function getKITinfo() {
  	global $parser;
  	$response = array();
  	$license = $this->getActualLicense($response);
  	switch ($license):
  	case false:
  		// FALSE = FEHLER
  		$license_info = sprintf('<div class="message">%s</div>', $this->getMessage());
  		$this->setMessage();
  		break;
  	case self::license_beta_limited:
  		$license_info = sprintf(kit_msg_service_license_beta_registered,
  														$this->license_key,
  														date(kit_cfg_date_str, strtotime($response[self::request_license_expire])),
  														$response[self::request_first_name],
  														$response[self::request_last_name]);
  		break;
  	default:
  		$license_info = sprintf(kit_msg_service_license_beta_evaluate, 
  														sprintf('%s&%s=%s&%s=%s',
  																		$this->page_link,
  																		kitBackend::request_action,
  																		kitBackend::action_start,
  																		self::request_action,
  																		self::action_register));
  	endswitch;
  	$data = array(
  		'logo' 			=> $this->img_url.'keepintouch-298-55.png',
  		'release'		=> sprintf('Release %01.2f', $this->getRelease()),
  		'register'	=> $license_info
  	);
  	return $parser->get($this->template_path.'backend.start.license.info.htt', $data);
  } // getLicenseInfo()
	
  private function getInfoBox() {
  	if ($this->isMessage()) {
  		return sprintf('<div id="kit_info_box">%s</div>', $this->getMessage());
  	}
  	// Abfrage externer Infoserver fehlt noch...
  	return '';
  }
  
  public function dlgStart() {
  	global $parser;
  	$menu_items = '';
  	$row = new Dwoo_Template_File($this->template_path.'backend.start.menu.item.htt');
  	// Alle Kontakte
  	$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="81" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_list,
  																	$this->img_url.'tango/x-office-address-book.png',
  																	kit_tab_list),
  								'info' => kit_start_list);
  	$menu_items .= $parser->get($row, $data);
		// Kontakt bearbeiten
		$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="80" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_contact,
  																	$this->img_url.'tango/contact-new.png',
  																	kit_tab_contact),
  								'info' => kit_start_contact);
  	$menu_items .= $parser->get($row, $data);
		// Gruppen E-Mail
  	$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="80" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_email,
  																	$this->img_url.'tango/group-mail.png',
  																	kit_tab_contact),
  								'info' => kit_start_email);
  	$menu_items .= $parser->get($row, $data);
		// Newsletter
  	$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="80" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_newsletter,
  																	$this->img_url.'tango/newsletter.png',
  																	kit_tab_newsletter),
  								'info' => kit_start_newsletter);
  	$menu_items .= $parser->get($row, $data);
  	// Einstellungen
  	$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="80" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_cfg,
  																	$this->img_url.'tango/configuration.png',
  																	kit_tab_cfg_general),
  								'info' => kit_start_config);
  	$menu_items .= $parser->get($row, $data);
		// Hilfe
		$data = array('icon' => sprintf('<a href="%s&%s=%s"><img src="%s" alt="%s" width="80" height="80" /></a>',
  																	$this->page_link,
  																	kitBackend::request_action,
  																	kitBackend::action_help,
  																	$this->img_url.'tango/help.png',
  																	kit_tab_help),
  								'info' => kit_start_help);
  	$menu_items .= $parser->get($row, $data);
		
  	$license_info = $this->getKITInfo();
  	
  	$kit_info = $this->getInfoBox();
  	
  	$data = array(
  		'menu_items'		=> $menu_items,
  		'license_info'	=> $license_info,
  		'kit_info'			=> $kit_info
  	);
  	return $parser->get($this->template_path.'backend.start.htt', $data);
  } // dlgStart()
	
  /**
   * Dialog fuer die Registrierung der KIT Installation
   * @return STR dialog
   */
  private function dlgRegister() {
  	global $parser;
  	$company = (isset($_REQUEST[self::request_company])) ? $_REQUEST[self::request_company] : '';
  	$email = (isset($_REQUEST[self::request_email])) ? $_REQUEST[self::request_email] : '';
  	$title = (isset($_REQUEST[self::request_title])) ? $_REQUEST[self::request_title] : self::person_title_mister;
  	$first_name = (isset($_REQUEST[self::request_first_name])) ? $_REQUEST[self::request_first_name] : '';
  	$last_name = (isset($_REQUEST[self::request_last_name])) ? $_REQUEST[self::request_last_name] : '';
  	$newsletter = (isset($_REQUEST[self::request_newsletter])) ? $_REQUEST[self::request_newsletter] : 0;

  	$items = '';
  	$row = new Dwoo_Template_File($this->template_path.'backend.start.register.dlg.tr.htt');
  	
  	
  	// Firma
  	$data = array(
  		'label'		=> kit_label_company_name,
  		'value'		=> sprintf(	'<input type="text" name="%s" value="%s" />',
  													self::request_company, $company)
  	);
  	$items .= $parser->get($row, $data);
  	// Anrede
  	$select = '';
  	foreach ($this->person_title_array as $value => $label) {
  		$selected = ($value == $title) ? ' selected="selected"' : '';
  		$select .= sprintf('<option value=%s"%s>%s</option>', $value, $selected, $label); 
  	}
  	$select = sprintf('<select name="%s">%s</select>', self::request_title, $select);
  	$data = array(
  		'label'		=> kit_label_person_title,
  		'value'		=> $select
  	);
  	$items .= $parser->get($row, $data);
  	// Vorname
  	$data = array(
  		'label'		=> kit_label_person_first_name,
  		'value'		=> sprintf(	'<input type="text" name="%s" value="%s" />',
  													self::request_first_name, $first_name)
  	);
  	$items .= $parser->get($row, $data);
  	// Nachname
  	$data = array(
  		'label'		=> kit_label_person_last_name,
  		'value'		=> sprintf(	'<input type="text" name="%s" value="%s" />',
  													self::request_last_name, $last_name)
  	);
  	$items .= $parser->get($row, $data);
  	// E-Mail
  	$data = array(
  		'label'		=> kit_label_contact_email.'<b>*</b>',
  		'value'		=> sprintf(	'<input type="text" name="%s" value="%s" />',
  													self::request_email, $email)
  	);
  	$items .= $parser->get($row, $data);
  	  	
  	// intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_register_installation);
		}	
  	$data = array(
  		'form_name'				=> 'lic_register',
  		'form_action'			=> $this->page_link,
  		'action_name'			=> kitBackend::request_action,
  		'action_value'		=> kitBackend::action_start,
  		'subact_name'			=> self::request_action,
  		'subact_value'		=> self::action_register_check,
  		'intro'						=> $intro,
  		'items'						=> $items,
  		'btn_send'				=> kit_btn_register,
  		'btn_abort'				=> kit_btn_abort,
  		'abort_location'	=> $this->page_link,
  		'image'						=> $this->img_url.'logo-keep-in-touch.jpg'
  	);
  	return $parser->get($this->template_path.'backend.start.register.dlg.htt', $data);
  } // dlgRegister()
  
  /**
   * Ueberprueft die Registrierdaten und fuehrt die Registrierung durch
   * @return STR dialog
   */
  private function checkRegister() {
  	global $tools;
  	
  	if (!$tools->validateEMail($_REQUEST[self::request_email])) {
  		$this->setMessage(sprintf(kit_msg_email_invalid, $_REQUEST[self::request_email]));
  		return $this->dlgRegister();
  	}
  	if ((strlen(trim($_REQUEST[self::request_first_name])) < 2) || (strlen(trim($_REQUEST[self::request_last_name])) < 3)) {
  		$this->setMessage(kit_msg_service_invalid_user_name);
  	}
  	// Kontakt mit dem Server aufnehmen
  	$url = sprintf(	'%s?%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s&%s=%s',
  									$this->update_server,
  									self::request_language,	LANGUAGE,
  									self::request_action,	self::action_register,
  									self::request_url, WB_URL,
  									self::request_ip,	$_SERVER['SERVER_ADDR'],
  									self::request_module_guid, $this->module_guid,
  									self::request_install_guid,	$this->license_key,
  									self::request_license_type,	self::license_beta_limited,
  									self::request_title, $_REQUEST[self::request_title],
  									self::request_first_name, urlencode(trim($_REQUEST[self::request_first_name])),
  									self::request_last_name, urlencode(trim($_REQUEST[self::request_last_name])),
  									self::request_company, urlencode(trim($_REQUEST[self::request_company])),
  									self::request_email, $_REQUEST[self::request_email] 
  									);
  	if (false == ($result = strip_tags(file_get_contents($url)))) {
  		$this->setMessage(kit_msg_service_no_connect);
  		return $this->dlgStart();
  	}
  	// Rueckgabe pruefen
  	$response = array();
  	if ($this->explodeAnswer($result, $response)) {
  		if (isset($response[self::request_status]) && ($response[self::request_status] == self::status_ok) && isset($response[self::request_license_type])) {
  			if (isset($response[self::request_message])) $this->setMessage($response[self::request_message]);
  			return $response[self::request_license_type];
  		}
  		elseif(isset($response[self::request_status]) && ($response[self::request_status] !== self::status_ok) && isset($response[self::request_message])) {
  			$this->setMessage($response[self::request_message]);
  			return false;
  		}
  		else {
  			$this->setMessage(kit_error_undefined);
  			return false;
  		}
  	}
  	else {
  		$error = (strlen($result) > 0) ? sprintf('<p>ERROR: %s</p>', $result) : kit_error_undefined;
  		$this->setMessage($error);
  		return false;
  	}
  	return $this->dlgStart();
  } // checkRegister()
  
} // class kitService
?>