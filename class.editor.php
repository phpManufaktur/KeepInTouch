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
  
  $Id: class.editor.php 32 2010-05-24 17:16:52Z ralf $
  
**/

if (!defined('WYSIWYG_EDITOR') || 
		WYSIWYG_EDITOR=="none" || 
		!file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	// no WYSIWYG editor present...
	function show_wysiwyg_editor($name, $id, $content, $width, $height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} 
else {
	// include default WYSIWYG editor
	require_once(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}


?>