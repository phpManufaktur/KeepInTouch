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

class kitAdmin {

  const REQUEST_ACTION = 'kcaa';

  const ACTION_DEFAULT = 'def';
  const ACTION_DELETE_CATEGORY = 'delc';

  private static $error = '';
  private static $message = '';

  protected static $pageLink = '';
  protected static $templatePath = '';

  protected $lang = NULL;
  protected static $table_prefix = TABLE_PREFIX;


  /**
   * Constructor for kitAdmin
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
     * The action handler for the class kitCSVexport
     *
     * @return Ambigous <boolean, Ambigous, string, mixed>
     */
    public function action() {
        // kitAdmin expects that XSS prevention is done before calling action()!
        $action = (isset($_REQUEST[self::REQUEST_ACTION])) ? $_REQUEST[self::REQUEST_ACTION] : self::ACTION_DEFAULT;
        switch ($action):
        case self::ACTION_DELETE_CATEGORY:
            return $this->checkDeleteCategory();
        case self::ACTION_DEFAULT:
        default:
            return $this->dlgAdmin();
        endswitch;
    } // action()

    protected function dlgAdmin()
    {
        global $database;

        $SQL = "SELECT `array_identifier`, `array_value`, `array_type` FROM `".self::$table_prefix."mod_kit_contact_array_cfg` ".
            "WHERE (`array_type`='typeCategory' OR `array_type`='typeDistribution' OR `array_type`='typeNewsletter') AND ".
            "`array_status`='statusActive' ORDER BY `array_value` ASC";
        if (null == ($query = $database->query($SQL))) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
            return false;
        }

        $categories = array();
        while (false !== ($category = $query->fetchRow(MYSQL_ASSOC))) {
            $categories[] = array(
                'identifier' => $category['array_identifier'],
                'value' => $category['array_value'],
                'text' => sprintf('[%s] %s', $this->lang->translate($category['array_type']), $category['array_value'])
            );
        }

        $data = array(
            'form' => array(
                'name' => 'kit_csv_export',
                'action' => self::$pageLink
            ),
            'action' => array(
                'name' => self::REQUEST_ACTION,
                'value' => self::ACTION_DELETE_CATEGORY
            ),
            'message' => array(
                'active' => $this->isMessage() ? 1 : 0,
                'text' => $this->getMessage()
            ),
            'categories' => $categories

        );
        return $this->getTemplate('admin.cfg.dwoo', $data);
    }

    protected function checkDeleteCategory()
    {
        global $database;

        if (!isset($_REQUEST['category']) || ($_REQUEST['category'] == -1)) {
            $this->setMessage($this->lang->translate('<p>Please select a category!</p>'));
            return $this->dlgAdmin();
        }
        $category = $_REQUEST['category'];

        if (isset($_REQUEST['reg_status_inactive'])) {
            // don't delete contacts with active registration status
            $contact_table = self::$table_prefix."mod_kit_contact";
            $register_table = self::$table_prefix."mod_kit_register";
            $SQL = "SELECT $contact_table.`contact_id` FROM `$contact_table`,`$register_table` WHERE $contact_table.`contact_id`=$register_table.`contact_id` AND ((`contact_category_ids`='$category') OR ".
                "(`contact_category_ids` LIKE '$category,%') OR (`contact_category_ids` LIKE '%,$category') OR ".
                "(`contact_category_ids` LIKE '%,$category,%') OR (`contact_newsletter_ids`='$category') OR ".
                "(`contact_newsletter_ids` LIKE '$category,%') OR (`contact_newsletter_ids` LIKE '%,$category') OR ".
                "(`contact_newsletter_ids` LIKE '%,$category,%') OR (`contact_distribution_ids`='$category') OR ".
                "(`contact_distribution_ids` LIKE '$category,%') OR (`contact_distribution_ids` LIKE '%,$category') OR ".
                "(`contact_distribution_ids` LIKE '%,$category,%')) AND $register_table.`reg_status`!='statusActive'";
        }
        else {
            $SQL = "SELECT `contact_id` FROM `".self::$table_prefix."mod_kit_contact` WHERE ((`contact_category_ids`='$category') OR ".
                "(`contact_category_ids` LIKE '$category,%') OR (`contact_category_ids` LIKE '%,$category') OR ".
                "(`contact_category_ids` LIKE '%,$category,%') OR (`contact_newsletter_ids`='$category') OR ".
                "(`contact_newsletter_ids` LIKE '$category,%') OR (`contact_newsletter_ids` LIKE '%,$category') OR ".
                "(`contact_newsletter_ids` LIKE '%,$category,%') OR (`contact_distribution_ids`='$category') OR ".
                "(`contact_distribution_ids` LIKE '$category,%') OR (`contact_distribution_ids` LIKE '%,$category') OR ".
                "(`contact_distribution_ids` LIKE '%,$category,%'))";
        }

        if (null == ($query = $database->query($SQL))) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
            return false;
        }

        $count = 0;
        $contact_ids = array();

        while (false !== ($contact = $query->fetchRow(MYSQL_ASSOC))) {
            $contact_id = $contact['contact_id'];
            $contact_ids[] = $contact_id;

            $SQL = "DELETE FROM `".self::$table_prefix."mod_kit_contact_protocol` WHERE `contact_id`='$contact_id'";
            if (!$database->query($SQL)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
                return false;
            }

            $SQL = "DELETE FROM `".self::$table_prefix."mod_kit_contact_memos` WHERE `contact_id`='$contact_id'";
            if (!$database->query($SQL)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
                return false;
            }

            $SQL = "DELETE FROM `".self::$table_prefix."mod_kit_register` WHERE `contact_id`='$contact_id'";
            if (!$database->query($SQL)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
                return false;
            }

            $SQL = "DELETE FROM `".self::$table_prefix."mod_kit_contact` WHERE `contact_id`='$contact_id'";
            if (!$database->query($SQL)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
                return false;
            }
            $count++;
        }

        if ($count > 0) {
            $this->setMessage($this->lang->translate('<p>Totally {{ count }} records where successfull removed!</p>', array('count' => $count)));
        }
        else {
            $this->setMessage($this->lang->translate('<p>There are no records to delete!</p>'));
        }
        return $this->dlgAdmin();
    }

} // class kitAdmin