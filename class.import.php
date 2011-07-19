<?php

/**
 * KeepInTouch (KIT)
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');


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

class dbKITimport extends dbConnectLE {
	
	const field_id							= 'imp_id';
	const field_date						= 'imp_date';
	const field_separator				= 'imp_sep';
	const field_charset					= 'imp_car';
	const field_file_original		= 'imp_file_org';
	const field_file_renamed		= 'imp_file_ren';
	const field_cols_original		= 'imp_cols_org';
	const field_cols_dedicated	= 'imp_cols_ded';
	const field_duplicates			= 'imp_dup';
	const field_status					= 'imp_status';
	const field_error						= 'imp_error';
	const field_timestamp				= 'imp_timestamp';
	
	const separator_comma				= 'sepc';
	const separator_semicolon		= 'seps';
	const separator_tab					= 'sept';
	const separator_pipe				= 'sepp';
	const separator_colon				= 'seco';
	
	public $separator_array = array(
		self::separator_comma			=> kit_text_comma_separated,
		self::separator_semicolon	=> kit_text_semicolon_separated,
		self::separator_tab				=> kit_text_tabulator_separated,
		self::separator_pipe			=> kit_text_pipe_separated,
		self::separator_colon			=> kit_text_colon_separated
	);
	
	public $separator_value = array(
		self::separator_comma			=> ',',
		self::separator_semicolon	=> ';',
		self::separator_tab				=> "\t",
		self::separator_pipe			=> '|',
		self::separator_colon			=> ':'
	);
	
	const charset_ansi					= 'ansi'; // definition missing in dbConnectLE
	
	public $charset_array = array(
		self::charset_ansi	=> 'ANSI',
		self::charset_utf8	=> 'UTF-8' // defined in dbConnectLE
	);
	
	const status_error					= 0;
	const status_start					= 1;
	const status_step_1					= 3;
	const status_step_2					= 4;
	const status_step_3					= 5;
	const status_step_4					= 6;
	const status_step_5					= 7;
	const status_success				= 2;
	
	public $status_array = array(
		self::status_error				=> kit_status_error,
		self::status_start				=> kit_status_start,
		self::status_step_1				=> kit_status_step_1,
		self::status_step_2				=> kit_status_step_2,
		self::status_step_3				=> kit_status_step_3,
		self::status_step_4				=> kit_status_step_4,
		self::status_step_5				=> kit_status_step_5,
		self::status_success			=> kit_status_success
	);
	
	const import_no_selection				= -1;
	
	const import_addr_pers_street		= 'imp_addr_pers_street';
	const import_addr_pers_zip			= 'imp_addr_pers_zip';
	const import_addr_pers_city			= 'imp_addr_pers_city';
	const import_addr_pers_country	= 'imp_addr_pers_country';
	const import_con_pers_email_1		= 'imp_con_pers_email_1';		
	const import_con_pers_email_2		= 'imp_con_pers_email_2';		
	const import_con_pers_phone			= 'imp_con_pers_phone';
	const import_con_pers_handy			= 'imp_con_pers_handy';
	const import_con_pers_fax				= 'imp_con_pers_fax';
	
	const import_addr_comp_street		= 'imp_addr_comp_street';
	const import_addr_comp_zip			= 'imp_addr_comp_zip';
	const import_addr_comp_city			= 'imp_addr_comp_city';
	const import_addr_comp_country	= 'imp_addr_comp_country';
	const import_con_comp_email_1		= 'imp_con_comp_email_1';		
	const import_con_comp_email_2		= 'imp_con_comp_email_2';		
	const import_con_comp_phone			= 'imp_con_comp_phone';
	const import_con_comp_handy			= 'imp_con_comp_handy';
	const import_con_comp_fax				= 'imp_con_comp_fax';
	
	const import_con_www						= 'imp_con_www';
	
	public $import_fields = array(
		self::import_no_selection											=> kit_imp_no_selection,														
		dbKITcontact::field_person_title							=> kit_imp_con_pers_title,
		dbKITcontact::field_person_title_academic			=> kit_imp_con_pers_title_academic,
		dbKITcontact::field_person_first_name					=> kit_imp_con_pers_first_name,
		dbKITcontact::field_person_last_name					=> kit_imp_con_pers_last_name,
		dbKITcontact::field_person_function						=> kit_imp_con_pers_function,
		self::import_addr_pers_street									=> kit_imp_con_pers_addr_street,
		self::import_addr_pers_zip										=> kit_imp_con_pers_addr_zip,
		self::import_addr_pers_city										=> kit_imp_con_pers_addr_city,
		self::import_addr_pers_country								=> kit_imp_con_pers_addr_country,
		self::import_con_pers_email_1									=> kit_imp_con_pers_email_1,
		self::import_con_pers_email_2									=> kit_imp_con_pers_email_2,
		self::import_con_pers_phone										=> kit_imp_con_pers_phone,
		self::import_con_pers_handy										=> kit_imp_con_pers_handy,
		self::import_con_pers_fax											=> kit_imp_con_pers_fax,
		
		dbKITcontact::field_company_name							=> kit_imp_con_comp_name,
		dbKITcontact::field_company_department				=> kit_imp_con_comp_department,
		dbKITcontact::field_company_additional				=> kit_imp_con_comp_additional,
		self::import_addr_comp_street									=> kit_imp_con_comp_addr_street,
		self::import_addr_comp_zip										=> kit_imp_con_comp_addr_zip,
		self::import_addr_comp_city										=> kit_imp_con_comp_addr_city,
		self::import_addr_comp_country								=> kit_imp_con_comp_addr_country,
		self::import_con_comp_email_1									=> kit_imp_con_comp_email_1,
		self::import_con_comp_email_2									=> kit_imp_con_comp_email_2,
		self::import_con_comp_phone										=> kit_imp_con_comp_phone,
		self::import_con_comp_handy										=> kit_imp_con_comp_handy,
		self::import_con_comp_fax											=> kit_imp_con_comp_fax,
		
		self::import_con_www													=> kit_imp_con_www
	);
	
	
	private $create_tables = false;
	
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_kit_import');
		$this->addFieldDefinition(self::field_id, "INT NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_date, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_separator, "VARCHAR(5) NOT NULL DEFAULT '".self::separator_comma."'");
		$this->addFieldDefinition(self::field_charset, "VARCHAR(10) NOT NULL DEFAULT '".self::charset_utf8."'");
		$this->addFieldDefinition(self::field_file_original, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_file_renamed, "VARCHAR(255) NOT NULL DEFAULT ''"); 
		$this->addFieldDefinition(self::field_cols_original, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_cols_dedicated, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_duplicates, "TINYINT NOT NULL DEFAULT '0'"); 
		$this->addFieldDefinition(self::field_status, "TINYINT NOT NULL DEFAULT '".self::status_start."'");
		$this->addFieldDefinition(self::field_error, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timestamp, "TIMESTAMP");
		// check field definitions
		$this->checkFieldDefinitions();
		// create tables
		if ($this->create_tables) {
			if (!$this->sqlTableExists()) {
				if (!$this->sqlCreateTable()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
					return false;
				}
			}
		}	
	} // __construct()
	
} // class dbKITimport

class kitImportDialog {
	
	const request_action				= 'impact';
	const request_command				= 'impcmd';
	const request_separator			= 'sep';
	const request_charset				= 'char';
	const request_enclosure			= 'enc';
	const request_csv_file			= 'csvf';
	const request_field					= 'field';
	const request_count					= 'count';
	
	const action_default				= 'def';
	const action_step_1					= 'step1';
	const action_check_step_1		= 'chk1';					
	const action_check_step_2		= 'chk2';
	
	
	private $page_link 							= '';
	private $img_url								= '';
	private $template_path					= '';
	private $help_path							= '';
	private $error									= '';
	private $message								= '';
	private $import_directory				= '';
	
	private $swNavHide							= array();
	private $overwriteNavigation		= '';
	
	public function __construct() {
		$this->page_link = sprintf(	'%s/admintools/tool.php?tool=kit&%s=%s&%s=%s',
																ADMIN_URL,
																kitBackend::request_action,
																kitBackend::action_cfg,
																kitBackend::request_cfg_tab,
																kitBackend::action_cfg_tab_import);
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		$this->help_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/languages/' ;
		$this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';
		$this->import_directory = WB_PATH.MEDIA_DIRECTORY.'/kit/import/';
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

  /**
   * Reset Error to empty String
   */
  public function clearError() {
  	$this->error = '';
  }

  /** Set $this->message to $message
    * 
    * @param STR $message
    */
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
   * Verhindert XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
  
  
  public function action() {
  	// ACHTUNG: erlaubte HTML Felder muessen auch in $kitBackend->action() angegeben werden !!!
  	$html_allowed = array();
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
  			$_REQUEST[$key] = $this->xssPrevent($value);
  		}
  	}
  	isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_default;
  	switch ($action):
  	case self::action_check_step_1:
  		return $this->show(self::action_step_1, $this->checkImportStep_1());
  		break;
  	case self::action_check_step_2:
  		return $this->show(self::action_step_1, $this->checkImportStep_2());
  		break;
  	case self::action_step_1:
  	case self::action_default:
  	default:
  		return $this->show(self::action_step_1, $this->dlgImportStep_1());
  		break;
  	endswitch;
  	return true;
  } // action();
  
  /**
   * Erstellt eine Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
  	$result = '';
  	// voreingestellen Navigationstab ueberschreiben?
  	if (!empty($this->overwriteNavigation)) $action = $this->overwriteNavigation;
  	foreach ($this->tab_navigation_array as $key => $value) {
  		if (!in_array($key, $this->swNavHide)) {
	  		($key == $action) ? $selected = ' class="selected"' : $selected = ''; 
	  		$result .= sprintf(	'<li%s><a href="%s">%s</a></li>', 
	  												$selected,
	  												sprintf('%s&%s=%s', $this->page_link, self::request_action, $key),
	  												$value
	  												);
  		}
  	}
  	$result = sprintf('<ul class="nav_tab">%s</ul>', $result);
  	return $result;
  } // getNavigation()
  
  
  /**
   * Ausgabe des formatierten Ergebnis mit Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @param $content - Inhalt
   * 
   * @return STR RESULT
   */
  public function show($action, $content) {
  	global $parser;
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = ' class="error"';
  	}
  	else {
  		$class = '';
  	}
  	$data = array(
  		'WB_URL'					=> WB_URL,
  		'navigation' 			=> '', //$this->getNavigation($action),
  		'class'						=> $class,
  		'content'					=> $content,
  	);
  	return $parser->get($this->template_path.'backend.body.htt', $data);
  } // show()
	
  /**
   * Start the import process
   */
  public function dlgImportStep_1() {
  	global $parser;
  	
  	$dbImport = new dbKITimport();
  	if ($dbImport->isError()) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbImport->getError()));
  		return false;
  	}
  	$items = '';
  	$row = new Dwoo_Template_File($this->template_path.'backend.import.step.1.tr.htt');
  	
  	$select = '';
  	foreach ($dbImport->separator_array as $key => $value) {
  		$selected = (isset($_REQUEST[self::request_separator]) && ($_REQUEST[self::request_separator] == $key)) ? ' selected="selected"' : '';
  		$select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
  	}
  	$data = array(
  		'label'	=> kit_label_import_separator,
  		'input'	=> sprintf('<select name="%s">%s</select>', self::request_separator, $select),
  		'help'	=> ''
  	);
  	$items .= $parser->get($row, $data);
  	
  	$select = '';
  	foreach ($dbImport->charset_array as $key => $value) {
  		$selected = (isset($_REQUEST[self::request_charset]) && ($_REQUEST[self::request_charset] == $key)) ? ' selected="selected"' : '';
  		$select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
  	}
  	$data = array(
  		'label'	=> kit_label_import_charset,
  		'input'	=> sprintf('<select name="%s">%s</select>', self::request_charset, $select),
  		'help'	=> ''
  	);
  	$items .= $parser->get($row, $data);
  	
  	$data = array(
  		'label'	=> kit_label_import_csv_file,
  		'input'	=> sprintf('<input name="%s" type="file">', self::request_csv_file),
  		'help'	=> ''
  	);
  	$items .= $parser->get($row, $data);
  	
  	// intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_import_start);
		}	 
		
  	$data = array(
  		'form_name'				=> 'kit_import',
  		'form_action'			=> $this->page_link,
  		'action_name'			=> self::request_action,
  		'action_value'		=> self::action_check_step_1,
  		'header'					=> kit_header_import_step_1,
  		'intro'						=> $intro,
  		'help'						=> kit_help_import_step_1,
  		'items'						=> $items,
  		'btn_next_step'		=> kit_btn_next_step,
  		'btn_abort'				=> kit_btn_abort,
  		'abort_location'	=> $this->page_link
  	);
  	return $parser->get($this->template_path.'backend.import.step.1.htt', $data);
  } // dlgImportStep_1()
  
  
  public function checkImportStep_1() {
  	global $parser;
  		
  	$dbImport = new dbKITimport();
  	
  	$message = '';
  	$separator = (isset($_REQUEST[self::request_separator])) ? $_REQUEST[self::request_separator] : dbKITimport::separator_comma;
  	$charset = (isset($_REQUEST[self::request_charset])) ? $_REQUEST[self::request_charset] : dbKITimport::charset_utf8;
  	if (is_uploaded_file($_FILES[self::request_csv_file]['tmp_name'])) {
  		// pruefen ob das import verzeichnis existiert
  		if (!file_exists($this->import_directory)) {
  			if (!mkdir($this->import_directory, 0777, true)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_create_dir, $this->import_directory)));
  				return false;
  			}
  		}
      // Dateiupload bearbeiten
      $csvFile = $this->import_directory.date('ymd-His').'-kit-import.csv';
      if (move_uploaded_file($_FILES[self::request_csv_file]['tmp_name'], $csvFile)) {
        // Datei uebertragen
        $message .= sprintf(kit_msg_csv_file_moved, $_FILES[self::request_csv_file]['name'], basename($csvFile));
        if (false === ($handle = fopen($csvFile, "r"))) {
        	// Datei konnte nicht geoeffnet werden
        	$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_open_file, basename($csvFile))));
        	return false;
        }
        // Wir benoetigen nur die Kopfzeile...
        if (false === ($data = fgetcsv($handle, 1000, $dbImport->separator_value[$separator]))) {
        	$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_get_csv));
        	return false;
        }
        $cols = count($data);
        if ($cols < 1) {
        	$this->setMessage(sprintf(kit_msg_csv_no_cols, basename($csvFile)));
        	return $this->dlgImportStep_1();
        }
        if (empty($data[0])) {
        	// Zeile ist leer
        	$this->setMessage(sprintf(kit_msg_csv_first_row_empty, basename($csvFile)));
        	return $this->dlgImportStep_1();
        }
        // Spalten auslesen - Zeichensatz beachten
        $columns = array();
        for ($i=0; $i < $cols; $i++) {
        	$columns[] = ($charset == dbKITimport::charset_utf8) ? $data[$i] : utf8_encode($data[$i]);
        }
        // Datensatz anlegen
        $import = array(
        	dbKITimport::field_date						=> date('Y-m-d H:i:s'),
        	dbKITimport::field_charset				=> $charset,
        	dbKITimport::field_cols_original	=> implode(';', $columns),
        	dbKITimport::field_file_original	=> $_FILES[self::request_csv_file]['name'],
        	dbKITimport::field_file_renamed		=> basename($csvFile),
        	dbKITimport::field_separator			=> $separator,
        	dbKITimport::field_status					=> dbKITimport::status_start
        );
        $id = -1;
        if (!$dbImport->sqlInsertRecord($import, $id)) {
        	$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbImport->getError()));
        	return false;
        }
        return $this->dlgImportStep_2($id);
      }
      else {
      	// Fehler beim Verschieben der CSV Datei
      	$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_move_csv_file, $_FILES[self::request_csv_file]['tmp_name'])));
      	return false;
      }
  	}
  	else {
  		// keine Datei uebermittelt
  		$this->setMessage(kit_msg_csv_no_file_transmitted);
  		return $this->dlgImportStep_1();
  	}
  } // checkImportStep_1()
	
  public function dlgImportStep_2($id=-1) {
  	global $parser;
  	$dbImport = new dbKITimport();
  	// pruefen ob die ID per REQUEST uebergeben wurde
  	if (isset($_REQUEST[dbKITimport::field_id]) && ($_REQUEST[dbKITimport::field_id] != -1)) $id = $_REQUEST[dbKITimport::field_id];
  	// ID gueltig?
  	if ($id < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_invalid_id));
  		return false;
  	}
  	$where = array(
  		dbKITimport::field_id => $id
  	);
  	$importData = array();
  	if (!$dbImport->sqlSelectRecord($where, $importData)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbImport->getError()));
  		return false;
  	}
  	if (count($importData) <1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
  		return false;
  	}
  	$importData = $importData[0];
  	$columns = explode(';', $importData[dbKITimport::field_cols_original]);
    // Felder fuer die Zuordnung anzeigen
    $items = '';
    $i = 0;
    $row = new Dwoo_Template_File($this->template_path.'backend.import.step.2.tr.htt');
    foreach ($columns as $column) {
     	$i++;
     	$field_id = sprintf('%s_%d', self::request_field, $i);
     	$select = '';
     	foreach ($dbImport->import_fields as $key => $value) {
     		$selected = (isset($_REQUEST[$field_id]) && ($_REQUEST[$field_id] == $key)) ? ' selected="selected"' : '';
     		$select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
     	}	
     	$data = array(
     		'label'	=> $column,
     		'input'	=> sprintf('<select name="%s">%s</select>', $field_id, $select),
     		'help'	=> ''
     	);
     	$items .= $parser->get($row, $data);
    }
        
    // intro oder meldung?
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', kit_intro_import_fields);
		}	 
		
    $data = array(
     	'form_name'				=> 'kit_import',
     	'form_action'			=> $this->page_link,
     	'action_name'			=> self::request_action,
     	'action_value'		=> self::action_check_step_2,
     	'count_name'			=> self::request_count,
     	'count_value'			=> $i,
     	'id_name'					=> dbKITimport::field_id,
     	'id_value'				=> $id,
     	'header'					=> kit_header_import_step_2,
     	'intro'						=> $intro,
     	'help'						=> kit_help_import_step_2,
     	'items'						=> $items,
     	'btn_next_step'		=> kit_btn_next_step,
     	'btn_abort'				=> kit_btn_abort,
     	'abort_location'	=> $this->page_link
    );
        
    return $parser->get($this->template_path.'backend.import.step.2.htt', $data);
  } // dlgImportStep_2()
  
  public function checkImportStep_2() {
  	$dbImport = new dbKITimport();
  	$id = (isset($_REQUEST[dbKITimport::field_id])) ? $_REQUEST[dbKITimport::field_id] : -1;
  	$where = array(dbKITimport::field_id => $id);
  	$import_data = array();
  	if (!$dbImport->sqlSelectRecord($where, $import_data)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbImport->getError()));
  		return false;
  	}
  	if (count($import_data) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
  		return false;
  	}
  	$import_data = $import_data[0];
  	$import_columns = explode(';', $import_data[dbKITimport::field_cols_original]);
  	$count = (isset($_REQUEST[self::request_count])) ? $_REQUEST[self::request_count] : -1;
  	$import_fields = array();
  	for ($i=1; $i < $count+1; $i++) {
  		$field = sprintf('%s_%d', self::request_field, $i);
  		if (isset($_REQUEST[$field]) && ($_REQUEST[$field] != -1)) {
  			$import_fields[$_REQUEST[$field]] = $import_columns[$i-1]; 
  		}
  	}
  	print_R($import_fields);
  	return "check";
  } // checkFields()
  
} // class kitImportDialog

?>