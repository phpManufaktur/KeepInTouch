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

$dbNewsletterCfg = new dbKITnewsletterCfg();

class dbKITnewsletterCfg extends dbConnectLE {
	
	const field_id						= 'nl_cfg_id';
	const field_name					= 'nl_cfg_name';
	const field_type					= 'nl_cfg_type';
	const field_value					= 'nl_cfg_value';
	const field_label					= 'nl_cfg_label';
	const field_description		= 'nl_cfg_desc';
	const field_status				= 'nl_cfg_status';
	const field_update_by			= 'nl_cfg_update_by';
	const field_update_when		= 'nl_cfg_update_when';
	
	const status_active				= 1;
	const status_deleted			= 0;
	
	const type_undefined			= 0;
	const type_array					= 7;
  const type_boolean				= 1;
  const type_email					= 2;
  const type_float					= 3;
  const type_integer				= 4;
  const type_path						= 5;
  const type_string					= 6;
  const type_url						= 8;
  
  public $type_array = array(
  	self::type_undefined		=> '-UNDEFINED-',
  	self::type_array				=> 'ARRAY',
  	self::type_boolean			=> 'BOOLEAN',
  	self::type_email				=> 'E-MAIL',
  	self::type_float				=> 'FLOAT',
  	self::type_integer			=> 'INTEGER',
  	self::type_path					=> 'PATH',
  	self::type_string				=> 'STRING',
  	self::type_url					=> 'URL'
  );
  
  private $createTables 		= false;
  private $message					= '';
    
  //const cfgDeveloperMode		= 'cfgDeveloperMode';
  const cfgSalutation_01		= 'cfgSalutation_01';
  const cfgSalutation_02		= 'cfgSalutation_02';
  const cfgSalutation_03		= 'cfgSalutation_03';
  const cfgSalutation_04		= 'cfgSalutation_04';
  const cfgSalutation_05		= 'cfgSalutation_05';
  const cfgSalutation_06		= 'cfgSalutation_06';
  const cfgSalutation_07		= 'cfgSalutation_07';
  const cfgSalutation_08		= 'cfgSalutation_08';
  const cfgSalutation_09		= 'cfgSalutation_09';
  const cfgSalutation_10		= 'cfgSalutation_10';
  const cfgSimulateMailing	= 'cfgSimulateMailing';
  const cfgAdjustRegister   = 'cfgAdjustRegister';
  
  public $config_array = array(
  	//array('kit_label_cfg_developer_mode', self::cfgDeveloperMode, self::type_boolean, 0, 'kit_desc_cfg_developer_mode'),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_01, self::type_string, 'Sehr geehrter Herr {$account_first_name} {$account_last_name},|Sehr geehrte Frau {$account_first_name} {$account_last_name},|Sehr geehrter Kunde,', 'kit_desc_cfg_nl_salutation'),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_02, self::type_string, 'Liebes Vereinsmitglied {$account_first_name},|Liebes Vereinsmitglied {$account_first_name},|Liebes Vereinsmitglied,', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_03, self::type_string, 'Guten Tag, Herr {$account_last_name}!|Guten Tag, {$account_last_name}!|Guten Tag!', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_04, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_05, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_06, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_07, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_08, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_09, self::type_string, '', ''),
  	array('kit_label_cfg_nl_salutation', self::cfgSalutation_10, self::type_string, '', ''),
  	array('kit_label_cfg_nl_simulate', self::cfgSimulateMailing, self::type_boolean, '0', 'kit_desc_cfg_nl_simulate'),
    array('kit_label_cfg_nl_adjust_register', self::cfgAdjustRegister, self::type_boolean, '0', 'kit_desc_cfg_nl_adjust_register')
  );  
  
  public function __construct($createTables = false) {
  	$this->createTables = $createTables;
  	parent::__construct();
  	$this->setTableName('mod_kit_newsletter_cfg');
  	$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
  	$this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::type_undefined."'");
  	$this->addFieldDefinition(self::field_value, "VARCHAR(255) NOT NULL DEFAULT ''", false, false, true);
  	$this->addFieldDefinition(self::field_label, "VARCHAR(64) NOT NULL DEFAULT 'ed_str_undefined'");
  	$this->addFieldDefinition(self::field_description, "VARCHAR(255) NOT NULL DEFAULT 'ed_str_undefined'");
  	$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
  	$this->addFieldDefinition(self::field_update_by, "VARCHAR(32) NOT NULL DEFAULT 'SYSTEM'");
  	$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
  	$this->setIndexFields(array(self::field_name));
  	$this->setAllowedHTMLtags('');
  	$this->checkFieldDefinitions();
  	// Tabelle erstellen
  	if ($this->createTables) {
  		if (!$this->sqlTableExists()) {
  			if (!$this->sqlCreateTable()) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			}
  		}
  	}
  	// Default Werte garantieren
  	if ($this->sqlTableExists()) {
  		$this->checkConfig();
  	}
  } // __construct()
  
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
   * Aktualisiert den Wert $new_value des Datensatz $name
   * 
   * @param $new_value STR - Wert, der uebernommen werden soll
   * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
   * 
   * @return BOOL Ergebnis
   * 
   */
  public function setValueByName($new_value, $name) {
  	$where = array();
  	$where[self::field_name] = $name;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_name, $name)));
  		return false;
  	}
  	return $this->setValue($new_value, $config[0][self::field_id]);
  } // setValueByName()
  
  /**
   * Aktualisiert den Wert $new_value des Datensatz $id
   * 
   * @param $new_value STR - Wert, der uebernommen werden soll
   * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
   * 
   * @return BOOL Ergebnis
   */
  public function setValue($new_value, $id) {
  	global $tools;
  	
  	$value = '';
  	$where = array();
  	$where[self::field_id] = $id;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_id, $id)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		// Funktion geht davon aus, dass $value als STR uebergeben wird!!!
  		$worker = explode(",", $new_value);
  		$data = array();
  		foreach ($worker as $item) {
  			$data[] = trim($item);
  		};
  		$value = implode(",", $data);  			
  		break;
  	case self::type_boolean:
  		$value = (bool) $new_value;
  		$value = (int) $value;
  		break;
  	case self::type_email:
  		if ($tools->validateEMail($new_value)) {
  			$value = trim($new_value);
  		}
  		else {
  			$this->setMessage(sprintf(kit_msg_invalid_email, $new_value));
  			return false;			
  		}
  		break;
  	case self::type_float:
  		$value = $tools->str2float($new_value);
  		break;
  	case self::type_integer:
  		$value = $tools->str2int($new_value);
  		break;
  	case self::type_url:
  	case self::type_path:
  		$value = $tools->addSlash(trim($new_value));
  		break;
  	case self::type_string:
  		$value = (string) trim($new_value);
  		// Hochkommas demaskieren
  		$value = str_replace('&quot;', '"', $value);
  		break;
  	endswitch;
  	unset($config[self::field_id]);
  	$config[self::field_value] = (string) $value;
  	$config[self::field_update_by] = 'SYSTEM'; //$tools->getDisplayName();
  	$config[self::field_update_when] = date('Y-m-d H:i:s');
  	if (!$this->sqlUpdateRecord($config, $where)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	return true;
  } // setValue()
  
  /**
   * Gibt den angeforderten Wert zurueck
   * 
   * @param $name - Bezeichner 
   * 
   * @return WERT entsprechend des TYP
   */
  public function getValue($name) {
  	$result = '';
  	$where = array();
  	$where[self::field_name] = $name;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_name, $name)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		$result = explode(",", $config[self::field_value]);
  		break;
  	case self::type_boolean:
  		$result = (bool) $config[self::field_value];
  		break;
  	case self::type_email:
  	case self::type_path:
  	case self::type_string:
  	case self::type_url:
  		$result = (string) utf8_decode($config[self::field_value]);
  		break;
  	case self::type_float:
  		$result = (float) $config[self::field_value];
  		break;
  	case self::type_integer:
  		$result = (integer) $config[self::field_value];
  		break;
  	default:
  		$result = utf8_decode($config[self::field_value]);
  		break;
  	endswitch;
  	return $result;
  } // getValue()
  
  public function checkConfig() {
  	foreach ($this->config_array as $item) {
  		$where = array();
  		$where[self::field_name] = $item[1];
  		$check = array();
  		if (!$this->sqlSelectRecord($where, $check)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			return false;
  		}
  		if (sizeof($check) < 1) {
  			// Eintrag existiert nicht
  			$data = array();
  			$data[self::field_label] = $item[0];
  			$data[self::field_name] = $item[1];
  			$data[self::field_type] = $item[2];
  			$data[self::field_value] = $item[3];
  			$data[self::field_description] = $item[4];
  			$data[self::field_update_when] = date('Y-m-d H:i:s');
  			$data[self::field_update_by] = 'SYSTEM';
  			if (!$this->sqlInsertRecord($data)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  				return false;
  			}
  		}
  	}
  	return true;
  }
	  
} // class dbKITnewsletterCfg


?>