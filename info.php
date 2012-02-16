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

$module_directory     = 'kit';
$module_name          = 'KeepInTouch';
$module_function      = 'tool';
$module_version       = '0.53';
$module_status        = 'beta';
$module_languages	  = 'DE';
$module_platform      = '2.8';
$module_author        = 'phpManufaktur, Berlin (Germany)';
$module_license       = 'GNU General Public License';
$module_description   = 'Contact management for WebsiteBaker';
$module_home          = 'http://phpmanufaktur.de';
$module_guid          = 'B8AF0EA2-26BD-4512-91D4-07B97A2E8DCA';

/**
 * For Version- and Releasenotes please look at info.txt
 */
?>