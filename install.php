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
  
  $Id: install.php 47 2010-07-17 02:41:57Z ralf $
  
**/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');

global $admin;

$error = '';

// first install configuration table!
$dbConfig = new dbKITcfg(true);
if ($dbConfig->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbConfig->getError());
}
if ($dbConfig->isConnected) {
	$dbConfig->close();
	$dbConfig->isConnected = false;
}

$dbKITcontact = new dbKITcontact(true);
if ($dbKITcontact->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITcontact->getError());
}
if ($dbKITcontact->isConnected) {
	$dbKITcontact->close();
	$dbKITcontact->isConnected = false;
}

$dbKITcontactAddress = new dbKITcontactAddress(true);
if ($dbKITcontactAddress->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITcontactAddress->getError());
}
if ($dbKITcontactAddress->isConnected) {
	$dbKITcontactAddress->close();
	$dbKITcontactAddress->isConnected = false;
}

$dbKITprovider = new dbKITprovider(true);
if ($dbKITprovider->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITprovider->getError());
}
if ($dbKITprovider->isConnected) {
	$dbKITprovider->close();
	$dbKITprovider->isConnected = false;
}

$dbKITmail = new dbKITmail(true);
if ($dbKITmail->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITmail->getError());
}
if ($dbKITmail->isConnected) {
	$dbKITmail->close();
	$dbKITmail->isConnected = false;
}

$dbKITdlgRegister = new dbKITdialogsRegister(true);
if ($dbKITdlgRegister->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITdlgRegister->getError());
}
if ($dbKITdlgRegister->isConnected) {
	$dbKITdlgRegister->close();
	$dbKITdlgRegister->isConnected = false;
}

$dbKITregister = new dbKITregister(true);
if ($dbKITregister->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITregister->getError());
}
if ($dbKITregister->isConnected) {
	$dbKITregister->close();
	$dbKITregister->isConnected = false;
}

// Install tables for newsletter module
$dbKITnewsletterTemplates = new dbKITnewsletterTemplates(true);
if ($dbKITnewsletterTemplates->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterTemplates->getError());
}
if ($dbKITnewsletterTemplates->isConnected) {
	$dbKITnewsletterTemplates->close();
	$dbKITnewsletterTemplates->isConnected = false;
}

$dbKITnewsletterPreview = new dbKITnewsletterPreview(true);
if ($dbKITnewsletterPreview->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterPreview->getError());
}
if ($dbKITnewsletterPreview->isConnected) {
	$dbKITnewsletterPreview->close();
	$dbKITnewsletterPreview->isConnected = false;
}

$dbKITnewsletterCfg = new dbKITnewsletterCfg(true);
if ($dbKITnewsletterCfg->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterCfg->getError());
}
if ($dbKITnewsletterCfg->isConnected) {
	$dbKITnewsletterCfg->close();
	$dbKITnewsletterCfg->isConnected = false;
}

$dbKITnewsletterArchive = new dbKITnewsletterArchive(true);
if ($dbKITnewsletterArchive->isError()) {
	$error .= sprintf('<p>[Installation] %s</p>', $dbKITnewsletterArchive->getError());
}
if ($dbKITnewsletterArchive->isConnected) {
	$dbKITnewsletterArchive->close();
	$dbKITnewsletterArchive->isConnected = false;
}

// Install Droplets
$droplets = new checkDroplets();
if ($droplets->insertDropletsIntoTable()) {
  $message = 'The Droplets for dbKeepInTouch where successfully installed! Please look at the Help for further informations.';
}
else {
  $message = 'The installation of the Droplets for KeepInTouch failed. Error: '. $droplets->getError();
}
if ($message != "") {
  echo '<script language="javascript">alert ("'.$message.'");</script>';
}
	
// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}
	
?>