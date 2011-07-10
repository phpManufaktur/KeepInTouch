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

define('KIT_INSTALL_RUNNING', true);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.dialogs.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.link.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.import.php');

global $admin;

$error = '';

$dbConfig = new dbKITcfg();
if ($dbConfig->sqlTableExists()) {
	if (!$dbConfig->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbConfig->getError());
	}
}

$dbContact = new dbKITcontact();
if ($dbContact->sqlTableExists()) {
	if (!$dbContact->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContact->getError());
	}
}

$dbContactArray = new dbKITcontactArrayCfg();
if ($dbContactArray->sqlTableExists()) {
	if (!$dbContactArray->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContactArray->getError());
	}
}

$dbContactAddress = new dbKITcontactAddress();
if ($dbContactAddress->sqlTableExists()) {
	if (!$dbContactAddress->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbContactAddress->getError());
	}
}

$dbCountries = new dbKITcountries();
if ($dbCountries->sqlTableExists()) {
	if (!$dbCountries->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbCountries->getError());
	}
}

$dbMemos = new dbKITmemos();
if ($dbMemos->sqlTableExists()) {
	if (!$dbMemos->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbMemos->getError());
	}
}

$dbKITprotocol = new dbKITprotocol();
if ($dbKITprotocol->sqlTableExists()) {
	if (!$dbKITprotocol->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITprotocol->getError());
	}
}

$dbKITprovider = new dbKITprovider();
if ($dbKITprovider->sqlTableExists()) {
	if (!$dbKITprovider->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITprovider->getError());
	}
}

$dbKITmail = new dbKITmail();
if ($dbKITmail->sqlTableExists()) {
	if (!$dbKITmail->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITmail->getError());
	}
}

$dbKITdlgRegister = new dbKITdialogsRegister();
if ($dbKITdlgRegister->sqlTableExists()) {
	if (!$dbKITdlgRegister->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITdlgRegister->getError());
	}
}

$dbKITregister = new dbKITregister();
if ($dbKITregister->sqlTableExists()) {
	if (!$dbKITregister->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s </p>', $dbKITregister->getError());
	}
}

$dbKITnewsletterCfg = new dbKITnewsletterCfg();
if ($dbKITnewsletterCfg->sqlTableExists()) {
	if (!$dbKITnewsletterCfg->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterCfg->getError());
	}
}

$dbKITnewsletterPreview = new dbKITnewsletterPreview();
if ($dbKITnewsletterPreview->sqlTableExists()) {
	if (!$dbKITnewsletterPreview->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterPreview->getError());
	}
}

$dbKITnewsletterTemplates = new dbKITnewsletterTemplates();
if ($dbKITnewsletterTemplates->sqlTableExists()) {
	if (!$dbKITnewsletterTemplates->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterTemplates->getError());
	}
}

$dbKITnewsletterArchive = new dbKITnewsletterArchive();
if ($dbKITnewsletterArchive->sqlTableExists()) {
	if (!$dbKITnewsletterArchive->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterArchive->getError());
	}
}

$dbKITnewsletterProcess = new dbKITnewsletterProcess();
if ($dbKITnewsletterProcess->sqlTableExists()) {
	if (!$dbKITnewsletterProcess->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbKITnewsletterProcess->getError());
	}
}

$dbCronjobData = new dbCronjobData();
if ($dbCronjobData->sqlTableExists()) {
	if (!$dbCronjobData->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbCronjobData->getError());
	}
}

$dbCronjobNewsletterLog = new dbCronjobNewsletterLog();
if ($dbCronjobNewsletterLog->sqlTableExists()) {
	if (!$dbCronjobNewsletterLog->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbCronjobNewsletterLog->getError());
	}
}

$dbCronjobErrorLog = new dbCronjobErrorLog();
if ($dbCronjobErrorLog->sqlTableExists()) {
	if (!$dbCronjobErrorLog->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbCronjobErrorLog->getError());
	}
}

$dbNewsletterLinks = new dbKITnewsletterLinks();
if ($dbNewsletterLinks->sqlTableExists()) {
	if (!$dbNewsletterLinks->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbNewsletterLinks->getError());
	}
}

$dbImport = new dbKITimport();
if ($dbImport->sqlTableExists()) {
	if (!$dbImport->sqlDeleteTable()) {
		$error .= sprintf('<p>[Delete Table] %s</p>', $dbImport->getError());
	}
}

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>