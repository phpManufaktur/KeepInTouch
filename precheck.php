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

// Checking Requirements
global $database;

$checked = true;

// check PHP version
$PRECHECK['PHP_VERSION'] = array(
    'VERSION' => '5.2.0',
    'OPERATOR' => '>='
);

// check dependencies for KIT at runtime but not at installation!
$PRECHECK['KIT'] = array(
    'kit_dirlist' => array('VERSION' => '0.28', 'OPERATOR' => '>='),
    'kit_form' => array('VERSION' => '0.40', 'OPERATOR' => '>=')
);

// modified precheck array
$check = array(
    'dbConnect_LE' => array(
        'directory' => 'dbconnect_le',
        'version' => '0.68',
        'problem' => 'dbConnect_LE => <b><a href="https://addons.phpmanufaktur.de/download.php?file=dbConnect_LE" target="_blank">Download actual version</a></b>'
        ),
    'wbLib' => array(
        'directory' => 'wblib',
        'version' => '0.65',
        'problem' => 'wbLib => <b><a href="https://github.com/webbird/wblib/downloads" target="_blank">Download actual version</a></b>'
        ),
    'LibraryAdmin' => array(
       'directory' => 'libraryadmin',
       'version' => '1.9',
       'problem' => 'LibraryAdmin => <b><a href="http://jquery.lepton-cms.org/modules/download_gallery/dlc.php?file=75&id=1318585713" target="_blank">Download actual version</a></b>'
        ),
    'libJQuery' => array(
       'directory' => 'lib_jquery',
       'version' => '1.25',
       'problem' => 'libJQuery => <b><a href="http://jquery.lepton-cms.org/modules/download_gallery/dlc.php?file=76&id=1320743410" target="_blank">Download actual version</a></b>'
       ),
);

if (!defined('CAT_VERSION')) {
    $check['Dwoo'] = array(
        'directory' => 'dwoo',
        'version' => '0.17',
        'problem' => 'Dwoo => <b><a href="https://addons.phpmanufaktur.de/download.php?file=Dwoo" target="_blank">Download actual version</a></b>'
    );
}

$versionSQL = "SELECT `version` FROM `".TABLE_PREFIX."addons` WHERE `directory`='%s'";

foreach ($check as $name => $addon) {
  // loop throug the addons and check the versions
  $version = $database->get_one(sprintf($versionSQL, $addon['directory']), MYSQL_ASSOC);
  if (false === ($status = version_compare(!empty($version) ? $version : '0', $addon['version'], '>='))) {
    $checked = false;
    $key = $addon['problem'];
  }
  else
    $key = $name;
  $PRECHECK['CUSTOM_CHECKS'][$key] = array(
      'REQUIRED' => $addon['version'],
      'ACTUAL' => !empty($version) ? $version : '- not installed -',
      'STATUS' => $status
  );
}

// check default charset
$SQL = "SELECT `value` FROM `".TABLE_PREFIX."settings` WHERE `name`='default_charset'";
$charset = $database->get_one($SQL, MYSQL_ASSOC);
if ($charset != 'utf-8') {
  $checked = false;
  $key = 'This addon needs UTF-8 as default charset!';
}
else
  $key = 'UTF-8';

$PRECHECK['CUSTOM_CHECKS'][$key] = array(
    'REQUIRED' => 'utf-8',
    'ACTUAL' => $charset,
    'STATUS' => ($charset == 'utf-8')
);

// jQueryAdmin should be uninstalled
if (file_exists(WB_PATH . '/modules/jqueryadmin/tool.php')) {
  $checked = false;
  $key = 'jQueryAdmin is <b>deprecated</b>, please uninstall and<br />use <b>LibraryAdmin</b> instead!';
}
else
  $key = 'jQueryAdmin';

$PRECHECK['CUSTOM_CHECKS'][$key] = array(
    'REQUIRED' => 'REMOVED',
    'ACTUAL' => (file_exists(WB_PATH . '/modules/jqueryadmin/tool.php')) ? 'INSTALLED' : 'REMOVED',
    'STATUS' => (!file_exists(WB_PATH . '/modules/jqueryadmin/tool.php'))
);

if (!$checked) {
  // if a problem occured prompt a hint and grant that the LEPTON/WB precheck fail
  $PRECHECK['CUSTOM_CHECKS']['Please install or update all required addons.<br />Need help? Please contact the <b><a href="https://phpmanufaktur.de/support" target="_blank">phpManufaktur Support Group</a></b>.'] = array(
      'REQUIRED' => 'OK',
      'ACTUAL' => 'PROBLEM',
      'STATUS' => false
  );
}
