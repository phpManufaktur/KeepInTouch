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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');

global $admin;

$error = '';

$dbConfig = new dbKITcfg();
if ($dbConfig->sqlTableExists()) {
	if (!$dbConfig->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbConfig->getError());
	}
}
if ($dbConfig->isConnected) {
	$dbConfig->close();
	$dbConfig->isConnected = false;
}

$dbContact = new dbKITcontact();
if ($dbContact->sqlTableExists()) {
	if (!$dbContact->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContact->getError());
	}
}
if ($dbContact->isConnected) {
	$dbContact->close();
	$dbContact->isConnected = false;
}

$dbContactArray = new dbKITcontactArrayCfg();
if ($dbContactArray->sqlTableExists()) {
	if (!$dbContactArray->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContactArray->getError());
	}
}
if ($dbContactArray->isConnected) {
	$dbContactArray->close();
	$dbContactArray->isConnected = false;
}

$dbContactAddress = new dbKITcontactAddress();
if ($dbContactAddress->sqlTableExists()) {
	if (!$dbContactAddress->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContactAddress->getError());
	}
}
if ($dbContactAddress->isConnected) {
	$dbContactAddress->close();
	$dbContactAddress->isConnected = false;
}

$dbCountries = new dbKITcountries();
if ($dbCountries->sqlTableExists()) {
	if (!$dbCountries->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbCountries->getError());
	}
}
if ($dbCountries->isConnected) {
	$dbCountries->close();
	$dbCountries->isConnected = false;
}

$dbMemos = new dbKITmemos();
if ($dbMemos->sqlTableExists()) {
	if (!$dbMemos->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbMemos->getError());
	}
}
if ($dbMemos->isConnected) {
	$dbMemos->close();
	$dbMemos->isConnected = false;
}

$dbKITprotocol = new dbKITprotocol();
if ($dbKITprotocol->sqlTableExists()) {
	if (!$dbKITprotocol->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITprotocol->getError());
	}
}
if ($dbKITprotocol->isConnected) {
	$dbKITprotocol->close();
	$dbKITprotocol->isConnected = false;
}

$dbKITprovider = new dbKITprovider();
if ($dbKITprovider->sqlTableExists()) {
	if (!$dbKITprovider->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITprovider->getError());
	}
}
if ($dbKITprovider->isConnected) {
	$dbKITprovider->close();
	$dbKITprovider->isConnected = false;
}

$dbKITmail = new dbKITmail();
if ($dbKITmail->sqlTableExists()) {
	if (!$dbKITmail->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITmail->getError());
	}
}
if ($dbKITmail->isConnected) {
	$dbKITmail->close();
	$dbKITmail->isConnected = false;
}

$dbKITdlgRegister = new dbKITdialogsRegister();
if ($dbKITdlgRegister->sqlTableExists()) {
	if (!$dbKITdlgRegister->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITdlgRegister->getError());
	}
}
if ($dbKITdlgRegister->isConnected) {
	$dbKITdlgRegister->close();
	$dbKITdlgRegister->isConnected = false;
}

$dbKITregister = new dbKITregister();
if ($dbKITregister->sqlTableExists()) {
	if (!$dbKITregister->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITregister->getError());
	}
}
if ($dbKITregister->isConnected) {
	$dbKITregister->close();
	$dbKITregister->isConnected = false;
}

$dbKITnewsletterCfg = new dbKITnewsletterCfg();
if ($dbKITnewsletterCfg->sqlTableExists()) {
	if (!$dbKITnewsletterCfg->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterCfg->getError());
	}
}
if ($dbKITnewsletterCfg->isConnected) {
	$dbKITnewsletterCfg->close();
	$dbKITnewsletterCfg->isConnected = false;
}

$dbKITnewsletterPreview = new dbKITnewsletterPreview();
if ($dbKITnewsletterPreview->sqlTableExists()) {
	if (!$dbKITnewsletterPreview->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterPreview->getError());
	}
}
if ($dbKITnewsletterPreview->isConnected) {
	$dbKITnewsletterPreview->close();
	$dbKITnewsletterPreview->isConnected = false;
}

$dbKITnewsletterTemplates = new dbKITnewsletterTemplates();
if ($dbKITnewsletterTemplates->sqlTableExists()) {
	if (!$dbKITnewsletterTemplates->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterTemplates->getError());
	}
}
if ($dbKITnewsletterTemplates->isConnected) {
	$dbKITnewsletterTemplates->close();
	$dbKITnewsletterTemplates->isConnected = false;
}

$dbKITnewsletterArchive = new dbKITnewsletterArchive();
if ($dbKITnewsletterArchive->sqlTableExists()) {
	if (!$dbKITnewsletterArchive->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterArchive->getError());
	}
}
if ($dbKITnewsletterArchive->isConnected) {
	$dbKITnewsletterArchive->close();
	$dbKITnewsletterArchive->isConnected = false;
}

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>