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

if ('á' != "\xc3\xa1") {
	// important: language files must be saved as UTF-8 (without BOM)
	trigger_error('The language file <b>'.basename(__FILE__).'</b> is damaged, it must be saved <b>UTF-8</b> encoded!', E_USER_ERROR);
}

if (!defined('CFG_CURRENCY'))
    define('CFG_CURRENCY', '$ %s');
if (!defined('CFG_DATE_SEPARATOR'))
    define('CFG_DATE_SEPARATOR', '/');
if (!defined('CFG_DATE_STR'))
    define('CFG_DATE_STR', 'm/d/Y');
if (!defined('CFG_DATETIME_STR'))
    define('CFG_DATETIME_STR', 'm/d/Y h:i a');
if (!defined('CFG_DAY_NAMES'))
    define('CFG_DAY_NAMES', "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday");
if (!defined('CFG_DECIMAL_SEPARATOR'))
    define('CFG_DECIMAL_SEPARATOR', '.');
if (!defined('CFG_MONTH_NAMES'))
    define('CFG_MONTH_NAMES', "January,February,March,April,May,June,July,August,September,October,November,December");
if (!defined('CFG_THOUSAND_SEPARATOR'))
    define('CFG_THOUSAND_SEPARATOR', ',');
if (!defined('CFG_TIME_LONG_STR'))
    define('CFG_TIME_LONG_STR', 'h:i:s a');
if (!defined('CFG_TIME_STR'))
    define('CFG_TIME_STR', 'h:i a');
if (!defined('CFG_TIME_ZONE'))
    define('CFG_TIME_ZONE', 'America/Chicago');
if (!defined('CFG_TITLE'))
    define('CFG_TITLE', 'Mister,Lady');
