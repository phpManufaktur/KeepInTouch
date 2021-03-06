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


/**
 * General data container for all contacts
 */
class dbKITcontact extends dbConnectLE {

	const field_id										= 'contact_id';

	const field_type									= 'contact_type';
	const field_access								= 'contact_access';
	const field_status								= 'contact_status';

	const field_contact_identifier		= 'contact_identifier';

	const field_company_title					= 'contact_company_title';
	const field_company_name					= 'contact_company_name';
	const field_company_department		= 'contact_company_dept';
	const field_company_additional		= 'contact_company_add';

	const field_person_title					= 'contact_person_title';
	const field_person_title_academic	= 'contact_person_title_academic';
	const field_person_first_name			= 'contact_person_first_name';
	const field_person_last_name			= 'contact_person_last_name';
	const field_person_function				= 'contact_person_function';

	const field_address								= 'contact_address_ids';
	const field_address_standard			= 'contact_address_standard';

	const field_category							= 'contact_category_ids';
	const field_newsletter						= 'contact_newsletter_ids';
	const field_distribution					= 'contact_distribution_ids';

	const field_internet							= 'contact_internet';

	const field_phone									= 'contact_phone';
	const field_phone_standard				= 'contact_phone_standard';

	const field_email									= 'contact_email';
	const field_email_standard				= 'contact_email_standard';

	const field_birthday							= 'contact_birthday';
	const field_contact_since					= 'contact_since';
	const field_contact_note					= 'contact_note';

	const field_contact_language      = 'contact_language';

	const field_picture_id						= 'contact_picture_id';

	const field_free_1								= 'contact_free_field_1';
	const field_free_2								= 'contact_free_field_2';
	const field_free_3								= 'contact_free_field_3';
	const field_free_4								= 'contact_free_field_4';
	const field_free_5								= 'contact_free_field_5';

	const field_free_note_1						= 'contact_free_note_1';
	const field_free_note_2						= 'contact_free_note_2';

	const field_update_when						= 'contact_update_when';
	const field_update_by							= 'contact_update_by';

	const access_internal							= 'accInternal'; //1;
	const access_public								= 'accPublic'; //2;

	public $access_array = array(
		self::access_internal		=> kit_contact_access_internal,
		self::access_public			=> kit_contact_access_public
	);

	const type_person									= 'typePerson';//1;
	const type_company								= 'typeCompany';//2;
	const type_institution						= 'typeInstitution'; //3;

	public $type_array = array(
		self::type_person				=> kit_contact_type_person,
		self::type_company			=> kit_contact_type_company,
		self::type_institution	=> kit_contact_type_institution
	);

	const status_active								= 'statusActive'; //1;
	const status_locked								= 'statusLocked'; //2;
	const status_deleted							= 'statusDeleted'; //-1;

	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);

	const company_title_none					= 'companyNone'; //0;
	const company_title_to						= 'companyTo'; //1;

	public $company_title_array = array(
		self::company_title_none	=> kit_contact_company_title_none,
		self::company_title_to		=> kit_contact_company_title_to
	);

	const person_title_mister					= 'titleMister'; //1;
	const person_title_lady						= 'titleLady'; //2;

	public $person_title_array = array(
		self::person_title_mister		=> kit_contact_person_title_mister,
		self::person_title_lady			=> kit_contact_person_title_lady
	);

	const person_title_academic_none	= 'academicNone'; //0;
	const person_title_academic_dr		= 'academicDr'; //1;
	const person_title_academic_prof	= 'academicProf'; //2;

	public $person_title_academic_array = array(
		self::person_title_academic_none	=> kit_contact_person_title_academic_none,
		self::person_title_academic_dr		=> kit_contact_person_title_academic_dr,
		self::person_title_academic_prof	=> kit_contact_person_title_academic_prof
	);

	const category_wb_user				= 'catWBUser'; //1;
	//const category_newsletter			= 'catNewsletter'; //2;

	public $category_array = array(
		self::category_wb_user		=> kit_contact_category_wb_user,
//		self::category_newsletter	=> kit_contact_category_newsletter
	);

	const newsletter_newsletter		= 'newsNewsletter';

	public $newsletter_array = array(
		self::newsletter_newsletter	=> kit_contact_newsletter_newsletter
	);

	const distribution_control		= 'distControl';

	public $distribution_array = array(
		self::distribution_control	=> kit_contact_distribution_control
	);

	const internet_homepage				= 'inetHomepage'; //1;
	const internet_xing						= 'inetXing'; //2;
	const internet_facebook				= 'inetFacebook'; //3;
	const internet_twitter				= 'inetTwitter'; //4;

	public $internet_array = array(
		self::internet_facebook			=> kit_contact_internet_facebook,
		self::internet_homepage			=> kit_contact_internet_homepage,
		self::internet_twitter			=> kit_contact_internet_twitter,
		self::internet_xing					=> kit_contact_internet_xing
	);

	const phone_phone							= 'phonePhone'; //1;
	const phone_handy							= 'phoneHandy'; //2;
	const phone_fax								= 'phoneFax'; //3;

	public $phone_array = array(
		self::phone_phone						=> kit_contact_phone_phone,
		self::phone_handy						=> kit_contact_phone_handy,
		self::phone_fax							=> kit_contact_phone_fax
	);

	const email_private						= 'emailPrivate'; //1;
	const email_business					= 'emailBusiness'; //2;

	public $email_array = array(
		self::email_private					=> kit_contact_email_private,
		self::email_business				=> kit_contact_email_business
	);

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;


  /**
   * Constructor for dbContact
   * @param bool $create_tables
   */
	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_contact');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_type, "VARCHAR(30) NOT NULL DEFAULT '".self::type_person."'");
		$this->addFieldDefinition(self::field_access, "VARCHAR(30) NOT NULL DEFAULT '".self::access_internal."'");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_contact_identifier, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_company_title, "VARCHAR(30) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_company_name, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_company_department, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_company_additional, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_person_title, "VARCHAR(30) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_person_title_academic, "VARCHAR(30) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_person_first_name, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_person_last_name, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_person_function, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_address, "VARCHAR(20) NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_address_standard, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_category, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_newsletter, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_distribution, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_internet, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_phone, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_phone_standard, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_email, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_email_standard, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_birthday, "VARCHAR(20) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_contact_since, "VARCHAR(20) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_contact_note, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_contact_language, "VARCHAR(2) NOT NULL DEFAULT 'en'");
		$this->addFieldDefinition(self::field_free_1, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_2, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_3, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_4, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_5, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_note_1, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_free_note_2, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_picture_id, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
		if (!defined('KIT_INSTALL_RUNNING')) {
			// init arrays
			$this->initArrays();
			// balance WB users...
			$this->checkWBusers();
		}
	} // __construct


	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		// memos installieren
		$dbMemos = new dbKITmemos($this->create_tables);
		if ($dbMemos->isError()) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
			return false;
		}
		return true;
	} // initTables()

	public function initArrays() {
		global $dbContactArrayCfg;
		global $dbContactAddress;
		global $dbProtocol;

		$dbContactArrayCfg->initTables();
		$dbContactAddress->initTables();
		$dbProtocol->initTables();

		// Arbeitsarray zusammenstellen
		$workarray = array(
			dbKITcontactArrayCfg::type_type							=> $this->type_array,
			dbKITcontactArrayCfg::type_access						=> $this->access_array,
			dbKITcontactArrayCfg::type_company_title		=> $this->company_title_array,
			dbKITcontactArrayCfg::type_person_title			=> $this->person_title_array,
			dbKITcontactArrayCfg::type_person_academic	=> $this->person_title_academic_array,
			dbKITcontactArrayCfg::type_category					=> $this->category_array,
			dbKITcontactArrayCfg::type_newsletter				=> $this->newsletter_array,
			dbKITcontactArrayCfg::type_distribution			=> $this->distribution_array,
			dbKITcontactArrayCfg::type_internet					=> $this->internet_array,
			dbKITcontactArrayCfg::type_phone						=> $this->phone_array,
			dbKITcontactArrayCfg::type_email						=> $this->email_array,
			dbKITcontactArrayCfg::type_protocol					=> $dbProtocol->type_array
		);
		// Arbeitsarray durchlaufen und Pflichteintraege pruefen
		foreach ($workarray as $type => $array) {
			//foreach ($array as $item) {
			foreach ($array as $identifier => $value) {
				// Pflichteintraege pruefen
				if (!$dbContactArrayCfg->checkArray($type, $identifier, $value)) return false;
			}
			// Datenbank abfragen und Ergebnis in das jeweilige Array uebertragen
			$where = array();
			$where[dbKITcontactArrayCfg::field_type] = $type;
			$where[dbKITcontactArrayCfg::field_status] = dbKITcontactArrayCfg::status_active;
			$result = array();
			if (!$dbContactArrayCfg->sqlSelectRecord($where, $result)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
				return false;
			}
			$result_array = array();
			// Ergebnisarray aufbauen
			foreach ($result as $item) {
				$result_array[$item[dbKITcontactArrayCfg::field_identifier]] = $item[dbKITcontactArrayCfg::field_value];
			}
			// Array sortieren
			asort($result_array);
			// Ergebnisarray zuordnen
			switch ($type):
			case dbKITcontactArrayCfg::type_type:
				$this->type_array = $result_array; break;
			case dbKITcontactArrayCfg::type_access:
				$this->access_array = $result_array; break;
			case dbKITcontactArrayCfg::type_company_title:
				$this->company_title_array = $result_array;	break;
			case dbKITcontactArrayCfg::type_person_title:
				$this->person_title_array = $result_array; break;
			case dbKITcontactArrayCfg::type_newsletter:
				$this->newsletter_array = $result_array; break;
			case dbKITcontactArrayCfg::type_distribution:
				$this->distribution_array = $result_array; break;
			case dbKITcontactArrayCfg::type_person_academic:
				$this->person_title_academic_array = $result_array; break;
			case dbKITcontactArrayCfg::type_category:
				$this->category_array = $result_array; break;
			case dbKITcontactArrayCfg::type_internet:
				$this->internet_array = $result_array; break;
			case dbKITcontactArrayCfg::type_phone:
				$this->phone_array = $result_array; break;
			case dbKITcontactArrayCfg::type_email:
				$this->email_array = $result_array; break;
			case dbKITcontactArrayCfg::type_protocol:
				$dbProtocol->type_array = $result_array; break;

			endswitch;
		}
		return true;
	} // initArrays()

	/**
	 * Fuegt einen Protokolleintrag fuer Systemereignisse ein
	 */
	public function addSystemNotice($kid_id, $memo) {
		global $tools;
		global $dbProtocol;
		$data = array();
		$data[dbKITprotocol::field_contact_id] = $kid_id;
		$data[dbKITprotocol::field_memo] = $memo;
		$data[dbKITprotocol::field_date] = date('Y-m-d H:i:s');
		$data[dbKITprotocol::field_type] = dbKITprotocol::type_memo;
		$data[dbKITprotocol::field_status] = dbKITprotocol::status_active;
		$data[dbKITprotocol::field_members] = '';
		$data[dbKITprotocol::field_update_by] = $tools->getDisplayName();
		$data[dbKITprotocol::field_update_when] = date('Y-m-d H:i:s');
		if (!$dbProtocol->sqlInsertRecord($data)) {
			return false;
		}
		return true;
	}

	/**
	 * Return the standard email address for the desired $id
	 *
	 * @param INT $id
	 * @return STR
	 */
	public function getStandardEMailByID($id) {
		$where = array();
		$where[self::field_id] = $id;
		$addr = array();
		if (!$this->sqlSelectRecord($where, $addr)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			return false;
		}
		if (count($addr) < 1) return false;
		$addr = $addr[0];
		(!empty($addr[dbKITcontact::field_email_standard])) ? $standard = $addr[dbKITcontact::field_email_standard] : $standard = 0;
		$email_array = explode(';', $addr[dbKITcontact::field_email]);
		if (count($email_array) < 1) return false;
		if (strpos($email_array[$standard], '|') === false) return false;
		$standard_mail = explode('|', $email_array[$standard]);
		return $standard_mail[1];
	} // getStandardEMailByID()

	public function getStandardPhoneByID($id) {
		$where = array();
		$where[self::field_id] = $id;
		$addr = array();
		if (!$this->sqlSelectRecord($where, $addr)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
		}
		if (count($addr) < 1) return false;
		$addr = $addr[0];
		(!empty($addr[dbKITcontact::field_phone_standard])) ? $standard = $addr[dbKITcontact::field_phone_standard] : $standard = 0;
		$phone_array = explode(';', $addr[dbKITcontact::field_phone]);
		if (count($phone_array) < 1) return '';
		$standard_phone = explode('|', $phone_array[$standard]);
		if (count($standard_phone) < 1) return '';
		if (isset($standard_phone[1])) {
			return $standard_phone[1];
		}
		else {
			return '';
		}
	} //getStandardPhoneByID()

	/**
	 * Return the contact by the requested ID
	 *
	 * @param INT $id
	 * @param REFERENCE ARRAY $contact
	 * @param boolean $isDeleted - Search for deleted addresses
	 * @return boolean
	 */
	public function getContactByID($id=-1, &$contact=array(), $isDeleted=false) {
		$check_deleted = $isDeleted ? '=' : '!=';
		// search for contact id
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s%s'%s'",
										$this->getTableName(),
										self::field_id,
										$id,
										self::field_status,
										$check_deleted,
										self::status_deleted);
		$contact = array();
		if (!$this->sqlExec($SQL, $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			return false;
		}
		if (count($contact) < 1) {
			$contact = $this->getFields();
			$contact[self::field_id] = -1;
			return false;
		}
		$contact = $contact[0];
		return true;
	} // getContactByID()

	/**
	 * Return the address requested by the address_ID
	 *
	 * @param integer $address_id
	 * @param reference array $address
	 * @param boolean $isDeleted
	 * @return boolean
	 */
	public function getAddressByID($address_id=-1, &$address=array(), $isDeleted=false) {
		global $dbContactAddress;
		$check_deleted = $isDeleted ? '=' : '!=';
		// search for address_id
		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s%s'%s'",
										$dbContactAddress->getTableName(),
										dbKITcontactAddress::field_id,
										$address_id,
										dbKITcontactAddress::field_status,
										$check_deleted,
										dbKITcontactAddress::status_deleted);
		$address = array();
		if (!$dbContactAddress->sqlExec($SQL, $address)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
			return false;
		}
		if (count($address) < 1) {
			// Datensatz nicht gefunden...
			$address = $dbContactAddress->getFields();
			return false;
		}
		$address = $address[0];
		return true;
	} // getAddressByID()

	/**
	 * Connect to WB users and balance Users with Contacts
	 * @return BOOL
	 */
	public function checkWBusers() {
		global $dbCfg;
		global $dbWBusers;
		global $dbRegister;
		global $dbWBgroups;
		global $tools;
		if ($dbCfg->getValue(dbKITcfg::cfgConnectWBusers)) {
			$SQL = sprintf(	"SELECT %s.* FROM %s LEFT JOIN %s ON %s.%s = %s.%s WHERE %s.%s!='1' AND %s.%s='1' AND %s.%s IS NULL",
											$dbWBusers->getTableName(),
											$dbWBusers->getTableName(),
											$dbRegister->getTableName(),
											$dbWBusers->getTableName(),
											dbWBusers::field_email,
											$dbRegister->getTableName(),
											dbKITregister::field_email,
											$dbWBusers->getTableName(),
											dbWBusers::field_group_id,
											$dbWBusers->getTableName(),
											dbWBusers::field_active,
											$dbRegister->getTableName(),
											dbKITregister::field_email);
			$users = array();
			if (!$dbRegister->sqlExec($SQL, $users)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
				return false;
			}

			foreach ($users as $user) {
				// WB User in KIT einfuegen...
				$data = array(
					dbKITcontact::field_category							=> dbKITcontact::category_wb_user,
					dbKITcontact::field_email									=> sprintf('%s|%s', dbKITcontact::email_business, strtolower($user[dbWBusers::field_email])),
					dbKITcontact::field_email_standard				=> 0,
					dbKITcontact::field_person_title					=> dbKITcontact::person_title_mister,
					dbKITcontact::field_person_title_academic	=> dbKITcontact::person_title_academic_none,
					dbKITcontact::field_company_title					=> dbKITcontact::company_title_none,
					dbKITcontact::field_contact_identifier		=> $user[dbWBusers::field_display_name],
					dbKITcontact::field_update_by							=> 'SYSTEM',
					dbKITcontact::field_update_when						=> date('Y-m-d H:i:s')
				);
				$id = -1;
				if (!$this->sqlInsertRecord($data, $id)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
				$this->addSystemNotice($id, sprintf(kit_protocol_import_wb_user, $user[dbWBusers::field_display_name]));
				// WB User in REGISTER einfuegen
				$data = array(
					dbKITregister::field_contact_id						=> $id,
					dbKITregister::field_email								=> strtolower($user[dbWBusers::field_email]),
					dbKITregister::field_username							=> $user[dbWBusers::field_username],
					dbKITregister::field_password							=> $user[dbWBusers::field_password],
					dbKITregister::field_status								=> dbKITregister::status_active,
					dbKITregister::field_register_date				=> date('Y-m-d H:i:s'),
					dbKITregister::field_register_confirmed		=> date('Y-m-d H:i:s'),
					dbKITregister::field_register_key					=> $tools->createGUID(),
					dbKITregister::field_update_by						=> 'SYSTEM',
					dbKITregister::field_update_when					=> date('Y-m-d H:i:s')
				);
				if (!$dbRegister->sqlInsertRecord($data)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
					return false;
				}
			}

			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND ((%s LIKE '%s') OR (%s LIKE '%s,%%') OR (%s LIKE '%%,%s') OR (%s LIKE '%%%s,%%'))",
											$this->getTableName(),
											dbKITcontact::field_status,
											dbKITcontact::status_active,
											dbKITcontact::field_category,
											dbKITcontact::category_wb_user,
											dbKITcontact::field_category,
											dbKITcontact::category_wb_user,
											dbKITcontact::field_category,
											dbKITcontact::category_wb_user,
											dbKITcontact::field_category,
											dbKITcontact::category_wb_user );
			$contacts = array();
			if (!$this->sqlExec($SQL, $contacts)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, ____LINE__, $this->getError()));
				return false;
			}

			// pruefen ob eine WB Gruppe fuer KIT existiert
			$where = array(dbWBgroups::field_name => dbWBgroups::kitWBgoup);
			$groups = array();
			if (!$dbWBgroups->sqlSelectRecord($where, $groups)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBgroups->getError()));
				return false;
			}
			if (count($groups) < 1) {
				// Gruppe existiert noch nicht, anlegen!
				$data = array(dbWBgroups::field_name => dbWBgroups::kitWBgoup);
				$kit_group_id = -1;
				if (!$dbWBgroups->sqlInsertRecord($data, $kit_group_id)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBgroups->getError()));
					return false;
				}
			}
			else {
				$kit_group_id = $groups[0][dbWBgroups::field_group_id];
			}

			foreach ($contacts as $contact) {
				if (!empty($contact[dbKITcontact::field_email])) {
					$emails = explode(',', $contact[dbKITcontact::field_email]);
					if (strpos($emails[0], '|') !== false) {
						list($type, $email) = explode('|', $emails[0]);
						$is_user = false;
						foreach ($users as $user) {
							if (strtolower($user[dbWBusers::field_email]) == strtolower($email)) {
								$is_user = true;
								break;
							}
						}
						if (!$is_user) {
							// vorsichtshalber pruefen ob die E-Mail Adresse einem Admin gehoert...
							$where = array(dbWBusers::field_email => $email);
							$users = array();
							if (!$dbWBusers->sqlSelectRecord($where, $users)) {
								$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
								return false;
							}
							if (count($users) == 0) {
								// Datensatz anlegen
								$register = array();
								$where = array(	dbKITregister::field_email => $email,
																dbKITregister::field_status => dbKITregister::status_active);
								if (!$dbRegister->sqlSelectRecord($where, $register)) {
									$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
									return false;
								}
								if (count($register) < 1) {
									// no entry - but dont prompt error, probably is an registration running ...
									continue;
									/*
									$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $email)));
									return false;
									*/
								}
								$data = array(
									dbWBusers::field_active 			=> 1,
									dbWBusers::field_display_name => $contact[dbKITcontact::field_contact_identifier],
									dbWBusers::field_email 				=> $email,
									dbWBusers::field_group_id 		=> $kit_group_id,
									dbWBusers::field_groups_id 		=> $kit_group_id,
									dbWBusers::field_password			=> $register[0][dbKITregister::field_password],
									dbWBusers::field_username			=> $email
								);
								if (!$dbWBusers->sqlInsertRecord($data)) {
									$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
									return false;
								}
							}
						}
					}
				}
			}
		}
		return true;
	} // checkWBusers()

	/**
	 * Get the primary E-Mail adresses of the defined admins
	 * @param REFERENCE $primary_emails
	 * @return BOOL if Admins are defined
	 */
	public function getAdmins(&$primary_emails= array()) {
		global $dbCfg;
		$primary_emails = $dbCfg->getValue(dbKITcfg::cfgKITadmins);
		return (count($primary_emails) < 1) ? false : true;
	} // getAdmins()

} // class dbKITcontact


class dbKITcontactArrayCfg extends dbConnectLE {

	const field_id							= 'array_id';
	const field_type						= 'array_type';
	const field_identifier			= 'array_identifier';
	const field_value						= 'array_value';
	const field_status					= 'array_status';
	const field_update_when			= 'array_update_when';
	const field_update_by				= 'array_update_by';

	const type_undefined				= 'typeUndefined'; //-1;
	const type_type							= 'typeType'; //1;
	const type_access						= 'typeAccess'; //2;
	const type_category					= 'typeCategory'; //3;
	const type_newsletter				= 'typeNewsletter';
	const type_internet					= 'typeInternet'; //4;
	const type_company_title		= 'typeCompanyTitle'; //5;
	const type_person_title			= 'typePersonTitle'; //6;
	const type_person_academic	= 'typePersonAcademic'; //7;
	const type_phone						= 'typePhone'; //8;
	const type_email						= 'typeEmail'; //9;
	const type_protocol					= 'typeProtocol'; //10;
	const type_distribution			= 'typeDistribution';

	public $type_array = array(
//		self::type_undefined				=> kit_contact_array_type_undefined,
		self::type_type							=> kit_contact_array_type_type,
		self::type_access						=> kit_contact_array_type_access,
		self::type_company_title		=> kit_contact_array_type_company_title,
		self::type_person_title			=> kit_contact_array_type_person_title,
		self::type_person_academic	=> kit_contact_array_type_person_academic,
		self::type_category					=> kit_contact_array_type_category,
		self::type_newsletter				=> kit_contact_array_type_newsletter,
		self::type_internet					=> kit_contact_array_type_internet,
		self::type_phone						=> kit_contact_array_type_phone,
		self::type_email						=> kit_contact_array_type_email,
		self::type_protocol					=> kit_contact_array_type_protocol,
		self::type_distribution			=> kit_contact_array_type_distribution
	);

	const status_active								= 'statusActive'; //1;
	const status_deleted							= 'statusDeleted'; //-1;

	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_deleted		=> kit_contact_status_deleted
	);

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_contact_array_cfg');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_type, "VARCHAR(30) NULL DEFAULT '".self::type_undefined."'");
		$this->addFieldDefinition(self::field_identifier, "VARCHAR(30) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_value, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()


	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

	public function checkArray($type, $identifier, $value) {
		$where = array();
		$where[self::field_type] = $type;
		$where[self::field_identifier] = $identifier;
		$result = array();
		if (!$this->sqlSelectRecord($where, $result)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			return false;
		}
		if ((sizeof($result) > 0) && ($result[0][self::field_status] == self::status_deleted)) {
			// Datensatz existiert bereits, wurde aber geloescht und muss wieder aktualisiert werden
			$data = $result[0];
			$where = array();
			$where[self::field_id] = $data[self::field_id];
			$data[self::field_status] = self::status_active;
			$data[self::field_update_by] = 'SYSTEM';
			$data[self::field_update_when] = date('Y-m-d H:i:s');
			if (!$this->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		elseif (sizeof($result) < 1) {
			// Datensatz einfuegen
			$data = array();
			$data[self::field_type] = $type;
			$data[self::field_value] = $value;
			$data[self::field_identifier] = $identifier;
			$data[self::field_status] = self::status_active;
			$data[self::field_update_by] = 'SYSTEM';
			$data[self::field_update_when] = date('Y-m-d H:i:s');
			if (!$this->sqlInsertRecord($data)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // checkArray()

} // class dbKITcontactArrayCfg


class dbKITcontactAddress extends dbConnectLE {

	const field_id					= 'address_id';
	const field_contact_id	= 'contact_id';
	const field_type				= 'address_type';
	const field_country			= 'address_country';
	const field_street			= 'address_street';
	const field_zip					= 'address_zip';
	const field_city				= 'address_city';
	const field_extra       = 'address_extra';
	const field_region      = 'address_region';
	const field_status			= 'address_status';
	const field_update_by		= 'address_update_by';
	const field_update_when	= 'address_update_when';

	const type_undefined		= 'typeUndefined'; //0;
	const type_private			= 'typePrivate'; //1;
	const type_business			= 'typeBusiness'; //2;
	const type_delivery			= 'typeDelivery';
	const type_post_office	= 'typePOB';

	public $type_array = array(
		self::type_undefined			=> kit_contact_address_type_undefined,
		self::type_private				=> kit_contact_address_type_private,
		self::type_business				=> kit_contact_address_type_business,
		self::type_delivery				=> kit_contact_address_type_delivery,
		self::type_post_office		=> kit_contact_address_type_post_office_box
	);

	const country_undefined	= '';
	const country_germany		= 'DE';
	const country_austria		= 'AU';
	const country_suisse		= 'CH';

	public $country_array = array(
		self::country_undefined		=> kit_country_undefined,
		self::country_germany			=> kit_country_germany,
		self::country_suisse			=> kit_country_suisse,
		self::country_austria			=> kit_country_austria
	);

	const status_active								= 'statusActive'; //1;
	const status_locked								= 'statusLocked'; //2;
	const status_deleted							= 'statusDeleted'; //-1;

	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
	//	self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_contact_address');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_contact_id, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_type, "VARCHAR(30) NOT NULL DEFAULT '".self::type_undefined."'");
		$this->addFieldDefinition(self::field_country, "VARCHAR(2) NOT NULL DEFAULT '".self::country_undefined."'");
		$this->addFieldDefinition(self::field_street, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_zip, "VARCHAR(20) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_city, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_extra, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_region, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->setIndexFields(array(self::field_contact_id));
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
		if (!defined('KIT_INSTALL_RUNNING')) {
			$this->initArrays();
		}
	} // __construct()

	public function initTables() {
		global $dbCountries;

		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		// init country table
		if (!is_object($dbCountries)) $dbCountries = new dbKITcountries(true);
		if (!$dbCountries->initTables()) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCountries->getError()));
			return false;
		}
		return true;
	} // initTables()

	public function initArrays() {
		$dbCountries = new dbKITcountries();
		$where = array();
		$countries = array();
		if (!$dbCountries->sqlSelectRecord($where, $countries)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCountries->getError()));
			return false;
		}
		$this->country_array = array(-1 => '');
		foreach ($countries as $country) {
		//	$this->country_array[$country[dbKITcountries::field_land_kfz]] = utf8_decode($country[dbKITcountries::field_land_name]);
			$this->country_array[$country[dbKITcountries::field_land_kfz]] = $country[dbKITcountries::field_land_name];
		}
		return true;
	} // initArrays()

} // class dbKITcontactAddress

class dbKITcountries extends dbConnectLE {

	const field_id					= 'land_id';
	const field_land_kfz		= 'land_kfz';
	const field_land_name		= 'land_name';

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_countries');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_land_kfz, "VARCHAR(2) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_land_name, "VARCHAR(50) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		$csv_file = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/csv/countries.csv';
		$csv_array = array();
		if (file_exists($csv_file)) {
			if (!$this->csvImport($csv_array, $csv_file)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

} // dbKITcountries

/**
 * ISO 639-1 language codes for KIT
 *
 * @author Ralf Hertsch
 *
 */
class dbKITlanguages extends dbConnectLE {

	const FIELD_ID = 'id';
	const FIELD_ISO = 'ISO';
	const FIELD_LOCAL = 'Local';
	const FIELD_ENGLISH = 'English';

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	/**
	 * Constructor
	 *
	 * @param boolean $create_tables - create table if not exists
	 */
	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_languages');
		$this->addFieldDefinition(self::FIELD_ID, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::FIELD_ISO, "VARCHAR(2) NOT NULL DEFAULT 'nn'");
		$this->addFieldDefinition(self::FIELD_LOCAL, "VARCHAR(64) NOT NULL DEFAULT '-undefined-'");
		$this->addFieldDefinition(self::FIELD_ENGLISH, "VARCHAR(64) NOT NULL DEFAULT '-undefined-'");
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	/**
	 * Initialize the ISO 639-1 language code table
	 *
	 * @return boolean
	 */
	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		$csv_file = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/csv/languages.csv';
		$csv_array = array();
		if (file_exists($csv_file)) {
			if (!$this->csvImport($csv_array, $csv_file, true, false, 1000, ";", "\"")) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

} // class dbKITlanguages

class dbKITmemos extends dbConnectLE {

	const field_id					= 'memo_id';
	const field_memo				= 'memo_memo';
	const field_contact_id	= 'contact_id';
	const field_status			= 'memo_status';
	const field_update_by		= 'memo_update_by';
	const field_update_when	= 'memo_update_when';

	const status_active								= 'statusActive'; //1;
	const status_locked								= 'statusLocked'; //2;
	const status_deleted							= 'statusDeleted'; //-1;

	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_contact_memos');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_memo, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_contact_id, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->setIndexFields(array(self::field_contact_id));
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

} // class dbKITmemos

class dbKITprotocol extends dbConnectLE {

	const field_id							= 'protocol_id';
	const field_contact_id			= 'contact_id';
	const field_date						= 'protocol_date';
	const field_type						= 'protocol_type';
	const field_memo						= 'protocol_memo';
	const field_members					= 'protocol_members';
	const field_status					= 'protocol_status';
	const field_update_by				= 'protocol_update_by';
	const field_update_when			= 'protocol_update_when';

	const status_active					= 'statusActive'; //1;
	const status_locked					= 'statusLocked'; //2;
	const status_deleted				= 'statusDeleted'; //-1;

	const type_undefined				= 'typeUndefined'; //-1;
	const type_memo							= 'typeMemo'; //1;
	const type_email						= 'typeEmail'; //2;
	const type_call							= 'typeCall'; //3;
	const type_newsletter				= 'typeNewsletter'; //4;
	const type_meeting					= 'typeMeeting'; //5;

	public $type_array = array(
		self::type_undefined			=> kit_contact_protocol_type_undefined,
		self::type_call						=> kit_contact_protocol_type_call,
		self::type_email					=> kit_contact_protocol_type_email,
		self::type_meeting				=> kit_contact_protocol_type_meeting,
		self::type_memo						=> kit_contact_protocol_type_memo,
		self::type_newsletter			=> kit_contact_protocol_type_newsletter
	);

	public $status_array = array(
		self::status_active			=> kit_contact_status_active,
		self::status_locked			=> kit_contact_status_locked,
		self::status_deleted		=> kit_contact_status_deleted
	);

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
    $this->setTableName('mod_kit_contact_protocol');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_contact_id, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_date, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_type, "VARCHAR(30) NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_memo, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_members, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->setIndexFields(array(self::field_contact_id));
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

	/**
	 * Fuegt einen Protokolleintrag fuer Systemereignisse ein
	 */
	public function addSystemNotice($kid_id, $memo) {
		global $tools;

		$data = array();
		$data[self::field_contact_id] = $kid_id;
		$data[self::field_memo] = $memo;
		$data[self::field_date] = date('Y-m-d H:i:s');
		$data[self::field_type] = self::type_memo;
		$data[self::field_status] = self::status_active;
		$data[self::field_members] = '';
		$data[self::field_update_by] = $tools->getDisplayName();
		$data[self::field_update_when] = date('Y-m-d H:i:s');
		if (!$this->sqlInsertRecord($data)) {
			return false;
		}
		return true;
	}

} // class dbKITprotocol


class dbKITprovider extends dbConnectLE {

    const field_id = 'provider_id';
    const field_name = 'provider_name';
    const field_email = 'provider_email';
    const field_identifier = 'provider_identifier';
    const field_remark = 'provider_remark';
    const field_smtp_auth = 'provider_smtp_auth';
    const field_smtp_host = 'provider_smtp_host';
    const field_smtp_user = 'provider_smtp_user';
    const field_smtp_pass = 'provider_smtp_pass';
    const field_relaying = 'provider_relaying';
    const field_status = 'provider_status';
    const field_update_by = 'provider_update_by';
    const field_update_when = 'provider_update_when';

    const status_active = 'statusActive';
    const status_locked = 'statusLocked';
    const status_deleted = 'statusDeleted';

    public $status_array = array(
    self::status_active => kit_contact_status_active,
    self::status_locked => kit_contact_status_locked,
    self::status_deleted => kit_contact_status_deleted);

    public $create_tables = false;

    protected static $config_file = 'config.json';
    protected static $table_prefix = TABLE_PREFIX;

    public function __construct($create_tables = false) {
      $this->create_tables = $create_tables;
  		// use another table prefix?
      if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
        $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
        if (isset($config['table_prefix']))
          self::$table_prefix = $config['table_prefix'];
      }
      parent::__construct();
      $this->setTablePrefix(self::$table_prefix);
      $this->setTableName('mod_kit_provider');
        $this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
        $this->addFieldDefinition(self::field_name, "VARCHAR(50) NOT NULL DEFAULT ''");
        $this->addFieldDefinition(self::field_identifier, "VARCHAR(50) NOT NULL DEFAULT ''");
        $this->addFieldDefinition(self::field_email, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_remark, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_smtp_auth, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_smtp_host, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_smtp_user, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_smtp_pass, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_relaying, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

} // class dbKITprovider


class dbKITregister extends dbConnectLE {

	const field_id									= 'reg_id';
	const field_email								= 'reg_email';
	const field_username						= 'reg_username';
	const field_password						= 'reg_password';
	const field_register_date				= 'reg_register_date';
	const field_register_key				= 'reg_register_key';
	const field_register_confirmed	= 'reg_register_confirmed';
	const field_status							= 'reg_status';
	const field_contact_id					= 'contact_id';
	const field_newsletter					= 'reg_newsletter';
	const field_login_failures			= 'reg_login_failures';
	const field_login_locked				= 'reg_login_locked';
	const field_update_by						= 'reg_update_by';
	const field_update_when					= 'reg_update_when';

	const status_active					= 'statusActive';
	const status_locked					= 'statusLocked';
	const status_deleted				= 'statusDeleted';
	const status_key_send				= 'statusKeySend';
	const status_key_created		= 'statusKeyCreated';

	public $status_array = array(
		self::status_key_created	=> kit_contact_status_key_created,
		self::status_key_send			=> kit_contact_status_key_send,
		self::status_active				=> kit_contact_status_active,
		self::status_locked				=> kit_contact_status_locked,
		self::status_deleted			=> kit_contact_status_deleted
	);

	public $status_dwoo_array = array(
	        array(
	                'value' => self::status_key_created,
	                'text' => kit_contact_status_key_created
	                ),
	        array(
	                'value' => self::status_key_send,
	                'text' => kit_contact_status_key_send,
	                ),
	        array(
	                'value' => self::status_active,
	                'text' => kit_contact_status_active
	                ),
	        array(
	                'value' => self::status_locked,
	                'text' => kit_contact_status_locked
	                ),
	        array(
	                'value' => self::status_deleted,
	                'text' => kit_contact_status_deleted
	                )
	        );

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables = false) {
		$this->create_tables = $create_tables;
  		// use another table prefix?
      if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
        $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
        if (isset($config['table_prefix']))
          self::$table_prefix = $config['table_prefix'];
      }
      parent::__construct();
      $this->setTablePrefix(self::$table_prefix);
      $this->setTableName('mod_kit_register');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_email, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_username, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_password, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_register_date, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_register_key, "VARCHAR(128) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_register_confirmed, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_status, "VARCHAR(30) NOT NULL DEFAULT '".self::status_locked."'");
		$this->addFieldDefinition(self::field_contact_id, "INT NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_newsletter, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_login_failures, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_login_locked, "TINYINT NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(30) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);
		// create tables
		if ($this->create_tables) {
			$this->initTables();
		}
	} // __construct()

	public function initTables() {
		if (!$this->sqlTableExists()) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
				return false;
			}
		}
		return true;
	} // initTables()

	public function getAdmins(&$primary_emails = array()) {
		global $dbContact;
		return $dbContact->getAdmins($primary_emails);
	} // getAdmins()

} // dbKITregister

class dbWBusers extends dbConnectLE {

	const field_user_id								= 'user_id';
	const field_group_id							= 'group_id';
	const field_groups_id							= 'groups_id';
	const field_active								= 'active';
	const field_username							= 'username';
	const field_password							= 'password';
	const field_remember_key					= 'remember_key';
	const field_last_reset						= 'last_reset';
	const field_display_name					= 'display_name';
	const field_email									= 'email';
	const field_timezone							= 'timezone';
	const field_date_format						= 'date_format';
	const field_time_format						= 'time_format';
	const field_language							= 'language';
	const field_home_folder						= 'home_folder';
	const field_login_when						= 'login_when';
	const field_login_ip							= 'login_ip';

	const status_active								= 1;
	const status_inactive							= 0;

	public function __construct() {
		parent::__construct();
		$this->setTableName('users');
		$this->addFieldDefinition(self::field_user_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_groups_id, "VARCHAR(255) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_active, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_username, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_password, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_remember_key, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_last_reset, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_display_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_email, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timezone, "INT(11) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_date_format, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_time_format, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_language, "VARCHAR(5) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_home_folder, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_login_when, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_login_ip, "VARCHAR(15) NOT NULL DEFAULT ''");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);

	} // __construct()

	public function sqlCreateTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}

	public function sqlDeleteTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}

} // class dbWBusers


class dbWBgroups extends dbConnectLE {

	const field_group_id							= 'group_id';
	const field_name									= 'name';
	const field_system_permissions		= 'system_permissions';
	const field_module_permissions		= 'module_permissions';
	const field_template_permissions	= 'template_permissions';

	const kitWBgoup										= 'kitContact';

	public function __construct() {
		parent::__construct();
		$this->setTableName('groups');
		$this->addFieldDefinition(self::field_group_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_system_permissions, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_module_permissions, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_template_permissions, "TEXT NOT NULL DEFAULT ''");
		// check field definitions
		$this->checkFieldDefinitions();
		// set default timezone
		date_default_timezone_set(CFG_TIME_ZONE);

	} // __construct()

	public function sqlCreateTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}

	public function sqlDeleteTable() {
		$this->setError('Function not implemented for this table!');
		return false;
	}

} // class dbWBgroups

if (!defined('KIT_INSTALL_RUNNING')) {
  global $tools;
  global $dbRegister;
  global $dbProvider;
  global $dbProtocol;
  global $dbMemos;
  global $dbCountries;
  global $dbContactArrayCfg;
  global $dbContactAddress;
  global $dbContact;
  global $dbWBusers;
  global $dbWBgroups;
  global $dbCfg;
  global $dbLanguages;

  if (!is_object($tools)) $tools = new kitTools();
  if (!is_object($dbCfg)) $dbCfg = new dbKITcfg();
  if (!is_object($dbRegister)) $dbRegister = new dbKITregister();
  if (!is_object($dbProvider)) $dbProvider = new dbKITprovider();
  if (!is_object($dbProtocol)) $dbProtocol = new dbKITprotocol();
  if (!is_object($dbMemos)) $dbMemos = new dbKITmemos();
  if (!is_object($dbCountries)) $dbCountries = new dbKITcountries();
  if (!is_object($dbContactArrayCfg)) $dbContactArrayCfg = new dbKITcontactArrayCfg();
  if (!is_object($dbContactAddress)) $dbContactAddress = new dbKITcontactAddress();
  if (!is_object($dbWBusers)) $dbWBusers = new dbWBusers();
  if (!is_object($dbWBgroups)) $dbWBgroups = new dbWBgroups();
  if (!is_object($dbContact)) $dbContact = new dbKITcontact();
  if (!is_object($dbLanguages)) $dbLanguages = new dbKITlanguages();
}

?>