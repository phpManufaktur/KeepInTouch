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

// Checking Requirements
if (defined('LEPTON_VERSION')) {
	// LEPTON
	require_once WB_PATH.'/framework/addon.precheck.inc.php';
	$PRECHECK['PHP_VERSION'] = array('VERSION' => '5.2.0', 'OPERATOR' => '>=');
	$PRECHECK['WB_ADDONS']['dbconnect_le'] = array('VERSION' => '0.64', 'OPERATOR' => '>=');
	if (versionCompare(LEPTON_VERSION, '2.0.0', '<')) {
		$PRECHECK['WB_ADDONS']['dwoo'] = array('VERSION' => '0.11', 'OPERATOR' => '>=');
	}
}
else {
	// WebsiteBaker
	$PRECHECK['WB_VERSION'] = array('VERSION' => '2.8', 'OPERATOR' => '>=');
	$PRECHECK['PHP_VERSION'] = array('VERSION' => '5.2.0', 'OPERATOR' => '>=');
	$PRECHECK['WB_ADDONS'] = array(
		'dbconnect_le'	=> array('VERSION' => '0.64', 'OPERATOR' => '>='),
		'dwoo' => array('VERSION' => '0.10', 'OPERATOR' => '>=')
	);
}

// SPECIAL: check dependencies at runtime but not at installation!
if (file_exists(WB_PATH.'/modules/kit_dirlist/include.php')) {
	$PRECHECK['KIT']['kit_dirlist'] = array('VERSION' => '0.27', 'OPERATOR' => '>=');
}

global $database;
$sql = "SELECT * FROM ".TABLE_PREFIX."settings WHERE name='default_charset'";
$result = $database->query($sql);
if ($result) {
	$data = $result->fetchRow();
	($data['value'] == 'utf-8') ? $status = true : $status = false;
	$PRECHECK['CUSTOM_CHECKS']['Default Charset'] = array('REQUIRED' => 'utf-8', 'ACTUAL' => $data['value'], 'STATUS' => $status);
}


?>