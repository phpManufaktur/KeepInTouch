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

class dbKITnewsletterLinks extends dbConnectLE {
	
	const field_id							= 'nl_link_id';
	const field_archive_id			= 'nl_arc_id';
	const field_newsletter_grps	= 'nl_arc_grps';
	const field_kit_id					= 'kit_id';
	const field_type						= 'nl_link_type';
	const field_option					= 'nl_link_option';
	const field_origin					= 'nl_link_origin';
	const field_link_value			= 'nl_link_value';
	const field_count						= 'nl_link_count';
	const field_last_update			= 'nl_link_update';
	
	const type_undefined				= 0;
	const type_link							= 1;
	const type_link_unsubscribe	= 2;
	const type_count						= 3;
	
	public $type_array = array(
		self::type_undefined				=> 'UNDEFINED',
		self::type_link							=> 'LINK',
		self::type_link_unsubscribe	=> 'UNSUBSCRIBE',
		self::type_count						=> 'COUNT'
	);
	
	private $createTables 	= false;
	
	public function __construct($createTables = false) {
  	$this->createTables = $createTables;
  	parent::__construct();
  	$this->setTableName('mod_kit_newsletter_links');
  	$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
  	$this->addFieldDefinition(self::field_archive_id, "INT(11) NOT NULL DEFAULT '-1'");
  	$this->addFieldDefinition(self::field_newsletter_grps, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_kit_id, "INT(11) NOT NULL DEFAULT '-1'");
  	$this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::type_undefined."'");
  	$this->addFieldDefinition(self::field_option, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_origin, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_link_value, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_count, "INT(11) NOT NULL DEFAULT '0'");
  	$this->addFieldDefinition(self::field_last_update, "TIMESTAMP");
  	$this->setIndexFields(array(self::field_link_value, self::field_archive_id, self::field_kit_id));
  	$this->checkFieldDefinitions();
  	// Tabelle erstellen
  	if ($this->createTables) {
  		if (!$this->sqlTableExists()) {
  			if (!$this->sqlCreateTable()) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			}
  		}
  	}
  } // __construct()
	
} // class dbKITnewsletterLinks

?>
