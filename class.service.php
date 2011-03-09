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
	
	const action_default							= 'def';
	
	private $page_link 								= '';
	private $img_url									= '';
	private $template_path						= '';
	private $help_path								= '';
	private $error										= '';
	private $message									= '';
	
	public function __construct() {
		global $dbCfg;
		$this->page_link = ADMIN_URL.'/admintools/tool.php?tool=kit';
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->help_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/languages/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';
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
  
  /**
   * Show Informations about KeepInTouch
   * @return STR Information
   */
  private function getKITinfo() {
  	global $parser;
  	$data = array(
  		'logo' 			=> $this->img_url.'keepintouch-298-55.png',
  		'release'		=> sprintf('Release %01.2f', $this->getRelease()),
  		'register'	=> ''
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
   
} // class kitService
?>