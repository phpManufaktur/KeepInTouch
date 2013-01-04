<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2010 - 2013
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

// initialize the KIT interface
require_once(WB_PATH.'/modules/kit/class.interface.php');

class kitCSVexport {

  const REQUEST_ACTION = 'kcea';
  const REQUEST_CSV_SEPARATOR = 'csep';
  const REQUEST_CSV_CHARSET = 'ccs';
  const REQUEST_CSV_EXPORT = 'exp';

  const ACTION_DEFAULT = 'def';
  const ACTION_EXPORT = 'exp';

  private static $error = '';
  private static $message = '';

  protected static $pageLink = '';
  protected static $templatePath = '';

  protected $lang = NULL;
  protected static $table_prefix = TABLE_PREFIX;

  protected static $temp_path = '';
  protected static $temp_url = '';

  /**
   * Constructor for kitCSVimport
   */
  public function __construct($pageLink) {
    global $I18n;

    $this->lang = $I18n;
    date_default_timezone_set(CFG_TIME_ZONE);

    // use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
    // set the pageLink
    self::$pageLink = $pageLink;
    // set the template path
    self::$templatePath = WB_PATH.'/modules/kit/templates/backend/';
    // set the TEMP path
    self::$temp_path = WB_PATH.'/temp/kit/export';
    // set the TEMP URL
    self::$temp_url = WB_URL.'/temp/kit/export';
  } // __construct()

  /**
   * Set self::$error to $error
   *
   * @param string $error
   */
  protected function setError($error) {
    self::$error = $error;
  } // setError()

  /**
   * Get Error from self::$error;
   *
   * @return string $this->error
   */
  public function getError() {
    return self::$error;
  } // getError()


  /**
   * Check if self::$error is empty
   *
   * @return boolean
   */
  public function isError() {
    return (bool) !empty(self::$error);
  } // isError


  /**
   * Set self::$message to $message
   *
   * @param string $message
   */
  protected function setMessage($message) {
    self::$message = $message;
  } // setMessage()


  /**
   * Get Message from self::$message;
   *
   * @return string self::$message
   */
  public function getMessage() {
    return self::$message;
  } // getMessage()


  /**
   * Check if self::$message is empty
   *
   * @return boolean
   */
  public function isMessage() {
    return (bool) !empty(self::$message);
  } // isMessage

  /**
   * Get the template, set the data and return the compiled result
   *
   * @param string $template the name of the template
   * @param array $data
   * @param boolean $trigger_error raise a trigger error on problems
   * @return boolean|Ambigous <string, mixed>
   */
  protected function getTemplate($template, $data, $trigger_error=false) {
    global $parser;
    // check if a custom template exists ...
    $load_template = (file_exists(self::$templatePath.'custom.'.$template)) ? self::$templatePath.'custom.'.$template : self::$templatePath.$template;
    try {
      $result = $parser->get($load_template, $data);
    }
    catch (Exception $e) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate('Error executing the template <b>{{ template }}</b>: {{ error }}',
          array('template' => basename($load_template), 'error' => $e->getMessage()))));
      if ($trigger_error)
        trigger_error($this->getError(), E_USER_ERROR);
      return false;
    }
    return $result;
  } // getTemplate()


  /**
   * The action handler for the class kitCSVexport
   *
   * @return Ambigous <boolean, Ambigous, string, mixed>
   */
  public function action() {
    // kitCSVimport expects that XSS prevention is done before calling action()!
    $action = (isset($_REQUEST[self::REQUEST_ACTION])) ? $_REQUEST[self::REQUEST_ACTION] : self::ACTION_DEFAULT;
    switch ($action):
      case self::ACTION_EXPORT:
        return $this->execExport();
      case self::ACTION_DEFAULT:
      default:
        return $this->dlgExport();
    endswitch;
  } // action()

  /**
   * Basic dialog for the export of CSV files
   *
   * @return Ambigous <boolean, Ambigous, string, mixed>
   */
  private function dlgExport() {
    global $database;

    // check if massmail is installed
    $SQL = "SHOW TABLE STATUS LIKE '".self::$table_prefix."mod_massmail_addresses'";
    $status = $database->get_one($SQL, MYSQL_ASSOC);
    $massmail_installed = !is_null($status);

    // check if Address Book is installed
    $SQL = "SHOW TABLE STATUS LIKE '".self::$table_prefix."mod_addressbook'";
    $status = $database->get_one($SQL, MYSQL_ASSOC);
    $addressbook_installed = !is_null($status);

    // check if Addresses (Aldus) is installed
    $SQL = "SHOW TABLE STATUS LIKE '".self::$table_prefix."mod_address_user'";
    $status = $database->get_one($SQL, MYSQL_ASSOC);
    $addresses_installed = !is_null($status);

    // check if User Extend is installed
    $SQL = "SHOW TABLE STATUS LIKE '".self::$table_prefix."user_extend'";
    $status = $database->get_one($SQL, MYSQL_ASSOC);
    $userextend_installed = !is_null($status);

    $data = array(
        'form' => array(
            'name' => 'kit_csv_export',
            'action' => self::$pageLink
            ),
        'action' => array(
            'name' => self::REQUEST_ACTION,
            'value' => self::ACTION_EXPORT
            ),
        'message' => array(
            'active' => $this->isMessage() ? 1 : 0,
            'text' => $this->getMessage()
            ),
        'csv' => array(
            'separator' => array(
                'name' => self::REQUEST_CSV_SEPARATOR
                ),
            'charset' => array(
                'name' => self::REQUEST_CSV_CHARSET
                ),
            'export' => array(
                'name' => self::REQUEST_CSV_EXPORT,
                'values' => array(
                    'addressbook' => array(
                        'value' => 'addressbook',
                        'enabled' => $addressbook_installed,
                        'text' => 'Address Book'
                        ),
                    'addresses' => array(
                        'value' => 'addresses',
                        'enabled' => $addresses_installed,
                        'text' => 'Addresses'
                        ),
                    'kit' => array(
                        'value' => 'kit',
                        'enabled' => 1,
                        'text' => 'KeepInTouch (KIT)'
                        ),
                    'massmail' => array(
                        'value' => 'massmail',
                        'enabled' => $massmail_installed,
                        'text' => 'Massmail'
                        ),
                    'user_extend' => array(
                        'value' => 'user_extend',
                        'enabled' => $userextend_installed,
                        'text' => 'User Extend'
                        )
                    )
                )
            )
        );
    return $this->getTemplate('export.csv.dwoo', $data);
  } // dlgExport()

  /**
   * Direct the export command to the associated export function
   *
   * @return Ambigous <Ambigous, boolean, string, mixed>|boolean|Ambigous <boolean, string>|string
   */
  private function execExport() {

    if (!isset($_REQUEST[self::REQUEST_CSV_EXPORT])) {
      $this->setMessage($this->lang->translate('<p>No data source selected!</p>'));
      return $this->dlgExport();
    }

    // check the temporary directory
    if (!file_exists(self::$temp_path)) {
      if (false === mkdir(self::$temp_path, 0777, true)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            '<p>Can\'t create the temporary directory for the CSV export!</p>')));
        return false;
      }
    }
    // remove the content of the temporary directory
    $handle = opendir(self::$temp_path);
    while (false !== ($data = readdir($handle)))
      if (!is_dir($data) && ($data != '.') && ($data != '..')) unlink(self::$temp_path."/$data");
    closedir($handle);

    switch ($_REQUEST[self::REQUEST_CSV_EXPORT]):
      case 'kit':
        return $this->exportKIT();
      case 'massmail':
        return $this->exportMassmail();
      case 'addressbook':
        return $this->exportAddressbook();
      case 'addresses':
        return $this->exportAddresses();
      case 'user_extend':
        return $this->exportUserExtend();
      default:
        $this->setMessage($this->lang->translate('<p>The CSV export for {{ export }} is not supported!</p>',
            array('export' => $_REQUEST[self::REQUEST_CSV_EXPORT])));
        return $this->dlgExport();
    endswitch;
  } // execExport

  /**
   * Export KeepInTouch (KIT) data records
   *
   * @return boolean|Ambigous <Ambigous, boolean, string, mixed>
   */
  private function exportKIT() {
    global $kitContactInterface;
    global $database;

    $SQL = "SELECT `contact_id` FROM `".self::$table_prefix."mod_kit_contact` WHERE `contact_status` != 'statusDeleted'";
    if (null == ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    if ($query->numRows() < 1) {
      // no data records to export
      $this->setMessage($this->lang->translate('<p>There are no data records to export for {{ export }}.</p>',
          array('export' => 'KeepInTouch (KIT)')));
      return $this->dlgExport();
    }

    $export_array = array();
    $i=0;
    $ansi = ($_REQUEST[self::REQUEST_CSV_CHARSET] == 'ANSI');

    while (false !== ($id = $query->fetchRow(MYSQL_ASSOC))) {
      $contact = array();
      if (!$kitContactInterface->getContact($id['contact_id'], $contact, false)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $kitContactInterface->getError()));
        return false;
      }
      $header = array();
      $values = array();
      foreach ($contact as $key => $value) {
        if ($i == 0)
          $header[] = $ansi ? utf8_decode($key) : $key;
        if (is_array($value))
          $value = implode(',', $value);
        $values[] = $ansi ? utf8_decode($value) : $value;
      }
      if ($i == 0)
        $export_array[] = $header;
      $export_array[] = $values;
      $i++;
    }

    $csv_file = self::$temp_path.'/'.date('Ymd-His').'-kit-export.csv';
    $csv_url = self::$temp_url.'/'.date('Ymd-His').'-kit-export.csv';

    if (false === ($handle = fopen($csv_file, 'w'))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csv_file)))));
      return false;
    }
    // write the CSV file
    foreach ($export_array as $fields) {
      if (!fputcsv($handle, $fields, $_REQUEST[self::REQUEST_CSV_SEPARATOR], '"')) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            'Error writing the CSV file {{ file }}!', array('file' => basename($csv_file)))));
        return false;
      }
    }
    // close the handle
    fclose($handle);

    $this->setMessage($this->lang->translate('<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>',
        array('count' => $i, 'url' => $csv_url, 'file' => basename($csv_file), 'export' => 'KeepInTouch (KIT)')));

    return $this->dlgExport();
  } // exportKIT()

  /**
   * Export Massmail data records
   *
   * @return boolean|Ambigous <Ambigous, boolean, string, mixed>
   */
  private function exportMassmail() {
    global $database;

    $ansi = ($_REQUEST[self::REQUEST_CSV_CHARSET] == 'ANSI');

    // get the massmail groups
    $SQL = "SELECT `group_id`, `group_name` FROM `".self::$table_prefix."mod_massmail_groups`";
    if (null === ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }
    $groups = array();
    while (false !== ($group = $query->fetchRow(MYSQL_ASSOC)))
      $groups[$group['group_id']] = ($ansi) ? utf8_decode($group['group_name']) : $group['group_name'];

    // get the massmail addresses
    $SQL = "SELECT * FROM `".self::$table_prefix."mod_massmail_addresses`";
    if (null === ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    if ($query->numRows() < 1) {
      // no data records to export
      $this->setMessage($this->lang->translate('<p>There are no data records to export for {{ export }}.</p>',
          array('export' => 'Massmail')));
      return $this->dlgExport();
    }

    $export_array = array();
    // set the CSV header
    $export_array[] = array('group_name', 'mail_to');
    $i=0;

    while (false !== ($data = $query->fetchRow(MYSQL_ASSOC))) {
      $export_array[] = array(
          $groups[$data['group_id']],
          ($ansi) ? utf8_decode($data['mail_to']) : $data['mail_to']
          );
      $i++;
    }

    $csv_file = self::$temp_path.'/'.date('Ymd-His').'-massmail-export.csv';
    $csv_url = self::$temp_url.'/'.date('Ymd-His').'-massmail-export.csv';

    if (false === ($handle = fopen($csv_file, 'w'))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csv_file)))));
      return false;
    }
    // write the CSV file
    foreach ($export_array as $fields) {
      if (!fputcsv($handle, $fields, $_REQUEST[self::REQUEST_CSV_SEPARATOR], '"')) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            'Error writing the CSV file {{ file }}!', array('file' => basename($csv_file)))));
        return false;
      }
    }
    // close the handle
    fclose($handle);

    $this->setMessage($this->lang->translate('<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>',
        array('count' => $i, 'url' => $csv_url, 'file' => basename($csv_file), 'export' => 'Massmail')));

    return $this->dlgExport();
  } // exportMassmail()

  /**
   * Export Address Book data records
   *
   * @return boolean|Ambigous <Ambigous, boolean, string, mixed>
   */
  private function exportAddressbook() {
    global $database;

    $SQL = "SELECT * FROM `".self::$table_prefix."mod_addressbook` WHERE `active`='1'";
    if (null === ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    if ($query->numRows() < 1) {
      // no data records to export
      $this->setMessage($this->lang->translate('<p>There are no data records to export for {{ export }}.</p>',
          array('export' => 'Address Book')));
      return $this->dlgExport();
    }

    $export_array = array();
    $i=0;
    $ansi = ($_REQUEST[self::REQUEST_CSV_CHARSET] == 'ANSI');

    while (false !== ($contact = $query->fetchRow(MYSQL_ASSOC))) {
      $header = array();
      $values = array();
      foreach ($contact as $key => $value) {
        if ($i == 0)
          $header[] = $ansi ? utf8_decode($key) : $key;
        if (is_array($value))
          $value = implode(',', $value);
        $values[] = $ansi ? utf8_decode($value) : $value;
      }
      if ($i == 0)
        $export_array[] = $header;
      $export_array[] = $values;
      $i++;
    }
    $csv_file = self::$temp_path.'/'.date('Ymd-His').'-addressbook-export.csv';
    $csv_url = self::$temp_url.'/'.date('Ymd-His').'-addressbook-export.csv';

    if (false === ($handle = fopen($csv_file, 'w'))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csv_file)))));
      return false;
    }
    // write the CSV file
    foreach ($export_array as $fields) {
      if (!fputcsv($handle, $fields, $_REQUEST[self::REQUEST_CSV_SEPARATOR], '"')) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            'Error writing the CSV file {{ file }}!', array('file' => basename($csv_file)))));
        return false;
      }
    }
    // close the handle
    fclose($handle);

    $this->setMessage($this->lang->translate('<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>',
        array('count' => $i, 'url' => $csv_url, 'file' => basename($csv_file), 'export' => 'Address Book')));

    return $this->dlgExport();
  } // exportAddressbook()

  /**
   * Export Addresses data records
   *
   * @return boolean|Ambigous <Ambigous, boolean, string, mixed>
   */
  private function exportAddresses() {
    global $database;

    $SQL = "SELECT * FROM`".self::$table_prefix."mod_address_user`";
    if (null === ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    if ($query->numRows() < 1) {
      // no data records to export
      $this->setMessage($this->lang->translate('<p>There are no data records to export for {{ export }}.</p>',
          array('export' => 'Addresses')));
      return $this->dlgExport();
    }

    $export_array = array();
    $i=0;
    $ansi = ($_REQUEST[self::REQUEST_CSV_CHARSET] == 'ANSI');

    while (false !== ($contact = $query->fetchRow(MYSQL_ASSOC))) {
      $header = array();
      $values = array();
      foreach ($contact as $key => $value) {
        if ($i == 0)
          $header[] = $ansi ? utf8_decode($key) : $key;
        if (is_array($value))
          $value = implode(',', $value);
        if ($key == 'info')
          // strip HTML from the 'info' field
          $value = strip_tags($value);
        $values[] = $ansi ? utf8_decode($value) : $value;
      }
      if ($i == 0)
        $export_array[] = $header;
      $export_array[] = $values;
      $i++;
    }
    $csv_file = self::$temp_path.'/'.date('Ymd-His').'-addresses-export.csv';
    $csv_url = self::$temp_url.'/'.date('Ymd-His').'-addresses-export.csv';

    if (false === ($handle = fopen($csv_file, 'w'))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csv_file)))));
      return false;
    }
    // write the CSV file
    foreach ($export_array as $fields) {
      if (!fputcsv($handle, $fields, $_REQUEST[self::REQUEST_CSV_SEPARATOR], '"')) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            'Error writing the CSV file {{ file }}!', array('file' => basename($csv_file)))));
        return false;
      }
    }
    // close the handle
    fclose($handle);

    $this->setMessage($this->lang->translate('<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>',
        array('count' => $i, 'url' => $csv_url, 'file' => basename($csv_file), 'export' => 'Addresses')));

    return $this->dlgExport();
  } // exportAddresses()

  /**
   * Export User Extend data records
   *
   * @return boolean|Ambigous <Ambigous, boolean, string, mixed>
   */
  private function exportUserExtend() {
    global $database;

    $SQL = "SELECT u.username,u.display_name,u.email,e.address,e.zipcode,e.city,e.country,e.phone,e.mobile,".
        "e.birthdate,e.website,e.freefield1,e.freefield2,e.freefield3,e.freefield4,e.freefield5,g.name FROM `".
        self::$table_prefix."user_extend` e JOIN `".self::$table_prefix."users` u ON e.user_id=u.user_id JOIN `".
        self::$table_prefix."groups` g ON g.group_id=u.group_id WHERE u.active='1' order by u.display_name";
    if (null === ($query = $database->query($SQL))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
      return false;
    }

    if ($query->numRows() < 1) {
      // no data records to export
      $this->setMessage($this->lang->translate('<p>There are no data records to export for {{ export }}.</p>',
          array('export' => 'User Extend')));
      return $this->dlgExport();
    }

    $export_array = array();
    $i=0;
    $ansi = ($_REQUEST[self::REQUEST_CSV_CHARSET] == 'ANSI');

    while (false !== ($contact = $query->fetchRow(MYSQL_ASSOC))) {
      $header = array();
      $values = array();
      foreach ($contact as $key => $value) {
        if ($i == 0)
          $header[] = $ansi ? utf8_decode($key) : $key;
        if (is_array($value))
          $value = implode(',', $value);
        if ($key == 'info')
          // strip HTML from the 'info' field
          $value = strip_tags($value);
        $values[] = $ansi ? utf8_decode($value) : $value;
      }
      if ($i == 0)
        $export_array[] = $header;
      $export_array[] = $values;
      $i++;
    }
    $csv_file = self::$temp_path.'/'.date('Ymd-His').'-user-extend-export.csv';
    $csv_url = self::$temp_url.'/'.date('Ymd-His').'-user-extend-export.csv';

    if (false === ($handle = fopen($csv_file, 'w'))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csv_file)))));
      return false;
    }
    // write the CSV file
    foreach ($export_array as $fields) {
      if (!fputcsv($handle, $fields, $_REQUEST[self::REQUEST_CSV_SEPARATOR], '"')) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            'Error writing the CSV file {{ file }}!', array('file' => basename($csv_file)))));
        return false;
      }
    }
    // close the handle
    fclose($handle);

    $this->setMessage($this->lang->translate('<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>',
        array('count' => $i, 'url' => $csv_url, 'file' => basename($csv_file), 'export' => 'User Extend')));

    return $this->dlgExport();
  } // exportUserExtend()

} // class kitCSVimport