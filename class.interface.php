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

global $kitContactInterface;

if (!is_object($kitContactInterface))
  $kitContactInterface = new kitContactInterface();

class kitContactInterface {

  const kit_address_type = 'kit_address_type';
  const kit_birthday = 'kit_birthday';
  const kit_categories = 'kit_categories'; // reines Info-Feld - wird nicht in die Feldliste aufgenommen!
  const kit_city = 'kit_city';
  const kit_company = 'kit_company';
  const kit_contact_language = 'kit_contact_language';
  const kit_contact_since = 'kit_contact_since';
  const kit_country = 'kit_country';
  const kit_department = 'kit_department';
  const kit_distribution = 'kit_distribution';
  const kit_email = 'kit_email';
  const kit_email_retype = 'kit_email_retype';
  const kit_fax = 'kit_fax';
  const kit_first_name = 'kit_first_name';
  const kit_free_field_1 = 'kit_free_field_1';
  const kit_free_field_2 = 'kit_free_field_2';
  const kit_free_field_3 = 'kit_free_field_3';
  const kit_free_field_4 = 'kit_free_field_4';
  const kit_free_field_5 = 'kit_free_field_5';
  const kit_free_note_1 = 'kit_free_note_1';
  const kit_free_note_2 = 'kit_free_note_2';
  const kit_id = 'kit_id';
  const kit_identifier = 'kit_identifier';
  const kit_intern = 'kit_intern'; // interner Verteiler, für Gruppenzuordnungen von Shops etc.
  const kit_last_name = 'kit_last_name';
  const kit_newsletter = 'kit_newsletter';
  const kit_newsletter_subscribe = 'kit_newsletter_subscribe'; // BOOL - fuer die An- und Abmeldung von Newslettern
  const kit_note = 'kit_note';
  const kit_password = 'kit_password';
  const kit_password_retype = 'kit_password_retype';
  const kit_phone = 'kit_phone';
  const kit_phone_mobile = 'kit_phone_mobile';
  const kit_status = 'kit_status';
  const kit_street = 'kit_street';
  const kit_title = 'kit_title';
  const kit_title_academic = 'kit_title_academic';
  const kit_zip = 'kit_zip';
  const kit_zip_city = 'kit_zip_city';

  public $field_array = array(
      self::kit_address_type => kit_label_address_type,
      self::kit_birthday => kit_label_birthday,
      self::kit_city => kit_label_address_city,
      self::kit_company => kit_label_company_name,
      self::kit_contact_language => kit_label_contact_language,
      self::kit_contact_since => kit_label_contact_since,
      self::kit_country => kit_label_country,
      self::kit_department => kit_label_company_department,
      self::kit_distribution => kit_label_distribution,
      self::kit_email => kit_label_contact_email,
      self::kit_email_retype => kit_label_contact_email_retype,
      self::kit_fax => kit_label_contact_fax,
      self::kit_first_name => kit_label_person_first_name,
      self::kit_free_field_1 => kit_label_free_field_1,
      self::kit_free_field_2 => kit_label_free_field_2,
      self::kit_free_field_3 => kit_label_free_field_3,
      self::kit_free_field_4 => kit_label_free_field_4,
      self::kit_free_field_5 => kit_label_free_field_5,
      self::kit_free_note_1 => kit_label_free_note_1,
      self::kit_free_note_2 => kit_label_free_note_2,
      self::kit_identifier => kit_label_contact_identifier,
      self::kit_intern => kit_label_categories,
      self::kit_last_name => kit_label_person_last_name,
      self::kit_newsletter => kit_label_newsletter,
      self::kit_note => kit_label_contact_note,
      self::kit_password => kit_label_password,
      self::kit_password_retype => kit_label_password_retype,
      self::kit_phone => kit_label_contact_phone,
      self::kit_phone_mobile => kit_label_contact_phone_mobile,
      self::kit_status => kit_label_status,
      self::kit_street => kit_label_address_street,
      self::kit_title => kit_label_person_title,
      self::kit_title_academic => kit_label_person_title_academic,
      self::kit_zip => kit_label_address_zip,
      self::kit_zip_city => kit_label_address_zip_city,
      );

  public $field_assign = array(
      self::kit_birthday => dbKITcontact::field_birthday,
      self::kit_city => dbKITcontactAddress::field_city,
      self::kit_company => dbKITcontact::field_company_name,
      self::kit_contact_language => dbKITcontact::field_contact_language,
      self::kit_contact_since => dbKITcontact::field_contact_since,
      self::kit_country => dbKITcontactAddress::field_country,
      self::kit_department => dbKITcontact::field_company_department,
      self::kit_distribution => dbKITcontact::field_distribution,
      self::kit_email => dbKITcontact::field_email,
      self::kit_first_name => dbKITcontact::field_person_first_name,
      self::kit_free_field_1 => dbKITcontact::field_free_1,
      self::kit_free_field_2 => dbKITcontact::field_free_2,
      self::kit_free_field_3 => dbKITcontact::field_free_3,
      self::kit_free_field_4 => dbKITcontact::field_free_4,
      self::kit_free_field_5 => dbKITcontact::field_free_5,
      self::kit_free_note_1 => dbKITcontact::field_free_note_1,
      self::kit_free_note_2 => dbKITcontact::field_free_note_2,
      self::kit_id => dbKITcontact::field_id,
      self::kit_identifier => dbKITcontact::field_contact_identifier,
      self::kit_intern => dbKITcontact::field_category,
      self::kit_last_name => dbKITcontact::field_person_last_name,
      self::kit_newsletter => dbKITcontact::field_newsletter,
      self::kit_note => dbKITcontact::field_contact_note,
      self::kit_status => dbKITcontact::field_status,
      self::kit_street => dbKITcontactAddress::field_street,
      self::kit_title => dbKITcontact::field_person_title,
      self::kit_title_academic => dbKITcontact::field_person_title_academic,
      self::kit_zip => dbKITcontactAddress::field_zip,
      );

  public $index_array = array(
      self::kit_title => 10,
      self::kit_title_academic => 11,
      self::kit_first_name => 12,
      self::kit_last_name => 13,
      self::kit_company => 14,
      self::kit_country => 26,
      self::kit_department => 15,
      self::kit_address_type => 16,
      self::kit_street => 17,
      self::kit_zip => 18,
      self::kit_city => 19,
      self::kit_zip_city => 20,
      self::kit_phone => 21,
      self::kit_phone_mobile => 22,
      self::kit_fax => 23,
      self::kit_email => 24,
      self::kit_email_retype => 29,
      self::kit_newsletter => 25,
      self::kit_password => 27,
      self::kit_password_retype => 28,
      self::kit_birthday => 30,
      self::kit_free_field_1 => 31,
      self::kit_free_field_2 => 32,
      self::kit_free_field_3 => 33,
      self::kit_free_field_4 => 34,
      self::kit_free_field_5 => 35,
      self::kit_free_note_1 => 36,
      self::kit_free_note_2 => 37,
      self::kit_contact_language => 38 // last added field
  );

  const address_type_private = 'private';
  const address_type_business = 'business';

  public $address_type_array = array(
      self::address_type_private => kit_label_address_type_private,
      self::address_type_business => kit_label_address_type_business);

  private $error = '';
  private $message = '';

  // SESSION Konstanten
  const session_kit_key = 'kit_key';
  const session_must_change_pwd = 'kit_mcp';
  const session_kit_contact_id = 'kit_cid';
  const session_kit_register_id = 'kit_rid';
  const session_kit_aid = self::session_kit_register_id;

  // KATEGORIEN
  const category_type_intern = dbKITcontactArrayCfg::type_category;
  const category_type_newsletter = dbKITcontactArrayCfg::type_newsletter;
  const category_type_distribution = dbKITcontactArrayCfg::type_distribution;

  // CONFIGURATION constants
  const CONFIG_PASSWORD_MINIMUM_LENGHT = dbKITcfg::cfgMinPwdLen;
  const CONFIG_MAX_INVALID_LOGINS = dbKITcfg::cfgMaxInvalidLogin;

  protected static $table_prefix = TABLE_PREFIX;


  public function __construct() {
    // use another table prefix?
    if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json')) {
      $config = json_decode(file_get_contents(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/config.json'), true);
      if (isset($config['table_prefix']))
        self::$table_prefix = $config['table_prefix'];
    }
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
   * Return the specified KIT configuration value
   *
   * @param string $value - any kitContactInterface constant CONFIG_xxx
   * @return mixed value in the desired format of the config value
   */
  public function getConfigurationValue($value) {
    global $dbCfg;
    return $dbCfg->getValue($value);
  } // getConfigurationValue()

  /**
   * Check if the user is authenticated
   *
   * @return BOOL
   */
  public function isAuthenticated() {
    $result = (isset($_SESSION[self::session_kit_register_id]) && isset($_SESSION[self::session_kit_key]) && isset($_SESSION[self::session_kit_contact_id])) ? true : false;
    return $result;
  } // isAuthenticated()

  /**
   * LOGOUT the user and unsets all specific $_SESSION vars
   * @return BOOL
   */
  public function logout() {
    unset($_SESSION[self::session_kit_register_id]);
    unset($_SESSION[self::session_kit_key]);
    unset($_SESSION[self::session_kit_contact_id]);
    unset($_SESSION[self::session_must_change_pwd]);
    return true;
  } // logout()

  /**
   * Gibt den PFAD auf das temporaere Verzeichnis fuer KIT Daten zurueck
   */
  public function getTempDir() {
    $tmp = WB_PATH.MEDIA_DIRECTORY.'/kit_temp/';
    if (!file_exists($tmp)) {
      if (!mkdir($tmp, 0777, true))
        return false;
    }
    return $tmp;
  } // getTempDir()

  /**
   * Return the KIT Contact ID if the user is logged in, otherwise -1
   *
   * @return INT
   */
  public function getContactID() {
    $contact_id = (isset($_SESSION[self::session_kit_contact_id])) ? $_SESSION[self::session_kit_contact_id] : -1;
    return $contact_id;
  }

  /**
   * Return the person title array (Mr., Mrs., ...) for usage in kitForm
   * @param REFERENCE ARRAY $title_array
   * @return BOOL
   */
  public function getFormPersonTitleArray(&$title_array = array()) {
    global $dbContact;
    $titles = $dbContact->person_title_array;
    $title_array = array();
    foreach ($titles as $key => $value) {
      $title_array[$key] = array(
          'value' => $key,
          'text' => $value,
          'checked' => ($key == dbKITcontact::person_title_mister) ? 1 : 0);
    }
    return true;
  } // getFormPersonTitleArray()

  /**
   * Return the person acadamic title array (Dr., Prof., ...) for usage in kitForm
   * @param REFERENCE ARRAY $academic_array
   * @return BOOL
   */
  public function getFormPersonTitleAcademicArray(&$academic_array = array()) {
    global $dbContact;
    $academics = $dbContact->person_title_academic_array;
    $academic_array = array();
    foreach ($academics as $key => $value) {
      $academic_array[$key] = array(
          'value' => $key,
          'text' => $value,
          'checked' => ($key == dbKITcontact::person_title_academic_none) ? 1 : 0);
    }
    return true;
  } // getFormPersonTitleAcademicArray()

  /**
   * Return the address type array (private, company, ...) for usage in kitForm
   * @param REFERENCE ARRAY $address_type_array
   * @return BOOL
   */
  public function getFormAddressTypeArray(&$address_type_array = array()) {
    global $dbContact;
    $address_types = $this->address_type_array;
    $address_type_array = array();
    foreach ($address_types as $key => $value) {
      $address_type_array[$key] = array(
          'value' => $key,
          'text' => $value,
          'checked' => ($key == self::address_type_private) ? 1 : 0);
    }
    return true;
  } // getFormAddressTypeArray()

  /**
   * Return the available newsletters as array for usage with kitForm
   *
   * @param REFERENCE ARRAY $newsletter_array
   * @return BOOL
   */
  public function getFormNewsletterArray(&$newsletter_array = array()) {
    global $dbContact;

    // check if language markers are in use
    $use_language_marker = $this->getConfigurationValue(dbKITcfg::cfgNewsletterLanguageMarkers);

    // get the complete newsletter array
    $newsletters = $dbContact->newsletter_array;
    // the newsletter array to return
    $newsletter_array = array();

    foreach ($newsletters as $key => $value) {
      if ($use_language_marker) {
        // look for matches of language markers, i.e. "[DE]" or "[EN]"
        preg_match('/\[[[A-Z,a-z][A-Z,a-z]\]/', $value, $match);
        if (isset($match[0])) {
          $lang = $match[0];
          $lang = str_replace(array('[',']',' '), '', $lang);
          if (strtoupper($lang) == LANGUAGE) {
            // language match, remove the marker an spaces
            $value = str_replace($match, '', $value);
            $value = trim($value);
          }
          // language of the newsletter does not match, continue
          else continue;
        }
      }
      $newsletter_array[$key] = array(
          'value' => $key,
          'text' => $value,
          'checked' => 0);
    }
    return true;
  } // getFormNewsletterArray()

  /**
   * Ueberprueft ob die E-Mail Adresse $email_address als primaere E-Mail Adresse
   * registriert ist und gibt bei einem Treffer die KIT Contact ID sowie den Status
   * des Adressdatensatz zurueck.
   *
   * @param STR $email_address
   * @param REFERENCE INT $contact_id
   * @param REFERENCE STR $status
   * @return BOOL
   */
  public function isEMailRegistered($email_address, &$contact_id = -1, &$status = dbKITcontact::status_active) {
    global $dbContact;
    $email_address = strtolower($email_address);
    $SQL = sprintf("SELECT %s,%s,%s,%s FROM %s WHERE (%s LIKE '%%|%s' OR %s LIKE '%%|%s%%') AND %s!='%s'", dbKITcontact::field_id, dbKITcontact::field_email, dbKITcontact::field_email_standard, dbKITcontact::field_status, $dbContact->getTableName(), dbKITcontact::field_email, $email_address, dbKITcontact::field_email, $email_address, dbKITcontact::field_status, dbKITcontact::status_deleted);
    $result = array();
    if (!$dbContact->sqlExec($SQL, $result)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    if (count($result) > 0) {
      // Treffer
      foreach ($result as $address) {
        // nach Standard Adresse in den Treffern suchen
        $email_array = explode(';', $address[dbKITcontact::field_email]);
        $i = 0;
        foreach ($email_array as $email_item) {
          list($email_type, $email_addr) = explode('|', $email_item);
          if (($email_address == $email_addr) && ($address[dbKITcontact::field_email_standard] == $i)) {
            $contact_id = $address[dbKITcontact::field_id];
            $status = $address[dbKITcontact::field_status];
            return true;
          }
          $i++;
        }
      }
    }
    // kein Treffer
    return false;
  } // isEMailRegistered()

  /**
   * Aktualisiert den angegebenen Kontakt $contact_id und gibt eine Referenz
   * auf ein Array mit den gespeicherten Kontaktdaten zurueck
   *
   * @param INT contact_id
   * @param REFERENCE ARRAY $contact_array
   * @return BOOL
   */
  public function updateContact($contact_id, &$contact_array = array()) {
    global $dbContact;
    global $dbContactAddress;
    global $dbRegister;

    $contact = array();
    if ($dbContact->getContactByID($contact_id, $contact)) {
      // Adresse existiert, Daten vergleichen
      $address = $dbContactAddress->getFields();
      $address[dbKITcontactAddress::field_id] = -1;
      $address[dbKITcontactAddress::field_contact_id] = -1;
      if ($contact[dbKITcontact::field_address_standard] > 0) {
        if (!$dbContact->getAddressByID($contact[dbKITcontact::field_address_standard], $address) && $dbContact->isError()) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
      }
      $contact_changed = false;
      $address_changed = false;
      foreach ($this->field_array as $field => $label) {
        switch ($field) :
          case self::kit_title:
          case self::kit_title_academic:
            if (isset($contact_array[$field]) && ($contact_array[$field] !== $contact[$this->field_assign[$field]])) {
              $contact[$this->field_assign[$field]] = $contact_array[$field];
              $contact_changed = true;
            }
            break;
          case self::kit_contact_language:
            if (isset($contact_array[$field])) {
              $lg = strtolower(trim($contact_array[$field]));
              if ($lg !== $contact[$this->field_assign[$field]]) {
                $contact[$this->field_assign[$field]] = $lg;
                $contact_changed = true;
              }
            }
            break;
          case self::kit_first_name:
          case self::kit_last_name:
          case self::kit_company:
          case self::kit_department:
          case self::kit_birthday:
            if (isset($contact_array[$field]) && !empty($contact_array[$field]) && ($contact_array[$field] != $contact[$this->field_assign[$field]])) {
              $contact[$this->field_assign[$field]] = $contact_array[$field];
              $contact_changed = true;
            }
            break;
          case self::kit_street:
          case self::kit_city:
          case self::kit_zip:
          case self::kit_country:
            if (isset($contact_array[$field]) && !empty($contact_array[$field]) && ($contact_array[$field] !== $address[$this->field_assign[$field]])) {
              $address[$this->field_assign[$field]] = $contact_array[$field];
              $address_changed = true;
            }
            break;
          case self::kit_phone:
          case self::kit_phone_mobile:
          case self::kit_fax:
            if (isset($contact_array[$field]) && !empty($contact_array[$field])) {
              $new_phone = trim($contact_array[$field]);
              $new_phone = str_replace(' ', '', $new_phone);
              if (strpos($contact[dbKITcontact::field_phone], $new_phone) === false) {
                // Telefonnummer existiert noch nicht, hinzufuegen
                switch ($field) :
                  case self::kit_phone:
                    $type = dbKITcontact::phone_phone;
                    break;
                  case self::kit_phone_mobile:
                    $type = dbKITcontact::phone_handy;
                    break;
                  case self::kit_fax:
                    $type = dbKITcontact::phone_fax;
                    break;
                  default:
                    $type = dbKITcontact::phone_phone;
                    break;
                endswitch;
                $phone = $contact[dbKITcontact::field_phone];
                if (!empty($phone))
                  $phone .= ';';
                $phone .= sprintf('%s|%s', $type, $new_phone);
                $contact[dbKITcontact::field_phone] = $phone;
                $contact_changed = true;
              }
            }
            break;
          case self::kit_newsletter:
            if (isset($contact_array[$field])) {
              if ($contact_array[$field] != $contact[dbKITcontact::field_newsletter]) {
                $contact[dbKITcontact::field_newsletter] = $contact_array[$field];
                $contact_changed = true;
                // Newsletter auch in dbKITregister aktualisieren!
                $where = array(
                    dbKITregister::field_contact_id => $contact_id,
                    dbKITregister::field_status => dbKITregister::status_active);
                $data = array(
                    dbKITregister::field_newsletter => $contact_array[$field],
                    dbKITregister::field_update_by => 'INTERFACE',
                    dbKITregister::field_update_when => date('Y-m-d H:i:s'));
                if (!$dbRegister->sqlUpdateRecord($data, $where)) {
                  $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
                  return false;
                }
              }
            }
            break;
          case self::kit_distribution:
            if (isset($contact_array[$field]))  {
              if ($contact_array[$field] != $contact[dbKITcontact::field_distribution]) {
                $contact[dbKITcontact::field_distribution] = $contact_array[$field];
                $contact_changed = true;
              }
            }
            break;
        endswitch;
      }
      if ($address_changed) {
        if ($address[dbKITcontactAddress::field_id] == -1) {
          // es existiert noch kein Adressdatensatz, Kontakt ID festhalten
          $address[dbKITcontactAddress::field_contact_id] = $contact_id;
          // Adresstyp festlegen
          $address[dbKITcontactAddress::field_type] = ((isset($contact_array[self::kit_address_type])) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private;
          $address[dbKITcontactAddress::field_status] = dbKITcontactAddress::status_active;
          $address[dbKITcontactAddress::field_update_by] = 'INTERFACE';
          if (!isset($address[dbKITcontactAddress::field_country]))
            $address[dbKITcontactAddress::field_country] = 'DE';
          // Adresse anlegen
          $address_id = -1;
          if (!$dbContactAddress->sqlInsertRecord($address, $address_id)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
            return false;
          }
          // Kontakt Datensatz aktualisieren
          $address_ids = explode(',', $contact[dbKITcontact::field_address]);
          if (false !== ($key = array_search('-1', $address_ids)))
            unset($address_ids[$key]);
          $address_ids[] = $address_id;
          $contact[dbKITcontact::field_address] = implode(',', $address_ids);
          $contact[dbKITcontact::field_address_standard] = ($contact[dbKITcontact::field_address_standard] > 0) ? $contact[dbKITcontact::field_address_standard] : $address_id;
          $contact_changed = true;
          // Protokoll aktualisieren
          $dbContact->addSystemNotice($contact_id, sprintf(kit_protocol_ki_address_added, $address[dbKITcontactAddress::field_street], $address[dbKITcontactAddress::field_zip], $address[dbKITcontactAddress::field_city]));
        }
        else {
          // Adressdatensatz aktualisieren
          // Adresstyp festlegen
          $address[dbKITcontactAddress::field_type] = ((isset($contact_array[self::kit_address_type])) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private;
          $address[dbKITcontactAddress::field_status] = dbKITcontactAddress::status_active;
          $address[dbKITcontactAddress::field_update_by] = 'INTERFACE';
          if (!isset($address[dbKITcontactAddress::field_country]))
            $address[dbKITcontactAddress::field_country] = 'DE';
          $where = array(
              dbKITcontactAddress::field_id => $address[dbKITcontactAddress::field_id]);
          if (!$dbContactAddress->sqlUpdateRecord($address, $where)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
            return false;
          }
          $dbContact->addSystemNotice($contact_id, sprintf(kit_protocol_ki_address_updated, $address[dbKITcontactAddress::field_street], $address[dbKITcontactAddress::field_zip], $address[dbKITcontactAddress::field_city]));
        }
      }
      if ($contact_changed) {
        $where = array(dbKITcontact::field_id => $contact_id);
        if (!$dbContact->sqlUpdateRecord($contact, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
          return false;
        }
        $dbContact->addSystemNotice($contact_id, kit_protocol_ki_contact_updated);
      }
      return true;
    }
    elseif ($dbContact->isError()) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    else {
      // Adresse existiert nicht
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact_id)));
      return false;
    }
  } // updateContact()

  /**
   * Fuegt eine Notiz zu der angegebenen Contact ID hinzu
   *
   * @param INT $contact_id
   * @param STR $notice
   * @return BOOL
   */
  public function addNotice($contact_id, $notice) {
    global $dbContact;
    $dbContact->addSystemNotice($contact_id, $notice);
    return true;
  } // addNotice()

  /**
   * Fuegt einen neuen Kontakt Datensatz ein
   *
   * @param array $contact_array
   * @param integer reference $contact_id
   * @param array reference $register_array
   * @return BOOL
   * @todo Die Laenderkennung wird momentan immer auf 'DE' gesetzt!
   */
  public function addContact($contact_array = array(), &$contact_id = -1, &$register_array = array()) {
    global $dbContact;
    global $dbContactAddress;
    global $dbRegister;
    global $tools;
    global $dbCfg;

    if (!isset($contact_array[self::kit_email])) {
      // es wurde keine E-Mail Adresse uebergeben
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_email_missing));
      return false;
    }
    $contact_email = strtolower($contact_array[self::kit_email]);
    if ($this->isEMailRegistered($contact_email, $contact_id)) {
      // es existiert bereits ein Datensatz fuer die angegebene E-Mail Adresse
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_record_for_email_exists, $contact_id, $contact_email)));
      return false;
    }
    // neuen Datensatz anlegen
    $contact = $dbContact->getFields();
    foreach ($contact as $key => $value) {
      switch ($key) :
        case dbKITcontact::field_address:
        case dbKITcontact::field_address_standard:
          $contact[$key] = -1;
          break;
        case dbKITcontact::field_access:
          $contact[$key] = dbKITcontact::access_internal;
          break;
        case dbKITcontact::field_person_first_name:
        case dbKITcontact::field_person_last_name:
        case dbKITcontact::field_company_name:
        case dbKITcontact::field_company_department:
        case dbKITcontact::field_free_1:
        case dbKITcontact::field_free_2:
        case dbKITcontact::field_free_3:
        case dbKITcontact::field_free_4:
        case dbKITcontact::field_free_5:
        case dbKITcontact::field_free_note_1:
        case dbKITcontact::field_free_note_2:
        case dbKITcontact::field_birthday:
          $contact[$key] = (isset($contact_array[array_search($key, $this->field_assign)])) ? $contact_array[array_search($key, $this->field_assign)] : '';
          break;
        case dbKITcontact::field_company_title:
          $contact[$key] = dbKITcontact::company_title_none;
          break;
        case dbKITcontact::field_contact_identifier:
          $contact[$key] = $contact_email;
          break;
        case dbKITcontact::field_contact_since:
          $contact[$key] = date('Y-m-d H:i:s');
          break;
        case dbKITcontact::field_email:
          $type = (isset($contact_array[self::kit_address_type]) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontact::type_company : dbKITcontact::type_person;
          $contact[$key] = sprintf('%s|%s', $type, $contact_email);
          break;
        case dbKITcontact::field_email_standard:
          $contact[$key] = 0;
          break;
        case dbKITcontact::field_category:
          $contact[$key] = (isset($contact_array[self::kit_intern])) ? $contact_array[self::kit_intern] : '';
          break;
        case dbKITcontact::field_newsletter:
          $contact[$key] = (isset($contact_array[self::kit_newsletter])) ? $contact_array[self::kit_newsletter] : '';
          break;
        case dbKITcontact::field_distribution:
          $contact[$key] = (isset($contact_array[self::kit_distribution])) ? $contact_array[self::kit_distribution] : '';
          break;
        case dbKITcontact::field_person_title:
          $contact[$key] = (isset($contact_array[self::kit_title])) ? $contact_array[self::kit_title] : dbKITcontact::person_title_mister;
          break;
        case dbKITcontact::field_person_title_academic:
          $contact[$key] = (isset($contact_array[self::kit_title_academic])) ? $contact_array[self::kit_title_academic] : dbKITcontact::person_title_academic_none;
          break;
        case dbKITcontact::field_phone:
          $contact[$key] = '';
          $types = array(
              self::kit_phone => dbKITcontact::phone_phone,
              self::kit_phone_mobile => dbKITcontact::phone_handy,
              self::kit_fax => dbKITcontact::phone_fax);
          foreach ($types as $type => $kit_type) {
            if (isset($contact_array[$type]) && !empty($contact_array[$type])) {
              if (!empty($contact[$key]))
                $contact[$key] .= ';';
              $contact[$key] .= sprintf('%s|%s', $kit_type, $contact_array[$type]);
            }
          }
          $contact[dbKITcontact::field_phone_standard] = (!empty($contact[$key])) ? 0 : -1;
          break;
        case dbKITcontact::field_type:
          $contact[$key] = dbKITcontact::type_person;
          break;
        case dbKITcontact::field_status:
          $contact[$key] = dbKITcontact::status_active;
          break;
        case dbKITcontact::field_contact_language:
          // set contact language
          $def_lang = $dbCfg->getValue(dbKITcfg::cfgContactLanguageDefault);
          $def_lang = strtolower(trim($def_lang));
          $contact[$key] = (isset($contact_array[self::kit_contact_language])) ? strtolower(trim($contact_array[self::kit_contact_language])) : $def_lang;
          break;
        default:
          // nothing to do...
          break;
      endswitch;
    }

    $contact[dbKITcontact::field_update_by] = 'INTERFACE';
    $contact[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');

    if (!$dbContact->sqlInsertRecord($contact, $contact_id)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }

    $dbContact->addSystemNotice($contact_id, kit_protocol_ki_contact_created);

    if (isset($contact_array[self::kit_city]) && !empty($contact_array[self::kit_city])) {
      // es muss zumindest die Stadt angegeben sein
      $address = array(
          dbKITcontactAddress::field_city => isset($contact_array[self::kit_city]) ? $contact_array[self::kit_city] : '',
          dbKITcontactAddress::field_contact_id => $contact_id,
          dbKITcontactAddress::field_country => isset($contact_array[self::kit_country]) ? strtoupper(trim($contact_array[self::kit_country])) : 'DE',
          dbKITcontactAddress::field_street => isset($contact_array[self::kit_street]) ? $contact_array[self::kit_street] : '',
          dbKITcontactAddress::field_zip => isset($contact_array[self::kit_zip]) ? $contact_array[self::kit_zip] : '',
          dbKITcontactAddress::field_type => (isset($contact_array[self::kit_address_type]) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private,
          dbKITcontactAddress::field_status => dbKITcontactAddress::status_active,
          dbKITcontactAddress::field_update_by => 'INTERFACE',
          dbKITcontactAddress::field_update_when => date('Y-m-d H:i:s'));
      $address_id = -1;
      if (!$dbContactAddress->sqlInsertRecord($address, $address_id)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
        return false;
      }
      // Kontakt Datensatz aktualisieren
      $contact[dbKITcontact::field_address] = $address_id;
      $contact[dbKITcontact::field_address_standard] = $address_id;
      $contact[dbKITcontact::field_update_by] = 'INTERFACE';
      $contact[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
      $where = array(dbKITcontact::field_id => $contact_id);
      if (!$dbContact->sqlUpdateRecord($contact, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
        return false;
      }
      $dbContact->addSystemNotice($contact_id, sprintf(kit_protocol_ki_address_added, $address[dbKITcontactAddress::field_street], $address[dbKITcontactAddress::field_zip], $address[dbKITcontactAddress::field_city]));
    }

    // Registrierung pruefen und anlegen
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s'", $dbRegister->getTableName(), dbKITregister::field_email, $contact_array[self::kit_email], dbKITregister::field_status, dbKITregister::status_deleted);
    $register_array = array();
    if (!$dbRegister->sqlExec($SQL, $register_array)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register_array) > 0) {
      $this->setError(sprintf(kit_error_register_email_already_exists, $contact_array[self::kit_email]));
      return false;
    }
    $register_array = array(
        dbKITregister::field_contact_id => $contact_id,
        dbKITregister::field_email => $contact_array[self::kit_email],
        dbKITregister::field_login_failures => 0,
        dbKITregister::field_login_locked => 0,
        dbKITregister::field_newsletter => $contact[dbKITcontact::field_newsletter],
        dbKITregister::field_password => md5($contact_array[self::kit_email]),
        dbKITregister::field_register_confirmed => '0000-00-00 00:00:00',
        dbKITregister::field_register_date => date('Y-m-d H:i:s'),
        dbKITregister::field_register_key => $tools->createGUID(),
        dbKITregister::field_status => dbKITregister::status_key_created,
        dbKITregister::field_username => $contact_array[self::kit_email],
        dbKITregister::field_update_by => 'INTERFACE',
        dbKITregister::field_update_when => date('Y-m-d H:i:s'));
    $register_id = -1;
    if (!$dbRegister->sqlInsertRecord($register_array, $register_id)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    $register_array[dbKITregister::field_id] = $register_id;

    return true;
  } // insertContact()

  /**
   * Sanitize variables and prepare them for saving in a MySQL record
   *
   * @param mixed $item
   * @return mixed
   */
  public static function sanitizeVariable($item) {
    if (!is_array($item)) {
      // undoing 'magic_quotes_gpc = On' directive
      if (get_magic_quotes_gpc())
        $item = stripcslashes($item);
      $item = self::sanitizeText($item);
    }
    return $item;
  } // sanitizeVariable()

  /**
   * Sanitize a text variable and prepare ist for saving in a MySQL record
   *
   * @param string $text
   * @return string
   */
  protected static function sanitizeText($text) {
    $text = str_replace(array("<",">","\"","'"), array("&lt;","&gt;","&quot;","&#039;"), $text);
    $text = mysql_real_escape_string($text);
    return $text;
  } // sanitizeText()

  /**
   * Unsanitize a text variable and prepare it for output
   *
   * @param string $text
   * @return string
   */
  public static function unsanitizeText($text) {
    $text = stripcslashes($text);
    $text = str_replace(array("&lt;","&gt;","&quot;","&#039;"), array("<",">","\"","'"), $text);
    return $text;
  } // unsanitizeText()

  /**
   * Liefert die Kontaktdaten zu der angegebenen $contact_id
   *
   * @param INT $contact_id
   * @param ARRAY REFERENCE $contact_array
   * @param boolean $get_deleted get also a deleted and inactive contact
   * @return BOOL
   */
  public function getContact($contact_id, &$contact_array = array(), $get_deleted=false) {
    global $dbContact;
    global $dbContactAddress;
    global $database;

    if ($get_deleted) {
      $SQL = sprintf("SELECT * FROM %s WHERE %s='%s'", $dbContact->getTableName(), dbKITcontact::field_id, $contact_id);
    }
    else {
      $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s'", $dbContact->getTableName(), dbKITcontact::field_id, $contact_id, dbKITcontact::field_status, dbKITcontact::status_deleted);
    }
    $contact = array();
    if (!$dbContact->sqlExec($SQL, $contact)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    if (count($contact) < 1) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact_id)));
      return false;
    }
    $contact = $contact[0];
    if ($contact[dbKITcontact::field_address_standard] > 0) {
      // Standard Adresse auslesen
      $where = array(
          dbKITcontactAddress::field_id => $contact[dbKITcontact::field_address_standard]);
      $address = array();
      if (!$dbContactAddress->sqlSelectRecord($where, $address)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
        return false;
      }
      if (count($address) < 1) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact[dbKITcontact::field_address_standard])));
        return false;
      }
      $address = $address[0];
    }
    else {
      // kein Eintrag vorhanden, nur die leeren Felder setzen
      $address = $dbContactAddress->getFields();
    }

    // create contact array
    $contact_array = array();
    $contact_array['kit_id'] = $contact_id;
    // loop through the datafields
    foreach ($this->field_array as $kit_field => $label) {
      switch ($kit_field) :
      case self::kit_birthday:
      case self::kit_contact_since:
      case self::kit_contact_language:
      case self::kit_identifier:
      case self::kit_status:
      case self::kit_first_name:
      case self::kit_last_name:
      case self::kit_company:
      case self::kit_department:
        $contact_array[$kit_field] = $contact[$this->field_assign[$kit_field]];
        break;
      case self::kit_address_type:
          //$contact_array[$kit_field] = ($contact[dbKITcontact::field_type] == dbKITcontact::type_person) ? $this->address_type_array[self::address_type_private] : $this->address_type_array[self::address_type_business];
          $contact_array[$kit_field] = ($contact[dbKITcontact::field_type] == dbKITcontact::type_person) ? self::address_type_private : self::address_type_business;
          break;
      case self::kit_country:
        case self::kit_street:
        case self::kit_zip:
        case self::kit_city:
          $contact_array[$kit_field] = $address[$this->field_assign[$kit_field]];
          break;
        case self::kit_phone:
        case self::kit_phone_mobile:
        case self::kit_fax:
          $contact_array[$kit_field] = '';
          $phone_array = explode(';', $contact[dbKITcontact::field_phone]);
          $types = array(
              self::kit_phone => dbKITcontact::phone_phone,
              self::kit_phone_mobile => dbKITcontact::phone_handy,
              self::kit_fax => dbKITcontact::phone_fax);
          foreach ($phone_array as $phone) {
            if (strpos($phone, '|') !== false) {
              list($type, $number) = explode('|', $phone);
              if (array_search($type, $types) == $kit_field) {
                $contact_array[$kit_field] = $number;
              }
            }
          }
          break;
        case self::kit_email:
          $email_array = explode(';', $contact[dbKITcontact::field_email]);
          if (!isset($email_array[$contact[dbKITcontact::field_email_standard]]) || empty($email_array[$contact[dbKITcontact::field_email_standard]]) || !strpos($email_array[$contact[dbKITcontact::field_email_standard]], '|')) {
            // missing the email address - set a non existing !!!
            $email = 'undefined@email.tld';
          }
          else {
            list($type, $email) = explode('|', $email_array[$contact[dbKITcontact::field_email_standard]]);
          }
          $contact_array[$kit_field] = $email;
          break;
        case self::kit_newsletter:
          $item_array = explode(',', $contact[dbKITcontact::field_newsletter]);
          $items = array();
          foreach ($item_array as $item) {
            $SQL = "SELECT `array_value` FROM `".self::$table_prefix."mod_kit_contact_array_cfg` WHERE `array_type`='typeNewsletter' AND `array_identifier`='$item'";
            $i = $database->get_one($SQL, MYSQL_ASSOC);
            if ($database->is_error()) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
              return false;
            }
            if (!is_null($i)) $items[] = $i;
          }
          $contact_array[$kit_field] = $items;
          break;
        case self::kit_distribution:
          $item_array = explode(',', $contact[dbKITcontact::field_distribution]);
          $items = array();
          foreach ($item_array as $item) {
            $SQL = "SELECT `array_value` FROM `".self::$table_prefix."mod_kit_contact_array_cfg` WHERE `array_type`='typeDistribution' AND `array_identifier`='$item'";
            $i = $database->get_one($SQL, MYSQL_ASSOC);
            if ($database->is_error()) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
              return false;
            }
            if (!is_null($i)) $items[] = $i;
          }
          $contact_array[$kit_field] = $items;
          break;
        case self::kit_intern:
          $item_array = explode(',', $contact[dbKITcontact::field_category]);
          $items = array();
          foreach ($item_array as $item) {
            $SQL = "SELECT `array_value` FROM `".self::$table_prefix."mod_kit_contact_array_cfg` WHERE `array_type`='typeCategory' AND `array_identifier`='$item'";
            $i = $database->get_one($SQL, MYSQL_ASSOC);
            if ($database->is_error()) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
              return false;
            }
            if (!is_null($i)) $items[] = $i;
          }
          $contact_array[$kit_field] = $items;
          break;
        case self::kit_free_field_1:
        case self::kit_free_field_2:
        case self::kit_free_field_3:
        case self::kit_free_field_4:
        case self::kit_free_field_5:
        case self::kit_free_note_1:
        case self::kit_free_note_2:
        case self::kit_note:
          // get the contact memo field
          $memo_id = $contact[$this->field_assign[$kit_field]];
          if ($memo_id > 0) {
            $SQL = "SELECT `memo_memo` FROM `".self::$table_prefix."mod_kit_contact_memos` WHERE `memo_id`='$memo_id'";
            if (null == ($contact_array[$kit_field] = $database->get_one($SQL, MYSQL_ASSOC))) {
              $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
              return false;
            }
          }
          else
            $contact_array[$kit_field] = '';
          break;
        case self::kit_title:
        case self::kit_title_academic:
          // get the field values from dbCfg
          $type = ($kit_field == self::kit_title) ? 'typePersonTitle' : 'typePersonAcademic';
          $SQL = "SELECT `array_value` FROM `".self::$table_prefix."mod_kit_contact_array_cfg` WHERE `array_type`='$type' AND `array_identifier`='{$contact[$this->field_assign[$kit_field]]}'";
          $contact_array[$kit_field] = $database->get_one($SQL, MYSQL_ASSOC);
          if ($database->is_error()) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
            return false;
          }
          break;
        default:
        // nothing to do...
          break;
      endswitch;
    }
    // Gruppen des Kontakts auslesen und in einem Array ablegen
    $contact_array[self::kit_categories] = array_merge(
        isset($contact_array[self::kit_intern]) ? $contact_array[self::kit_intern] : array(),
        isset($contact_array[self::kit_distribution]) ? $contact_array[self::kit_distribution] : array(),
        isset($contact_array[self::kit_newsletter]) ? $contact_array[self::kit_newsletter] : array());

    return true;
  } // getContact()

  /**
   * Kategorien fuer den angegebenen Kontakt auslesen
   *
   * @param INT $contact_id
   * @param ARRAY $categories
   * @param boolean $use_keys if true return structured $categories
   * @return BOOL
   */
  public function getCategories($contact_id, &$categories = array(), $use_keys=false) {
    global $dbContact;

    $SQL = sprintf("SELECT %s,%s,%s FROM %s WHERE %s='%s' AND %s='%s'", dbKITcontact::field_category, dbKITcontact::field_distribution, dbKITcontact::field_newsletter, $dbContact->getTableName(), dbKITcontact::field_id, $contact_id, dbKITcontact::field_status, dbKITcontact::status_active);
    $cats = array();
    if (!$dbContact->sqlExec($SQL, $cats)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    $categories = array();
    if (count($cats) < 1) {
      // kein Treffer, leeres Array zurueckliefern
      return true;
    }
    $cats = $cats[0];
    $ga = array(
        dbKITcontact::field_category => 'kit_category',
        dbKITcontact::field_distribution => 'kit_disctribution',
        dbKITcontact::field_newsletter => 'kit_newsletter'
        );
    foreach ($ga as $grp => $field) {
      $x = explode(',', $cats[$grp]);
      foreach ($x as $i) {
        if (!empty($i)) {
          if ($use_keys) {
            $categories[] = array(
                'type' => $field,
                'value' => $i
                );
          }
          else
            $categories[] = $i;
        }
      }
    }
    return true;
  } // getCategories()

  /**
   * Get contact ID's for the specified category identifier
   *
   * @param string $identifier
   * @param array $contacts
   * @return boolean
   */
  public function getContactsByCategory($identifier, &$contacts=array()) {
      global $database;

      $SQL = "SELECT `contact_id` FROM `".self::$table_prefix."mod_kit_contact` WHERE ((`contact_distribution_ids`='$identifier') OR ".
                      "(`contact_distribution_ids` LIKE '%,$identifier') OR (`contact_distribution_ids` LIKE '%,$identifier,%') OR ".
                      "(`contact_distribution_ids` LIKE '$identifier,%')) AND `contact_status`='statusActive'";
      if (null === ($query = $database->query($SQL))) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $database->get_error()));
          return false;
      }
      while (false !== ($contact_id = $query->fetchRow(MYSQL_ASSOC)))
          $contacts[] = $contact_id['contact_id'];
      return true;
  } // getContactsByCategory()

  /**
   * Ueberprueft ob fuer den angegebenen $category_type:
   * category_type_intern, category_type_newsletter, category_type_distribution
   * ein Eintrag mit dem Bezeichner $category_identifier existiert.
   *
   * @param CONST $category_type
   * @param STR $category_identifier
   * @return BOOL
   */
  public function existsCategory($category_type, $category_identifier) {
    global $dbContactArrayCfg;

    $where = array(
        dbKITcontactArrayCfg::field_type => $category_type,
        dbKITcontactArrayCfg::field_identifier => $category_identifier,
        dbKITcontactArrayCfg::field_status => dbKITcontactArrayCfg::status_active);
    $categories = array();
    if (!$dbContactArrayCfg->sqlSelectRecord($where, $categories)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactArrayCfg->getError()));
      return false;
    }
    $result = (count($categories) > 0) ? true : false;
    return $result;
  } // existsCategory()

  /**
   * Fuegt fuer die angegebene Kategorie $category_type einen Bezeichner
   * $category_identifier mit dem Wert $category_value ein
   *
   * @param CONST $category_type
   * @param STR $category_identifier
   * @param STR $category_value
   * @return BOOL
   */
  public function addCategory($category_type, $category_identifier, $category_value) {
    global $dbContactArrayCfg;
    return $dbContactArrayCfg->checkArray($category_type, $category_identifier, $category_value);
  } // addCategory()

  /**
   * Check the user login and set the KIT SESSION variables on success.
   * Return a reference array with the &$contact on success.
   * Set &$must_change_password to TRUE if the user must change the default password
   *
   * @param STR $email
   * @param STR $password
   * @param REFERENCE ARRAY $contact
   * @param REFERENCE BOOL $must_change_password
   * @return BOOL TRUE on success and BOOL FALSE on fail or error - set $message or $error
   */
  public function checkLogin($email, $password, &$contact = array(), &$must_change_password = false) {
    global $dbRegister;
    global $dbContact;
    global $dbCfg;

    /* this method will cause trouble if the email was deleteted and is now reactivated!
    $where = array(
    dbKITregister::field_email 	=> $email,
    );
    $register = array();
    if (!$dbRegister->sqlSelectRecord($where, $register)) {
    $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
    return false;
    }
     */
    // avoid conflict if email was already registered but is deleted, select only the last record!
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s' ORDER BY %s DESC LIMIT 1", $dbRegister->getTableName(), dbKITregister::field_email, $email, dbKITregister::field_status, dbKITregister::status_deleted, dbKITregister::field_update_when);
    $register = array();
    if (!$dbRegister->sqlExec($SQL, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) < 1) {
      // Benutzer ist nicht bekannt
      $this->setMessage(sprintf(kit_msg_login_user_unknown, $email));
      return false;
    }
    $register = $register[0];
    if ($register[dbKITregister::field_status] !== dbKITregister::status_active) {
      // Konto ist nicht aktiv
      $this->setMessage(sprintf(kit_msg_login_status_fail, $email));
      return false;
    }
    $max_login = $dbCfg->getValue(dbKITcfg::cfgMaxInvalidLogin);
    if ($register[dbKITregister::field_login_locked] == 1) {
      // Konto gesperrt, zuviele Fehlversuche
      $this->setMessage(sprintf(kit_msg_login_locked, $email));
      return false;
    }
    if (md5($password) !== $register[dbKITregister::field_password]) {
      // Passwort stimmt nicht, Zaehler fuer Fehlversuche hochsetzen...
      $where = array();
      $where[dbKITregister::field_id] = $register[dbKITregister::field_id];
      $data = array(
          dbKITregister::field_login_failures => $register[dbKITregister::field_login_failures] + 1,
          dbKITregister::field_login_locked => (($register[dbKITregister::field_login_failures] + 1) > $max_login) ? 1 : 0,
          dbKITregister::field_update_by => 'INTERFACE',
          dbKITregister::field_update_when => date('Y-m-d H:i:s'));
      if (!$dbRegister->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
      if ($data[dbKITregister::field_login_locked] == 1) {
        // Konto ist gesperrt, Systemlog aktualisieren
        $dbContact->addSystemNotice($register[dbKITregister::field_contact_id], kit_protocol_login_locked);
        $this->setMessage(kit_msg_login_locked);
        return false;
      }
      $this->setMessage(kit_msg_login_password_invalid);
      return false;
    }
    else {
      // OK - LOGIN erfolgreich
      if (strtolower($password) == strtolower($register[dbKITregister::field_email])) {
        // special: initial password is identical with email address and must be changed
        $_SESSION[self::session_must_change_pwd] = true;
        $must_change_password = true;
      }
      $_SESSION[self::session_kit_register_id] = $register[dbKITregister::field_id];
      $_SESSION[self::session_kit_key] = $register[dbKITregister::field_register_key];
      $_SESSION[self::session_kit_contact_id] = $register[dbKITregister::field_contact_id];

      // Kontaktdaten uebergeben
      $this->getContact($register[dbKITregister::field_contact_id], $contact);

      return true;
    }
  } // checkLogin()

  /**
   * Neues Passwort fuer den Kontakt mit der E-Mail $email erzeugen
   *
   * @param string $email
   * @param string reference $newPassword
   * @return boolean
   */
  public function generateNewPassword($email, &$newPassword) {
    global $dbRegister;
    global $tools;
    global $dbCfg;

    $where = array(dbKITregister::field_email => $email);
    $register = array();
    if (!$dbRegister->sqlSelectRecord($where, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) < 1) {
      // Benutzer ist nicht bekannt
      $this->setMessage(sprintf(kit_msg_login_user_unknown, $email));
      return false;
    }
    $register = $register[0];
    if ($register[dbKITregister::field_status] !== dbKITregister::status_active) {
      // Konto ist nicht aktiv
      $this->setMessage(sprintf(kit_msg_login_status_fail, $email));
      return false;
    }
    // neues Passwort erzeugen
    $newPassword = $tools->generatePassword($dbCfg->getValue(dbKITcfg::cfgMinPwdLen));

    $data = array(
        dbKITregister::field_password => md5($newPassword),
        dbKITregister::field_login_failures => 0,
        dbKITregister::field_login_locked => 0,
        dbKITregister::field_update_by => 'INTERFACE',
        dbKITRegister::field_update_when => date('Y-m-d H:i:s'));

    if (!$dbRegister->sqlUpdateRecord($data, $where)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    return true;
  } // generateNewPassword()

  /**
   * Check the send Activation Key and if ok change status to Active and generate a password
   *
   * @param STR $key - Activation Key
   * @param REFERENCE ARRAY $register_array - registration record of the user
   * @param REFERENCE ARRAY $contact_array - contact record of the user
   * @param REFERENCE STR $password - the generated password
   */
  public function checkActivationKey($key, &$register = array(), &$contact_array = array(), &$password = '') {
    global $dbRegister;
    global $tools;
    global $dbCfg;

    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s'", $dbRegister->getTableName(), dbKITregister::field_register_key, $key, dbKITregister::field_status, dbKITregister::status_deleted);
    $register = array();
    if (!$dbRegister->sqlExec($SQL, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) < 1) {
      $this->setMessage(kit_msg_activation_key_invalid);
      return false;
    }
    $register = $register[0];
    if (($register[dbKITregister::field_status] == dbKITregister::status_key_created) || ($register[dbKITregister::field_status] == dbKITregister::status_key_send)) {
      // ok - Aktiverungskey wurde versendet und noch nicht verwendet
      $password = $tools->generatePassword($dbCfg->getValue(dbKITcfg::cfgMinPwdLen));
      $md5_password = md5($password);
      $where = array(
          dbKITregister::field_id => $register[dbKITregister::field_id]);
      $data = array(
          dbKITregister::field_register_confirmed => date('Y-m-d H:i:s'),
          dbKITregister::field_password => $md5_password,
          dbKITregister::field_status => dbKITregister::status_active,
          dbKITregister::field_update_by => 'INTERFACE',
          dbKITregister::field_update_when => date('Y-m-d H:i:s'));
      if (!$dbRegister->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
      // Notiz hinzufuegen
      $this->addNotice($register[dbKITregister::field_contact_id], kit_protocol_ki_account_activated);
      if (!$this->getContact($register[dbKITregister::field_contact_id], $contact_array)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      // check if user should added as LEPTON user
      $this->addContactAsLeptonUser($register[dbKITregister::field_contact_id], $register[dbKITregister::field_email], $md5_password);
      if ($this->isError()) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      // get the actual register record
      $where = array(
          dbKITregister::field_id => $register[dbKITregister::field_id]);
      $register_array = array();
      if (!$dbRegister->sqlSelectRecord($where, $register_array)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
      $register = $register_array[0];
      return true;
    }
    elseif ($register[dbKITregister::field_status] == dbKITregister::status_locked) {
      // Konto ist gesperrt
      $this->setMessage(kit_msg_account_locked);
      return false;
    }
    elseif ($register[dbKITregister::field_status] == dbKITregister::status_active) {
      // Konto ist aktiv und freigeschaltet, nix tun...
      if (!$this->getContact($register[dbKITregister::field_contact_id], $contact_array)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      // Passwort auf -1 setzen
      $password = -1;
      return true;
    }
    else {
      ;
      // Aktivierungskey wurde bereits verwendet
      $this->setMessage(kit_msg_activation_key_used);
      return false;
    }
  } // checkActivationKey()

  public function addContactAsLeptonUser($contact_id, $email, $password) {
    global $dbRegister;
    global $dbCfg;
    global $dbWBusers;
    global $dbWBgroups;
    global $dbContact;

    // get the KIT categories of the user
    $SQL = sprintf("SELECT %s FROM %s WHERE %s='%s'", dbKITcontact::field_category, $dbContact->getTableName(), dbKITcontact::field_id, $contact_id);
    $contact = array();
    if (!$dbContact->sqlExec($SQL, $contact)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
      return false;
    }
    if (count($contact) < 1) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact_id)));
      return false;
    }
    $groups = explode(',', $contact[0][dbKITcontact::field_category]);
    if (!in_array(dbKITcontact::category_wb_user, $groups)) {
      // nothing to do - user is not member of the group catWBUser
      return false;
    }

    if (!$dbCfg->getValue(dbKITcfg::cfgConnectWBusers)) {
      // The KIT connection to LEPTON Users is not active
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, kit_error_lepton_user_connection_inactive));
      return false;
    }

    // check if a LEPTON group exists for KIT ...
    $where = array(dbWBgroups::field_name => dbWBgroups::kitWBgoup);
    $groups = array();
    if (!$dbWBgroups->sqlSelectRecord($where, $groups)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBgroups->getError()));
      return false;
    }
    if (count($groups) < 1) {
      // the group does not exist and must be created
      $data = array(dbWBgroups::field_name => dbWBgroups::kitWBgoup);
      $kit_group_id = -1;
      if (!$dbWBgroups->sqlInsertRecord($data, $kit_group_id)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBgroups->getError()));
        return false;
      }
    }
    else {
      $kit_group_id = $groups[0][dbWBgroups::field_group_id];
    }

    // check if the KIT contact is LEPTON user
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s='%s'", $dbWBusers->getTableName(), dbWBusers::field_email, $email, dbWBusers::field_active, dbWBusers::status_active);
    $user = array();
    if (!$dbWBusers->sqlExec($SQL, $user)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
      return false;
    }
    if (count($user) == 0) {
      // user does not exists, add contact
      $data = array(
          dbWBusers::field_active => dbWBusers::status_active,
          dbWBusers::field_display_name => $email,
          dbWBusers::field_email => $email,
          dbWBusers::field_group_id => $kit_group_id,
          dbWBusers::field_groups_id => $kit_group_id,
          dbWBusers::field_password => $password,
          dbWBusers::field_username => $email);
      if (!$dbWBusers->sqlInsertRecord($data)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
        return false;
      }
      $this->addNotice($contact_id, kit_protocol_ki_added_as_lepton_user);
    }
    else {
      // user exists, check groups
      $groups = explode(',', $user[0][dbWBusers::field_groups_id]);
      if (!in_array(array(1, $kit_group_id), $groups)) {
        $groups[] = $kit_group_id;
        $data = array(dbWBusers::field_groups_id => implode(',', $groups));
        $where = array(
            dbWBusers::field_user_id => $user[0][dbWBusers::field_user_id]);
        if (!$dbWBusers->sqlUpdateRecord($data, $where)) {
          $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbWBusers->getError()));
          return false;
        }
        $this->addNotice($contact_id, sprintf(kit_protocol_ki_lepton_user_group_added, dbWBgroups::kitWBgoup));
      }
    }
    return true;
  } // addContactAsLeptonUser()

  public function changePassword($register_id, $contact_id, $new_password, $new_password_retype) {
    global $dbCfg;
    global $dbRegister;

    if ($new_password != $new_password_retype) {
      $this->setMessage(kit_msg_passwords_mismatch);
      return false;
    }
    $min_length = $dbCfg->getValue(dbKITcfg::cfgMinPwdLen);
    if (strlen($new_password) < $min_length) {
      $this->setMessage(sprintf(kit_msg_password_too_short, $min_length));
      return false;
    }
    $data = array(
        dbKITregister::field_password => md5($new_password),
        dbKITregister::field_login_failures => 0,
        dbKITregister::field_login_locked => 0,
        dbKITregister::field_update_by => 'INTERFACE',
        dbKITregister::field_update_when => date('Y-m-d H:i:s'));
    $where = array(dbKITregister::field_id => $register_id);
    if (!$dbRegister->sqlUpdateRecord($data, $where)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    $this->setMessage(kit_msg_password_changed);
    $this->addNotice($contact_id, kit_protocol_ki_password_changed);
    return true;
  } // changePassword()

  /**
   * Subscribe the $email for one or more newsletters
   *
   * @param string $email
   * @param string $newsletter
   * @param boolean $subscribe
   * @param boolean $use_subscribe
   * @param array reference $register
   * @param array reference $contact
   * @param boolean reference $send_activation
   */
  public function subscribeNewsletter($email, $newsletter, $subscribe = true, $use_subscribe = false, &$register = array(), &$contact = array(), &$send_activation = false) {
    global $dbRegister;
    global $dbContact;
    global $tools;

    if (!$tools->validateEMail($email)) {
      // E-Mail Adresse ist ungueltig
      $this->setMessage(sprintf(kit_msg_email_invalid, $email));
      return false;
    }

    // pruefen, ob bereits ein Datensatz existiert
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s' AND %s!='%s'", $dbRegister->getTableName(), dbKITregister::field_email, $email, dbKITregister::field_status, dbKITregister::status_deleted);
    $register = array();
    if (!$dbRegister->sqlExec($SQL, $register)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
      return false;
    }
    if (count($register) > 0) {
      // Anwender ist bereits registriert
      $register = $register[0];
      if ($register[dbKITregister::field_status] != dbKITregister::status_active) {
        // Registrierung ist nicht aktiv
        if (($register[dbKITregister::field_status] == dbKITregister::status_key_created) || ($register[dbKITregister::field_status] == dbKITregister::status_key_send)) {
          // Benutzer ist registriert aber noch nicht bestaetigt
          $send_activation = true;
          if (!$this->getContact($register[dbKITregister::field_contact_id], $contact)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
            return false;
          }
          $this->setMessage(sprintf(kit_msg_newsletter_account_not_activated, $email));
          return true;
        }
        else {
          // Konto ist gesperrt
          $this->setMessage(sprintf(kit_msg_newsletter_account_locked, $email));
          return false;
        }
      }
      $newsletter_array = explode(',', $register[dbKITregister::field_newsletter]);
      $new_array = explode(',', $newsletter);
      if (($use_subscribe == true) && ($subscribe == true)) {
        // angegebene Newsletter anmelden
        foreach ($new_array as $new) {
          if (!in_array($new, $newsletter_array))
            $newsletter_array[] = $new;
        }
      }
      elseif (($use_subscribe == true) && ($subscribe == false)) {
        // angegebene Newsletter abmelden
        foreach ($new_array as $new) {
          if (false !== ($key = array_search($new, $newsletter_array))) {
            unset($newsletter_array[$key]);
          }
        }
      }
      else {
        // Newsletter 1:1 uebernehmen
        $newsletter_array = $new_array;
      }
      // Registrierung aktualisieren
      $data = array(
          dbKITregister::field_newsletter => implode(',', $newsletter_array),
          dbKITregister::field_update_by => 'INTERFACE',
          dbKITregister::field_update_when => date('Y-m-d H:i:s'));
      $where = array(
          dbKITregister::field_id => $register[dbKITregister::field_id]);
      if (!$dbRegister->sqlUpdateRecord($data, $where)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbRegister->getError()));
        return false;
      }
      // Kontaktdatensatz aktualisieren
      $contact = array();
      foreach ($this->field_array as $key => $value) {
        switch ($key) :
          case self::kit_newsletter:
            $contact[$key] = implode(',', $newsletter_array);
            break;
          default:
            if (isset($_REQUEST[$key]))
              $contact[$key] = $_REQUEST[$key];
        endswitch;
      }
      if (!$this->updateContact($register[dbKITregister::field_contact_id], $contact)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      // Kontaktdaten auslesen
      if (!$this->getContact($register[dbKITregister::field_contact_id], $contact)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      // LOG Eintrag...
      $this->addNotice($register[dbKITregister::field_contact_id], kit_protocol_ki_newsletter_updated);
      return true;
    }
    else {
      // neue Anmeldung
      if (($use_subscribe == true) && ($subscribe == false)) {
        // Benutzer kann keine Newsletter abmelden, da er nicht registriert ist
        $this->setMessage(sprintf(kit_msg_newsletter_user_not_registered, $email));
        return false;
      }
      if (empty($newsletter)) {
        $this->setMessage(sprintf(kit_msg_newsletter_no_abonnement, $email));
        return false;
      }
      // neuen Benutzer anlegen
      $contact = array();
      foreach ($this->field_array as $key => $value) {
        switch ($key) :
          case self::kit_email:
            $contact[$key] = $email;
            break;
          case self::kit_newsletter:
            $contact[$key] = $newsletter;
            break;
          default:
            if (isset($_REQUEST[$key]))
              $contact[$key] = $_REQUEST[$key];
        endswitch;
      }
      $contact_id = -1;
      if (!$this->addContact($contact, $contact_id, $register)) {
        $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
        return false;
      }
      $send_activation = true;
      return true;
    }
  } // subscribeNewsletter

  /**
   * Return an assoziative array of all active
   * service providers defined in KIT settings
   *
   * @param REFERENCE ARRAY $service_provider
   * @return BOOL
   */
  public function getServiceProviderList(&$service_provider) {
    global $dbProvider;
    $SQL = sprintf("SELECT * FROM %s WHERE %s='%s'", $dbProvider->getTableName(), dbKITprovider::field_status, dbKITprovider::status_active);
    $providers = array();
    if (!$dbProvider->sqlExec($SQL, $providers)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
      return false;
    }
    if (count($providers) < 1) {
      $this->setMessage(kit_msg_provider_missing);
      return false;
    }
    $service_provider = array();
    foreach ($providers as $provider) {
      $service_provider[] = array(
          'id' => $provider[dbKITprovider::field_id],
          'name' => $provider[dbKITprovider::field_name],
          'email' => $provider[dbKITprovider::field_email],
          'identifier' => $provider[dbKITprovider::field_identifier],
          'remark' => $provider[dbKITprovider::field_remark],
          'smtp_auth' => $provider[dbKITprovider::field_smtp_auth],
          'smtp_host' => $provider[dbKITprovider::field_smtp_host],
          'smtp_user' => $provider[dbKITprovider::field_smtp_user],
          'smtp_pass' => $provider[dbKITprovider::field_smtp_pass],
          'status' => $provider[dbKITprovider::field_status],
          'update_by' => $provider[dbKITprovider::field_update_by],
          'update_when' => $provider[dbKITprovider::field_update_when]);
    }
    return true;
  } // getServiceProviderList()

  /**
   * Return all data for the provider with the assigned
   * $provider_id - the provider must be active
   *
   * @param INT $provider_id
   * @param REFERENCE ARRAY $provider_data
   * @return BOOL
   */
  public function getServiceProviderByID($provider_id, &$provider_data) {
    global $dbProvider;
    $where = array(
        dbKITprovider::field_id => $provider_id,
        dbKITprovider::field_status => dbKITprovider::status_active);
    $provider = array();
    $provider_data = array();
    if (!$dbProvider->sqlSelectRecord($where, $provider)) {
      $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbProvider->getError()));
      return false;
    }
    if (count($provider) < 1) {
      $this->setMessage(sprintf(kit_msg_provider_id_invalid, $provider_id));
      return false;
    }
    $provider = $provider[0];
    $provider_data = array(
        'id' => $provider[dbKITprovider::field_id],
        'name' => $provider[dbKITprovider::field_name],
        'email' => $provider[dbKITprovider::field_email],
        'identifier' => $provider[dbKITprovider::field_identifier],
        'remark' => $provider[dbKITprovider::field_remark],
        'smtp_auth' => $provider[dbKITprovider::field_smtp_auth],
        'smtp_host' => $provider[dbKITprovider::field_smtp_host],
        'smtp_user' => $provider[dbKITprovider::field_smtp_user],
        'smtp_pass' => $provider[dbKITprovider::field_smtp_pass],
        'relaying' => $provider[dbKITprovider::field_relaying],
        'status' => $provider[dbKITprovider::field_status],
        'update_by' => $provider[dbKITprovider::field_update_by],
        'update_when' => $provider[dbKITprovider::field_update_when]);
    return true;
  } // getServiceProviderByID()

  /**
   * Return the primary E-Mail addresses of the defined KIT admins
   *
   * @param REFERENCE ARRAY $emails
   * @return BOOL true if admins defined, false if not
   */
  public function getAdmins(&$emails = array()) {
    global $dbCfg;
    $emails = $dbCfg->getValue(dbKITcfg::cfgKITadmins);
    return (count($emails) < 1) ? false : true;
  } // getAdmins

} // class kitContactInterface

?>