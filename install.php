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

define('KIT_INSTALL_RUNNING', true);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.import.php');
global $admin;

$error = '';

// first install configuration table!
$dbConfig = new dbKITcfg(true);
if ($dbConfig->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbConfig->getError());
}

$dbKITcontact = new dbKITcontact(true);
if ($dbKITcontact->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITcontact->getError());
}

$dbKITcontactAddress = new dbKITcontactAddress(true);
if ($dbKITcontactAddress->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITcontactAddress->getError());
}

$dbKITprovider = new dbKITprovider(true);
if ($dbKITprovider->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITprovider->getError());
}

$dbKITmail = new dbKITmail(true);
if ($dbKITmail->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITmail->getError());
}

$dbKITregister = new dbKITregister(true);
if ($dbKITregister->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITregister->getError());
}

// Install tables for newsletter module
$dbKITnewsletterTemplates = new dbKITnewsletterTemplates(true);
if ($dbKITnewsletterTemplates->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterTemplates->getError());
}

$dbKITnewsletterPreview = new dbKITnewsletterPreview(true);
if ($dbKITnewsletterPreview->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterPreview->getError());
}

$dbKITnewsletterCfg = new dbKITnewsletterCfg(true);
if ($dbKITnewsletterCfg->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterCfg->getError());
}

$dbKITnewsletterArchive = new dbKITnewsletterArchive(true);
if ($dbKITnewsletterArchive->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterArchive->getError());
}

$dbKITnewsletterProcess = new dbKITnewsletterProcess(true);
if ($dbKITnewsletterProcess->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterProcess->getError());
}

$dbCronjobData = new dbCronjobData(true);
if ($dbCronjobData->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbCronjobData->getError());
}
// Blindwerte eintragen
$datas = array(	array(dbCronjobData::field_item => dbCronjobData::item_last_call, dbCronjobData::field_value => ''),
								array(dbCronjobData::field_item => dbCronjobData::item_last_job, dbCronjobData::field_value => ''),
								array(dbCronjobData::field_item => dbCronjobData::item_last_nl_id, dbCronjobData::field_value => ''));
foreach ($datas as $data) {
	if (!$dbCronjobData->sqlInsertRecord($data)) {
		$error .= sprintf('<p>[Installation] %s</p>', $dbCronjobData->getError());
	}
}

$dbCronjobNewsletterLog = new dbCronjobNewsletterLog(true);
if ($dbCronjobNewsletterLog->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbCronjobNewsletterLog->getError());
}

$dbCronjobErrorLog = new dbCronjobErrorLog(true);
if ($dbCronjobErrorLog->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbCronjobErrorLog->getError());
}

$dbImport = new dbKITimport(true);
if ($dbImport->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbImport->getError());
}

$dbLanguages = new dbKITlanguages(true);
if ($dbLanguages->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbLanguages->getError());
}

// create the kit_link table
$SQL = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_kit_links` ( ".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "`url` TEXT NOT NULL, ".
    "`guid` VARCHAR(128) NOT NULL DEFAULT '', ".
    "`type` ENUM('DOWNLOAD','UPLOAD','UNDEFINED') NOT NULL DEFAULT 'UNDEFINED', ".
    "`option` ENUM('THROW-AWAY','PERMANENT') NOT NULL DEFAULT 'THROW-AWAY', ".
    "`status` ENUM('ACTIVE','LOCKED','DELETED') NOT NULL DEFAULT 'ACTIVE', ".
    "`last_call` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', ".
    "`kit_id` INT(11) NOT NULL DEFAULT '-1', ".
    "`timestamp` TIMESTAMP, ".
    "PRIMARY KEY (`id`), ".
    "KEY (`guid`,`kit_id`)".
    ") ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
$database->query($SQL);
if ($database->is_error()) {
  $error .= sprintf('[INSTALLATION kit_links] %s', $database->get_error());
}

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>