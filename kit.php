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


// Include config file
$config_path = dirname(__FILE__).'/config.php';
if (!file_exists($config_path)) {
	// Vermutung: kit.php befindet sich im /modules/kit Verzeichnis
	$config_path = '../../config.php';
	if (!file_exists($config_path)) {
		die("<b>".$_SERVER['SCRIPT_NAME']."</b> was not able to access the WebsiteBaker Configuration file."); 
	}
}
require_once($config_path);
if (file_exists(WB_PATH.'/modules/kit/class.request.php')) {
	// call KIT request handler...
	require_once(WB_PATH.'/modules/kit/class.request.php');
	$request = new kitRequest();
	$request->action();
}
else {
	die("Invalid call of <b>".$_SERVER['SCRIPT_NAME']."</b>!");
}
?>