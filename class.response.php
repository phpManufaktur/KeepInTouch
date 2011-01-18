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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.request.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

global $parser;
if (!is_object($parser)) $parser = new Dwoo();

class kitResponse {
	
	private $template_path;
	private $img_url;
	
	function __construct() {
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';		
	} // __construct()
		
  /**
   * REQUEST action handler
   */
  public function action() { 	
  	
  	// Important: get $_REQUEST vars from $_SESSION...
  	foreach ($_SESSION as $key => $value) {
  		if (strpos($key, 'kit7543_') !== false) {
  			$new_key = str_replace('kit7543_', '', $key);
  			$_REQUEST[$new_key] = $value;
  			unset($_SESSION[$key]);
  		}
  	}
 		// use $_GET!
		isset($_REQUEST[kitRequest::request_action]) ? $action = $_REQUEST[kitRequest::request_action] : $action = kitRequest::action_none; 
		switch($action):
  	case kitRequest::action_login:
  	case kitRequest::action_unsubscribe:	
  	case kitRequest::action_activate_key:
  		// Dialog zur Aktivierung anzeigen
  		$config = new dbKITcfg();
  		$registerDataDlg = $config->getValue(dbKITcfg::cfgRegisterDlgACC);
  		
  		$dbDlgRegister = new dbKITdialogsRegister();
  		$where = array();
  		$where[dbKITdialogsRegister::field_name] = $registerDataDlg;
  		$dialog = array();
  		if (!$dbDlgRegister->sqlSelectRecord($where, $dialog)) {
  			$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
  			break;
  		}
  		if (count($dialog) < 1) {
  			// Dialog nicht in der Datenbank gefunden
  			if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.strtolower($registerDataDlg).'/'.strtolower($registerDataDlg).'.php')) {
  				$dialog = array();
  				$dialog[dbKITdialogsRegister::field_name] = $registerDataDlg;
  				$dialog_id = -1;
  				if (!$dbDlgRegister->sqlInsertRecord($dialog, $dialog_id)) {
  					$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
  					break;
  				}
  				$dialog[dbKITdialogsRegister::field_id] = $dialog_id;
  			}
  			else {
  				$this->promptError(sprintf(kit_error_request_dlg_invalid_name, $registerDataDlg));
  				break;
  			}
  		}
  		else {
  			$dialog = $dialog[0];
  		}
  		if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.strtolower($dialog[dbKITdialogsRegister::field_name]).'/'.strtolower($dialog[dbKITdialogsRegister::field_name]).'.php')) {
  			require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.strtolower($dialog[dbKITdialogsRegister::field_name]).'/'.strtolower($dialog[dbKITdialogsRegister::field_name]).'.php');
				$callDialog = new $dialog[dbKITdialogsRegister::field_name];
				$callDialog->setDlgID((int) $dialog[dbKITdialogsRegister::field_id]);
				$callDialog->action();
			}
			else {
				$this->promptError(sprintf(kit_error_dlg_missing, $dialog[dbKITdialogsRegister::field_name]));
			}
  		break;
  	case kitRequest::action_dialog:
  		// Dialog anzeigen
  		if (!isset($_REQUEST[kitRequest::request_dialog])) {
  			// keine Dialog ID angegeben
  			$this->promptError(sprintf(kit_error_request_missing_parameter, kitRequest::request_dialog));
  			break;
  		}
  		$dbDlgRegister = new dbKITdialogsRegister();
  		$where = array();
  		$where[dbKITdialogsRegister::field_id] = (int) $_REQUEST[kitRequest::request_dialog];
  		$dialog = array();
  		if (!$dbDlgRegister->sqlSelectRecord($where, $dialog)) {
  			$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
  			break;
  		}
  		if (count($dialog) < 1) {
  			// Dialog nicht gefunden
  			$this->promptError(sprintf(kit_error_request_dlg_invalid_id, (int) $_REQUEST[kitRequest::request_dialog]));
  			break;
  		}
  		$dialog = $dialog[0][dbKITdialogsRegister::field_name];
  		$lowerDialog = strtolower($dialog);
  		if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.$lowerDialog.'/'.$lowerDialog.'.php')) {
  			require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.$lowerDialog.'/'.$lowerDialog.'.php');
				$callDialog = new $dialog;
				$callDialog->setDlgID((int) $_REQUEST[kitRequest::request_dialog]);
				echo $callDialog->action();
			}
			else {
				$this->promptError(sprintf(kit_error_dlg_missing, $dialog));
			}
  		break;
  	case kitRequest::action_error: 
  		// Fehlermeldung ausgeben
  		$this->promptError($_REQUEST[kitRequest::request_message]);
  		break;
  	default:
  		// keine Aktion angefordert oder fehlende Parameter...
  		$this->promptError(kit_error_request_no_action);
  		break;
  	endswitch;
  }
  
  public function promptError($message) {
  	global $parser;
  	$data = array(
  		'header'				=> kit_header_error,
  		'error'					=> $message,
  		'error_hint'		=> kit_hint_error_msg
  	);
  	echo $parser->get($this->template_path.'frontend.error.htt', $data);
  } // getErrorPrompt()
  
} // class kitResponse

?>