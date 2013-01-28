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

$dbKITlanguages = new dbKITlanguages();
if ($dbKITlanguages->sqlTableExists()) {
  if (!$dbKITlanguages->sqlDeleteTable()) {
    $error .= sprintf('<p>[Delete Table] %s</p>', $dbKITlanguages->getError());
  }
}

// Prompt Errors
if (!empty($error)) {
  $admin->print_error($error);
}

?>