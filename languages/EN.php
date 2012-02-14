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

// try to include LEPTON class.secure.php to protect this file and the whole
// CMS!
if (defined('WB_PATH')) {
    if (defined('LEPTON_VERSION')) include (WB_PATH . '/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/framework/class.secure.php')) {
    include ($_SERVER['DOCUMENT_ROOT'] . '/framework/class.secure.php');
} else {
    $subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));
    $dir = $_SERVER['DOCUMENT_ROOT'];
    $inc = false;
    foreach ($subs as $sub) {
        if (empty($sub)) continue;
        $dir .= '/' . $sub;
        if (file_exists($dir . '/framework/class.secure.php')) {
            include ($dir . '/framework/class.secure.php');
            $inc = true;
            break;
        }
    }
    if (! $inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

if ('á' != "\xc3\xa1") {
	// important: language files must be saved as UTF-8 (without BOM)
	trigger_error('The language file <b>'.basename(__FILE__).'</b> is damaged, it must be saved <b>UTF-8</b> encoded!', E_USER_ERROR);
}

// Deutsche Modulbeschreibung
$module_description = 'dbKeepInTouch (KIT) is a contact details management system that provides a variety of centralized applications.';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Ralf Hertsch (phpManufaktur), sky writer';

define('kit_btn_abort', 'Abort');
define('kit_btn_edit', 'Edit');
define('kit_btn_export', 'Export');
define('kit_btn_import', 'Import');
define('kit_btn_mail_bcc', 'BCC Recipients:');
define('kit_btn_mail_from', 'from:');
define('kit_btn_mail_to', 'To:');
define('kit_btn_next_step', 'Next Step');
define('kit_btn_no', 'No');
define('kit_btn_ok', 'Okay');
define('kit_btn_preview', 'Preview');
define('kit_btn_register', 'Register');
define('kit_btn_save', 'Save');
define('kit_btn_send', 'Send');
define('kit_btn_yes', 'Yes');

define('kit_cfg_date_str', 'd.m.Y');
define('kit_cfg_date_time_str', 'd.m.Y - H:i:s');
define('kit_cfg_thousand_separator', '.');
define('kit_cfg_date_separator', '.');
define('kit_cfg_decimal_separator', ',');
define('kit_cfg_price', '%s €');
define('kit_cfg_euro', '%s EUR');
define('kit_cfg_time_zone', 'Europe/Berlin');

define('kit_cmd_nl_account_email', 'E-Mail Address');
define('kit_cmd_nl_account_first_name', 'First Name');
define('kit_cmd_nl_account_id', 'ID of the Subscriber (Account)');
define('kit_cmd_nl_account_last_name', 'Last Name');
define('kit_cmd_nl_account_login', 'URL: Link on the login dialog of the subscriber (Account)');
define('kit_cmd_nl_account_newsletter', 'Newsletter Subscription comma separated');
define('kit_cmd_nl_account_register_key', 'Registered subscribers of the key account');
define('kit_cmd_nl_account_title', 'Title of subscribers (Mr. or Ms.)');
define('kit_cmd_nl_account_title_academic', 'Title of the subscriber (for example, Dr. or Prof. ');
define('kit_cmd_nl_account_username', 'User name of the subscriber (user name)');
define('kit_cmd_nl_contact_id', 'ID of the entry in the Contact Database (Contact)');
define('kit_cmd_nl_content', 'The actual text of the newsletter will be inserted at this point');
define('kit_cmd_nl_kit_info', 'Gives information about KeepInTouch (KIT) from');
define('kit_cmd_nl_kit_release', 'Returns the release number of KeepInTouch (KIT) from');
define('kit_cmd_nl_newsletter_unsubscribe', 'URL: link to unsubscribe to the newsletter');
define('kit_cmd_nl_salutation', 'Greetings 1-10, the salutation variations are defined in the preferences (Salutation)');

define('kit_contact_access_internal', 'Internal');
define('kit_contact_access_public', 'Public');
define('kit_contact_category_wb_user', 'WB Users');
define('kit_contact_category_newsletter', 'Newsletter');
define('kit_contact_company_title_none', '');
define('kit_contact_company_title_to', 'To');
define('kit_contact_distribution_control', 'Control Group');
define('kit_contact_email_private', 'E-Mail, personal');
define('kit_contact_email_business', 'E-Mail, business');
define('kit_contact_internet_facebook', 'Facebook');
define('kit_contact_internet_homepage', 'Homepage');
define('kit_contact_internet_twitter', 'Twitter');
define('kit_contact_internet_xing', 'Xing');
define('kit_contact_newsletter_newsletter', 'Newsletter');
define('kit_contact_person_title_academic_dr', 'Dr.');
define('kit_contact_person_title_academic_none', '');
define('kit_contact_person_title_academic_prof', 'Prof.');
define('kit_contact_person_title_lady', 'Female');
define('kit_contact_person_title_mister', 'Male');
define('kit_contact_phone_fax', 'Fax');
define('kit_contact_phone_handy', 'Cell');
define('kit_contact_phone_phone', 'Telephone');
define('kit_contact_status_active', 'Active');
define('kit_contact_status_deleted', 'Deleted');
define('kit_contact_status_key_created', 'Key generated');
define('kit_contact_status_key_send', 'Key sent');
define('kit_contact_status_locked', 'Locked');
define('kit_contact_type_company', 'Company');
define('kit_contact_type_institution', 'Institution');
define('kit_contact_type_person', 'Person');

define('kit_contact_array_type_access', 'Contact sharing');
define('kit_contact_array_type_category', 'Category - Internal');
define('kit_contact_array_type_company_title', 'Company Title');
define('kit_contact_array_type_distribution', 'Category - Distribution');
define('kit_contact_array_type_email', 'E-Mail');
define('kit_contact_array_type_internet', 'Internet Adress');
define('kit_contact_array_type_newsletter', 'Category - Newsletter');
define('kit_contact_array_type_person_academic', 'Person Title');
define('kit_contact_array_type_person_title', 'Person Title');
define('kit_contact_array_type_phone', 'Telecommunications');
define('kit_contact_array_type_protocol', 'Protocol Type');
define('kit_contact_array_type_type', 'Contact Type');
define('kit_contact_array_type_undefined', '- undefined -');

define('kit_contact_address_type_business', 'Service');
define('kit_contact_address_type_delivery', 'Delivery');
define('kit_contact_address_type_post_office_box', 'Post Office Box');
define('kit_contact_address_type_private', 'Private');
define('kit_contact_address_type_undefined', '');

define('kit_contact_protocol_type_call', 'Phone Call');
define('kit_contact_protocol_type_email', 'E-Mail');
define('kit_contact_protocol_type_meeting', 'Meeting');
define('kit_contact_protocol_type_memo', 'Memo');
define('kit_contact_protocol_type_newsletter', 'Newsletter');
define('kit_contact_protocol_type_undefined', '- open -');

define('kit_country_austria', 'Austria');
define('kit_country_germany', 'Germany');
define('kit_country_suisse', 'Suisse');
define('kit_country_undefined', '');

define('kit_desc_cfg_add_app_tab', 'Additional TAB \'s Add to simply switch to other add-ons can. TAB \'s separated by comma, building identifier | URL');
define('kit_desc_cfg_additional_fields', 'Sie können bis zu 5 zusätzliche Eingabefelder in KIT definieren, diese werden im Kontakt Dialog angezeigt und in kitForm zur Verfügung gestellt.<br/>Definieren Sie die Felder in der Form <NUMMER>|<BEZEICHNUNG> und trennen Sie die Felder mit einem Komma, z.B.: "1|Lieblingsfarbe,2|Haustier".');
define('kit_desc_cfg_additional_notes', 'Sie können 2 zusätzliche Notizfelder in KIT definieren, diese werden im Kontakt Dialog angezeigt und in kitForm zur Verfügung gestellt.<br />Definieren Sie die Felder in der Form <NUMMER>|<BEZEICHNUNG> und trennen Sie die Felder mit einem Komma, z.B.: "1|Letztes Telefonat,2|Diagnose".');
define('kit_desc_cfg_clear_compile_dir', 'Sie können die von der Dwoo Template Engine kompilierten Templates zurücksetzen und ein erneutes Schreiben erzwingen. Setzen Sie den Schalter dazu auf 1, KIT setzt den Schalter nach dem Reset automatisch zurück.');
define('kit_desc_cfg_connect_wb_users', 'KIT You can connect with the WB user management. If set, automatically assumes KIT newly created user and lock in KIT blocked in user administration, or deleted users. Contacts to whom you will assign to the category <b>catWBUser KIT</b> in the user administration with the WB group <b>kitContact</b> Remove created the assignment, the contacts are locked in the user management. <i>administrators</i> can not be associated with KIT for security reasons.');
define('kit_desc_cfg_cronjob_key', 'To prevent cronjobs to be executed by a simple call to the <b>cronjob.php</b>, the specified keys passed as parameters. The call of the file is <b>cronjob.php?<i>key=KEY</i></b>.');
define('kit_desc_cfg_developer_mode', 'Allows the programmer to add configuration parameters.');
define('kit_desc_cfg_google_maps_api_key', 'For the use and display the cards you need a <a href="http://code.google.com/intl/de-DE/apis/maps/signup.html" target="_blank">Google Maps API Key</a>.');
define('kit_desc_cfg_kit_admins', '<b>KIT administrators</b> have extended rights within KIT and KIT in extensions and receive system notifications. Enter the <b>primary e-mail addresses</b> to the administrators, separated by commas. <b>Administrators must be active registered his contacts in KIT</b>.');
define('kit_desc_cfg_kit_request_link', '<b>kit.php</b> with all queries will return data, or calls for dialogue. The file is located in the directory /modules/kit, but it can also be copied to another location, eg in the root directory');
define('kit_desc_cfg_kit_response_page', 'Kit needed for the display of the dialogue and references a separate page');
define('kit_desc_cfg_limit_contact_list', 'Determine how many entries in the contacts list to display per page.');
define('kit_desc_cfg_max_invalid_login', 'Maximum number of failed login attempts by users before the account is locked.');
define('kit_desc_cfg_min_pwd_len', 'Mindeslänge used the password');
define('kit_desc_cfg_nl_adjust_register', 'Smooths the call of the newsletter dialogue table with kit_register kit_contact from (Use this setting only when requested by the Support! 0 = OFF, 1 = ON ).');
define('kit_desc_cfg_nl_max_package_size', 'Sets the maximum number of recipients per packet during the newsletters, the individual packets are processed by a cron job by degrees, the highest allowable value is 100..');
define('kit_desc_cfg_nl_set_time_limit', 'Determines the duration in seconds that the newsletter script max may. need to send out the mails. If the value is too low, you will get a runtime error, increase the value as required in steps (DEFAULT = 60 ).');
define('kit_desc_cfg_nl_salutation', 'You can define 10 different greetings that you can use with <b>salutation_01 {$}</b> to <b>salutation_10 {$}</b> within the newsletter. The greetings consist from 3 definitions. <b>male</b> <b>, female</b> and <b>neutral</b> The definitions are supported by a pipe symbol can be separated from each other within the definitions KIT CMDs. use. ');
define('kit_desc_cfg_nl_simulate', 'runs through the entire shipping process, the newsletter <b>without</b> to send the mails actually (0 = OFF, 1 = ON ).');
define('kit_desc_cfg_register_data_dlg', 'dialogue, which allows visitors to manage their data');
define('kit_desc_cfg_register_dlg', 'dialogue, which is invoked when users want to register or order a newsletter');
define('kit_desc_cfg_register_dlg_unsubscribe', 'dialogue, which is invoked when a subscriber wants to unsubscribe from a newsletter or more newsletters');
define('kit_desc_cfg_session_id', 'ID to uniquely identify the session variables are used by KeepInTouch.');
define('kit_desc_cfg_sort_contact_list', 'default setting for sorting the contact list: 0 = unsorted, 1 = E-mail ... 3 = last name - the possible digits are displayed in the drop down list for sorting. ');
define('kit_desc_cfg_use_captcha', 'Specify whether to use the dialogues in front CAPTCHA spam protection to');
define('kit_desc_cfg_use_custom_files', 'If set, you can use individually customized templates and language files, the files will be "custom." prefixed, eg "custom.DE.php", these files are not overwritten during an update. ');

define('kit_error_blank_title', '<p>The page must contain a title</p>');
define('kit_error_cfg_id', '<p>The configuration record with the ID <b>%05d</b> could not be read</p>');
define('kit_error_cfg_name', '<p>to the identifier <b>%s</b> has found a configuration record</p>');
define('kit_error_create_dir', '<p>The directory<br/><br/>%s</b><br />could not be created</p>');
define('kit_error_create_file', '<p>Can\'t create the file <b>%s</b>!</p>');
define('kit_error_delete_access_file', '<p>access <b>%s</b> could not be deleted</p>');
define('kit_error_dlg_missing', '<p>The requested dialog <b>%s</b> was not found</p>');
define('kit_error_email_missing', '<p>There no e-mail address was given</p>');
define('kit_error_get_csv', '<p>errors when reading the CSV file</p>');
define('kit_error_google_maps_api_key_missing', '<p>card can not be displayed, there is no Google Maps API key defined</p>');
define('kit_error_import_massmail_grp_missing', '<p>The table with the mass mail groups was not found</p>');
define('kit_error_import_massmail_missing_vars', '<p>There not all the variables that were to import Massmail pass data.</p>');
define('kit_error_invalid_id', '<p>It was be no valid ID</p>');
define('kit_error_item_id', '<p><b>record with the id %s</b> was not found.</p>');
define('kit_error_lepton_user_connection_inactive', '<p>Die Verbindung von KeepInTouch (KIT) zur LEPTON Benutzerverwaltung ist nicht aktiv. Bitte prüfen Sie die KIT Einstellungen und aktivieren Sie den Schalter "Mit LEPTON Benutzern verbinden".</p>');
define('kit_error_mail_init_settings', '<p>WebsiteBaker configuration settings for the mail could not be loaded.</p>');
define('kit_error_map_address_invalid', '<p>The address <b>%s</b> was not found</p>');
define('kit_error_move_csv_file', '<p><b>temporary file %s</b> could not be moved to the destination directory</p>');
define('kit_error_newsletter_tpl_id_invalid', '<p>newsletter template with the ID <b>%03d</b> was not found</p>');
define('kit_error_no_provider_defined', '<p>You do not have a service defined Set this first set on "Settings" and "service".</p>');
define('kit_error_insufficient_permissions', '<p>you do not have permission to modify this page</p>');
define('kit_error_open_file', '<p><b>File %s</b> could not be opened</p>');
define('kit_error_page_exists', '<p>The page with the basic designation <b>%s</b> already exists</p>');
define('kit_error_page_not_found', '<p>The side with the PAGE_ID <b>%d</b> was not found</p>');
define('kit_error_please_update',	'<p>Bitte aktualisieren Sie <b>%s</b>! Installiert ist die Version <b>%s</b>, benoetigt wird die Version <b>%s</b> oder hoeher!</p>');
define('kit_error_preview_id_invalid', '<p><b>preview with the id %05d</b> was not found</p>');
define('kit_error_preview_id_missing', '<p>There is no preview ID was specified</p>');
define('kit_error_record_for_email_exists', '<p>There is already a record with <b>ID %03d</b> for the e-mail address <b>%s</b>, please update this record instead of one To create new</p>');
define('kit_error_register_email_already_exists', '<p>Fatal error: There is already a registration record for the e-mail address <b>%s</b>.</p>');
define('kit_error_register_contact_id_invalid', '<p>The KIT ID <b>%s</b> was not found in the registry table - the ID does not exists or is locked!</p>');
define('kit_error_request_dlg_invalid_id', '<p>[kitRequest] The dialogue with the <b>ID %03d</b> was not found, operation aborted</p>');
define('kit_error_request_dlg_invalid_name', '<p>[kitRequest] The dialogue with the class name <b>%s</b> was not found, operation aborted</p>');
define('kit_error_request_invalid_action', '<p>[kitRequest] <b>The parameter %s=%s</b> is invalid, operation aborted</p>');
define('kit_error_request_link_action_unknown', '<p>[kitRequest] for the link type <b>%s</b> no action is specified</p>');
define('kit_error_request_link_invalid', '<p>[kitRequest] There is no valid link was given</p>');
define('kit_error_request_link_type', '<p>The link type <b>%s</b> of this process can not be processed.</p>');
define('kit_error_request_link_unknown', '<p>[kitRequest] The link "<b>%s</b>" is unfortunately not linked</p>');
define('kit_error_request_missing_parameter', '<p>[kitRequest] The parameter <b>%s</b> was not specified, operation aborted</p>');
define('kit_error_request_no_action', '<p>[kitRequest] were not passing appropriate parameters</p><p><b>Note:</b> This error message is displayed even if you tried to open a dialogue to reload (Reload <i>Lock </ i >).</p> ');
define('kit_error_request_parameter_incomplete', '<p>[kitRequest] The given parameters are not complete, the command could not be executed.</p>');
define('kit_error_salutation_definition', '<p><b>FEHLER:</b> Bitte prüfen Sie die Grußformel <b>%s</b>, sie muss 3 Anreden enthalten: männlich, weiblich und neutral, die jeweils durch eine Pipe "|" getrennt werden.</p>');
define('kit_error_undefined', '<p>There one of undefined error occurred please contact support about your problem.</p>');

define('kit_header_addresses', 'addresses, map');
define('kit_header_categories', 'Categories');
define('kit_header_cfg', 'settings');
define('kit_header_cfg_array', 'Edit lists, and add');
define('kit_header_cfg_description', 'description');
define('kit_header_cfg_identifier', 'identifier');
define('kit_header_cfg_import', 'Import Data');
define('kit_header_cfg_label', 'label');
define('kit_header_cfg_typ', 'type');
define('kit_header_cfg_value', 'value');
define('kit_header_communication', 'communication');
define('kit_header_contact', 'Contact');
define('kit_header_contact_list', 'contact list');
define('kit_header_email', 'Send email');
define('kit_header_error', 'KeepInTouch (KIT) Error Message');
define('kit_header_help_documentation', 'Help and Documentation');
define('kit_header_import_step_1', 'contact import');
define('kit_header_import_step_2', 'Imported assign fields');
define('kit_header_nl_cronjob_protocol_list', 'Running Jobs');
define('kit_header_nl_cronjob_active_list', 'Not yet executed jobs');
define('kit_header_preview', 'preview');
define('kit_header_protocol', 'log');
define('kit_header_provider', 'service');
define('kit_header_template', 'Edit Templates');

define('kit_help_import_step_1', 'Use the online documentation to learn more about the <a href="http://phpmanufaktur.de/kit/help/import" target="_blank">data import KIT</a>.');
define('kit_help_import_step_2', 'Use the online documentation to learn more about the <a href="http://phpmanufaktur.de/kit/help/import" target="_blank">data import KIT</a>.');

define('kit_hint_error_msg', '<p>suspect If you get this error message several times or that this is a malfunction, please connect to the system administrator to</p>');

define('kit_imp_con_pers_title', 'person: Title (Mr, Ms)');
define('kit_imp_con_pers_title_academic', 'person: Title (Dr., Prof.)');
define('kit_imp_con_pers_first_name', 'person: first name (s)');
define('kit_imp_con_pers_last_name', 'person: name');
define('kit_imp_con_pers_function', 'Person: function, activity');
define('kit_imp_con_pers_addr_street', 'Person: Address');
define('kit_imp_con_pers_addr_zip', 'Person: Zip Code');
define('kit_imp_con_pers_addr_city', 'Person: City');
define('kit_imp_con_pers_addr_country', 'Person: Country');
define('kit_imp_con_pers_email_1', 'person: E-Mail 1');
define('kit_imp_con_pers_email_2', 'person: E-Mail 2');
define('kit_imp_con_pers_phone', 'Person: Phone');
define('kit_imp_con_pers_handy', 'Person: Mobile');
define('kit_imp_con_pers_fax', 'Person: Fax');

define('kit_imp_con_comp_name', 'Company Name');
define('kit_imp_con_comp_department', 'Company: Department');
define('kit_imp_con_comp_additional', 'Company: Extra address');
define('kit_imp_con_comp_addr_street', 'Company: Address');
define('kit_imp_con_comp_addr_zip', 'Company: Zip Code');
define('kit_imp_con_comp_addr_city', 'Company: City');
define('kit_imp_con_comp_addr_country', 'Company: Country');
define('kit_imp_con_comp_email_1', 'Company: E-Mail 1');
define('kit_imp_con_comp_email_2', 'Company: E-Mail 2');
define('kit_imp_con_comp_phone', 'Company: Phone');
define('kit_imp_con_comp_handy', 'Company: Mobile');
define('kit_imp_con_comp_fax', 'Company: Fax');

define('kit_imp_con_www', 'Contact: Internet');
define('kit_imp_no_selection', '- not assigned -');

define('kit_info', '<a href="http://phpmanufaktur.de/kit" target="_blank">KeepInTouch (KIT)</a> %s Release - Open Source CRM for WebsiteBaker - (c) 2010 by <a href="http://phpmanufaktur.de" phpManufaktur target="_blank"></a>');

define('kit_intro_cfg', '<p>Edit the settings for <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_add_item', '<p>adding entries to the configuration is only useful if the values correspond with the program.</p>');
define('kit_intro_cfg_array', '<p>edit the lists for different <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_import', '<p>With this dialog you can import data from other applications in <b>KeepInTouch</b>.</p>');
define('kit_intro_cfg_provider', '<p>Select a service provider to edit, or create a new service provider.</p><p>services are used for sending e-mails in KeepInTouch defined service independent of the system settings. - You can assign a specific service provider newsletters or forms</p><p>Please note that the service provider to send you <b>Forms (kit form)</b> use a <b>Relaying </ b must admit> that it send to be possible over the specified e-mail account E-mails with a different sender address. If this is not possible, choose to send out forms for a service with no STMP authentication kit used in this case, the PHP mailer to send out notifications.</p>');
define('kit_intro_contact', '<p>edit Use this dialog to the contact</p>');
define('kit_intro_contact_list', '<p>This list shows the available contacts, depending on the selected order.</p>');
define('kit_intro_cronjobs', '<p>KeepInTouch (kit required) for sending the newsletter of a cron job at regular intervals, eg every 5 minutes, the control file <b>/modules/kit/cronjob.php</b> calls.</p><p>In this way it is ensured that a large number of newsletters KIT continuously sent in smaller packets. By the modest shipping also prevents your press release is classified as a critical provider of your mass mailing.</p><p>If you are missing on your Web server can run cron jobs, just use a free service like <a href="http://www.cronjob.de" target="_blank">cronjob.de</a> for the activation of KIT</p>');
define('kit_intro_email', '<p>With this dialog you can create and send e-mails and.</p>');
define('kit_intro_import_fields', '<p>Assign the fields from the CSV file into the appropriate fields KeepInTouch</p><p>You must use not assign any fields.</p>');
define('kit_intro_import_start', '<p>You can contact data from other applications in <i><b>C</b>omma-<b>S</b>eparated <b>V</b>alues​​</i> (CSV) format in KeepInTouch</p><p>This dialogue leads them in several steps through the import process.</p>');
define('kit_intro_newsletter_cfg', '<p>you edit the specific settings for the newsletter module KeepInTouch.</p>');
define('kit_intro_newsletter_create', '<p>Create a newsletter and send it to their subscribers.</p>');
define('kit_intro_newsletter_commands', '<p>commands and variables are executed at run time and inserted into the template.</p><p>A simple click to the appropriate command at the cursor position in the HTML code to insert.</p>');
define('kit_intro_newsletter_template', '<p>from Select a newsletter template to edit or create a new template.</p>');
define('kit_intro_nl_cronjob_active_list', '<p>The list shows you the current cron jobs that have not yet been executed.</p>');
define('kit_intro_nl_cronjob_protocol_list', '<p>This list shows the last 200 jobs performed newsletter, which carried out the KeepInTouch cronjob.</p>');
define('kit_intro_preview', '<p>Check the preview in the <b>HTML</b> and in the <b>TEXT ONLY</b> view.</p>');
define('kit_intro_register_installation', '<p>KeepInTouch Register your installation</p><p>This allows you to charge the full functionality of KeepInTouch test.</p>');

define('kit_label_add_new_address', 'Add Additional Address');
define('kit_label_additional_fields', 'Benutzerdefinierte Felder');
define('kit_label_address_city', 'city');
define('kit_label_address_street', 'street');
define('kit_label_address_type', 'type the address');
define('kit_label_address_type_private', 'private');
define('kit_label_address_type_business', 'business');
define('kit_label_address_zip', 'Zip');
define('kit_label_address_zip_city', 'ZIP & city');
define('kit_label_admin', 'Administration');
define('kit_label_archive_id', 'archive ID');
define('kit_label_audience', 'Recipient');
define('kit_label_birthday', 'birthday');
define('kit_label_categories', 'Intern'); // instead of category
define('kit_label_cfg_add_app_tab', 'Additional TAB\'s add');
define('kit_label_cfg_additional_fields', 'Zusätzliche KIT Felder');
define('kit_label_cfg_additional_notes', 'Zusätzliche KIT Notizen');
define('kit_label_cfg_array_add_items', 'Add more items');
define('kit_label_cfg_clear_compile_dir', 'Templates zurücksetzen');
define('kit_label_cfg_connect_wb_users', 'Connect to WB users');
define('kit_label_cfg_cronjob_key', 'key for cronjobs');
define('kit_label_cfg_developer_mode', 'Developer Mode');
define('kit_label_cfg_google_maps_api_key', 'Google Maps API Key');
define('kit_label_cfg_kit_admins', 'KIT administrators');
define('kit_label_cfg_kit_request_link', 'KIT Request Link');
define('kit_label_cfg_kit_reponse_page', 'KIT response page');
define('kit_label_cfg_limit_contact_list', 'max entries contact list. ');
define('kit_label_cfg_max_invalid_login', 'Maximum login attempts');
define('kit_label_cfg_min_pwd_len', 'min password length');
define('kit_label_cfg_nl_adjust_register', 'kit_register Syndicate');
define('kit_label_cfg_nl_max_package_size', 'Max packet size');
define('kit_label_cfg_nl_salutation', 'salutation');
define('kit_label_cfg_nl_set_time_limit', 'Maximum execution time');
define('kit_label_cfg_nl_simulate', 'Shipping simulate');
define('kit_label_cfg_register_data_dlg', 'User, Data Manage');
define('kit_label_cfg_register_dlg', 'user registration');
define('kit_label_cfg_register_dlg_unsubscribe', 'users unsubscribe newsletter');
define('kit_label_cfg_session_id', 'Session ID');
define('kit_label_cfg_sort_contact_list', 'Contact list sort');
define('kit_label_cfg_temp_dir', 'Temporary directory');
define('kit_label_cfg_use_captcha', 'Use CAPTCHA');
define('kit_label_cfg_use_custom_files', 'Custom files allow');
define('kit_label_checksum', 'checksum');
define('kit_label_contact_access', 'contact sharing');
define('kit_label_contact_edit', 'Edit Contact');
define('kit_label_contact_email', 'email');
define('kit_label_contact_email_retype', 'E-Mail wiederholen');
define('kit_label_contact_fax', 'Fax');
define('kit_label_contact_phone', 'Phone');
define('kit_label_contact_phone_mobile', 'Mobile');
define('kit_label_contact_since', 'Contact Date');
define('kit_label_contact_note', 'Notes');
define('kit_label_company_additional', 'add');
define('kit_label_company_department', 'dept');
define('kit_label_contact_status', 'Contact Status');
define('kit_label_contact_identifier', 'Contact identifier');
define('kit_label_company_name', 'Company');
define('kit_label_company_title', 'Title');
define('kit_label_contact_type', 'contact type');
define('kit_label_country', 'Country');
define('kit_label_csv_export', 'CSV Export');
define('kit_label_csv_import', 'Import CSV');
define('kit_label_distribution', 'distribution');
define('kit_label_enable_relaying', 'Use Relaying');
define('kit_label_free_field_1', 'Freies Datenfeld 1');
define('kit_label_free_field_2', 'Freies Datenfeld 2');
define('kit_label_free_field_3', 'Freies Datenfeld 3');
define('kit_label_free_field_4', 'Freies Datenfeld 4');
define('kit_label_free_field_5', 'Freies Datenfeld 5');
define('kit_label_free_note_1', 'Freies Textfeld 1');
define('kit_label_free_note_2', 'Freies Textfeld 2');
define('kit_label_html_format', 'HTML Format');
define('kit_label_id', 'id');
define('kit_label_identifier', 'identifier');
define('kit_label_image', 'image');
define('kit_label_import_action', '');
define('kit_label_import_charset', 'charset');
define('kit_label_import_csv_file', 'CSV file');
define('kit_label_import_from', 'Import');
define('kit_label_import_separator', 'separator');
define('kit_label_job_id', 'Job id');
define('kit_label_job_created', 'commissioned');
define('kit_label_job_process', 'process');
define('kit_label_job_count', 'e-mails TARGET');
define('kit_label_job_done', 'Executed');
define('kit_label_job_time', 'Duration (sec.)');
define('kit_label_job_send', 'e-mails IS');
define('kit_label_kit_id', 'ID KIT');
define('kit_label_last_changed_by', 'Last Modified');
define('kit_label_list_sort', 'list sorted by');
define('kit_label_mail_bcc', '<i>BCC</i> <b>receiver</b>');
define('kit_label_mail_from', 'sender');
define('kit_label_mail_subject', 'Subject');
define('kit_label_mail_text', 'Message');
define('kit_label_mail_to', 'Recipient');
define('kit_label_map', '');
define('kit_label_massmail', 'mass mail');
define('kit_label_newsletter', 'newsletter');
define('kit_label_newsletter_archive_select', 'Load from Archive');
define('kit_label_newsletter_commands', 'commands and variables');
define('kit_label_newsletter_tpl_desc', 'description');
define('kit_label_newsletter_tpl_html', 'HTML code');
define('kit_label_newsletter_tpl_name', 'name');
define('kit_label_newsletter_tpl_select', 'template');
define('kit_label_newsletter_tpl_text', 'TEXT ONLY');
define('kit_label_newsletter_tpl_text_preview', '<b>TEXT ONLY</b><br /><span style="font-size:smaller;">The contents are wrapped in this list does not automatically</ span>');
define('kit_label_password', 'password');
define('kit_label_password_new', 'New password');
define('kit_label_password_retype', 'Password');
define('kit_label_person_title', 'Title');
define('kit_label_person_title_academic', 'title');
define('kit_label_person_first_name', 'first name');
define('kit_label_person_last_name', 'Last Name');
define('kit_label_person_function', 'Function / Position');
define('kit_label_protocol', 'log');
define('kit_label_protocol_date', 'Date');
define('kit_label_protocol_members', 'participant');
define('kit_label_protocol_memo', 'Entry');
define('kit_label_protocol_type', 'Contact');
define('kit_label_provider', 'service');
define('kit_label_provider_email', 'e-mail service provider');
define('kit_label_provider_identifier', 'letter');
define('kit_label_provider_name', 'Name of service');
define('kit_label_provider_remark', 'Notes');
define('kit_label_provider_response', 'reply-to address (n)');
define('kit_label_provider_select', 'Select Service Provider');
define('kit_label_provider_smtp_auth', 'SMTP authentication');
define('kit_label_provider_smtp_user', 'SMTP Username');
define('kit_label_provider_smtp_host', 'SMTP host name');
define('kit_label_register_confirmed', 'Registrierung, Bestätigung');
define('kit_label_register_date', 'Registrierung, Datum');
define('kit_label_register_key', 'Registrierung, Schlüssel');
define('kit_label_register_login_errors', 'Login Fehler');
define('kit_label_register_login_locked', 'Login gesperrt');
define('kit_label_register_password_1', 'Neues Passwort');
define('kit_label_register_password_2', 'Passwort wiederholen');
define('kit_label_register_status', 'Registrierung, Status');
define('kit_label_standard', 'default');
define('kit_label_status', 'status');
define('kit_label_subscribe', 'Login');
define('kit_label_type', 'type');
define('kit_label_unsubscribe', 'logout');
define('kit_label_value', 'value');

define('kit_list_sort_city', 'city');
define('kit_list_sort_company', 'Company');
define('kit_list_sort_deleted', 'deleted items');
define('kit_list_sort_email', 'email');
define('kit_list_sort_firstname', 'first name');
define('kit_list_sort_lastname', 'Last Name');
define('kit_list_sort_locked', 'locked entries');
define('kit_list_sort_phone', 'Number');
define('kit_list_sort_street', 'street');
define('kit_list_sort_unsorted', '- unsorted -');

define('kit_msg_activation_key_invalid', '<p>Aktiverungskey passed is invalid</p>');
define('kit_msg_activation_key_used', '<p>The activation code has already been used and is invalid.</p>');
define('kit_msg_account_locked', '<p>This account is locked please contact the Service.</p>');
define('kit_msg_address_deleted', '<p>An address record to the contact with the <b>ID %05d</b> deleted.</p>');
define('kit_msg_address_insert', '<p>the contact was added to a new address.</p>');
define('kit_msg_address_invalid', '<p>The address can not be assumed that data are not sufficient.<br /> Please enter <i>street, city and ZIP</i> or street and town <i>< / i> or just <i>City</i>.</p>');
define('kit_msg_address_update', '<p>An address to the contact record with the ID <b>%05d</b> have been updated.</p>');
define('kit_msg_categories_changed', '<p>The categories were changed.</p>');
define('kit_msg_cfg_add_exists', '<p>The configuration data set with the identifier <b>%s</b> already exists and can not be added again</p>');
define('kit_msg_cfg_add_incomplete', '<p>hinzuzufgende new configuration data set is incomplete, please check your details</p>');
define('kit_msg_cfg_add_success', '<p>The configuration data set with the <b>ID #%05d</b> and the identifier <b>%s</b> added.</p>');
define('kit_msg_cfg_array_item_updated', '<p>The entry with the ID <b>%05d</b> has been updated.</p>');
define('kit_msg_cfg_array_item_add', '<p>The entry with the ID <b>%05d</b> added.</p>');
define('kit_msg_cfg_array_identifier_in_use', '<p>The identifier <b>%s</b> is already used by the <b>ID %05d</b> and can not be assumed. Please attach a different set identifier.</p>');
define('kit_msg_cfg_clear_compile_dir', '<p>Es wurden insgesamt <b>%s</b> kompilierte Templates zurückgesetzt.</p>');
define('kit_msg_cfg_csv_export', '<p>The configuration data as <b>%s</b> saved in / MEDIA directory.</p>');
define('kit_msg_cfg_id_updated', '<p>The configuration data set with the <b>ID #%05d</b> and the identifier <b>%s</b> has been updated</p>');
define('kit_msg_contact_deleted', '<p>Contact with the <b>ID %05d</b> deleted.</p>');
define('kit_msg_contact_insert', '<p>Contact with the <b>ID %05d</b> was successfully created and saved.</p>');
define('kit_msg_contact_minimum_failed', '<p><b>record can not be backed up</b>, because the minimum requirements are not met.<br />Please enter <i>least</i> either an e <i>-mail address or</i> a company name and a city or <i></i> a company name and a phone number or <i></i> a last name and a city or <i></i> a last name and a phone number.</p>');
define('kit_msg_contact_update', '<p>Contact with the <b>ID %05d</b> has been updated successfully.</p>');
define('kit_msg_cronjob_last_call', '<p>There no jobs to be processed.</p><p>The cron job control file was last accessed on <b>%s</b>.</p>');
define('kit_msg_csv_file_moved', '<p>CSV file <b>%s</b> has been secured in the import directory as <b>%s</b>.</p>');
define('kit_msg_csv_first_row_empty', '<p>The first line of the CSV file <b>%s</b> is empty then the headers were expected.</p>');
define('kit_msg_csv_no_cols', '<p>CSV file <b>%s</b> contains no columns, please check the file!</p>');
define('kit_msg_csv_no_file_transmitted', '<p>There is no CSV file has been sent.</p>');
define('kit_msg_email_added', '<p>The e-mail address <b>%s</b> added.</p>');
define('kit_msg_email_changed', '<p>The e-mail address <i>%s</i> has been changed to <b>%s</b>.</p>');
define('kit_msg_email_deleted', '<p>The e-mail address <b>%s</b> deleted.</p>');
define('kit_msg_email_invalid', '<p>The e-mail address <b>%s</b> is not valid, please check your entry.</p>');
define('kit_msg_email_type_changed', '<p>type for the e-mail address <b>%s</b> has been changed.</p>');
define('kit_msg_internet_added', '<p>The Internet address <b>%s</b> added.</p>');
define('kit_msg_internet_changed', '<p>The Internet address <i>%s</i> was in <b>%s</b> changed.</p>');
define('kit_msg_internet_deleted', '<p>The Internet address <b>%s</b> deleted.</p>');
define('kit_msg_internet_invalid', '<p>The Internet address <b>%s</b> is not valid, please check your entry.</p>');
define('kit_msg_internet_type_changed', '<p>type the web address <b>%s</b> has been changed.</p>');
define('kit_msg_invalid_email', '<p>The e-mail address <b>%s</b> is not valid, please check your entry.</p>');
define('kit_msg_invalid_id', '<p>It was be no valid ID</p>');
define('kit_msg_login_locked', '<p>The account for user <b>%s</b> has too many failed login attempts, and is locked. Please contact the webmaster.</p>');
define('kit_msg_login_password_invalid', '<p>The password is not correct.</p>');
define('kit_msg_login_status_fail', '<p>The account for user <b>%s</b> is not active, please contact the webmaster.</p>');
define('kit_msg_login_user_unknown', '<p>The user <b>%s</b> is not known or if the account is not enabled for an application.</p>');
define('kit_msg_mail_incomplete', '<p>The information is incomplete: email sender, email receiver, selecting a category, subject and text must be set.</p>');
define('kit_msg_mail_send_error', '<p>The e-mail could not be sent, there are a total <b>error %d</b> occurred, the error message is: <br />%s </ b ></p>');
define('kit_msg_mail_send_success', '<p>The e-mail was sent successfully.</p>');
define('kit_msg_mails_send_success', '<p>There were <b>%d</b> E-mails sent successfully.</p>');
define('kit_msg_mails_send_errors', '<p>There total <b>%d</b> error occurred with the following messages:</p>%s');
define('kit_msg_massmail_not_installed', '<p>mass mail is not installed.</p>');
define('kit_msg_massmail_group_no_data', '<p>The mass mail <b>group with ID%d</b> contains no records.</p>');
define('kit_msg_massmail_email_skipped', '<p>The following e-mail addresses are used in the stated KIT records already, and were therefore ignored <b></b>:%s</p>');
define('kit_msg_massmail_no_emails_imported', '<p><b>no e-mail addresses were taken</b></p>');
define('kit_msg_massmail_emails_imported', '<p>There <p>total <b>%d were acquired e-mail addresses</b> as a separate rows:%s</p>');
define('kit_msg_newsletter_account_not_activated', '<p>the e-mail address <b>%s</b> There is already an account that has not Activates Please first enable this account.</p>');
define('kit_msg_newsletter_account_locked', '<p>account for the e-mail address <b>%s</b> is currently locked, please contact the Service.</p>');
define('kit_msg_newsletter_adjust_register', '<p>The command <b>cfgAdjustRegister</b> has been <b>%s</b> run</p>');
define('kit_msg_newsletter_new_no_groups', '<p>Please select one or more groups <b>Newsletter</b> or <b>distribution groups</b></p>');
define('kit_msg_newsletter_new_no_html', '<p>promising us you give the newsletter text in HTML format <b></b></p>');
define('kit_msg_newsletter_new_no_provider', '<p>promising us choose a service provider <b></b> for sending the newsletter out</p>');
define('kit_msg_newsletter_new_no_subject', '<p>Please enter a subject for the newsletter</p>');
define('kit_msg_newsletter_new_no_template', '<p>Please select a <b>Template</b> for sending the newsletter out</p>');
define('kit_msg_newsletter_new_no_text', '<p><b>TEXT ONLY</b> format was generated automatically, please check the output</p>');
define('kit_msg_newsletter_new_packages_created', '<p>There were a total of <b>%d packages</b> prepared for shipping.</p>');
define('kit_msg_newsletter_no_abonnement', '<p>There is no <p>newsletter for the e-mail address <b>%s</b> were ordered</p>');
define('kit_msg_newsletter_simulate_mailing', '<p>SIMULATION MODE ACTIVE</b> - it will not send newsletters</p>');
define('kit_msg_newsletter_tpl_added', '<p>The template <b>%s</b> added.</p>');
define('kit_msg_newsletter_tpl_changed', '<p>The template <b>%s</b> has been updated.</p>');
define('kit_msg_newsletter_tpl_cmd_content', '<p>record is not saved, the template must contain at least the wildcard <b>%s</b> At this point, the actual content of the newsletter will be inserted.</p>');
define('kit_msg_newsletter_tpl_minimum_failed', '<p>record is not saved, you must have at least one identifier <b></b> and the HTML code <b></b> state</p>');
define('kit_msg_newsletter_tpl_missing', '<p><b>still no template for sending newsletters to create.</b></p>Please first create a template before you create a newsletter.</p>');
define('kit_msg_newsletter_tpl_text_inserted', '<p><b>TEXT ONLY</b> output of the template has been generated automatically, please check the output.</p>');
define('kit_msg_newsletter_tpl_unchanged', '<p>The template with the ID <b>%05d</b> was not changed.</p>');
define('kit_msg_newsletter_user_not_registered', '<p>The e-mail address <b>%s</b> is not in the distribution</p>');
define('kit_msg_password_changed', '<p>The password was successfully changed</p>');
define('kit_msg_password_needed', '<p>Please enter the password</p>');
define('kit_msg_passwords_mismatch', '<p>specified passwords do not match</p>');
define('kit_msg_password_too_short', '<p>The password is too short <b>The minimum length is%d</b> characters!</p>');
define('kit_msg_phone_added', '<p>The phone number <b>%s</b> added.</p>');
define('kit_msg_phone_changed', '<p>The phone number <i>%s</i> was in <b>%s</b> changed.</p>');
define('kit_msg_phone_deleted', '<p>The phone number <b>%s</b> deleted.</p>');
define('kit_msg_phone_invalid', '<p>The phone number <b>%s</b> is not valid, please check your spelling. We only accept phone numbers in international format +49 (30) 1234567.</p>');
define('kit_msg_phone_type_changed', '<p>The type for the phone number <b>%s</b> has been changed.</p>');
define('kit_msg_protocol_updated', '<p>The protocol has been updated.</p>');
define('kit_msg_provider_check_auth', '<p>You no SMTP authentication but have specified an SMTP host and registered name, please check your details</p>');
define('kit_msg_provider_id_invalid', '<p>There is no active record for a provider with the <b>ID %05d</ b>!</p>');
define('kit_msg_provider_inserted', '<p>The service <b>%s</b> Created.</p>');
define('kit_msg_provider_minum_failed', '<p>The data are insufficient to service you must specify at least name and e-mail address and - if the mail server requires SMTP Authentication - SMTP Host, SMTP username and password </.. p> ');
define('kit_msg_provider_missing', '<p>There is no active service is available, you define KIT -> Settings -> a service provider please</p>');
define('kit_msg_provider_updated', '<p>The service <b>%s</b> has been updated.</p>');
define('kit_msg_register_status_updated', '<p>Status <b>dbKITregister</b> has been updated.</p>');
define('kit_msg_service_invalid_user_name', '<p>Please enter a valid first name and surname.</p>');
define('kit_msg_service_license_beta_evaluate', '<p>KeepInTouch This installation is not registered.</b></p><a href="%s"> Register this BETA version </ a> free to obtain a full product support for KeepInTouch</p>');
define('kit_msg_service_license_beta_registered', '<p <b>KeepInTouch BETA</b> <br /> <i>%s</i> <br /> product support until%s, registered for <i>%s%s</i>.</p>');
define('kit_msg_service_no_connect', '<p>The update server could not be reached.</p>');

define('kit_protocol_create_contact', '%s: Record created.');
define('kit_protocol_create_contact_massmail', 'record by importing the e-mail address%s created from mass mail.');
define('kit_protocol_import_wb_user', 'WB <b>user%s</b> taken in KeepInTouch.');
define('kit_protocol_ki_account_activated', 'The account was confirmed, and enabled');
define('kit_protocol_ki_address_added', '[kitInterface] The address %s, %s %s was added');
define('kit_protocol_ki_address_updated', '[kitInterface] The address %s, %s %s has been updated.');
define('kit_protocol_ki_contact_created', '[kitInterface] record is created.');
define('kit_protocol_ki_contact_updated', '[kitInterface] The contact details have been updated.');
define('kit_protocol_ki_added_as_lepton_user', '[kitInterface] Der Kontakt wurde als LEPTON Benutzer hinzugefügt.');
define('kit_protocol_ki_lepton_user_group_added', '[kitInterface] Dem LEPTON Benutzer wurde die Gruppe %s hinzugefügt.');
define('kit_protocol_ki_newsletter_updated', '[kitInterface] The newsletter subscription is updated.');
define('kit_protocol_ki_password_changed', '[kitInterface] Password changed.');
define('kit_protocol_login_locked', 'ACCOUNT LOCKED, the user has too many incorrect login attempts.');
define('kit_protocol_send_newsletter_success', 'newsletter <i>"%s"</i> to <b>%s</b> Clock to <b>%s</b> shipped.');
define('kit_protocol_send_newsletter_fail', 'newsletter <i>"%s"</i> was able to <b>%s</b> not <b>Clock</b> on <b>%s</b> shipped . <b>errors are:</b>%s.');
define('kit_protocol_simulate_send_newsletter', '<p>SIMULATION: The newsletter was to <b>%s</b> shipped</p>');

define('kit_start_list', '<h2>All Contacts</h2><p>All the contacts you manage KeepInTouch in the index.</p>');
define('kit_start_contact', '<h2>Edit Contact</h2><p>Insert new contacts or edit your contacts you have selected in the overview.</p>');
define('kit_start_email', '<h2>groups e-mail</h2><p>you quickly and easily send e-mails to contacts that you have organized in groups.</p>');
define('kit_start_newsletter', '<h2>Send Newsletter</h2><p>Create and send personalized newsletters with KeepInTouch.</p>');
define('kit_start_config', '<h2>Settings</h2><p>General settings, lists, adjust, service and manage import and export data.</p>');
define('kit_start_help', '<h2>Help & Documentation</h2><p>The help and documentation to KeepInTouch.</p>');

define('kit_status_ok', 'OK');
define('kit_status_error', 'ERROR');
define('kit_status_simulation', 'SIMULATION');
define('kit_status_start', 'START');
define('kit_status_step_1', 'STEP 1');
define('kit_status_step_2', 'STEP 2');
define('kit_status_step_3', 'STEP 3');
define('kit_status_step_4', 'STEP 4');
define('kit_status_step_5', 'STEP 5');
define('kit_status_success', 'SUCCESS');

define('kit_tab_cfg_array', 'Listen to adapt');
define('kit_tab_cfg_export', 'export');
define('kit_tab_cfg_general', 'General');
define('kit_tab_cfg_import', 'Import');
define('kit_tab_cfg_provider', 'service');
define('kit_tab_config', 'settings');
define('kit_tab_contact', 'Edit Contact');
define('kit_tab_cronjobs_active', 'Current job');
define('kit_tab_cronjobs_protocol', 'log');
define('kit_tab_email', 'group email');
define('kit_tab_help', '?');
define('kit_tab_list', 'All Contacts');
define('kit_tab_newsletter', 'Send Newsletter');
define('kit_tab_nl_create', 'Creating a Newsletter');
define('kit_tab_nl_template', 'administer templates');
define('kit_tab_start', 'Start');

define('kit_text_as_email_type', 'E-Mail type:');
define('kit_text_calendar_delete', 'Clear Date');
define('kit_text_calendar_select', 'Select Date');
define('kit_text_colon_separated', 'separated by a colon');
define('kit_text_comma_separated', 'separated by commas');
define('kit_text_from_massmail_group', 'Group import:');
define('kit_text_new_id', '- new -');
define('kit_text_pipe_separated', 'with | (pipe) separated');
define('kit_text_please_select', '- select -');
define('kit_text_process_execute', '<b>run</b>');
define('kit_text_process_simulate', '<i>simulate</i>');
define('kit_text_records', 'records');
define('kit_text_semicolon_separated', 'separated by a semicolon');
define('kit_text_tabulator_separated', 'separated by a tab');
define('kit_text_to_category', 'in the KIT Newsletter:');
define('kit_text_unknown', '- unknown -');

?>