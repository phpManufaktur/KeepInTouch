<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2010, Ralf Hertsch
  Contact me: ralf.hertsch@phpManufaktur.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id$
  
**/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');

global $admin;

$error = '';

$dbKITdlgRegister = new dbKITdialogsRegister();
if (!$dbKITdlgRegister->sqlTableExists()) {
	if (!$dbKITdlgRegister->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITdlgRegister->getError());
	}
}

$dbKITregister = new dbKITregister();
if (!$dbKITregister->sqlTableExists()) {
	if (!$dbKITregister->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITregister->getError());
	}
}

$dbKITprovider = new dbKITprovider();
if (!$dbKITprovider->sqlFieldExists(dbKITprovider::field_identifier)) {
	if (!$dbKITprovider->sqlAlterTableAddField(dbKITprovider::field_identifier, "VARCHAR (50) NOT NULL DEFAULT ''", dbKITprovider::field_name)) {
		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITprovider->getError());
	}
}

$dbKITcontact = new dbKITcontact();
if (!$dbKITcontact->sqlFieldExists(dbKITcontact::field_newsletter)) {
	if (!$dbKITcontact->sqlAlterTableAddField(dbKITcontact::field_newsletter, "VARCHAR (255) NOT NULL DEFAULT ''", dbKITcontact::field_category)) {
		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITcontact->getError());
	}
}

// install tables for newsletter module
$dbKITnewsletterTemplates = new dbKITnewsletterTemplates();
if (!$dbKITnewsletterTemplates->sqlTableExists()) {
	if (!$dbKITnewsletterTemplates->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterTemplates->getError());
	}
}

$dbKITnewsletterPreview = new dbKITnewsletterPreview();
if (!$dbKITnewsletterPreview->sqlTableExists()) {
	if (!$dbKITnewsletterPreview->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterPreview->getError());
	}
}

$dbKITnewsletterCfg = new dbKITnewsletterCfg();
if (!$dbKITnewsletterCfg->sqlTableExists()) {
	if (!$dbKITnewsletterCfg->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterCfg->getError());
	}
}

$dbKITnewsletterArchive = new dbKITnewsletterArchive();
if (!$dbKITnewsletterArchive->sqlTableExists()) {
	if (!$dbKITnewsletterArchive->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterArchive->getError());
	}
}

$dbKITnewsletterProcess = new dbKITnewsletterProcess();
if (!$dbKITnewsletterProcess->sqlTableExists()) {
	if (!$dbKITnewsletterProcess->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterProcess->getError());
	}
}

$dbCronjobData = new dbCronjobData();
if (!$dbCronjobData->sqlTableExists()) {
	if (!$dbCronjobData->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbCronjobData->getError());
	}
	else {
		// Blindwerte eintragen
		$datas = array(	array(dbCronjobData::field_item => dbCronjobData::item_last_call, dbCronjobData::field_value => ''), 
										array(dbCronjobData::field_item => dbCronjobData::item_last_job, dbCronjobData::field_value => ''),
										array(dbCronjobData::field_item => dbCronjobData::item_last_nl_id, dbCronjobData::field_value => ''));
		foreach ($datas as $data) {
			if (!$dbCronjobData->sqlInsertRecord($data)) {
				$error .= sprintf('<p>[Installation] %s</p>', $dbCronjobData->getError());
			}
		}				
	}
}

$dbCronjobNewsletterLog = new dbCronjobNewsletterLog();
if (!$dbCronjobNewsletterLog->sqlTableExists()) {
	if (!$dbCronjobNewsletterLog->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbCronjobNewsletterLog->getError());
	}
}

$dbCronjobErrorLog = new dbCronjobErrorLog();
if (!$dbCronjobErrorLog->sqlTableExists()) {
	if (!$dbCronjobErrorLog->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbCronjobErrorLog->getError());
	}
}

// Install Droplets
$droplets = new checkDroplets();
if ($droplets->insertDropletsIntoTable()) {
  $message = 'The Droplets for dbKeepInTouch where successfully installed! Please look at the Help for further informations.';
}
else {
  $message = 'The installation of the Droplets for dbKeepInTouch failed. Error: '. $droplets->getError();
}
if ($message != "") {
  echo '<script language="javascript">alert ("'.$message.'");</script>';
}

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>