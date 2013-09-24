<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2010 - 2013
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

$module_directory = 'kit';
$module_name = 'KeepInTouch';
$module_function = 'tool';
$module_version = '0.73';
$module_status = 'stable';
$module_languages = 'DE';
$module_platform = '2.8';
$module_author = 'Ralf Hertsch, Berlin (Germany)';
$module_license = 'MIT License (MIT)';
$module_description = 'Customer Relationship Management (CRM)';
$module_home = 'https://addons.phpmanufaktur.de/KeepInTouch';
$module_guid = 'B8AF0EA2-26BD-4512-91D4-07B97A2E8DCA';
