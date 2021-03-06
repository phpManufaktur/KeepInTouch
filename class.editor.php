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