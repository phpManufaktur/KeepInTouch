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

class kitCSVimport {

  const REQUEST_ACTION = 'kcia';
  const REQUEST_CSV_SEPARATOR = 'csep';
  const REQUEST_CSV_CHARSET = 'ccs';
  const REQUEST_CSV_FIELD = 'ccf_';
  const REQUEST_CSV_FIELD_COUNT = 'ccfc';
  const REQUEST_CSV_FILE = 'cf';
  const REQUEST_KIT_FIELDS = 'ckf';

  const ACTION_ASSIGN_FIELDS = 'caf';
  const ACTION_DEFAULT = 'def';
  const ACTION_CHECK_CSV = 'ccc';

  private static $error = '';
  private static $message = '';

  protected static $pageLink = '';
  protected static $templatePath = '';

  protected $lang = NULL;
  protected static $table_prefix = TABLE_PREFIX;

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
   * The action handler for the class kitCSVimport
   */
  public function action() {
    // kitCSVimport expects that XSS prevention is done before calling action()!
    $action = (isset($_REQUEST[self::REQUEST_ACTION])) ? $_REQUEST[self::REQUEST_ACTION] : self::ACTION_DEFAULT;
    switch ($action):
      case self::ACTION_ASSIGN_FIELDS:
        return $this->assignFields();
      case self::ACTION_CHECK_CSV:
        return $this->checkCSVfile();
      case self::ACTION_DEFAULT:
      default:
        return $this->dlgImport();
    endswitch;
  } // action()

  /**
   * Basic dialog for the import of CSV files
   *
   * @return Ambigous <boolean, Ambigous, string, mixed>
   */
  private function dlgImport() {
    // check the temporary directory
    $temp_path = WB_PATH.'/temp/kit/import';
    if (!file_exists($temp_path)) {
      if (false === mkdir($temp_path, 0777, true)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            '<p>Can\'t create the temporary directory for the CSV import!</p>')));
        return false;
      }
    }
    // remove the content of the temporary directory
    $handle = opendir($temp_path);
    while (false !== ($data = readdir($handle)))
      if (!is_dir($data) && ($data != '.') && ($data != '..')) unlink("$temp_path/$data");
    closedir($handle);

    $data = array(
        'form' => array(
            'name' => 'kit_csv_import',
            'action' => self::$pageLink
            ),
        'action' => array(
            'name' => self::REQUEST_ACTION,
            'value' => self::ACTION_CHECK_CSV
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
            'file' => array(
                'name' => self::REQUEST_CSV_FILE
                )
            )
        );
    return $this->getTemplate('import.csv.dwoo', $data);
  } // dlgImport()

  /**
   * Check the CSV file basics, get the first line with the field names and
   * start to process the CSV file
   *
   * @return Ambigous <boolean, Ambigous, string, mixed>|string
   */
  private function checkCSVfile() {
    global $kitContactInterface;

    $separator = $_REQUEST[self::REQUEST_CSV_SEPARATOR];
    $charset = $_REQUEST[self::REQUEST_CSV_CHARSET];

    if (!isset($_REQUEST[self::REQUEST_CSV_FILE])) {
      // we assume a file upload!
      if (!is_uploaded_file($_FILES[self::REQUEST_CSV_FILE]['tmp_name'])) {
        // no file uploaded
        $this->setMessage($this->lang->translate('<p>No CSV file uploaded!</p>'));
        return $this->dlgImport();
      }

      $csvFile = WB_PATH.'/temp/kit/import/'.date('Ymd-His').'-kit-csv-import.csv';
      if (!move_uploaded_file($_FILES[self::REQUEST_CSV_FILE]['tmp_name'], $csvFile)) {
        // can't move the uploaded file
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
            '<p>Can\'t move the uploaded file to the destination directory!</p>')));
        return false;
      }
    }
    else {
      // the file is already uploaded, use it!
      $csvFile = $_REQUEST[self::REQUEST_CSV_FILE];
    }

    if (false === ($handle = fopen($csvFile, "r"))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csvFile)))));
      return false;
    }
    // we need only the first line
    if (false === ($data = fgetcsv($handle, 1000, $separator))) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Error reading the CSV file {{ file }}!</p>', array('file' => basename($csvFile)))));
      return false;
    }
    $cols = count($data);
    if ($cols < 1) {
      // does not contain any columns!
      $this->setMessage($this->lang->translate('<p>The CSV file {{ file }} does not have any columns, please check the file!</p>',
          array('file' => basename($csvFile))));
      return $this->dlgImport();
    }
    if (empty($data[0])) {
      // the first row is empty!
      $this->setMessage($this->lang->translate('<p>The first row of the CSV file {{ file }} is empty, expected header informations!</p>',
          array('file' => basename($csvFile))));
      return $this->dlgImport();
    }

    // read the columns - beware of the charset!
    $columns_array = array();
    for ($i=0; $i < $cols; $i++) {
      $columns_array[] = array(
          'name' => self::REQUEST_CSV_FIELD.$i,
          'value' => ($charset == 'UTF-8') ? $data[$i] : utf8_encode($data[$i])
          );
    }
    // close the CSV file
    fclose($handle);

    // create the KIT fields
    $kit_fields = array('kit_id');
    $ignore = array('kit_email_retype', 'kit_zip_city', 'kit_password_retype', 'kit_password');
    foreach ($kitContactInterface->index_array as $field => $assign) {
      if (in_array($field, $ignore)) continue;
      $kit_fields[] = $field;
    }
    // sort alphabetical
    sort($kit_fields);

    $data = array(
        'form' => array(
            'name' => 'kit_csv_import',
            'action' => self::$pageLink
            ),
        'action' => array(
            'name' => self::REQUEST_ACTION,
            'value' => self::ACTION_ASSIGN_FIELDS
            ),
        'message' => array(
            'active' => $this->isMessage() ? 1 : 0,
            'text' => $this->getMessage()
            ),
        'import' => array(
            'fields' => array(
                'count' => array(
                    'name' => self::REQUEST_CSV_FIELD_COUNT,
                    'value' => count($columns_array)
                    ),
                'names' => $columns_array
                ),
            'file' => array(
                'name' => self::REQUEST_CSV_FILE,
                'value' => $csvFile
                ),
            'charset' => array(
                'name' => self::REQUEST_CSV_CHARSET,
                'value' => $charset
                ),
            'separator' => array(
                'name' => self::REQUEST_CSV_SEPARATOR,
                'value' => $separator
                )
            ),
        'kit' => array(
            'fields' => array(
                'array' => $kit_fields,
                'name' => self::REQUEST_KIT_FIELDS,
                'value' => implode(',', $kit_fields)
                )
            )
        );
    return $this->getTemplate('import.csv.assign.dwoo', $data);
  } // checkCSVfile()

  /**
   * In this dialog the user assign the CSV fields to the KIT fields and start
   * the import of the CSV file
   *
   * @return Ambigous <boolean, Ambigous, string, mixed>|string
   */
  private function assignFields() {
    global $dbContact;
    global $kitContactInterface;
    global $database;

    $field_count = (int) $_REQUEST[self::REQUEST_CSV_FIELD_COUNT];
    $charset = $_REQUEST[self::REQUEST_CSV_CHARSET];
    $separator = $_REQUEST[self::REQUEST_CSV_SEPARATOR];
    $csvFile = $_REQUEST[self::REQUEST_CSV_FILE];
    $kit_fields = explode(',', $_REQUEST[self::REQUEST_KIT_FIELDS]);
    $use_fields = array();

    $columns = array();
    for ($i=0; $i < $field_count; $i++) {
      $field_assign = (isset($_REQUEST[self::REQUEST_CSV_FIELD.$i])) ? $_REQUEST[self::REQUEST_CSV_FIELD.$i] : -1;
      if ($field_assign != '-1') {
        if (in_array($field_assign, $use_fields)) {
          // field is assigned twice!
          $this->setMessage($this->lang->translate('<p>The field <b>{{ field }}</b> was assigned twice, please assign each field only once!</p>',
              array('field' => $field_assign)));
          return $this->checkCSVfile();
        }
        $use_fields[] = $field_assign;
      }
      $columns[$i] = $field_assign;
    }
    if (!in_array('kit_email', $use_fields) && !in_array('kit_id', $use_fields)) {
      // need at minimun kit_email or kit_id
      $this->setMessage($this->lang->translate('<p>At minimum you must assign an email address to <b>kit_email</b> or assign a valid <b>ID</b> to <b>kit_id</b>. Can\'t start the import!</p>'));
      return $this->checkCSVfile();
    }

    // open the CSV file
    if (false === ($handle = fopen($csvFile, "r"))) {
      // can't open the file
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->lang->translate(
          '<p>Can\'t open the file {{ file }}!</p>', array('file' => basename($csvFile)))));
      return false;
    }

    // get previous messages to the $message var
    $message = $this->getMessage();

    $title_array = $dbContact->person_title_array;
    $academic_array = $dbContact->person_title_academic_array;
    $newsletter_array = $dbContact->newsletter_array;

    // now we loop through the CSV file and import the data
    $line = 0;
    $update_records = 0;
    $add_records = 0;

    while (false !== ($data = fgetcsv($handle, 1000, $separator))) {
      $line++;
      if ($line == 1) continue;
      if ($field_count != count($data)) {
        // number of columns differ from the field count!
        $message .= $this->lang->translate('<p>Skipped line {{ line }} because the number of columns differ from the CSV definition!</p>',
            array('line' => $line));
        continue;
      }
      // step through the columns
      $contact = array();
      for ($i=0; $i < $field_count; $i++) {
        switch ($columns[$i]):
          case -1:
            // continue, if column is not needed
            break;
          case 'kit_title':
            // set the person title
            $title = ($charset == 'ANSI') ? utf8_encode($data[$i]) : $data[$i];
            $contact[$columns[$i]] = (false !== array_search($title, $title_array)) ? array_search($title, $title_array) : dbKITcontact::person_title_mister;
            break;
          case 'kit_title_academic':
            // set the academic title
            $academic = ($charset == 'ANSI') ? utf8_encode($data[$i]) : $data[$i];
            $contact[$columns[$i]] = (false !== array_search($academic, $academic_array)) ? array_search($academic, $academic_array) : dbKITcontact::person_title_academic_none;
            break;
          case 'kit_address_type':
            // set the address type
            $type = ($charset == 'ANSI') ? utf8_encode($data[$i]) : $data[$i];
            $contact[$columns[$i]] = (strtolower($type) == 'business') ? 'business' : 'private';
            break;
          case 'kit_newsletter':
            // check for newsletters
            $nl = explode(',', $data[$i]);
            $newsletter = array();
            foreach ($nl as $news) {
              $news = trim($news);
              if (empty($news)) continue;
              if (false !== ($add = array_search($news, $newsletter_array)))
                $newsletter[] = $add;
              else
                $message .= $this->lang->translate('<p>Skipped invalid entry <b>{{ newsletter }}</b> for <i>kit_newsletter</i> in line <i>{{ line }}</i>.</p>',
                    array('newsletter' => $news, 'line' => $line));
            }
            $contact[$columns[$i]] = implode(',', $newsletter);
            break;
          case 'kit_birthday':
            // set the contact birthday
            if (empty($data[$i])) {
              $contact[$columns[$i]] = '';
              break;
            }
            if (false === ($date = strtotime($data[$i]))) {
              $message .= $this->lang->translate('<p>Skipped invalid date <b>{{ date }}</b> for <i>kit_birthday</i> in line <i>{{ line }}</i>.</p>',
                  array('date' => $data[$i], 'line' => $line));
              $contact[$columns[$i]] = '';
              break;
            }
            $contact[$columns[$i]] = date('Y-m-d H:i:s', $date);
            break;
          default:
            // all other KIT fields
            $contact[$columns[$i]] = ($charset == 'ANSI') ? utf8_encode($data[$i]) : $data[$i];
            break;
        endswitch;
      } // for columns

      $kit_id = -1;
      $status = dbKITcontact::status_active;
      $register = array();

      if (isset($contact['kit_id'])) {
        // update an existing KIT record
        if (null === ($kit_id = $database->get_one("SELECT `contact_id` FROM `".self::$table_prefix."mod_kit_contact` WHERE `contact_id`='{$contact['kit_id']}'", MYSQL_ASSOC))) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
          return false;
        }
        if ($kit_id != $contact['kit_id']) {
          $message .= $this->lang->translate('<p>Skipped line <i>{{ line }}</i>, the KIT ID <b>{{ kit_id }}</b> does not exists!</p>',
              array('line' => $line, 'kit_id' => $contact['kit_id']));
        }
        elseif (!$kitContactInterface->updateContact($contact['kit_id'], $contact)) {
          if ($kitContactInterface->isError()) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $kitContactInterface->getError()));
            return false;
          }
          $message .= $kitContactInterface->getMessage();
        }
        else
          $update_records++;
      }
      elseif ($kitContactInterface->isEMailRegistered($contact['kit_email'], $kit_id, $status)) {
        // KIT contact already exists, update the record
        if (!$kitContactInterface->updateContact($kit_id, $contact)) {
          if ($kitContactInterface->isError()) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $kitContactInterface->getError()));
            return false;
          }
          $message .= $kitContactInterface->getMessage();
        }
        else
          $update_records++;
      }
      else {
        // add a new record to KIT
        if (!$kitContactInterface->addContact($contact, $kit_id, $register)) {
          if ($kitContactInterface->isError()) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $kitContactInterface->getError()));
            return false;
          }
          $message .= $kitContactInterface->getMessage();
        }
        else
          $add_records++;
      }
    } // while

        // close the CSV file
    fclose($handle);

    $message .= $this->lang->translate('<p>Added <b>{{ add }}</b> records, updated <b>{{ update }}</b> records.</p>',
        array('add' => $add_records, 'update' => $update_records));

    $this->setMessage($message);
    return $this->dlgImport();
  } // assignFields()



} // class kitCSVimport