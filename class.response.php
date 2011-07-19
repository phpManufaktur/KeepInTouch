<?php

/**
 * KeepInTouch (KIT)
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.request.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.link.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

global $parser;
global $dbNewsletterLinks;
global $dbCfg;

if (!is_object($parser)) $parser = new Dwoo();
if (!is_object($dbNewsletterLinks)) $dbNewsletterLinks = new dbKITnewsletterLinks();
if (!is_object($dbCfg)) $dbCfg = new dbKITcfg();

class kitResponse {
	
	private $template_path;
	private $img_url;
	
	function __construct() {
		global $dbCfg;
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';
	} // __construct()
		
  /**
   * REQUEST action handler
   */
  public function action() { 	
  	
  	global $dbNewsletterLinks;
  	
  	// Important: get $_REQUEST vars from $_SESSION...
  	foreach ($_SESSION as $key => $value) {
  		if (strpos($key, KIT_SESSION_ID) !== false) {
  			$new_key = str_replace(KIT_SESSION_ID, '', $key);
  			$_REQUEST[$new_key] = $value;
  			unset($_SESSION[$key]);
  		}
  	}
		isset($_REQUEST[kitRequest::request_action]) ? $action = $_REQUEST[kitRequest::request_action] : $action = kitRequest::action_none; 

		switch($action):
  	case kitRequest::action_login:
  	case kitRequest::action_unsubscribe:	
  	case kitRequest::action_activate_key:
  		// Dialog zur Aktivierung anzeigen
  		$this->execDialog(dbKITcfg::cfgRegisterDlgACC);
  		break;
  	case kitRequest::action_dialog:
  		// Dialog anzeigen
  		if (!isset($_REQUEST[kitRequest::request_dialog])) {
  			// keine Dialog ID angegeben
  			$this->promptError(sprintf(kit_error_request_missing_parameter, kitRequest::request_dialog));
  			break;
  		}
  		$dbDlgRegister = new dbKITdialogsRegister();
  		$where = array(dbKITdialogsRegister::field_id => (int) $_REQUEST[kitRequest::request_dialog]);
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
  	case kitRequest::action_link: 
  		// Link in einen Befehl umwandeln
  		if (!isset($_REQUEST[kitRequest::request_link])) {
  			$this->promptError(kit_error_request_link_invalid);
  			break;
  		}
  		$link_value = $_REQUEST[kitRequest::request_link];
  		$where = array(
  			dbKITnewsletterLinks::field_link_value => $link_value
  		);
			$link = array();
			if (!$dbNewsletterLinks->sqlSelectRecord($where, $link)) {
				$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
				break;
			}  		
			if (count($link) < 1) {
				$this->promptError(sprintf(kit_error_request_link_unknown, $link_value));
				break;
			}
			switch ($link[0][dbKITnewsletterLinks::field_type]):
  		case dbKITnewsletterLinks::type_link_unsubscribe:
  			// Dialog zur Abmeldung vom Newsletter anzeigen
  			$this->execDialog(dbKITcfg::cfgRegisterDlgUSUB);  			
  			break;
  		default:
  			// keine Aktion festgelegt...
  			$this->promptError(sprintf(kit_error_request_link_action_unknown, $link[0][dbKITnewsletterLinks::field_type]));
  			break; 
			endswitch;
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
  
  public function execDialog($dialog_id) {
  	// Dialog mit der ID  $dialog_id anzeigen
  	$config = new dbKITcfg();
  	$registerDataDlg = $config->getValue($dialog_id);
  		
  	$dbDlgRegister = new dbKITdialogsRegister();
  	$where = array();
  	$where[dbKITdialogsRegister::field_name] = $registerDataDlg;
  	$dialog = array();
  	if (!$dbDlgRegister->sqlSelectRecord($where, $dialog)) {
  		$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
  		return false;
  	}
  	if (count($dialog) < 1) {
  		// Dialog nicht in der Datenbank gefunden
  		if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/dialogs/'.strtolower($registerDataDlg).'/'.strtolower($registerDataDlg).'.php')) {
  			$dialog = array();
  			$dialog[dbKITdialogsRegister::field_name] = $registerDataDlg;
  			$dialog_id = -1;
  			if (!$dbDlgRegister->sqlInsertRecord($dialog, $dialog_id)) {
  				$this->promptError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDlgRegister->getError()));
  				return false;
  			}
  			$dialog[dbKITdialogsRegister::field_id] = $dialog_id;
  		}
  		else {
  			$this->promptError(sprintf(kit_error_request_dlg_invalid_name, $registerDataDlg));
  			return false;
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
			return true;
		}
		else {
			$this->promptError(sprintf(kit_error_dlg_missing, $dialog[dbKITdialogsRegister::field_name]));
			return false;
		}	  		
  } // execDialog()
  
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