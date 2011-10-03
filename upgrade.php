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

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.import.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.config.php');

require_once(WB_PATH.'/framework/functions.php');

global $database;
global $admin;

$error = '';

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
// check field_distribution --> #0.29
if (!$dbKITcontact->sqlFieldExists(dbKITcontact::field_distribution)) {
  if (!$dbKITcontact->sqlAlterTableAddField(dbKITcontact::field_distribution, "VARCHAR(255) NOT NULL DEFAULT ''", dbKITcontact::field_newsletter)) {
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
// check support for distributions --> #0.29
if (!$dbKITnewsletterArchive->sqlFieldExists(dbKITnewsletterArchive::field_distributions)) {
  if (!$dbKITnewsletterArchive->sqlAlterTableAddField(dbKITnewsletterArchive::field_distributions, "VARCHAR(255) NOT NULL DEFAULT ''", dbKITnewsletterArchive::field_groups)) {
 		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITnewsletterArchive->getError()); 	
  }
}		


$dbKITnewsletterProcess = new dbKITnewsletterProcess();
if (!$dbKITnewsletterProcess->sqlTableExists()) {
	if (!$dbKITnewsletterProcess->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbKITnewsletterProcess->getError());
	}
}
// check process for distributions --> #0.29
if (!$dbKITnewsletterProcess->sqlFieldExists(dbKITnewsletterProcess::field_distribution_ids)) {
  if (!$dbKITnewsletterProcess->sqlAlterTableAddField(dbKITnewsletterProcess::field_distribution_ids, "VARCHAR(255) NOT NULL DEFAULT ''", dbKITnewsletterProcess::field_register_ids)) {
 		$error .= sprintf('<p>[Upgrade] %s </p>', $dbKITnewsletterProcess->getError()); 	
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

/*
$dbNewsletterLinks = new dbKITnewsletterLinks();
if (!$dbNewsletterLinks->sqlTableExists()) {
	if (!$dbNewsletterLinks->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbNewsletterLinks->getError());
	}
}
*/

$dbImport = new dbKITimport();
if (!$dbImport->sqlTableExists()) {
	if (!$dbImport->sqlCreateTable()) {
		$error .= sprintf('<p>[Upgrade] %s</p>', $dbImport->getError());
	}
}

/**
 * BUGFIX correct a problem of KIT < 0.34 with duplicate active entries for the same e-mail address within dbKITregister  
 */
$SQL = sprintf("SELECT %s, %s, COUNT(*) AS cnt FROM %s WHERE %s='%s' GROUP BY %s HAVING cnt>1",
								dbKITregister::field_contact_id,
								dbKITregister::field_id,
								$dbRegister->getTableName(),
								dbKITregister::field_status,
								dbKITregister::status_active,
								dbKITregister::field_email);
if (!$dbKITregister->sqlExec($SQL, $registers)) {
	$error .= sprintf('<p>[BUGFIX] %s</p>', $dbKITregister->getError());
}
else {
	foreach ($registers as $register) {
		$SQL = sprintf( "SELECT %s FROM %s WHERE %s='%s' AND %s='%s'",
										dbKITcontact::field_id,
										$dbContact->getTableName(),
										dbKITcontact::field_id,
										$register[dbKITregister::field_contact_id],
										dbKITcontact::field_status,
										dbKITcontact::status_deleted);
		$contacts = array();
		if (!$dbKITcontact->sqlExec($SQL, $contacts)) {
			$error .= sprintf('<p>[BUGFIX] %s</p>', __METHOD__, __LINE__, $dbKITcontact->getError());
		}
		else {
			if (count($contacts) > 0) {
				$where = array(dbKITregister::field_id => $register[dbKITregister::field_id]);
				$data = array(
					dbKITregister::field_status 			=> dbKITregister::status_deleted,
					dbKITregister::field_update_by		=> 'UPDATE FIXUP',
					dbKITregister::field_update_when	=> date('Y-m-d H:i:s')
				);
				if (!$dbKITregister->sqlUpdateRecord($data, $where)) {
					$error .= sprintf('<p>[BUGFIX] %s</p>', $dbKITregister->getError());
				}
			}
		}
	}
}

/**
 * Release 0.43
 * Remove /dialogs, /droplets, kit.php, class.request.php and class.repsonse.php and use kitForms instead 
 */

// remove mod_kit_newsletter_links
$SQL = sprintf("DROP TABLE IF EXISTS %smod_kit_newsletter_links", TABLE_PREFIX);
$database->query($SQL);
if ($database->is_error()) {
	$error .= sprintf('<p>[DROP TABLE mod_kit_newsletter_links] %s</p>', $database->get_error());
}

// remove Droplet kit_newsletter
$SQL = sprintf("DELETE FROM %smod_droplets WHERE name='kit_newsletter'", TABLE_PREFIX);
$database->query($SQL);
if ($database->is_error()) {
	$error .= sprintf('<p>[DELETE DROPLET kit_newsletter] %s</p>', $database->get_error());
}

// delete no longer needed entries from mod_kit_config
$SQL = sprintf("DELETE FROM %smod_kit_config WHERE cfg_name IN ('cfgLicenseKey','cfgKITResponsePage','cfgUseCaptcha','cfgUseCustomFiles','cfgRegisterDlgNL','cfgRegisterDlgACC','cfgRegisterDlgUSUB','cfgKITRequestLink','cfgMaxInvalidLogin','cfgMinPwdLen')", TABLE_PREFIX);
$database->query($SQL);
if ($database->is_error()) {
	$error .= sprintf('<p>[DELETE ENTRIES FROM mod_kit_config] %s</p>', $database->get_error());
}

// delete no longer needed files and directories
$delete_array = array('kit.php', 'class.newsletter.link.php', 'class.response.php', 'class.request.php', 'droplets', 'class.droplets.php', 'class.dialogs.php', 'dialogs');
foreach ($delete_array as $item) {
	if (file_exists(WB_PATH.'/modules/kit/'.$item)) {
		if (!rm_full_dir(WB_PATH.'/modules/kit/'.$item)) {
			$error .= sprintf('<p>[DELETE FILES/DIRECTORIES] Can\'t delete /modules/kit/%s</p>', $item);
		}
	}
}

/**
 * The Droplet kit_newsletter is since Release 0.43 no longer used!

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

*/

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>