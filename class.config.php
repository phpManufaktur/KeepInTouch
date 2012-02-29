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

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php


require_once (WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/initialize.php');

if (!defined('KIT_INSTALL_RUNNING')) {
    global $dbCfg;
    if (! is_object($dbCfg)) $dbCfg = new dbKITcfg();

    //	define('USE_CUSTOM_FILES', $dbCfg->getValue(dbKITcfg::cfgUseCustomFiles));
    define('KIT_SESSION_ID', $dbCfg->getValue(dbKITcfg::cfgSessionID));
}

class dbKITcfg extends dbConnectLE {

    const field_id = 'cfg_id';
    const field_name = 'cfg_name';
    const field_type = 'cfg_type';
    const field_value = 'cfg_value';
    const field_label = 'cfg_label';
    const field_description = 'cfg_desc';
    const field_status = 'cfg_status';
    const field_update_by = 'cfg_update_by';
    const field_update_when = 'cfg_update_when';

    const status_active = 1;
    const status_deleted = 0;

    const type_undefined = 0;
    const type_array = 7;
    const type_boolean = 1;
    const type_email = 2;
    const type_float = 3;
    const type_integer = 4;
    const type_path = 5;
    const type_string = 6;
    const type_url = 8;

    public $type_array = array(
    		self::type_undefined => '-UNDEFINED-',
        self::type_array => 'ARRAY',
    		self::type_boolean => 'BOOLEAN',
        self::type_email => 'E-MAIL',
    		self::type_float => 'FLOAT',
        self::type_integer => 'INTEGER',
    		self::type_path => 'PATH',
        self::type_string => 'STRING',
    		self::type_url => 'URL'
    		);

    private $createTables = false;
    private $message = '';

    const cfgDeveloperMode = 'cfgDeveloperMode';
    const cfgGoogleMapsAPIkey = 'cfgGoogleMapsAPIkey';
    const cfgMaxInvalidLogin = 'cfgMaxInvalidLogin';
    const cfgAddAppTab = 'cfgAddAppTab';
    const cfgCronjobKey = 'cfgCronjobKey';
    const cfgSessionID = 'cfgSessionID';
    const cfgSortContactList = 'cfgSortContactList';
    const cfgLimitContactList = 'cfgLimitContactList';
    const cfgConnectWBusers = 'cfgConnectWBusers';
    const cfgKITadmins = 'cfgKITadmins';
    const cfgMinPwdLen = 'cfgMinPwdLen';
    const cfgClearCompileDir = 'cfgClearCompileDir';
    const cfgAdditionalFields = 'cfgAdditionalFields';
    const cfgAdditionalNotes = 'cfgAdditionalNotes';
    const cfgContactLanguageDefault = 'cfgContactLanguageDefault';
    const cfgContactLanguageSelect = 'cfgContactLanguageSelect';
    const cfgNewsletterLanguageMarkers = 'cfgNewsletterLanguageMarkers';
    const cfgNewsletterAccountInfo = 'cfgNewsletterNoAccountInfo';

    public $config_array = array(
        array(
            'kit_label_cfg_google_maps_api_key',
            self::cfgGoogleMapsAPIkey,
            self::type_string,
            '',
            'kit_desc_cfg_google_maps_api_key'
            ),
        array(
            'kit_label_cfg_max_invalid_login',
            self::cfgMaxInvalidLogin,
            self::type_integer,
            '10',
            'kit_desc_cfg_max_invalid_login'
            ),
        array(
            'kit_label_cfg_add_app_tab',
            self::cfgAddAppTab,
            self::type_array,
            '',
            'kit_desc_cfg_add_app_tab'
            ),
        array(
            'kit_label_cfg_cronjob_key',
            self::cfgCronjobKey,
            self::type_string,
            '',
            'kit_desc_cfg_cronjob_key'
            ),
        array(
            'kit_label_cfg_session_id',
            self::cfgSessionID,
            self::type_string,
            'kit7543_',
            'kit_desc_cfg_session_id'
            ),
        array(
            'kit_label_cfg_sort_contact_list',
            self::cfgSortContactList,
            self::type_integer,
            '0',
            'kit_desc_cfg_sort_contact_list'
            ),
        array(
            'kit_label_cfg_limit_contact_list',
            self::cfgLimitContactList,
            self::type_integer,
            '50',
            'kit_desc_cfg_limit_contact_list'
            ),
        array(
            'kit_label_cfg_connect_wb_users',
            self::cfgConnectWBusers,
            self::type_boolean,
            '0',
            'kit_desc_cfg_connect_wb_users'
            ),
        array(
            'kit_label_cfg_kit_admins',
            self::cfgKITadmins,
            self::type_array,
            '',
            'kit_desc_cfg_kit_admins'
            ),
        array(
            'kit_label_cfg_min_pwd_len',
            self::cfgMinPwdLen,
            self::type_integer,
            7,
            'kit_desc_cfg_min_pwd_len'
            ),
        array(
            'kit_label_cfg_clear_compile_dir',
            self::cfgClearCompileDir,
            self::type_boolean,
            '0',
            'kit_desc_cfg_clear_compile_dir'
            ),
        array(
            'kit_label_cfg_additional_fields',
            self::cfgAdditionalFields,
            self::type_array,
            '',
            'kit_desc_cfg_additional_fields'
            ),
        array(
            'kit_label_cfg_additional_notes',
            self::cfgAdditionalNotes,
            self::type_array,
            '',
            'kit_desc_cfg_additional_notes'
            ),
    		array(
    				'kit_label_cfg_contact_language_default',
    				self::cfgContactLanguageDefault,
    				self::type_string,
    				'de',
    				'kit_desc_cfg_contact_language_default'
    				),
    		array(
    				'kit_label_cfg_contact_language_select',
    				self::cfgContactLanguageSelect,
    				self::type_string,
    				'locale',
    				'kit_desc_cfg_contact_language_select'
    				),
    		array(
    				'kit_label_cfg_newsletter_language_marker',
    				self::cfgNewsletterLanguageMarkers,
    				self::type_boolean,
    				'1',
    				'kit_desc_cfg_newsletter_language_marker'
    				),
        array(
            'kit_label_cfg_newsletter_account_info',
            self::cfgNewsletterAccountInfo,
            self::type_boolean,
            '1',
            'kit_desc_cfg_newsletter_account_info'
            )
        );

    public function __construct($createTables = false) {
        $this->createTables = $createTables;
        parent::__construct();
        $this->setTableName('mod_kit_config');
        $this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
        $this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
        $this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '" . self::type_undefined . "'");
        $this->addFieldDefinition(self::field_value, "VARCHAR(255) NOT NULL DEFAULT ''", false, false, true);
        $this->addFieldDefinition(self::field_label, "VARCHAR(64) NOT NULL DEFAULT 'ed_str_undefined'");
        $this->addFieldDefinition(self::field_description, "VARCHAR(255) NOT NULL DEFAULT 'ed_str_undefined'");
        $this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '" . self::status_active . "'");
        $this->addFieldDefinition(self::field_update_by, "VARCHAR(32) NOT NULL DEFAULT 'SYSTEM'");
        $this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->setIndexFields(array(self::field_name));
        $this->setAllowedHTMLtags('<a><abbr><acronym><span>');
        $this->checkFieldDefinitions();
        date_default_timezone_set(kit_cfg_time_zone);
        // Tabelle erstellen
        if ($this->createTables) {
            if (! $this->sqlTableExists()) {
                if (! $this->sqlCreateTable()) {
                    $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
                }
            }
        }
        if (! defined('KIT_INSTALL_RUNNING')) {
            // Default Werte garantieren
            if ($this->sqlTableExists()) {
                $this->checkConfig();
            }
        }
    } // __construct()


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
        return (bool) ! empty($this->message);
    } // isMessage


    /**
     * Aktualisiert den Wert $new_value des Datensatz $name
     *
     * @param $new_value STR - Wert, der uebernommen werden soll
     * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
     *
     * @return BOOL Ergebnis
     *
     */
    public function setValueByName($new_value, $name) {
        $where = array();
        $where[self::field_name] = $name;
        $config = array();
        if (! $this->sqlSelectRecord($where, $config)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
            return false;
        }
        if (sizeof($config) < 1) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_name, $name)));
            return false;
        }
        return $this->setValue($new_value, $config[0][self::field_id]);
    } // setValueByName()


    /**
     * Haengt einen Slash an das Ende des uebergebenen Strings
     * wenn das letzte Zeichen noch kein Slash ist
     *
     * @param STR $path
     * @return STR
     */
    public function addSlash($path) {
        $path = substr($path, strlen($path) - 1, 1) == "/" ? $path : $path . "/";
        return $path;
    }

    /**
     * Wandelt einen String in einen Float Wert um.
     * Geht davon aus, dass Dezimalzahlen mit ',' und nicht mit '.'
     * eingegeben wurden.
     *
     * @param STR $string
     * @return FLOAT
     */
    public function str2float($string) {
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        $float = floatval($string);
        return $float;
    }

    public function str2int($string) {
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        $int = intval($string);
        return $int;
    }

    /**
     * Ueberprueft die uebergebene E-Mail Adresse auf logische Gueltigkeit
     *
     * @param STR $email
     * @return BOOL
     */
    public function validateEMail($email) {
        //if(eregi("^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$", $email)) {
        // PHP 5.3 compatibility - eregi is deprecated
        if (preg_match("/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/i", $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Aktualisiert den Wert $new_value des Datensatz $id
     *
     * @param $new_value STR - Wert, der uebernommen werden soll
     * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
     *
     * @return BOOL Ergebnis
     */
    public function setValue($new_value, $id) {
        global $tools;
        $value = '';
        $where = array();
        $where[self::field_id] = $id;
        $config = array();
        if (! $this->sqlSelectRecord($where, $config)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
            return false;
        }
        if (sizeof($config) < 1) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_id, $id)));
            return false;
        }
        $config = $config[0];
        switch ($config[self::field_type]) :
            case self::type_array:
                // Funktion geht davon aus, dass $value als STR uebergeben wird!!!
                $worker = explode(",", $new_value);
                $data = array();
                foreach ($worker as $item) {
                    $data[] = trim($item);
                }
                ;
                $value = implode(",", $data);
                break;
            case self::type_boolean:
                $value = (bool) $new_value;
                $value = (int) $value;
                break;
            case self::type_email:
                if ($this->validateEMail($new_value)) {
                    $value = trim($new_value);
                } else {
                    $this->setMessage(sprintf(kit_msg_invalid_email, $new_value));
                    return false;
                }
                break;
            case self::type_float:
                $value = $this->str2float($new_value);
                break;
            case self::type_integer:
                $value = $this->str2int($new_value);
                break;
            case self::type_url:
            case self::type_path:
                $value = $this->addSlash(trim($new_value));
                break;
            case self::type_string:
                $value = (string) trim($new_value);
                // Hochkommas demaskieren
                $value = str_replace('&quot;', '"', $value);
                break;
        endswitch
        ;
        unset($config[self::field_id]);
        $config[self::field_value] = (string) $value;
        if (is_object($tools)) {
            $config[self::field_update_by] = $tools->getDisplayName();
        } else {
            $config[self::field_update_by] = 'SYSTEM';
        }
        $config[self::field_update_when] = date('Y-m-d H:i:s');
        if (! $this->sqlUpdateRecord($config, $where)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
            return false;
        }
        return true;
    } // setValue()


    /**
     * Gibt den angeforderten Wert zurueck
     *
     * @param $name - Bezeichner
     *
     * @return WERT entsprechend des TYP
     */
    public function getValue($name) {
        $result = '';
        $where = array();
        $where[self::field_name] = $name;
        $config = array();
        if (! $this->sqlSelectRecord($where, $config)) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
            return false;
        }
        if (sizeof($config) < 1) {
            $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_cfg_name, $name)));
            return false;
        }
        $config = $config[0];
        switch ($config[self::field_type]) :
            case self::type_array:
                $result = explode(",", $config[self::field_value]);
                break;
            case self::type_boolean:
                $result = (bool) $config[self::field_value];
                break;
            case self::type_email:
            case self::type_path:
            case self::type_string:
            case self::type_url:
                $result = (string) utf8_decode($config[self::field_value]);
                break;
            case self::type_float:
                $result = (float) $config[self::field_value];
                break;
            case self::type_integer:
                $result = (integer) $config[self::field_value];
                break;
            default:
                $result = utf8_decode($config[self::field_value]);
                break;
        endswitch
        ;
        return $result;
    } // getValue()


    public function checkConfig() {
        foreach ($this->config_array as $item) {
            $where = array();
            $where[self::field_name] = $item[1];
            $check = array();
            if (! $this->sqlSelectRecord($where, $check)) {
                $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
                return false;
            }
            if (sizeof($check) < 1) {
                // Eintrag existiert nicht
                $data = array();
                $data[self::field_label] = $item[0];
                $data[self::field_name] = $item[1];
                $data[self::field_type] = $item[2];
                $data[self::field_value] = $item[3];
                $data[self::field_description] = $item[4];
                $data[self::field_update_when] = date('Y-m-d H:i:s');
                $data[self::field_update_by] = 'SYSTEM';
                if (! $this->sqlInsertRecord($data)) {
                    $this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
                    return false;
                }
            }
        }
        return true;
    }

} // class dbKITcfg


?>