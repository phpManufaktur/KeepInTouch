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

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	public function __construct($create_tables=false) {
		$this->create_tables = $create_tables;
  		// use another table prefix?
      if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
        $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
        if (isset($config['table_prefix']))
          self::$table_prefix = $config['table_prefix'];
      }
      parent::__construct();
      $this->setTablePrefix(self::$table_prefix);
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

	/**
	 * Return the last Call of cronjob.php as UNIX timestamp or FALSE on error
	 * @return INT timestamp
	 */
	public function getLastCronjobCall() {
		$where = array(self::field_item => self::item_last_call);
		$result = array();
		if (!$this->sqlSelectRecord($where, $result)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			return false;
		}
		if (count($result) > 0) {
			return strtotime($result[0][self::field_value]);
		}
		return false;
	} // getLastCronjobCall()

} // class dbCronjobData

class dbCronjobErrorLog extends dbConnectLE {

	const field_id						= 'cj_error_id';
	const field_error					= 'cj_error_str';
	const field_timestamp			= 'cj_error_stamp';

	public $create_tables = false;

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	function __construct($create_tables=false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
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

	protected static $config_file = 'config.json';
	protected static $table_prefix = TABLE_PREFIX;

	function __construct($create_tables=false) {
		$this->create_tables = $create_tables;
		// use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    parent::__construct();
    $this->setTablePrefix(self::$table_prefix);
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