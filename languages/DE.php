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

	Translated to German (Original Source) by Ralf Hertsch
  
**/




// Deutsche Modulbeschreibung
$module_description 	= 'dbKeepInTouch (KIT) ist eine zentrale Adress- und Kontaktverwaltung, die unterschiedlichen Anwendungen Kontaktdaten zur Verfuegung stellt.';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Ralf Hertsch (phpManufaktur)';

define('kit_btn_abort',														'Abbruch');
define('kit_btn_edit',														'Bearbeiten');
define('kit_btn_export',													'Exportieren');
define('kit_btn_import',													'Importieren');
define('kit_btn_mail_bcc',												'BCC Empfänger:');
define('kit_btn_mail_from',												'Von:');
define('kit_btn_mail_to',													'An:');
define('kit_btn_ok',															'Übernehmen');
define('kit_btn_preview',													'Vorschau');
define('kit_btn_register',                        'Registrieren');
define('kit_btn_save',														'Speichern');
define('kit_btn_send',														'Abschicken');

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

define('kit_contact_access_internal',							'Intern');
define('kit_contact_access_public',								'Öffentlich');
define('kit_contact_category_wb_user',						'WB Benutzer');
define('kit_contact_category_newsletter',					'Newsletter');
define('kit_contact_company_title_none',					'');
define('kit_contact_company_title_to',						'An');
define('kit_contact_email_private',								'E-Mail, privat');
define('kit_contact_email_business',							'E-Mail, dienstlich');
define('kit_contact_internet_facebook',						'Facebook');
define('kit_contact_internet_homepage',						'Homepage');
define('kit_contact_internet_twitter',						'Twitter');
define('kit_contact_internet_xing',								'Xing');
define('kit_contact_newsletter_newsletter',				'Newsletter');
define('kit_contact_person_title_academic_dr',		'Dr.');
define('kit_contact_person_title_academic_none',	'');
define('kit_contact_person_title_academic_prof',	'Prof.');
define('kit_contact_person_title_lady',						'Frau');
define('kit_contact_person_title_mister',					'Herr');
define('kit_contact_phone_fax',										'Telefax');
define('kit_contact_phone_handy',									'Handy');
define('kit_contact_phone_phone',									'Telefon');
define('kit_contact_status_active',								'Aktiv');
define('kit_contact_status_deleted',							'Gelöscht');
define('kit_contact_status_key_created',					'Schlüssel erzeugt');
define('kit_contact_status_key_send',							'Schlüssel versendet');
define('kit_contact_status_locked',								'Gesperrt');
define('kit_contact_type_company',								'Firma');
define('kit_contact_type_institution',						'Institution');
define('kit_contact_type_person',									'Person');

define('kit_contact_array_type_access',						'Kontakt Freigabe');
define('kit_contact_array_type_category',					'Kontakt Kategorie');
define('kit_contact_array_type_company_title',		'Firma Anrede');
define('kit_contact_array_type_email',						'E-Mail');
define('kit_contact_array_type_internet',					'Internet Adresse');
define('kit_contact_array_type_newsletter',				'Newsletter');
define('kit_contact_array_type_person_academic',	'Person Titel');
define('kit_contact_array_type_person_title',			'Person Anrede');
define('kit_contact_array_type_phone',						'Telekommunikation');
define('kit_contact_array_type_protocol',					'Protokoll Typ');
define('kit_contact_array_type_type',							'Kontakt Typ');
define('kit_contact_array_type_undefined',				'- nicht definiert -');

define('kit_contact_address_type_business',				'Dienst');
define('kit_contact_address_type_delivery',				'Lieferung');
define('kit_contact_address_type_post_office_box','Postfach');
define('kit_contact_address_type_private',				'Privat');
define('kit_contact_address_type_undefined',			'');

define('kit_contact_protocol_type_call',					'Telefonat');
define('kit_contact_protocol_type_email',					'E-Mail');
define('kit_contact_protocol_type_meeting',				'Meeting');
define('kit_contact_protocol_type_memo',					'Notiz');
define('kit_contact_protocol_type_newsletter',		'Newsletter');
define('kit_contact_protocol_type_undefined',			'- offen -');

define('kit_country_austria',											'Österreich');
define('kit_country_germany',											'Deutschland');
define('kit_country_suisse',											'Schweiz');
define('kit_country_undefined',										'');

define('kit_desc_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen, um einfach zu anderen Add-ons wechseln zu können. TAB\'s mit Komma trennen, Aufbau BEZEICHNER|URL');
define('kit_desc_cfg_developer_mode',							'Ermöglicht dem Programmierer das Hinzufügen von Konfigurationsparametern.');
define('kit_desc_cfg_google_maps_api_key',				'Für die Verwendung und Anzeige der Karten benötigen Sie einen Google Maps API Key.');
define('kit_desc_cfg_kit_request_link',						'<b>kit.php</b> nimmt alle Anfragen entgegen, gibt Daten zurück oder ruft Dialoge auf. Die Datei befindet sich im Verzeichnis /modules/kit, sie kann aber auch an eine andere Stelle kopiert werden, z.B. in das Root-Verzeichnis');
define('kit_desc_cfg_kit_response_page', 					'KIT benötigt für die Anzeige von Dialogen und Hinweisen eine eigene Seite');
define('kit_desc_cfg_license_key',                'Um KeepInTouch in vollem Umfang nutzen zu können benötigen Sie einen Lizenzschlüssel, dieser wird hier gesichert');
define('kit_desc_cfg_max_invalid_login',					'Maximale Anzahl von fehlerhaften Login Versuchen von Anwendern, bevor das Konto gesperrt wird.');
define('kit_desc_cfg_nl_adjust_register',         'Gleicht beim Aufruf des Newsletter Dialog die Tabelle kit_register mit kit_contact ab (Verwenden Sie diese Einstellung nur nach Aufforderung durch den Support! 0=AUS, 1=AN).');
define('kit_desc_cfg_nl_max_package_size',        'Legt die max. Anzahl von Adressaten pro Paket während des Newsletterversand fest. Die einzelnen Pakete werden von einem Cronjob nach und nach abgearbeitet, der höchste zulässige Wert ist 100.');
define('kit_desc_cfg_nl_set_time_limit',          'Legt die Dauer in Sekunden fest, die das Newsletter Script max. für die Versendung der Mails benötigen darf. Ist der Wert zu niedrig, werden Sie einen Laufzeitfehler erhalten, erhöhen Sie den Wert bei Bedarf schrittweise (DEFAULT=60).');
define('kit_desc_cfg_nl_salutation',							'Sie können 10 unterschiedliche Grußformeln definieren, die Sie mit <b>{$salutation_01}</b> bis <b>{$salutation_10}</b> innerhalb des Newsletters verwenden können. Die Grußformeln bestehen jeweils aus 3 Definitionen: <b>männlich</b>, <b>weiblich</b> sowie <b>neutral</b>. Die Definitionen werden durch ein Pipe-Symbol von einander getrennt. Sie können innerhalb der Definitionen KIT CMDs verwenden.');
define('kit_desc_cfg_nl_simulate',                'Durchläuft die vollständige Versandabwicklung der Newsletter <b>ohne</b> die Mails tatsächlich zu versenden (0=AUS, 1=AN).');
define('kit_desc_cfg_register_data_dlg',					'Dialog, der den Besuchern die Verwaltung ihrer Daten ermöglicht');
define('kit_desc_cfg_register_dlg',								'Dialog, der aufgerufen wird, wenn sich Besucher registrieren oder einen Newsletter bestellen möchten');
define('kit_desc_cfg_use_captcha', 								'Legen Sie fest, ob die Dialoge im Frontend CAPTCHA zum Schutz vor Spam verwenden sollen');
define('kit_desc_cfg_use_custom_files',						'Falls gesetzt, können Sie individuell angepasste Templates und Sprachdateien verwenden. Den Dateien wird "custom." vorangestellt, z.B. "custom.DE.php", diese Dateien werden bei einem Update nicht überschrieben.');

define('kit_error_blank_title',										'<p>Die Seite muss einen Titel enthalten!</p>');
define('kit_error_cfg_id',												'<p>Der Konfigurationsdatensatz mit der <b>ID %05d</b> konnte nicht ausgelesen werden!</p>');
define('kit_error_cfg_name',											'<p>Zu dem Bezeichner <b>%s</b> wurde kein Konfigurationsdatensatz gefunden!</p>');
define('kit_error_delete_access_file',						'<p>Die Zugriffsseite <b>%s</b> konnte nicht gelöscht werden!</p>');
define('kit_error_dlg_missing',										'<p>Der angeforderte Dialog <b>%s</b> wurde nicht gefunden!</p>');
define('kit_error_google_maps_api_key_missing',		'<p>Karte kann nicht angezeigt werden, es ist kein Google Maps API Key definiert!</p>');
define('kit_error_import_massmail_grp_missing',		'<p>Die Tabelle mit den MassMail Gruppen wurde nicht gefunden!</p>');
define('kit_error_import_massmail_missing_vars',	'<p>Es wurden nicht alle benötigten Variablen zum Import von Massmail Daten übergeben.</p>');
define('kit_error_item_id',												'<p>Der Datensatz mit der <b>ID %s</b> wurde nicht gefunden!</p>');
define('kit_error_mail_init_settings',						'<p>Die WebsiteBaker Einstellungen für die Mailkonfiguration konnten nicht geladen werden.</p>');
define('kit_error_map_address_invalid',						'<p>Die Adresse <b>%s</b> wurde nicht gefunden!</p>');
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
define('kit_error_request_no_action',							'<p>[kitRequest] Es wurden keine geeigneten Parameter übergeben!</p><p><b>Hinweis:</b> Diese Fehlermeldung wird auch dann angezeigt, wenn Sie versucht haben, einen Dialog erneut zu laden (<i>Reload Sperre</i>).</p>');
define('kit_error_request_missing_parameter',			'<p>[kitRequest] Der Parameter <b>%s</b> wurde nicht angegeben, Vorgang abgebrochen!</p>');
define('kit_error_request_parameter_incomplete',	'<p>[kitRequest] Die übergebenen Parameter sind nicht vollständig, der Befehl konnte nicht ausgeführt werden.</p>');
define('kit_error_undefined',											'<p>Es ist ein nicht näher definierter Fehler aufgetreten, bitte informieren Sie den Support über das aufgetretene Problem.</p>');

define('kit_header_addresses',										'Adressen, Stadtplan');
define('kit_header_categories',										'Kategorien');
define('kit_header_cfg',													'Einstellungen');
define('kit_header_cfg_array',										'Listen bearbeiten und ergänzen');
define('kit_header_cfg_description',							'Beschreibung');
define('kit_header_cfg_identifier',								'Bezeichner');
define('kit_header_cfg_import',										'Daten importieren');
define('kit_header_cfg_label',										'Label');
define('kit_header_cfg_typ',											'Typ');
define('kit_header_cfg_value',										'Wert');
define('kit_header_communication',								'Kommunikation');
define('kit_header_contact',											'Kontakt');
define('kit_header_contact_list',									'Kontaktliste');
define('kit_header_email',												'E-Mail versenden');
define('kit_header_error',												'KeepInTouch (KIT) Fehlermeldung');
define('kit_header_help_documentation',						'Hilfe & Dokumentation');
define('kit_header_nl_cronjob_protocol_list',     'Ausgeführte Jobs');
define('kit_header_nl_cronjob_active_list',       'Noch nicht ausgeführte Jobs');
define('kit_header_preview',											'Vorschau');
define('kit_header_protocol',											'Protokoll');
define('kit_header_provider',											'Dienstleister');
define('kit_header_template',											'Templates bearbeiten');

define('kit_hint_error_msg',											'<p>Wenn Sie diese Fehlermeldung mehrfach angezeigt bekommen oder vermuten, dass es sich hierbei um eine Fehlfunktion handelt, nehmen Sie bitte Verbindung mit dem Systemadministrator auf!</p>');

define('kit_info',																'<a href="http://phpmanufaktur.de/kit" target="_blank">KeepInTouch (KIT)</a>, Release %s - Open Source CRM for WebsiteBaker - (c) 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>');

define('kit_intro_cfg',														'<p>Bearbeiten Sie die Einstellungen für <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_add_item',									'<p>Das Hinzufügen von Einträgen zur Konfiguration ist nur sinnvoll, wenn die angegebenen Werte mit dem Programm korrespondieren.</p>');
define('kit_intro_cfg_array',											'<p>Bearbeiten Sie die unterschiedlichen Listen für <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_import',										'<p>Mit diesem Dialog können Sie Daten aus anderen Anwendungen in <b>dbKeepInTouch</b> importieren.</p>');
define('kit_intro_cfg_provider',									'<p>Wählen Sie einen Dienstleister zum Bearbeiten aus oder legen Sie einen neuen Dienstleister an.</p>');
define('kit_intro_contact',												'<p>Mit diesem Dialog bearbeiten Sie die Kontaktdaten</p>');
define('kit_intro_contact_list',									'<p>Diese Liste zeigt Ihnen die verfügbaren Kontakte je nach ausgewählter Sortierung an.</p>');
define('kit_intro_cronjobs',                      '<p>KeepInTouch (KIT) benötigt für den Versand der Newsletter einen Cronjob der in regelmäßigen Abständen, z.B. alle 5 Minuten die Steuerdatei <b>/modules/kit/cronjob.php</b> aufruft.</p><p>Auf diese Weise wird sichergestellt, dass KIT eine grosse Anzahl von Newslettern kontinuierlich in kleineren Paketen versendet. Durch den maßvollen Versand wird außerdem verhindert, dass Ihre Aussendung von Ihrem Provider als kritisches Massenmailing klassifiziert wird.</p><p>Sollte Ihnen auf Ihrem Webserver die Möglichkeit fehlen Cronjobs auszuführen, verwenden Sie einfach einen kostenlosen Dienstleister, wie <a href="http://www.cronjob.de" target="_blank">cronjob.de</a> für die Ansteuerung von KIT.</p></p>');
define('kit_intro_email',													'<p>Mit diesem Dialog können Sie E-Mails erstellen und versenden.</p>');
define('kit_intro_newsletter_cfg',								'<p>Bearbeiten Sie die speziellen Einstellungen für das Newsletter Modul von KeepInTouch.</p>');
define('kit_intro_newsletter_create',							'<p>Erstellen Sie einen Newsletter und versenden Sie ihn an ihre Abonnenten.</p>');
define('kit_intro_newsletter_commands',						'<p>Befehle und Variablen werden zur Laufzeit ausgeführt und in das Template eingefügt.</p><p>Ein einfacher Klick genügt um den jeweiligen Befehl an der Cursor Position in den <b>HTML Code</b> einzufügen.</p>');
define('kit_intro_newsletter_template',						'<p>Wählen Sie ein Newsletter Template zum Bearbeiten aus oder legen Sie ein neues Template an.</p>');
define('kit_intro_nl_cronjob_active_list',        '<p>Die Liste zeigt Ihnen aktuelle Cronjobs an, die noch nicht ausgeführt worden sind.</p>');
define('kit_intro_nl_cronjob_protocol_list',      '<p>Diese Liste zeigt die letzten 200 durchgeführten Newsletter Jobs, die der KeepInTouch Cronjob durchgeführt hat.</p>');
define('kit_intro_preview',												'<p>Prüfen Sie die Vorschau in der <b>HTML</b> und in der <b>NUR TEXT</b> Ansicht.</p>');
define('kit_intro_register_installation',         '<p>Registrieren Sie Ihre KeepInTouch Installation.</p><p>Dies ermöglicht Ihnen unentgeltlich den vollen Funktionsumfang von KeepInTouch zu testen.</p>');

define('kit_label_add_new_address',								'Zusätzliche Anschrift hinzufügen');
define('kit_label_address_city',									'Stadt');
define('kit_label_address_street',								'Straße');
define('kit_label_address_zip',										'PLZ');
define('kit_label_address_zip_city',							'PLZ, Stadt');
define('kit_label_archive_id',                    'Archiv ID');
define('kit_label_audience',											'Empfänger');
define('kit_label_birthday',											'Geburtstag');
define('kit_label_categories',										'Kategorien');
define('kit_label_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen');
define('kit_label_cfg_array_add_items',						'Fügen Sie weitere Einträge hinzu:');
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
define('kit_label_cfg_register_dlg',							'Benutzer, Registrierung');
define('kit_label_cfg_use_captcha',								'CAPTCHA verwenden');
define('kit_label_cfg_use_custom_files',					'Angepasste Dateien zulassen');
define('kit_label_checksum',											'Prüfsumme');
define('kit_label_contact_access',								'Kontakt Freigabe');
define('kit_label_contact_edit',									'Kontakt bearbeiten');
define('kit_label_contact_email',									'E-Mail');
define('kit_label_contact_phone',									'Telefon');
define('kit_label_contact_since',									'Kontakt seit');
define('kit_label_contact_note',									'Anmerkungen');
define('kit_label_company_additional',						'Zusatz');
define('kit_label_company_department',						'Abteilung');
define('kit_label_contact_status',								'Kontakt Status');
define('kit_label_contact_identifier',						'Kontakt Bezeichner');
define('kit_label_company_name',									'Firma');
define('kit_label_company_title',									'Anrede');
define('kit_label_contact_type',									'Kontakt Typ');
define('kit_label_csv_export',										'CSV Export');
define('kit_label_csv_import',										'CSV Import');
define('kit_label_html_format',										'HTML Format');
define('kit_label_id',														'ID');
define('kit_label_identifier',										'Bezeichner');
define('kit_label_image',													'Bild');
define('kit_label_import_action',									'');
define('kit_label_import_from',										'Import');
define('kit_label_job_id',                        'Job ID');
define('kit_label_job_created',                   'Beauftragt');
define('kit_label_job_process',                   'Prozess');
define('kit_label_job_count',                     'E-Mails SOLL');
define('kit_label_job_done',                      'Ausgeführt');
define('kit_label_job_time',                      'Dauer (Sec.)');
define('kit_label_job_send',                      'E-Mails IST');
define('kit_label_kit_id',												'KIT ID');
define('kit_label_last_changed_by',								'Letzte Änderung');
define('kit_label_list_sort',											'Liste sortieren nach');
define('kit_label_mail_bcc',											'<i>BCC</i> <b>Empfänger</b>');
define('kit_label_mail_from',											'Absender');
define('kit_label_mail_subject',									'Betreff');
define('kit_label_mail_text',											'Nachricht');
define('kit_label_mail_to',												'Empfänger');
define('kit_label_map',														'&nbsp;');
define('kit_label_massmail',											'MassMail');
define('kit_label_newsletter',										'Newsletter');
define('kit_label_newsletter_commands',						'Befehle & Variablen');
define('kit_label_newsletter_send_from_no',       'Versende von Datensatz Nr.:');
define('kit_label_newsletter_send_to_no',         'bis Datensatz Nr.:');
define('kit_label_newsletter_tpl_desc',						'Beschreibung');
define('kit_label_newsletter_tpl_html',						'HTML Code');
define('kit_label_newsletter_tpl_name',						'Bezeichnung');
define('kit_label_newsletter_tpl_select',					'Template');
define('kit_label_newsletter_tpl_text',						'NUR TEXT');
define('kit_label_newsletter_tpl_text_preview',		'<b>NUR TEXT</b><br /><span style="font-size:smaller;">Die Inhalte werden in dieser Ansicht nicht automatisch umgebrochen!</span>');
define('kit_label_password',											'Passwort');
define('kit_label_password_retype',								'Passwort wiederholen');
define('kit_label_person_title',									'Anrede');
define('kit_label_person_title_academic',					'Titel');
define('kit_label_person_first_name',							'Vorname');
define('kit_label_person_last_name',							'Nachname');
define('kit_label_person_function',								'Funktion/Position');
define('kit_label_protocol',											'Protokoll');
define('kit_label_protocol_date',									'Datum');
define('kit_label_protocol_members',							'Teilnehmer');
define('kit_label_protocol_memo',									'Eintrag');
define('kit_label_protocol_type',									'Kontakt');
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
define('kit_label_type',													'Typ');
define('kit_label_unsubscribe',										'Abmelden');
define('kit_label_value',													'Wert');

define('kit_list_sort_city',											'Stadt');
define('kit_list_sort_company',										'Firma');
define('kit_list_sort_email',											'E-Mail');
define('kit_list_sort_lastname',									'Nachname');
define('kit_list_sort_firstname',									'Vorname');
define('kit_list_sort_phone',											'Rufnummer');
define('kit_list_sort_street',										'Straße');
define('kit_list_sort_unsorted',									'- unsortiert -');

define('kit_msg_address_deleted',									'<p>Ein Adressdatensatz zu dem Kontakt mit der <b>ID %05d</b> wurde gelöscht.</p>');
define('kit_msg_address_insert',									'<p>Zu dem Kontakt wurde eine neue Adresse hinzugefügt.</p>');
define('kit_msg_address_invalid',									'<p>Die Adresse kann nicht übernommen werden, die Angaben sind nicht ausreichend.<br />Bitte geben Sie <i>Straße, PLZ und Stadt</i> oder <i>Straße und Stadt</i> oder nur <i>Stadt</i> an.</p>');
define('kit_msg_address_update',									'<p>Ein Adressdatensatz zu dem Kontakt mit der <b>ID %05d</b> wurden aktualisiert.</p>');
define('kit_msg_categories_changed',							'<p>Die Kategorien wurden geändert.</p>');
define('kit_msg_cfg_add_exists',									'<p>Der Konfigurationsdatensatz mit dem Bezeichner <b>%s</b> existiert bereits und kann nicht noch einmal hinzugefügt werden!</p>');
define('kit_msg_cfg_add_incomplete',							'<p>Der neu hinzuzuf�gende Konfigurationsdatensatz ist unvollständig! Bitte prüfen Sie Ihre Angaben!</p>');
define('kit_msg_cfg_add_success',									'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_cfg_array_item_updated',					'<p>Der Eintrag mit der <b>ID %05d</b> wurde aktualisiert.</p>');
define('kit_msg_cfg_array_item_add',							'<p>Der Eintrag mit der <b>ID %05d</b> wurde hinzugefügt.</p>');
define('kit_msg_cfg_array_identifier_in_use',			'<p>Der Bezeichner <b>%s</b> wird bereits von der <b>ID %05d</b> verwendet und kann nicht übernommen werden. Bitte legen Sie einen anderen Bezeichner fest.</p>');
define('kit_msg_cfg_csv_export',									'<p>Die Konfigurationsdaten wurden als <b>%s</b> im /MEDIA Verzeichnis gesichert.</p>');
define('kit_msg_cfg_id_updated',									'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_contact_deleted',									'<p>Der Kontakt mit der <b>ID %05d</b> wurde gelöscht.</p>');
define('kit_msg_contact_insert',									'<p>Der Kontakt mit der <b>ID %05d</b> wurde erfolgreich angelegt und gesichert.</p>');
define('kit_msg_contact_minimum_failed',					'<p>Der <b>Datensatz kann nicht gesichert werden</b>, da die minimalen Anforderungen nicht erfüllt sind.<br />Bitte geben Sie <i>mindestens</i> entweder eine E-Mail Adresse <i>oder</i> einen Firmennamen und eine Stadt <i>oder</i> einen Firmennamen und eine Telefonnummer <i>oder</i> einen Nachnamen und eine Stadt <i>oder</i> einen Nachnamen und eine Telefonnummer an.</p>');
define('kit_msg_contact_update',									'<p>Der Kontakt mit der <b>ID %05d</b> wurde erfolgreich aktualisiert.</p>');
define('kit_msg_email_added',											'<p>Die E-Mail Adresse <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_email_changed',										'<p>Die E-Mail Adresse <i>%s</i> wurde in <b>%s</b> geändert.</p>');
define('kit_msg_email_deleted',										'<p>Die E-Mail Adresse <b>%s</b> wurde gelöscht.</p>');
define('kit_msg_email_invalid',										'<p>Die E-Mail Adresse <b>%s</b> ist nicht gültig, bitte prüfen Sie Ihre Eingabe.</p>');
define('kit_msg_email_type_changed',							'<p>Der Typ für die E-Mail Adresse <b>%s</b> wurde geändert.</p>');
define('kit_msg_internet_added',									'<p>Die Internetadresse <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_internet_changed',								'<p>Die Internetadresse <i>%s</i> wurde in <b>%s</b> geändert.</p>');
define('kit_msg_internet_deleted',								'<p>Die Internetadresse <b>%s</b> wurde gelöscht.</p>');
define('kit_msg_internet_invalid',								'<p>Die Internetadresse <b>%s</b> ist nicht gültig, bitte prüfen Sie Ihre Eingabe.</p>');
define('kit_msg_internet_type_changed',						'<p>Der Typ für die Internetadresse <b>%s</b> wurde geändert.</p>');
define('kit_msg_invalid_email',										'<p>Die E-Mail Adresse <b>%s</b> ist nicht gültig, bitte prüfen Sie Ihre Eingabe.</p>');
define('kit_msg_mail_incomplete',									'<p>Die Angaben sind unvollständig: E-Mail Absender, E-Mail Empfänger, Auswahl einer Kategorie, Betreff und Text müssen gesetzt sein.</p>');
define('kit_msg_mail_send_error',									'<p>Die E-Mail konnte nicht versendet werden, es sind insgesamt <b>%d Fehler</b> aufgetreten, die Fehlermeldung lautet:<br /><b>%s</b></p>');
define('kit_msg_mail_send_success',								'<p>Die E-Mail wurde erfolgreich versendet.</p>');
define('kit_msg_mails_send_success',							'<p>Es wurden <b>%d</b> E-Mails erfolgreich versendet.</p>');
define('kit_msg_mails_send_errors',								'<p>Es traten insgesamt <b>%d</b> Fehler mit folgenden Meldungen auf:</p>%s');
define('kit_msg_massmail_not_installed',					'<p>MassMail ist nicht installiert.</p>');
define('kit_msg_massmail_group_no_data',					'<p>Die MassMail Gruppe mit der <b>ID %d</b> enthält keine Datensätze.</p>');
define('kit_msg_massmail_email_skipped',					'<p>Die folgenden E-Mail Adressen werden in den angegebenen KIT Datensätzen bereits verwendet und wurden deshalb <b>ignoriert</b>: %s</p>');
define('kit_msg_massmail_no_emails_imported',			'<p><b>Es wurden keine E-Mail Adressen übernommen!</b></p>');
define('kit_msg_massmail_emails_imported',				'<p>Es wurden insgesamt <b>%d E-Mail Adressen</b> als eigenständige Datensätze übernommen: %s</p>');
define('kit_msg_newsletter_adjust_register',      '<p>Der Befehl <b>cfgAdjustRegister</b> wurde für <b>%s</b> ausgeführt!</p>');
define('kit_msg_newsletter_new_no_groups',				'<p>Bitte wählen Sie eine oder mehrere <b>Newsletter Gruppen</b> aus!</p>');
define('kit_msg_newsletter_new_no_html',					'<p>Bitte geben Sie den Newsletter Text im <b>HTML Format</b> an!</p>');
define('kit_msg_newsletter_new_no_provider',			'<p>Bitte wählen Sie einen <b>Dienstleister</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_subject',				'<p>Bitte geben Sie einen Betreff für den Newsletter an!</p>');
define('kit_msg_newsletter_new_no_template',			'<p>Bitte wählen Sie ein <b>Template</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_text',					'<p>Das <b>NUR TEXT</b> Format wurde automatisch generiert, bitte pruefen Sie die Ausgabe!</p>');
define('kit_msg_newsletter_simulate_mailing',     '<p><b>SIMULATIONSMODUS AKTIV</b> - es werden keine Newsletter versendet!</p>');
define('kit_msg_newsletter_tpl_added',						'<p>Das Template <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_newsletter_tpl_changed',					'<p>Das Template <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_newsletter_tpl_cmd_content',			'<p>Datensatz nicht gesichert, das Template muss zumindest den Platzhalter <b>%s</b> enthalten. An dieser Stelle wird der eigentliche Inhalt des Newsletters eingefügt.</p>');
define('kit_msg_newsletter_tpl_minimum_failed',		'<p>Datensatz nicht gesichert, Sie müssen mindestens einen <b>Bezeichner</b> und den <b>HTML Code</b> angeben!</p>');
define('kit_msg_newsletter_tpl_missing',					'<p><b>Sie haben noch kein Template für den Versand von Newslettern angelegt.</b></p><p>Bitte legen Sie zunächst ein Template an, bevor Sie einen Newsletter erstellen.</p>');
define('kit_msg_newsletter_tpl_text_inserted',		'<p>Die <b>NUR TEXT</b> Ausgabe des Templates wurde automatisch generiert, bitte prüfen Sie die Ausgabe.</p>');
define('kit_msg_newsletter_tpl_unchanged',				'<p>Das Template mit der <b>ID %05d</b> wurde nicht geändert.</p>');
define('kit_msg_password_needed',									'<p>Bitte geben Sie das Passwort an!</p>');
define('kit_msg_passwords_mismatch',							'<p>Die angegebenen Passwörter stimmen nicht überein!</p>');
define('kit_msg_phone_added',											'<p>Die Telefonnummer <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_phone_changed',										'<p>Die Telefonnummer <i>%s</i> wurde in <b>%s</b> geändert.</p>');
define('kit_msg_phone_deleted',										'<p>Die Telefonnummer <b>%s</b> wurde gelöscht.</p>');
define('kit_msg_phone_invalid',										'<p>Die Telefonnummer <b>%s</b> ist nicht gültig, bitte prüfen Sie Ihre Eingabe. Es werden nur Telefonnummern im internationalen Format +49 (30) 1234567 akzeptiert.</p>');
define('kit_msg_phone_type_changed',							'<p>Der Typ für die Telefonnummer <b>%s</b> wurde geändert.</p>');
define('kit_msg_protocol_updated',								'<p>Das Protokoll wurde aktualisiert.</p>');
define('kit_msg_provider_check_auth',							'<p>Sie haben keine SMTP Authentifizierung angegeben aber einen SMTP Host und Namen eingetragen, bitte prüfen Sie Ihre Angaben!</p>');
define('kit_msg_provider_inserted',								'<p>Der Dienstleister <b>%s</b> wurde neu angelegt.</p>');
define('kit_msg_provider_minum_failed',						'<p>Die Angaben zum Dienstleister reichen nicht aus. Sie müssen mindestens angeben: Name und E-Mail Adresse und - falls der Mailserver eine SMTP Authentifizierung erfordert - SMTP Host, SMTP Benutzername und das Passwort.</p>');
define('kit_msg_provider_updated',								'<p>Der Dienstleister <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_service_invalid_user_name',       '<p>Bitte geben Sie einen gültigen Vor- und Nachnamen an.</p>');
define('kit_msg_service_license_beta_evaluate',   '<p><b>Diese KeepInTouch Installation ist nicht registriert.</b></p><p><a href="%s">Registrieren Sie diese BETA Version</a> damit Sie den vollen Funktionsumfang von KeepInTouch testen können!</p>');
define('kit_msg_service_license_beta_registered', '<p<b>KeepInTouch BETA</b><br /><i>%s</i><br />Lizenz gültig bis zum %s, registriert für <i>%s %s</i>.</p>');
define('kit_msg_service_no_connect',              '<p>Der Updateserver konnte nicht erreicht werden.</p>');

define('kit_protocol_create_contact',							'%s: Datensatz angelegt.');
define('kit_protocol_create_contact_massmail',		'Datensatz durch Import der E-Mail Adresse %s von Massmail angelegt.');
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

define('kit_tab_cfg_array',												'Listen anpassen');
define('kit_tab_cfg_general',											'Allgemein');
define('kit_tab_cfg_import',											'Import/Export');
define('kit_tab_cfg_provider',										'Dienstleister');
define('kit_tab_config',													'Einstellungen');
define('kit_tab_contact',													'Kontakt bearbeiten');
define('kit_tab_cronjobs_active',                 'Aktuelle Jobs');
define('kit_tab_cronjobs_protocol',               'Protokoll');
define('kit_tab_email',														'Gruppen E-Mail');
define('kit_tab_help',														'?');
define('kit_tab_list',														'Alle Kontakte');
define('kit_tab_newsletter',											'Newsletter versenden');
define('kit_tab_nl_create',												'Newsletter erstellen');
define('kit_tab_nl_template',											'Vorlagen verwalten');
define('kit_tab_start',														'Start');

define('kit_text_as_email_type',									'als E-Mail Typ:');
define('kit_text_calendar_delete',								'Datum löschen');
define('kit_text_calendar_select',								'Datum auswählen');
define('kit_text_from_massmail_group',						'Gruppe importieren:');
define('kit_text_new_id',													'- neu -');
define('kit_text_please_select',									'- bitte auswählen -');
define('kit_text_process_execute',                '<b>ausführen</b>');
define('kit_text_process_simulate',               '<i>simulieren</i>');
define('kit_text_records',												'Datensätze');
define('kit_text_to_category',										'in die KIT Kategorie:');
define('kit_text_unknown',                        '- unbekannt -');

?>