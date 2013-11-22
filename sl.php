<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2010 - 2012
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

// Include config file
$config_path = '../../config.php';
if (!file_exists($config_path)) {
  die(sprintf('[ %s ] Missing Configuration File ...', __LINE__));
}

require_once($config_path);

// use LEPTON 2.x I18n for access to language files
require_once WB_PATH.'/modules/kit/framework/LEPTON/Helper/I18n.php';

// use LEPTON 2.x I18n for access to language files
if (!class_exists('CAT_Helper_I18n') && !class_exists('LEPTON_Helper_I18n')) {
    require_once WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/framework/LEPTON/Helper/I18n.php';
}

global $I18n;

if (!is_object($I18n)) {
    if (class_exists('CAT_Helper_I18n')) {
        // this is a BlackCat environment
        $I18n = new CAT_Helper_I18n(array('lang' => LANGUAGE));
    }
    else {
        // all other environments
        $I18n = new LEPTON_Helper_I18n(array('lang' => LANGUAGE));
    }
}
else {
    $I18n->addFile('DE.php', WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/');
}

global $database;

// check if the GUID isset
if (!isset($_GET['guid']))
  exit($I18n->translate('[ {{ line }} ] Invalid call, missing GUID!', array('line' => __LINE__)));

$guid = trim($_GET['guid']);

// get the record
$SQL = "SELECT * FROM `".TABLE_PREFIX."mod_kit_links` WHERE `guid`='$guid'";
$query = $database->query($SQL);
if ($database->is_error())
  exit(sprintf('[ %s ] %s', __LINE__, $database->get_error()));

if ($query->numRows() != 1)
  exit($I18n->translate('[ {{ line }} ] Invalid GUID, please contact the webmaster.', array('line' => __LINE__)));

$link = $query->fetchRow(MYSQL_ASSOC);


if ($link['type'] != 'DOWNLOAD')
  exit($I18n->translate('[ {{ line }} ] This is no valid download link, please contact the webmaster.', array('line' => __LINE__)));

if ($link['status'] != 'ACTIVE')
  exit($I18n->translate('[ {{ line }} ] This download link is no longer valid, please contact the webmaster to get a new one!', array('line' => __LINE__)));

$file_path = WB_PATH . substr($link['file_url'], strlen(WB_URL));

if (!file_exists($file_path))
  exit($I18n->translate('[ {{ line }} ] Oooops, missing the requested file. Please contact the webmaster!', array('line' => __LINE__)));

// update the mod_kit_links record
$status = ($link['option'] == 'THROW-AWAY') ? 'LOCKED' : 'ACTIVE';
$count = $link['count'] +1;
$last_call = date('Y-m-d H:i:s');

$SQL = "UPDATE `".TABLE_PREFIX."mod_kit_links` SET `status`='$status', `count`='$count', `last_call`='$last_call' WHERE `guid`='$guid'";
$database->query($SQL);
if ($database->is_error())
  exit(sprintf('[ %s ] %s', __LINE__, $database->get_error()));

// update the KIT contact protocol
$log = $I18n->translate('The contact has downloaded the file <b>{{ file }}</b> with the GUID <b>{{ guid }}</b>.',
    array('file' => basename($link['file_url']), 'guid' => $guid));
$SQL = "INSERT INTO `".TABLE_PREFIX."mod_kit_contact_protocol` (`contact_id`,`protocol_memo`,`protocol_date`,`protocol_type`,`protocol_update_when`) ".
    "VALUES('{$link['kit_id']}', '$log', '$last_call', 'typeMemo', '$last_call')";
$database->query($SQL);
if ($database->is_error())
  exit(sprintf('[ %s ] %s', __LINE__, $database->get_error()));


// start download
header('Content-type: application/force-download');
header('Content-Transfer-Encoding: Binary');
header('Content-length: '.filesize($file_path));
header('Content-disposition: attachment;filename="'.basename($link['file_url']).'"');
readfile($file_path);
exit();
