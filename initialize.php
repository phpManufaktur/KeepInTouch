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

if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// include language file
if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); 
}

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.tools.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.config.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.contact.php');
require_once(WB_PATH.'/framework/class.wb.php');
require_once(WB_PATH.'/framework/class.admin.php');

// initialize Dwoo
global $parser;
 
if (!is_object($parser)) {
	require_once WB_PATH.'/framework/addon.precheck.inc.php';
	if (!defined('LEPTON_VERSION')) {
		if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/dwoo/include.php');
	} 
	elseif (versionCompare(LEPTON_VERSION, '1.2.0', '<')) {
		if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/dwoo/include.php');
	}
	else {
		if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/lib_dwoo/library.php');
	}
	$parser = new Dwoo();
}


?>