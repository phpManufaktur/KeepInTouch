<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2009, Ralf Hertsch
  Contact me: hertsch(at)berlin.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id: class.droplets.php 45 2010-06-30 00:54:44Z ralf $

	THIS CLASS REALIZE AN INTERFACE TO THE DROPLETS MODULE OF RUUD EISINGA (RUUD)
  
**/

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

class dbDroplets extends dbConnect {

	const field_id							= 'id';
	const field_name						= 'name';
	const field_code						= 'code';
	const field_description			= 'description';
	const field_modified_when		= 'modified_when';
	const field_modified_by			= 'modified_by';
	const field_active					= 'active';
	const field_comments				= 'comments';
	
	public function __construct() {
		parent::__construct();
		$this->setTableName('mod_droplets');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_code, "TEXT NOT NULL DEFAULT ''", false, false, true);
		$this->addFieldDefinition(self::field_description, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_modified_when, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_modified_by, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_active, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_comments, "TEXT NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();	
	} // __construct()
	
} // class dbDroplets


class checkDroplets {
	
	var $droplet_path	= '';
	var $error = '';
	
	public function __construct() {
		$this->droplet_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/droplets/' ;
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
	
	public function insertDropletsIntoTable() {
		global $admin;
		// Read droplets from directory
		$folder = opendir($this->droplet_path.'.'); 
		$names = array();
		while (false !== ($file = readdir($folder))) {
			if (basename(strtolower($file)) != 'index.php') {
				$ext = strtolower(substr($file,-4));
				if ($ext	==	".php") {
					$names[count($names)] = $file; 
				}
			}
		}
		closedir($folder);
		// init droplets
		$dbDroplets = new dbDroplets();
		if (!$dbDroplets->sqlTableExists()) {
			// Droplets not installed!
			return false;
		}
		// walk through array
		foreach ($names as $dropfile) {
			//$droplet = addslashes($this->getDropletCodeFromFile($dropfile));
			$droplet = $this->getDropletCodeFromFile($dropfile);
			if ($droplet != "") {
				// get droplet name
				$name = substr($dropfile,0,-4);
				$where = array();
				$where[dbDroplets::field_name] = $name;
				$result = array();
				if (!$dbDroplets->sqlSelectRecord($where, $result)) {
					// error exec query
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDroplets->getError()));
					return false;
				}
				if (sizeof($result) < 1) {
					// insert this droplet into table
					$description = "Example Droplet";
					$comments = "Example Droplet";
					$cArray = explode("\n",$droplet);
					if (substr($cArray[0],0,3) == "//:") {
						// extract description
						$description = trim(substr($cArray[0],3));
						array_shift($cArray);
					}
					if (substr($cArray[0],0,3) == "//:") {
						// extract comment
						$comments = trim(substr($cArray[0],3));
						array_shift($cArray);
					}
					$data = array();
					$data[dbDroplets::field_name] = $name;
					$code = implode("\r\n", $cArray);
					$data[dbDroplets::field_code] = $code;
					$data[dbDroplets::field_description] = $description;
					$data[dbDroplets::field_comments] = $comments;
					$data[dbDroplets::field_active] = 1;
					$data[dbDroplets::field_modified_by] = $admin->get_user_id();
					$data[dbDroplets::field_modified_when] = time();
					if (!$dbDroplets->sqlInsertRecord($data)) {
						// error exec query
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbDroplets->getError()));
						return false;
					}
				}				
			}  
		}
		return true;
	} // insertDropletsIntoTable()
	
	public function getDropletCodeFromFile($dropletfile) {
		$data = "";
		$filename = $this->droplet_path.$dropletfile;
		if (file_exists($filename)) {
			$filehandle = fopen ($filename, "r");
			$data = fread ($filehandle, filesize ($filename));
			fclose($filehandle);
		}	
		return $data;
	} // getDropletCodeFromFile()
	
} // checkDroplets


?>