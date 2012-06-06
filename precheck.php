<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2012 - phpManufaktur by Ralf Hertsch
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License (GPL)
 * @version $Id$
 *
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
  if (defined('LEPTON_VERSION')) include (WB_PATH . '/framework/class.secure.php');
}
else {
  $oneback = "../";
  $root = $oneback;
  $level = 1;
  while (($level < 10) && (!file_exists($root . '/framework/class.secure.php'))) {
    $root .= $oneback;
    $level += 1;
  }
  if (file_exists($root . '/framework/class.secure.php')) {
    include ($root . '/framework/class.secure.php');
  }
  else {
    trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
  }
}
// end include class.secure.php

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

// SPECIAL: check dependency at runtime but not at installation!
$PRECHECK['KIT']['kit_dirlist'] = array('VERSION' => '0.28', 'OPERATOR' => '>=');

global $database;
$sql = "SELECT * FROM ".TABLE_PREFIX."settings WHERE name='default_charset'";
$result = $database->query($sql);
if ($result) {
	$data = $result->fetchRow();
	($data['value'] == 'utf-8') ? $status = true : $status = false;
	$PRECHECK['CUSTOM_CHECKS']['Default Charset'] = array('REQUIRED' => 'utf-8', 'ACTUAL' => $data['value'], 'STATUS' => $status);
}


?>