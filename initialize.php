<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2010 - 2012
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
  if (defined('LEPTON_VERSION'))
    include(WB_PATH.'/framework/class.secure.php');
}
else {
  $oneback = "../";
  $root = $oneback;
  $level = 1;
  while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
    $root .= $oneback;
    $level += 1;
  }
  if (file_exists($root.'/framework/class.secure.php')) {
    include($root.'/framework/class.secure.php');
  }
  else {
    trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
  }
}
// end include class.secure.php

if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// use LEPTON 2.x I18n for access to language files
if (!class_exists('LEPTON_Helper_I18n')) require_once WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/framework/LEPTON/Helper/I18n.php';
global $I18n;
if (!is_object($I18n)) {
  $I18n = new LEPTON_Helper_I18n(array('lang' => LANGUAGE));
}
else {
  $I18n->addFile('DE.php', WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/');
}

// load language depending onfiguration
if (!file_exists(WB_PATH.'/modules/' . basename(dirname(__FILE__)) . '/languages/' . LANGUAGE . '.cfg.php')) {
  require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/DE.cfg.php');
} else {
  require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.cfg.php');
}
if (!file_exists(WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php')) {
  if (! defined('KIT_LANGUAGE')) define('KIT_LANGUAGE', 'DE'); // important: language flag is used by template selection
} else {
  if (! defined('KIT_LANGUAGE')) define('KIT_LANGUAGE', LANGUAGE);
}


// include the OLD language file - needed as long not complete switched to I18n !!!
if (!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/old.' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/old.DE.php');
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/old.' .LANGUAGE .'.php');
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

if (!class_exists('Dwoo'))
  require_once WB_PATH.'/modules/dwoo/include.php';

// initialize the template engine
global $parser;
if (!is_object($parser)) {
  $cache_path = WB_PATH.'/temp/cache';
  if (!file_exists($cache_path)) mkdir($cache_path, 0755, true);
  $compiled_path = WB_PATH.'/temp/compiled';
  if (!file_exists($compiled_path)) mkdir($compiled_path, 0755, true);
  $parser = new Dwoo($compiled_path, $cache_path);
}
