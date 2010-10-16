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

if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// include language file
if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/EN.php'); 
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); 
}

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');
if (!class_exists('Dwoo')) require_once(WB_PATH.'/modules/dwoo/include.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.config.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.tools.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.contact.php');
require_once(WB_PATH.'/framework/class.wb.php');
require_once(WB_PATH.'/framework/class.admin.php');

global $parser;

if (!is_object($parser)) $parser = new Dwoo();

?>