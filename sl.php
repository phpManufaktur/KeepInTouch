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

global $database;

// check if the GUID isset
if (!isset($_GET['guid']))
  exit(sprintf('[ %s ] Invalid call, missing GUID!', __LINE__));

$guid = trim($_GET['guid']);

// get the record
$SQL = "SELECT * FROM `".TABLE_PREFIX."mod_kit_links` WHERE `guid`='$guid'";
$query = $database->query($SQL);
if ($database->is_error())
  exit(sprintf('[ %s ] %s', __LINE__, $database->get_error()));

if ($query->numRows() != 1)
  exit(sprintf('[ %s ] Invalid GUID, please contact the webmaster.', __LINE__));

$link = $query->fetchRow(MYSQL_ASSOC);

if ($link['type'] != 'DOWNLOAD')
  exit(sprintf('[ %s ] This is no valid download link, please contact the webmaster.', __LINE__));

if ($link['status'] != 'ACTIVE')
  exit(sprintf('[ %s ] This download link is no longer valid, please contact the webmaster to get a new one!', __LINE__));

$file_path = WB_PATH . substr($link['file_url'], strlen(WB_URL));

if (!file_exists($file_path))
  exit(sprintf('[ %d ] Oooops, missing the requested file. Please contact the webmaster!'));

// update the record
$status = ($link['option'] == 'THROW-AWAY') ? 'LOCKED' : 'ACTIVE';
$count = $link['count'] +1;
$last_call = date('Y-m-d H:i:s');

$SQL = "UPDATE `".TABLE_PREFIX."mod_kit_links` SET `status`='$status', `count`='$count', `last_call`='$last_call' WHERE `guid`='$guid'";
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
