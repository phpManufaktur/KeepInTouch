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

// Checking Requirements

$PRECHECK['WB_VERSION'] = array('VERSION' => '2.8', 'OPERATOR' => '>=');
$PRECHECK['PHP_VERSION'] = array('VERSION' => '5.2.0', 'OPERATOR' => '>=');
$PRECHECK['WB_ADDONS'] = array(
	'dbconnect_le'	=> array('VERSION' => '0.60', 'OPERATOR' => '>='),
	'dwoo' => array('VERSION' => '0.10', 'OPERATOR' => '>=')
);

global $database;
$sql = "SELECT * FROM ".TABLE_PREFIX."settings WHERE name='default_charset'";
$result = $database->query($sql);
if ($result) {
	$data = $result->fetchRow();
	($data['value'] == 'utf-8') ? $status = true : $status = false;
	$PRECHECK['CUSTOM_CHECKS'] = array(
    'Default Charset' => array('REQUIRED' => 'utf-8', 'ACTUAL' => $data['value'], 'STATUS' => $status));
}

?>