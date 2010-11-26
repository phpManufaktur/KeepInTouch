<?php

/**
  Module developed for the Open Source Content Management System Website Baker 
  (http://websitebaker.org)

  Copyright (c) 2010, Ralf Hertsch
  Contact me: ralf.hertsch@phpManufaktur.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id$

	IMPORTANT NOTE:

  If you are editing this file or creating a new language file
  you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
  Otherwise all special chars will be destroyed and displayed improper!

	It is NOT NECESSARY to mask special chars as HTML entities!

	Translated to english by sky writer
  
**/




// english module description
$module_description 	= 'dbKeepInTouch (KIT) is an extensive and powerful contact management tool.';

// name of the person(s) who translated and edited this language file
$module_translation_by = '"sky writer"';

define('kit_btn_abort',														'Abort');
define('kit_btn_edit',														'Bearbeiten');
define('kit_btn_export',													'Export');
define('kit_btn_import',													'Import');
define('kit_btn_mail_bcc',												'BCC:');
define('kit_btn_mail_from',												'From:');
define('kit_btn_mail_to',													'To:');
define('kit_btn_ok',															'Okay');
define('kit_btn_preview',													'Vorschau');
define('kit_btn_register',                        'Registrieren');
define('kit_btn_save',														'Speichern');
define('kit_btn_send',														'Send');

define('kit_cfg_date_str',                        'd.m.Y');
define('kit_cfg_date_time_str',                   'd.m.Y - H:i:s');
define('kit_cfg_thousand_separator',							'.');
define('kit_cfg_date_separator',									'.');
define('kit_cfg_decimal_separator',               ',');
define('kit_cfg_price',                           '%s €');
define('kit_cfg_euro',														'%s EUR');

define('kit_cmd_nl_account_email',								'E-Mail Adresse des Abonnenten');
define('kit_cmd_nl_account_first_name',						'Vorname des Abonnenten');
define('kit_cmd_nl_account_id',										'ID des Abonnentenkonto (Account)');
define('kit_cmd_nl_account_last_name',						'Nachname des Abonnenten');
define('kit_cmd_nl_account_login',								'URL: Link auf den Login Dialog des Abonnentenkonto (Account)');
define('kit_cmd_nl_account_newsletter',						'Abonnierte Newsletter, kommasepariert');
define('kit_cmd_nl_account_register_key',					'Registrierschlüssel des Abonnentenkontos');
define('kit_cmd_nl_account_title',								'Anrede des Abonnenten (Herr oder Frau)');
define('kit_cmd_nl_account_title_academic',				'Titel des Abonnenten (z.B. Dr. oder Prof.');
define('kit_cmd_nl_account_username',							'Benutzername des Abonnenten (Username)');
define('kit_cmd_nl_contact_id',										'ID des Eintrags in der Kontaktdatenbank (Contact)');
define('kit_cmd_nl_content',											'Der eigentliche Text des Newsletters wird an dieser Stelle eingefügt');
define('kit_cmd_nl_kit_info',											'Gibt eine Information über KeepInTouch (KIT) aus');
define('kit_cmd_nl_kit_release',									'Gibt die Release Nummer von KeepInTouch (KIT) aus');
define('kit_cmd_nl_newsletter_unsubscribe',				'URL: Link zum Abbestellen des Newsletters');
define('kit_cmd_nl_salutation',										'Grußformeln 1-10, die Anredevarianten werden in den Einstellungen definiert (Salutation)');

define('kit_contact_access_internal',							'Internal');
define('kit_contact_access_public',								'Public');
define('kit_contact_category_newsletter',					'Newsletter');
define('kit_contact_category_wb_user',						'User');
define('kit_contact_company_title_to',						'Of');
define('kit_contact_email_business',							'E-Mail, business');
define('kit_contact_email_private',								'E-Mail, personal');
define('kit_contact_company_title_none',					'');
define('kit_contact_internet_facebook',						'Facebook');
define('kit_contact_internet_homepage',						'Homepage');
define('kit_contact_internet_twitter',						'Twitter');
define('kit_contact_internet_xing',								'Xing');
define('kit_contact_newsletter_newsletter',				'Newsletter');
define('kit_contact_person_title_academic_dr',		'Dr.');
define('kit_contact_person_title_academic_none',	'');
define('kit_contact_person_title_academic_prof',	'Prof.');
define('kit_contact_person_title_lady',						'Mrs.');
define('kit_contact_person_title_mister',					'Mr.');
define('kit_contact_phone_fax',										'Fax');
define('kit_contact_phone_handy',									'Cell');
define('kit_contact_phone_phone',									'Telephone');
define('kit_contact_status_active',								'Active');
define('kit_contact_status_deleted',							'Deleted');
define('kit_contact_status_key_created',					'Schlüssel erzeugt');
define('kit_contact_status_key_send',							'Wartet auf Aktivierung');
define('kit_contact_status_locked',								'Locked');
define('kit_contact_type_person',									'Person');
define('kit_contact_type_company',								'Company');
define('kit_contact_type_institution',						'Institution');

define('kit_contact_array_type_access',						'Contact Release');
define('kit_contact_array_type_category',					'Contact Category');
define('kit_contact_array_type_company_title',		'Company Address');
define('kit_contact_array_type_email',						'E-Mail');
define('kit_contact_array_type_internet',					'Website Address');
define('kit_contact_array_type_newsletter',				'Newsletter');
define('kit_contact_array_type_person_academic',	'Title');
define('kit_contact_array_type_person_title',			'Person Address');
define('kit_contact_array_type_phone',						'Telephone');
define('kit_contact_array_type_protocol',					'Protocol');
define('kit_contact_array_type_type',							'Contact Type');
define('kit_contact_array_type_undefined',				'- undefined -');

define('kit_contact_address_type_business',				'Business');
define('kit_contact_address_type_delivery',				'Lieferung');
define('kit_contact_address_type_post_office_box','Postfach');
define('kit_contact_address_type_private',				'Personal');
define('kit_contact_address_type_undefined',			'');

define('kit_contact_protocol_type_call',					'Phone Call');
define('kit_contact_protocol_type_email',					'E-Mail');
define('kit_contact_protocol_type_meeting',				'Meeting');
define('kit_contact_protocol_type_memo',					'Memo');
define('kit_contact_protocol_type_newsletter',		'Newsletter');
define('kit_contact_protocol_type_undefined',			'- Open -');

define('kit_country_austria',											'Austria');
define('kit_country_germany',											'Germany');
define('kit_country_suisse',											'Switzerland');
define('kit_country_undefined',										'');

define('kit_desc_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen, um einfach zu anderen Add-ons wechseln zu können. TAB\'s mit Komma trennen, Aufbau BEZEICHNER|URL');
define('kit_desc_cfg_cronjob_key',                'Um zu verhindern, dass Cronjobs durch einen einfachen Aufruf der <b>cronjob.php</b> ausgeführt werden, muss der angegebene Schlüssel als Parameter übergeben werden. Der Aufruf der Datei lautet <b>cronjob.php?key=SCHLÜSSEL</b>.');
define('kit_desc_cfg_developer_mode',							'Makes adding configuration parameters possible for the programmer.');
define('kit_desc_cfg_google_maps_api_key',				'For the use and announcement of the maps you need a Google Maps API key.');
define('kit_desc_cfg_kit_request_link',						'<b>kit.php</b> nimmt alle Anfragen entgegen, gibt Daten zurück oder ruft Dialoge auf. Die Datei befindet sich im Verzeichnis /modules/kit, sie kann aber auch an eine andere Stelle kopiert werden, z.B. in das Root-Verzeichnis');
define('kit_desc_cfg_kit_response_page', 					'KIT benötigt für die Anzeige von Dialogen und Hinweisen eine eigene Seite');
define('kit_desc_cfg_license_key',                'Um KeepInTouch in vollem Umfang nutzen zu können benötigen Sie einen Lizenzschlüssel, dieser wird hier gesichert');
define('kit_desc_cfg_max_invalid_login',					'Maximale Anzahl von fehlerhaften Login Versuchen von Anwendern, bevor das Konto gesperrt wird.');
define('kit_desc_cfg_nl_adjust_register',         'Gleicht beim Aufruf des Newsletter Dialog die Tabelle kit_register mit kit_contact ab (Verwenden Sie diese Einstellung nur nach Aufforderung durch den Support!).');
define('kit_desc_cfg_nl_max_package_size',        'Legt die max. Anzahl von Adressaten pro Paket während des Newsletterversand fest. Die einzelnen Pakete werden von einem Cronjob nach und nach abgearbeitet, der höchste zulässige Wert ist 100.');
define('kit_desc_cfg_nl_set_time_limit',          'Legt die Dauer in Sekunden fest, die das Newsletter Script max. für die Versendung der Mails benötigen darf. Ist der Wert zu niedrig, werden Sie einen Laufzeitfehler erhalten, erhöhen Sie den Wert dann schrittweise.');
define('kit_desc_cfg_nl_salutation',							'Sie können 10 unterschiedliche Grußformeln definieren, die Sie mit {$salutation_01} bis {$salutation_10} innerhlab des Newsletters verwenden können. Die Grußformeln bestehen jeweils aus 3 Definitionen: männlich, weiblich sowie unbekannt. Die Definitionen werden durch ein Pipe-Symbol von einander getrennt. Sie können innerhalb der Definitionen KIT CMDs verwenden.');
define('kit_desc_cfg_nl_simulate',                'Durchläuft die vollständige Versandabwicklung der Newsletter <b>ohne</b> die Mails tatsächlich zu versenden.');
define('kit_desc_cfg_register_data_dlg',					'Dialog, der den Besuchern die Verwaltung ihrer Daten ermöglicht');
define('kit_desc_cfg_register_dlg',								'Dialog, der aufgerufen wird, wenn sich Besucher registrieren oder einen Newsletter bestellen möchten');
define('kit_desc_cfg_use_captcha', 								'Legen Sie fest, ob die Dialoge im Frontend CAPTCHA zum Schutz vor Spam verwenden sollen');
define('kit_desc_cfg_use_custom_files',						'Falls gesetzt, können Sie individuell angepasste Templates und Sprachdateien verwenden. Den Dateien wird "custom." vorangestellt, z.B. "custom.DE.php", diese Dateien werden bei einem Update nicht überschrieben.');

define('kit_error_blank_title',										'<p>Die Seite muss einen Titel enthalten!</p>');
define('kit_error_cfg_id',												'<p>The configuration data set with the ID <b>ID %05d</b> could not be picked out!</p>');
define('kit_error_cfg_name',											'<p>No configuration data set was found for the designator <b>%s</b>!</p>');
define('kit_error_delete_access_file',						'<p>Die Zugriffsseite <b>%s</b> konnte nicht gelöscht werden!</p>');
define('kit_error_dlg_missing',										'<p>Der angeforderte Dialog <b>%s</b> wurde nicht gefunden!</p>');
define('kit_error_google_maps_api_key_missing',		'<p>Map cannot be indicated.  No Google Maps API key has benn defined!</p>');
define('kit_error_import_massmail_grp_missing',		'<p>The table with the MassMail groups was not found!</p>');
define('kit_error_import_massmail_missing_vars',	'<p>Not all necessary data variables were imported into Massmail.</p>');
define('kit_error_item_id',												'<p>The data record with the ID <b>ID %s</b> was not found!</p>');
define('kit_error_mail_init_settings',						'<p>The WebsiteBaker settings for the Mail configuration could not be loaded.</p>');
define('kit_error_map_address_invalid',						'<p>The Address <b>%s</b> was not found!</p>');
define('kit_error_newsletter_tpl_id_invalid',			'<p>Das Newsletter Template mit der <b>ID %03d</b> wurde nicht gefunden!</p>');
define('kit_error_no_provider_defined',						'<p>Sie haben noch keinen Dienstleister definiert. Legen Sie diesen zunächst über "Einstellungen" und "Dienstleister" fest.</p>');
define('kit_error_insufficient_permissions',			'<p>Sie haben keine Berechtigung, diese Seite zu ändern!</p>');
define('kit_error_page_exists',										'<p>Die Seite mit der Grundbezeichnung <b>%s</b> existiert bereits!</p>');
define('kit_error_page_not_found',								'<p>Die Seite mit der PAGE_ID <b>%d</b> wurde nicht gefunden!</p>');
define('kit_error_preview_id_invalid',						'<p>Die Preview mit der <b>ID %05d</b> wurde nicht gefunden!');
define('kit_error_preview_id_missing',						'<p>Es wurde keine Preview ID angegeben!</p>');
define('kit_error_request_dlg_invalid_id',				'<p>[kitRequest] Der Dialog mit der <b>ID %03d</b> wurde nicht gefunden, Vorgang abgebrochen!</p>');
define('kit_error_request_dlg_invalid_name',			'<p>[kitRequest] Der Dialog mit der Klassenbezeichnung <b>%s</b> wurde nicht gefunden, Vorgang abgebrochen!</p>');
define('kit_error_request_invalid_action',				'<p>[kitRequest] Der Parameter <b>%s=%s</b> ist ungültig, Vorgang abgebrochen!</p>');
define('kit_error_request_missing_parameter',			'<p>[kitRequest] Der Parameter <b>%s</b> wurde nicht angegeben, Vorgang abgebrochen!</p>');
define('kit_error_request_no_action',							'<p>[kitRequest] Es wurden keine geeigneten Parameter übergeben!</p><p><b>Hinweis:</b> Diese Fehlermeldung wird auch dann angezeigt, wenn Sie versucht haben, einen Dialog erneut zu laden (<i>Reload Sperre</i>).</p>');
define('kit_error_request_parameter_incomplete',	'<p>[kitRequest] Die übergebenen Parameter sind nicht vollständig, der Befehl konnte nicht ausgeführt werden.</p>');
define('kit_error_undefined',											'<p>Es ist ein nicht näher definierter Fehler aufgetreten, bitte informieren Sie den Support über das aufgetretene Problem.</p>');

define('kit_header_addresses',										'Addresses, Map');
define('kit_header_categories',										'Kategorien');
define('kit_header_cfg',													'Configuration');
define('kit_header_cfg_array',										'Lists work on and supplement');
define('kit_header_cfg_description',							'Description');
define('kit_header_cfg_identifier',								'Identifier');
define('kit_header_cfg_import',										'Data Import');
define('kit_header_cfg_label',										'Label');
define('kit_header_cfg_typ',											'Type');
define('kit_header_cfg_value',										'Value');
define('kit_header_communication',								'Kommunikation');
define('kit_header_contact',											'Contact');
define('kit_header_contact_list',									'Contact List');
define('kit_header_email',												'E-Mail Dispatch');
define('kit_header_error',												'KeepInTouch (KIT) Fehlermeldung');
define('kit_header_help_documentation',						'Hilfe & Dokumentation');
define('kit_header_nl_cronjob_active_list',       'Noch nicht ausgeführte Jobs');
define('kit_header_nl_cronjob_protocol_list',     'Ausgeführte Jobs');
define('kit_header_preview',											'Vorschau');
define('kit_header_protocol',											'Protokoll');
define('kit_header_provider',											'Dienstleister');
define('kit_header_template',											'Templates bearbeiten');

define('kit_hint_error_msg',											'<p>Bitte nehmen Sie Verbindung mit dem Systemadministrator auf!</p>');

define('kit_info',																'<a href="http://phpmanufaktur.de/kit" target="_blank">KeepInTouch (KIT)</a>, Release %s - Open Source CRM for WebsiteBaker - (c) 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>');

define('kit_intro_cfg',														'<p>Work on the settings for <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_add_item',									'<p>Adding from entries to the configuration is meaningful only if the indicated values correspond with the program.</p>');
define('kit_intro_cfg_array',											'<p>Work on the different lists for <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_import',										'<p>With this dialogue you know data from other applications in <b>dbKeepInTouch</b> importieren.</p>');
define('kit_intro_cfg_provider',									'<p>Wählen Sie einen Dienstleister zum Bearbeiten aus oder legen Sie einen neuen Dienstleister an.</p>');
define('kit_intro_contact',												'<p>With this dialogue you work on the contact contacts</p>');
define('kit_intro_contact_list',									'<p>This list indicates the available contacts to you depending upon selected assortment.</p>');
define('kit_intro_cronjobs',                      '<p>KeepInTouch (KIT) benötigt für den Versand der Newsletter einen Cronjob der in regelmäßigen Abständen, z.B. alle 5 Minuten die Steuerdatei <b>/modules/kit/cronjob.php</b> aufruft.</p><p>Auf diese Weise wird sichergestellt, dass KIT eine grosse Anzahl von Newslettern kontinuierlich in kleineren Paketen versendet. Durch den maßvollen Versand wird außerdem verhindert, dass Ihre Aussendung von Ihrem Provider als kritisches Massenmailing klassifiziert wird.</p><p>Sollte Ihnen auf Ihrer Installation die Möglichkeit fehlen Cronjobs auszuführen, verwenden Sie einfach einen kostenlosen Dienstleister, wie <a href="http://www.cronjob.de">cronjob.de</a> für die Ansteuerung von KIT.</p></p>');
define('kit_intro_email',													'<p>With this dialogue you can emails provide and dispatch.</p>');
define('kit_intro_newsletter_cfg',								'<p>Bearbeiten Sie die speziellen Einstellungen für das Newsletter Modul von KeepInTouch.</p>');
define('kit_intro_newsletter_create',							'<p>Erstellen Sie einen Newsletter und versenden Sie ihn an ihre Abonnenten.</p>');
define('kit_intro_newsletter_commands',						'<p>Befehle und Variablen werden zur Laufzeit ausgeführt und in das Template eingefügt.</p>');
define('kit_intro_newsletter_template',						'<p>Wählen Sie ein Newsletter Template zum Bearbeiten aus oder legen Sie ein neues Template an.</p>');
define('kit_intro_nl_cronjob_active_list',        '<p>Die Liste zeigt Ihnen aktuelle Cronjobs an, die noch nicht abgwickelt sind.</p>');
define('kit_intro_nl_cronjob_protocol_list',      '<p>Diese Liste zeigt die letzten 200 durchgeführten Newsletter Jobs, die der KeepInTouch Cronjob durchgeführt hat.</p>');
define('kit_intro_preview',												'<p>Prüfen Sie die Vorschau in der <b>HTML</b> und in der <b>NUR TEXT</b> Ansicht.</p>');
define('kit_intro_register_installation',         '<p>Registrieren Sie Ihre KeepInTouch Installation.</p><p>Dies ermöglicht Ihnen unentgeltlich den vollen Funktionsumfang von KeepInTouch zu testen.</p>');

define('kit_label_add_new_address',								'Add Additional Address');
define('kit_label_address_city',									'City');
define('kit_label_address_street',								'Street');
define('kit_label_address_zip',										'ZIP');
define('kit_label_address_zip_city',							'ZIP Code');
define('kit_label_archive_id',                    'Archiv ID');
define('kit_label_audience',											'Empfänger');
define('kit_label_birthday',											'Birthday');
define('kit_label_categories',										'Categories');
define('kit_label_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen');
define('kit_label_cfg_array_add_items',						'Add Further Entries:');
define('kit_label_cfg_cronjob_key',               'Schlüssel für Cronjobs');
define('kit_label_cfg_developer_mode',						'Developer Mode');
define('kit_label_cfg_google_maps_api_key',				'Google Maps API Key');
define('kit_label_cfg_kit_request_link',					'KIT Request Link');
define('kit_label_cfg_kit_reponse_page',					'KIT Antwortseite');
define('kit_label_cfg_license_key',               'Lizenzschlüssel');
define('kit_label_cfg_max_invalid_login',					'Maximale Loginversuche');
define('kit_label_cfg_nl_adjust_register',        'kit_register abgleichen');
define('kit_label_cfg_nl_max_package_size',       'Max. Paketgröße');
define('kit_label_cfg_nl_salutation',							'Grußformel');
define('kit_label_cfg_nl_set_time_limit',         'Max. Ausführungsdauer');
define('kit_label_cfg_nl_simulate',								'Versand simulieren');
define('kit_label_cfg_register_data_dlg',					'Benutzer, Datenverwalten');
define('kit_label_cfg_register_dlg',							'Benutzer Registrierung');
define('kit_label_cfg_use_captcha',								'CAPTCHA verwenden');
define('kit_label_cfg_use_custom_files',					'Angepasste Dateien zulassen');
define('kit_label_checksum',											'Prüfsumme');
define('kit_label_contact_edit',									'Edit Contact');
define('kit_label_contact_email',									'E-Mail');
define('kit_label_contact_note',									'Notes');
define('kit_label_contact_phone',									'Telephone');
define('kit_label_contact_since',									'Contact Since');
define('kit_label_contact_type',									'Contact Type');
define('kit_label_company_additional',						'Additional');
define('kit_label_company_department',						'Department');
define('kit_label_company_name',									'Company');
define('kit_label_company_title',									'Title');
define('kit_label_contact_access',								'Contact Release');
define('kit_label_contact_identifier',						'Contact Identifier');
define('kit_label_contact_status',								'Contact Status');
define('kit_label_csv_export',										'CSV Export');
define('kit_label_csv_import',										'CSV Import');
define('kit_label_html_format',										'HTML Format');
define('kit_label_id',														'ID');
define('kit_label_identifier',										'Identifier');
define('kit_label_image',													'Picture');
define('kit_label_import_action',									'');
define('kit_label_import_from',										'Import');
define('kit_label_job_id',                        'Job ID');
define('kit_label_job_created',                   'Angelegt am');
define('kit_label_job_process',                   'Prozess');
define('kit_label_job_count',                     'Aussendungen');
define('kit_label_kit_id',												'KIT ID');
define('kit_label_job_done',                      'Ausgeführt');
define('kit_label_job_time',                      'Dauer (Sec.)');
define('kit_label_job_send',                      'E-Mails IST');
define('kit_label_last_changed_by',								'Last Changed By');
define('kit_label_list_sort',											'Sort List');
define('kit_label_map',														'&nbsp;');
define('kit_label_mail_bcc',											'<i>BCC</i> <b>Recipient</b>');
define('kit_label_mail_from',											'From');
define('kit_label_mail_subject',									'Subject');
define('kit_label_mail_text',											'Text');
define('kit_label_mail_to',												'Recipient');
define('kit_label_massmail',											'MassMail');
define('kit_label_newsletter',										'Newsletter');
define('kit_label_newsletter_commands',						'Befehle');
define('kit_label_newsletter_tpl_desc',						'Template Beschreibung');
define('kit_label_newsletter_tpl_html',						'HTML Code');
define('kit_label_newsletter_tpl_name',						'Template Bezeichnung');
define('kit_label_newsletter_tpl_select',					'Template auswählen');
define('kit_label_newsletter_tpl_text',						'Nur TEXT');
define('kit_label_newsletter_tpl_text_preview',		'NUR TEXT<br /><span class="smaller">Der Inhalt wird in dieser Ansicht nicht automatisch umgebrochen!</span>');
define('kit_label_password',											'Passwort');
define('kit_label_password_retype',								'Passwort wiederholen');
define('kit_label_person_first_name',							'First Name');
define('kit_label_person_function',								'Position');
define('kit_label_person_title',									'Personal Title');
define('kit_label_person_title_academic',					'Acedemic Title');
define('kit_label_person_last_name',							'Last Name');
define('kit_label_protocol',											'Protocol');
define('kit_label_protocol_members',							'Participants');
define('kit_label_protocol_type',									'Contact');
define('kit_label_protocol_date',									'Date');
define('kit_label_protocol_memo',									'Memo');
define('kit_label_provider',											'Dienstleister');
define('kit_label_provider_email',								'E-Mail des Dienstleisters');
define('kit_label_provider_identifier',						'Kurzbezeichnung');
define('kit_label_provider_name',									'Name des Dienstleisters');
define('kit_label_provider_remark',								'Anmerkungen');
define('kit_label_provider_response',							'Antwortadresse(n)');
define('kit_label_provider_select',								'Dienstleister auswählen');
define('kit_label_provider_smtp_auth',						'SMTP Authentifizierung');
define('kit_label_provider_smtp_user',						'SMTP Benutzername');
define('kit_label_provider_smtp_host',						'SMTP Hostname');
define('kit_label_standard',											'Standard');
define('kit_label_status',												'Status');
define('kit_label_subscribe',											'Anmelden');
define('kit_label_type',													'Type');
define('kit_label_unsubscribe',										'Abmelden');
define('kit_label_value',													'Value');

define('kit_list_sort_city',											'City');
define('kit_list_sort_company',										'Company');
define('kit_list_sort_email',											'E-Mail');
define('kit_list_sort_firstname',									'First Name');
define('kit_list_sort_lastname',									'Last Name');
define('kit_list_sort_phone',											'Telephone');
define('kit_list_sort_street',										'Street');
define('kit_list_sort_unsorted',									'- unsorted -');

define('kit_msg_address_deleted',									'<p>Address data for the contact with the <b>ID %05d</b> was deleted.</p>');
define('kit_msg_address_insert',									'<p>A new address was added to the contact.</p>');
define('kit_msg_address_invalid',									'<p>The address cannot be accepted, the data is not sufficient.<br />Please provide the <i>street, postal code and city</i>, or <i>road and city>/i>, or only <i>city</i>.</p>');
define('kit_msg_address_update',									'<p>Address data for the contact with the <b>ID %05d</b> was updated.</p>');
define('kit_msg_categories_changed',							'<p>The categories were changed.</p>');
define('kit_msg_cfg_add_exists',									'<p>The configuration data set with the designator <b>%s</b> already exists and cannot be added again!</p>');
define('kit_msg_cfg_add_incomplete',							'<p>The added configuration data set is incomplete! Please examine your data!</p>');
define('kit_msg_cfg_add_success',									'<p>The configuration data set with the <b>ID #%05d</b> and the designator <b>%s</b> was added.</p>');
define('kit_msg_cfg_array_identifier_in_use',			'<p>The designator <b>%s</b> is already used for <b>ID %05d</b> and cannot not be imported. Please specify another designator.</p>');
define('kit_msg_cfg_array_item_add',							'<p>The entry with the <b>ID %05d</b> was added.</p>');
define('kit_msg_cfg_array_item_updated',					'<p>The entry for <b>ID %05d</b> was updated.</p>');
define('kit_msg_cfg_csv_export',									'<p>The Configuration data became secured as <b>%s</b> in /MEDIA listing.</p>');
define('kit_msg_cfg_id_updated',									'<p>The configuration data set with the <b>ID #%05d</b> and the designator <b>%s</b> was updated.</p>');
define('kit_msg_contact_deleted',									'<p>Der Kontakt mit der <b>ID %05d</b> wurde gelöscht.</p>');
define('kit_msg_contact_insert',									'<p>The contact with the ID <b>ID %05d</b> was successfully added and secured.</p>');
define('kit_msg_contact_minimum_failed',					'<p>The <b>data record cannot become secured</b> because the minimum requirements are not fulfilled.<br />Please indicate at least either an email address <i>or</i> a company name and a city <i>or</i> a company name and a telephone number <i>or</i> a surname and a city <i>or</i> a surname and a telephone number.</p>');
define('kit_msg_contact_update',									'<p>The contact with the ID <b>ID %05d</b> was successfully updated.</p>');
define('kit_msg_email_added',											'<p>The email address <b>%s</b> was added.</p>');
define('kit_msg_email_deleted',										'<p>The email address <b>%s</b> was deleted.</p>');
define('kit_msg_email_changed',										'<p>The E-Mail address <i>%s</i> was changed to <b>%s</b>.</p>');
define('kit_msg_email_invalid',										'<p>The email address <b>%s</b> is not valid, please check your input.</p>');
define('kit_msg_email_type_changed',							'<p>The type for the email address <b>%s</b> was changed.</p>');
define('kit_msg_internet_added',									'<p>The web address <b>%s</b> was added.</p>');
define('kit_msg_internet_changed',								'<p>The web address <i>%s</i> was changed to <b>%s</b>.</p>');
define('kit_msg_internet_deleted',								'<p>The web address <b>%s</b> was deleted.</p>');
define('kit_msg_internet_invalid',								'<p>The web address <b>%s</b> is not valid, please check your input.</p>');
define('kit_msg_internet_type_changed',						'<p>The type for the web address <b>%s</b> was changed.</p>');
define('kit_msg_invalid_email',										'<p>The email address <b>%s</b> is not valid, please check your input.</p>');
define('kit_msg_mail_incomplete',									'<p>The data is incomplete: Email sender, email receiver, selection of a category, a reference and a text must be set.</p>');
define('kit_msg_mail_send_error',									'<p>The email could not be sent, it encountered <b>%d errors</b>, the error message reads:<br /><b>%s</b></p>');
define('kit_msg_mail_send_success',								'<p>The email was successfully sent.</p>');
define('kit_msg_mails_send_success',							'<p>Es wurden <b>%d</b> E-Mails erfolgreich versendet.</p>');
define('kit_msg_mails_send_errors',								'<p>Es traten insgesamt <b>%d</b> Fehler mit folgenden Meldungen auf:</p>%s');
define('kit_msg_massmail_email_skipped',					'<p>The following email addresses in the indicated KIT data records are already used and will be <b>ignored</b>: %s</p>');
define('kit_msg_massmail_emails_imported',				'<p><b>%d E-Mail Adresses</b> were imported as independent data records: %s</p>');
define('kit_msg_massmail_group_no_data',					'<p>The MassMail Group with the <b>ID %d</b> does not contain any data.</p>');
define('kit_msg_massmail_not_installed',					'<p>MassMail is not installed.</p>');
define('kit_msg_massmail_no_emails_imported',			'<p><b>No E-Mail addreses were imported!</b></p>');
define('kit_msg_newsletter_adjust_register',      '<p>Der Befehl <b>cfgAdjustRegister</b> wurde für <b>%s</b> ausgeführt!</p>');
define('kit_msg_newsletter_new_no_groups',				'<p>Bitte wählen Sie eine oder mehrere <b>Newsletter Gruppen</b> aus!</p>');
define('kit_msg_newsletter_new_no_html',					'<p>Bitte geben Sie den Newsletter Text im <b>HTML Format</b> an!</p>');
define('kit_msg_newsletter_new_no_provider',			'<p>Bitte wählen Sie einen <b>Dienstleister</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_subject',				'<p>Bitte geben Sie einen Betreff für den Newsletter an!</p>');
define('kit_msg_newsletter_new_no_template',			'<p>Bitte wählen Sie ein <b>Template</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_text',					'<p>Das <b>NUR TEXT</b> Format wurde automatisch generiert, bitte pruefen Sie die Ausgabe!</p>');
define('kit_msg_newsletter_simulate_mailing',     '<p><b>SIMULATIONSMODUS AKTIV</b> - es werden keine Newsletter versendet!</p>');
define('kit_msg_newsletter_tpl_added',						'<p>Dast Template <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_newsletter_tpl_changed',					'<p>Das Template <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_newsletter_tpl_cmd_content',			'<p>Datensatz nicht gesichert, das Template muss zumindest den Platzhalter <b>%s</b> enthalten. An dieser Stelle wird der eigentliche Inhalt des Newsletters eingefügt.</p>');
define('kit_msg_newsletter_tpl_minimum_failed',		'<p>Datensatz nicht gesichert, Sie müssen mindestens einen <b>Bezeichner</b> und den <b>HTML Code</b> angeben!</p>');
define('kit_msg_newsletter_tpl_missing',					'<p><b>Sie haben noch kein Template für den Versand von Newslettern angelegt.</b></p><p>Bitte legen Sie zunächst ein Template an.</p>');
define('kit_msg_newsletter_tpl_text_inserted',		'<p>Die <b>NUR TEXT</b> Ausgabe des Templates wurde automatisch generiert, bitte prüfen Sie die Ausgabe.</p>');
define('kit_msg_newsletter_tpl_unchanged',				'<p>Das Template mit der <b>ID %05d</b> wurde nicht geändert.</p>');
define('kit_msg_password_needed',									'<p>Bitte geben Sie das Passwort an!</p>');
define('kit_msg_passwords_mismatch',							'<p>Die angegebenen Passwörter stimmen nicht überein!</p>');
define('kit_msg_phone_added',											'<p>The telephone number <b>%s</b> was added.</p>');
define('kit_msg_phone_changed',										'<p>The telephone number <i>%s</i> was changed to <b>%s</b>.</p>');
define('kit_msg_phone_deleted',										'<p>The telephone number <b>%s</b> was deleted.</p>');
define('kit_msg_phone_invalid',										'<p>The teephone number <b>%s</b> is not valid, please check your input.  Only telephone numbers in the international format are accepted +49 (30) 1234567.</p>');
define('kit_msg_phone_type_changed',							'<p>The type for telephone number <b>%s</b> was changed.</p>');
define('kit_msg_protocol_updated',								'<p>The protocol was updated.</p>');
define('kit_msg_provider_check_auth',							'<p>Sie haben keine SMTP Authentifizierung angegeben aber einen SMTP Host und Namen eingetragen, bitte prüfen Sie Ihre Angaben!</p>');
define('kit_msg_provider_inserted',								'<p>Der Dienstleister <b>%s</b> wurde neu angelegt.</p>');
define('kit_msg_provider_minum_failed',						'<p>Die Angaben zum Dienstleister reichen nicht aus. Sie müssen mindestens angeben: Name und E-Mail Adresse und - falls der Mailserver eine SMTP Authentifizierung erfordert - SMTP Host, SMTP Benutzername und das Passwort.</p>');
define('kit_msg_provider_updated',								'<p>Der Dienstleister <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_service_invalid_user_name',       '<p>Bitte geben Sie einen gültigen Vor- und Nachnamen an.</p>');
define('kit_msg_service_license_beta_evaluate',   '<p><b>Diese KeepInTouch Installation ist nicht registriert.</b></p><p><a href="%s">Registrieren Sie diese BETA Version</a> unentgeltlich damit Sie den vollen Funktionsumfang von KeepInTouch testen können!</p>');
define('kit_msg_service_license_beta_registered', '<p>KeepInTouch BETA <i>%s</i><br />Lizenz gültig bis %s, registriert auf <i>%s %s</i>.</p>');
define('kit_msg_service_no_connect',              '<p>Der Updateserver konnte nicht erreicht werden.</p>');

define('kit_protocol_create_contact',							'%s: Data record created.');
define('kit_protocol_create_contact_massmail',		'Data record by import of the email address %s put on by Massmail.');
define('kit_protocol_send_newsletter_success',    'Newsletter <i>"%s"</i> um <b>%s</b> Uhr an <b>%s</b> versendet.');
define('kit_protocol_send_newsletter_fail',       'Newsletter <i>"%s"</i> konnte um <b>%s</b> Uhr <b>nicht</b> an <b>%s</b> versendet werden.<br><b>Fehler:</b> %s.');
define('kit_protocol_simulate_send_newsletter',   '<p>SIMULATION: Der Newsletter wurde an <b>%s</b> versendet!</p>');

define('kit_start_list',                          '<h2>Alle Kontakte</h2><p>Alle Kontakte, die Sie mit KeepInTouch verwalten in der Übersicht.</p>');
define('kit_start_contact',                       '<h2>Kontakt bearbeiten</h2><p>Legen Sie neue Kontakte an oder bearbeiten Sie Kontakte, die Sie in der Übersicht ausgewählt haben.</p>');
define('kit_start_email',                         '<h2>Gruppen E-Mail</h2><p>Versenden Sie schnell und einfach E-Mails an Kontakte, die Sie in Gruppen organisiert haben.</p>');
define('kit_start_newsletter',                    '<h2>Newsletter versenden</h2><p>Erstellen und versenden Sie personalisierte Newsletter mit KeepInTouch.</p>');
define('kit_start_config',                        '<h2>Einstellungen</h2><p>Allgemeine Einstellungen, Listen anpassen, Dienstleister verwalten sowie Daten importieren und exportieren.</p>');
define('kit_start_help',                          '<h2>Hilfe & Dokumentation</h2><p>Die Hilfe und Dokumentation zu KeepInTouch.</p>');

define('kit_status_ok',                           'OK');
define('kit_status_error',                        'ERROR');
define('kit_status_simulation',                   'SIMULATION');

define('kit_tab_cfg_array',												'Addapted Lists');
define('kit_tab_cfg_general',											'General');
define('kit_tab_cfg_import',											'Import/Export');
define('kit_tab_cfg_provider',										'Service Provider');
define('kit_tab_config',													'Configuration');
define('kit_tab_contact',													'Contact');
define('kit_tab_cronjobs_active',                 'Aktuelle Jobs');
define('kit_tab_cronjobs_protocol',               'Protokoll');
define('kit_tab_email',														'E-Mail');
define('kit_tab_help',														'?');
define('kit_tab_list',														'Overview');
define('kit_tab_newsletter',											'Newsletter');
define('kit_tab_nl_create',												'Erstellen');
define('kit_tab_nl_template',											'Vorlagen');
define('kit_tab_start',														'Start');

define('kit_text_as_email_type',									'as E-Mail Type:');
define('kit_text_calendar_select',								'Select date');
define('kit_text_calendar_delete',								'Delete date');
define('kit_text_from_massmail_group',						'Group import:');
define('kit_text_new_id',													'- new -');
define('kit_text_please_select',									'- bitte auswählen -');
define('kit_text_process_execute',                '<b>ausführen</b>');
define('kit_text_process_simulate',               '<i>simulieren</i>');
define('kit_text_records',												'Datensätze');
define('kit_text_to_category',										'Into KIT Category:');
define('kit_text_unknown',                        '- unbekannt -');

?>