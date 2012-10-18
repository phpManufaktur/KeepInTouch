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

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.editor.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.import.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');

if (DEBUG_MODE) {
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
}
else {
  ini_set('display_errors', 0);
  error_reporting(E_ERROR);
}

global $parser;
global $dbContact;
global $dbContactAddress;

if (!is_object($dbContact))
  $dbContact = new dbKITcontact();
if (!is_object($dbContactAddress))
  $dbContactAddress = new dbKITcontactAddress();

class kitBackend {

  const session_basket_ids = 'basket_ids';

  const basket_from = 'baf';
  const basket_to = 'bat';
  const basket_bcc = 'babcc';

  const request_action = 'act';
  const request_csv_export = 'csvex';
  const request_csv_import = 'csvim';
  const request_items = 'its';
  const request_sub_action = 'sub';
  const request_basket_add = 'basadd';
  const request_cfg_tab = 'ctab';
  const request_import = 'imp';
  const request_last_action = 'lact';
  const request_limit_start = 'lims';
  const request_limit_end = 'lime';
  const request_page = 'pg';

  const action_default = 'def';
  const action_help = 'hlp';
  const action_list = 'list';
  const action_contact = 'con';
  const action_contact_save = 'cons';
  const action_cfg = 'cfg';
  const action_cfg_save_general = 'cfgsg';
  const action_cfg_save_array = 'cfgsa';
  const action_cfg_tab_general = 'cftg';
  const action_cfg_tab_array = 'cfta';
  const action_cfg_tab_array_save = 'cftas';
  const action_cfg_tab_import = 'cfti';
  const action_cfg_tab_provider = 'cftp';
  const action_cfg_tab_provider_save = 'cfgsp';
  const action_list_company = 2;
  const action_list_city = 5;
  const action_list_deleted = 9;
  const action_list_email = 1;
  const action_list_firstname = 7;
  const action_list_lastname = 3; // attention: don't change action_list_ sort values! They are also used by config!
  const action_list_locked = 8;
  const action_list_phone = 4;
  const action_list_street = 6;
  const action_list_unsorted = 0;
  const action_list_language = 10;
  const action_newsletter = 'nl';
  const action_import_massmail = 'impmm';
  const action_email = 'mail';
  const action_email_send = 'ms';
  const action_start = 'start';

  public $list_sort_array = array(
      // list will be sorted automatically !
      self::action_list_city => kit_list_sort_city,
      self::action_list_company => kit_list_sort_company,
      self::action_list_deleted => kit_list_sort_deleted,
      self::action_list_email => kit_list_sort_email,
      self::action_list_firstname => kit_list_sort_firstname,
      self::action_list_lastname => kit_list_sort_lastname,
      self::action_list_locked => kit_list_sort_locked,
      self::action_list_phone => kit_list_sort_phone,
      self::action_list_street => kit_list_sort_street,
      self::action_list_unsorted => kit_list_sort_unsorted,
  		self::action_list_language => kit_list_sort_language
  		);

  private $tab_navigation_array = array(
      self::action_start => kit_tab_start,
      self::action_list => kit_tab_list,
      self::action_contact => kit_tab_contact,
      self::action_email => kit_tab_email,
      self::action_newsletter => kit_tab_newsletter,
      self::action_cfg => kit_tab_config,
      self::action_help => kit_tab_help);

  private $tab_config_array = array(
      self::action_cfg_tab_general => kit_tab_cfg_general,
      self::action_cfg_tab_provider => kit_tab_cfg_provider,
      self::action_cfg_tab_array => kit_tab_cfg_array)//self::action_cfg_tab_import		=> kit_tab_cfg_import,
  //self::action_cfg_tab_export		=> kit_tab_cfg_export
  ;

  private $page_link = '';
  private $img_url = '';
  private $template_path = '';
  private $help_path = '';
  private $error = '';
  private $message = '';

  public $contact_array = array();

  private $swNavHide = array();

  protected $lang = NULL;
  protected static $table_prefix = TABLE_PREFIX;

  /**
   * Constructor for kitBackend
   */
  public function __construct() {
    global $I18n;

    $this->lang = $I18n;
    date_default_timezone_set(CFG_TIME_ZONE);

    $this->page_link = ADMIN_URL.'/admintools/tool.php?tool=kit';
    $this->template_path = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/htt/';
    $this->help_path = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/languages/';
    $this->img_url = WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/';

    // use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }

  } // __construct()

  /**
   * Check dependency to to other KIT modules
   *
   * @return boolean true on success
   */
  public function checkDependency() {
  	// check dependency for KIT
  	global $PRECHECK;
  	global $database;

  	if (file_exists(WB_PATH.'/modules/kit_dirlist/info.php')) {
  		// check only if kitDirList is installed
  		require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/precheck.php');

  		if (isset($PRECHECK['KIT']['kit_dirlist'])) {
  			$table = TABLE_PREFIX.'addons';
  			$version = $database->get_one("SELECT `version` FROM $table WHERE `directory`='kit_dirlist'", MYSQL_ASSOC);
  			if (!version_compare($version, $PRECHECK['KIT']['kit_dirlist']['VERSION'], $PRECHECK['KIT']['kit_dirlist']['OPERATOR'])) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__,
  						sprintf(kit_error_please_update, 'kitDirList', $version, $PRECHECK['KIT']['kit_dirlist']['VERSION'])));
  				return false;
  			}
  		}
  	} // if file_exists()
  	return true;
  } // checkDependency()


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
   * Return Version of Module
   *
   * @return FLOAT
   */
  public function getVersion() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1;
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = explode('=', $item);
        // return floatval
        return floatval(preg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1]));
      }
    }
    return -1;
  } // getVersion()

  /**
   * Parse the $template with the $template_data and return the result.
   *
   * @param string $template
   * @param array $template_data
   * @return string $result
   */
  public function getTemplate($template, $template_data) {
    global $parser;

    $template_path = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/templates/backend/';

    // check if a custom template exists ...
    $load_template = (file_exists($template_path.'custom.'.$template)) ? $template_path.'custom.'.$template : $template_path.$template;
    try {
      $result = $parser->get($load_template, $template_data);
    }
    catch (Exception $e) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate('Error executing the template <b>{{ template }}</b>: {{ error }}', array(
          'template' => basename($load_template),
          'error' => $e->getMessage()))));
      return false;
    }
    return $result;
  } // getTemplate()

  /**
   * return the official supported languages of KIT
   */
  public function getSupportedLanguages() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return false;
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_languages') !== false) {
        // split string $module_version
        $value = explode('=', $item);
        // return floatval
        return explode(',', $value);
      }
    }
    return false;
  } // getSupportedLanguages()

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
    // fields with HTML code
    $html_allowed = array(
        dbKITmail::field_html,
        dbKITnewsletterTemplates::field_html,
        dbKITnewsletterPreview::field_view,
        dbKITnewsletterArchive::field_html,
        dbKITprotocol::field_memo);
    foreach ($_REQUEST as $key => $value) {
      if (!in_array($key, $html_allowed)) {
        $_REQUEST[$key] = $this->xssPrevent($value);
      }
    }

    $this->checkDependency();

    isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_start;

    switch ($action) :
      case self::action_cfg:
        $this->show(self::action_cfg, $this->dlgConfig());
        break;
      case self::action_cfg_save_general:
        $this->show(self::action_cfg, $this->saveConfigGeneral());
        break;
      case self::action_contact:
        $this->show(self::action_contact, $this->dlgContact());
        break;
      case self::action_contact_save:
        $this->show(self::action_contact, $this->saveContact());
        break;
      case self::action_list:
        $this->show(self::action_list, $this->dlgList());
        break;
      case self::action_email:
        $this->show(self::action_email, $this->dlgEMail());
        break;
      case self::action_email_send:
        $this->show(self::action_email, $this->sendEMail());
        break;
      case self::action_help:
        $this->show(self::action_help, $this->dlgAbout());
        break;
      case self::action_newsletter:
        // Newsletter Module einbinden
        require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
        $newsletter = new kitNewsletterDialog();
        $result = $newsletter->action();
        if ($newsletter->isError())
          $this->setError($newsletter->getError());
        $this->show(self::action_newsletter, $result);
        break;
      case self::action_start:
        // Startdialog anzeigen
        $this->show(self::action_start, $this->dlgStart());
        break;
      case self::action_default:
      default:
        $this->show(self::action_list, $this->dlgList());
        break;
    endswitch;
  } // action

  /**
   * Erstellt eine Navigationsleiste
   *
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
    global $dbCfg;

    $result = '';
    foreach ($this->tab_navigation_array as $key => $value) {
      if (!in_array($key, $this->swNavHide)) {
        if ($key == self::action_help) {
          // Spezial: pruefen, ob zusaetzliche TAB's einzufuegen sind...
          $tab_array = $dbCfg->getValue(dbKITcfg::cfgAddAppTab);
          if (is_array($tab_array)) {
            foreach ($tab_array as $item) {
              $tab = explode('|', $item);
              if (count($tab) > 1)
                $result .= sprintf('<li class="extra_tab"><a href="%s">%s</a></li>', $tab[1], $tab[0]);
            }
          }
        }
        ($key == $action) ? $selected = ' class="selected"' : $selected = '';
        $result .= sprintf('<li%s><a href="%s">%s</a></li>', $selected, sprintf('%s&%s=%s', $this->page_link, self::request_action, $key), $value);
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
   * @return ECHO RESULT
   */
  public function show($action, $content) {
    global $parser;
    $class = '';
    if ($this->isError()) {
      $content = $this->getError();
      $class = ' class="error"';
    }
    $data = array(
        'WB_URL' => WB_URL,
        'navigation' => $this->getNavigation($action),
        'class' => $class,
        'content' => $content);
    $parser->output($this->template_path.'backend.body.htt', $data);
  } // show()

  private function addContactToBasket($contact_id) {
    if (!isset($_SESSION[self::session_basket_ids])) {
      $_SESSION[self::session_basket_ids] = array();
    }
    $_SESSION[self::session_basket_ids][] = $contact_id;
  } // addContact2Basket()

  private function getContactFromBasket() {
    if (!isset($_SESSION[self::session_basket_ids])) {
      $_SESSION[self::session_basket_ids] = array();
    }
    return $_SESSION[self::session_basket_ids];
  }

  private function clearContactBasket() {
    if (!isset($_SESSION[self::session_basket_ids])) {
      $_SESSION[self::session_basket_ids] = array();
    }
    $_SESSION[self::session_basket_ids] = array();
  }

  /**
   * Contact List
   * shows all contacts sortable and much more.
   *
   * @return boolean|Ambigous <string, boolean, mixed>
   */
  protected function dlgList() {
    global $database;
    global $kitContactInterface;
    global $dbCfg;

    require_once WB_PATH.'/modules/kit/class.interface.php';

    $get_deleted = false;

    $SQL = "SELECT `contact_id` FROM `".self::$table_prefix."mod_kit_contact` WHERE `contact_status` != 'statusDeleted'";
    if (null == ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    $contact_array = array();

    while (false !== ($con = $query->fetchRow(MYSQL_ASSOC))) {
      $contact = array();
      if (!$kitContactInterface->getContact($con['contact_id'], $contact, $get_deleted)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $kitContactInterface->getError()));
        return false;
      }
      $contact['link'] = array(
          'edit' => sprintf('%s&%s', $this->page_link, http_build_query(array(
              self::request_action => self::action_contact,
              dbKITcontact::field_id => $con['contact_id']
              ))),
          );
      $contact_array[$con['contact_id']] = $contact;
    }

    // get the names of the free fields and free notes
    $field_translation = array(
        'kit_free_field_1' => 'kit_free_field_1',
        'kit_free_field_2' => 'kit_free_field_2',
        'kit_free_field_3' => 'kit_free_field_3',
        'kit_free_field_4' => 'kit_free_field_4',
        'kit_free_field_5' => 'kit_free_field_5',
        'kit_free_note_1' => 'kit_free_note_1',
        'kit_free_note_2' => 'kit_free_note_2'
        );
    $free_fields = $dbCfg->getValue(dbKITcfg::cfgAdditionalFields);
    foreach ($free_fields as $field) {
      if (!strpos($field, '|')) continue;
      list($number, $name) = explode('|', $field);
      $field_translation[sprintf('kit_free_field_%d', $number)] = $name;
    }
    $free_notes = $dbCfg->getValue(dbKITcfg::cfgAdditionalNotes);
    foreach ($free_fields as $field) {
      if (!strpos($field, '|')) continue;
      list($number, $name) = explode('|', $field);
      $field_translation[sprintf('kit_free_note_%d', $number)] = $name;
    }

    // check if libraryAdmin exists
    if (file_exists(WB_PATH.'/modules/libraryadmin/inc/class.LABackend.php')) {
      require_once WB_PATH.'/modules/libraryadmin/inc/class.LABackend.php';
      // create instance; if you're not using OOP, use a simple var, like $la
      $libraryAdmin = new LABackend();
      // load the preset
      $libraryAdmin->loadPreset(array(
          'module' => 'kit',
          'lib'    => 'lib_jquery',
          'preset' => 'dataTable'
      ));
      // print the preset
      $libraryAdmin->printPreset();
    }

    $data = array(
        'language' => LANGUAGE,
        'fields' => $dbCfg->getValue(dbKITcfg::cfgContactListColumns),
        'contacts' => $contact_array,
        'translation' => $field_translation
        );
    return $this->getTemplate('list.dwoo', $data);
  } // dlgList()

  /**
   * Dialog for creating and editing contacts
   *
   * @todo personal photo/image is not supported yet!
   */
/*
  public function dlgContact() {
    global $dbContact;
    global $tools;
    global $parser;
    global $dbContactAddress;
    global $dbMemos;
    global $dbProtocol;
    global $dbRegister;
    global $dbCfg;
    global $dbLanguages;

    $id = ((isset($_REQUEST[dbKITcontact::field_id])) && (!empty($_REQUEST[dbKITcontact::field_id]))) ? intval($_REQUEST[dbKITcontact::field_id]) : -1;


    if ($id > 0) {
      // Existierende Adresse auslesen
      $item = array();
      $where = array();
      $where[dbKITcontact::field_id] = $id;
      if (!$dbContact->sqlSelectRecord($where, $item)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      if (sizeof($item) < 1) {
        // Fehler: gesuchter Datensatz existiert nicht
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
        return false;
      }
      $item = $item[0];
    }
    else {
      // neuer Datensatz
      $item = $dbContact->getFields();
      $item[dbKITcontact::field_id] = $id;
      $item[dbKITcontact::field_status] = dbKITcontact::status_locked;
      $item[dbKITcontact::field_type] = dbKITcontact::type_person; //array_search(kit_contact_type_person, $dbContact->type_array);
      $item[dbKITcontact::field_access] = dbKITcontact::access_internal; //array_search(kit_contact_access_public, $dbContact->access_array);
      $item[dbKITcontact::field_person_title] = dbKITcontact::person_title_mister; //array_search(kit_contact_person_title_mister, $dbContact->person_title_array);
      $item[dbKITcontact::field_person_title_academic] = dbKITcontact::person_title_academic_none; //array_search(kit_contact_person_title_academic_none, $dbContact->person_title_academic_array);
      $item[dbKITcontact::field_company_title] = dbKITcontact::company_title_none; //array_search(kit_contact_company_title_none, $dbContact->company_title_array);
      $lang_default = $dbCfg->getValue(dbKITcfg::cfgContactLanguageDefault);
      $lang_default = strtolower(trim($lang_default));
      $item[dbKITcontact::field_contact_language] = $lang_default;
      foreach ($item as $key => $value) {
        if (isset($_REQUEST[$key])) {
          (is_string($_REQUEST[$key])) ? $item[$key] = utf8_decode($_REQUEST[$key]) : $item[$key] = $_REQUEST[$key];
        }
      }
    }

    // init empty contact_array
    $contact_array = array();

    // contact ID
    $contact_array['id'] = array(
        'name' => dbKITcontact::field_id,
        'value' => $id
        );

    // contact identifier
    $contact_array['identifier'] = array(
        'name' => dbKITcontact::field_contact_identifier,
        'value' => $item[dbKITcontact::field_contact_identifier]
        );

    // contact type
    $options = array();
    foreach ($dbContact->type_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['type'] = array(
        'name' => dbKITcontact::field_type,
        'value' => $item[dbKITcontact::field_type],
        'options' => $options
        );

    // contact access
    $options = array();
    foreach ($dbContact->access_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['access'] = array(
        'name' => dbKITcontact::field_access,
        'value' => $item[dbKITcontact::field_access],
        'options' => $options
        );

    // contact status
    $options = array();
    foreach ($dbContact->status_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['status'] = array(
        'name' => dbKITcontact::field_status,
        'value' => $item[dbKITcontact::field_status],
        'options' => $options
        );

    // contact language
    $languages = array();
    if (!$dbLanguages->sqlExec(sprintf("SELECT * FROM %s", $dbLanguages->getTableName()), $languages)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbLanguages->getError()));
      return false;
    }
    $lang_select = $dbCfg->getValue(dbKITcfg::cfgContactLanguageSelect);
    $lang_select = strtolower(trim($lang_select));
    $options = array();
    foreach ($languages as $language) {
      if ($lang_select == 'iso') {
        $lang = $language[dbKITlanguages::FIELD_ISO];
      }
      elseif ($lang_select == 'english') {
        $lang = $language[dbKITlanguages::FIELD_ENGLISH];
      }
      else {
        // use local settings
        $lang = $language[dbKITlanguages::FIELD_LOCAL];
      }
      $options[] = array(
          'value' => $language[dbKITlanguages::FIELD_ISO],
          'text' => $lang
          );
    }
    $contact_array['contact_language'] = array(
        'name' => dbKITcontact::field_contact_language,
        'value' => $item[dbKITcontact::field_contact_language],
        'options' => $options
        );

    // person title
    $options = array();
    foreach ($dbContact->person_title_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['person']['title'] = array(
        'name' => dbKITcontact::field_person_title,
        'value' => $item[dbKITcontact::field_person_title],
        'options' => $options
        );
    // person title academic
    $options = array();
    foreach ($dbContact->person_title_academic_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['person']['title_academic'] = array(
        'name' => dbKITcontact::field_person_title_academic,
        'value' => $item[dbKITcontact::field_person_title_academic],
        'options' => $options
        );
    // person last name
    $contact_array['person']['last_name'] = array(
        'name' => dbKITcontact::field_person_last_name,
        'value' => $item[dbKITcontact::field_person_last_name]
        );
    // person first name
    $contact_array['person']['first_name'] = array(
        'name' => dbKITcontact::field_person_first_name,
        'value' => $item[dbKITcontact::field_person_first_name]
        );
    // person function
    $contact_array['person']['function'] = array(
        'name' => dbKITcontact::field_person_function,
        'value' => $item[dbKITcontact::field_person_function]
        );
    // person birthday
    $timestamp = -1;
    if ($this->check_date($item[dbKITcontact::field_birthday], $timestamp)) {
      $datetime = new DateTime($item[dbKITcontact::field_birthday]);
      $date = $datetime->format(DATE_FORMAT);
    }
    else {
      $date = $item[dbKITcontact::field_birthday];
    }
    $contact_array['person']['birthday'] = array(
        'name' => dbKITcontact::field_birthday,
        'value' => $date
        );

    // contact since
    $timestamp = -1;
    if ($this->check_date($item[dbKITcontact::field_contact_since], $timestamp)) {
      $datetime = new DateTime($item[dbKITcontact::field_contact_since]);
      $date = $datetime->format(DATE_FORMAT);
    }
    else {
      $date = $item[dbKITcontact::field_contact_since];
    }
    $contact_array['contact_since'] = array(
        'name' => dbKITcontact::field_contact_since,
        'value' => $date
    );

    // company title
    $options = array();
    foreach ($dbContact->company_title_array as $value => $text) {
      $options[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['company']['title'] = array(
        'name' => dbKITcontact::field_company_title,
        'value' => $item[dbKITcontact::field_company_title],
        'options' => $options
        );
    // company name
    $contact_array['company']['name'] = array(
        'name' => dbKITcontact::field_company_name,
        'value' => $item[dbKITcontact::field_company_name]
        );
    // company department
    $contact_array['company']['department'] = array(
        'name' => dbKITcontact::field_company_department,
        'value' => $item[dbKITcontact::field_company_department]
        );
    // company additional
    $contact_array['company']['additional'] = array(
        'name' => dbKITcontact::field_company_additional,
        'value' => $item[dbKITcontact::field_company_additional]
        );

    // contact notes
    if ($item[dbKITcontact::field_contact_note] > 0) {
      // get memo record
      $where = array();
      $where[dbKITmemos::field_id] = $item[dbKITcontact::field_contact_note];
      $memos = array();
      if (!$dbMemos->sqlSelectRecord($where, $memos)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
        return false;
      }
      $memo = $memos[0][dbKITmemos::field_memo];
    }
    else {
      $memo = '';
    }
    $contact_array['notes'] = array(
        'name' => dbKITcontact::field_contact_note,
        'value' => $memo
        );

    // email addresses
    if (empty($item[dbKITcontact::field_email])) {
      $item[dbKITcontact::field_email] = '-1|-1';
    }
    $email_array = explode(';', $item[dbKITcontact::field_email]);
    // append additional email field (for a new entry)
    if (!in_array('-1|-1', $email_array))
      $email_array[] = '-1|-1';
    $email_array_str = implode(';', $email_array);

    $emails = array();
    $i=-1;
    foreach ($email_array as $email_item) {
      $i++;
      list($email_type, $email_address) = explode('|', $email_item);
      if (isset($_REQUEST[dbKITcontact::field_email.'_'.$i]))
        $email_address = $_REQUEST[dbKITcontact::field_email.'_'.$i];
      if (isset($_REQUEST['email_type_'.$i]))
        $email_type = $_REQUEST['email_type_'.$i];
      if ($email_address == -1)
        $email_address = '';
      $emails[] = array(
          'name' => sprintf('%s_%d', dbKITcontact::field_email, $i),
          'name_type' => sprintf('email_type_%d', $i),
          'value' => $email_address,
          'type' => $email_type
          );
    }
    $types = array();
    foreach ($dbContact->email_array as $value => $text) {
      $types[] = array(
          'value' => $value,
          'text' => $text
          );
    }
    $contact_array['emails'] = array(
        'name' => dbKITcontact::field_email,
        'value' => $email_array_str,
        'standard' => array(
            'value' =>$item[dbKITcontact::field_email_standard],
            'name' => dbKITcontact::field_email_standard
            ),
        'emails' => $emails,
        'types' => $types
        );

    // phone numbers
    if (empty($item[dbKITcontact::field_phone])) {
      $item[dbKITcontact::field_phone] = '-1|-1';
    }
    $phone_array = explode(';', $item[dbKITcontact::field_phone]);
    // append additional phone field (for a new entry)
    if (!in_array('-1|-1', $phone_array))
      $email_array[] = '-1|-1';
    $phone_array_str = implode(';', $phone_array);

    $phones = array();
    $i=-1;
    foreach ($phone_array as $phone_item) {
      $i++;
      list($phone_type, $phone_number) = explode('|', $phone_item);
      if (isset($_REQUEST[dbKITcontact::field_phone.'_'.$i]))
        $phone_number = $_REQUEST[dbKITcontact::field_phone.'_'.$i];
      if (isset($_REQUEST['phone_type_'.$i]))
        $phone_type = $_REQUEST['phone_type_'.$i];
      if ($phone_number == -1)
        $phone_number = '';
      $phones[] = array(
          'name' => sprintf('%s_%d', dbKITcontact::field_phone, $i),
          'name_type' => sprintf('phone_type_%d', $i),
          'value' => $phone_number,
          'type' => $phone_type
      );
    }
    $types = array();
    foreach ($dbContact->phone_array as $value => $text) {
      $types[] = array(
          'value' => $value,
          'text' => $text
      );
    }
    $contact_array['phones'] = array(
        'name' => dbKITcontact::field_phone,
        'value' => $phone_array_str,
        'standard' => array(
            'value' =>$item[dbKITcontact::field_phone_standard],
            'name' => dbKITcontact::field_phone_standard
        ),
        'phones' => $phones,
        'types' => $types
    );

    // internet addresses
    if (empty($item[dbKITcontact::field_internet])) {
      $item[dbKITcontact::field_internet] = '-1|-1';
    }
    $internet_array = explode(';', $item[dbKITcontact::field_internet]);
    // append additional internet field (for a new entry)
    if (!in_array('-1|-1', $internet_array))
      $internet_array[] = '-1|-1';
    $internet_array_str = implode(';', $internet_array);

    $internet = array();
    $i=-1;
    foreach ($internet_array as $internet_item) {
      $i++;
      list($internet_type, $internet_url) = explode('|', $internet_item);
      if (isset($_REQUEST[dbKITcontact::field_internet.'_'.$i]))
        $internet_url = $_REQUEST[dbKITcontact::field_internet.'_'.$i];
      if (isset($_REQUEST['internet_type_'.$i]))
        $internet_type = $_REQUEST['internet_type_'.$i];
      if ($internet_url == -1)
        $internet_url = '';
      $internet[] = array(
          'name' => sprintf('%s_%d', dbKITcontact::field_internet, $i),
          'name_type' => sprintf('internet_type_%d', $i),
          'value' => $internet_url,
          'type' => $internet_type
      );
    }
    $types = array();
    foreach ($dbContact->internet_array as $value => $text) {
      $types[] = array(
          'value' => $value,
          'text' => $text
      );
    }
    $contact_array['internet'] = array(
        'name' => dbKITcontact::field_internet,
        'value' => $internet_array_str,
        'internet' => $internet,
        'types' => $types
    );

    if (empty($item[dbKITcontact::field_address])) $item[dbKITcontact::field_address] = 0;
    $address_ids = explode(',', $item[dbKITcontact::field_address]);
    // add zero to insert an additional empty address
    if (!in_array(0, $address_ids)) $address_ids[] = 0;
    $addresses = array();
    $address_array = array();
    $map_address = array();
    $address_standard = (isset($item[dbKITcontact::field_address_standard]) && is_numeric($item[dbKITcontact::field_address_standard])) ? $item[dbKITcontact::field_address_standard] : 0;

    foreach ($address_ids as $addr) {
      if ($addr < 1) {
        // insert empty fields
        $addr = 0;
        $addr_values = array(
            dbKITcontactAddress::field_id => $addr,
            dbKITcontactAddress::field_street => (isset($_REQUEST[dbKITcontactAddress::field_street.'_'.$addr])) ? $_REQUEST[dbKITcontactAddress::field_street.'_'.$addr] : '',
            dbKITcontactAddress::field_zip => (isset($_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr])) ? $_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr] : '',
            dbKITcontactAddress::field_city => (isset($_REQUEST[dbKITcontactAddress::field_city.'_'.$addr])) ? $_REQUEST[dbKITcontactAddress::field_city.'_'.$addr] : '',
            dbKITcontactAddress::field_country => (isset($_REQUEST[dbKITcontactAddress::field_country.'_'.$addr])) ? $_REQUEST[dbKITcontactAddress::field_country.'_'.$addr] : LANGUAGE,
            dbKITcontactAddress::field_type => (isset($_REQUEST[dbKITcontactAddress::field_type.'_'.$addr])) ? $_REQUEST[dbKITcontactAddress::field_type.'_'.$addr] : dbKITcontactAddress::type_undefined,
            dbKITcontactAddress::field_status => dbKITcontactAddress::status_active
            );
      }
      else {
        $addr_values = array();
        $SQL = sprintf("SELECT * FROM %s WHERE `%s`='%s' AND `%s`='%s'",
            $dbContactAddress->getTableName(),
            dbKITcontactAddress::field_id,
            $addr,
            dbKITcontactAddress::field_status,
            dbKITcontactAddress::status_active);
        if (!$dbContactAddress->sqlExec($SQL, $addr_values)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
          return false;
        }
        if (count($addr_values) < 1) continue;
        $addr_values = $addr_values[0];
      }
      // get the address for the map
      if ($addr == $address_standard) $map_address = $addr_values;
      // now collect address array ...
      $address_array[] = $addr;

      // get country list
      $country_array = $dbContactAddress->country_array;
      // sort countries by key (2-digit identifer)
      ksort($country_array);
      $countries = array();
      foreach ($country_array as $value => $text) {
        $countries[] = array(
            'value' => $value,
            'text' => $text
        );
      }
      // get address type list
      $type_array = $dbContactAddress->type_array;
      $types = array();
      foreach ($type_array as $value => $text) {
        $types[] = array(
            'value' => $value,
            'text' => $text
            );
      }
      // get status array
      $status_array = $dbContactAddress->status_array;
      $status = array();
      foreach ($status_array as $value => $text) {
        $status[] = array(
            'value' => $value,
            'text' => $text
            );
      }

      $addresses[] = array(
          'id' => array(
              'name' => dbKITcontactAddress::field_id,
              'value' => $addr
              ),
          'street' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_street, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_street]
              ),
          'zip' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_zip, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_zip]
              ),
          'city' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_city, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_city]
          ),
          'country' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_country, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_country],
              'options' => $countries
          ),
          'type' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_type, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_type],
              'options' => $types
              ),
          'status' => array(
              'name' => sprintf('%s_%d', dbKITcontactAddress::field_status, $addr),
              'value' => $addr_values[dbKITcontactAddress::field_status],
              'options' => $status
              )
          );
    }

    $contact_array['addresses'] = array(
        'name' => dbKITcontact::field_address,
        'value' => implode(',', $address_array),
        'addresses' => $addresses,
        'standard' => array(
            'name' => dbKITcontact::field_address_standard,
            'value' => $address_standard
            )
        );

    // Google map
    $width = $dbCfg->getValue(dbKITcfg::cfgGMapStaticWidth);
    $height = $dbCfg->getValue(dbKITcfg::cfgGMapStaticHeight);
    $zoom = $dbCfg->getValue(dbKITcfg::cfgGMapStaticZoom);
    $color = $dbCfg->getValue(dbKITcfg::cfgGMapStaticMarkerColor);
    $protocol = ($dbCfg->getValue(dbKITcfg::cfgUseSSL)) ? 'https' : 'http';
    if (isset($map_address[dbKITcontactAddress::field_id]) && ($map_address[dbKITcontactAddress::field_id] > 0)) {
      // try to show a valid address
      $location = '';
      if (!empty($map_address[dbKITcontactAddress::field_street])) $location .= $map_address[dbKITcontactAddress::field_street];
      if (!empty($map_address[dbKITcontactAddress::field_zip])) {
        if (!empty($location)) $location .= ',';
        $location .= $map_address[dbKITcontactAddress::field_zip];
      }
      if (!empty($map_address[dbKITcontactAddress::field_city])) {
        if (!empty($location)) $location .= ',';
        $location .= $map_address[dbKITcontactAddress::field_city];
      }
      if (!empty($map_address[dbKITcontactAddress::field_country])) {
        if (!empty($location)) $location .= ',';
        $location .= $dbContactAddress->country_array[$map_address[dbKITcontactAddress::field_country]];
      }
      $url = sprintf('%s://maps.google.com/maps/api/staticmap?center=%s&size=%dx%d&sensor=false&zoom=%d&markers=color:%s|label:.|%s',
          $protocol, urlencode($location), $width, $height, $zoom, $color, urlencode($location));
      $contact_array['map'] = array(
          'use' => (int) $dbCfg->getValue(dbKITcfg::cfgGMapStaticUse),
          'url' => $url,
          'width' => $width,
          'height' => $height
      );
    }
    else {
      // no valid address, still show europe
      $url = sprintf('%s://maps.google.com/maps/api/staticmap?center=europa&size=%dx%d&sensor=false',
          $protocol, $width, $height);
      $contact_array['map'] = array(
          'use' => (int) $dbCfg->getValue(dbKITcfg::cfgGMapStaticUse),
          'url' => $url,
          'width' => $width,
          'height' => $height
          );
    }

    // Categories
    $categories = array();
    $categories_array = explode(',', $item[dbKITcontact::field_category]);
    foreach ($dbContact->category_array as $value => $text) {
      $categories[$value] = array(
          'value' => $value,
          'text' => $text,
          'checked' => (int) in_array($value, $categories_array)
          );
    }
    $contact_array['categories'] = array(
        'name' => dbKITcontact::field_category,
        'values' => $categories_array,
        'categories' => $categories
        );

    // Distribution
    $distribution = array();
    $distribution_array = explode(',', $item[dbKITcontact::field_distribution]);
    foreach ($dbContact->distribution_array as $value => $text) {
      $distribution[$value] = array(
          'value' => $value,
          'text' => $text,
          'checked' => (int) in_array($value, $distribution_array)
      );
    }
    $contact_array['distribution'] = array(
        'name' => dbKITcontact::field_distribution,
        'values' => $distribution_array,
        'distribution' => $distribution
    );

    // Newsletter
    $newsletter = array();
    $newsletter_array = explode(',', $item[dbKITcontact::field_newsletter]);
    foreach ($dbContact->newsletter_array as $value => $text) {
      $newsletter[$value] = array(
          'value' => $value,
          'text' => $text,
          'checked' => (int) in_array($value, $newsletter_array)
      );
    }
    $contact_array['newsletter'] = array(
        'name' => dbKITcontact::field_newsletter,
        'values' => $newsletter_array,
        'newsletter' => $newsletter
    );

 */   /*
    // Kategorien

    $categories = '';
    $categories_array = explode(',', $item[dbKITcontact::field_category]);
    $categories_td = '';
    $categories_tr = '';
    $i = 0;
    // Template fuer die Kategorien
    $template_category_td = new Dwoo_Template_File($this->template_path.'backend.contact.categories.td.htt');
    $template_category_tr = new Dwoo_Template_File($this->template_path.'backend.contact.categories.tr.htt');
    foreach ($dbContact->category_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        //$categories_tr .= sprintf('<tr>%s</tr>', $categories_td);
        $data = array('category_td' => $categories_td);
        $categories_tr .= $parser->get($template_category_tr, $data);
        $categories_td = '';
      }
      (in_array($key, $categories_array)) ? $checked = ' checked="checked"' : $checked = '';
      $cat = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_category, $key, $checked, $value);
      $data = array('category_item' => $cat);
      $categories_td .= $parser->get($template_category_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('category_item' => '&nbsp;');
      $categories_td .= $parser->get($template_category_td, $data);

      //$categories_td .= '<td>&nbsp;</td>';
    }
    $data = array('category_td' => $categories_td);
    $categories_tr .= $parser->get($template_category_tr, $data);
    $data = array('rows' => $categories_tr);
    $categories = $parser->get($this->template_path.'backend.contact.categories.htt', $data);


    // Newsletter

    $newsletter = '';
    $newsletter_array = explode(',', $item[dbKITcontact::field_newsletter]);
    $newsletter_td = '';
    $newsletter_tr = '';
    $i = 0;
    // Template fuer die Newsletter
    $template_newsletter_td = new Dwoo_Template_File($this->template_path.'backend.contact.newsletter.td.htt');
    $template_newsletter_tr = new Dwoo_Template_File($this->template_path.'backend.contact.newsletter.tr.htt');
    foreach ($dbContact->newsletter_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        $data = array('newsletter_td' => $newsletter_td);
        $newsletter_tr .= $parser->get($template_newsletter_tr, $data);
        $newsletter_td = '';
      }
      (in_array($key, $newsletter_array)) ? $checked = ' checked="checked"' : $checked = '';
      $news = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_newsletter, $key, $checked, $value);
      $data = array('newsletter_item' => $news);
      $newsletter_td .= $parser->get($template_newsletter_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('newsletter_item' => '&nbsp;');
      $newsletter_td .= $parser->get($template_newsletter_td, $data);
    }
    $data = array('newsletter_td' => $newsletter_td);
    $newsletter_tr .= $parser->get($template_newsletter_tr, $data);
    $data = array('rows' => $newsletter_tr);
    $newsletter = $parser->get($this->template_path.'backend.contact.newsletter.htt', $data);


    // Verteiler

    $distribution = '';
    $distribution_array = explode(',', $item[dbKITcontact::field_distribution]);
    $distribution_td = '';
    $distribution_tr = '';
    $i = 0;
    // Template fuer die Newsletter
    $template_distribution_td = new Dwoo_Template_File($this->template_path.'backend.contact.distribution.td.htt');
    $template_distribution_tr = new Dwoo_Template_File($this->template_path.'backend.contact.distribution.tr.htt');
    foreach ($dbContact->distribution_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        $data = array('distribution_td' => $distribution_td);
        $distribution_tr .= $parser->get($template_distribution_tr, $data);
        $distribution_td = '';
      }
      (in_array($key, $distribution_array)) ? $checked = ' checked="checked"' : $checked = '';
      $news = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_distribution, $key, $checked, $value);
      $data = array('distribution_item' => $news);
      $distribution_td .= $parser->get($template_distribution_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('distribution_item' => '&nbsp;');
      $distribution_td .= $parser->get($template_distribution_td, $data);
    }
    $data = array('distribution_td' => $distribution_td);
    $distribution_tr .= $parser->get($template_distribution_tr, $data);
    $data = array('rows' => $distribution_tr);
    $distribution = $parser->get($this->template_path.'backend.contact.distribution.htt', $data);

    */
    // the data array for the parser
 /*   $data = array(
        'form' => array(
            'name' => 'kit_contact',
            'action' => $this->page_link
            ),
        'action' => array(
            'name' => self::request_action,
            'value' => self::action_contact_save
            ),
        'language' => (LANGUAGE == 'EN') ? '' : strtolower(LANGUAGE),
        'contact' => $contact_array
        );
    return $this->getTemplate('contact.lte', $data);
  } // dlgContact()
*/

  /**
   * Dialog for creating and editing contacts
   */

  public function dlgContact() {
    global $dbContact;
    global $tools;
    global $parser;
    global $dbContactAddress;
    global $dbMemos;
    global $dbProtocol;
    global $dbRegister;
    global $dbCfg;
    global $dbLanguages;

    $id = ((isset($_REQUEST[dbKITcontact::field_id])) && (!empty($_REQUEST[dbKITcontact::field_id]))) ? intval($_REQUEST[dbKITcontact::field_id]) : -1;


    if ($id != -1) {
      // Existierende Adresse auslesen
      $item = array();
      $where = array();
      $where[dbKITcontact::field_id] = $id;
      if (!$dbContact->sqlSelectRecord($where, $item)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      if (sizeof($item) < 1) {
        // Fehler: gesuchter Datensatz existiert nicht
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
      }
      $item = $item[0];
    }
    else {
      // neuer Datensatz
      $item = $dbContact->getFields();
      $item[dbKITcontact::field_id] = $id;
      $item[dbKITcontact::field_status] = dbKITcontact::status_active;
      $item[dbKITcontact::field_type] = array_search(kit_contact_type_person, $dbContact->type_array);
      $item[dbKITcontact::field_access] = array_search(kit_contact_access_public, $dbContact->access_array);
      $item[dbKITcontact::field_person_title] = array_search(kit_contact_person_title_mister, $dbContact->person_title_array);
      $item[dbKITcontact::field_person_title_academic] = array_search(kit_contact_person_title_academic_none, $dbContact->person_title_academic_array);
      $item[dbKITcontact::field_company_title] = array_search(kit_contact_company_title_none, $dbContact->company_title_array);
      $lang_default = $dbCfg->getValue(dbKITcfg::cfgContactLanguageDefault);
      $lang_default = strtolower(trim($lang_default));
      $item[dbKITcontact::field_contact_language] = $lang_default;
      foreach ($item as $key => $value) {
        if (isset($_REQUEST[$key])) {
          (is_string($_REQUEST[$key])) ? $item[$key] = utf8_decode($_REQUEST[$key]) : $item[$key] = $_REQUEST[$key];
        }
      }
    }
    // id
    ($id != -1) ? $value_id = sprintf('%05d', $id) : $value_id = kit_text_new_id;
    // formname
    $form_name = 'kit_contact';
    // init template
    // type
    $select = '';
    foreach ($dbContact->type_array as $value => $name) {
      ($value == $item[dbKITcontact::field_type]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $type = sprintf('<select name="%s">%s</select>', dbKITcontact::field_type, $select);
    // access
    $select = '';
    foreach ($dbContact->access_array as $value => $name) {
      ($value == $item[dbKITcontact::field_access]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $access = sprintf('<select name="%s">%s</select>', dbKITcontact::field_access, $select);

    // contact language
    $languages = array();
    if (!$dbLanguages->sqlExec(sprintf("SELECT * FROM %s", $dbLanguages->getTableName()), $languages)) {
    	$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbLanguages->getError()));
    	return false;
    }
    $select = '';
    $lang_select = $dbCfg->getValue(dbKITcfg::cfgContactLanguageSelect);
    $lang_select = strtolower(trim($lang_select));
    foreach ($languages as $language) {
    	$selected = ($language[dbKITlanguages::FIELD_ISO] == $item[dbKITcontact::field_contact_language]) ? ' selected="selected"' : '';
    	if ($lang_select == 'iso') {
    		$lang = $language[dbKITlanguages::FIELD_ISO];
    	}
    	elseif ($lang_select == 'english') {
    		$lang = $language[dbKITlanguages::FIELD_ENGLISH];
    	}
    	else {
    		// use local settings
    		$lang = $language[dbKITlanguages::FIELD_LOCAL];
    	}
    	$select .= sprintf('<option value="%s"%s>%s</option>', $language[dbKITlanguages::FIELD_ISO], $selected, $lang);
    }
    $contact_languages = sprintf('<select name="%s">%s</select>', dbKITcontact::field_contact_language, $select);

    // status
    $select = '';
    foreach ($dbContact->status_array as $value => $name) {
      ($value == $item[dbKITcontact::field_status]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $status = sprintf('<select name="%s">%s</select>', dbKITcontact::field_status, $select);
    // identifier
    $identifier = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_contact_identifier, $item[dbKITcontact::field_contact_identifier]);
    // company title
    $select = '';
    foreach ($dbContact->company_title_array as $value => $name) {
      ($value == $item[dbKITcontact::field_company_title]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $company_title = sprintf('<select name="%s">%s</select>', dbKITcontact::field_company_title, $select);
    // company name
    $company_name = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_company_name, $item[dbKITcontact::field_company_name]);
    // company department
    $company_department = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_company_department, $item[dbKITcontact::field_company_department]);
    // company additional
    $company_additional = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_company_additional, $item[dbKITcontact::field_company_additional]);
    // person title
    $select = '';
    foreach ($dbContact->person_title_array as $value => $name) {
      ($value == $item[dbKITcontact::field_person_title]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $person_title = sprintf('<select name="%s">%s</select>', dbKITcontact::field_person_title, $select);
    // person title academic
    $select = '';
    foreach ($dbContact->person_title_academic_array as $value => $name) {
      ($value == $item[dbKITcontact::field_person_title_academic]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $name);
    }
    $person_title_academic = sprintf('<select name="%s">%s</select>', dbKITcontact::field_person_title_academic, $select);
    // person last name
    $person_last_name = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_person_last_name, $item[dbKITcontact::field_person_last_name]);
    // person first name
    $person_first_name = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_person_first_name, $item[dbKITcontact::field_person_first_name]);
    // person function
    $person_function = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_person_function, $item[dbKITcontact::field_person_function]);

    // address standard
    if (isset($item[dbKITcontact::field_address_standard]) && is_numeric($item[dbKITcontact::field_address_standard])) {
      $address_standard = $item[dbKITcontact::field_address_standard];
    }
    else {
      $address_standard = 0;
    }
    // insert addresses
    $map_address = '';
    $addresses = '';
    if (empty($item[dbKITcontact::field_address]))
      $item[dbKITcontact::field_address] = -1;
    $address_array = explode(',', $item[dbKITcontact::field_address]);
    if (isset($_REQUEST['add_address']))
      $address_array[] = 0;
    $address_array_new = array();
    // address template
    $addr_template = new Dwoo_Template_File($this->template_path.'backend.contact.address.htt');
    $countries = $dbContactAddress->country_array;
    // sort countries by key (2-digit identifer)
    ksort($countries);
    foreach ($address_array as $addr) {
      $addr_values = array();
      $skip = false;
      if (($addr == -1) || ($addr == 0)) {
        // insert empty fields
        $addr = 0;
        (isset($_REQUEST[dbKITcontactAddress::field_street.'_'.$addr])) ? $street = $_REQUEST[dbKITcontactAddress::field_street.'_'.$addr] : $street = '';
        (isset($_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr])) ? $zip = $_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr] : $zip = '';
        (isset($_REQUEST[dbKITcontactAddress::field_city.'_'.$addr])) ? $city = $_REQUEST[dbKITcontactAddress::field_city.'_'.$addr] : $city = '';
        (isset($_REQUEST[dbKITcontactAddress::field_country.'_'.$addr])) ? $country = $_REQUEST[dbKITcontactAddress::field_country.'_'.$addr] : $country = 'DE';
        (isset($_REQUEST[dbKITcontactAddress::field_type.'_'.$addr])) ? $addr_type = $_REQUEST[dbKITcontactAddress::field_type.'_'.$addr] : $addr_type = dbKITcontactAddress::type_undefined;
        $addr_values[dbKITcontactAddress::field_id] = $addr;
        $addr_values[dbKITcontactAddress::field_street] = $street;
        $addr_values[dbKITcontactAddress::field_zip] = $zip;
        $addr_values[dbKITcontactAddress::field_city] = $city;
        $addr_values[dbKITcontactAddress::field_country] = $country;
        $addr_values[dbKITcontactAddress::field_type] = $addr_type;
        $addr_values[dbKITcontactAddress::field_status] = dbKITcontactAddress::status_active;
      }
      else {
        $where = array();
        $where[dbKITcontactAddress::field_id] = $addr;
        $where[dbKITcontactAddress::field_status] = dbKITcontactAddress::status_active;
        if (!$dbContactAddress->sqlSelectRecord($where, $addr_values)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
          return false;
        }
        if (sizeof($addr_values) > 0) {
          $addr_values = $addr_values[0];
        }
        else {
          // no active address - skip this address
          $skip = true;
        }
      }
      if (!$skip) {
        $address_array_new[] = $addr_values[dbKITcontactAddress::field_id];
        $street = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontactAddress::field_street.'_'.$addr_values[dbKITcontactAddress::field_id], $addr_values[dbKITcontactAddress::field_street]);
        $zip = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontactAddress::field_zip.'_'.$addr_values[dbKITcontactAddress::field_id], $addr_values[dbKITcontactAddress::field_zip]);
        $city = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontactAddress::field_city.'_'.$addr_values[dbKITcontactAddress::field_id], $addr_values[dbKITcontactAddress::field_city]);
        $additional = '';
        $select = '';
        foreach ($countries as $key => $name) {
          ($key == $addr_values[dbKITcontactAddress::field_country]) ? $selected = ' selected="selected"' : $selected = '';
          $select .= sprintf('<option value="%s"%s title="%s">%s</option>', $key, $selected, $name, $key);
        }
        $country = sprintf('<select name="%s">%s</select>', dbKITcontactAddress::field_country.'_'.$addr_values[dbKITcontactAddress::field_id], $select);
        $select = '';
        foreach ($dbContactAddress->type_array as $key => $name) {
          ($key == $addr_values[dbKITcontactAddress::field_type]) ? $selected = ' selected="selected"' : $selected = '';
          $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $name);
        }
        $addr_type = sprintf('<select name="%s">%s</select>', dbKITcontactAddress::field_type.'_'.$addr_values[dbKITcontactAddress::field_id], $select);
        $select = '';
        foreach ($dbContactAddress->status_array as $key => $name) {
          ($key == $addr_values[dbKITcontactAddress::field_status]) ? $selected = ' selected="selected"' : $selected = '';
          $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $name);
        }
        $addr_status = sprintf('<select name="%s">%s</select>', dbKITcontactAddress::field_status.'_'.$addr_values[dbKITcontactAddress::field_id], $select);
        if ($addr_values[dbKITcontactAddress::field_id] == $address_standard) {
          // Standard Adresse wird fuer die Karte verwendet
          $checked = ' checked="checked"';
          if (!empty($addr_values[dbKITcontactAddress::field_street])) {
            $map_address = sprintf('%s, %s %s', $addr_values[dbKITcontactAddress::field_street], $addr_values[dbKITcontactAddress::field_zip], $addr_values[dbKITcontactAddress::field_city]);
          }
        }
        else {
          $checked = '';
        }
        $addr_standard = sprintf('<input type="radio" name="%s" value="%s"%s />%s', dbKITcontact::field_address_standard, $addr_values[dbKITcontactAddress::field_id], $checked, kit_label_standard);

        $additional = $country.$addr_type.$addr_status.$addr_standard;

        $addr_array = array(
            'label_street' => kit_label_address_street,
            'value_street' => $street,
            'class_street' => dbKITcontactAddress::field_street,
            'label_zip_city' => kit_label_address_zip_city,
            'value_city' => $city,
            'class_city' => dbKITcontactAddress::field_city,
            'value_zip' => $zip,
            'class_zip' => dbKITcontactAddress::field_zip,
            'label_add' => '&nbsp;',
            'value_add' => $additional);
        $addresses .= $parser->get($addr_template, $addr_array);
      } // !$skip
    }
    $address_array = implode(',', $address_array_new);
    // add "insert new address" checkbox
    $add_address = sprintf('<input type="checkbox" name="add_address" value="1" />%s', kit_label_add_new_address);
    $data = array(
        'label_add_address' => ' ',
        'value_add_address' => $add_address);
    $addresses .= $parser->get($this->template_path.'backend.contact.address.add.htt', $data);

    // RIGHT COLUMN
    // birthday
    $timestamp = -1;
    if ($this->check_date($item[dbKITcontact::field_birthday], $timestamp)) {
      $datetime = new DateTime($item[dbKITcontact::field_birthday]);
      $date = $datetime->format(DATE_FORMAT);
    }
    else {
      $date = $item[dbKITcontact::field_birthday];
    }
    $birthday = sprintf('<input type="text" id="%s" name="%s" value="%s" />', 'datepicker', dbKITcontact::field_birthday, $date);
    // contact since
    $timestamp = -1;
    if ($this->check_date($item[dbKITcontact::field_contact_since], $timestamp)) {
      $datetime = new DateTime($item[dbKITcontact::field_contact_since]);
      $date = $datetime->format(DATE_FORMAT);
    }
    else {
      $date = $item[dbKITcontact::field_contact_since];
    }
    $contact_since = sprintf('<input type="text" id="%s" name="%s" value="%s" />', 'datepicker_2', dbKITcontact::field_contact_since, $date);
    // contact note
    if ($item[dbKITcontact::field_contact_note] > 0) {
      // get memo record
      $where = array();
      $where[dbKITmemos::field_id] = $item[dbKITcontact::field_contact_note];
      $memos = array();
      if (!$dbMemos->sqlSelectRecord($where, $memos)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
        return false;
      }
      $memo = $memos[0][dbKITmemos::field_memo];
    }
    else {
      $memo = '';
    }
    $contact_note = sprintf('<textarea name="%s">%s</textarea>', dbKITcontact::field_contact_note, $memo);
    // contact image
    $src = WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/img/no-contact-image.jpg';
    $size = @getimagesize($src);
    if ($size !== false) {
      list($width, $height) = $size;
      if ($height > 200) {
        $x = $height / 200;
        $width = (int) $width / $x;
        $height = (int) $height / $x;
      }
      elseif ($width > 200) {
        $x = width / 200;
        $width = (int) $width / $x;
        $height = (int) $height / $x;
      }
    }
    else {
      $width = 0;
      $height = 0;
    }
    $src = str_replace(WB_PATH, WB_URL, $src);
    $txt = 'undefined';
    $contact_image = sprintf('<img src="%s" alt="%s" title="%s" width="%s" height="%s" />', $src, $txt, $txt, $width, $height);

    // E-Mail Adressen
    $email = '';
    if (empty($item[dbKITcontact::field_email])) {
      $item[dbKITcontact::field_email] = '-1|-1';
    }
    $email_array = explode(';', $item[dbKITcontact::field_email]);
    // zusaetzliches E-Mail Feld anhaengen
    if (!in_array('-1|-1', $email_array))
      $email_array[] = '-1|-1';
    // E-mail eintraege festhalten
    $email_array_str = implode(';', $email_array);
    $i = -1;
    if ($item[dbKITcontact::field_email_standard] >= count($email_array) - 1) {
      $item[dbKITcontact::field_email_standard] = 0;
    }
    // Template fuer E-Mail Adressen
    $template_email = new Dwoo_Template_File($this->template_path.'backend.contact.email.htt');
    foreach ($email_array as $email_item) {
      $i++;
      list($email_type, $email_address) = explode('|', $email_item);
      if (isset($_REQUEST[dbKITcontact::field_email.'_'.$i]))
        $email_address = $_REQUEST[dbKITcontact::field_email.'_'.$i];
      if (isset($_REQUEST['email_type_'.$i]))
        $email_type = $_REQUEST['email_type_'.$i];
      if ($email_address == -1)
        $email_address = '';
      $select = '';
      foreach ($dbContact->email_array as $key => $name) {
        ($key == $email_type) ? $selected = ' selected="selected"' : $selected = '';
        $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $name);
      }
      $email_type = sprintf('<select name="%s">%s</select>', 'email_type_'.$i, $select);
      ($item[dbKITcontact::field_email_standard] == $i) ? $checked = ' checked="checked"' : $checked = '';
      $email_address = sprintf('<input type="text" name="%s" value="%s" /><input type="radio" name="%s" value="%s"%s />', dbKITcontact::field_email.'_'.$i, $email_address, dbKITcontact::field_email_standard, $i, $checked);
      $data = array(
          'label_email' => $email_type,
          'value_email' => $email_address);
      $email .= $parser->get($template_email, $data);
    }

    // Telefon

    $phone = '';
    if (empty($item[dbKITcontact::field_phone]))
      $item[dbKITcontact::field_phone] = '-1|-1';
    $phone_array = explode(';', $item[dbKITcontact::field_phone]);
    // zusaetzliches Telefon eintragen
    if (!in_array('-1|-1', $phone_array))
      $phone_array[] = '-1|-1';
    // Telefoneintraege festhalten
    $phone_array_str = implode(';', $phone_array);
    $i = -1;
    if ($item[dbKITcontact::field_phone_standard] >= count($phone_array) - 1) {
      $item[dbKITcontact::field_phone_standard] = 0;
    }
    // Template fuer Telefonnummern
    $template_phone = new Dwoo_Template_File($this->template_path.'backend.contact.phone.htt');
    foreach ($phone_array as $phone_item) {
      $i++;
      list($phone_type, $phone_number) = explode('|', $phone_item);
      if (isset($_REQUEST[dbKITcontact::field_phone.'_'.$i]))
        $phone_number = $_REQUEST[dbKITcontact::field_phone.'_'.$i];
      if (isset($_REQUEST['phone_type_'.$i]))
        $phone_type = $_REQUEST['phone_type_'.$i];
      if ($phone_number == -1)
        $phone_number = '';
      $select = '';
      foreach ($dbContact->phone_array as $key => $number) {
        ($key == $phone_type) ? $selected = ' selected="selected"' : $selected = '';
        $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $number);
      }
      $phone_type = sprintf('<select name="%s">%s</select>', 'phone_type_'.$i, $select);
      ($item[dbKITcontact::field_phone_standard] == $i) ? $checked = ' checked="checked"' : $checked = '';
      $phone_number = sprintf('<input type="text" id="%s" name="%s" value="%s" /><input type="radio" name="%s" value="%s"%s />', dbKITcontact::field_phone.'_'.$i, dbKITcontact::field_phone.'_'.$i, $phone_number, dbKITcontact::field_phone_standard, $i, $checked);
      $data = array(
          'label_phone' => $phone_type,
          'value_phone' => $phone_number);
      $phone .= $parser->get($template_phone, $data);
    }

    // Internet Verbindungen

    $internet = '';
    if (empty($item[dbKITcontact::field_internet]))
      $item[dbKITcontact::field_internet] = '-1|-1';
    $internet_array = explode(';', $item[dbKITcontact::field_internet]);
    // zusaetzliches Internet Feld
    if (!in_array('-1|-1', $internet_array))
      $internet_array[] = '-1|-1';
    // Interneteintraege festhalten
    $internet_array_str = implode(';', $internet_array);
    $i = -1;
    $template_internet = new Dwoo_Template_File($this->template_path.'backend.contact.internet.htt');
    foreach ($internet_array as $internet_item) {
      $i++;
      list($internet_type, $internet_address) = explode('|', $internet_item);
      if (isset($_REQUEST[dbKITcontact::field_internet.'_'.$i]))
        $internet_address = $_REQUEST[dbKITcontact::field_internet.'_'.$i];
      if (isset($_REQUEST['internet_type_'.$i]))
        $internet_type = $_REQUEST['internet_type_'.$i];
      if ($internet_address == -1)
        $internet_address = '';
      $select = '';
      foreach ($dbContact->internet_array as $key => $i_address) {
        ($key == $internet_type) ? $selected = ' selected="selected"' : $selected = '';
        $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $i_address);
      }
      $internet_type = sprintf('<select name="%s">%s</select>', 'internet_type_'.$i, $select);
      $internet_address = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontact::field_internet.'_'.$i, $internet_address);
      $data = array(
          'label_internet' => $internet_type,
          'value_internet' => $internet_address);
      $internet .= $parser->get($template_internet, $data);
    }


    // Karte anzeigen?

    $map_width = 240;
    $map_height = 240;
    $map_zoom = 16;
    $map_pos = ''; // 'l' = float:left - 'r' = float:right
    if (!empty($map_address)) {
      $map = $tools->showOSMmap($item[dbKITcontact::field_contact_identifier], $map_address, $map_zoom, $map_width, $map_height, $map_pos);
    }
    else {
      switch ($map_pos) :
        case 'l':
          $pos = 'float:left;';
          break;
        case 'r':
          $pos = 'float:right;';
          break;
        default:
          $pos = '';
      endswitch;
      $map = sprintf('<div id="map" style="width:%dpx;height:%dpx; %s"></div>', $map_width, $map_height, $pos);
    }

    // Kategorien

    $categories = '';
    $categories_array = explode(',', $item[dbKITcontact::field_category]);
    $categories_td = '';
    $categories_tr = '';
    $i = 0;
    // Template fuer die Kategorien
    $template_category_td = new Dwoo_Template_File($this->template_path.'backend.contact.categories.td.htt');
    $template_category_tr = new Dwoo_Template_File($this->template_path.'backend.contact.categories.tr.htt');
    foreach ($dbContact->category_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        //$categories_tr .= sprintf('<tr>%s</tr>', $categories_td);
        $data = array('category_td' => $categories_td);
        $categories_tr .= $parser->get($template_category_tr, $data);
        $categories_td = '';
      }
      (in_array($key, $categories_array)) ? $checked = ' checked="checked"' : $checked = '';
      $cat = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_category, $key, $checked, $value);
      $data = array('category_item' => $cat);
      $categories_td .= $parser->get($template_category_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('category_item' => '&nbsp;');
      $categories_td .= $parser->get($template_category_td, $data);

      //$categories_td .= '<td>&nbsp;</td>';
    }
    $data = array('category_td' => $categories_td);
    $categories_tr .= $parser->get($template_category_tr, $data);
    $data = array('rows' => $categories_tr);
    $categories = $parser->get($this->template_path.'backend.contact.categories.htt', $data);


    // Newsletter

    $newsletter = '';
    $newsletter_array = explode(',', $item[dbKITcontact::field_newsletter]);
    $newsletter_td = '';
    $newsletter_tr = '';
    $i = 0;
    // Template fuer die Newsletter
    $template_newsletter_td = new Dwoo_Template_File($this->template_path.'backend.contact.newsletter.td.htt');
    $template_newsletter_tr = new Dwoo_Template_File($this->template_path.'backend.contact.newsletter.tr.htt');
    foreach ($dbContact->newsletter_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        $data = array('newsletter_td' => $newsletter_td);
        $newsletter_tr .= $parser->get($template_newsletter_tr, $data);
        $newsletter_td = '';
      }
      (in_array($key, $newsletter_array)) ? $checked = ' checked="checked"' : $checked = '';
      $news = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_newsletter, $key, $checked, $value);
      $data = array('newsletter_item' => $news);
      $newsletter_td .= $parser->get($template_newsletter_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('newsletter_item' => '&nbsp;');
      $newsletter_td .= $parser->get($template_newsletter_td, $data);
    }
    $data = array('newsletter_td' => $newsletter_td);
    $newsletter_tr .= $parser->get($template_newsletter_tr, $data);
    $data = array('rows' => $newsletter_tr);
    $newsletter = $parser->get($this->template_path.'backend.contact.newsletter.htt', $data);


    // Verteiler

    $distribution = '';
    $distribution_array = explode(',', $item[dbKITcontact::field_distribution]);
    $distribution_td = '';
    $distribution_tr = '';
    $i = 0;
    // Template fuer die Newsletter
    $template_distribution_td = new Dwoo_Template_File($this->template_path.'backend.contact.distribution.td.htt');
    $template_distribution_tr = new Dwoo_Template_File($this->template_path.'backend.contact.distribution.tr.htt');
    foreach ($dbContact->distribution_array as $key => $value) {
      $i++;
      if ($i > 5) {
        $i = 1;
        $data = array('distribution_td' => $distribution_td);
        $distribution_tr .= $parser->get($template_distribution_tr, $data);
        $distribution_td = '';
      }
      (in_array($key, $distribution_array)) ? $checked = ' checked="checked"' : $checked = '';
      $news = sprintf('<input type="checkbox" name="%s[]" value="%s"%s />%s', dbKITcontact::field_distribution, $key, $checked, $value);
      $data = array('distribution_item' => $news);
      $distribution_td .= $parser->get($template_distribution_td, $data);
    }
    for ($u = $i; $u < 6; $u++) {
      $data = array('distribution_item' => '&nbsp;');
      $distribution_td .= $parser->get($template_distribution_td, $data);
    }
    $data = array('distribution_td' => $distribution_td);
    $distribution_tr .= $parser->get($template_distribution_tr, $data);
    $data = array('rows' => $distribution_tr);
    $distribution = $parser->get($this->template_path.'backend.contact.distribution.htt', $data);


    // Protokoll

    $protocol = '';
    // Protokoll abfragen
    $where = array();
    $where[dbKITprotocol::field_contact_id] = $item[dbKITcontact::field_id];
    $where[dbKITprotocol::field_status] = dbKITprotocol::status_active;
    $protocol_data = array();
    $order = array(dbKITprotocol::field_date);
    if (!$dbProtocol->sqlSelectRecordOrderBy($where, $protocol_data, $order, false)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProtocol->getError()));
      return false;
    }
    $rows = '';
    $flipflop = true;
    $template_row = new Dwoo_Template_File($this->template_path.'backend.contact.protocol.list.tr.htt');
    foreach ($protocol_data as $pdata) {
      ($flipflop) ? $flipflop = false : $flipflop = true;
      ($flipflop) ? $class = 'flip' : $class = 'flop';
      $data = array(
          'class' => $class,
          'date' => date(DATE_FORMAT, strtotime($pdata[dbKITprotocol::field_date])),
          'type' => $dbProtocol->type_array[$pdata[dbKITprotocol::field_type]],
          'memo' => $pdata[dbKITprotocol::field_memo],
          'members' => $pdata[dbKITprotocol::field_members]);
      $rows .= $parser->get($template_row, $data);
    }
    $data = array(
        'header_date' => kit_label_protocol_date,
        'header_type' => kit_label_protocol_type,
        'header_memo' => kit_label_protocol_memo,
        'header_members' => kit_label_protocol_members,
        'rows' => $rows);
    $prodata = $parser->get($this->template_path.'backend.contact.protocol.list.htt', $data);
    $protocol_date = sprintf('<input type="text" id="%s" name="%s" value="%s" />', 'datepicker_3', dbKITprotocol::field_date, $date);

    $select = '';
    foreach ($dbProtocol->type_array as $key => $value) {
      $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
    }
    $protocol_type = sprintf('%s<select name="%s">%s</select>', kit_label_protocol_type, dbKITprotocol::field_type, $select);
    $protocol_members = sprintf('<textarea class="%s" name="%s">%s</textarea>', dbKITprotocol::field_members, dbKITprotocol::field_members, '');
    $protocol_memo = sprintf('<textarea class="%s" name="%s">%s</textarea>', dbKITprotocol::field_memo, dbKITprotocol::field_memo, '');

    $data = array(
        'date' => $protocol_date,
        'type' => $protocol_type,
        'label_members' => kit_label_protocol_members,
        'members' => $protocol_members,
        'memo' => $protocol_memo,
        'protocol' => $prodata);
    $protocol = $parser->get($this->template_path.'backend.contact.protocol.dlg.htt', $data);
    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_contact);
    }

    // Administration
    $where = array(dbKITregister::field_contact_id => $id);
    $register = array();
    if (!$dbRegister->sqlSelectRecord($where, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) > 0) {
      // step through register
      $register = $register[0];
      $administration = array(
          'active' => 1,
          'title' => kit_label_admin,
          'register' => array(
              'date' => array(
                  'label' => kit_label_register_date,
                  'value' => date(CFG_DATETIME_STR, strtotime($register[dbKITregister::field_register_date])),
                  'date' => $register[dbKITregister::field_register_date]),
              'confirmed' => array(
                  'label' => kit_label_register_confirmed,
                  'value' => date(CFG_DATETIME_STR, strtotime($register[dbKITregister::field_register_confirmed])),
                  'date' => $register[dbKITregister::field_register_confirmed]),
              'key' => array(
                  'label' => kit_label_register_key,
                  'value' => $register[dbKITregister::field_register_key]),
              'status' => array(
                  'label' => kit_label_register_status,
                  'name' => dbKITregister::field_status,
                  'value' => $register[dbKITregister::field_status],
                  'options' => $dbRegister->status_dwoo_array),
              'login' => array(
                  'errors' => array(
                      'label' => kit_label_register_login_errors,
                      'value' => (int) $register[dbKITregister::field_login_failures]),
                  'locked' => array(
                      'label' => kit_label_register_login_locked,
                      'name' => dbKITregister::field_login_locked,
                      'value' => (int) $register[dbKITregister::field_login_locked],)),
              'password' => array(
                  'change' => array(
                      'password_1' => array(
                          'label' => kit_label_register_password_1,
                          'name' => dbKITregister::field_password.'_1',),
                      'password_2' => array(
                          'label' => kit_label_register_password_2,
                          'name' => dbKITregister::field_password.'_2'),))),);
    }
    else {
      $administration = array('active' => 0);
    }

    // additional fields
    $show_additional_fields = false;
    $additional_fields = $dbCfg->getValue(dbKITcfg::cfgAdditionalFields);
    if (isset($additional_fields[0]) && !empty($additional_fields[0]))
      $show_additional_fields = true;
    $additional_notes = $dbCfg->getValue(dbKITcfg::cfgAdditionalNotes);
    if (isset($additional_notes[0]) && !empty($additional_notes[0]))
      $show_additional_fields = true;

    if ($show_additional_fields) {
      $fields = array();
      foreach ($additional_fields as $field_data) {
        if (empty($field_data))
          continue;
        if (false === (strpos($field_data, '|')))
          continue;
        list($fid, $label) = explode('|', $field_data);
        $fid = (int) $fid;
        if (!isset($item[sprintf('contact_free_field_%d', $fid)]))
          continue;
        $i = $item[sprintf('contact_free_field_%d', $fid)];
        $SQL = sprintf("SELECT %s FROM %s WHERE %s='%s' AND %s='%s'", dbKITmemos::field_memo, $dbMemos->getTableName(), dbKITmemos::field_id, $i, dbKITmemos::field_status, dbKITmemos::status_active);
        $memo = array();
        if (!$dbMemos->sqlExec($SQL, $memo)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
          return false;
        }
        $value = (count($memo) > 0) ? $memo[0][dbKITmemos::field_memo] : '';
        $fields[] = array(
            'label' => trim($label),
            'value' => $value,
            'name' => sprintf('contact_free_field_%d', $fid));
      }
      $notes = array();
      foreach ($additional_notes as $note_data) {
        if (empty($note_data))
          continue;
        if (false === (strpos($note_data, '|')))
          continue;
        list($fid, $label) = explode('|', $note_data);
        $fid = (int) $fid;
        if (!isset($item[sprintf('contact_free_note_%d', $fid)]))
          continue;
        $i = $item[sprintf('contact_free_note_%d', $fid)];
        $SQL = sprintf("SELECT %s FROM %s WHERE %s='%s' AND %s='%s'", dbKITmemos::field_memo, $dbMemos->getTableName(), dbKITmemos::field_id, $i, dbKITmemos::field_status, dbKITmemos::status_active);
        $memo = array();
        if (!$dbMemos->sqlExec($SQL, $memo)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
          return false;
        }
        $value = (count($memo) > 0) ? $memo[0][dbKITmemos::field_memo] : '';
        $notes[] = array(
            'label' => trim($label),
            'value' => $value,
            'name' => sprintf('contact_free_note_%d', $fid));
      }

      $additional = array(
          'active' => 1,
          'title' => kit_label_additional_fields,
          'fields' => $fields,
          'notes' => $notes);
    }
    else {
      $additional = array('active' => 0);
    }
    $data = array(
        'form_name' => $form_name,
        'form_action' => $this->page_link,
        'action_name' => self::request_action,
        'action_value' => self::action_contact_save,
        'language' => (LANGUAGE == 'EN') ? '' : strtolower(LANGUAGE),
        'btn_ok' => kit_btn_ok,
        'btn_abort' => kit_btn_abort,
        'abort_location' => $this->page_link,
        'id_name' => dbKITcontact::field_id,
        'id_value' => $id,
        'intro' => $intro,
        'header_contact' => kit_header_contact,
        'header_addresses' => kit_header_addresses,
        'header_communication' => kit_header_communication,
        'header_categories' => kit_header_categories,
        'header_protocol' => kit_header_protocol,
        'header_help' => kit_header_help_documentation,
        'address_array_name' => 'address_array',
        'address_array_value' => $address_array,
        'email_array_name' => 'email_array',
        'email_array_value' => $email_array_str,
        'phone_array_name' => 'phone_array',
        'phone_array_value' => $phone_array_str,
        'internet_array_name' => 'internet_array',
        'internet_array_value' => $internet_array_str,
        // linke Seite
        'label_id' => kit_label_kit_id,
        'class_id' => dbKITcontact::field_id,
        'value_id' => $value_id,
        'label_type' => kit_label_contact_type,
        'value_type' => $type,
        'class_type' => dbKITcontact::field_type,
        'label_access' => kit_label_contact_access,
        'value_access' => $access,
        'class_access' => dbKITcontact::field_access,
    		'label_language' => kit_label_contact_language,
    		'value_language' => $contact_languages,
    		'class_language' => dbKITcontact::field_contact_language,
        'label_status' => kit_label_contact_status,
        'value_status' => $status,
        'class_status' => dbKITcontact::field_status,
        'label_identifier' => kit_label_contact_identifier,
        'value_identifier' => $identifier,
        'class_identifier' => dbKITcontact::field_internet,
        'label_company_title' => kit_label_company_title,
        'value_company_title' => $company_title,
        'class_company_title' => dbKITcontact::field_company_title,
        'label_company_name' => kit_label_company_name,
        'value_company_name' => $company_name,
        'class_company_name' => dbKITcontact::field_company_name,
        'label_company_department' => kit_label_company_department,
        'value_company_department' => $company_department,
        'class_company_department' => dbKITcontact::field_company_department,
        'label_company_additional' => kit_label_company_additional,
        'value_company_additional' => $company_additional,
        'class_company_additional' => dbKITcontact::field_company_additional,
        'label_person_title' => kit_label_person_title,
        'value_person_title' => $person_title,
        'class_person_title' => dbKITcontact::field_person_title,
        'label_person_title_academic' => kit_label_person_title_academic,
        'value_person_title_academic' => $person_title_academic,
        'class_person_title_academic' => dbKITcontact::field_person_title_academic,
        'label_person_first_name' => kit_label_person_first_name,
        'value_person_first_name' => $person_first_name,
        'class_person_first_name' => dbKITcontact::field_person_first_name,
        'label_person_last_name' => kit_label_person_last_name,
        'value_person_last_name' => $person_last_name,
        'class_person_last_name' => dbKITcontact::field_person_last_name,
        'label_person_function' => kit_label_person_function,
        'value_person_function' => $person_function,
        'class_person_function' => dbKITcontact::field_person_function,
        'addresses' => $addresses,
        // rechte Seite
        'label_birthday' => kit_label_birthday,
        'value_birthday' => $birthday,
        'class_birthday' => dbKITcontact::field_birthday,
        'label_contact_since' => kit_label_contact_since,
        'value_contact_since' => $contact_since,
        'class_contact_since' => dbKITcontact::field_contact_since,
        'label_contact_note' => kit_label_contact_note,
        'value_contact_note' => $contact_note,
        'class_contact_note' => dbKITcontact::field_contact_note,
        'label_image' => kit_label_image,
        'value_image' => $contact_image,
        'label_map' => kit_label_map,
        'value_map' => $map,
        'class_image' => dbKITcontact::field_picture_id,
        'email' => $email,
        'phone' => $phone,
        'internet' => $internet,
        // Kategorien
        'label_categories' => kit_label_categories,
        'value_categories' => $categories,
        // Newsletter
        'label_newsletter' => kit_label_newsletter,
        'value_newsletter' => $newsletter,
        // Verteiler
        'label_distribution' => kit_label_distribution,
        'value_distribution' => $distribution,
        // Protokoll
        'label_protocol' => kit_label_protocol,
        'value_protocol' => $protocol,
        // Administration
        'administration' => $administration,
        // additional fields & notes
        'additional_fields' => $additional);
    return $parser->get($this->template_path.'backend.contact.htt', $data);
  } // dlgContact()


  private function getKeyIndexOfValue($array = array(), $item) {
    foreach ($array as $key => $value) {
      if ($value == $item) {
        return $key;
        break;
      }
    }
    return -1;
  }

  private function request2contact() {
    global $dbContact;
    $message = $this->getMessage();
    $default = array(
        dbKITcontact::field_id => -1,
        dbKITcontact::field_type => dbKITcontact::type_person,
        dbKITcontact::field_access => dbKITcontact::access_internal,
        dbKITcontact::field_status => dbKITcontact::status_active,
        dbKITcontact::field_contact_identifier => '',
    		dbKITcontact::field_contact_language => '',
        dbKITcontact::field_company_title => '',
        dbKITcontact::field_company_name => '',
        dbKITcontact::field_company_department => '',
        dbKITcontact::field_company_additional => '',
        dbKITcontact::field_person_title => $this->getKeyIndexOfValue($dbContact->person_title_array, kit_contact_person_title_mister),
        dbKITcontact::field_person_title_academic => $this->getKeyIndexOfValue($dbContact->person_title_academic_array, kit_contact_person_title_academic_none),
        dbKITcontact::field_person_first_name => '',
        dbKITcontact::field_person_last_name => '',
        dbKITcontact::field_person_function => '',
        dbKITcontact::field_address => -1,
        dbKITcontact::field_address_standard => -1,
        dbKITcontact::field_category => '',
        dbKITcontact::field_newsletter => '',
        dbKITcontact::field_distribution => '',
        dbKITcontact::field_internet => '',
        dbKITcontact::field_phone => '',
        dbKITcontact::field_phone_standard => -1,
        dbKITcontact::field_email => '',
        dbKITcontact::field_email_standard => -1,
        dbKITcontact::field_birthday => '',
        dbKITcontact::field_contact_since => '',
        dbKITcontact::field_contact_note => '',
        // will be checked later
        dbKITcontact::field_free_1 => '',
        dbKITcontact::field_free_2 => '',
        dbKITcontact::field_free_3 => '',
        dbKITcontact::field_free_4 => '',
        dbKITcontact::field_free_5 => '',
        dbKITcontact::field_free_note_1 => -1,
        dbKITcontact::field_free_note_2 => -1,
        dbKITcontact::field_picture_id => -1,
        dbKITcontact::field_update_by => 'SYSTEM',
        dbKITcontact::field_update_when => '0000-00-00 00:00:00');
    foreach ($default as $key => $value) {
      $this->contact_array[$key] = $value;
      if (isset($_REQUEST[$key])) {
        switch ($key) :
          case dbKITcontact::field_category:
            $this->contact_array[$key] = implode(',', $_REQUEST[dbKITcontact::field_category]);
            break;
          case dbKITcontact::field_newsletter:
            $this->contact_array[$key] = implode(',', $_REQUEST[dbKITcontact::field_newsletter]);
            break;
          case dbKITcontact::field_distribution:
            $this->contact_array[$key] = implode(',', $_REQUEST[dbKITcontact::field_distribution]);
            break;
          default:
            $this->contact_array[$key] = $_REQUEST[$key];
        endswitch;
      }
    }
    if (empty($message)) {
      return true;
    }
    else {
      $this->setMessage($message);
      return false;
    }
  } // request2contact()

  private function check_date($date_str, &$timestamp) {
    $stamp = strtotime($date_str);
    if (!is_numeric($stamp)) {
      return false;
    }
    $month = date('m', $stamp);
    $day = date('d', $stamp);
    $year = date('Y', $stamp);
    if (checkdate($month, $day, $year)) {
      $timestamp = mktime(date('H', $stamp), date('i', $stamp), date('s'), $month, $day, $year);
      return true;
    }
    return false;
  } // check_date()

  private function checkContact() {
    global $dbContact;
    global $dbMemos;
    global $tools;
    global $dbProtocol;
    global $dbContactAddress;
    global $dbRegister;
    global $dbWBusers;

    $message = $this->getMessage();
    if ($this->contact_array[dbKITcontact::field_id] != -1) {
      // existierenden Datensatz auslesen
      $where = array();
      $where[dbKITcontact::field_id] = $this->contact_array[dbKITcontact::field_id];
      $old_data = array();
      if (!$dbContact->sqlSelectRecord($where, $old_data)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      $old_data = $old_data[0];
    }
    else {
      $old_data[dbKITcontact::field_contact_note] = -1;
    }
    // check dates
    if (!empty($this->contact_array[dbKITcontact::field_birthday]) || !empty($this->contact_array[dbKITcontact::field_contact_since])) {
      // if dates are valid, store them in MySQL format, otherwise ignore
      $timestamp = 0;
      if ($this->check_date($this->contact_array[dbKITcontact::field_birthday], $timestamp)) {
        $this->contact_array[dbKITcontact::field_birthday] = date('Y-m-d H:i:s', $timestamp);
      }
      if ($this->check_date($this->contact_array[dbKITcontact::field_birthday], $timestamp)) {
        $this->contact_array[dbKITcontact::field_birthday] = date('Y-m-d H:i:s', $timestamp);
      }
    }
    // check contact note
    if ($old_data[dbKITcontact::field_contact_note] > 0) {
      // Datensatz existiert bereits, aktualisieren
      $where = array();
      $where[dbKITmemos::field_id] = $old_data[dbKITcontact::field_contact_note];
      $memo = array();
      if (!$dbMemos->sqlSelectRecord($where, $memo)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
        return false;
      }
      $memo = $memo[0];
      $changed = false;
      // was the memo locked or deleted?
      if ($memo[dbKITmemos::field_status] != dbKITmemos::status_active)
        $changed = true;
      // was the memo changed?
      if (utf8_decode($memo[dbKITmemos::field_memo]) != $this->contact_array[dbKITcontact::field_contact_note])
        $changed = true;
      if ($changed) {
        $data = array();
        $data[dbKITmemos::field_contact_id] = $this->contact_array[dbKITcontact::field_id];
        $data[dbKITmemos::field_memo] = $this->contact_array[dbKITcontact::field_contact_note];
        $data[dbKITmemos::field_status] = dbKITmemos::status_active;
        $data[dbKITmemos::field_update_by] = $tools->getDisplayName();
        $data[dbKITmemos::field_update_when] = date('Y-m-d H:i:s');
        if (!$dbMemos->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
          return false;
        }
      }
      // change entry in record
      $this->contact_array[dbKITcontact::field_contact_note] = $old_data[dbKITcontact::field_contact_note];
    }
    elseif (!empty($this->contact_array[dbKITcontact::field_contact_note])) {
      // new record and note is not empty
      $memo_id = -1;
      $data = array();
      $data[dbKITmemos::field_contact_id] = -1;
      $data[dbKITmemos::field_memo] = $this->contact_array[dbKITcontact::field_contact_note];
      $data[dbKITmemos::field_status] = dbKITmemos::status_active;
      $data[dbKITmemos::field_update_by] = $tools->getDisplayName();
      $data[dbKITmemos::field_update_when] = date('Y-m-d H:i:s');
      if (!$dbMemos->sqlInsertRecord($data, $memo_id)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
        return false;
      }
      $this->contact_array[dbKITcontact::field_contact_note] = $memo_id;
    }
    // check addresses
    if (isset($_REQUEST['address_array'])) {
      $address_array = explode(',', $_REQUEST['address_array']);
      $new_address_array = array();
      foreach ($address_array as $addr_id) {
        $street_ok = false;
        $zip_ok = false;
        $city_ok = false;
        if ((isset($_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id])) && (!empty($_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id])))
          $street_ok = true;
        if ((isset($_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id])) && (!empty($_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id])))
          $zip_ok = true;
        if ((isset($_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id])) && (!empty($_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id])))
          $city_ok = true;
        if (($street_ok && $zip_ok && $city_ok) || ($street_ok && $city_ok) || ($city_ok)) {
          // allowed combination, save address
          if ($addr_id == 0) {
            // new address record
            $data = array();
            $data[dbKITcontactAddress::field_street] = $_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_zip] = $_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_city] = $_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_country] = $_REQUEST[dbKITcontactAddress::field_country.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_country.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_type] = $_REQUEST[dbKITcontactAddress::field_type.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_type.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_status] = $_REQUEST[dbKITcontactAddress::field_status.'_'.$addr_id];
            unset($_REQUEST[dbKITcontactAddress::field_status.'_'.$addr_id]);
            $data[dbKITcontactAddress::field_contact_id] = $_REQUEST[dbKITcontact::field_id];
            $data[dbKITcontactAddress::field_update_by] = $tools->getDisplayName();
            $data[dbKITcontactAddress::field_update_when] = date('Y-m-d H:i:s');
            $id = -1;
            if (!$dbContactAddress->sqlInsertRecord($data, $id)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
            }
            $message .= kit_msg_address_insert;
            $new_address_array[] = $id;
          }
          else {
            // update record?
            $where = array();
            $where[dbKITcontactAddress::field_id] = $addr_id;
            $addr_old = array();
            if (!$dbContactAddress->sqlSelectRecord($where, $addr_old)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
              return false;
            }
            $addr_old = $addr_old[0];
            if (($addr_old[dbKITcontactAddress::field_street] != $_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id]) || ($addr_old[dbKITcontactAddress::field_zip] != $_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id]) || ($addr_old[dbKITcontactAddress::field_city] != $_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id]) || ($addr_old[dbKITcontactAddress::field_country] != $_REQUEST[dbKITcontactAddress::field_country.'_'.$addr_id]) || ($addr_old[dbKITcontactAddress::field_type] != $_REQUEST[dbKITcontactAddress::field_type.'_'.$addr_id]) || ($addr_old[dbKITcontactAddress::field_status] != $_REQUEST[dbKITcontactAddress::field_status.'_'.$addr_id])) {
              // one ore more entries changed...
              $data = array();
              $data[dbKITcontactAddress::field_street] = $_REQUEST[dbKITcontactAddress::field_street.'_'.$addr_id];
              $data[dbKITcontactAddress::field_zip] = $_REQUEST[dbKITcontactAddress::field_zip.'_'.$addr_id];
              $data[dbKITcontactAddress::field_city] = $_REQUEST[dbKITcontactAddress::field_city.'_'.$addr_id];
              $data[dbKITcontactAddress::field_country] = $_REQUEST[dbKITcontactAddress::field_country.'_'.$addr_id];
              $data[dbKITcontactAddress::field_type] = $_REQUEST[dbKITcontactAddress::field_type.'_'.$addr_id];
              $data[dbKITcontactAddress::field_status] = $_REQUEST[dbKITcontactAddress::field_status.'_'.$addr_id];
              $data[dbKITcontactAddress::field_contact_id] = $_REQUEST[dbKITcontact::field_id];
              $data[dbKITcontactAddress::field_update_by] = $tools->getDisplayName();
              $data[dbKITcontactAddress::field_update_when] = date('Y-m-d H:i:s');
              if (!$dbContactAddress->sqlUpdateRecord($data, $where)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
                return false;
              }
              if (isset($_REQUEST[dbKITcontactAddress::field_status]) && $_REQUEST[dbKITcontactAddress::field_status] == dbKITcontactAddress::status_deleted) {
                // record deleted
                $message .= sprintf(kit_msg_address_deleted, $_REQUEST[dbKITcontact::field_id]);
              }
              else {
                // record updated
                $message .= sprintf(kit_msg_address_update, $_REQUEST[dbKITcontact::field_id]);
                $new_address_array[] = $addr_id;
              }

            }
            else {
              // nothing to do...
              $new_address_array[] = $addr_id;
            }
          }
        }
        elseif ($addr_id != 0) {
          $message .= kit_msg_address_invalid;
          $new_address_array[] = $addr_id;
        }
      }
      // enshure update of address array
      $_REQUEST['address_array'] = implode(',', $new_address_array);
      $this->contact_array[dbKITcontact::field_address] = $_REQUEST['address_array'];
      if (!in_array($this->contact_array[dbKITcontact::field_address_standard], $new_address_array)) {
        if (sizeof($new_address_array) > 0) {
          $this->contact_array[dbKITcontact::field_address_standard] = $new_address_array[0];
        }
        else {
          $this->contact_array[dbKITcontact::field_address_standard] = -1;
        }
      }
    } // address_array

    /*
     * check email addresses
     */
    if (isset($_REQUEST['email_array'])) {
      $email_array = explode(';', $_REQUEST['email_array']);
      $max_email = count($email_array);
      $new_email_array = array();
      for ($i = 0; $i < $max_email; $i++) {
        list($email_type, $email_address) = explode('|', $email_array[$i]);
        if ($email_address == -1)
          $email_address = '';
        if (isset($_REQUEST[dbKITcontact::field_email.'_'.$i]) && !empty($_REQUEST[dbKITcontact::field_email.'_'.$i])) {
          if ($tools->validateEMail($_REQUEST[dbKITcontact::field_email.'_'.$i])) {
            // E-Mail adresse ist in ordnung
            $new_email_array[] = sprintf('%s|%s', $_REQUEST['email_type_'.$i], strtolower($_REQUEST[dbKITcontact::field_email.'_'.$i]));
            if (empty($email_address)) {
              // neue E-Mail Adresse
              $message .= sprintf(kit_msg_email_added, $_REQUEST[dbKITcontact::field_email.'_'.$i]);
            }
            elseif (strtolower($_REQUEST[dbKITcontact::field_email.'_'.$i]) != $email_address) {
              // E-Mail Adresse geaendert
              $message .= sprintf(kit_msg_email_changed, $email_address, $_REQUEST[dbKITcontact::field_email.'_'.$i]);
            }
            elseif ($_REQUEST['email_type_'.$i] != $email_type) {
              // E-Mail Typ geaendert
              $message .= sprintf(kit_msg_email_type_changed, $email_address);
            }
            else {
              // no change
            }
            unset($_REQUEST[dbKITcontact::field_email.'_'.$i]);
            unset($_REQUEST['email_type_'.$i]);
          }
          else {
            // ungueltige email adresse
            $message .= sprintf(kit_msg_email_invalid, $_REQUEST[dbKITcontact::field_email.'_'.$i]);
          }
        }
        elseif (empty($_REQUEST[dbKITcontact::field_email.'_'.$i]) && !empty($email_address)) {
          // email adresse geloescht
          $message .= sprintf(kit_msg_email_deleted, $email_address);
        }
      }
      $this->contact_array[dbKITcontact::field_email] = implode(';', $new_email_array);
      $_REQUEST['email_array'] = $this->contact_array[dbKITcontact::field_email];
    } // check email addresses

    /*
     * check phone numbers
     */
    if (isset($_REQUEST['phone_array'])) {
      $phone_array = explode(';', $_REQUEST['phone_array']);
      $max_phones = count($phone_array);
      $new_phone_array = array();
      for ($i = 0; $i < $max_phones; $i++) {
        list($phone_type, $phone_number) = explode('|', $phone_array[$i]);
        if ($phone_number == -1)
          $phone_number = '';
        if (isset($_REQUEST[dbKITcontact::field_phone.'_'.$i]) && !empty($_REQUEST[dbKITcontact::field_phone.'_'.$i])) {
          $phone = $_REQUEST[dbKITcontact::field_phone.'_'.$i];
          if ($tools->checkPhone($phone)) {
            $new_phone_array[] = sprintf('%s|%s', $_REQUEST['phone_type_'.$i], $phone);
            if (empty($phone_number)) {
              // neue Telefonnummer
              $message .= sprintf(kit_msg_phone_added, $phone);
            }
            elseif ($phone != $phone_number) {
              // Telefonnummer geaendert
              $message .= sprintf(kit_msg_phone_changed, $phone_number, $phone);
            }
            elseif ($_REQUEST['phone_type_'.$i] != $phone_type) {
              $message .= sprintf(kit_msg_phone_type_changed, $phone);
            }
            else {
              // no change
            }
            unset($_REQUEST[dbKITcontact::field_phone.'_'.$i]);
            unset($_REQUEST['phone_type_'.$i]);
          }
          else {
            // Telefonnummer ist ungueltig
            $message .= sprintf(kit_msg_phone_invalid, $phone);
          }
        }
        elseif (empty($_REQUEST[dbKITcontact::field_phone.'_'.$i]) && !empty($phone_number)) {
          // Telefonnummer geloescht
          $message .= sprintf(kit_msg_phone_deleted, $phone_number);
        }
      }
      $this->contact_array[dbKITcontact::field_phone] = implode(';', $new_phone_array);
      $_REQUEST['phone_array'] = $this->contact_array[dbKITcontact::field_phone];
    } // check phone numbers

    /*
     * check internet addresses
     */
    if (isset($_REQUEST['internet_array'])) {
      $internet_array = explode(';', $_REQUEST['internet_array']);
      $max_internet_addresses = count($internet_array);
      $new_internet_array = array();
      for ($i = 0; $i < $max_internet_addresses; $i++) {
        list($internet_type, $internet_address) = explode('|', $internet_array[$i]);
        if ($internet_address == -1)
          $internet_address = '';
        if (isset($_REQUEST[dbKITcontact::field_internet.'_'.$i]) && !empty($_REQUEST[dbKITcontact::field_internet.'_'.$i])) {
          $internet = $_REQUEST[dbKITcontact::field_internet.'_'.$i];
          if ($tools->checkLink($internet)) {
            // internet adresse ist in ordnung
            $new_internet_array[] = sprintf('%s|%s', $_REQUEST['internet_type_'.$i], $internet);
            if (empty($internet_address)) {
              // neue internet adresse
              $message .= sprintf(kit_msg_internet_added, $internet);
            }
            elseif ($internet != $internet_address) {
              // Internetadresse geaendert
              $message .= sprintf(kit_msg_internet_changed, $internet_address, $internet);
            }
            elseif ($_REQUEST['internet_type_'.$i] != $internet_type) {
              // Internettyp geaendert
              $message .= sprintf(kit_msg_internet_type_changed, $internet);
            }
            else {
              // no change
            }
            unset($_REQUEST[dbKITcontact::field_internet.'_'.$i]);
            unset($_REQUEST['internet_type_'.$i]);
          }
          else {
            // Internetadresse ist ungueltig
            $message .= sprintf(kit_msg_internet_invalid, $internet);
          }
        }
        elseif (empty($_REQUEST[dbKITcontact::field_internet.'_'.$i]) && !empty($internet_address)) {
          // Internet Adresse geloescht
          $message .= sprintf(kit_msg_internet_deleted, $internet_address);
        }
      } // for
      $this->contact_array[dbKITcontact::field_internet] = implode(';', $new_internet_array);
      $_REQUEST['internet_array'] = $this->contact_array[dbKITcontact::field_internet];
    } // check internet addresses

    /*
     * Check Categories
     * - nothing to do ... -
     */
    if (!empty($old_data[dbKITcontact::field_category])) {
      $new_cats = explode(',', $this->contact_array[dbKITcontact::field_category]);
      $categories = explode(',', $old_data[dbKITcontact::field_category]);
      if (!in_array(dbKITcontact::category_wb_user, $new_cats) && in_array(dbKITcontact::category_wb_user, $categories)) {
        // Kontakt wurde aus der WB User Gruppe entfernt...
        $emails = explode(',', $this->contact_array[dbKITcontact::field_email]);
        list($type, $email) = explode('|', $emails[$this->contact_array[dbKITcontact::field_email_standard]]);
        $where = array(dbWBusers::field_email => $email);
        $data = array(dbWBusers::field_active => dbWBusers::status_inactive);
        if (!$dbWBusers->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
          return false;
        }
      }

    }

    /**
     * Check Identifier
     */
    if (empty($_REQUEST[dbKITcontact::field_contact_identifier])) {
      // es sollte ein Identifier gesetzt sein
      if (!empty($this->contact_array[dbKITcontact::field_company_name]) && !empty($this->contact_array[dbKITcontact::field_person_last_name])) {
        // Firmenname + Nachname
        $this->contact_array[dbKITcontact::field_contact_identifier] = sprintf('%s, %s %s', $this->contact_array[dbKITcontact::field_company_name], $dbKITcontact->person_title_array[$this->contact_array[dbKITcontact::field_person_title]], $this->contact_array[dbKITcontact::field_person_last_name]);
      }
      elseif (!empty($this->contact_array[dbKITcontact::field_company_name])) {
        // nur Firmenname
        if (!empty($this->contact_array[dbKITcontact::field_company_department])) {
          $this->contact_array[dbKITcontact::field_contact_identifier] = sprintf('%s, %s', $this->contact_array[dbKITcontact::field_company_name], $this->contact_array[dbKITcontact::field_company_department]);
        }
        else {
          $this->contact_array[dbKITcontact::field_contact_identifier] = $this->contact_array[dbKITcontact::field_company_name];
        }
      }
      elseif (!empty($this->contact_array[dbKITcontact::field_person_last_name])) {
        if (!empty($this->contact_array[dbKITcontact::field_person_first_name])) {
          $this->contact_array[dbKITcontact::field_contact_identifier] = sprintf('%s, %s', $this->contact_array[dbKITcontact::field_person_last_name], $this->contact_array[dbKITcontact::field_person_first_name]);
        }
        else {
          $this->contact_array[dbKITcontact::field_contact_identifier] = $this->contact_array[dbKITcontact::field_person_last_name];
        }
      }
      else {
        // nur E-Mail
        (!empty($this->contact_array[dbKITcontact::field_email_standard])) ? $ie = $this->contact_array[dbKITcontact::field_email_standard] : $ie = 0;
        $e_array = explode(';', $this->contact_array[dbKITcontact::field_email]);
        $e_mail = explode('|', $e_array[$ie]);
        $this->contact_array[dbKITcontact::field_contact_identifier] = $e_mail[1];
      }
    } // check identifier

    /*
     * Protocol
     */
    if (isset($_REQUEST[dbKITprotocol::field_memo]) && !empty($_REQUEST[dbKITprotocol::field_memo])) {
      $data = array();
      (isset($_REQUEST[dbKITprotocol::field_date]) && !empty($_REQUEST[dbKITprotocol::field_date])) ? $date = date('Y-m-d H:i:s', strtotime($_REQUEST[dbKITprotocol::field_date])) : $date = date('Y-m-d H:i:s');
      $data[dbKITprotocol::field_date] = $date;
      $data[dbKITprotocol::field_type] = $_REQUEST[dbKITprotocol::field_type];
      $data[dbKITprotocol::field_members] = $_REQUEST[dbKITprotocol::field_members];
      $data[dbKITprotocol::field_memo] = $_REQUEST[dbKITprotocol::field_memo];
      $data[dbKITprotocol::field_contact_id] = $this->contact_array[dbKITcontact::field_id];
      $data[dbKITprotocol::field_status] = dbKITprotocol::status_active;
      $data[dbKITprotocol::field_update_by] = $tools->getDisplayName();
      $data[dbKITprotocol::field_update_when] = date('Y-m-d H:i:s');
      if (!$dbProtocol->sqlInsertRecord($data)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProtocol->getError()));
        return false;
      }
      $message .= kit_msg_protocol_updated;
    } // check Protocol

    /*
     * STATUS
     */
    if (isset($old_data[dbKITcontact::field_status]) && ($old_data[dbKITcontact::field_status] != $this->contact_array[dbKITcontact::field_status])) {
      // Status hat sich geaendert - pruefen ob ein Eintrag in dbKITregister existiert!
      $where = array(
          dbKITregister::field_contact_id => $this->contact_array[dbKITcontact::field_id]);
      $reg = array();
      if (!$dbRegister->sqlSelectRecord($where, $reg)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
      if (count($reg) > 0) {
        // es existiert ein Datensatz, dbKITregister anpassen
        $data = array(
            dbKITregister::field_status => $this->contact_array[dbKITcontact::field_status],
            dbKITregister::field_update_when => date('Y-m-d H:i:s'),
            dbKITregister::field_update_by => $tools->getDisplayName());
        if (!$dbRegister->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
          return false;
        }
        $message .= kit_msg_register_status_updated;
      }
    }

    $this->setMessage($message);
    return true;
  } // checkContact()

  /**
   * Kontaktdaten sichern
   *
   * @return STR dlgContact()
   */
  private function saveContact() {
    global $tools;
    global $dbContact;
    global $dbRegister;
    global $dbContactAddress;
    global $dbMemos;
    /**
     * minimale Bedingungen ueberpruefen, es muessen mindestens gesetzt sein:
     * E-Mail ODER Firma+Stadt ODER Firma+Telefon ODER Name+Stadt ODER
     * Name+Telefon
     */
    if ((empty($_REQUEST[dbKITcontact::field_email.'_0'])) && (empty($_REQUEST[dbKITcontact::field_company_name]) && empty($_REQUEST[dbKITcontactAddress::field_city])) && (empty($_REQUEST[dbKITcontact::field_company_name]) && empty($_REQUEST[dbKITcontact::field_phone])) && (empty($_REQUEST[dbKITcontact::field_person_last_name]) && empty($_REQUEST[dbKITcontactAddress::field_city])) && (empty($_REQUEST[dbKITcontact::field_person_last_name]) && empty($_REQUEST[dbKITcontact::field_phone]))) {
      // Mindesbedingungen nicht erfuellt
      $this->setMessage(kit_msg_contact_minimum_failed);
      return $this->dlgContact();
    }
    // exit if ID is not set
    if (!isset($_REQUEST[dbKITcontact::field_id])) {
      return $this->dlgContact();
    }
    if (!$this->request2contact()) {
      // error requesting data
      return $this->dlgContact();
    }
    if (!$this->checkContact()) {
      // error checking data
      return $this->dlgContact();
    }
    // ok - save data
    $message = $this->getMessage();
    if ($this->contact_array[dbKITcontact::field_id] == -1) {
      // new data set
      $data = $this->contact_array;
      $id = $data[dbKITcontact::field_id];
      unset($data[dbKITcontact::field_id]);
      // unset the free fields and notes! (will be saved later)
      unset($data[dbKITcontact::field_free_1]);
      unset($data[dbKITcontact::field_free_2]);
      unset($data[dbKITcontact::field_free_3]);
      unset($data[dbKITcontact::field_free_4]);
      unset($data[dbKITcontact::field_free_5]);
      unset($data[dbKITcontact::field_free_note_1]);
      unset($data[dbKITcontact::field_free_note_2]);
      $data[dbKITcontact::field_update_by] = $tools->getDisplayName();
      $data[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
      if (!$dbContact->sqlInsertRecord($data, $id)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      $message .= sprintf(kit_msg_contact_insert, $id);
      $_REQUEST[dbKITcontact::field_id] = $id;
      $dbContact->addSystemNotice($id, sprintf(kit_protocol_create_contact, $tools->getDisplayName()));
    }
    else {
      // update data
      $where = array();
      $id = $this->contact_array[dbKITcontact::field_id];
      $where[dbKITcontact::field_id] = $id;
      $data = $this->contact_array;
      unset($data[dbKITcontact::field_id]);
      // unset the free fields and notes! (will be saved later)
      unset($data[dbKITcontact::field_free_1]);
      unset($data[dbKITcontact::field_free_2]);
      unset($data[dbKITcontact::field_free_3]);
      unset($data[dbKITcontact::field_free_4]);
      unset($data[dbKITcontact::field_free_5]);
      unset($data[dbKITcontact::field_free_note_1]);
      unset($data[dbKITcontact::field_free_note_2]);
      $data[dbKITcontact::field_update_by] = $tools->getDisplayName();
      $data[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
      if (!$dbContact->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      if ($this->contact_array[dbKITcontact::field_status] == dbKITcontact::status_deleted) {
        // contact is deleted...
        $message .= sprintf(kit_msg_contact_deleted, $id);
        // check if user account exist and delete user account...
        $sql = sprintf("UPDATE %s SET %s='%s', %s='%s', %s='%s' WHERE %s='%s' AND %s!='%s'", $dbRegister->getTableName(), dbKITregister::field_status, dbKITregister::status_deleted, dbKITregister::field_update_by, $tools->getDisplayName(), dbKITregister::field_update_when, date('Y-m-d H:i:s'), dbKITregister::field_contact_id, $id, dbKITregister::field_status, dbKITregister::status_deleted);
        $result = array();
        if (!$dbRegister->sqlExec($sql, $result)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
          return false;
        }
      }
      else {
        $message .= sprintf(kit_msg_contact_update, $id);
      }
      $_REQUEST[dbKITcontact::field_id] = $id;
    }
    /**
     * Check Newsletter
     */
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s'", $dbRegister->getTableName(), dbKITregister::field_contact_id, $id, dbKITregister::field_status, dbKITregister::status_deleted);
    $result = array();
    if (!$dbRegister->sqlExec($SQL, $result)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if ((count($result) < 1) && ($this->contact_array[dbKITcontact::field_status] == dbKITcontact::status_active)) {
      // Datensatz existiert nicht
      $data = array();
      $data[dbKITregister::field_contact_id] = $id;
      $data[dbKITregister::field_email] = $dbContact->getStandardEMailByID($id);
      $data[dbKITregister::field_status] = dbKITregister::status_active;
      $data[dbKITregister::field_username] = $dbContact->getStandardEMailByID($id);
      $data[dbKITregister::field_password] = md5($dbContact->getStandardEMailByID($id));
      $data[dbKITregister::field_register_key] = $tools->createGUID();
      $data[dbKITregister::field_register_date] = date('Y-m-d H:i:s');
      $data[dbKITregister::field_register_confirmed] = date('Y-m-d H:i:s');
      $data[dbKITregister::field_newsletter] = $this->contact_array[dbKITcontact::field_newsletter];
      $data[dbKITregister::field_update_by] = $tools->getDisplayName();
      $data[dbKITregister::field_update_when] = date('Y-m-d H:i:s');
      $aid = -1;
      if (!$dbRegister->sqlInsertRecord($data, $aid)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
    }
    elseif ($this->contact_array[dbKITcontact::field_status] == dbKITcontact::status_active) {
      $where = array();
      $where[dbKITregister::field_id] = $result[0][dbKITregister::field_id];
      $data = array();
      $data[dbKITregister::field_newsletter] = $this->contact_array[dbKITcontact::field_newsletter];
      $data[dbKITregister::field_update_by] = $tools->getDisplayName();
      $data[dbKITregister::field_newsletter] = $this->contact_array[dbKITcontact::field_newsletter];
      if (!$dbRegister->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
    }

    /**
     * check memo
     */
    if ($this->contact_array[dbKITcontact::field_contact_note] > 0) {
      // make sure that contact id is in memo record
      $where = array();
      $where[dbKITmemos::field_id] = $this->contact_array[dbKITcontact::field_contact_note];
      $data = array();
      $data[dbKITmemos::field_contact_id] = $id;
      if (!$dbMemos->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
        return false;
      }
    }
    /**
     * check address
     */
    if (!empty($this->contact_array[dbKITcontact::field_address])) {
      $address_array = explode(',', $this->contact_array[dbKITcontact::field_address]);
      foreach ($address_array as $address) {
        $where = array();
        $where[dbKITcontactAddress::field_id] = $address;
        $data = array();
        $data[dbKITcontactAddress::field_contact_id] = $this->contact_array[dbKITcontact::field_id];
        if (!$dbContactAddress->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
          return false;
        }
      }
    }

    /**
     * Check Administration
     */
    $register = array();
    $where = array(
        dbKITregister::field_contact_id => $this->contact_array[dbKITcontact::field_id]);
    if (!$dbRegister->sqlSelectRecord($where, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) > 0) {
      $register = $register[0];
      $data = array();
      if (isset($_REQUEST[dbKITregister::field_status]) && ($_REQUEST[dbKITregister::field_status] != $register[dbKITregister::field_status])) {
        $data[dbKITregister::field_status] = $_REQUEST[dbKITregister::field_status];
      }
      if (isset($_REQUEST[dbKITregister::field_login_locked]) && ($_REQUEST[dbKITregister::field_login_locked] != $register[dbKITregister::field_login_locked])) {
        $data[dbKITregister::field_login_locked] = 1;
      }
      if (!isset($_REQUEST[dbKITregister::field_login_locked]) && ($register[dbKITregister::field_login_locked] == 1)) {
        $data[dbKITregister::field_login_locked] = 0;
        $data[dbKITregister::field_login_failures] = 0;
      }
      if ((isset($_REQUEST[dbKITregister::field_password.'_1']) && (!empty($_REQUEST[dbKITRegister::field_password.'_1']))) && (isset($_REQUEST[dbKITregister::field_password.'_2']) && (!empty($_REQUEST[dbKITRegister::field_password.'_2']))) && ($_REQUEST[dbKITregister::field_password.'_1'] == $_REQUEST[dbKITregister::field_password.'_2'])) {
        $data[dbKITregister::field_password] = md5($_REQUEST[dbKITregister::field_password.'_1']);
      }
      if (!empty($data)) {
        if (!$dbRegister->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
          return false;
        }
        $message .= kit_msg_register_status_updated;
      }
    }

    /*
     * user defined fields
     */
    // get the old user defined fields for compare
    $SQL = sprintf("SELECT %s,%s,%s,%s,%s,%s,%s FROM %s WHERE %s='%s'", dbKITcontact::field_free_1, dbKITcontact::field_free_2, dbKITcontact::field_free_3, dbKITcontact::field_free_4, dbKITcontact::field_free_5, dbKITcontact::field_free_note_1, dbKITcontact::field_free_note_2, $dbContact->getTableName(), dbKITcontact::field_id, $id);
    $field_array = array();
    if (!$dbContact->sqlExec($SQL, $field_array)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    $field_array = $field_array[0];
    $user_defined_fields = array(
        'contact_free_note_%d',
        'contact_free_field_%d');
    foreach ($user_defined_fields as $user_defined_field) {
      // walk through the user defined fields & notes and look if they ar set
      for ($i = 1; $i < 6; $i++) {
        if (isset($_REQUEST[sprintf($user_defined_field, $i)])) {
          $fid = $field_array[sprintf($user_defined_field, $i)];
          if (($fid < 1) && (!empty($_REQUEST[sprintf($user_defined_field, $i)]))) {
            // field is not empty and does not exists in in dbKITmemo
            $data = array(
                dbKITmemos::field_contact_id => $id,
                dbKITmemos::field_memo => trim($_REQUEST[sprintf($user_defined_field, $i)]),
                dbKITmemos::field_status => dbKITmemos::status_active,
                dbKITmemos::field_update_by => 'SYSTEM',
                dbKITmemos::field_update_when => date('Y-m-d H:i:s'));
            $mid = -1;
            if (!$dbMemos->sqlInsertRecord($data, $mid)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
              return false;
            }
            // update contact record!
            $where = array(dbKITcontact::field_id => $id);
            $data = array(sprintf($user_defined_field, $i) => $mid);
            if (!$dbContact->sqlUpdateRecord($data, $where)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
              return false;
            }
          }
          elseif ($fid > 0) {
            // field already exists in dbKITmemo - get the data field
            $where = array(dbKITmemos::field_id => $fid);
            $memo = array();
            if (!$dbMemos->sqlSelectRecord($where, $memo)) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
              return false;
            }
            $memo = $memo[0];
            if (trim($_REQUEST[sprintf($user_defined_field, $i)] != $memo[dbKITmemos::field_memo])) {
              // entries differ - update record
              $data = array(
                  dbKITmemos::field_memo => trim($_REQUEST[sprintf($user_defined_field, $i)]),
                  dbKITmemos::field_status => dbKITmemos::status_active,
                  dbKITmemos::field_update_by => 'SYSTEM',
                  dbKITmemos::field_update_when => date('Y-m-d H:i:s'));
              if (!$dbMemos->sqlUpdateRecord($data, $where)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMemos->getError()));
                return false;
              }
            }
          }
        }
      }
    }

    $this->setMessage($message);
    if ($this->contact_array[dbKITcontact::field_status] == dbKITcontact::status_deleted) {
      // return List if contact is deleted...
      return $this->dlgList();
    }
    else {
      return $this->dlgContact();
    }
  } // saveContact()

  /**
   * Gibt den Dialog fuer allgemeine Einstellungen zurueck
   */
  private function dlgConfigGeneral() {
    global $dbCfg;
    global $parser;

    $SQL = sprintf("SELECT * FROM %s WHERE NOT %s='%s' ORDER BY %s", $dbCfg->getTableName(), dbKITcfg::field_status, dbKITcfg::status_deleted, dbKITcfg::field_label);
    $config = array();
    if (!$dbCfg->sqlExec($SQL, $config)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
      return false;
    }
    $count = array();
    $data = array(
        'label' => '',
        'value' => kit_header_cfg_value,
        'description' => kit_header_cfg_description);
    $items = $parser->get($this->template_path.'backend.config.general.th.htt', $data);

    $row = new Dwoo_Template_File($this->template_path.'backend.config.general.tr.htt');
    // bestehende Eintraege auflisten
    foreach ($config as $entry) {
      $id = $entry[dbKITcfg::field_id];
      $count[] = $id;
      $label = constant($entry[dbKITcfg::field_label]);
      (isset($_REQUEST[dbKITcfg::field_value.'_'.$id])) ? $val = $_REQUEST[dbKITcfg::field_value.'_'.$id] : $val = $entry[dbKITcfg::field_value];
      // Hochkommas maskieren, UTF8 decodieren
      $val = str_replace('"', '&quot;', $val);
      $value = sprintf('<input type="text" name="%s_%s" value="%s" />', dbKITcfg::field_value, $id, $val);
      $desc = constant($entry[dbKITcfg::field_description]);
      $data = array(
          'label' => $label,
          'value' => $value,
          'description' => $desc);
      $items .= $parser->get($row, $data);
    }
    $items_value = implode(",", $count);

    // Checkbox fuer CSV Export
    $items .= $parser->get($this->template_path.'backend.config.general.csv.htt', array(
        'name' => self::request_csv_export,
        'label' => kit_label_csv_export));
    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_cfg);
    }
    $data = array(
        'form_name' => 'config',
        'form_action' => $this->page_link,
        'action_name' => self::request_action,
        'action_value' => self::action_cfg_save_general,
        'items_name' => self::request_items,
        'items_value' => $items_value,
        'header' => kit_header_cfg,
        'intro' => $intro,
        'items' => $items,
        'add' => '',
        'btn_ok' => kit_btn_ok,
        'btn_abort' => kit_btn_abort,
        'abort_location' => $this->page_link);
    return $parser->get($this->template_path.'backend.config.general.htt', $data);
  } // dlgConfigGeneral()

  public function dlgConfigArray() {
    global $dbContactArrayCfg;
    global $parser;
    $form_name = 'dlg_config_array';
    $SQL = sprintf("SELECT * FROM %s WHERE %s!='%s' ORDER BY %s, %s", $dbContactArrayCfg->getTableName(), dbKITcontactArrayCfg::field_status, dbKITcontactArrayCfg::status_deleted, dbKITcontactArrayCfg::field_type, dbKITcontactArrayCfg::field_value);
    $config_array = array();
    if (!$dbContactArrayCfg->sqlExec($SQL, $config_array)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
      return false;
    }
    $items = '';
    $item_list = array();
    // template of row
    $row = new Dwoo_Template_File($this->template_path.'backend.config.array.tr.htt');
    foreach ($config_array as $item) {
      $item_list[] = $item[dbKITcontactArrayCfg::field_id];
      // type
      $type = $dbContactArrayCfg->type_array[$item[dbKITcontactArrayCfg::field_type]];
      // value
      $ivalue = sprintf('<input type="text" name="%s" value="%s" />', dbKITcontactArrayCfg::field_value.'_'.$item[dbKITcontactArrayCfg::field_id], $item[dbKITcontactArrayCfg::field_value]);
      // identifier
      $identifier = $item[dbKITcontactArrayCfg::field_identifier];
      // status
      $option = '';
      foreach ($dbContactArrayCfg->status_array as $key => $value) {
        ($key == $item[dbKITcontactArrayCfg::field_status]) ? $selected = ' selected="selected"' : $selected = '';
        $option .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
      }
      $status = sprintf('<select name="%s">%s</select>', dbKITcontactArrayCfg::field_status.'_'.$item[dbKITcontactArrayCfg::field_id], $option);
      // last change
      $last_change = sprintf('%s, %s', $item[dbKITcontactArrayCfg::field_update_by], date(DATE_FORMAT.' - '.TIME_FORMAT, strtotime($item[dbKITcontactArrayCfg::field_update_when])));
      $data = array(
          'id' => sprintf('%05d', $item[dbKITcontactArrayCfg::field_id]),
          'type' => $type,
          'identifier' => $identifier,
          'value' => $ivalue,
          'status' => $status,
          'lastchange' => $last_change);
      $items .= $parser->get($row, $data);
    }

    // neue Eintraege ermoeglichen
    $add = '';
    $type_array = $dbContactArrayCfg->type_array;
    natcasesort($type_array);
    for ($i = 0; $i < 3; $i++) {
      $option = '';
      foreach ($type_array as $key => $value) {
        $option .= sprintf('<option value="%s">%s</option>', $key, $value);
      }
      $type = sprintf('<select name="%s">%s</select>', dbKITcontactArrayCfg::field_type.'_add_'.$i, $option);
      $value = sprintf('<input type="text" name="%s" value="" />', dbKITcontactArrayCfg::field_value.'_add_'.$i);

      $identifier = sprintf('<input type="text" name="%s" value="" />', dbKITcontactArrayCfg::field_identifier.'_add_'.$i);
      $data = array(
          'id' => '',
          'type' => $type,
          'identifier' => $identifier,
          'value' => $value,
          'status' => '',
          'lastchange' => '');
      $add .= $parser->get($row, $data);
    }

    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_cfg_array);
    }

    $data = array(
        'form_name' => $form_name,
        'form_action' => $this->page_link,
        'action_name' => self::request_action,
        'action_value' => self::action_cfg,
        //self::action_cfg_save_array,
        'cfg_name' => self::request_cfg_tab,
        'cfg_value' => self::action_cfg_tab_array_save,
        //self::action_cfg_tab_array,
        'item_name' => 'item_count',
        'item_value' => implode(',', $item_list),
        'header' => kit_header_cfg_array,
        'intro' => $intro,
        'header_id' => kit_label_id,
        'header_type' => kit_label_type,
        'header_identifier' => kit_label_identifier,
        'header_value' => kit_label_value,
        'header_status' => kit_label_status,
        'header_changed' => kit_label_last_changed_by,
        'items' => $items,
        'add_title' => kit_label_cfg_array_add_items,
        'add' => $add,
        'btn_ok' => kit_btn_ok,
        'btn_abort' => kit_btn_abort,
        'abort_location' => $this->page_link);
    return $parser->get($this->template_path.'backend.config.array.htt', $data);
  } // dlgConfigArray()

  /**
   * Importieren von Daten
   */
  public function dlgConfigImport() {
    global $dbContact;
    global $parser;

    $dbMassmail = new dbMassMailAddresses();
    $items = '';
    if ($dbMassmail->sqlTableExists()) {
      $dbMassmailGroups = new dbMassMailGroups();
      if (!$dbMassmailGroups->sqlTableExists()) {
        // fataler Fehler, Gruppen Tabelle fehlt!
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_import_massmail_grp_missing));
        return false;
      }
      $where = array();
      $group_array = array();
      if (!$dbMassmailGroups->sqlSelectRecord($where, $group_array)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMassmailGroups->getError()));
        return false;
      }

      $option = '';
      foreach ($group_array as $group) {
        $option .= sprintf('<option value="%s">%s</option>', $group[dbMassmailGroups::field_group_id], utf8_decode($group[dbMassmailGroups::field_group_name]));
      }
      $select_massmail_group = sprintf('<select name="%s">%s</select>', dbMassmailGroups::field_group_name, $option);

      $option = '';
      foreach ($dbContact->newsletter_array as $key => $value) {
        $option .= sprintf('<option value="%s">%s</option>', $key, $value);
      }
      $select_kit_newsletter = sprintf('<select name="%s">%s</select>', dbKITcontact::field_newsletter, $option);

      $option = '';
      foreach ($dbContact->email_array as $key => $value) {
        $option .= sprintf('<option value="%s">%s</option>', $key, $value);
      }
      $select_email_type = sprintf('<select name="%s">%s</select>', dbKITcontact::field_email, $option);
      $data = array(
          'header_massmail' => kit_label_massmail,
          'form_name' => 'import_massmail',
          'form_action' => $this->page_link,
          'action_name' => self::request_action,
          'action_value' => self::action_cfg,
          'cfg_name' => self::request_cfg_tab,
          'cfg_value' => self::action_cfg_tab_import,
          'import_name' => self::request_import,
          'import_value' => self::action_import_massmail,
          'massmail_group' => sprintf('%s %s', kit_text_from_massmail_group, $select_massmail_group),
          'kit_group' => sprintf('%s %s<br />%s %s', kit_text_to_category, $select_kit_newsletter, kit_text_as_email_type, $select_email_type),
          'import' => kit_btn_import);
      $items .= $parser->get($this->template_path.'backend.config.import.massmail.htt', $data);
    }
    else {
      $items .= $parser->get($this->template_path.'backend.config.import.massmail.tr.htt', array(
          'label' => kit_label_massmail,
          'value' => kit_msg_massmail_not_installed));
    }
    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_cfg_import);
    }
    $data = array(
        'header' => kit_header_cfg_import,
        'intro' => $intro,
        'header_import' => kit_label_import_from,
        'header_action' => kit_label_import_action,
        'items' => $items);
    return $parser->get($this->template_path.'backend.config.import.htt', $data);
  } // dlgConfigImport()

  /**
   * Fuehrt den Import von Massmail Daten durch und gibt Meldungen ueber den Verlauf aus.
   *
   * @return STR dlgConfigImport()
   */
  public function execImportMassmail() {
    global $tools;
    global $dbContact;

    if (!isset($_REQUEST[dbMassmailGroups::field_group_name]) || !isset($_REQUEST[dbKITcontact::field_newsletter]) || !isset($_REQUEST[dbKITcontact::field_email])) {
      // Fataler Fehler: nicht alle Variablen gesetzt
      $this->setError(kit_error_import_massmail_missing_vars);
      return false;
    }
    $message = '';
    $dbMassmail = new dbMassMailAddresses();
    $where = array();
    $where[dbMassMailAddresses::field_group_id] = $_REQUEST[dbMassMailGroups::field_group_name];
    $massmail_data = array();
    if (!$dbMassmail->sqlSelectRecord($where, $massmail_data)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbMassmail));
      return false;
    }
    if (count($massmail_data) < 1) {
      // Gruppe enthaelt keine Daten
      $this->setMessage(sprintf(kit_msg_massmail_group_no_data, $_REQUEST[dbMassMailGroups::field_group_name]));
      return $this->dlgConfigImport();
    }
    else {
      // Import starten
      $add_emails = array();
      $ignore_emails = array();
      foreach ($massmail_data as $massmail) {
        $SQL = sprintf("SELECT %s FROM %s WHERE %s LIKE '%%%s%%'", dbKITcontact::field_id, $dbContact->getTableName(), dbKITcontact::field_email, $massmail[dbMassmailAddresses::field_mail_to]);
        $kitCheck = array();
        if (!$dbContact->sqlExec($SQL, $kitCheck)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
        if (count($kitCheck) > 0) {
          // E-Mail Adresse existiert bereits
          $ignore_emails[] = sprintf('%s (ID %05d)', $massmail[dbMassmailAddresses::field_mail_to], $kitCheck[0][dbKITcontact::field_id]);
        }
        else {
          // E-Mail Adresse uebernehmen
          $data = array();
          $data[dbKITcontact::field_email] = sprintf('%s|%s', $_REQUEST[dbKITcontact::field_email], strtolower($massmail[dbMassmailAddresses::field_mail_to]));
          $data[dbKITcontact::field_email_standard] = 0;
          $data[dbKITcontact::field_contact_identifier] = strtolower($massmail[dbMassmailAddresses::field_mail_to]);
          $data[dbKITcontact::field_status] = dbKITcontact::status_active;
          $data[dbKITcontact::field_access] = dbKITcontact::access_internal;
          //$data[dbKITcontact::field_category] = $_REQUEST[dbKITcontact::field_category];
          $data[dbKITcontact::field_newsletter] = $_REQUEST[dbKITcontact::field_newsletter];
          $data[dbKITcontact::field_update_by] = $tools->getDisplayName();
          $data[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
          $id = -1;
          if (!$dbContact->sqlInsertRecord($data, $id)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
            return false;
          }
          $add_emails[] = $massmail[dbMassmailAddresses::field_mail_to];
          $dbContact->addSystemNotice($id, sprintf(kit_protocol_create_contact_massmail, $massmail[dbMassmailAddresses::field_mail_to]));
        }
      }
      if (count($ignore_emails) > 0) {
        // ignorierte E-Mails anzeigen
        $message .= sprintf(kit_msg_massmail_email_skipped, implode(', ', $ignore_emails));
      }
      if (count($add_emails) > 0) {
        // importierte E-Mails anzeigen
        $message .= sprintf(kit_msg_massmail_emails_imported, count($add_emails), implode(', ', $add_emails));
      }
      else {
        // keine E-Mails uebernommen
        $message .= kit_msg_massmail_no_emails_imported;
      }
      $this->setMessage($message);
      return $this->dlgConfigImport();
    }
  } // execImportMassmail()

  /**
   * Dialog for configuring KIT
   *
   * @return STR
   */
  public function dlgConfig() {
    $cfg_tab = '';
    (isset($_REQUEST[self::request_cfg_tab])) ? $choose_cfg = $_REQUEST[self::request_cfg_tab] : $choose_cfg = self::action_cfg_tab_general;
    foreach ($this->tab_config_array as $key => $value) {
      ($key == $choose_cfg) ? $selected = ' class="selected"' : $selected = '';
      $cfg_tab .= sprintf('<li%s><a href="%s">%s</a></li>', $selected, sprintf('%s&%s=%s&%s=%s', $this->page_link, self::request_action, self::action_cfg, self::request_cfg_tab, $key), $value);
    }
    $cfg_tab = sprintf('<ul class="nav_tab">%s</ul>', $cfg_tab);
    switch ($choose_cfg) :
      case self::action_cfg_tab_array:
        $result = $this->dlgConfigArray();
        break;
      case self::action_cfg_tab_array_save:
        $result = $this->saveConfigArray();
        break;
      case self::action_cfg_tab_import:
        $kitImportDlg = new kitImportDialog();
        $result = $kitImportDlg->action();
        break;
      /*
       * Massmail Import wird nicht laenger verwendet!
       *
      (isset($_REQUEST[self::request_import])) ? $import = $_REQUEST[self::request_import] : $import = '';
      switch ($import):
      case self::action_import_massmail:
      $result = $this->execImportMassmail();
      break;
      default:
      $result = $this->dlgConfigImport();
      break;
      endswitch;
      break;
       */
      case self::action_cfg_tab_provider:
        $result = $this->dlgConfigProvider();
        break;
      case self::action_cfg_tab_provider_save:
        $result = $this->saveConfigProvider();
        break;
      case self::action_cfg_tab_general:
      default:
        $result = $this->dlgConfigGeneral();
        break;
    endswitch;
    $result = sprintf('<div class="config_container">%s%s</div>', $cfg_tab, $result);
    return $result;
  } // dlgConfig()

  /**
   * Prueft und sichert das ConfigArray (Listenverwaltung)
   *
   * @return STR dlgConfigArray()
   */
  public function saveConfigArray() {
    global $dbContactArrayCfg;

    $message = '';
    $tools = new kitTools();
    // Bestehende Eintraege pruefen
    if (isset($_REQUEST['item_count'])) {
      $item_array = explode(',', $_REQUEST['item_count']);
      foreach ($item_array as $id) {
        $where = array();
        $where[dbKITcontactArrayCfg::field_id] = $id;
        $data = array();
        if (!$dbContactArrayCfg->sqlSelectRecord($where, $data)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
          return false;
        }
        $data = $data[0];
        (isset($_REQUEST[dbKITcontactArrayCfg::field_value.'_'.$id])) ? $req_value = $_REQUEST[dbKITcontactArrayCfg::field_value.'_'.$id] : $req_value = '';
        if (($req_value != $data[dbKITcontactArrayCfg::field_value]) || ($_REQUEST[dbKITcontactArrayCfg::field_status.'_'.$id] != $data[dbKITcontactArrayCfg::field_status])) {
          // Datensatz geaendert
          $data = array();
          $data[dbKITcontactArrayCfg::field_value] = $req_value;
          $data[dbKITcontactArrayCfg::field_status] = $_REQUEST[dbKITcontactArrayCfg::field_status.'_'.$id];
          $data[dbKITcontactArrayCfg::field_update_by] = $tools->getDisplayName();
          $data[dbKITcontactArrayCfg::field_update_when] = date('Y-m-d H:i:s');
          if (!$dbContactArrayCfg->sqlUpdateRecord($data, $where)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
            return false;
          }
          $message .= sprintf(kit_msg_cfg_array_item_updated, $id);
        }
      }
    }
    // neue Eintraege hinzufuegen?
    for ($i = 0; $i < 3; $i++) {
      if (isset($_REQUEST[dbKITcontactArrayCfg::field_value.'_add_'.$i]) && !empty($_REQUEST[dbKITcontactArrayCfg::field_value.'_add_'.$i]) && !empty($_REQUEST[dbKITcontactArrayCfg::field_identifier.'_add_'.$i])) {
        // pruefen, ob der Identifier bereits existiert
        $where = array();
        $where[dbKITcontactArrayCfg::field_status] = dbKITcontactArrayCfg::status_active;
        $where[dbKITcontactArrayCfg::field_identifier] = $_REQUEST[dbKITcontactArrayCfg::field_identifier.'_add_'.$i];
        $check = array();
        if (!$dbContactArrayCfg->sqlSelectRecord($where, $check)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
          return false;
        }
        if (count($check) > 0) {
          // Identifier existiert bereits
          $message .= sprintf(kit_msg_cfg_array_identifier_in_use, $_REQUEST[dbKITcontactArrayCfg::field_identifier.'_add_'.$i], $check[0][dbKITcontactArrayCfg::field_id]);
        }
        else {
          // neuen Eintrag hinzufuegen
          $data = array();
          $data[dbKITcontactArrayCfg::field_value] = $_REQUEST[dbKITcontactArrayCfg::field_value.'_add_'.$i];
          $data[dbKITcontactArrayCfg::field_type] = $_REQUEST[dbKITcontactArrayCfg::field_type.'_add_'.$i];
          $data[dbKITcontactArrayCfg::field_identifier] = $_REQUEST[dbKITcontactArrayCfg::field_identifier.'_add_'.$i];
          $data[dbKITcontactArrayCfg::field_status] = dbKITcontactArrayCfg::status_active;
          $data[dbKITcontactArrayCfg::field_update_by] = $tools->getDisplayName();
          $data[dbKITcontactArrayCfg::field_update_when] = date('Y-m-d H:i:s');
          $id = -1;
          if (!$dbContactArrayCfg->sqlInsertRecord($data, $id)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
            return false;
          }
          $message .= sprintf(kit_msg_cfg_array_item_add, $id);
          unset($_REQUEST[dbKITcontactArrayCfg::field_value.'_add_'.$i]);
          unset($_REQUEST[dbKITcontactArrayCfg::field_value.'_add_'.$i]);
          unset($_REQUEST[dbKITcontactArrayCfg::field_identifier.'_add_'.$i]);
        }
      }
    }
    $this->setMessage($message);
    return $this->dlgConfigArray();
  }

  /**
   * Ueberprueft Aenderungen die im Dialog dlgConfig() vorgenommen wurden
   * und aktualisiert die entsprechenden Datensaetze.
   * Fuegt neue Datensaetze ein.
   *
   * @return STR DIALOG dlgConfig()
   */
  public function saveConfigGeneral() {
    global $dbCfg;
    global $tools;
    global $parser;

    $message = '';
    // ueberpruefen, ob ein Eintrag geaendert wurde
    if ((isset($_REQUEST[self::request_items])) && (!empty($_REQUEST[self::request_items]))) {
      $ids = explode(",", $_REQUEST[self::request_items]);
      foreach ($ids as $id) {
        if (isset($_REQUEST[dbKITcfg::field_value.'_'.$id])) {
          $value = utf8_decode($_REQUEST[dbKITcfg::field_value.'_'.$id]);
          $where = array();
          $where[dbKITcfg::field_id] = $id;
          $config = array();
          if (!$dbCfg->sqlSelectRecord($where, $config)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
            return false;
          }
          if (sizeof($config) < 1) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_id, $id)));
            return false;
          }
          $config = $config[0];
          if ($config[dbKITcfg::field_value] != $value) {
            if (($config[dbKITcfg::field_name] == dbKITcfg::cfgClearCompileDir) && ($value == 1)) {
              // reset the Dwoo compile directory
              $compileDirs = new RecursiveDirectoryIterator($parser->getCompileDir());
              $compile = new RecursiveIteratorIterator($compileDirs);
              $count = 0;
              foreach ($compile as $file) {
                if ($compile->isDot() || $compile->isDir()) {
                  continue;
                }
                $count += unlink((string) $file) ? 1 : 0;
              }
              $message .= sprintf(kit_msg_cfg_clear_compile_dir, $count);
              unset($_REQUEST[dbKITcfg::field_value.'_'.$id]);
              continue;
            }
            // Wert wurde geaendert
            if (!$dbCfg->setValue($value, $id) && $dbCfg->isError()) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
              return false;
            }
            elseif ($dbCfg->isMessage()) {
              $message .= $dbCfg->getMessage();
            }
            else {
              // Datensatz wurde aktualisiert
              $message .= sprintf(kit_msg_cfg_id_updated, $id, $config[dbKITcfg::field_name]);
            }
          }
        }
      }
    }
    // ueberpruefen, ob ein neuer Eintrag hinzugefuegt wurde
    if ((isset($_REQUEST[dbKITcfg::field_name])) && (!empty($_REQUEST[dbKITcfg::field_name]))) {
      // pruefen ob dieser Konfigurationseintrag bereits existiert
      $where = array();
      $where[dbKITcfg::field_name] = utf8_decode($_REQUEST[dbKITcfg::field_name]);
      $where[dbKITcfg::field_status] = dbKITcfg::status_active;
      $result = array();
      if (!$dbCfg->sqlSelectRecord($where, $result)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
        return false;
      }
      if (sizeof($result) > 0) {
        // Eintrag existiert bereits
        $message .= sprintf(kit_msg_cfg_add_exists, $where[dbKITcfg::field_name]);
      }
      else {
        // Eintrag kann hinzugefuegt werden
        $data = array();
        $data[dbKITcfg::field_name] = utf8_decode($_REQUEST[dbKITcfg::field_name]);
        if (((isset($_REQUEST[dbKITcfg::field_type])) && ($_REQUEST[dbKITcfg::field_type] != dbKITcfg::type_undefined)) && ((isset($_REQUEST[dbKITcfg::field_value])) && (!empty($_REQUEST[dbKITcfg::field_value]))) && ((isset($_REQUEST[dbKITcfg::field_label])) && (!empty($_REQUEST[dbKITcfg::field_label]))) && ((isset($_REQUEST[dbKITcfg::field_description])) && (!empty($_REQUEST[dbKITcfg::field_description])))) {
          // Alle Daten vorhanden
          unset($_REQUEST[dbKITcfg::field_name]);
          $data[dbKITcfg::field_type] = $_REQUEST[dbKITcfg::field_type];
          unset($_REQUEST[dbKITcfg::field_type]);
          $data[dbKITcfg::field_value] = utf8_decode($_REQUEST[dbKITcfg::field_value]);
          unset($_REQUEST[dbKITcfg::field_value]);
          $data[dbKITcfg::field_label] = utf8_decode($_REQUEST[dbKITcfg::field_label]);
          unset($_REQUEST[dbKITcfg::field_label]);
          $data[dbKITcfg::field_description] = utf8_decode($_REQUEST[dbKITcfg::field_description]);
          unset($_REQUEST[dbKITcfg::field_description]);
          $data[dbKITcfg::field_status] = dbKITcfg::status_active;
          $data[dbKITcfg::field_update_by] = $tools->getDisplayName();
          $data[dbKITcfg::field_update_when] = date('Y-m-d H:i:s');
          $id = -1;
          if (!$dbCfg->sqlInsertRecord($data, $id)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
            return false;
          }
          $message .= sprintf(kit_msg_cfg_add_success, $id, $data[dbKITcfg::field_name]);
        }
        else {
          // Daten unvollstaendig
          $message .= kit_msg_cfg_add_incomplete;
        }
      }
    }
    // Sollen Daten als CSV gesichert werden?
    if ((isset($_REQUEST[self::request_csv_export])) && ($_REQUEST[self::request_csv_export] == 1)) {
      // Daten sichern
      $where = array();
      $where[dbKITcfg::field_status] = dbKITcfg::status_active;
      $csv = array();
      $csvFile = WB_PATH.MEDIA_DIRECTORY.'/'.date('ymd-His').'-kit-cfg.csv';
      if (!$dbCfg->csvExport($where, $csv, $csvFile)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
        return false;
      }
      $message .= sprintf(kit_msg_cfg_csv_export, basename($csvFile));
    }

    if (!empty($message))
      $this->setMessage($message);
    return $this->dlgConfig();
  } // saveConfigGeneral()

  /**
   * Dialog for creating and configuring the different Service Providers
   * used in KeppInTouch
   */
  public function dlgConfigProvider() {
    global $dbProvider;
    global $parser;

    (isset($_REQUEST[dbKITprovider::field_id])) ? $id = intval($_REQUEST[dbKITprovider::field_id]) : $id = -1;
    if ($id != -1) {
      $where = array();
      $where[dbKITprovider::field_id] = $id;
      $provider = array();
      if (!$dbProvider->sqlSelectRecord($where, $provider)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
        return false;
      }
      if (count($provider) < 1) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
        return false;
      }
      $provider = $provider[0];
    }
    else {
      // init empty field array
      $provider = $dbProvider->getFields();
      $provider[dbKITprovider::field_id] = -1;
    }
    // init value array for template results
    $value = array();
    // get all service provider for selection list
    $SQL = sprintf("SELECT %s, %s FROM %s WHERE %s != '%s' ORDER BY %s ASC", dbKITprovider::field_id, dbKITprovider::field_name, $dbProvider->getTableName(), dbKITprovider::field_status, dbKITprovider::status_deleted, dbKITprovider::field_name);
    $provider_list = array();

    if (!$dbProvider->sqlExec($SQL, $provider_list)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
      return false;
    }

    // select provider
    $option = sprintf('<option value="-1">%s</option>', kit_text_please_select);
    foreach ($provider_list as $item) {
      ($item[dbKITprovider::field_id] == $provider[dbKITprovider::field_id]) ? $selected = ' selected="selected"' : $selected = '';
      $option .= sprintf('<option value="%s"%s>%s</option>', $item[dbKITprovider::field_id], $selected, $item[dbKITprovider::field_name]);
    }
    $value[dbKITprovider::field_id] = sprintf('<select id="%s" name="%s" onchange="javascript:addSelectToLink(\'%s\',\'%s\');">%s</select>', dbKITprovider::field_id, dbKITprovider::field_id, sprintf('%s&amp;%s=%s&amp;%s=%s%s&amp;%s=', $this->page_link, self::request_action, self::action_cfg, self::request_cfg_tab, self::action_cfg_tab_provider, (defined('LEPTON_VERSION') && isset($_GET['leptoken'])) ? sprintf('&amp;leptoken=%s', $_GET['leptoken']) : '', dbKITprovider::field_id), dbKITprovider::field_id, $option);
    $worker = array(
        dbKITprovider::field_name,
        dbKITprovider::field_identifier,
        dbKITprovider::field_email,
        dbKITprovider::field_smtp_host,
        dbKITprovider::field_smtp_user);
    foreach ($worker as $item) {
      (isset($_REQUEST[$item])) ? $val = $_REQUEST[$item] : $val = $provider[$item];
      $value[$item] = sprintf('<input type="text" name="%s" value="%s" />', $item, $val);
    }
    // remarks
    (isset($_REQUEST[dbKITprovider::field_remark])) ? $val = $_REQUEST[dbKITprovider::field_remark] : $val = $provider[dbKITprovider::field_remark];
    $value[dbKITprovider::field_remark] = sprintf('<textarea name="%s">%s</textarea>', dbKITprovider::field_remark, $val);
    // authentication?
    (isset($_REQUEST[dbKITprovider::field_smtp_auth])) ? $val = 1 : $val = $provider[dbKITprovider::field_smtp_auth];
    (boolean) $val ? $checked = ' checked="checked"' : $checked = '';
    $value[dbKITprovider::field_smtp_auth] = sprintf('<input type="checkbox" name="%s" value="1"%s />', dbKITprovider::field_smtp_auth, $checked);
    // password and retype password
    $value[dbKITprovider::field_smtp_pass] = sprintf('<input type="password" name="%s" value="" />', dbKITprovider::field_smtp_pass);
    $value['pass_retype'] = '<input type="password" name="pass_retype" value="" />';
    $value[dbKITprovider::field_relaying] = sprintf('<input type="checkbox" name="%s", value="1"%s />', dbKITprovider::field_relaying, ($provider[dbKITprovider::field_relaying] == 1) ? ' checked="checked"' : '');
    // status
    $select = '';
    foreach ($dbProvider->status_array as $key => $name) {
      ($key == $provider[dbKITprovider::field_status]) ? $selected = ' selected="selected"' : $selected = '';
      $select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $name);
    }
    $value[dbKITprovider::field_status] = sprintf('<select name="%s">%s</select>', dbKITprovider::field_status, $select);

    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_cfg_provider);
    }
    $data = array(
        'form_name' => 'form_provider',
        'form_action' => $this->page_link,
        'action_name' => self::request_action,
        'action_value' => self::action_cfg,
        'cfg_name' => self::request_cfg_tab,
        'cfg_value' => self::action_cfg_tab_provider_save,
        'header_provider' => kit_header_provider,
        'intro' => $intro,
        'label_select_provider' => kit_label_provider_select,
        'value_select_provider' => $value[dbKITprovider::field_id],
        'label_name' => kit_label_provider_name,
        'value_name' => $value[dbKITprovider::field_name],
        'label_identifier' => kit_label_provider_identifier,
        'value_identifier' => $value[dbKITprovider::field_identifier],
        'label_email' => kit_label_provider_email,
        'value_email' => $value[dbKITprovider::field_email],
        'label_remark' => kit_label_provider_remark,
        'value_remark' => $value[dbKITprovider::field_remark],
        'label_auth' => kit_label_provider_smtp_auth,
        'value_auth' => $value[dbKITprovider::field_smtp_auth],
        'label_host' => kit_label_provider_smtp_host,
        'value_host' => $value[dbKITprovider::field_smtp_host],
        'label_user' => kit_label_provider_smtp_user,
        'value_user' => $value[dbKITprovider::field_smtp_user],
        'label_pass' => kit_label_password,
        'value_pass' => $value[dbKITprovider::field_smtp_pass],
        'label_pass_retype' => kit_label_password_retype,
        'value_pass_retype' => $value['pass_retype'],
        'label_enable_relaying' => kit_label_enable_relaying,
        'value_enable_relaying' => $value[dbKITprovider::field_relaying],
        'label_status' => kit_label_status,
        'value_status' => $value[dbKITprovider::field_status],
        'btn_ok' => kit_btn_ok,
        'btn_abort' => kit_btn_abort,
        'abort_location' => $this->page_link,
        'header_help' => kit_header_help_documentation);
    return $parser->get($this->template_path.'backend.config.provider.htt', $data);
  } // dlgConfigProvider()

  public function saveConfigProvider() {
    global $dbProvider;
    global $tools;

    isset($_REQUEST[dbKITprovider::field_id]) ? $id = intval($_REQUEST[dbKITprovider::field_id]) : $id = -1;
    if ($id > 0) {
      // checking existing data set
      $where = array();
      $where[dbKITprovider::field_id] = $id;
      $old = array();
      if (!$dbProvider->sqlSelectRecord($where, $old)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
        return false;
      }
      if (count($old) < 1) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $id)));
        return false;
      }
      $old = $old[0];
    }
    else {
      // setting defaults for data set
      $old = $dbProvider->getFields();
      $old[dbKITprovider::field_id] = $id;
    }
    $new = $dbProvider->getFields();
    (isset($_REQUEST[dbKITprovider::field_smtp_auth])) ? $new[dbKITprovider::field_smtp_auth] = 1 : $new[dbKITprovider::field_smtp_auth] = 0;

    // check minimum settings
    if ((((bool) $new[dbKITprovider::field_smtp_auth]) && (empty($_REQUEST[dbKITprovider::field_name]) || empty($_REQUEST[dbKITprovider::field_identifier]) || empty($_REQUEST[dbKITprovider::field_email]) || empty($_REQUEST[dbKITprovider::field_smtp_host]) || empty($_REQUEST[dbKITprovider::field_smtp_user]))) || (!(bool) $new[dbKITprovider::field_smtp_auth]) && (empty($_REQUEST[dbKITprovider::field_name]) || empty($_REQUEST[dbKITprovider::field_identifier]) || empty($_REQUEST[dbKITprovider::field_email]))) {
      // failed minimum check
      $this->setMessage(kit_msg_provider_minum_failed);
      return $this->dlgConfigProvider();
    }
    if (!(bool) $new[dbKITprovider::field_smtp_auth] && (!empty($_REQUEST[dbKITprovider::field_smtp_host])) && !empty($_REQUEST[dbKITprovider::field_smtp_user])) {
      // maybe an error, unchecked SMTP auth, but name and host...
      $this->setMessage(kit_msg_provider_check_auth);
      return $this->dlgConfigProvider();
    }
    // checking email
    if (!$tools->validateEMail($_REQUEST[dbKITprovider::field_email])) {
      $this->setMessage(sprintf(kit_msg_email_invalid, $_REQUEST[dbKITprovider::field_email]));
      return $this->dlgConfigProvider();
    }
    // checking password(s)
    if ($id == -1 || (!empty($_REQUEST[dbKITprovider::field_smtp_pass]))) {
      if ($_REQUEST[dbKITprovider::field_smtp_pass] != $_REQUEST['pass_retype']) {
        // passwords missmatch
        $this->setMessage(kit_msg_passwords_mismatch);
        return $this->dlgConfigProvider();
      }
    }
    // ok - save dataset
    $new[dbKITprovider::field_name] = $_REQUEST[dbKITprovider::field_name];
    $new[dbKITprovider::field_identifier] = $_REQUEST[dbKITprovider::field_identifier];
    $new[dbKITprovider::field_email] = $_REQUEST[dbKITprovider::field_email];
    $new[dbKITprovider::field_remark] = $_REQUEST[dbKITprovider::field_remark];
    $new[dbKITprovider::field_smtp_host] = $_REQUEST[dbKITprovider::field_smtp_host];
    $new[dbKITprovider::field_smtp_user] = $_REQUEST[dbKITprovider::field_smtp_user];
    $new[dbKITprovider::field_smtp_pass] = $_REQUEST[dbKITprovider::field_smtp_pass];
    $new[dbKITprovider::field_relaying] = (isset($_REQUEST[dbKITprovider::field_relaying])) ? 1 : 0;
    $new[dbKITprovider::field_status] = $_REQUEST[dbKITprovider::field_status];
    $new[dbKITprovider::field_update_by] = $tools->getDisplayName();
    $new[dbKITprovider::field_update_when] = date('Y-m-d H:i:s');

    if ($id == -1) {
      // insert a new record
      if (!$dbProvider->sqlInsertRecord($new)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
        return false;
      }
      $this->setMessage(sprintf(kit_msg_provider_inserted, $new[dbKITprovider::field_name]));
      unset($_REQUEST);
    }
    else {
      // update existing record
      $where = array();
      $where[dbKITprovider::field_id] = $id;
      if (!$dbProvider->sqlUpdateRecord($new, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
        return false;
      }
      $this->setMessage(sprintf(kit_msg_provider_updated, $new[dbKITprovider::field_name]));
      unset($_REQUEST);
    }
    return $this->dlgConfigProvider();
  } // saveConfigProvider()

  public function dlgEMail() {
    global $parser;
    global $dbProvider;
    global $dbContactArrayCfg;
    global $dbContact;

    $form_name = 'email_form';
    // get all service provider for selection list
    $SQL = sprintf("SELECT %s,%s,%s FROM %s WHERE %s != '%s' ORDER BY %s ASC", dbKITprovider::field_id, dbKITprovider::field_name, dbKITprovider::field_email, $dbProvider->getTableName(), dbKITprovider::field_status, dbKITprovider::status_deleted, dbKITprovider::field_name);
    $provider_list = array();

    if (!$dbProvider->sqlExec($SQL, $provider_list)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
      return false;
    }
    if (count($provider_list) < 1) {
      // error: no provider defined!
      $this->setError(kit_error_no_provider_defined);
      return false;
    }
    // select provider
    $option = '';
    isset($_REQUEST[dbKITprovider::field_id]) ? $id = $_REQUEST[dbKITprovider::field_id] : $id = -1;
    foreach ($provider_list as $item) {
      ($item[dbKITprovider::field_id] == $id) ? $selected = ' selected="selected"' : $selected = '';
      $option .= sprintf('<option value="%s"%s>%s</option>', $item[dbKITprovider::field_id], $selected, sprintf('[%s] %s', $item[dbKITprovider::field_email], $item[dbKITprovider::field_name]));
    }
    $provider = sprintf('<select name="%s">%s</select>', dbKITprovider::field_id, $option);
    // load template row
    $row = new Dwoo_Template_File($this->template_path.'backend.email.dlg.tr.htt');
    $items = $parser->get($row, array(
        'label' => kit_label_provider,
        'value' => $provider));

    // get all categories
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s='%s' ORDER BY %s ASC", $dbContactArrayCfg->getTableName(), dbKITcontactArrayCfg::field_type, dbKITcontactArrayCfg::type_category, dbKITcontactArrayCfg::field_status, dbKITcontactArrayCfg::status_active, dbKITcontactArrayCfg::field_value);
    $category_array = array();
    if (!$dbContactArrayCfg->sqlExec($SQL, $category_array)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
      return false;
    }
    // walk through category array
    $cat = '';
    isset($_REQUEST[dbKITcontact::field_category]) ? $cat_val = $_REQUEST[dbKITcontact::field_category] : $cat_val = array();
    foreach ($category_array as $category) {
      $SQL = sprintf("SELECT COUNT(%s) FROM %s WHERE %s='%s' AND ((%s LIKE '%s') OR (%s LIKE '%s,%%') OR (%s LIKE '%%,%s') OR (%s LIKE '%%%s,%%'))", dbKITcontact::field_id, $dbContact->getTableName(), dbKITcontact::field_status, dbKITcontact::status_active, dbKITcontact::field_category, $category[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_category, $category[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_category, $category[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_category, $category[dbKITcontactArrayCfg::field_identifier]);
      $result = array();
      if (!$dbContact->sqlExec($SQL, $result)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }

      (in_array($category[dbKITcontactArrayCfg::field_identifier], $cat_val)) ? $checked = ' checked="checked"' : $checked = '';
      $cat .= sprintf('<input type="checkbox" name="%s[]" value="%s"%s /> %s (<b>%d</b> %s)<br />', dbKITcontact::field_category, $category[dbKITcontactArrayCfg::field_identifier], $checked, $category[dbKITcontactArrayCfg::field_value], $result[0]['COUNT('.dbKITcontact::field_id.')'], kit_text_records);
    } // foreach
    $items .= $parser->get($row, array(
        'label' => kit_label_audience,
        'value' => $cat));

    // get all newsletters
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s='%s' ORDER BY %s ASC", $dbContactArrayCfg->getTableName(), dbKITcontactArrayCfg::field_type, dbKITcontactArrayCfg::type_newsletter, dbKITcontactArrayCfg::field_status, dbKITcontactArrayCfg::status_active, dbKITcontactArrayCfg::field_value);
    $newsletter_array = array();
    if (!$dbContactArrayCfg->sqlExec($SQL, $newsletter_array)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
      return false;
    }
    // walk through newsletter array
    $news = '';
    isset($_REQUEST[dbKITcontact::field_newsletter]) ? $news_val = $_REQUEST[dbKITcontact::field_newsletter] : $news_val = array();
    foreach ($newsletter_array as $news_item) {
      $SQL = sprintf("SELECT COUNT(%s) FROM %s WHERE %s='%s' AND ((%s LIKE '%s') OR (%s LIKE '%s,%%') OR (%s LIKE '%%,%s') OR (%s LIKE '%%%s,%%'))", dbKITcontact::field_id, $dbContact->getTableName(), dbKITcontact::field_status, dbKITcontact::status_active, dbKITcontact::field_newsletter, $news_item[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_newsletter, $news_item[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_newsletter, $news_item[dbKITcontactArrayCfg::field_identifier], dbKITcontact::field_newsletter, $news_item[dbKITcontactArrayCfg::field_identifier]);
      if (!$dbContact->sqlExec($SQL, $result)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }

      (in_array($news_item[dbKITcontactArrayCfg::field_identifier], $news_val)) ? $checked = ' checked="checked"' : $checked = '';
      $news .= sprintf('<input type="checkbox" name="%s[]" value="%s"%s /> %s (<b>%d</b> %s)<br />', dbKITcontact::field_newsletter, $news_item[dbKITcontactArrayCfg::field_identifier], $checked, $news_item[dbKITcontactArrayCfg::field_value], $result[0]['COUNT('.dbKITcontact::field_id.')'], kit_text_records);
    } // foreach
    $items .= $parser->get($row, array('label' => '', 'value' => $news));

    // HTML
    $html = sprintf('<input type="checkbox" name="%s" value="1" checked="checked" />', dbKITmail::field_is_html);
    $items .= $parser->get($row, array(
        'label' => kit_label_html_format,
        'value' => $html));

    // Subject
    isset($_REQUEST[dbKITmail::field_subject]) ? $sub = $_REQUEST[dbKITmail::field_subject] : $sub = '';
    $subject = sprintf('<input type="text" name="%s" value="%s" />', dbKITmail::field_subject, $sub);
    $items .= $parser->get($row, array(
        'label' => kit_label_mail_subject,
        'value' => $subject));

    // Message
    isset($_REQUEST[dbKITmail::field_html]) ? $content = utf8_encode($_REQUEST[dbKITmail::field_html]) : $content = '';
    ob_start();
    show_wysiwyg_editor(dbKITmail::field_html, dbKITmail::field_html, $content, '99%', '400px');
    $editor = ob_get_contents();
    ob_end_clean();

    // Mitteilungen anzeigen
    if ($this->isMessage()) {
      $intro = sprintf('<div class="message">%s</div>', $this->getMessage());
    }
    else {
      $intro = sprintf('<div class="intro">%s</div>', kit_intro_email);
    }
    $data = array(
        'form_name' => $form_name,
        'form_action' => $this->page_link,
        'action_name' => self::request_action,
        'action_value' => self::action_email_send,
        'header_email' => kit_header_email,
        'intro' => $intro,
        'items' => $items,
        'value_editor' => $editor,
        'header_help' => kit_header_help_documentation,
        'btn_send' => kit_btn_send,
        'btn_abort' => kit_btn_abort,
        'abort_location' => $this->page_link);
    return $parser->get($this->template_path.'backend.email.dlg.htt', $data);
  } // dlgEMail()

  public function sendEMail() {
    global $dbProvider;
    global $dbContact;
    global $tools;
    global $dbProtocol;

    // checking if all fields are set...
    if ((!isset($_REQUEST[dbKITcontact::field_category]) && !isset($_REQUEST[dbKITcontact::field_newsletter])) || empty($_REQUEST[dbKITmail::field_subject]) || empty($_REQUEST[dbKITmail::field_html])) {
      // missing values...
      $this->setMessage(kit_msg_mail_incomplete);
      return $this->dlgEMail();
    }

    // get FROM email
    $where = array();
    $where[dbKITprovider::field_id] = intval($_REQUEST[dbKITprovider::field_id]);
    $provider = array();
    if (!$dbProvider->sqlSelectRecord($where, $provider)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
      return false;
    }
    $provider = $provider[0];

    $to_array = array();
    if (isset($_REQUEST[dbKITcontact::field_category])) {
      foreach ($_REQUEST[dbKITcontact::field_category] as $category) {
        // get contact ID's from selected categories...
        $SQL = sprintf("SELECT %s FROM %s WHERE %s='%s' AND ((%s LIKE '%s') OR (%s LIKE '%s,%%') OR (%s LIKE '%%,%s') OR (%s LIKE '%%%s,%%'))", dbKITcontact::field_id, $dbContact->getTableName(), dbKITcontact::field_status, dbKITcontact::status_active, dbKITcontact::field_category, $category, dbKITcontact::field_category, $category, dbKITcontact::field_category, $category, dbKITcontact::field_category, $category);
        $result = array();
        if (!$dbContact->sqlExec($SQL, $result)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
        foreach ($result as $id) {
          if (!in_array($id[dbKITcontact::field_id], $to_array))
            $to_array[] = $id[dbKITcontact::field_id];
        }
      }
    } // categories

    if (isset($_REQUEST[dbKITcontact::field_newsletter])) {
      foreach ($_REQUEST[dbKITcontact::field_newsletter] as $newsletter) {
        // get contact ID's from selected newsletter...
        $SQL = sprintf("SELECT %s FROM %s WHERE %s='%s' AND ((%s LIKE '%s') OR (%s LIKE '%s,%%') OR (%s LIKE '%%,%s') OR (%s LIKE '%%%s,%%'))", dbKITcontact::field_id, $dbContact->getTableName(), dbKITcontact::field_status, dbKITcontact::status_active, dbKITcontact::field_newsletter, $newsletter, dbKITcontact::field_newsletter, $newsletter, dbKITcontact::field_newsletter, $newsletter, dbKITcontact::field_newsletter, $newsletter);
        $result = array();
        if (!$dbContact->sqlExec($SQL, $result)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
        foreach ($result as $id) {
          if (!in_array($id[dbKITcontact::field_id], $to_array))
            $to_array[] = $id[dbKITcontact::field_id];
        }
      }
    } // newsletter

    isset($_REQUEST[dbKITmail::field_is_html]) ? $is_html = 1 : $is_html = 0;
    // insert new record
    $dbKITmail = new dbKITmail();
    $data = array();
    $data[dbKITmail::field_from_email] = $provider[dbKITprovider::field_email];
    $data[dbKITmail::field_from_name] = $provider[dbKITprovider::field_name];
    $data[dbKITmail::field_to_array] = implode(',', $to_array);
    $data[dbKITmail::field_is_html] = $is_html;
    $data[dbKITmail::field_subject] = $_REQUEST[dbKITmail::field_subject];
    $data[dbKITmail::field_html] = utf8_encode($_REQUEST[dbKITmail::field_html]);
    $data[dbKITmail::field_text] = strip_tags(utf8_encode($_REQUEST[dbKITmail::field_html]));
    $data[dbKITmail::field_status] = dbKITmail::status_active;
    $data[dbKITmail::field_update_by] = $tools->getDisplayName();
    $data[dbKITmail::field_update_when] = date('Y-m-d H:i:s');
    $mail_id = -1;
    if (!$dbKITmail->sqlInsertRecord($data, $mail_id)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKITmail->getError()));
      return false;
    }

    // starting protocol
    $transmitted = 0;
    // sending mails...
    $error = '';

    $kitMail = new kitMail($provider[dbKITprovider::field_id]);
    $receiver_array = array(
        $provider[dbKITprovider::field_email] => $provider[dbKITprovider::field_name]);
    (bool) $is_html ? $text = $data[dbKITmail::field_html] : $text = $data[dbKITmail::field_text];
    $bcc_array = array();
    foreach ($to_array as $to_id) {
      $bcc_array[] = $dbContact->getStandardEMailByID($to_id);
    }
    if ($kitMail->mail($data[dbKITmail::field_subject], $text, $provider[dbKITprovider::field_email], $provider[dbKITprovider::field_name], $receiver_array, (bool) $is_html, array(), $bcc_array)) {
      // success

      foreach ($to_array as $to_id) {
        $note = array();
        $note[dbKITprotocol::field_contact_id] = $to_id;
        $note[dbKITprotocol::field_type] = dbKITprotocol::type_email;
        $note[dbKITprotocol::field_memo] = $data[dbKITmail::field_subject];
        $note[dbKITprotocol::field_date] = date('Y-m-d H:i:s');
        $note[dbKITprotocol::field_status] = dbKITprotocol::status_active;
        $note[dbKITprotocol::field_members] = '';
        $note[dbKITprotocol::field_update_by] = $tools->getDisplayName();
        $note[dbKITprotocol::field_update_when] = date('Y-m-d H:i:s');
        if (!$dbProtocol->sqlInsertRecord($note)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProtocol->getError()));
          return false;
        }
        $transmitted++;
      }

    }
    else {
      // error
      $error .= sprintf('<p>[%s] %s</p>', $provider[dbKITprovider::field_email], $kitMail->getMailError());
    }
    $message = sprintf(kit_msg_mails_send_success, $transmitted);
    if (!empty($error)) {
      $message .= sprintf(kit_msg_mails_send_errors, 1, $error);
    }
    $this->setMessage($message);
    return $this->dlgStart();
  } // sendEMail()

  /**
   * Show the Start dialog of KeepInTouch
   * @return STR dialog
   */
  public function dlgStart() {
    require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.service.php');
    $service = new kitService();
    if ($this->isMessage())
      $service->setMessage($this->getMessage());
    $result = $service->action();
    if ($service->isError())
      $this->setError($service->getError());
    return $result;
  } // dlgStart()

  public function dlgAbout() {
    global $parser;
    $data = array(
        'version' => sprintf('%01.2f', $this->getVersion()),
        'img_url' => WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/logo-keep-in-touch.jpg',
        'signature' => WB_URL.'/modules/'.basename(dirname(__FILE__)).'/img/rh_schriftzug_small.png',
        'release_notes' => file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/CHANGELOG'));
    return $parser->get($this->template_path.'backend.about.htt', $data);
  } // dlgAbout()

} // class kitBackend

$kitBackend = new kitBackend();
$kitBackend->action();

?>