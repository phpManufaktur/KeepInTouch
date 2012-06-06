<?php

/**
 * kitDirList
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// Include config file
$config_path = '../../config.php';
if (!file_exists($config_path)) {
	die('Missing Configuration File...'); 
}
require_once($config_path);

// include dbConnect
if (!class_exists('dbConnectLE')) require_once(WB_PATH.'/modules/dbconnect_le/include.php');

if (file_exists(WB_PATH.'/modules/kit_dirlist/class.link.php')) {
    // try to load from kit_dirlist
    require_once(WB_PATH.'/modules/kit_dirlist/class.link.php');
}
else {
    // use KIT framework instead ...
    require_once WB_PATH.'/modules/kit/framework/KIT/kit_dirlist/class.link.php';
}

// first check if user is authenticated...
if ((isset($_SESSION['kdl_pct']) && isset($_SESSION['kdl_aut']) && isset($_SESSION['kdl_usr']) && isset($_GET['id'])) ||
        (isset($_SESSION['USER_ID']) && (in_array('admintools', $_SESSION['SYSTEM_PERMISSIONS'])) && isset($_GET['id']))) {
    $id = $_GET['id'];
    if (isset($_SESSION['kdl_usr'])) {
        $where = array(
        	dbKITdirList::field_id		=> $id,
        	dbKITdirList::field_user	=> $_SESSION['kdl_usr']
        );
    }
    else {
        $where = array(
                dbKITdirList::field_id => $id
                );
    }
    $dirlist = array();
    $dbDirList = new dbKITdirList();
    if (!$dbDirList->sqlSelectRecord($where, $dirlist)) {
    	echo sprintf('[%s] %s', __LINE__, $dbDirList->getError());
    	exit();
    }
    if (count($dirlist) < 1) {
    	echo sprintf("The file with the <b>%05d ID</b> is not available!", $id);
    	exit();
    }
    
    $dirlist = $dirlist[0];
    if (!file_exists($dirlist[dbKITdirList::field_path])) {
    	echo sprintf("File <b>%s</b> not found!", $dirlist[dbKITdirList::field_file]);
    	exit();
    }
    
    // Datensatz aktualisieren
    $data = array(
    	dbKITdirList::field_count => $dirlist[dbKITdirList::field_count]+1,
    	dbKITdirList::field_date => date('Y-m-d H:i:s')
    );
    $where = array(
    	dbKITdirList::field_id => $dirlist[dbKITdirlist::field_id]
    );
    if (!$dbDirList->sqlUpdateRecord($data, $where)) {
    	echo sprintf('[%s] %s', __LINE__, $dbDirList->getError());
    	exit();
    }
    
    // start download
    header('Content-type: application/force-download');
    header('Content-Transfer-Encoding: Binary');
    header('Content-length: '.filesize($dirlist[dbKITdirList::field_path]));
    header('Content-disposition: attachment;filename="'.$dirlist[dbKITdirList::field_file].'"');
    readfile($dirlist[dbKITdirList::field_path]);
    exit();				
}
else {
    // no permission
    header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
    exit('<p><i>kitDirList:</i> <b>ACCESS DENIED!</b></p>');
}
?>