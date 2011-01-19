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
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.editor.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.request.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.cfg.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.cronjob.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.link.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

global $dbNewsletterArchive;
global $dbNewsletterPreview;
global $dbNewsletterTemplates;
global $newsletterCommands;
global $dbNewsletterProcess;
global $dbCronjobData;
global $dbCronjobErrorLog;
global $dbCronjobNewsletterLog;
global $dbNewsletterLinks;

if (!is_object($dbNewsletterArchive)) $dbNewsletterArchive = new dbKITnewsletterArchive();
if (!is_object($dbNewsletterPreview)) $dbNewsletterPreview = new dbKITnewsletterPreview();
if (!is_object($dbNewsletterTemplates)) $dbNewsletterTemplates = new dbKITnewsletterTemplates();
if (!is_object($newsletterCommands)) $newsletterCommands = new kitNewsletterCommands();
if (!is_object($dbNewsletterProcess)) $dbNewsletterProcess = new dbKITnewsletterProcess();
if (!is_object($dbCronjobData)) $dbCronjobData = new dbCronjobData();
if (!is_object($dbCronjobErrorLog)) $dbCronjobErrorLog = new dbCronjobErrorLog();
if (!is_object($dbCronjobNewsletterLog)) $dbCronjobNewsletterLog = new dbCronjobNewsletterLog();
if (!is_object($dbNewsletterLinks)) $dbNewsletterLinks = new dbKITnewsletterLinks();

class dbKITnewsletterTemplates extends dbConnectLE {
	
	const field_id								= 'nl_tpl_id';
	const field_name							= 'nl_tpl_name';
	const field_description				= 'nl_tpl_desc';
	const field_html							= 'nl_tpl_html';
	const field_text							= 'nl_tpl_text';
	const field_status						= 'nl_tpl_status';
	const field_update_when				= 'nl_tpl_update_when';
	const field_update_by					= 'nl_tpl_update_by';
	
	const status_active						= 'statusActive'; 
	const status_locked						= 'statusLocked'; 
	const status_deleted					= 'statusDeleted';
	
	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);
		
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_newsletter_template');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_description, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_html, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_text, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}
		return true;
	} // __construct()
	
	
} // class dbKITnewsletterTemplates

class dbKITnewsletterArchive extends dbConnectLE {
	
	const field_id							= 'nl_arc_id';
	const field_description			= 'nl_arc_desc';
	const field_template				= 'nl_arc_tpl';
	const field_html						= 'nl_arc_html';
	const field_text						= 'nl_arc_text';
	const field_provider				= 'nl_arc_prov';
	const field_subject					= 'nl_arc_subj';
	const field_recipients			= 'nl_arc_recip';
	const field_groups					= 'nl_arc_grps'; 		// Newsletter Groups
	const field_distributions		= 'nl_arc_dist';		// Distribution Groups
	const field_update_when			= 'nl_arc_update_when';
	const field_update_by				= 'nl_arc_update_by';
	
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_newsletter_archive');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_description, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_template, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_html, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_text, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_provider, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_subject, "VARCHAR(80) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_recipients, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_groups, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_distributions, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}
		return true;
	} // __construct()
	
	
} // class dbKITnewsletterArchive

class dbKITnewsletterPreview extends dbConnectLE {
	
	const field_id							= 'nl_pre_id';
	const field_keys						= 'nl_pre_keys';
	
	const field_view						= 'nl_pre_view';
	const field_update_when			= 'nl_pre_update_when';
	
	public $create_tables = false;
	
	const array_separator					= '[:item:]';
	const array_separator_value		= '[:split:]';
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_newsletter_preview');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_view, "LONGTEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_update_when, "TIMESTAMP");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}	
		return true;
	} // __construct()
	
} // class dbKITnewsletterPreview

class dbKITnewsletterProcess extends dbConnectLE {
	
	const field_id								= 'nl_pro_id';
	const field_archiv_id					= 'nl_arc_id';
	const field_register_ids			= 'nl_pro_reg_ids';
	const field_distribution_ids	= 'nl_pro_dist_ids';
	const field_count							= 'nl_pro_count';
	const field_send							= 'nl_pro_send';
	const field_simulate					= 'nl_pro_simulate';
	const field_job_created_dt		= 'nl_pro_job_created_dt';
	const field_job_done_dt				= 'nl_pro_job_done_dt';
	const field_job_process_time  = 'nl_pro_process_time';
	const field_is_done						= 'nl_pro_job_is_done';
	const field_update_when				= 'nl_pro_update_when';

	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_newsletter_process');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_archiv_id, "INT(11) NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_register_ids, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_distribution_ids, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_count, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_send, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_simulate, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_job_created_dt, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_job_done_dt, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_job_process_time, "FLOAT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_is_done, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_update_when, "TIMESTAMP");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}	
		return true;
	} // __construct()
	
} // class dbKITnewsletterProcess

class kitNewsletterCommands {
	
	const cmd_account_email							= '{$account_email}';
	const cmd_account_first_name				= '{$account_first_name}';
	const cmd_account_id								= '{$account_id}';
	const cmd_account_last_name					= '{$account_last_name}';
	const cmd_account_login							= '{$account_login}';
	const cmd_account_newsletter				= '{$account_newsletter}';
	const cmd_account_register_key			= '{$account_register_key}';
	const cmd_account_title							= '{$account_title}';
	const cmd_account_title_academic		= '{$account_title_academic}';
	const cmd_account_username					= '{$account_username}';
	const cmd_contact_id								= '{$contact_id}';
	const cmd_content										= '{$content}';
	const cmd_kit_info									= '{$kit_info}';
	const cmd_kit_release								= '{$kit_release}';					
	const cmd_newsletter_unsubscribe		= '{$newsletter_unsubscribe}';
	const cmd_salutation_01							= '{$salutation_01}';
	const cmd_salutation_02							= '{$salutation_02}';
	const cmd_salutation_03							= '{$salutation_03}';
	const cmd_salutation_04							= '{$salutation_04}';
	const cmd_salutation_05							= '{$salutation_05}';
	const cmd_salutation_06							= '{$salutation_06}';
	const cmd_salutation_07							= '{$salutation_07}';
	const cmd_salutation_08							= '{$salutation_08}';
	const cmd_salutation_09							= '{$salutation_09}';
	const cmd_salutation_10							= '{$salutation_10}';
	
	public $cmd_array = array(
		self::cmd_account_email							=> kit_cmd_nl_account_email,
		self::cmd_account_first_name				=> kit_cmd_nl_account_first_name,
		self::cmd_account_id								=> kit_cmd_nl_account_id,
		self::cmd_account_last_name					=> kit_cmd_nl_account_last_name,
		self::cmd_account_login							=> kit_cmd_nl_account_login,
		self::cmd_account_newsletter				=> kit_cmd_nl_account_newsletter,
		self::cmd_account_register_key			=> kit_cmd_nl_account_register_key,
		self::cmd_account_title							=> kit_cmd_nl_account_title,
		self::cmd_account_title_academic		=> kit_cmd_nl_account_title_academic,
		self::cmd_account_username					=> kit_cmd_nl_account_username,
		self::cmd_contact_id								=> kit_cmd_nl_contact_id,
		self::cmd_content										=> kit_cmd_nl_content,
		self::cmd_kit_info									=> kit_cmd_nl_kit_info,
		self::cmd_kit_release								=> kit_cmd_nl_kit_release,
		self::cmd_newsletter_unsubscribe		=> kit_cmd_nl_newsletter_unsubscribe,
		self::cmd_salutation_01							=> kit_cmd_nl_salutation,
		self::cmd_salutation_02							=> kit_cmd_nl_salutation,
		self::cmd_salutation_03							=> kit_cmd_nl_salutation,
		self::cmd_salutation_04							=> kit_cmd_nl_salutation,
		self::cmd_salutation_05							=> kit_cmd_nl_salutation,
		self::cmd_salutation_06							=> kit_cmd_nl_salutation,
		self::cmd_salutation_07							=> kit_cmd_nl_salutation,
		self::cmd_salutation_08							=> kit_cmd_nl_salutation,
		self::cmd_salutation_09							=> kit_cmd_nl_salutation,
		self::cmd_salutation_10							=> kit_cmd_nl_salutation
		
	);
	
	private $error = '';
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError

  /**
   * Gibt einen Command Str ohne {$ und } zurueck
   * 
   * @param STR $cmd
   * @return STR command
   */
  public function extractCommand($cmd) {
  	return preg_replace('/[{$}]/eS', '', $cmd);
  }
	
  /**
   * Parst ein Newsletter Template und ersetzt alle Commands durch die
   * entsprechenden Werte
   * 
   * @param REFERENCE STR &$template
   * @param STR $content
   * @param INT $contact_id
   * @return BOOL
   */
	public function parseCommands(&$template, $content='', $contact_id=-1, $newsletter_archive = array()) {
		global $dbContact;
		global $dbRegister; 
		global $kitRequest;
		global $parser;

		if ($contact_id == -1) {
			// Simulation
			$contact = array_merge($dbContact->getFields(), $dbRegister->getFields());
			// Musterwerte eintragen
			$contact[dbKITregister::field_id] 									= -1;
			$contact[dbKITregister::field_contact_id]						= -1;
			$contact[dbKITregister::field_email]								= 'daniela@musterfrau.de';
			$contact[dbKITregister::field_username]							= 'daniela@musterfrau.de';
			$contact[dbKITregister::field_register_key]					= '0a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p';
			$contact[dbKITregister::field_newsletter]						= dbKITcontact::newsletter_newsletter;
			$contact[dbKITcontact::field_person_title]					= dbKITcontact::person_title_lady;
			$contact[dbKITcontact::field_person_title_academic]	= dbKITcontact::person_title_academic_dr;
			$contact[dbKITcontact::field_person_first_name]			= 'Daniela';
			$contact[dbKITcontact::field_person_last_name]			= 'Musterfrau';
		}
		else {
			$SQL = sprintf(	"SELECT %s, %s, %s, %s FROM %s WHERE %s='%s' AND %s='%s'",
											dbKITcontact::field_person_title,
											dbKITcontact::field_person_title_academic,
											dbKITcontact::field_person_first_name,
											dbKITcontact::field_person_last_name,
											$dbContact->getTableName(),
											dbKITcontact::field_id,
											$contact_id,
											dbKITcontact::field_status,
											dbKITcontact::status_active);
			$contact = array();
			if (!$dbContact->sqlExec($SQL, $contact)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			if (count($contact) < 1) {
				$this->setError(sprintf(kit_error_item_id, $contact_id));
				return false;
			}
			$contact = $contact[0];
						
			$where = array(	dbKITregister::field_contact_id => $contact_id,
											dbKITregister::field_status => dbKITregister::status_active);
			$register = array();
			if (!$dbRegister->sqlSelectRecord($where, $register)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
				return false;
			}
			if (count($register) < 1) {
				$this->setError(sprintf(kit_error_item_id, $contact_id));
				return false;
			}
			$register = $register[0];
			foreach ($register as $key => $value) {
				if (!key_exists($key, $contact)) $contact[$key] = $value;
			}
		}
		
		// Login Dialog
		$request_link = $kitRequest->getRequestLink();
		
		// Newsletter
		$news_array = explode(',', $contact[dbKITregister::field_newsletter]);
		$newsletter = '';
		if (count($news_array) > 0) {
			foreach ($news_array as $item) { 
				if (!empty($item)) {
					if ($newsletter != '') $newsletter .= ', ';
					$newsletter .= $dbContact->newsletter_array[$item];
				}
			}
		}
		if (empty($content)) { 
			$tpl = new Dwoo_Template_File(WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/lorem.ipsum.htt');
			$data = array();
			$content = $parser->get($tpl, $data); 
		}	
		
		// unsubscribe link
		if ($contact_id < 1) {
			$unsubscribe_link = sprintf('%s&%s=%s',
																	$request_link,
																	kitRequest::request_link,
																	0);
		}
		else {
			$unsubscribe_link = $this->getUnsubscribeLink($contact_id, $newsletter_archive);
		}
		
		// Daten zusammenstellen
		$data = array(
			$this->extractCommand(self::cmd_account_email)						=> $contact[dbKITregister::field_email],
			$this->extractCommand(self::cmd_account_first_name)				=> $contact[dbKITcontact::field_person_first_name],
			$this->extractCommand(self::cmd_account_id)								=> $contact[dbKITregister::field_id],
			$this->extractCommand(self::cmd_account_last_name)				=> $contact[dbKITcontact::field_person_last_name],
			$this->extractCommand(self::cmd_account_login)						=> sprintf(	'%s&%s=%s',
																																						$request_link,
																																						kitRequest::request_action,
																																						kitRequest::action_login),
			$this->extractCommand(self::cmd_account_newsletter)				=> $newsletter,
			$this->extractCommand(self::cmd_account_register_key)			=> $contact[dbKITregister::field_register_key],
			$this->extractCommand(self::cmd_account_title)						=> $dbContact->person_title_array[$contact[dbKITcontact::field_person_title]],
			$this->extractCommand(self::cmd_account_title_academic)		=> @$dbContact->person_title_academic_array[$contact[dbKITcontact::field_person_title_academic]],
			$this->extractCommand(self::cmd_account_username)					=> $contact[dbKITregister::field_username],
			$this->extractCommand(self::cmd_contact_id)								=> $contact[dbKITregister::field_contact_id],
			$this->extractCommand(self::cmd_content)									=> $content,
			$this->extractCommand(self::cmd_kit_info)									=> sprintf(kit_info, $this->getVersion()),
			$this->extractCommand(self::cmd_kit_release)							=> $this->getVersion(),
			/*
			$this->extractCommand(self::cmd_newsletter_unsubscribe)		=> sprintf(	'%s&%s=%s',
																																						$request_link,
																																						kitRequest::request_action,
																																						kitRequest::action_login),
			*/																																	
			$this->extractCommand(self::cmd_newsletter_unsubscribe)		=> $unsubscribe_link,					
			$this->extractCommand(self::cmd_salutation_01)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_01, $contact),
			$this->extractCommand(self::cmd_salutation_02)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_02, $contact),
			$this->extractCommand(self::cmd_salutation_03)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_03, $contact),
			$this->extractCommand(self::cmd_salutation_04)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_04, $contact),
			$this->extractCommand(self::cmd_salutation_05)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_05, $contact),
			$this->extractCommand(self::cmd_salutation_06)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_06, $contact),
			$this->extractCommand(self::cmd_salutation_07)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_07, $contact),
			$this->extractCommand(self::cmd_salutation_08)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_08, $contact),
			$this->extractCommand(self::cmd_salutation_09)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_09, $contact),
			$this->extractCommand(self::cmd_salutation_10)						=> $this->getSalutationStr(dbKITnewsletterCfg::cfgSalutation_10, $contact),
			 
		);
		
		$tpl = new Dwoo_Template_String($template);
		$template = $parser->get($tpl, $data);
		return true;
	} // parseCommands()
	
	/**
	 * Gibt einen formatierten Begruessungsstring zurueck
	 * 
	 * @param STR $salutation
	 * @param ARRAY $contact
	 * @return STR
	 */
	public function getSalutationStr($salutation, $contact) { 
		global $dbContact;
		global $dbNewsletterCfg;
		global $parser;
		$salut_str = $dbNewsletterCfg->getValue($salutation);  
		// keine Aktion bei einem leeren String
		if (empty($salut_str)) return '';
		// Parameterstr aufsplitten
		list($male, $female, $neutral) = explode('|', $salut_str);
		if (empty($contact[dbKITcontact::field_person_first_name]) && empty($contact[dbKITcontact::field_person_last_name])) {
			$template = new Dwoo_Template_String($neutral);
		}
		elseif ($contact[dbKITcontact::field_person_title] == dbKITcontact::person_title_mister) {
			$template = new Dwoo_Template_String($male);
		}
		else {
			$template = new Dwoo_Template_String($female);
		} 
		$data = array(
			$this->extractCommand(self::cmd_account_title_academic)		=> $contact[dbKITcontact::field_person_title_academic] == dbKITcontact::person_title_academic_none ? '' : 
																																	 @$dbContact->person_title_academic_array[$contact[dbKITcontact::field_person_title_academic]],
			$this->extractCommand(self::cmd_account_title)						=> $dbContact->person_title_array[$contact[dbKITcontact::field_person_title]],
			$this->extractCommand(self::cmd_account_first_name)				=> empty($contact[dbKITcontact::field_person_first_name]) ? '' : $contact[dbKITcontact::field_person_first_name],
			$this->extractCommand(self::cmd_account_last_name)				=> $contact[dbKITcontact::field_person_last_name]																														 
		);
		$result = $parser->get($template, $data);
		// doppelte Leerzeichen durch einfache ersetzen und so unerwuenschte Luecken schliessen...
		str_replace('  ', ' ', $result);
		return $result; 
	} // getSalutationStr()
	
	public function getUnsubscribeLink($contact_id, $newsletter_archive) {
		global $dbNewsletterLinks;
		global $kitRequest;
		
		$where = array();
		$where[dbKITnewsletterLinks::field_kit_id] = $contact_id;
		$where[dbKITnewsletterLinks::field_type] = dbKITnewsletterLinks::type_link_unsubscribe;
		$links = array();
		if (!$dbNewsletterLinks->sqlSelectRecord($where, $links)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		if (count($links) > 0) {
			// Eintrag existiert bereits
			$links = $links[0];
			if ($links[dbKITnewsletterLinks::field_archive_id] !== $newsletter_archive[dbKITnewsletterArchive::field_id]) {
				// Newsletter Archive ID aktualisieren
				$links[dbKITnewsletterLinks::field_archive_id] = $newsletter_archive[dbKITnewsletterArchive::field_id];
				$links[dbKITnewsletterLinks::field_newsletter_grps] = $newsletter_archive[dbKITnewsletterArchive::field_groups];
				if (!$dbNewsletterLinks->sqlUpdateRecord($links, array(dbKITnewsletterLinks::field_id => $links[dbKITnewsletterLinks::field_id]))) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
					return false;
				}
			}
			$result = sprintf('%s&%s=%s&%s=%s',
												$kitRequest->getRequestLink(),
												kitRequest::request_action,
												kitRequest::action_link,
												kitRequest::request_link,
												$links[dbKITnewsletterLinks::field_link_value]);
			return $result;	
		}
		// neuen Eintrag anlegen
		$links = array(
			dbKITnewsletterLinks::field_archive_id			=> $newsletter_archive[dbKITnewsletterArchive::field_id],
			dbKITnewsletterLinks::field_type						=> dbKITnewsletterLinks::type_link_unsubscribe,
			dbKITnewsletterLinks::field_count						=> 0,
			dbKITnewsletterLinks::field_kit_id					=> $contact_id,
			dbKITnewsletterLinks::field_newsletter_grps	=> $newsletter_archive[dbKITnewsletterArchive::field_groups],
			dbKITnewsletterLinks::field_option					=> '',
			dbKITnewsletterLinks::field_origin					=> ''
		);
		$id = -1;
		if (!$dbNewsletterLinks->sqlInsertRecord($links, $id)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		$base = base_convert($id, 10, 36);
		$links = array(
			dbKITnewsletterLinks::field_link_value			=> $base
		);
		if (!$dbNewsletterLinks->sqlUpdateRecord($links, array(dbKITnewsletterLinks::field_id => $id))) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterLinks->getError()));
			return false;
		}
		$result = sprintf('%s&%s=%s&%s=%s',
												$kitRequest->getRequestLink(),
												kitRequest::request_action,
												kitRequest::action_link,
												kitRequest::request_link,
												$base);
		return $result;	
	} // getUnsubscribeLink()
	
	/**
   * Return Version of KIT
   *
   * @return FLOAT
   */
  public function getVersion() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1; 
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = explode('=', $item);
        // return floatval
        return floatval(preg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1]));
      } 
    }
    return -1;
  } // getVersion()
	
	
} // class kitNewsletterCommands


class kitNewsletterDialog {
	
	const request_action						= 'nlact';
	const request_command						= 'cmd';
	const request_items							= 'its';
	const action_config							= 'cfg';
	const action_config_save				= 'cfgs';
	const action_cronjobs_active		= 'cja';
	const action_cronjobs_act_check	= 'cjac';
	const action_cronjobs_protocol	= 'cjp';
	const action_default						= 'def';
	const action_template						= 'tpl';
	const action_template_check			= 'tplc';
	const action_template_save			= 'tpls';
	const action_newsletter					= 'nl';
	const action_newsletter_check		= 'nlc';
	const action_newsletter_save		= 'nls';
	
	private $tab_navigation_array = array(
		self::action_cronjobs_active		=> kit_tab_cronjobs_active,
		self::action_cronjobs_protocol	=> kit_tab_cronjobs_protocol,
		self::action_newsletter					=> kit_tab_nl_create,
		self::action_template						=> kit_tab_nl_template,
		self::action_config							=> kit_tab_config
	);
	
	private $page_link 							= '';
	private $img_url								= '';
	private $template_path					= '';
	private $help_path							= '';
	private $error									= '';
	private $message								= '';
	
	private $swNavHide							= array();
	private $overwriteNavigation		= '';
	
	public function __construct() {
		$this->page_link = ADMIN_URL.'/admintools/tool.php?tool=kit&'.kitBackend::request_action.'='.kitBackend::action_newsletter;
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->help_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/languages/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';
	} // __construct()
	
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError

  /**
   * Reset Error to empty String
   */
  public function clearError() {
  	$this->error = '';
  }

  /** Set $this->message to $message
    * 
    * @param STR $message
    */
  public function setMessage($message) {
    $this->message = $message;
  } // setMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMessage() {
    return $this->message;
  } // getMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMessage() {
    return (bool) !empty($this->message);
  } // isMessage
	
    /**
   * Verhindert XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
  
  
  public function action() {
  	// ACHTUNG: erlaubte HTML Felder muessen auch in $kitBackend->action() angegeben werden !!!
  	$html_allowed = array(dbKITnewsletterTemplates::field_html, dbKITnewsletterPreview::field_view, dbKITnewsletterArchive::field_html);
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
  	isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
  	switch ($action):
  	case self::action_template:
  		return $this->show(self::action_template, $this->dlgTemplate());
  		break;
  	case self::action_template_check:
  		return $this->show(self::action_template, $this->checkTemplate());
  		break;
  	case self::action_template_save:
  		return $this->show(self::action_template, $this->saveTemplate());
  		break;
  	case self::action_config:
  		return $this->show(self::action_config, $this->dlgConfig());
  		break;
  	case self::action_config_save:
  		return $this->show(self::action_config, $this->saveConfig());
  		break;
  	case self::action_newsletter_check:
  		return $this->show(self::action_newsletter, $this->checkNewsletter());
  		break;
  	case self::action_newsletter_save:
  		return $this->show(self::action_cronjobs_active, $this->saveNewsletter());
  		break;
  	case self::action_cronjobs_protocol:
  		return $this->show(self::action_cronjobs_protocol, $this->dlgCronjobsProtocoll());
  		break;
  	case self::action_newsletter:
  		return $this->show(self::action_newsletter, $this->dlgNewsletter());
  		break;
  	default:
  	case self::action_cronjobs_active:
  		return $this->show(self::action_cronjobs_active, $this->dlgCronjobsActive());
  		break;
  	endswitch;
  	return true;
  } // action();
  
    /**
   * Erstellt eine Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
  	$result = '';
  	// voreingestellen Navigationstab ueberschreiben?
  	if (!empty($this->overwriteNavigation)) $action = $this->overwriteNavigation;
  	foreach ($this->tab_navigation_array as $key => $value) {
  		if (!in_array($key, $this->swNavHide)) {
	  		($key == $action) ? $selected = ' class="selected"' : $selected = ''; 
	  		$result .= sprintf(	'<li%s><a href="%s">%s</a></li>', 
	  												$selected,
	  												sprintf('%s&%s=%s', $this->page_link, self::request_action, $key),
	  												$value
	  												);
  		}
  	}
  	$result = sprintf('<ul class="nav_tab">%s</ul>', $result);
  	return $result;
  } // getNavigation()
  
  
  /**
   * Ausgabe des formatierten Ergebnis mit Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @param $content - Inhalt
   * 
   * @return STR RESULT
   */
  public function show($action, $content) {
  	global $parser;
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = ' class="error"';
  	}
  	else {
  		$class = '';
  	}
  	$data = array(
  		'WB_URL'					=> WB_URL,
  		'navigation' 			=> $this->getNavigation($action),
  		'class'						=> $class,
  		'content'					=> $content,
  	);
  	return $parser->get($this->template_path.'backend.body.htt', $data);
  } // show()
  
  /**
   * Dialog zum Erstellen und Bearbeiten von Templates fuer Newsletter
   * 
   * @return STR Dialog
   */
  public function dlgTemplate() {
  	global $dbNewsletterPreview;
  	global $dbNewsletterTemplates;
  	global $newsletterCommands;
  	global $parser;
  	
  	// Bei Fehler sofort wieder raus...
  	if ($this->isError()) return false;
  	
  	// Zurueck von der Vorschau? Daten holen...
  	if (isset($_REQUEST[dbKITnewsletterPreview::field_id]) && $_REQUEST[dbKITnewsletterPreview::field_id] != -1) {
  		$where = array();
			$where[dbKITnewsletterPreview::field_id] = $_REQUEST[dbKITnewsletterPreview::field_id];
			$prev = array();
			if (!$dbNewsletterPreview->sqlSelectRecord($where, $prev)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
				return false;
			}
			if (count($prev) < 1) {
				// Datensatz nicht gefunden
				$this->setError(sprintf(kit_error_item_id, $_REQUEST[self::request_id]));
				return false;
			}
			$prev_array = explode(dbKITnewsletterPreview::array_separator, $prev[0][dbKITnewsletterPreview::field_view]);
			$preview = array();
			foreach ($prev_array as $item) {
				list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
				$preview[$key] = $value;
			}
			foreach ($preview as $key => $value) {
				$_REQUEST[$key] = $value;
			}			
  	}
  	
  	// Template ID gesetzt?
  	(isset($_REQUEST[dbKITnewsletterTemplates::field_id])) ? $template_id = $_REQUEST[dbKITnewsletterTemplates::field_id] : $template_id = -1;  
  	
  	$where = array();
  	$where[dbKITnewsletterTemplates::field_status] = dbKITnewsletterTemplates::status_active;
  	$templates = array();
  	if (!$dbNewsletterTemplates->sqlSelectRecord($where, $templates)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
  		return false;
  	}
  	$template_select = sprintf('<option value="-1">%s</option>', kit_text_please_select);
  	foreach ($templates as $item) {
  		($item[dbKITnewsletterTemplates::field_id] == $template_id) ? $selected = ' selected="selected"' : $selected = '';
			$template_select .= sprintf('<option value="%s"%s>%s</option>', 
																	$item[dbKITnewsletterTemplates::field_id], 
																	$selected, 
																	$item[dbKITnewsletterTemplates::field_name]);
  	}
  	$template_select = sprintf(	'<select name="%s" onchange="document.body.style.cursor=\'wait\';window.location=\'%s\'+this.value; return false;">%s</select>', 
  															dbKITnewsletterTemplates::field_id, 
  															sprintf('%s&%s=%s&%s=',
																				$this->page_link,
																				self::request_action,
																				self::action_template,
																				dbKITnewsletterTemplates::field_id), 
  															$template_select);
  	
  	if ($template_id != -1) {
  		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s'",
  										$dbNewsletterTemplates->getTableName(),
  										dbKITnewsletterTemplates::field_id,
  										$template_id);
  		$tpl = array();
  		if (!$dbNewsletterTemplates->sqlExec($SQL, $tpl)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
  			return false;
  		}
  		if (count($tpl) < 1) {
  			$this->setError(sprintf(kit_error_newsletter_tpl_id_invalid, $template_id));
  			return false;
  		}
  		$tpl = $tpl[0];
  	}
  	else {
  		// neuer Datensatz
  		$tpl = $dbNewsletterTemplates->getFields();
  		$tpl[dbKITnewsletterTemplates::field_id] = $template_id;
  		$tpl[dbKITnewsletterTemplates::field_status] = dbKITnewsletterTemplates::status_active;
  	}
  	
  	$template_name = sprintf(	'<input type="text" name="%s" value="%s" />', 
  														dbKITnewsletterTemplates::field_name,
  														isset($_REQUEST[dbKITnewsletterTemplates::field_name]) ? $_REQUEST[dbKITnewsletterTemplates::field_name] : $tpl[dbKITnewsletterTemplates::field_name]);
  	$template_desc = sprintf(	'<textarea name="%s" rows="2">%s</textarea>', 
  														dbKITnewsletterTemplates::field_description,
  														isset($_REQUEST[dbKITnewsletterTemplates::field_description]) ? $_REQUEST[dbKITnewsletterTemplates::field_description] : $tpl[dbKITnewsletterTemplates::field_description]);
  	$template_html = sprintf( '%s<textarea name="%s" id="%s" rows="20" style="width=98%%;">%s</textarea>',
  														function_exists('registerEditArea') ? registerEditArea(dbKITnewsletterTemplates::field_html, 'html', false, 'both', true, true, 300, 300, 'default') : '',
  														dbKITnewsletterTemplates::field_html,
  														dbKITnewsletterTemplates::field_html,
  														isset($_REQUEST[dbKITnewsletterTemplates::field_html]) ? $_REQUEST[dbKITnewsletterTemplates::field_html] : $tpl[dbKITnewsletterTemplates::field_html]);
  	$template_text = sprintf(	'<textarea name="%s" rows="20">%s</textarea>',
  														dbKITnewsletterTemplates::field_text,
  														isset($_REQUEST[dbKITnewsletterTemplates::field_text]) ? $_REQUEST[dbKITnewsletterTemplates::field_text] : $tpl[dbKITnewsletterTemplates::field_text]);
  	$template_status = '';
  	foreach ($dbNewsletterTemplates->status_array as $key => $value) {
  		($key == $tpl[dbKITnewsletterTemplates::field_status]) ? $selected = ' selected="selected"' : $selected = '';
  		$template_status .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
  	}
  	$template_status = sprintf('<select name="%s">%s</select>', dbKITnewsletterTemplates::field_status, $template_status);
  	
  	$form_name = 'template_form';
  	$commands = '';
  	$cmd_array = $newsletterCommands->cmd_array;
  	ksort($cmd_array); 
  	foreach ($cmd_array as $key => $hint) {
  		$commands .= sprintf('<option value="%s" title="%s">%s</option>', $key, $hint, $key);
  	}
  	$commands = sprintf('<select name="%s" size="%d" onchange="editAreaLoader.insertTags(\'%s\', this.value, \'\');">%s</select>',
  											self::request_command,
  											count($cmd_array),
  											dbKITnewsletterTemplates::field_html,
  											$commands);
  	
  	// intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_newsletter_template);
		}	
  	$data = array(
  		'header'									=> kit_header_template,
  		'intro'										=> $intro,
  		'form_name'								=> $form_name,
  		'form_action'							=> $this->page_link,
  		'action_name'							=> self::request_action,
  		'action_value'						=> self::action_template_check,
  		'preview_name'						=> dbKITnewsletterPreview::field_id,
  		'preview_value'						=> -1,
  		'tid_name'								=> dbKITnewsletterTemplates::field_id,
  		'tid_value'								=> $template_id,
  		'template_select_label'		=> kit_label_newsletter_tpl_select,
  		'template_select'					=> $template_select,
  		'template_name_label'			=> kit_label_newsletter_tpl_name,
  		'template_name'						=> $template_name,
  		'template_description_label' => kit_label_newsletter_tpl_desc,
  		'template_description'		=> $template_desc,
  		'template_html_label'			=> kit_label_newsletter_tpl_html,
  		'template_html'						=> $template_html, 
  		'template_text_label'			=> kit_label_newsletter_tpl_text,
  		'template_text'						=> $template_text,
  		'template_status_label'		=> kit_label_status,
  		'template_status'					=> $template_status,
  		'btn_preview'							=> kit_btn_preview,
  		'btn_abort'								=> kit_btn_abort,
  		'abort_location'					=> $this->page_link,
  		'header_commands'					=> kit_label_newsletter_commands,
  		'intro_commands'					=> kit_intro_newsletter_commands,
  		'commands'								=> $commands
  	);
  	return $parser->get($this->template_path.'backend.newsletter.template.htt', $data);
  } // dlgTemplate()
  
  /**
   * Prueft Eingaben und ruft anschliessend die Vorschau auf
   * 
   * @return STR dialog
   */
  public function checkTemplate() {
  	global $dbNewsletterPreview;
  	global $dbNewsletterTemplates;
  	
  	// Mindesanforderungen pruefen
  	if (empty($_REQUEST[dbKITnewsletterTemplates::field_name]) || empty($_REQUEST[dbKITnewsletterTemplates::field_html])) {
  		$this->setMessage(kit_msg_newsletter_tpl_minimum_failed);
  		return $this->dlgTemplate();
  	}
  	
  	// Mindestens der {$content} Befehl muss enthalten sein
  	if (strpos($_REQUEST[dbKITnewsletterTemplates::field_html], kitNewsletterCommands::cmd_content) === false) {
  		$this->setMessage(sprintf(kit_msg_newsletter_tpl_cmd_content, kitNewsletterCommands::cmd_content));
  		return $this->dlgTemplate();
  	} 
  	
  	if (empty($_REQUEST[dbKITnewsletterTemplates::field_text])) {
  		$_REQUEST[dbKITnewsletterTemplates::field_text] = trim(strip_tags($_REQUEST[dbKITnewsletterTemplates::field_html]));
  		$this->setMessage(kit_msg_newsletter_tpl_text_inserted);
  		return $this->dlgTemplate();
  	}
  	
  	// Mindestens der {$content} Befehl muss enthalten sein
  	if (strpos($_REQUEST[dbKITnewsletterTemplates::field_text], kitNewsletterCommands::cmd_content) === false) {
  		$this->setMessage(sprintf(kit_msg_newsletter_tpl_cmd_content, kitNewsletterCommands::cmd_content));
  		return $this->dlgTemplate();
  	} 
  	
  	// als Vorschau sichern
  	if (isset($_REQUEST[dbKITnewsletterPreview::field_id]) && $_REQUEST[dbKITnewsletterPreview::field_id] !== -1) {
  		// alte Preview loeschen...
  		$where = array();
  		$where[dbKITnewsletterPreview::field_id] = $_REQUEST[dbKITnewsletterPreview::field_id];
  		if (!$dbNewsletterPreview->sqlDeleteRecord($where)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
  			return false;
  		}
  	}
  	
  	$view = array();
  	foreach ($dbNewsletterTemplates->getFields() as $key => $value) {
  		if (isset($_REQUEST[$key])) {
  			$view[] = sprintf('%s%s%s', $key, dbKITnewsletterPreview::array_separator_value, $_REQUEST[$key]);
  		}
  		else { 
  			$view[] = sprintf('%s%s%s', $key, dbKITnewsletterPreview::array_separator_value, $value);
  		}
  	}
  	$preview = array();
  	$preview[dbKITnewsletterPreview::field_view] = implode(dbKITnewsletterPreview::array_separator, $view);
  	$pid = -1;
  	if (!$dbNewsletterPreview->sqlInsertRecord($preview, $pid)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
  		return false;
  	}
  	$_REQUEST[dbKITnewsletterPreview::field_id] = $pid;
  	return $this->dlgCheckTemplatePreview();
  } // checkTemplate()
  
  public function dlgCheckTemplatePreview() {
  	global $dbCfg;
  	global $parser;
  	
  	if (!isset($_REQUEST[dbKITnewsletterPreview::field_id])) {
  		$this->setError(kit_error_preview_id_missing);
  		return false;
  	}
  	$request_link = $dbCfg->getValue(dbKITcfg::cfgKITRequestLink);
  	
  	$data = array(
			'header_preview'						=> kit_header_preview,
			'intro'											=> kit_intro_preview,
			'html_label'								=> kit_label_newsletter_tpl_html,
			'html_source'								=> sprintf(	'%s?%s=%s&%s=%s&%s=%s',
																							$request_link,
																							kitRequest::request_action,
																							kitRequest::action_preview_template,
																							kitRequest::request_id,
																							$_REQUEST[dbKITnewsletterPreview::field_id],
																							kitRequest::request_type,
																							kitRequest::action_type_html),
			'text_label'								=> kit_label_newsletter_tpl_text_preview,
			'text_source'								=> sprintf(	'%s?%s=%s&%s=%s&%s=%s',
																							$request_link,
																							kitRequest::request_action,
																							kitRequest::action_preview_template,
																							kitRequest::request_id,
																							$_REQUEST[dbKITnewsletterPreview::field_id],
																							kitRequest::request_type,
																							kitRequest::action_type_text),		
			'form_action'								=> $this->page_link,
			'action_name'								=> self::request_action,
			'action_save_value'					=> self::action_template_save,
			'preview_name'							=> dbKITnewsletterPreview::field_id,
			'preview_value'							=> $_REQUEST[dbKITnewsletterPreview::field_id],
			'btn_save'									=> kit_btn_save,
			'action_edit_value'					=> self::action_template,
			'btn_edit'									=> kit_btn_edit,
			'btn_abort'									=> kit_btn_abort,
			'abort_location'						=> $this->page_link
		);
		return $parser->get($this->template_path.'backend.newsletter.preview.htt', $data);
  } // dlgCheckTemplatePreview()
  
  /**
   * Sichert das Template
   */
  public function saveTemplate() {
  	global $dbNewsletterPreview;
  	global $dbNewsletterTemplates;
  	
  	if (!isset($_REQUEST[dbKITnewsletterPreview::field_id])) {
  		// keine Preview ID gesetzt
  		$this->setError(kit_error_preview_id_missing);
  		return false;
  	}
  	$where = array();
		$where[dbKITnewsletterPreview::field_id] = $_REQUEST[dbKITnewsletterPreview::field_id];
		$prev = array();
		if (!$dbNewsletterPreview->sqlSelectRecord($where, $prev)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
			return false;
		}
		if (count($prev) < 1) {
			// Datensatz nicht gefunden
			$this->setError(sprintf(kit_error_item_id, $_REQUEST[self::request_id]));
			return false;
		}
		
		// Vorschau löschen
		unset($_REQUEST[dbKITnewsletterPreview::field_id]);
		
		$prev_array = explode(dbKITnewsletterPreview::array_separator, $prev[0][dbKITnewsletterPreview::field_view]);
		$preview = array();
		foreach ($prev_array as $item) {
			list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
			$preview[$key] = $value;
		}
		if (intval($preview[dbKITnewsletterTemplates::field_id]) == -1) { 
			// neuer Datensatz
			$tid = -1;
			unset($preview[dbKITnewsletterTemplates::field_id]);
			if (!$dbNewsletterTemplates->sqlInsertRecord($preview, $tid)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
				return false;
			}
			$_REQUEST[dbKITnewsletterTemplates::field_id] = $tid;
			$this->setMessage(sprintf(kit_msg_newsletter_tpl_added, $preview[dbKITnewsletterTemplates::field_name]));
		}
		else { 
			// Datensatz existiert bereits
			$_REQUEST[dbKITnewsletterTemplates::field_id] = $preview[dbKITnewsletterTemplates::field_id];
			$where = array();
			$where[dbKITnewsletterTemplates::field_id] = $preview[dbKITnewsletterTemplates::field_id];
			$template = array();
			if (!$dbNewsletterTemplates->sqlSelectRecord($where, $template)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
				return false;
			}
			if (count($template) < 1) {
				$this->setError(sprintf(kit_error_newsletter_tpl_id_invalid, $preview[dbKITnewsletterTemplates::field_id]));
				return false;
			}
			$template = $template[0];
			// Daten vergleichen
			$changed = false;
			foreach ($preview as $key => $value) {
				if ($template[$key] !== $value) $changed = true;
			}
			if ($changed) {
				// Datensatz sichern
				unset($preview[dbKITnewsletterTemplates::field_id]);
				if (!$dbNewsletterTemplates->sqlUpdateRecord($preview, $where)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
					return false;
				}
				$this->setMessage(sprintf(kit_msg_newsletter_tpl_changed, $preview[dbKITnewsletterTemplates::field_name]));
			}
			else {
				// keine Aenderung
				$this->setMessage(sprintf(kit_msg_newsletter_tpl_unchanged, $preview[dbKITnewsletterTemplates::field_id]));
			}
		}
		return $this->dlgTemplate();
  } // saveTemplate()
  
  
  /**
	 * Gibt den Dialog fuer allgemeine Einstellungen zurueck
	 */
	private function dlgConfig() {
		global $dbNewsletterCfg;
		global $parser;
		
		$SQL = sprintf(	"SELECT * FROM %s WHERE NOT %s='%s' ORDER BY %s",
										$dbNewsletterCfg->getTableName(),
										dbKITnewsletterCfg::field_status,
										dbKITnewsletterCfg::status_deleted,
										dbKITnewsletterCfg::field_label);
		$config = array();
		if (!$dbNewsletterCfg->sqlExec($SQL, $config)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterCfg->getError()));
			return false;
		}
		$count = array();
		$data = array(
			'label'				=> '',
			'value'				=> kit_header_cfg_value,
			'description'	=> kit_header_cfg_description
		);
		$items = $parser->get($this->template_path.'backend.newsletter.config.th.htt', $data);
		
		$row = new Dwoo_Template_File($this->template_path.'backend.newsletter.config.tr.htt');
		// bestehende Eintraege auflisten
		foreach ($config as $entry) {
			$id = $entry[dbKITnewsletterCfg::field_id];
			$count[] = $id;
			$label = constant($entry[dbKITnewsletterCfg::field_label]);
			(isset($_REQUEST[dbKITnewsletterCfg::field_value.'_'.$id])) ? 
				$val = $_REQUEST[dbKITnewsletterCfg::field_value.'_'.$id] : 
				$val = $entry[dbKITnewsletterCfg::field_value];
				// Hochkommas maskieren, UTF8 decodieren 
				$val = str_replace('"', '&quot;', $val);
			$value = sprintf(	'<input type="text" name="%s_%s" value="%s" />', dbKITnewsletterCfg::field_value, $id,	$val);
			if (!empty($entry[dbKITnewsletterCfg::field_description])) {
				$desc = constant($entry[dbKITnewsletterCfg::field_description]);
			}
			else { 
				$desc = ''; 
			}
			$data = array(
				'label'				=> $label,
				'value'				=> $value,
				'description'	=> $desc
			);
			$items .= $parser->get($row, $data);
		}
		$items_value = implode(",", $count);
		/*
		// Checkbox fuer CSV Export
		$items .= $template->get($this->template_path.'backend.newsletter.config.csv.htt', array('name' => self::request_csv_export, 'label' => kit_label_csv_export));
		*/
		// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_newsletter_cfg);
		}		
		$data = array(
			'form_name'						=> 'config',
			'form_action'					=> $this->page_link,
			'action_name'					=> self::request_action,
			'action_value'				=> self::action_config_save,
			'items_name'					=> self::request_items,
			'items_value'					=> $items_value,
			'header'							=> kit_header_cfg,
			'intro'								=> $intro,
			'items'								=> $items,
			'add'									=> '',
			'btn_ok'							=> kit_btn_ok,
			'btn_abort'						=> kit_btn_abort,
			'abort_location'			=> $this->page_link
		);
		return $parser->get($this->template_path.'backend.newsletter.config.htt', $data);
	} // dlgConfig()
	
	/**
	 * Ueberprueft Aenderungen die im Dialog dlgConfig() vorgenommen wurden
	 * und aktualisiert die entsprechenden Datensaetze.
	 * Fuegt neue Datensaetze ein.
	 * 
	 * @return STR DIALOG dlgConfig()
	 */
	public function saveConfig() {
		global $dbNewsletterCfg;
		global $tools;
		
		$message = '';
		// ueberpruefen, ob ein Eintrag geaendert wurde
		if ((isset($_REQUEST[self::request_items])) && (!empty($_REQUEST[self::request_items]))) {
			$ids = explode(",", $_REQUEST[self::request_items]);
			foreach ($ids as $id) {
				if (isset($_REQUEST[dbKITnewsletterCfg::field_value.'_'.$id])) {
					$value = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_value.'_'.$id]);
					$where = array();
					$where[dbKITnewsletterCfg::field_id] = $id; 
					$config = array();
					if (!$dbNewsletterCfg->sqlSelectRecord($where, $config)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterCfg->getError()));
						return false;
					}
					if (sizeof($config) < 1) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_id, $id)));
						return false;
					}
					$config = $config[0];
					if ($config[dbKITnewsletterCfg::field_value] != $value) {
						// Wert wurde geaendert
						if (!$dbNewsletterCfg->setValue($value, $id) && $dbNewsletterCfg->isError()) {
							$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterCfg->getError()));
							return false;
						}
						elseif ($dbNewsletterCfg->isMessage()) {
							$message .= $dbNewsletterCfg->getMessage();
						}
						else {
							// Datensatz wurde aktualisiert
							$message .= sprintf(kit_msg_cfg_id_updated, $id, $config[dbKITnewsletterCfg::field_name]);
						}
					}
				}
			}		
		}		
		// ueberpruefen, ob ein neuer Eintrag hinzugefuegt wurde
		if ((isset($_REQUEST[dbKITnewsletterCfg::field_name])) && (!empty($_REQUEST[dbKITnewsletterCfg::field_name]))) {
			// pruefen ob dieser Konfigurationseintrag bereits existiert
			$where = array();
			$where[dbKITnewsletterCfg::field_name] = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_name]);
			$where[dbKITnewsletterCfg::field_status] = dbKITnewsletterCfg::status_active;
			$result = array();
			if (!$dbNewsletterCfg->sqlSelectRecord($where, $result)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterCfg->getError()));
				return false;
			}
			if (sizeof($result) > 0) {
				// Eintrag existiert bereits
				$message .= sprintf(kit_msg_cfg_add_exists, $where[dbKITnewsletterCfg::field_name]);
			}
			else {
				// Eintrag kann hinzugefuegt werden
				$data = array();
				$data[dbKITnewsletterCfg::field_name] = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_name]);
				if (((isset($_REQUEST[dbKITnewsletterCfg::field_type])) && ($_REQUEST[dbKITnewsletterCfg::field_type] != dbKITnewsletterCfg::type_undefined)) &&
						((isset($_REQUEST[dbKITnewsletterCfg::field_value])) && (!empty($_REQUEST[dbKITnewsletterCfg::field_value]))) &&
						((isset($_REQUEST[dbKITnewsletterCfg::field_label])) && (!empty($_REQUEST[dbKITnewsletterCfg::field_label]))) &&
						((isset($_REQUEST[dbKITnewsletterCfg::field_description])) && (!empty($_REQUEST[dbKITnewsletterCfg::field_description])))) {
					// Alle Daten vorhanden
					unset($_REQUEST[dbKITnewsletterCfg::field_name]);
					$data[dbKITnewsletterCfg::field_type] = $_REQUEST[dbKITnewsletterCfg::field_type];
					unset($_REQUEST[dbKITnewsletterCfg::field_type]);
					$data[dbKITnewsletterCfg::field_value] = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_value]);
					unset($_REQUEST[dbKITnewsletterCfg::field_value]);
					$data[dbKITnewsletterCfg::field_label] = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_label]);
					unset($_REQUEST[dbKITnewsletterCfg::field_label]);
					$data[dbKITnewsletterCfg::field_description] = utf8_decode($_REQUEST[dbKITnewsletterCfg::field_description]);
					unset($_REQUEST[dbKITnewsletterCfg::field_description]);
					$data[dbKITnewsletterCfg::field_status] = dbKITnewsletterCfg::status_active;
					$data[dbKITnewsletterCfg::field_update_by] = $tools->getDisplayName();
					$data[dbKITnewsletterCfg::field_update_when] = date('Y-m-d H:i:s');
					$id = -1;
					if (!$dbNewsletterCfg->sqlInsertRecord($data, $id)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterCfg->getError()));
						return false; 
					}
					$message .= sprintf(kit_msg_cfg_add_success, $id, $data[dbKITnewsletterCfg::field_name]);		
				}
				else {
					// Daten unvollstaendig
					$message .= kit_msg_cfg_add_incomplete;
				}
			}
		} 
		if (!empty($message)) $this->setMessage($message);
		return $this->dlgConfig();
	} // saveConfig()
  
  public function dlgNewsletter() {
  	global $dbNewsletterPreview;
  	global $parser;
  	global $dbProvider;
  	global $dbContact;
  	global $dbContactArrayCfg;
  	global $newsletterCommands;
  	global $dbNewsletterTemplates;
  	global $dbNewsletterArchive;
    global $dbRegister;
    global $tools;
  	global $dbNewsletterCfg;
  	// bestimmtes Archiv laden?
  	isset($_REQUEST[dbKITnewsletterArchive::field_id]) ? $aid = $_REQUEST[dbKITnewsletterArchive::field_id] : $aid = -1;
  	// existiert bereits eine Vorschau?
  	isset($_REQUEST[dbKITnewsletterPreview::field_id]) ? $pid = $_REQUEST[dbKITnewsletterPreview::field_id] : $pid = -1;

    // kit_contact mit kit_register abgleichen?
    $cfgAdjustRegister = $dbNewsletterCfg->getValue(dbKITnewsletterCfg::cfgAdjustRegister);

    // Simulation aktiv?
    $cfgSimulateMailing = $dbNewsletterCfg->getValue(dbKITnewsletterCfg::cfgSimulateMailing);
    if ($cfgSimulateMailing) $this->setMessage (kit_msg_newsletter_simulate_mailing.$this->getMessage());

  	if ($aid != -1) {
  		// Archiv laden
  		$where = array();
  		$where[dbKITnewsletterArchive::field_id] = $aid;
  		$result = array();
  		if (!$dbNewsletterArchive->sqlSelectRecord($where, $result)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
				return false;
  		}
  		if (count($result) < 1) {
				// Datensatz nicht gefunden
				$this->setError(sprintf(kit_error_item_id, $aid));
				return false;
			}
			$result = $result[0];
			foreach ($result as $key => $value) {
				switch ($key):
				case dbKITnewsletterArchive::field_distributions:
				case dbKITnewsletterArchive::field_groups:
					$value = explode(',', $value);
					break;
				endswitch;
				$_REQUEST[$key] = $value;
			}
  	}
  	elseif ($pid != -1) {
  		$where = array();
			$where[dbKITnewsletterPreview::field_id] = $pid;
			$preview = array();
			if (!$dbNewsletterPreview->sqlSelectRecord($where, $preview)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
				return false; 
			}
			if (count($preview) < 1) {
				// Datensatz nicht gefunden
				$this->setError(sprintf(kit_error_item_id, $pid));
				return false;
			}
			$prev_array = explode(dbKITnewsletterPreview::array_separator, $preview[0][dbKITnewsletterPreview::field_view]);
			foreach ($prev_array as $item) {
				list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
				switch ($key):
				case dbKITnewsletterArchive::field_distributions:
				case dbKITnewsletterArchive::field_groups:
					$value = explode(',', $value);
					break;
				endswitch;
				$_REQUEST[$key] = $value;
			}
  	}
  	$items = '';
  	$row = new Dwoo_Template_File($this->template_path.'backend.newsletter.dlg.tr.htt');
		
  	// show newsletter archive
  	$SQL = sprintf(	"SELECT %s, %s, %s FROM %s ORDER BY %s DESC",
  									dbKITnewsletterArchive::field_id,
  									dbKITnewsletterArchive::field_subject,
  									dbKITnewsletterArchive::field_update_when,
  									$dbNewsletterArchive->getTableName(),
  									dbKITnewsletterArchive::field_id);
  	$newsletter_archive = array();
  	if (!$dbNewsletterArchive->sqlExec($SQL, $newsletter_archive)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
  		return false;
  	}
  	if (count($newsletter_archive) > 0) {
  		// show newsletter archive for selection
  		$option = sprintf('<option value="-1">%s</option>', kit_text_please_select);
  		foreach ($newsletter_archive as $item) {
  			$option .= sprintf(	'<option value="%s">[%04d] %s - %s',
  													$item[dbKITnewsletterArchive::field_id],
  													$item[dbKITnewsletterArchive::field_id],
  													date(kit_cfg_date_time_str, strtotime($item[dbKITnewsletterArchive::field_update_when])),
  													$item[dbKITnewsletterArchive::field_subject]);
  		}
  		$archive = sprintf(	'<select name="%s" onchange="javascript: window.location = \'%s\' + this.value; return false;">%s</select>', 
  												dbKITnewsletterArchive::field_id,
  												sprintf('%s&%s=%s&%s=',
  																$this->page_link,
  																self::request_action,
  																self::action_newsletter,
  																dbKITnewsletterArchive::field_id ), 
  												$option);
  		$items .= $parser->get($row, array('label' => sprintf('<i>%s</i>', kit_label_newsletter_archive_select), 'value' => $archive));
  		$items .= $parser->get($row, array('label' => '', 'value' => '<hr />'));
  	}
  	
  	// get all service provider for selection list
		$SQL = sprintf(	"SELECT %s,%s,%s FROM %s WHERE %s != '%s' ORDER BY %s ASC",
										dbKITprovider::field_id,
										dbKITprovider::field_name,
										dbKITprovider::field_email,
										$dbProvider->getTableName(),
										dbKITprovider::field_status,
										dbKITprovider::status_deleted,
										dbKITprovider::field_name);
		$provider_list = array();
		
		if (!$dbProvider->sqlExec($SQL, $provider_list)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
			return false;
		}
		if (count($provider_list) < 1) {
			// error: no provider defined!
			$this->setError(kit_error_no_provider_defined);
			return false;
		}
		// select provider
		$option = sprintf('<option value="-1">%s</option>', kit_text_please_select);
  	isset($_REQUEST[dbKITnewsletterArchive::field_provider]) ? $id = $_REQUEST[dbKITnewsletterArchive::field_provider] : $id = -1;
		foreach ($provider_list as $item) {
			($item[dbKITprovider::field_id] == $id) ? $selected = ' selected="selected"' : $selected = '';
			$option .= sprintf(	'<option value="%s"%s>%s</option>', 
													$item[dbKITprovider::field_id],
													$selected, 
													sprintf('[%s] %s', 
																	$item[dbKITprovider::field_email], 
																	$item[dbKITprovider::field_name]));
	  }
		$provider = sprintf('<select name="%s">%s</select>', dbKITnewsletterArchive::field_provider, $option);
		$items .= $parser->get($row, array('label' => kit_label_provider, 'value' => $provider));
		
  	// Select Newsletter Type
  	$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s='%s' ORDER BY %s ASC",
										$dbContactArrayCfg->getTableName(),
										dbKITcontactArrayCfg::field_type,
										dbKITcontactArrayCfg::type_newsletter,
										dbKITcontactArrayCfg::field_status,
										dbKITcontactArrayCfg::status_active,
										dbKITcontactArrayCfg::field_value);
		$newsletter_array = array();
		if (!$dbContactArrayCfg->sqlExec($SQL, $newsletter_array)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
			return false;
		}
		// walk through newsletter array
		$news = '';
		isset($_REQUEST[dbKITnewsletterArchive::field_groups]) ? $news_val = $_REQUEST[dbKITnewsletterArchive::field_groups] : $news_val = array();
		foreach ($newsletter_array as $news_item) {
      // wenn erforderlich kit_contact mit der db_kit_register abgleichen...
      if ($cfgAdjustRegister) {
        // Newsletter Adressaten ermitteln...
        $SQL = sprintf(	'SELECT %1$s, %2$s, %3$s FROM %4$s WHERE %5$s=\'%6$s\' AND ((%7$s LIKE \'%8$s\') OR (%7$s LIKE \'%8$s,%%\') OR (%7$s LIKE \'%%,%8$s\') OR (%7$s LIKE \'%%%8$s,%%\'))',
                        dbKITcontact::field_email, // 1
                        dbKITcontact::field_email_standard, // 2
                        dbKITcontact::field_id, // 3
                        $dbContact->getTableName(), // 4
                        dbKITcontact::field_status, // 5
                        dbKITcontact::status_active, // 6
                        dbKITcontact::field_newsletter, // 7
                        $news_item[dbKITcontactArrayCfg::field_identifier]); // 8
        $result = array();
        if (!$dbContact->sqlExec($SQL, $result)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
        $this->setMessage($this->getMessage().sprintf(kit_msg_newsletter_adjust_register, $news_item[dbKITcontactArrayCfg::field_identifier]));
        $count = 0;
        $email_array = array();
        foreach ($result as $contact) {
          $emails = explode(';', $contact[dbKITcontact::field_email]);
          list($type, $email) = explode('|', $emails[$contact[dbKITcontact::field_email_standard]]);
          $where = array(dbKITregister::field_email => $email);
          $email_array[] = $email;
          $register = array();
          if (!$dbRegister->sqlSelectRecord($where, $register)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
            return false;
          }
          if (count($register) > 0) {
            // Treffer
            $newsletters = explode(',', $register[0][dbKITregister::field_newsletter]);
            if (in_array($news_item[dbKITcontactArrayCfg::field_identifier], $newsletters)) {
              // befindet sich in der db_kit_register
              $count++;
            }
            else {
              // befindet sich nicht in der db_kit_register - Annahme: dieser Newsletter wurde abbestellt, ignorieren...
            }
          }
          else {
            // Datensatz nicht gefunden, neu anlegen
            $data = array();
            $data[dbKITregister::field_contact_id] = $contact[dbKITcontact::field_id];
            $data[dbKITregister::field_email] = $email;
            $data[dbKITregister::field_status] = dbKITregister::status_active;
            $data[dbKITregister::field_username] = $email;
            $data[dbKITregister::field_password] = md5($email);
            $data[dbKITregister::field_register_key] = $tools->createGUID();
            $data[dbKITregister::field_register_date] = date('Y-m-d H:i:s');
            $data[dbKITregister::field_register_confirmed] = date('Y-m-d H:i:s');
            $data[dbKITregister::field_newsletter] = $news_item[dbKITcontactArrayCfg::field_identifier];
            $data[dbKITregister::field_update_by] = 'SYSTEM';
            $data[dbKITregister::field_update_when] = date('Y-m-d H:i:s');
            $aid = -1;
            if (!$dbRegister->sqlInsertRecord($data, $aid)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
              return false;
            }
            // increase counter
            $count++;
          }
        } // foreach
      } // cfgAdjustRegister
      else {
        // Newsletter Adressaten ermitteln...
        $SQL = sprintf(	'SELECT count(%1$s) FROM %2$s WHERE %3$s=\'%4$s\' AND ((%5$s LIKE \'%6$s\') OR (%5$s LIKE \'%6$s,%%\') OR (%5$s LIKE \'%%,%6$s\') OR (%5$s LIKE \'%%%6$s,%%\'))',
                        dbKITregister::field_id, // 1
                        $dbRegister->getTableName(), // 2
                        dbKITregister::field_status, // 3
                        dbKITregister::status_active, // 4
                        dbKITregister::field_newsletter, // 5
                        $news_item[dbKITcontactArrayCfg::field_identifier]); // 6
        $result = array();
        if (!$dbRegister->sqlExec($SQL, $result)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
          return false;
        }        
        $count = $result[0][sprintf('count(%s)', dbKITregister::field_id)];
      }
      
			(in_array($news_item[dbKITcontactArrayCfg::field_identifier], $news_val)) ? $checked=' checked="checked"' : $checked = '';
			$news .= sprintf('<input type="checkbox" name="%s[]" value="%s"%s /> %s (<b>%d</b> %s)<br />',
											dbKITnewsletterArchive::field_groups,
											$news_item[dbKITcontactArrayCfg::field_identifier],
											$checked,
											$news_item[dbKITcontactArrayCfg::field_value],
											$count, //$result[0]['COUNT('.dbKITcontact::field_id.')'],
											kit_text_records);											
		} // foreach
		$items .= $parser->get($row, array('label' => kit_label_newsletter, 'value' => $news));
		
		// Verteiler ermitteln
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s='%s' ORDER BY %s ASC",
										$dbContactArrayCfg->getTableName(),
										dbKITcontactArrayCfg::field_type,
										dbKITcontactArrayCfg::type_distribution,
										dbKITcontactArrayCfg::field_status,
										dbKITcontactArrayCfg::status_active,
										dbKITcontactArrayCfg::field_value);
		$distribution_array = array();
		if (!$dbContactArrayCfg->sqlExec($SQL, $distribution_array)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
			return false;
		}
		// walk through distribution array
		$dist = '';
		isset($_REQUEST[dbKITnewsletterArchive::field_distributions]) ? $dist_val = $_REQUEST[dbKITnewsletterArchive::field_distributions] : $dist_val = array();
		foreach ($distribution_array as $dist_item) {
			// Verteiler Adressaten ermitteln...
      $SQL = sprintf(	'SELECT count(%1$s) FROM %2$s WHERE %3$s=\'%4$s\' AND ((%5$s LIKE \'%6$s\') OR (%5$s LIKE \'%6$s,%%\') OR (%5$s LIKE \'%%,%6$s\') OR (%5$s LIKE \'%%%6$s,%%\'))',
                      dbKITcontact::field_id, // 1
                      $dbContact->getTableName(), // 2
                      dbKITcontact::field_status, // 3
                      dbKITcontact::status_active, // 4
                      dbKITcontact::field_distribution, // 5
                      $dist_item[dbKITcontactArrayCfg::field_identifier]); // 6
      $result = array();
      if (!$dbContact->sqlExec($SQL, $result)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      $count = $result[0][sprintf('count(%s)', dbKITcontact::field_id)];
      (in_array($dist_item[dbKITcontactArrayCfg::field_identifier], $dist_val)) ? $checked=' checked="checked"' : $checked = '';
			$dist .= sprintf('<input type="checkbox" name="%s[]" value="%s"%s /> %s (<b>%d</b> %s)<br />',
											dbKITnewsletterArchive::field_distributions,
											$dist_item[dbKITcontactArrayCfg::field_identifier],
											$checked,
											$dist_item[dbKITcontactArrayCfg::field_value],
											$count, 
											kit_text_records);
		} // foreach
		$items .= $parser->get($row, array('label' => kit_label_distribution, 'value' => $dist));
		
		
		// Template auswaehlen
		(isset($_REQUEST[dbKITnewsletterArchive::field_template])) ? $template_id = $_REQUEST[dbKITnewsletterArchive::field_template] : $template_id = -1;  
  	
  	$where = array();
  	$where[dbKITnewsletterTemplates::field_status] = dbKITnewsletterTemplates::status_active;
  	$templates = array();
  	if (!$dbNewsletterTemplates->sqlSelectRecord($where, $templates)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError()));
  		return false;
  	}
  	if (count($templates) < 1) {
  		// es existiert noch kein Template
  		$this->setMessage(kit_msg_newsletter_tpl_missing);
  		// Navigation umschalten
  		$this->overwriteNavigation = self::action_template;
  		return $this->dlgTemplate();
  	}
  	$template_select = sprintf('<option value="-1">%s</option>', kit_text_please_select);
  	foreach ($templates as $item) {
  		($item[dbKITnewsletterTemplates::field_id] == $template_id) ? $selected = ' selected="selected"' : $selected = '';
			$template_select .= sprintf('<option value="%s"%s>%s</option>', 
																	$item[dbKITnewsletterTemplates::field_id], 
																	$selected, 
																	$item[dbKITnewsletterTemplates::field_name]);
  	}
  	$template_select = sprintf(	'<select name="%s">%s</select>', 
  															dbKITnewsletterArchive::field_template, 
  															$template_select);
  	$items .= $parser->get($row, array('label' => kit_label_newsletter_tpl_select, 'value' => $template_select));
		
		
  	// Subject
		isset($_REQUEST[dbKITnewsletterArchive::field_subject]) ? $sub = $_REQUEST[dbKITnewsletterArchive::field_subject] : $sub = '';
		$subject = sprintf(	'<input type="text" name="%s" value="%s" />', dbKITnewsletterArchive::field_subject, $sub);
		$items .= $parser->get($row, array('label' => kit_label_mail_subject, 'value' => $subject));
		
  	// HTML Edit
  	isset($_REQUEST[dbKITnewsletterArchive::field_html]) ? $content=stripcslashes($_REQUEST[dbKITnewsletterArchive::field_html]) : $content = '';
		ob_start();
			show_wysiwyg_editor(dbKITnewsletterArchive::field_html, dbKITnewsletterArchive::field_html, $content, '99%', '400px');
			$editor = ob_get_contents();
		ob_end_clean();
		
		// TEXT Edit
		$txt_editor = sprintf('<textarea name="%s" rows="20">%s</textarea>',
  												dbKITnewsletterArchive::field_text,
  												isset($_REQUEST[dbKITnewsletterArchive::field_text]) ? $_REQUEST[dbKITnewsletterArchive::field_text] : '');
		
		// show commands list
  	$commands = '';
  	$cmd_array = $newsletterCommands->cmd_array;
  	ksort($cmd_array); 
  	foreach ($cmd_array as $key => $hint) {
  		$commands .= sprintf('<option value="%s" title="%s">%s</option>', $key, $hint, $key);
  	}
  	$commands = sprintf('<select name="%s" size="%d" >%s</select>',
  											self::request_command,
  											count($cmd_array),
  											$commands);
  	
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_newsletter_create);
		}		
		
  	$data = array(
  		'form_name'						=> 'newsletter_dlg',
  		'form_action'					=> $this->page_link,
  		'action_name'					=> self::request_action,
  		'action_value'				=> self::action_newsletter_check,
  		'preview_name'				=> dbKITnewsletterPreview::field_id,
  		'preview_value'				=> $pid,
  		'intro'								=> $intro,
  		'items'								=> $items,
  		'label_html_editor'		=> kit_label_html_format,
  		'value_html_editor'		=> $editor,
  		'label_text_editor'		=> kit_label_newsletter_tpl_text,
  		'value_text_editor'		=> $txt_editor,
  		'value_commands'			=> $commands,
  		'btn_preview'					=> kit_btn_preview,
  		'btn_abort'						=> kit_btn_abort,
  		'abort_location'			=> $this->page_link
  	);
  	return $parser->get($this->template_path.'backend.newsletter.dlg.htt', $data);
  } // dlgNewsletter
  
  /**
   * Prueft die Angaben zum Newsletter, gibt Meldungen aus und ruft die Vorschau auf
   * 
   * @return STR Dialog
   */
  public function checkNewsletter() {
  	global $dbNewsletterPreview;
  	global $dbNewsletterArchive;
  	
  	// check provider
  	if ($_REQUEST[dbKITnewsletterArchive::field_provider] == -1) {
  		$this->setMessage(kit_msg_newsletter_new_no_provider);
  		return $this->dlgNewsletter();
  	}
  	// check newsletter groups
  	if (!isset($_REQUEST[dbKITnewsletterArchive::field_groups]) && !isset($_REQUEST[dbKITnewsletterArchive::field_distributions]))  {
  		$this->setMessage(kit_msg_newsletter_new_no_groups);
  		return $this->dlgNewsletter();
  	}
  	// check Template
  	if ($_REQUEST[dbKITnewsletterArchive::field_template] == -1) {
  		$this->setMessage(kit_msg_newsletter_new_no_template);
  		return $this->dlgNewsletter();
  	}
  	// check Subject
  	if (empty($_REQUEST[dbKITnewsletterArchive::field_subject])) {
  		$this->setMessage(kit_msg_newsletter_new_no_subject);
  		return $this->dlgNewsletter();
  	}
  	// HTML Format
  	if (empty($_REQUEST[dbKITnewsletterArchive::field_html])) {
  		$this->setMessage(kit_msg_newsletter_new_no_html);
  		return $this->dlgNewsletter();
  	}
  	// TEXT Format
  	if (empty($_REQUEST[dbKITnewsletterArchive::field_text])) {
  		// automatisch generieren und zurueck...
  		$_REQUEST[dbKITnewsletterArchive::field_text] = strip_tags($_REQUEST[dbKITnewsletterArchive::field_html]);
  		$this->setMessage(kit_msg_newsletter_new_no_text);
  		return $this->dlgNewsletter();
  	}
  	
  	// als Vorschau sichern
  	if (isset($_REQUEST[dbKITnewsletterPreview::field_id]) && $_REQUEST[dbKITnewsletterPreview::field_id] !== -1) {
  		// alte Preview loeschen...
  		$where = array();
  		$where[dbKITnewsletterPreview::field_id] = $_REQUEST[dbKITnewsletterPreview::field_id];
  		if (!$dbNewsletterPreview->sqlDeleteRecord($where)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
  			return false;
  		}
  	}
  	
  	$view = array();
  	foreach ($dbNewsletterArchive->getFields() as $key => $value) {
  		if (isset($_REQUEST[$key])) $value = $_REQUEST[$key];
  		switch ($key):
  		case dbKITnewsletterArchive::field_distributions:
  		case dbKITnewsletterArchive::field_groups:
  			if (!empty($value)) $value = implode(',', $value);
  			break;
  		endswitch;
  		$view[] = sprintf('%s%s%s', $key, dbKITnewsletterPreview::array_separator_value, $value);
  	}
  	$preview = array();
  	$preview[dbKITnewsletterPreview::field_view] = implode(dbKITnewsletterPreview::array_separator, $view);
  	$pid = -1;
  	if (!$dbNewsletterPreview->sqlInsertRecord($preview, $pid)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
  		return false;
  	}
  	$_REQUEST[dbKITnewsletterPreview::field_id] = $pid;
  	return $this->dlgCheckNewsletterPreview();
  } // checkNewsletter()
  
  /**
   * Zeigt einen Dialog zum Pruefen der Vorschau fuer den Newsletter an
   * 
   * @return STR dialog
   */
  public function dlgCheckNewsletterPreview() {
  	global $dbCfg;
  	global $parser;
  	
  	if (!isset($_REQUEST[dbKITnewsletterPreview::field_id])) {
  		$this->setError(kit_error_preview_id_missing);
  		return false;
  	}
  	$request_link = $dbCfg->getValue(dbKITcfg::cfgKITRequestLink);
  	
  	$data = array(
			'header_preview'						=> kit_header_preview,
			'intro'											=> kit_intro_preview,
			'html_label'								=> kit_label_newsletter_tpl_html,
			'html_source'								=> sprintf(	'%s?%s=%s&%s=%s&%s=%s',
																							$request_link,
																							kitRequest::request_action,
																							kitRequest::action_preview_newsletter,
																							kitRequest::request_id,
																							$_REQUEST[dbKITnewsletterPreview::field_id],
																							kitRequest::request_type,
																							kitRequest::action_type_html),
			'text_label'								=> kit_label_newsletter_tpl_text_preview,
			'text_source'								=> sprintf(	'%s?%s=%s&%s=%s&%s=%s',
																							$request_link,
																							kitRequest::request_action,
																							kitRequest::action_preview_newsletter,
																							kitRequest::request_id,
																							$_REQUEST[dbKITnewsletterPreview::field_id],
																							kitRequest::request_type,
																							kitRequest::action_type_text),		
			'form_action'								=> $this->page_link,
			'action_name'								=> self::request_action,
			'action_save_value'					=> self::action_newsletter_save,
			'preview_name'							=> dbKITnewsletterPreview::field_id,
			'preview_value'							=> $_REQUEST[dbKITnewsletterPreview::field_id],
			'btn_save'									=> kit_btn_send,
			'action_edit_value'					=> self::action_newsletter,
			'btn_edit'									=> kit_btn_edit,
			'btn_abort'									=> kit_btn_abort,
			'abort_location'						=> $this->page_link
		);
		return $parser->get($this->template_path.'backend.newsletter.preview.htt', $data);
  } // dlgCheckNewsletterPreview()
  
  /**
   * Sichert den Newsletter und startet den Versand...
   * 
   * @return STR dialog 
   */
  public function saveNewsletter() {
  	global $dbNewsletterPreview;
  	global $dbNewsletterArchive;
  	global $tools;
  	
  	isset($_REQUEST[dbKITnewsletterPreview::field_id]) ? $pid = $_REQUEST[dbKITnewsletterPreview::field_id] : $pid = -1;
  	if ($pid != -1) {
  		$where = array();
			$where[dbKITnewsletterPreview::field_id] = $pid;
			$preview = array();
			if (!$dbNewsletterPreview->sqlSelectRecord($where, $preview)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError()));
				return false; 
			}
			if (count($preview) < 1) {
				// Datensatz nicht gefunden
				$this->setError(sprintf(kit_error_item_id, $pid));
				return false;
			}
			$prev_array = explode(dbKITnewsletterPreview::array_separator, $preview[0][dbKITnewsletterPreview::field_view]);
			foreach ($prev_array as $item) {
				list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
				$_REQUEST[$key] = $value;				
			}
  	}
  	else {
  		$this->setError(sprintf('[%s %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $pid)));
  		return false;
  	}
  	// Datensatz sichern
  	$data = array();
  	foreach ($dbNewsletterArchive->getFields() as $key => $value) {
  		if (isset($_REQUEST[$key])) $value = $_REQUEST[$key];
  		switch ($key):
  		case dbKITnewsletterArchive::field_update_by:
  			$value = $tools->getDisplayName();
  			break;
  		case dbKITnewsletterArchive::field_update_when:
  			$value = date('Y-m-d H:i:s');
  			break;
  		endswitch;
  		$data[$key] = $value;
  	}
  	unset($data[dbKITnewsletterArchive::field_id]);
  	$archive_id = -1;
  	if (!$dbNewsletterArchive->sqlInsertRecord($data, $archive_id)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
  		return false;
  	}
  	return $this->processNewsletter($archive_id);
  } // saveNewsletter()
  
  /**
   * Wickelt den Newsletter Versand ab
   * 
   * @return STR result
   */
  public function processNewsletter($id=-1) {
  	global $dbNewsletterCfg;
		global $dbNewsletterArchive;
		global $dbRegister;
		global $dbNewsletterProcess;
		global $dbContact;
		
  	// Simulation?
    $cfgSimulateMailing = $dbNewsletterCfg->getValue(dbKITnewsletterCfg::cfgSimulateMailing);
  	
    // max. Paketgroesse
    $cfgMaxPackageSize = $dbNewsletterCfg->getValue(dbKITnewsletterCfg::cfgMaxPackageSize);
    if ($cfgMaxPackageSize > 100) {
    	// max. zulaessiger Wert ist 100
    	$cfgMaxPackageSize = 100;
    	$dbNewsletterCfg->setValueByName(100, dbKITnewsletterCfg::cfgMaxPackageSize);
    }
    
    // Newsletter auslesen
  	$where = array();
  	$where[dbKITnewsletterArchive::field_id] = $id;
  	$newsletter = array();
  	if (!$dbNewsletterArchive->sqlSelectRecord($where, $newsletter)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
  		return false;
  	}
  	if (count($newsletter) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
  		return false;
  	}
  	$newsletter = $newsletter[0];
    
  	// Newsletter Gruppen
  	$nl_groups = (!empty($newsletter[dbKITnewsletterArchive::field_groups])) ? explode(',', $newsletter[dbKITnewsletterArchive::field_groups]) : array();
  	// Verteiler Gruppen
		$dist_groups = (!empty($newsletter[dbKITnewsletterArchive::field_distributions])) ? explode(',', $newsletter[dbKITnewsletterArchive::field_distributions]) : array();
		
  	// Sammelarray fuer NEWSLETTER Adressaten
		$register_ids = array();
		// Sammelarray fuer VERTEILER Addressaten
		$dist_ids = array();
		
		$count_packages = 0;
	    
		$QUERY = 'SELECT %1$s%2$s FROM %3$s WHERE %4$s=\'%5$s\' AND ((%6$s LIKE \'%7$s\') OR (%6$s LIKE \'%7$s,%%\') OR (%6$s LIKE \'%%,%7$s\') OR (%6$s LIKE \'%%%7$s,%%\')) LIMIT %8$d,%9$d';
		
		/**
     * Verteiler erfassen
     */ 
    if (!empty($dist_groups)) {
	    foreach ($dist_groups as $dist) {
				// First read only ONE row from the table and COUNT the ROWs
	    	$SQL = sprintf(	$QUERY,
	      								'SQL_CALC_FOUND_ROWS ',
	      								dbKITcontact::field_id,
												$dbContact->getTableName(),
	                      dbKITcontact::field_status,
												dbKITcontact::status_active,
												dbKITcontact::field_distribution,
												$dist,
												0,
												1);
				$addresses = array();
				if (!$dbContact->sqlExec($SQL, $addresses)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
					return false;
				}
				// rows total
				$rows_total = $dbContact->sqlFoundRows();
				
				// Step to the table to prevent exceeded memory usage
				for ($i=0; $i<$rows_total; $i=$i+$cfgMaxPackageSize) {
					$SQL = sprintf(	$QUERY,
		      								'',
		      								dbKITcontact::field_id,
													$dbContact->getTableName(),
		                      dbKITcontact::field_status,
													dbKITcontact::status_active,
													dbKITcontact::field_distribution,
													$dist,
													$i,
													$cfgMaxPackageSize);
					$addresses = array();
					if (!$dbContact->sqlExec($SQL, $addresses)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
						return false;
					}
					// save unique IDs in $dist_ids...
					foreach ($addresses as $address) {
						if (!in_array($address[dbKITregister::field_contact_id], $dist_ids)) {
							$dist_ids[] = $address[dbKITregister::field_contact_id];
						}
					}		
				} // for			
			} // foreach
	  	
	    $count = 1;
	    $id_array = array();
	    foreach ($dist_ids as $item) {
	    	if ($count > $cfgMaxPackageSize) {
	    		// Max. Paketgroesse erreicht, Paket sichern
	    		$data = array(
	    			dbKITnewsletterProcess::field_archiv_id => $id,
	    			dbKITnewsletterProcess::field_count => $count-1,
	    			dbKITnewsletterProcess::field_is_done => 0,
	    			dbKITnewsletterProcess::field_job_created_dt => date('Y-m-d H:i:s'),
	    			dbKITnewsletterProcess::field_register_ids => '', 
	    			dbKITnewsletterProcess::field_distribution_ids => implode(',', $id_array),
	    			dbKITnewsletterProcess::field_simulate => $cfgSimulateMailing
	    		);
	    		if (!$dbNewsletterProcess->sqlInsertRecord($data)) {
	    			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
	    			return false;
	    		}
	    		$count = 1;
	    		$count_packages++;
	    		$id_array = array();
	    	}
	    	$id_array[] = $item;
	    	$count++;
	    } // foreach
	    
	    if (count($id_array) > 0) {
	    	// Uebrige IDs in einem Paket sichern
	    	$data = array(
	    		dbKITnewsletterProcess::field_archiv_id => $id,
	    		dbKITnewsletterProcess::field_count => $count-1,
	    		dbKITnewsletterProcess::field_is_done => 0,
	    		dbKITnewsletterProcess::field_job_created_dt => date('Y-m-d H:i:s'),
	    		dbKITnewsletterProcess::field_register_ids => '',
	    		dbKITnewsletterProcess::field_distribution_ids => implode(',', $id_array),
	    		dbKITnewsletterProcess::field_simulate => $cfgSimulateMailing
	    	);
	    	if (!$dbNewsletterProcess->sqlInsertRecord($data)) {
	    		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
	    		return false;
	    	}
	    	$count_packages++;	
	    }
    } // !empty($dist_groups)
    
    /**
     * Newsletter durchlaufen
     */
    if (!empty($nl_groups)) {
			foreach ($nl_groups as $nl) { 
				// get contact ID's from selected newsletter...
				// First read only ONE row from the table and COUNT the ROWs
	    	$SQL = sprintf(	$QUERY,
	      								'SQL_CALC_FOUND_ROWS ',
	      								dbKITregister::field_contact_id,
												$dbRegister->getTableName(),
	                      dbKITregister::field_status,
												dbKITregister::status_active,
												dbKITregister::field_newsletter,
												$nl,
												0,
												1);
				$addresses = array();
				if (!$dbRegister->sqlExec($SQL, $addresses)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
					return false;
				}
				// rows total
				$rows_total = $dbRegister->sqlFoundRows();
				
				// Step to the table to prevent exceeded memory usage
				for ($i=0; $i<$rows_total; $i=$i+$cfgMaxPackageSize) {
					$SQL = sprintf(	$QUERY,
		      								'',
		      								dbKITregister::field_contact_id,
													$dbRegister->getTableName(),
		                      dbKITregister::field_status,
													dbKITregister::status_active,
													dbKITregister::field_newsletter,
													$nl,
													$i,
													$cfgMaxPackageSize);
					$addresses = array();
					if (!$dbRegister->sqlExec($SQL, $addresses)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
						return false;
					}
					// save unique IDs in $register_ids AND $dist_ids...
					foreach ($addresses as $address) {
						if ((!in_array($address[dbKITregister::field_contact_id], $register_ids)) &&
								(!in_array($address[dbKITregister::field_contact_id], $dist_ids))) {
							$register_ids[] = $address[dbKITregister::field_contact_id];
						}
					}		
				} // for			
			} // foreach
		
			$count = 1;
	    $id_array = array();
	    foreach ($register_ids as $item) { 
	    	if ($count > $cfgMaxPackageSize) {
	    		// Max. Paketgroesse erreicht, Paket sichern
	    		$data = array(
	    			dbKITnewsletterProcess::field_archiv_id => $id,
	    			dbKITnewsletterProcess::field_count => $count-1,
	    			dbKITnewsletterProcess::field_is_done => 0,
	    			dbKITnewsletterProcess::field_job_created_dt => date('Y-m-d H:i:s'),
	    			dbKITnewsletterProcess::field_register_ids => implode(',', $id_array),
	    			dbKITnewsletterProcess::field_distribution_ids => '',
	    			dbKITnewsletterProcess::field_simulate => $cfgSimulateMailing
	    		);
	    		if (!$dbNewsletterProcess->sqlInsertRecord($data)) {
	    			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
	    			return false;
	    		}
	    		$count = 1;
	    		$count_packages++;
	    		$id_array = array();
	    	}
	    	$id_array[] = $item;
	    	$count++;
	    } // foreach
	    
	    if (count($id_array) > 0) {
	    	// Uebrige IDs in einem Paket sichern
	    	$data = array(
	    		dbKITnewsletterProcess::field_archiv_id => $id,
	    		dbKITnewsletterProcess::field_count => $count-1,
	    		dbKITnewsletterProcess::field_is_done => 0,
	    		dbKITnewsletterProcess::field_job_created_dt => date('Y-m-d H:i:s'),
	    		dbKITnewsletterProcess::field_register_ids => implode(',', $id_array),
	    		dbKITnewsletterProcess::field_distribution_ids => '',
	    		dbKITnewsletterProcess::field_simulate => $cfgSimulateMailing
	    	);
	    	if (!$dbNewsletterProcess->sqlInsertRecord($data)) {
	    		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
	    		return false;
	    	}
	    	$count_packages++;	
	    }
    } // !empty($nl_groups)
    
    // Job finished
    $this->setMessage(sprintf(kit_msg_newsletter_new_packages_created, $count_packages));
    return $this->dlgCronjobsActive();
  } // processNewsletter()
  
  public function dlgCronjobsActive() {
  	global $dbNewsletterProcess;
  	global $dbNewsletterArchive;
  	global $parser;
  	global $dbCfg;
  	global $tools;
  	global $dbCronjobData;
  	
  	// Pruefen ob ein CronjobKey existiert
  	$cronjob_key = $dbCfg->getValue(dbKITcfg::cfgCronjobKey);
  	if (strlen($cronjob_key) < 3) { 
  		$key = $tools->generatePassword(); 
  		$dbCfg->setValueByName($key, dbKITcfg::cfgCronjobKey);
  	}
  	
  	$cronjob_running = (false !== ($cronjob_last_call = $dbCronjobData->getLastCronjobCall())) ? true : false;
  	
  	// offene Auftraege ermitteln
  	$where = array(dbKITnewsletterProcess::field_is_done => 0);
  	$process = array();
  	if (!$dbNewsletterProcess->sqlSelectRecord($where, $process)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
  		return false;
  	}
  	if (count($process) < 1) {
  		// keine Eintraege vorhanden
  		if ($cronjob_running) {
  			return sprintf('<div class="intro">%s</div>', sprintf(kit_msg_cronjob_last_call, date(kit_cfg_date_time_str, $cronjob_last_call)));
  		}
  		else {
  			return sprintf('<div class="intro">%s</div>', kit_intro_cronjobs);
  		}
  	}
  	else {
  		// Liste anzeigen
  		$row = new Dwoo_Template_File($this->template_path.'backend.newsletter.cronjob.active.list.tr.htt');
  		$rows = '';
			$flipflop = true;
			
  		foreach ($process as $job) {
  			($flipflop) ? $flipflop = false : $flipflop = true;
				($flipflop) ? $class = 'flip' : $class = 'flop';
				$where = array(dbKITnewsletterArchive::field_id => $job[dbKITnewsletterProcess::field_archiv_id]);
				$archiv = array();
				if (!$dbNewsletterArchive->sqlSelectRecord($where, $archiv)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterArchive->getError()));
					return false;
				}
				$newsletter = (count($archiv) > 0) ? $archiv[0][dbKITnewsletterArchive::field_subject] : kit_text_unknown; 
				$data = array(
					'class'				=> $class,
					'select'			=> sprintf('<input type="checkbox" name="%s[]" value="%s" />', dbKITnewsletterProcess::field_id, $job[dbKITnewsletterProcess::field_id]),
					'id'					=> sprintf('%05d', $job[dbKITnewsletterProcess::field_id]),
					'created'			=> date(kit_cfg_date_time_str, strtotime($job[dbKITnewsletterProcess::field_update_when])),
					'process'			=> ($job[dbKITnewsletterProcess::field_simulate] == 1) ? kit_text_process_simulate : kit_text_process_execute,
					'count'				=> $job[dbKITnewsletterProcess::field_count],
					'archiv_id'		=> sprintf('%05d', $job[dbKITnewsletterProcess::field_archiv_id]),
					'newsletter'	=> $newsletter 
				);
				$rows .= $parser->get($row, $data);
  		}
  	}
  	
  	// intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_nl_cronjob_active_list);
		}		
		
  	$data = array(
  		'form_name'					=> 'process_list',
  		'form_action'				=> $this->page_link,
  		'action_name'				=> self::request_action,
  		'action_value'			=> self::action_cronjobs_act_check,
  		'header'						=> kit_header_nl_cronjob_active_list,
  		'intro'							=> $intro,
  		'header_select'			=> '',
  		'header_id'					=> kit_label_job_id,
  		'header_created'		=> kit_label_job_created,
  		'header_process'		=> kit_label_job_process,
  		'header_count'			=> kit_label_job_count,
  		'header_archive_id'	=> kit_label_archive_id,
  		'header_newsletter'	=> kit_label_newsletter,
  		'rows'							=> $rows
  	);
   	return $parser->get($this->template_path.'backend.newsletter.cronjob.active.list.htt', $data);
  } // dlgCronjobsActive()
  
  public function dlgCronjobsProtocoll() {
  	global $dbNewsletterProcess;
  	global $parser;
  	
  	$SQL = sprintf(	"SELECT * FROM %s ORDER BY %s DESC LIMIT 200", 
  									$dbNewsletterProcess->getTableName(),
  									dbKITnewsletterProcess::field_job_done_dt);
  	$protocols = array();
  	if (!$dbNewsletterProcess->sqlExec($SQL, $protocols)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterProcess->getError()));
  		return false;
  	}
  	
  	$row = new Dwoo_Template_File($this->template_path.'backend.newsletter.cronjob.protocol.list.tr.htt');
  	$rows = '';
		$flipflop = true;
			
  	foreach ($protocols as $protocol) {
  		($flipflop) ? $flipflop = false : $flipflop = true;
			($flipflop) ? $class = 'flip' : $class = 'flop';
			$data = array(
				'class'			=> $class,
				'pid'				=> sprintf('%010d', $protocol[dbKITnewsletterProcess::field_id]),
				'aid'				=> sprintf('%08d', $protocol[dbKITnewsletterProcess::field_archiv_id]),
				'process'		=> ($protocol[dbKITnewsletterProcess::field_simulate] == 1) ? kit_text_process_simulate : kit_text_process_execute,
				'created'		=> date(kit_cfg_date_time_str, strtotime($protocol[dbKITnewsletterProcess::field_job_created_dt])),	
				'done'			=> date(kit_cfg_date_time_str, strtotime($protocol[dbKITnewsletterProcess::field_job_done_dt])),
				'time'			=> number_format($protocol[dbKITnewsletterProcess::field_job_process_time], 4, kit_cfg_decimal_separator, kit_cfg_thousand_separator),
				'count'			=> $protocol[dbKITnewsletterProcess::field_count],
				'send'			=> $protocol[dbKITnewsletterProcess::field_send]
			);
			$rows .= $parser->get($row, $data);
  	}
  	
  	// intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_nl_cronjob_protocol_list);
		}		
		
		$data = array(
			'header'			=> kit_header_nl_cronjob_protocol_list,
			'intro'				=> $intro,
			'rows'				=> $rows,
			'pid'					=> kit_label_id,
			'aid'					=> kit_label_archive_id,	
			'process'			=> kit_label_job_process,
			'created'			=> kit_label_job_created,
			'done'				=> kit_label_job_done,	
			'time'				=> kit_label_job_time,
			'count'				=> kit_label_job_count,
			'send'				=> kit_label_job_send
		);
		
		return $parser->get($this->template_path.'backend.newsletter.cronjob.protocol.list.htt', $data);
  } // dlgCronjobsProtocoll()
  
} // kitNewsletterDialog

?>