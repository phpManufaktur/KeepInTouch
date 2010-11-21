<?php

/**
 * @author       Ralf Hertsch
 * @copyright    2010-today by phpManufaktur   
 * @link         http://phpManufaktur.de
 * @license      http://www.gnu.org/licenses/gpl.html
 * @version      $Id$
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

class dbCronjobData extends dbConnectLE {
	
	const field_id							= 'cj_id';
	const field_item						= 'cj_item';
	const field_value						= 'cj_value';
	const field_timestamp				= 'cj_timestamp';

	const item_last_call				= 'last_call';
	const item_last_job					= 'last_job';
	const item_last_nl_id				= 'last_nl_id';
	
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_cronjob_data');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_item, "VARCHAR(30) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_value, "VARCHAR(80) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timestamp, "TIMESTAMP");
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
	
} // class dbCronjobData

class dbCronjobErrorLog extends dbConnectLE {
	
	const field_id						= 'cj_error_id';
	const field_error					= 'cj_error_str';
	const field_timestamp			= 'cj_error_stamp';
	
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_cronjob_error');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_error, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timestamp, "TIMESTAMP");
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
	
} // class dbCronjobErrorLog

class dbCronjobNewsletterLog extends dbConnectLE {
	
	const field_id							= 'cj_log_id';
	const field_nl_process_id		= 'nl_pro_id';
	const field_email						= 'cj_log_email';
	const field_kit_id					= 'kit_id';
	const field_status					= 'cj_log_status';
	const field_error						= 'cj_log_error';
	const field_timestamp				= 'cj_log_timestamp';

	const status_ok							= 1;
	const status_error					= -1;
	const status_simulation			= 2;
	
	public $status_array = array(
		self::status_ok						=> kit_status_ok,
		self::status_error				=> kit_status_error,
		self::status_simulation		=> kit_status_simulation
	);
	
	public $create_tables = false;
	
	function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_cronjob_nl_log');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_nl_process_id, "INT(11) NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_email, "VARCHAR(80) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_kit_id, "INT(11) NOT NULL DEFAULT '-1'");
		$this->addFieldDefinition(self::field_status, "TINYINT NOT NULL DEFAULT '".self::status_ok."'");
		$this->addFieldDefinition(self::field_error, "VARCHAR(255) NOT NULL DEFAULT ''");  
		$this->addFieldDefinition(self::field_timestamp, "TIMESTAMP");
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
	
} // class dbCronjopNewsletterLog

?>