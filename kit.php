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
  
  $Id: kit.php 29 2010-05-23 07:53:55Z ralf $
  
**/

/*
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
*/

// Include config file
$config_path = dirname(__FILE__).'/config.php';
if (!file_exists($config_path)) {
	// Vermutung: kit.php befindet sich im /modules/kit Verzeichnis
	$config_path = '../../config.php';
	if (!file_exists($config_path)) {
		die("<b>".$_SERVER['SCRIPT_NAME']."</b> was not able to access the WebsiteBaker Configuration file."); 
	}
}
require_once($config_path);
if (file_exists(WB_PATH.'/modules/kit/class.request.php')) {
	// call KIT request handler...
	require_once(WB_PATH.'/modules/kit/class.request.php');
	$request = new kitRequest();
	$request->action();
}
else {
	die("Invalid call of <b>".$_SERVER['SCRIPT_NAME']."</b>!");
}
?>