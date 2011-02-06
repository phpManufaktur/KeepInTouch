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

/**
 * Definition fuer MASSMAIL
 * Wird fuer den Import von MASSMAIL Adressen benoetigt
 *
 */
class dbMassMailAddresses extends dbConnectLE {
	
	const field_group_id				= 'group_id';
	const field_mail_to					= 'mail_to';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_massmail_addresses');
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL DEFAULT '0'", true);
		$this->addFieldDefinition(self::field_mail_to, "TEXT NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbMassmailAdresses

/**
 * Definition fuer MASSMAIL
 * Wird fuer den Import von MASSMAIL Adressen benoetigt
 *
 */
class dbMassMailGroups extends dbConnectLE {
	
	const field_group_id				= 'group_id';
	const field_group_name			= 'group_name';
	const field_mail_to					= 'mail_to';
	const field_wb_group				= 'wb_group';
	const field_wb_group_id			= 'wb_group_id';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_massmail_groups');
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_group_name, "TINYTEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_mail_to, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_wb_group, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_wb_group_id, "INT(11) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbMassmailGroups

/**
 * Definition fuer NEWSLETTERSNIPPET
 * Wird fuer den Import von NEWSLETTERSNIPPET Adressen benoetigt
 *
 */
class dbNewsletterSnippet extends dbConnectLE {
	
	const field_id							= 'id';
	const field_email						= 'email';
	const field_checksum				= 'checkSum';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_newsletter');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_email, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_checksum, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbNewsletterSnippet

class kitImportDialog {
	
	const request_action				= 'impact';
	const request_command				= 'impcmd';
	
	const action_default				= 'def';
	const action_start					= 'start';					
	
	private $page_link 							= '';
	private $img_url								= '';
	private $template_path					= '';
	private $help_path							= '';
	private $error									= '';
	private $message								= '';
	
	private $swNavHide							= array();
	private $overwriteNavigation		= '';
	
	public function __construct() {
		$this->page_link = sprintf(	'%s/admintools/tool.php?tool=kit&%s=%s&%s=%s',
																ADMIN_URL,
																kitBackend::request_action,
																kitBackend::action_cfg,
																kitBackend::request_cfg_tab,
																kitBackend::action_cfg_tab_import);
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
  	case self::action_start:
  	case self::action_default:
  	default:
  		return $this->show(self::action_start, $this->dlgImportStart());
  		break;
  	endswitch;
  	return true;
  } // action();
  
  /**
   * Erstellt eine Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
  	$result = '';
  	// voreingestellen Navigationstab ueberschreiben?
  	if (!empty($this->overwriteNavigation)) $action = $this->overwriteNavigation;
  	foreach ($this->tab_navigation_array as $key => $value) {
  		if (!in_array($key, $this->swNavHide)) {
	  		($key == $action) ? $selected = ' class="selected"' : $selected = ''; 
	  		$result .= sprintf(	'<li%s><a href="%s">%s</a></li>', 
	  												$selected,
	  												sprintf('%s&%s=%s', $this->page_link, self::request_action, $key),
	  												$value
	  												);
  		}
  	}
  	$result = sprintf('<ul class="nav_tab">%s</ul>', $result);
  	return $result;
  } // getNavigation()
  
  
  /**
   * Ausgabe des formatierten Ergebnis mit Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @param $content - Inhalt
   * 
   * @return STR RESULT
   */
  public function show($action, $content) {
  	global $parser;
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = ' class="error"';
  	}
  	else {
  		$class = '';
  	}
  	$data = array(
  		'WB_URL'					=> WB_URL,
  		'navigation' 			=> '', //$this->getNavigation($action),
  		'class'						=> $class,
  		'content'					=> $content,
  	);
  	return $parser->get($this->template_path.'backend.body.htt', $data);
  } // show()
	
  public function dlgImportStart() {
  	return "Start Import";
  } // dlgImportStart()
	
} // class kitImportDialog

?>