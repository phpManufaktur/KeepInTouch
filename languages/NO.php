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
  
  $Id: NO.php 49 2010-08-06 05:03:59Z ralf $

	IMPORTANT NOTE:

  If you are editing this file or creating a new language file
  you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
  Otherwise all special chars will be destroyed and displayed improper!

	It is NOT NECESSARY to mask special chars as HTML entities!

  Translated to Norwegian by Odd Egil Hansen (oeh) 
  
**/




// Norwegian Module Description
$module_description 	= 'dbKeepInTouch (KIT) er et omfrattende og kraftig kontakt behandlings verkt&oslash;y.';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Odd Egil Hansen (oeh)';

define('kit_btn_abort',														'Avbryt');
define('kit_btn_edit',														'Bearbeiten');
define('kit_btn_export',													'Eksporter');
define('kit_btn_import',													'Import');
define('kit_btn_mail_bcc',												'BCC:');
define('kit_btn_mail_from',												'Fra:');
define('kit_btn_mail_to',													'Til:');
define('kit_btn_ok',															'Ok');
define('kit_btn_preview',													'Vorschau');
define('kit_btn_save',														'Speichern');
define('kit_btn_send',														'Send');

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

define('kit_contact_access_internal',							'Internt');
define('kit_contact_access_public',								'Offentlig');
define('kit_contact_category_newsletter',					'Nyhetsbrev');
define('kit_contact_category_wb_user',						'Bruker');
define('kit_contact_company_title_none',					'');
define('kit_contact_company_title_to',						'Av');
define('kit_contact_email_private',								'e-post, privat');
define('kit_contact_email_business',							'e-post, jobb');
define('kit_contact_internet_facebook',						'Facebook');
define('kit_contact_internet_homepage',						'Hjemmeside');
define('kit_contact_internet_twitter',						'Twitter');
define('kit_contact_internet_xing',								'Xing');
define('kit_contact_newsletter_newsletter',				'Newsletter');
define('kit_contact_person_title_academic_dr',		'Dr.');
define('kit_contact_person_title_academic_none',	'');
define('kit_contact_person_title_academic_prof',	'Prof.');
define('kit_contact_person_title_lady',						'Fru.');
define('kit_contact_person_title_mister',					'Hr.');
define('kit_contact_phone_fax',										'Fax');
define('kit_contact_phone_handy',									'Mobil');
define('kit_contact_phone_phone',									'Telefon');
define('kit_contact_status_active',								'Aktiv');
define('kit_contact_status_deleted',							'Slettet');
define('kit_contact_status_key_created',					'Schlüssel erzeugt');
define('kit_contact_status_key_send',							'Wartet auf Aktivierung');
define('kit_contact_status_locked',								'L&aring;st');
define('kit_contact_type_company',								'Firma');
define('kit_contact_type_institution',						'Institusjon');
define('kit_contact_type_person',									'Persjon');

define('kit_contact_array_type_access',						'Kontakt Frigi');
define('kit_contact_array_type_category',					'Kontakt Kategori');
define('kit_contact_array_type_company_title',		'Firmanavn');
define('kit_contact_array_type_email',						'e-post');
define('kit_contact_array_type_type',							'Kontakt Type');
define('kit_contact_array_type_newsletter',				'Newsletter');
define('kit_contact_array_type_internet',					'Website Address');
define('kit_contact_array_type_person_academic',	'Tittle');
define('kit_contact_array_type_person_title',			'Person Tittel');
define('kit_contact_array_type_phone',						'Telefon');
define('kit_contact_array_type_protocol',					'Protokoll type');
define('kit_contact_array_type_undefined',				'- udefinert -');

define('kit_contact_address_type_undefined',			'');
define('kit_contact_address_type_business',				'Bedrift');
define('kit_contact_address_type_private',				'Privat');

define('kit_contact_protocol_type_call',					'Telefonsamtale');
define('kit_contact_protocol_type_email',					'e-post');
define('kit_contact_protocol_type_meeting',				'M&oslash;te');
define('kit_contact_protocol_type_memo',					'Notat');
define('kit_contact_protocol_type_newsletter',		'Nyhetsbrev');
define('kit_contact_protocol_type_undefined',			'- &Aring;pen -');

define('kit_country_austria',											'Austria');
define('kit_country_germany',											'Germany');
define('kit_country_suisse',											'Switzerland');
define('kit_country_undefined',										'');

define('kit_desc_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen, um einfach zu anderen Add-ons wechseln zu können. TAB\'s mit Komma trennen, Aufbau BEZEICHNER|URL');
define('kit_desc_cfg_developer_mode',							'Gj&oslash;r det mulig &aring; legge tilkonfigurasjonsparametere for programerere.');
define('kit_desc_cfg_google_maps_api_key',				'For &aring; benytte og vise kart trenger du en Google Maps API n&oslash;kkel.');
define('kit_desc_cfg_kit_request_link',						'<b>kit.php</b> nimmt alle Anfragen entgegen, gibt Daten zurück oder ruft Dialoge auf. Die Datei befindet sich im Verzeichnis /modules/kit, sie kann aber auch an eine andere Stelle kopiert werden, z.B. in das Root-Verzeichnis');
define('kit_desc_cfg_kit_response_page', 					'KIT benötigt für die Anzeige von Dialogen und Hinweisen eine eigene Seite');
define('kit_desc_cfg_max_invalid_login',					'Maximale Anzahl von fehlerhaften Login Versuchen von Anwendern, bevor das Konto gesperrt wird.');
define('kit_desc_cfg_nl_salutation',							'Sie können 10 unterschiedliche Grußformeln definieren, die Sie mit {$salutation_01} bis {$salutation_10} innerhlab des Newsletters verwenden können. Die Grußformeln bestehen jeweils aus 3 Definitionen: männlich, weiblich sowie unbekannt. Die Definitionen werden durch ein Pipe-Symbol von einander getrennt. Sie können innerhalb der Definitionen KIT CMDs verwenden.');
define('kit_desc_cfg_register_data_dlg',					'Dialog, der den Besuchern die Verwaltung ihrer Daten ermöglicht');
define('kit_desc_cfg_register_dlg',								'Dialog, der aufgerufen wird, wenn sich Besucher registrieren oder einen Newsletter bestellen möchten');
define('kit_desc_cfg_use_captcha', 								'Legen Sie fest, ob die Dialoge im Frontend CAPTCHA zum Schutz vor Spam verwenden sollen');
define('kit_desc_cfg_use_custom_files',						'Falls gesetzt, können Sie individuell angepasste Templates und Sprachdateien verwenden. Den Dateien wird "custom." vorangestellt, z.B. "custom.DE.php", diese Dateien werden bei einem Update nicht überschrieben.');

define('kit_error_blank_title',										'<p>Die Seite muss einen Titel enthalten!</p>');
define('kit_error_cfg_id',												'<p>Konfigurasjonsdataene med ID <b>ID %05d</b> kunne ikke leses!</p>');
define('kit_error_cfg_name',											'<p>Det ble ikke funnet konfigutasjonsdata for posten <b>%s</b>!</p>');
define('kit_error_delete_access_file',						'<p>Die Zugriffsseite <b>%s</b> konnte nicht gelöscht werden!</p>');
define('kit_error_dlg_missing',										'<p>Der angeforderte Dialog <b>%s</b> wurde nicht gefunden!</p>');
define('kit_error_google_maps_api_key_missing',		'<p>Kartet kan ikke vises. Det er ikke lagt inn en Google Maps API n&oslash;kkel!</p>');
define('kit_error_import_massmail_grp_missing',		'<p>Tabellen med MassMail gruppene ble ikke funnet!</p>');
define('kit_error_import_massmail_missing_vars',	'<p>Alle de n&oslash;dvendige dataene ble ikke importert til MassMail.</p>');
define('kit_error_item_id',												'<p>Dataoppf&oslash;ringen med ID <b>ID %s</b> ble ikke funnet!</p>');
define('kit_error_map_address_invalid',						'<p>Adressen <b>%s</b> ble ikke funnet!</p>');
define('kit_error_mail_init_settings',						'<p>WebsiteBaker sine e-post innstillinger kunne ikke lastes.</p>');
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
define('kit_header_cfg',													'Konfigurasjon');
define('kit_header_cfg_array',										'Rediger og legg til lister');
define('kit_header_cfg_description',							'Beskrivelse');
define('kit_header_cfg_identifier',								'Identifikasjonsnavn ');
define('kit_header_cfg_import',										'Importer Data');
define('kit_header_cfg_label',										'Etikett');
define('kit_header_cfg_typ',											'Type');
define('kit_header_cfg_value',										'Verdi');
define('kit_header_communication',								'Kommunikation');
define('kit_header_contact',											'Kontakt');
define('kit_header_contact_list',									'Kontaktliste');
define('kit_header_email',												'e-post Sentralbord');
define('kit_header_error',												'KeepInTouch (KIT) Fehlermeldung');
define('kit_header_help_documentation',						'Hilfe & Dokumentation');
define('kit_header_preview',											'Vorschau');
define('kit_header_protocol',											'Protokoll');
define('kit_header_provider',											'Dienstleister');
define('kit_header_template',											'Templates bearbeiten');

define('kit_hint_error_msg',											'<p>Bitte nehmen Sie Verbindung mit dem Systemadministrator auf!</p>');

define('kit_info',																'<a href="http://phpmanufaktur.de/kit" target="_blank">KeepInTouch (KIT)</a>, Release %s - Open Source CRM for WebsiteBaker - (c) 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>');

define('kit_intro_cfg',														'<p>Endre innstillingene for <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_add_item',									'<p>Innlegging av data i konfigurasjonene har bare mening dersom de samsvarer med programmet.</p>');
define('kit_intro_cfg_array',											'<p>Jobb med forskjellige <b>dbKeepInTouch</b> lister.</p>');
define('kit_intro_cfg_import',										'<p>Her kan du importere data fra andre programmer til <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_provider',									'<p>Wählen Sie einen Dienstleister zum Bearbeiten aus oder legen Sie einen neuen Dienstleister an.</p>');
define('kit_intro_contact',												'<p>Bruk denne dilogboksen til &aring; redigerer kontaktinformasjonen</p>');
define('kit_intro_contact_list',									'<p>Denne listen viser de tilgjengelige kontaketene avhengig av hvilke utvalg som er gjort.</p>');
define('kit_intro_email',													'<p>Her kan du opprette og sende e-post.</p>');
define('kit_intro_newsletter_cfg',								'<p>Bearbeiten Sie die speziellen Einstellungen für das Newsletter Modul von KeepInTouch.</p>');
define('kit_intro_newsletter_create',							'<p>Erstellen Sie einen Newsletter und versenden Sie ihn an ihre Abonnenten.</p>');
define('kit_intro_newsletter_commands',						'<p>Befehle und Variablen werden zur Laufzeit ausgeführt und in das Template eingefügt.</p>');
define('kit_intro_newsletter_template',						'<p>Wählen Sie ein Newsletter Template zum Bearbeiten aus oder legen Sie ein neues Template an.</p>');
define('kit_intro_preview',												'<p>Prüfen Sie die Vorschau in der <b>HTML</b> und in der <b>NUR TEXT</b> Ansicht.</p>');

define('kit_label_add_new_address',								'Legg Til Ytterligere Adresse');
define('kit_label_address_city',									'Postadresse');
define('kit_label_address_street',								'Gate');
define('kit_label_address_zip_city',							'Postnummer');
define('kit_label_audience',											'Empfänger');
define('kit_label_birthday',											'Gebursdag');
define('kit_label_categories',										'Kategorier');
define('kit_label_cfg_add_app_tab',								'Zusätzliche TAB\'s einfügen');
define('kit_label_cfg_array_add_items',						'Legg Til Flere Oppf&oslash;ringer:');
define('kit_label_contact_edit',									'Rediger Kontakt');
define('kit_label_contact_email',									'e-post');
define('kit_label_cfg_kit_request_link',					'KIT Request Link');
define('kit_label_contact_note',									'Notater');
define('kit_label_cfg_max_invalid_login',					'Maximale Loginversuche');
define('kit_label_cfg_nl_salutation',							'Grußformel');
define('kit_label_cfg_register_data_dlg',					'Benutzer, Datenverwalten');
define('kit_label_cfg_register_dlg',							'Benutzer Registrierung');
define('kit_label_cfg_use_captcha',								'CAPTCHA verwenden');
define('kit_label_cfg_use_custom_files',					'Angepasste Dateien zulassen');
define('kit_label_checksum',											'Prüfsumme');
define('kit_label_contact_since',									'Kontakt Siden');
define('kit_label_cfg_developer_mode',						'Utvikkler Modus');
define('kit_label_cfg_google_maps_api_key',				'Google Maps API N&oslash;kkel');
define('kit_label_cfg_kit_reponse_page',					'KIT Antwortseite');
define('kit_label_company_additional',						'Tilleggsinnformasjon');
define('kit_label_company_department',						'Avdeling');
define('kit_label_company_name',									'Firma');
define('kit_label_company_title',									'Tittel');
define('kit_label_contact_access',								'Del KontaktContact Release');
define('kit_label_contact_identifier',						'Kontakt identifikator');
define('kit_label_contact_phone',									'Telefon');
define('kit_label_contact_type',									'Kontakt Type');
define('kit_label_contact_status',								'Kontakt Status');
define('kit_label_csv_import',										'CSV Import');
define('kit_label_csv_export',										'CSV Eksport');
define('kit_label_html_format',										'HTML Format');
define('kit_label_id',														'ID');
define('kit_label_identifier',										'Identifikatorer');
define('kit_label_image',													'Bilde');
define('kit_label_import_action',									'');
define('kit_label_import_from',										'Import');
define('kit_label_kit_id',												'KIT ID');
define('kit_label_last_changed_by',								'Sist Endret Av');
define('kit_label_list_sort',											'Sorter Liste');
define('kit_label_map',														'&nbsp;');
define('kit_label_massmail',											'MassMail');
define('kit_label_mail_bcc',											'<i>BCC</i> <b>Mottaker</b>');
define('kit_label_mail_from',											'Fra');
define('kit_label_mail_subject',									'Emne');
define('kit_label_mail_text',											'Tekst');
define('kit_label_mail_to',												'Motteker');
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
define('kit_label_person_title',									'Personelig Tittel');
define('kit_label_person_title_academic',					'Akademisk Tittel');
define('kit_label_person_first_name',							'Fornavn');
define('kit_label_person_last_name',							'Etternavn');
define('kit_label_person_function',								'Funksjon/ Possisjon');
define('kit_label_protocol',											'Protokoll');
define('kit_label_protocol_date',									'Dato');
define('kit_label_protocol_members',							'Deltakere');
define('kit_label_protocol_memo',									'Notat');
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
define('kit_label_type',													'Type');
define('kit_label_unsubscribe',										'Abmelden');
define('kit_label_value',													'Verdi');

define('kit_list_sort_company',										'Firma');
define('kit_list_sort_city',											'Postadresse');
define('kit_list_sort_email',											'e-post');
define('kit_list_sort_firstname',									'Fornavn');
define('kit_list_sort_lastname',									'Etternavn');
define('kit_list_sort_phone',											'Telefon');
define('kit_list_sort_street',										'Adresse');
define('kit_list_sort_unsorted',									'- usortert -');

define('kit_msg_address_deleted',									'<p>Adressedataene for kontakten med <b>ID %05d</b> ble slettet.</p>');
define('kit_msg_address_invalid',									'<p>Adressen ikke kan aksepteres, det er ikke tilstrekkelige data.<br />Du m&aring; angi <i>Adresse, Postnummer og Postadresse</i>, eller <i>Adresse og Postadresse</i>, eller bare <i>Postadresse</i>.</p>');
define('kit_msg_address_insert',									'<p>Ny adresse ble lagt til kontakten.</p>');
define('kit_msg_address_update',									'<p>Adressedataene for kontakten med <b>ID %05d</b> ble oppdatert.</p>');
define('kit_msg_categories_changed',							'<p>Kategorien ble endret.</p>');
define('kit_msg_cfg_add_exists',									'<p>Konfigurasjonsdataene med identifikatoren <b>%s</b> eksisterer allerede og kan derfor ikke legges til p&aring; nytt!</p>');
define('kit_msg_cfg_add_incomplete',							'<p>Kofigurasjonsdataene du fors&oslash;ker &aring; legge til er ufulstendige. Sjekk de dataene du har lagt inn!</p>');
define('kit_msg_cfg_add_success',									'<p>Konfigurasjonsdataene med <b>ID #%05d</b> og identifikatoren <b>%s</b> ble lagt til.</p>');
define('kit_msg_cfg_array_item_add',							'<p>Oppf&oslash;ringen med <b>ID %05d</b> ble lagt til.</p>');
define('kit_msg_cfg_array_item_updated',					'<p>Oppf&oslash;ringen med <b>ID %05d</b> ble oppdatert.</p>');
define('kit_msg_cfg_array_identifier_in_use',			'<p>Identifikatoren <b>%s</b> er allerede i bruk p&aring; <b>ID %05d</b> og kan derfor ikke importeres . Du m&aring; spesifisere en annen identifikator.</p>');
define('kit_msg_cfg_csv_export',									'<p>Konfigurasjonsdataene ble lagret som <b>%s</b> i /MEDIA mappen.</p>');
define('kit_msg_cfg_id_updated',									'<p>Konfigurasjonsdataene med <b>ID #%05d</b> og identifikatoren <b>%s</b> ble oppdatert.</p>');
define('kit_msg_contact_deleted',									'<p>Der Kontakt mit der <b>ID %05d</b> wurde gelöscht.</p>');
define('kit_msg_contact_minimum_failed',					'<p><b>Oppf&oslash;ringen kan ikke sikkerhetskopieres </b>, fordi minimumskravet ikke er oppfylt. <br/> Vennligst fyll ut <i> minst </i> enten en e-postadresse <i> eller </i> et firmanavn og postadresse <i> eller </i> et firmanavn og telefonnummer <i> eller </i> et etternavn, og postadresse <i> eller </i> et etternavn og et telefonnummer..</p>');
define('kit_msg_contact_insert',									'<p>Kontakten med <b>ID %05d</b> ble opprettet og lagret.</p>');
define('kit_msg_contact_update',									'<p>Kontakten med <b>ID %05d</b> ble oppdatert.</p>');
define('kit_msg_email_invalid',										'<p>e-postadressen <b>%s</b> er ikke gyldig, sjekk e-postadressen du anga.</p>');
define('kit_msg_email_deleted',										'<p>e-postadressen <b>%s</b> ble slettet.</p>');
define('kit_msg_email_changed',										'<p>e-postadressen <i>%s</i> ble endret til <b>%s</b>.</p>');
define('kit_msg_email_added',											'<p>e-postadressen <b>%s</b> ble lagt til.</p>');
define('kit_msg_email_type_changed',							'<p>Type e-postadresse for e-postadressen <b>%s</b> ble endret.</p>');
define('kit_msg_internet_added',									'<p>Internettadressen <b>%s</b> ble lagt til.</p>');
define('kit_msg_internet_changed',								'<p>Internettadressen <i>%s</i> ble endret til <b>%s</b>.</p>');
define('kit_msg_internet_deleted',								'<p>Internettadressen <b>%s</b> ble slettet.</p>');
define('kit_msg_internet_invalid',								'<p>Internettadressen <b>%s</b> ier ikke gyldig, sjekk internettadressen du anga.</p>');
define('kit_msg_internet_type_changed',						'<p>Type internettadresse for internettadressen <b>%s</b> ble endret.</p>');
define('kit_msg_invalid_email',										'<p>e-post adressen <b>%s</b> er ikke gyldig, sjekk e-post adressen du angav.</p>');
define('kit_msg_mail_incomplete',									'<p>Datene er ufulstendige: avsender e-postadresse, mottaker e-postadresse, valg av en kategori, et emne og en tekst m&aring; v&aelig;re utfylt.</p>');
define('kit_msg_mail_send_success',								'<p>e-posten ble sendt.</p>');
define('kit_msg_mail_send_error',									'<p>e-posten kunne ikke sendes, det oppstod <b>%d feil</b>, feilmeldingene var:<br /><b>%s</b></p>');
define('kit_msg_mails_send_success',							'<p>Es wurden <b>%d</b> E-Mails erfolgreich versendet.</p>');
define('kit_msg_mails_send_errors',								'<p>Es traten insgesamt <b>%d</b> Fehler mit folgenden Meldungen auf:</p>%s');
define('kit_msg_massmail_not_installed',					'<p>MassMail er ikke innstallert.</p>');
define('kit_msg_massmail_group_no_data',					'<p>MassMail Gruppen med<b>ID %d</b> inneholder ingen data.</p>');
define('kit_msg_massmail_email_skipped',					'<p>Denne e-post er allerede benyttet i en annen KIT oppf&oslash;ring og ble derfor <b>ignorert</b>: %s</p>');
define('kit_msg_massmail_no_emails_imported',			'<p><b>Ingen e-postadresse ble importert!</b></p>');
define('kit_msg_massmail_emails_imported',				'<p><b>%d e-postadresser</b> ble inpoertert som selvstendige oppf&oslash;ringer: %s</p>');
define('kit_msg_newsletter_new_no_groups',				'<p>Bitte wählen Sie eine oder mehrere <b>Newsletter Gruppen</b> aus!</p>');
define('kit_msg_newsletter_new_no_html',					'<p>Bitte geben Sie den Newsletter Text im <b>HTML Format</b> an!</p>');
define('kit_msg_newsletter_new_no_provider',			'<p>Bitte wählen Sie einen <b>Dienstleister</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_subject',				'<p>Bitte geben Sie einen Betreff für den Newsletter an!</p>');
define('kit_msg_newsletter_new_no_template',			'<p>Bitte wählen Sie ein <b>Template</b> für den Versand des Newsletters aus!</p>');
define('kit_msg_newsletter_new_no_text',					'<p>Das <b>NUR TEXT</b> Format wurde automatisch generiert, bitte pruefen Sie die Ausgabe!</p>');
define('kit_msg_newsletter_tpl_added',						'<p>Dast Template <b>%s</b> wurde hinzugefügt.</p>');
define('kit_msg_newsletter_tpl_changed',					'<p>Das Template <b>%s</b> wurde aktualisiert.</p>');
define('kit_msg_newsletter_tpl_cmd_content',			'<p>Datensatz nicht gesichert, das Template muss zumindest den Platzhalter <b>%s</b> enthalten. An dieser Stelle wird der eigentliche Inhalt des Newsletters eingefügt.</p>');
define('kit_msg_newsletter_tpl_minimum_failed',		'<p>Datensatz nicht gesichert, Sie müssen mindestens einen <b>Bezeichner</b> und den <b>HTML Code</b> angeben!</p>');
define('kit_msg_newsletter_tpl_missing',					'<p><b>Sie haben noch kein Template für den Versand von Newslettern angelegt.</b></p><p>Bitte legen Sie zunächst ein Template an.</p>');
define('kit_msg_newsletter_tpl_text_inserted',		'<p>Die <b>NUR TEXT</b> Ausgabe des Templates wurde automatisch generiert, bitte prüfen Sie die Ausgabe.</p>');
define('kit_msg_newsletter_tpl_unchanged',				'<p>Das Template mit der <b>ID %05d</b> wurde nicht geändert.</p>');
define('kit_msg_password_needed',									'<p>Bitte geben Sie das Passwort an!</p>');
define('kit_msg_passwords_mismatch',							'<p>Die angegebenen Passwörter stimmen nicht überein!</p>');
define('kit_msg_phone_deleted',										'<p>Telefonnummeret <b>%s</b> ble slettet.</p>');
define('kit_msg_phone_changed',										'<p>Telefonnummeret <i>%s</i> ble endret til <b>%s</b>.</p>');
define('kit_msg_phone_invalid',										'<p>Telefonnummeret <b>%s</b> er ikke gyldig, sjekk telefonnummeret du anga.  Kun telefonnummere med det internationale formatet +49 (30) 1234567 aksepteres.</p>');
define('kit_msg_phone_added',											'<p>Telefonnummeret <b>%s</b> ble lagt til.</p>');
define('kit_msg_phone_type_changed',							'<p>Type telefonnummer for telefonnummeret <b>%s</b> ble endret.</p>');
define('kit_msg_protocol_updated',								'<p>Protokollen ble oppdatert.</p>');
define('kit_msg_provider_check_auth',							'<p>Sie haben keine SMTP Authentifizierung angegeben aber einen SMTP Host und Namen eingetragen, bitte prüfen Sie Ihre Angaben!</p>');
define('kit_msg_provider_inserted',								'<p>Der Dienstleister <b>%s</b> wurde neu angelegt.</p>');
define('kit_msg_provider_minum_failed',						'<p>Die Angaben zum Dienstleister reichen nicht aus. Sie müssen mindestens angeben: Name und E-Mail Adresse und - falls der Mailserver eine SMTP Authentifizierung erfordert - SMTP Host, SMTP Benutzername und das Passwort.</p>');
define('kit_msg_provider_updated',								'<p>Der Dienstleister <b>%s</b> wurde aktualisiert.</p>');

define('kit_protocol_create_contact',							'%s: Oppf&oslash;ringen ble opprettet.');
define('kit_protocol_create_contact_massmail',		'Data som skal benyttes ved import av e-postadresser %s oppretter i MassMail.');

define('kit_tab_cfg_array',												'Tilpassede Lister');
define('kit_tab_cfg_general',											'Generelt');
define('kit_tab_cfg_import',											'Import/Eksport');
define('kit_tab_cfg_provider',										'Service Provider');
define('kit_tab_config',													'Konfigurasjon');
define('kit_tab_contact',													'Kontakt');
define('kit_tab_email',														'e-post');
define('kit_tab_help',														'?');
define('kit_tab_list',														'Oversikt');
define('kit_tab_newsletter',											'Newsletter');
define('kit_tab_nl_create',												'Erstellen');
define('kit_tab_nl_template',											'Vorlagen');

define('kit_text_as_email_type',									'som e-post Type:');
define('kit_text_calendar_delete',								'Slett dato');
define('kit_text_calendar_select',								'Velg Dato');
define('kit_text_from_massmail_group',						'Importer Gruppe:');
define('kit_text_new_id',													'- ny -');
define('kit_text_please_select',									'- bitte auswählen -');
define('kit_text_records',												'Datensätze');
define('kit_text_to_category',										'Til KIT Kategori:');

?>