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

/**
 * Definition fuer MASSMAIL
 * Wird fuer den Import von MASSMAIL Adressen benoetigt
 *
 */
class dbMassMailAddresses extends dbConnectLE {
	
	const field_group_id				= 'group_id';
	const field_mail_to					= 'mail_to';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_massmail_addresses');
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL DEFAULT '0'", true);
		$this->addFieldDefinition(self::field_mail_to, "TEXT NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbMassmailAdresses

/**
 * Definition fuer MASSMAIL
 * Wird fuer den Import von MASSMAIL Adressen benoetigt
 *
 */
class dbMassMailGroups extends dbConnectLE {
	
	const field_group_id				= 'group_id';
	const field_group_name			= 'group_name';
	const field_mail_to					= 'mail_to';
	const field_wb_group				= 'wb_group';
	const field_wb_group_id			= 'wb_group_id';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_massmail_groups');
		$this->addFieldDefinition(self::field_group_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_group_name, "TINYTEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_mail_to, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_wb_group, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_wb_group_id, "INT(11) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbMassmailGroups

/**
 * Definition fuer NEWSLETTERSNIPPET
 * Wird fuer den Import von NEWSLETTERSNIPPET Adressen benoetigt
 *
 */
class dbNewsletterSnippet extends dbConnectLE {
	
	const field_id							= 'id';
	const field_email						= 'email';
	const field_checksum				= 'checkSum';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_newsletter');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_email, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_checksum, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	}
} // class dbNewsletterSnippet


?>