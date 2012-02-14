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
$module_description = 'dbKeepInTouch (KIT) ist eine zentrale Adress- und Kontaktverwaltung, die unterschiedlichen Anwendungen Kontaktdaten zur Verfuegung stellt.';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Frank Bos (ConsultOne)';

define('kit_btn_abort', 'Afbreken');
define('kit_btn_edit', 'Bewerken');
define('kit_btn_export', 'Exporteren');
define('kit_btn_import', 'Importeren');
define('kit_btn_mail_bcc', 'BCC geadresseerde:');
define('kit_btn_mail_from', 'Van:');
define('kit_btn_mail_to', 'An:');
define('kit_btn_next_step', 'Volgende stap');
define('kit_btn_no', 'Nee');
define('kit_btn_ok', 'Overnemen');
define('kit_btn_preview', 'Inkijken');
define('kit_btn_register', 'Registreren');
define('kit_btn_save', 'Opslaan');
define('kit_btn_send', 'Versturen');
define('kit_btn_yes', 'Ja');

define('kit_cfg_date_str', 'd.m.Y');
define('kit_cfg_date_time_str', 'd.m.Y - H:i:s');
define('kit_cfg_thousand_separator', '.');
define('kit_cfg_date_separator', '.');
define('kit_cfg_decimal_separator', ',');
define('kit_cfg_price', '%s €');
define('kit_cfg_euro', '%s EUR');
define('kit_cfg_time_zone', 'Europe/Berlin');

define('kit_cmd_nl_account_email', 'E-Mail adres abonnee');
define('kit_cmd_nl_account_first_name', 'Voornaam abonnee');
define('kit_cmd_nl_account_id', 'ID abonnee account (Account)');
define('kit_cmd_nl_account_last_name', 'Achternaam abonnee');
define('kit_cmd_nl_account_login', 'URL: Link op het login dialoog van het abonnee-account  (Account)');
define('kit_cmd_nl_account_newsletter', 'Abonnement op nieuwsbrief, comma gescheiden');
define('kit_cmd_nl_account_register_key', 'Registersleutel abonnee-account');
define('kit_cmd_nl_account_title', 'Aanroep abonnee (Heer of Mevrouw)');
define('kit_cmd_nl_account_title_academic', 'Titel abonnee (v.b. Dr. of Prof.');
define('kit_cmd_nl_account_username', 'Gebruikersnaam abonnee (Username)');
define('kit_cmd_nl_contact_id', 'ID vermelding in de contactendatabase (Contact)');
define('kit_cmd_nl_content', 'De eigenlijke tekst van de nieuwsbrief zal worden ingevoegd op dit punt ');
define('kit_cmd_nl_kit_info', 'Geeft informatie over KeepInTouch (KIT)');
define('kit_cmd_nl_kit_release', 'Geeft release nummer van KeepInTouch (KIT) ');
define('kit_cmd_nl_newsletter_unsubscribe', 'URL: Link voor afmelden van de nieuwsbriefs');
define('kit_cmd_nl_salutation', 'Groetvormen 1-10, de aanhefvariaties worden in de voorkeuren ingesteld (Aanhef) ');

define('kit_contact_access_internal', 'Intern');
define('kit_contact_access_public', 'Publiek');
define('kit_contact_category_wb_user', 'WB Gebruiker');
define('kit_contact_category_newsletter', 'nieuwsbrief');
define('kit_contact_company_title_none', '');
define('kit_contact_company_title_to', 'Aan');
define('kit_contact_distribution_control', 'Controlegroep');
define('kit_contact_email_private', 'E-Mail, privé');
define('kit_contact_email_business', 'E-Mail, zakelijk');
define('kit_contact_internet_facebook', 'Facebook');
define('kit_contact_internet_homepage', 'Homepage');
define('kit_contact_internet_twitter', 'Twitter');
define('kit_contact_internet_xing', 'Xing');
define('kit_contact_newsletter_newsletter', 'nieuwsbrief');
define('kit_contact_person_title_academic_dr', 'Dr.');
define('kit_contact_person_title_academic_none', '');
define('kit_contact_person_title_academic_prof', 'Prof.');
define('kit_contact_person_title_lady', 'Mevrouw');
define('kit_contact_person_title_mister', 'Heer');
define('kit_contact_phone_fax', 'Fax');
define('kit_contact_phone_handy', 'Handy');
define('kit_contact_phone_phone', 'Telefoon');
define('kit_contact_status_active', 'Aktief');
define('kit_contact_status_deleted', 'Verwijderd');
define('kit_contact_status_key_created', 'Sleutel gecreerd');
define('kit_contact_status_key_send', 'Sleutel verstuurd');
define('kit_contact_status_locked', 'Gesloten');
define('kit_contact_type_company', 'Bedrijf');
define('kit_contact_type_institution', 'Instituut');
define('kit_contact_type_person', 'Persoon');

define('kit_contact_array_type_access', 'Contact vrijgave');
define('kit_contact_array_type_category', 'Categorie - Intern');
define('kit_contact_array_type_company_title', 'Titel Bedrijf');
define('kit_contact_array_type_distribution', 'Categorie - distributie');
define('kit_contact_array_type_email', 'E-Mail');
define('kit_contact_array_type_internet', 'Internet Adres');
define('kit_contact_array_type_newsletter', 'Categorie - nieuwsbrief');
define('kit_contact_array_type_person_academic', 'Persoon Titel');
define('kit_contact_array_type_person_title', 'Titel Persoon');
define('kit_contact_array_type_phone', 'Telefoon');
define('kit_contact_array_type_protocol', 'Protocol Type');
define('kit_contact_array_type_type', 'Contact Type');
define('kit_contact_array_type_undefined', '- niet gedefinieerd -');

define('kit_contact_address_type_business', 'Dienst');
define('kit_contact_address_type_delivery', 'Bezorging');
define('kit_contact_address_type_post_office_box', 'Postbus');
define('kit_contact_address_type_private', 'Privé');
define('kit_contact_address_type_undefined', '');

define('kit_contact_protocol_type_call', 'Telefoongesprek');
define('kit_contact_protocol_type_email', 'E-Mail');
define('kit_contact_protocol_type_meeting', 'Meeting');
define('kit_contact_protocol_type_memo', 'Memo');
define('kit_contact_protocol_type_newsletter', 'nieuwsbrief');
define('kit_contact_protocol_type_undefined', '- open -');

define('kit_country_austria', 'Oostenrijk');
define('kit_country_germany', 'Duitsland');
define('kit_country_suisse', 'Zwitserland');
define('kit_country_undefined', '');

define('kit_desc_cfg_add_app_tab', 'Tabblad Extra toevoegen om eenvoudig naar andere Add-ons wechseln te kunnen wisselen. TAB\'s scheiden middels een comma, Aufbau AANWIJZEN|URL');
define('kit_desc_cfg_additional_fields', 'Sie können bis zu 5 zusätzliche Eingabefelder in KIT definieren, diese werden im Kontakt Dialog angezeigt und in kitForm zur Verfügung gestellt.<br/>Definieren Sie die Felder in der Form <NUMMER>|<BEZEICHNUNG> und trennen Sie die Felder mit einem Komma, z.B.: "1|Lieblingsfarbe,2|Haustier".');
define('kit_desc_cfg_additional_notes', 'Sie können 2 zusätzliche Notizfelder in KIT definieren, diese werden im Kontakt Dialog angezeigt und in kitForm zur Verfügung gestellt.<br />Definieren Sie die Felder in der Form <NUMMER>|<BEZEICHNUNG> und trennen Sie die Felder mit einem Komma, z.B.: "1|Letztes Telefonat,2|Diagnose".');
define('kit_desc_cfg_clear_compile_dir', 'Sie können die von der Dwoo Template Engine kompilierten Templates zurücksetzen und ein erneutes Schreiben erzwingen. Setzen Sie den Schalter dazu auf 1, KIT setzt den Schalter nach dem Reset automatisch zurück.');
define('kit_desc_cfg_connect_wb_users', 'U kunt KIT verbinden met het WB gebruikersbeheer. Als de verbinding tot stand is gekomen dan neemt KIT automatisch nieuw toegevoegde gebruikers over en blokkeert in het gebruikersbeheer van KIT, geblokkeerde of verwijderede gebruikers. Contacten, die in KIT de categorie <b>catWBUser</b> toekent krijgen, worden in de WB gebruikersbeheer gekoppeld aan de groep <b>kitContact</b>. U moet zelf de geblokkeerde gebruikers verwijderen. <i>Administratoren</i> kunnen uit veiligheid gronden niet met KIT verbonden worden.');
define('kit_desc_cfg_cronjob_key', 'Om te voorkomen dat cronjobs worden uitgevoerd door een eenvoudige oproep aan de cronjob.php moet de aangegeven sleutels doorgegeven worden als parameter. De oproep van het bestand is <b>cronjob.php?key=<i>SLEUTEL</i></b>.');
define('kit_desc_cfg_developer_mode', 'Mogelijkheid voor de programmeur om configuratieparameters toe te voegen.');
define('kit_desc_cfg_google_maps_api_key', 'Voor het gebruik en weergave van de kaarten die je nodig hebt een link <a href="http://code.google.com/intl/nl-NL/apis/maps/signup.html" target="_blank">Google Maps API Key</a>.');
define('kit_desc_cfg_kit_admins', '<b>KIT Administratoren</b> hebben uitgebreide rechten binnen KIT en kunnen systeemberichten ontvangen en bekijken. Geeft u hier het <b>primaire E-Mail Adressen</b> van de administrators door een comma gescheiden. De Administrators <b>moeten als contacten actief zijn in KIT</b>.');
define('kit_desc_cfg_kit_request_link', '<b>kit.php</b> accepteert alle aanvragen, retourneert data en geeft een dialoog weer. Het bestand bevindt zich in de directory /modules/kit, maar het kan ook worden gekopieerd naar een andere locatie, bijvoorbeeld in de root directory');
define('kit_desc_cfg_kit_response_page', 'Kit die nodig is voor de weergave van de dialoog en verwijst naar een aparte pagina');
define('kit_desc_cfg_limit_contact_list', 'Bepalen hoeveel items in de lijst met contacten moet worden weergegeven per pagina.');
define('kit_desc_cfg_max_invalid_login', 'Maximum aantal mislukte login pogingen van gebruikers voordat de account wordt geblokkeerd.');
define('kit_desc_cfg_min_pwd_len', 'Minimale lengte van de gebruikte wachtwoorden');
define('kit_desc_cfg_nl_adjust_register', 'Rechts op de oproep van de nieuwsbrief, de dialoog tafel met een kit van kit_contact register (Gebruik deze instelling alleen op verzoek van de ondersteuning! 0=UIT, 1=AAN).');
define('kit_desc_cfg_nl_max_package_size', 'Stelt het maximum aantal ontvangers per pakket tijdens het versturen van de nieuwsbrieven vast. De individuele pakketten worden verwerkt door een cron job, de maximaal toegestane waarde is 100.');
define('kit_desc_cfg_nl_set_time_limit', 'Stelt de maximale duur in seconden dat het script nieuwsbrief nodig heeft voor het versturen de e-mails. Als de waarde te laag is, krijg je een runtime error, verhoog de waarde geleidelijk aan indien nodig (standaard = 60).');
define('kit_desc_cfg_nl_salutation', 'U kunt 10 verschillende begroetingen gebruiken die u kunt gebruiken in de nieuwsbrief met <b> salutation_01 {$} </ b> tot <b> salutation_10 {$} </ b>. De groeten bestaan ​​uit drie definities: <b> man </ b> <b>, vrouw </ b> en <b> neutraal </ b>. De definities zijn van elkaar gescheiden door een pipe symbool. U kunt gebruik maken van de definities binnen in de KIT CMDS.');
define('kit_desc_cfg_nl_simulate', 'Doorloopt het hele proces van verzending van de nieuwsbrief <b> zonder </ b> daadwerkelijk het versturen van de mails (0 = UIT, 1 = AAN).');
define('kit_desc_cfg_register_data_dlg', 'Dialoog om de data van de bezoekers te beheren');
define('kit_desc_cfg_register_dlg', 'Dialoogvenster dat wordt aangeroepen wanneer gebruikers zich willen registreren of bij het bestellen van een nieuwsbrief');
define('kit_desc_cfg_register_dlg_unsubscribe', 'Dialoogvenster dat wordt aangeroepen wanneer een abonnement zich wil uitschrijven voor een nieuwsbrief of meerdere nieuwsbrieven');
define('kit_desc_cfg_session_id', 'ID voor de unieke identificatie van de sessie variabelen die worden gebruikt door KeepInTouch.');
define('kit_desc_cfg_sort_contact_list', 'Voorkeur voor het sorteren van de lijst met contactpersonen: 0 = ongesorteerd, 1 = E-mail ... 3 = achternaam - de mogelijke cijfers worden weergegeven in de keuzelijst voor het sorteren.');
define('kit_desc_cfg_use_captcha', 'Beslis of de diaglogen gebruik maken van de Frontend CAPTCHA spam bescherming ');
define('kit_desc_cfg_use_custom_files', 'Indien ingesteld, kunt u gebruik maken van op maat gemaakte templates en taal bestanden. De bestanden beginnen met "custom", bijvoorbeeld "Custom.NL.php", deze bestanden worden niet overschreven tijdens een update.');

define('kit_error_blank_title', '<p>De pagina moet een titel hebben!</p>');
define('kit_error_cfg_id', '<p>De configuratie opgenomen met het ID <b>% 05D </ b> kon niet worden gelezen!</p>');
define('kit_error_cfg_name', '<p>Als u de aanduiding <b>% s </ b> heeft zijn geen configuratie gegevens gevonden!</p>');
define('kit_error_create_dir', '<p>De map <br />% s </ b> <br /> kon niet worden aangemaakt!');
define('kit_error_create_file', '<p>Die Datei <b>%s</b> konnte nicht angelegt werden.</p>');
define('kit_error_delete_access_file', '<p>De toegang <b>% s </ b> kon niet worden verwijderd!</p>');
define('kit_error_dlg_missing', '<p>De gevraagde dialoog <b>% s </ b> is niet gevonden!</p>');
define('kit_error_email_missing', '<p>Er was geen e-mail adres doorgegeven!</p>');
define('kit_error_get_csv', '<p>Fouten bij het lezen van het CSV-bestand!</p>');
define('kit_error_google_maps_api_key_missing', '<p>Kaart kan niet worden weergegeven, er is geen Google Maps API-sleutel gedefinieerd!</p>');
define('kit_error_import_massmail_grp_missing', '<p>De tabel massmail is niet gevonden! Installleer de module WB massmail</p>');
define('kit_error_import_massmail_missing_vars', '<p>Niet alle vereiste variabelen zijn geimporteerd vanuit MassMail.</p>');
define('kit_error_invalid_id', '<p>Er wordt geen geldige ID doorgegeven!</p>');
define('kit_error_item_id', '<p>Het record met het ID <b>% s </ b> is niet gevonden!</p>');
define('kit_error_lepton_user_connection_inactive', '<p>Die Verbindung von KeepInTouch (KIT) zur LEPTON Benutzerverwaltung ist nicht aktiv. Bitte prüfen Sie die KIT Einstellungen und aktivieren Sie den Schalter "Mit LEPTON Benutzern verbinden".</p>');
define('kit_error_mail_init_settings', '<p>De WebsiteBaker configuratie-instellingen voor de e-mail kunnen niet worden geladen.</p>');
define('kit_error_map_address_invalid', '<p>Het adres <b>% s </ b> is niet gevonden!</p>');
define('kit_error_move_csv_file', '<p>Het tijdelijke bestand <b>% s </ b> kon niet worden verplaatst naar de doelmap!</p>');
define('kit_error_newsletter_tpl_id_invalid', '<p>De nieuwsbrief sjabloon met de ID <b>% 03D </ b> is niet gevonden!</p>');
define('kit_error_no_provider_defined', '<p>U heeft nog geen dienst gedefinieerd. Leg deze eerst via instellingen en service vast.</p>');
define('kit_error_insufficient_permissions', '<p>Je hebt geen toestemming om deze pagina te wijzigen!</p>');
define('kit_error_open_file', '<p>Je hebt geen toestemming om deze pagina te wijzigen!</p>');
define('kit_error_page_exists', '<p>De pagina met de basis-aanduiding <b>% s </ b> bestaat al!</p>');
define('kit_error_page_not_found', '<p>De pagina met de page_id <b>% d </ b> is niet gevonden!</p>');
define('kit_error_please_update',	'<p>Bitte aktualisieren Sie <b>%s</b>! Installiert ist die Version <b>%s</b>, benoetigt wird die Version <b>%s</b> oder hoeher!</p>');
define('kit_error_preview_id_invalid', '<p>De preview van de <b> ID% 05D </ b> is niet gevonden!');
define('kit_error_preview_id_missing', '<p>Er wordt geen preview ID gegeven!</p>');
define('kit_error_record_for_email_exists', '<p>Er bestaat al een record met de ID <b>% 03D </ b> voor het e-mailadres <b>% s </ b>, update dit record in plaats van het creeren van een nieuw record!</p>');
define('kit_error_register_email_already_exists', '<p>Fatal error: Er is al een registratie record voor de e-mail adres <b>%s</b>.</p>');
define('kit_error_register_contact_id_invalid', '<p>Die KIT ID <b>%s</b> wurde in der Registrierung nicht gefunden - die ID existiert nicht oder ist gesperrt.</p>');
define('kit_error_request_dlg_invalid_id', '<p>[kitRequest] De dialoog met de <b> ID% 03D </b> is niet gevonden, de werking wordt afgebroken!</p>');
define('kit_error_request_dlg_invalid_name', '<p>[kitRequest] De dialoog met de class naam <b>%s</b> is niet gevonden, de werking wordt afgebroken!</p>');
define('kit_error_request_invalid_action', '<p>[kitRequest] De parameter <b>%s=%s</b> is ongeldig, bediening wordt afgebroken!</p>');
define('kit_error_request_link_action_unknown', '<p>[kitRequest] Voor de koppeling <b>%s</b> is geen actie vastgelegd!</p>');
define('kit_error_request_link_invalid', '<p>[kitRequest] Er wordt geen geldige link doorgeven!</p>');
define('kit_error_request_link_type', '<p>De link op <b>%s</b> kan door dit proces verwerkt worden.</p>');
define('kit_error_request_link_unknown', '<p>[kitRequest] De link "<b>%s</b>" is helaas niet gekoppeld!</p>');
define('kit_error_request_missing_parameter', '<p>[kitRequest] De parameter <b>% s </ b> is niet gespecificeerd, de werking wordt afgebroken!</p>');
define('kit_error_request_no_action', '<p>[kitRequest] De juiste parameters worden niet doorgegeven!</p><p><b>Referentie:</b> Deze foutmelding wordt weergegeven, zelfs als u probeert om een dialoog opnieuw te laden (<i>Reload bariere</i>).</p>');
define('kit_error_request_parameter_incomplete', '<p>[kitRequest] De gegeven parameters zijn niet compleet, de opdracht kan niet worden uitgevoerd.</p>');
define('kit_error_salutation_definition', '<p><b>FEHLER:</b> Bitte prüfen Sie die Grußformel <b>%s</b>, sie muss 3 Anreden enthalten: männlich, weiblich und neutral, die jeweils durch eine Pipe "|" getrennt werden.</p>');
define('kit_error_undefined', '<p>Een onbekende fout is opgetreden, informeer met support over de opgetreden fout.</p>');

define('kit_header_addresses', 'Adressen, Map');
define('kit_header_categories', 'Categorien');
define('kit_header_cfg', 'Instellingen');
define('kit_header_cfg_array', 'Lijst aanvullen en bewerken');
define('kit_header_cfg_description', 'Omschrijving');
define('kit_header_cfg_identifier', 'Identifier');
define('kit_header_cfg_import', 'Gegevens importeren');
define('kit_header_cfg_label', 'Label');
define('kit_header_cfg_typ', 'Type');
define('kit_header_cfg_value', 'Waarde');
define('kit_header_communication', 'Communicatie');
define('kit_header_contact', 'Contact');
define('kit_header_contact_list', 'Contactlijst');
define('kit_header_email', 'E-Mail versturen');
define('kit_header_error', 'KeepInTouch (KIT) foutmelding');
define('kit_header_help_documentation', 'Help & Dokumentatie');
define('kit_header_import_step_1', 'Contactgegevens importeren');
define('kit_header_import_step_2', 'Geïmporteerde velden toewijzen');
define('kit_header_nl_cronjob_protocol_list', 'Uitgevoerde Jobs');
define('kit_header_nl_cronjob_active_list', 'Nog niet uitgevoerde Jobs');
define('kit_header_preview', 'Voorbeeld');
define('kit_header_protocol', 'Protocol');
define('kit_header_provider', 'Provider');
define('kit_header_template', 'Bewerken van Sjablonen');

define('kit_help_import_step_1', 'Gebruik de online documentatie om meer te leren over het importeren van gegevens uit KIT <a href="http://phpmanufaktur.de/kit/help/import" target="_blank">');
define('kit_help_import_step_2', 'Gebruik de online documentatie om meer te leren over het importeren van gegevens uit KIT <a href="http://phpmanufaktur.de/kit/help/import" target="_blank">.');

define('kit_hint_error_msg', '<p>Als u deze foutmelding meerdere malen krijgt en vermoedt u dat dit een storing is, dan kunt u contact opnemen met de webmanager!</p>');

define('kit_imp_con_pers_title', 'Persoon: Titel (de heer, mevrouw)');
define('kit_imp_con_pers_title_academic', 'Persoon: Titel (Dr., Prof.)');
define('kit_imp_con_pers_first_name', 'Persoon: Voornaam');
define('kit_imp_con_pers_last_name', 'Persoon: Achternaam');
define('kit_imp_con_pers_function', 'Persoon: Functie, Activiteit');
define('kit_imp_con_pers_addr_street', 'Persoon: Straat');
define('kit_imp_con_pers_addr_zip', 'Persoon: Postcode');
define('kit_imp_con_pers_addr_city', 'Persoon: plaats');
define('kit_imp_con_pers_addr_country', 'Persoon: Land');
define('kit_imp_con_pers_email_1', 'Persoon: E-Mail 1');
define('kit_imp_con_pers_email_2', 'Persoon: E-Mail 2');
define('kit_imp_con_pers_phone', 'Persoon: Telefoon');
define('kit_imp_con_pers_handy', 'Persoon: Mobiel');
define('kit_imp_con_pers_fax', 'Person: Fax');

define('kit_imp_con_comp_name', 'Firma: Naam');
define('kit_imp_con_comp_department', 'Firma: Afdeling');
define('kit_imp_con_comp_additional', 'Firma: Extra adres');
define('kit_imp_con_comp_addr_street', 'Firma: Straat');
define('kit_imp_con_comp_addr_zip', 'Firma: Postcode');
define('kit_imp_con_comp_addr_city', 'Firma: plaats');
define('kit_imp_con_comp_addr_country', 'Firma: Land');
define('kit_imp_con_comp_email_1', 'Firma: E-Mail 1');
define('kit_imp_con_comp_email_2', 'Firma: E-Mail 2');
define('kit_imp_con_comp_phone', 'Firma: Telefoon');
define('kit_imp_con_comp_handy', 'Firma: Mobiel');
define('kit_imp_con_comp_fax', 'Firma: Fax');

define('kit_imp_con_www', 'Contakt: Internet');
define('kit_imp_no_selection', '- niet toegewezen -');

define('kit_info', '<a href="http://phpmanufaktur.de/kit" target="_blank">KeepInTouch (KIT)</a>, Release %s - Open Source CRM for WebsiteBaker - (c) 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>');

define('kit_intro_cfg', '<p>Bewerk de instellingen voor <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_add_item', '<p>Het toevoegen van inzendingen voor de configuratie is alleen zinvol als de waarden overeenkomen met het programma .</p>');
define('kit_intro_cfg_array', '<p>Bewerk de verschillende lijsten voor <b>dbKeepInTouch</b>.</p>');
define('kit_intro_cfg_import', '<p>Met dit dialoogvenster kunt u gegevens vanuit andere toepassingen in <b>KeepInTouch</b> importeren.</p>');
define('kit_intro_cfg_provider', '<p>Selecteer een serviceprovider om te bewerken of een nieuwe dienst te creëren.</p>');
define('kit_intro_contact', '<p>Gebruik dit dialoogvenster om de contactgegevens te bewerken</p>');
define('kit_intro_contact_list', '<p>Deze lijst toont de beschikbare contacten, afhankelijk van de gekozen volgorde.</p>');
define('kit_intro_cronjobs', '<p>KeepInTouch (KIT) is vereist voor het versturen van een nieuwsbrief die via een cron job op gezette tijden, bijvoorbeeld elke vijf minuten, het controle bestand <b>/modules/kit/cronjob.php</b> oproept.</p><p>Op deze manier wordt ervoor gezorgd dat een groot aantal van KIT nieuwsbrieven continu wordt verzonden in kleine verpakkingen. Door de gematigde verzending wordt voorkomen dat uw account wordt gezien als spam account.</p><p>Als het niet lukt om op uw webserver cronjobs uit te voeren, dan zou u gebruik kunnen maken van den gratis dienst om deze als controle voor de KIT te laten uitvoeren. Voorbeeld van een gratis dienst is bijvoorbeeld <a href="http://www.cronjob.de" target="_blank">cronjob.de</a>.</p></p>');
define('kit_intro_email', '<p>Met dit dialoogvenster kunt u e-mail maken en verzenden.</p>');
define('kit_intro_import_fields', '<p>Wijs de velden vanuit het CSV bestand toe aan de juiste velden in KIT.Ordnen Sie den Feldern aus der CSV Datei die entsprechenden Felder in KeepInTouch zu.</p><p>U hoeft niet alle velden te gebruiken of toe te wijzen.</p>');
define('kit_intro_import_start', '<p>U kan contactgegevens vanuit andere toepassingen als <i><b>C</b>omma-<b>S</b>eparated <b>V</b>alues</i> (CSV) Formaat in KeepInTouch importeren.</p><p>Deze dialoog leidt U in een aantal stappen door het importeerproces.</p>');
define('kit_intro_newsletter_cfg', '<p>Bewerk de bijzondere settings voor de nieuwsbrief in de module KeepInTouch.</p>');
define('kit_intro_newsletter_create', '<p>Maak een nieuwsbrief op om deze te sturen naar hun abonnees.</p>');
define('kit_intro_newsletter_commands', "<p>Commando's en variabelen kunnen worden uitgevoerd tijdens de uitvoering en worden ingevoegd in het sjabloon.</p><p>Een simpele klik is voldoende om de desbetreffende commando's in op de cursorpositie in de <b>HTML Code</b> in te voegen.</p>");
define('kit_intro_newsletter_template', '<p>Selecteer een nieuwsbrief om een template te bewerken of een nieuw sjabloon te creëren.</p>');
define('kit_intro_nl_cronjob_active_list', '<p>e lijst toont de huidige cron jobs die nog niet zijn uitgevoerd.</p>');
define('kit_intro_nl_cronjob_protocol_list', '<p>Deze lijst toont de laatste 200 uitgevoerde nieuwsbrief jobs, uitgevoerd door de KeepInTouch cronjob.</p>');
define('kit_intro_preview', '<p>Controleer het voorbeeld in <b>HTML</b> en in <b>platte tekst</b></p>');
define('kit_intro_register_installation', '<p>Registreer uw KeepInTouch Installatie.</p><p>Hiermee krijgt u de mogelijkheid om de volledige functionaliteit van KeepInTouch te testen.</p>');

define('kit_label_add_new_address', 'Voeg extra Adres toe');
define('kit_label_additional_fields', 'Benutzerdefinierte Felder');
define('kit_label_address_city', 'plaats');
define('kit_label_address_street', 'Straat');
define('kit_label_address_type', 'type Adres');
define('kit_label_address_type_private', 'Particulier');
define('kit_label_address_type_business', 'Zakelijk');
define('kit_label_address_zip', 'Postcode');
define('kit_label_address_zip_city', 'Postcode, plaats');
define('kit_label_admin', 'Administration');
define('kit_label_archive_id', 'Archief ID');
define('kit_label_audience', 'Ontvanger');
define('kit_label_birthday', 'Verjaardag');
define('kit_label_categories', 'Intern'); // anstatt Kategorie
define('kit_label_cfg_add_app_tab', 'Extra TAB\'s toevoegen');
define('kit_label_cfg_additional_fields', 'Zusätzliche KIT Felder');
define('kit_label_cfg_additional_notes', 'Zusätzliche KIT Notizen');
define('kit_label_cfg_array_add_items', 'Voeg meer inzendingen toe:');
define('kit_label_cfg_clear_compile_dir', 'Templates zurücksetzen');
define('kit_label_cfg_connect_wb_users', 'Met WB gebruikers verbinden');
define('kit_label_cfg_cronjob_key', 'Sleutel voor de Cronjobs');
define('kit_label_cfg_developer_mode', 'Developer Mode');
define('kit_label_cfg_google_maps_api_key', 'Google Maps API Key');
define('kit_label_cfg_kit_admins', 'KIT Administratoren');
define('kit_label_cfg_kit_request_link', 'KIT Request Link');
define('kit_label_cfg_kit_reponse_page', 'KIT antwoord website');
define('kit_label_cfg_limit_contact_list', 'max. vermeldingen in contactlijst');
define('kit_label_cfg_max_invalid_login', 'Maximale Login pogingen');
define('kit_label_cfg_min_pwd_len', 'Min. lengte wachtwoord');
define('kit_label_cfg_nl_adjust_register', 'kit_register syndicaat');
define('kit_label_cfg_nl_max_package_size', 'Max. Paketgroote');
define('kit_label_cfg_nl_salutation', 'Aanhef');
define('kit_label_cfg_nl_set_time_limit', 'Max. tijd limiet');
define('kit_label_cfg_nl_simulate', 'Verzending simuleren');
define('kit_label_cfg_register_data_dlg', 'Gebruikers data beheren');
define('kit_label_cfg_register_dlg', 'Gebruikers, Registratie');
define('kit_label_cfg_register_dlg_unsubscribe', 'Gebruiker, Nieuwsbrief afmelding');
define('kit_label_cfg_session_id', 'Sessie ID');
define('kit_label_cfg_sort_contact_list', 'Contact lijst sorteren');
define('kit_label_cfg_temp_dir', 'Tijdelijke directory');
define('kit_label_cfg_use_captcha', 'CAPTCHA gebruiken');
define('kit_label_cfg_use_custom_files', 'Aangepaste gegevensbestanden gebruiken');
define('kit_label_checksum', 'Checksum');
define('kit_label_contact_access', 'Toegang Contact');
define('kit_label_contact_edit', 'Contact bewerken');
define('kit_label_contact_email', 'E-Mail');
define('kit_label_contact_email_retype', 'E-Mail wiederholen');
define('kit_label_contact_fax', 'Fax');
define('kit_label_contact_phone', 'Telefoon');
define('kit_label_contact_phone_mobile', 'Mobiel');
define('kit_label_contact_since', 'Contact sinds');
define('kit_label_contact_note', 'Aanmerkingen');
define('kit_label_company_additional', 'Toevoeging');
define('kit_label_company_department', 'Afdeling');
define('kit_label_contact_status', 'Contact Status');
define('kit_label_contact_identifier', 'Contact ID');
define('kit_label_company_name', 'Bedrijf');
define('kit_label_company_title', 'Titel');
define('kit_label_contact_type', 'Contact Type');
define('kit_label_country', 'Land');
define('kit_label_csv_export', 'CSV Export');
define('kit_label_csv_import', 'CSV Import');
define('kit_label_distribution', 'Verdeler');
define('kit_label_enable_relaying', 'Use Relaying');
define('kit_label_free_field_1', 'Freies Datenfeld 1');
define('kit_label_free_field_2', 'Freies Datenfeld 2');
define('kit_label_free_field_3', 'Freies Datenfeld 3');
define('kit_label_free_field_4', 'Freies Datenfeld 4');
define('kit_label_free_field_5', 'Freies Datenfeld 5');
define('kit_label_free_note_1', 'Freies Textfeld 1');
define('kit_label_free_note_2', 'Freies Textfeld 2');
define('kit_label_html_format', 'HTML Format');
define('kit_label_id', 'ID');
define('kit_label_identifier', 'ID');
define('kit_label_image', 'Afbeelding');
define('kit_label_import_action', '');
define('kit_label_import_charset', 'Character set');
define('kit_label_import_csv_file', 'CSV bestand');
define('kit_label_import_from', 'Import');
define('kit_label_import_separator', 'Separator');
define('kit_label_job_id', 'Job ID');
define('kit_label_job_created', 'gecreërd');
define('kit_label_job_process', 'Proces');
define('kit_label_job_count', 'E-Mails count');
define('kit_label_job_done', 'Uitgevoerd');
define('kit_label_job_time', 'Duur (Sec.)');
define('kit_label_job_send', 'E-Mails verzonden');
define('kit_label_kit_id', 'KIT ID');
define('kit_label_last_changed_by', 'Gewijzigd door');
define('kit_label_list_sort', 'Lijst sorteren op');
define('kit_label_mail_bcc', '<i>BCC</i> <b>Ontvanger</b>');
define('kit_label_mail_from', 'Afzender');
define('kit_label_mail_subject', 'Onderwerp');
define('kit_label_mail_text', 'Bericht');
define('kit_label_mail_to', 'Ontvanger');
define('kit_label_map', '&nbsp;');
define('kit_label_massmail', 'MassMail');
define('kit_label_newsletter', 'Nieuwsbrief');
define('kit_label_newsletter_archive_select', 'Uit archief laden');
define('kit_label_newsletter_commands', 'Commando\'s & variabelen');
define('kit_label_newsletter_tpl_desc', 'Omschrijving');
define('kit_label_newsletter_tpl_html', 'HTML Code');
define('kit_label_newsletter_tpl_name', 'Benaming');
define('kit_label_newsletter_tpl_select', 'Template');
define('kit_label_newsletter_tpl_text', 'ALLEEN TEKST');
define('kit_label_newsletter_tpl_text_preview', '<b>NUR TEXT</b><br /><span style="font-size:smaller;">De woorden van lange zinnen worden niet automatisch op de volgende regel geplaatst (word wrap)!</span>');
define('kit_label_password', 'Wachtwoord');
define('kit_label_password_new', 'Nieuw wachtwoord');
define('kit_label_password_retype', 'Nogmaals nieuw wachtwoord');
define('kit_label_person_title', 'Aanhef');
define('kit_label_person_title_academic', 'Titel');
define('kit_label_person_first_name', 'Voornaam');
define('kit_label_person_last_name', 'Achternaam');
define('kit_label_person_function', 'Funktie/Positie');
define('kit_label_protocol', 'Protocol');
define('kit_label_protocol_date', 'Datum');
define('kit_label_protocol_members', 'Deelnemer');
define('kit_label_protocol_memo', 'Toegang');
define('kit_label_protocol_type', 'Contact');
define('kit_label_provider', 'Provider');
define('kit_label_provider_email', 'E-Mail van Provider');
define('kit_label_provider_identifier', 'provider identifier');
define('kit_label_provider_name', 'Naam Provider');
define('kit_label_provider_remark', 'Aanmerkingen');
define('kit_label_provider_response', 'Antwoordadresse(n)');
define('kit_label_provider_select', 'Selecteer een dienst');
define('kit_label_provider_smtp_auth', 'SMTP Authenticatie');
define('kit_label_provider_smtp_user', 'SMTP gebruiker');
define('kit_label_provider_smtp_host', 'SMTP Hostnaam');
define('kit_label_register_confirmed', 'Registrierung, Bestätigung');
define('kit_label_register_date', 'Registrierung, Datum');
define('kit_label_register_key', 'Registrierung, Schlüssel');
define('kit_label_register_login_errors', 'Login Fehler');
define('kit_label_register_login_locked', 'Login gesperrt');
define('kit_label_register_password_1', 'Neues Passwort');
define('kit_label_register_password_2', 'Passwort wiederholen');
define('kit_label_register_status', 'Registrierung, Status');
define('kit_label_standard', 'Standaard');
define('kit_label_status', 'Status');
define('kit_label_subscribe', 'Aanmelden');
define('kit_label_type', 'Type');
define('kit_label_unsubscribe', 'Afmelden');
define('kit_label_value', 'Waarde');

define('kit_list_sort_city', 'plaats');
define('kit_list_sort_company', 'Bedrijf');
define('kit_list_sort_deleted', 'geschrapte ingangen');
define('kit_list_sort_email', 'E-Mail');
define('kit_list_sort_firstname', 'Voornaam');
define('kit_list_sort_lastname', 'Achternaam');
define('kit_list_sort_locked', 'Gesloten entries');
define('kit_list_sort_phone', 'Telefoonnummer');
define('kit_list_sort_street', 'Straat');
define('kit_list_sort_unsorted', '- ongesorteerd -');

define('kit_msg_activation_key_invalid', '<p>De opgegeven activatie code is niet geldig!</p>');
define('kit_msg_activation_key_used', '<p>De activatie code is al gebruikt en is niet geldig .</p>');
define('kit_msg_account_locked', '<p>Dit account is geblokkeerd. Neem contact op met uw Provider.</p>');
define('kit_msg_address_deleted', '<p>Het adres met contact <b>ID %05d</b> wordt verwijderd.</p>');
define('kit_msg_address_insert', '<p>Aan het contact wordt een nieuw adres toegevoegd.</p>');
define('kit_msg_address_invalid', '<p>Het adres kan niet worden toegevoegd, de gegevens zijn niet compleet.<br />Geeft u aub <i>Straat, Postcode en Postcode en plaats</i> of <i>Straat en Plaats</i> of alleen <i>Plaats</i> op.</p>');
define('kit_msg_address_update', '<p>Het adres van Contact <b>ID %05d</b> wordt bijgewerkt.</p>');
define('kit_msg_categories_changed', '<p>De Categoriën worden veranderd.</p>');
define('kit_msg_cfg_add_exists', '<p>Het configuratiebestand met de naam <b>%s</b> bestaat al en kan niet opnieuw worden toegevoegd!</p>');
define('kit_msg_cfg_add_incomplete', '<p>HEt nieuwe configuratiebestand is niet volledig! Controleer aub uw gegevens!</p>');
define('kit_msg_cfg_add_success', '<p>Het configuratiebestand met <b>ID #%05d</b> en de identifier <b>%s</b> wordt toegevoegd.</p>');
define('kit_msg_cfg_array_item_updated', '<p>Het item met <b>ID %05d</b> wordt bijgewerkt.</p>');
define('kit_msg_cfg_array_item_add', '<p>Het item met <b>ID %05d</b> wordt toegevoegd.</p>');
define('kit_msg_cfg_array_identifier_in_use', '<p>Identificatiecode <b>%s</b> wordt al gebruikt door het <b>ID %05d</b> en kan niet worden toegevoegd. Geef een ander id op.</p>');
define('kit_msg_cfg_clear_compile_dir', '<p>Es wurden insgesamt <b>%s</b> kompilierte Templates zurückgesetzt.</p>');
define('kit_msg_cfg_csv_export', '<p>Het configuratiebestand wordt opgeslagen als de <b>%s</b> in de  /MEDIA directory. </p>');
define('kit_msg_cfg_id_updated', '<p>Het configuratiebestand met <b>ID #%05d</b> en identifier <b>%s</b> wordt bijgwerkt.</p>');
define('kit_msg_contact_deleted', '<p>Contact met <b>ID %05d</b> wordt verwijderd.</p>');
define('kit_msg_contact_insert', '<p>Contact met <b>ID %05d</b> is succesvol aangemaakt en opgeslagen.</p>');
define('kit_msg_contact_minimum_failed', '<p>Der <b>Gegevensbestand kan niet worden opgeslagen</b>. Er is niet voldaan aan de minimale eisen.<br />Geeft u AUB <i>minstens</i> ofwel een E-Mail Adres <i>of</i> een bedrijfsnaam en een plaatst <i>of</i> een bedrijfsnaam en een telefoonnummer <i>of</i> een achternaam en een plaats <i>of</i> een achternaam en telefoonnummer op.</p>');
define('kit_msg_contact_update', '<p>Het contact met <b>ID %05d</b> wordt bijgewerkt</p>');
define('kit_msg_cronjob_last_call', '<p>Er zijn momenteel geen taken.</p><p>De cron controle bestand is voor het laatst geraadpleegd op <b>%s</b>.</p>');
define('kit_msg_csv_file_moved', '<p>Het CSV bestand <b>%s</b> wordt in de importlijst als <b>%s</b> opgeslagen.');
define('kit_msg_csv_first_row_empty', '<p>Het eerste gedeelte van het CVS bestand <b>%s</b> is leeg. Er worden headers verwacht.</p>');
define('kit_msg_csv_no_cols', '<p>Het CSV bestand <b>%s</b> bevat geen kolommen! Controleer het CSV bestand.</p>');
define('kit_msg_csv_no_file_transmitted', '<p>Er is geen CSV bestand overgedragen.</p>');
define('kit_msg_email_added', '<p>Het E-Mail Adres <b>%s</b> wordt toegevoegd.</p>');
define('kit_msg_email_changed', '<p>Het E-Mail Adres <i>%s</i> wordt in <b>%s</b> veranderd.</p>');
define('kit_msg_email_deleted', '<p>Het E-Mail Adres <b>%s</b> wordt verwijderd.</p>');
define('kit_msg_email_invalid', '<p>HEt E-Mail Adres <b>%s</b> is niet geldig, controleer u ingave.</p>');
define('kit_msg_email_type_changed', '<p>Het type voor het E-Mail Adres <b>%s</b> is gewijzigd.</p>');
define('kit_msg_internet_added', '<p>Het internetadres <b>%s</b> wordt toegevoegd.</p>');
define('kit_msg_internet_changed', '<p>Het internetadres <i>%s</i> wordt in <b>%s</b> veranderd.</p>');
define('kit_msg_internet_deleted', '<p>Het Internetadres <b>%s</b> wordt verwijderd.</p>');
define('kit_msg_internet_invalid', '<p>Het Internetadres <b>%s</b> is niet geldig, controleer uw ingave.</p>');
define('kit_msg_internet_type_changed', '<p>Het type van het Internetadres <b>%s</b> wordt veranderd.</p>');
define('kit_msg_invalid_email', '<p>Het E-Mail Adres <b>%s</b> is niet geldig, controleer uw ingave.</p>');
define('kit_msg_invalid_id', '<p>Er wordt geen geldige ID gegeven!</p>');
define('kit_msg_login_locked', '<p>Het account voor gebruiker <b>%s</b> heeft te veel mislukte inlog pogingen uitgevoerd, account is vergrendeld. Neem contact op met de Webmaster.</p>');
define('kit_msg_login_password_invalid', '<p>Wachtwoord klopt niet.</p>');
define('kit_msg_login_status_fail', '<p>Het account voor gebruiker <b>%s</b> is niet actief. Neem contact op met de Webmaster.</p>');
define('kit_msg_login_user_unknown', '<p>De gebruiker <b>%s</b> is niet bekend of het account is voor deze applicatie niet geactiveerd.</p>');
define('kit_msg_mail_incomplete', '<p>De gegevens zijn onvolledig: E-Mail afzender, E-Mail ontvanger, selecteer een categorie, Onderwerp en emailtekst moeten ingesteld zijn.</p>');
define('kit_msg_mail_send_error', '<p>De email kan niet verstuurd worden er zijn totaal <b>%d Fouten</b> opgetreden, de Foutmelding is:<br /><b>%s</b></p>');
define('kit_msg_mail_send_success', '<p>De E-Mail is succesvol verstuurd.</p>');
define('kit_msg_mails_send_success', '<p>Er zijn <b>%d</b> E-Mails succesvol verstuurd.</p>');
define('kit_msg_mails_send_errors', '<p>Er treden totaal <b>%d</b> fouten met de volgende meldingen op:</p>%s');
define('kit_msg_massmail_not_installed', '<p>MassMail is niet geinstalleerd.</p>');
define('kit_msg_massmail_group_no_data', '<p>De MassMail Groep met <b>ID %d</b> bevat geen data.</p>');
define('kit_msg_massmail_email_skipped', '<p>De volgende e-mail adressen worden al gebruikt in de opgegeven KIT records en worden daarom <b>genegeerd</b>: %s</p>');
define('kit_msg_massmail_no_emails_imported', '<p><b>Er zijn geen E-Mail Adressen overgenomen!</b></p>');
define('kit_msg_massmail_emails_imported', '<p>Er zijn in totaal <b>%d E-Mail Adressen</b> als onafhankelijke datasets overgenomen: %s</p>');
define('kit_msg_newsletter_account_not_activated', '<p>Voor het e-mail adres <b>%s</b> bestaat al een account die niet is geactiveerd. Gelieve eerst in staat stellen voor dit account!</p>');
define('kit_msg_newsletter_account_locked', '<p>Het account met het E-Mail Adres <b>%s</b> is geblokkeerd, neem contact op met uw provider.</p>');
define('kit_msg_newsletter_adjust_register', '<p>HEt commando <b>cfgAdjustRegister</b> wordt voor <b>%s</b> uitgevoerd!</p>');
define('kit_msg_newsletter_new_no_groups', '<p>Selecteer meerdere <b>Nieuwsbrief Groepen</b> of <b>Distributie Groepen</b>!</p>');
define('kit_msg_newsletter_new_no_html', '<p>Vermeld de nieuwsbrief tekst in <b>HTML-formaat!<b/></p>');
define('kit_msg_newsletter_new_no_provider', '<p>Selecteer een service provider voor het versturen van de nieuwsbrief!</p>');
define('kit_msg_newsletter_new_no_subject', '<p>Vul een onderwerp in voor de nieuwsbrief!</p>');
define('kit_msg_newsletter_new_no_template', '<p>Selecteer een <b>Sjabloon</b> voor het verzenden van de nieuwsbrief!</p>');
define('kit_msg_newsletter_new_no_text', '<p>De <b>ALLEEN TEKST</b> format is automatisch gegenereerd, controleer de uitvoer!</p>');
define('kit_msg_newsletter_new_packages_created', '<p>Er worden in totaal <b>%d Pakketten</b> klaargemaakt voor verzending.</p>');
define('kit_msg_newsletter_no_abonnement', '<p>Er is geen Niewwsletter voor E-Mail Adres <b>%s</b> gekoppeld!</p>');
define('kit_msg_newsletter_simulate_mailing', '<p><b>SIMULATIONSMODUS AKTIEF</b> - er zijn geen nieuwsbriefs verstuurd!</p>');
define('kit_msg_newsletter_tpl_added', '<p>De sjabloon <b>%s</b> wordt toegevoegd.</p>');
define('kit_msg_newsletter_tpl_changed', '<p>De sjabloon <b>%s</b> wordt veranderd.</p>');
define('kit_msg_newsletter_tpl_cmd_content', '<p>Gegevens niet opgeslagen, de Sjabloon moet teminste placeholder <b>%s</b> bevatten. Op deze plek wordt de tekst van de nieuwbrief toegevoegd.</p>');
define('kit_msg_newsletter_tpl_minimum_failed', '<p>Gegevens niet opgeslagen, U moet minstens een <b>Indentifier</b> en <b>HTML Code</b> opgeven!</p>');
define('kit_msg_newsletter_tpl_missing', '<p><b>U heeft nog geen sjabloon gecreerd voor het versturen van de nieuwsbrieven.</b></p><p>Maakt u eerst een sjabloon voordat u een nieuwsbrief samensteld.</p>');
define('kit_msg_newsletter_tpl_text_inserted', '<p>De <b>ALLEEN TEKST</b> format voor de sjabloon is automatisch gegenereerd, controleer de uitvoer.</p>');
define('kit_msg_newsletter_tpl_unchanged', '<p>De sjabloon met <b>ID %05d</b> wordt niet veranderd.</p>');
define('kit_msg_newsletter_user_not_registered', '<p>Het E-Mail Adres <b>%s</b> staat niet in de distributielijst!</p>');
define('kit_msg_password_changed', '<p>Het wachtwoord is succesvol gewijzigd!</p>');
define('kit_msg_password_needed', '<p>Geef uw wachtwoord op!</p>');
define('kit_msg_passwords_mismatch', '<p>De opgegevens wachtwoorden komen niet overeen!</p>');
define('kit_msg_password_too_short', '<p>Het wachtwoord is te kort! De minimale lengte is <b>%d</b>.</p>');
define('kit_msg_phone_added', '<p>Het Telefoonnummer <b>%s</b> wordt toegevoegd.</p>');
define('kit_msg_phone_changed', '<p>Het Telefoonnummer <i>%s</i> wordt veranderd in <b>%s</b>.</p>');
define('kit_msg_phone_deleted', '<p>Het Telefonnummer <b>%s</b> wordt verwijderd.</p>');
define('kit_msg_phone_invalid', '<p>Het Telefoonnummer <b>%s</b> is niet geldig, controleer uw ingave. Wij accepteren alleen telefoonnummers in het internationale formaat +31 (30) 1234567.</p>');
define('kit_msg_phone_type_changed', '<p>Het Type voor het Telefoonnummer <b>%s</b> wordt gewijzigd.</p>');
define('kit_msg_protocol_updated', '<p>HEt Protocol wordt geactualisseerd.</p>');
define('kit_msg_provider_check_auth', '<p>U heeft geen SMTP Authenticatie opgegeven maar alleen een SMTP Host en Naam, controleer uw ingave!</p>');
define('kit_msg_provider_id_invalid', '<p>Es existiert kein aktiver Datensatz für einen Provider mit der <b>ID %05d</b>!');
define('kit_msg_provider_inserted', '<p>De Dienstverlener <b>%s</b> wordt nieuw aangemaakt.</p>');
define('kit_msg_provider_minum_failed', '<p>De ingegeven informatie over de dienstverlener is niet voldoende. U moet in ieder geval het volgende opgeven: Naam en  E-Mail Adres en - als uw e-mail server een SMTP-verificatie vereist - SMTP Host, SMTP gebruikersnaam en het wachtwoord.</p>');
define('kit_msg_provider_missing', '<p>Es ist kein aktiver Dienstleister vorhanden, definieren Sie in KIT --> Einstellungen --> Dienstleister bitte einen Dienstleister!</p>');
define('kit_msg_provider_updated', '<p>De Dienstverlener <b>%s</b> wordt geactualisseerd.</p>');
define('kit_msg_register_status_updated', '<p>De Status voor <b>dbKITregister</b> wordt geactualisseerd.</p>');
define('kit_msg_service_invalid_user_name', '<p>Vul een geldige voor-en achternaam in.</p>');
define('kit_msg_service_license_beta_evaluate', '<p><b>Deze KeepInTouch Installatie is niet geregistreerd.</b></p><p><a href="%s">Registreer deze BETA Versie</a> U krijgt dan ​​volledige ondersteuning voor KeepInTouch!</p>');
define('kit_msg_service_license_beta_registered', '<p<b>KeepInTouch BETA</b><br /><i>%s</i><br />Produktondersteuningung naar %s, registreer voor <i>%s %s</i>.</p>');
define('kit_msg_service_no_connect', '<p>De Updateserver kan niet bereikt worden.</p>');

define('kit_protocol_create_contact', '%s: Gegevensbestand aangemaakt.');
define('kit_protocol_create_contact_massmail', 'Gegevensbestand door Import van E-Mail Adres %s van Massmail aangemaakt.');
define('kit_protocol_import_wb_user', 'WB gebruiker <b>%s</b> in KeepInTouch overgenomen.');
define('kit_protocol_ki_account_activated', 'Het account wordt bevestigd en geactiveerd');
define('kit_protocol_ki_address_added', '[kitInterface] Adres %s, %s %s wordt toegevoegd.');
define('kit_protocol_ki_address_updated', '[kitInterface] Adres %s, %s %s wordt geactualisseerd.');
define('kit_protocol_ki_contact_created', '[kitInterface] Gegevensbestand aangemaakt.');
define('kit_protocol_ki_contact_updated', '[kitInterface] De Contactgegevens worden geactualisseerd.');
define('kit_protocol_ki_added_as_lepton_user', '[kitInterface] Der Kontakt wurde der LEPTON Benutzer hinzugefügt.');
define('kit_protocol_ki_lepton_user_group_added', '[kitInterface] Dem LEPTON Benutzer wurde die Gruppe %s hinzugefügt.');
define('kit_protocol_ki_newsletter_updated', '[kitInterface] Het nieuwsbrief Abonnement wordt geactualisseerd.');
define('kit_protocol_ki_password_changed', '[kitInterface] HEt wachtwoord wordt veranderd.');
define('kit_protocol_login_locked', 'ACCOUNT GEBLOKKEERD, de gebruiker heeft teveel foutieve login pogingen uitgevoerd.');
define('kit_protocol_send_newsletter_success', 'nieuwsbrief <i>"%s"</i> om <b>%s</b> uur op <b>%s</b> verstuurd.');
define('kit_protocol_send_newsletter_fail', 'nieuwsbrief <i>"%s"</i> kan om <b>%s</b> uur <b>nicht</b> op <b>%s</b> verstuurd worden.<br><b>Fout:</b> %s.');
define('kit_protocol_simulate_send_newsletter', '<p>SIMULATION: De nieuwsbrief wordt op <b>%s</b> verstuurd!</p>');

define('kit_start_list', '<h2>Alle Contacten</h2><p>Alle Contacten, die u beheert met KeepInTouch staan in het overzicht.</p>');
define('kit_start_contact', '<h2>Contact bewerken</h2><p>Maak nieuwe contacten aan of bewerk de contacten die u hebt geselecteerd uit het overzicht.</p>');
define('kit_start_email', '<h2>E-Mail Groepen</h2><p>Verstuur snel en simpel E-Mail berichten en contacten, die u georganiseerd heeft in groepen.</p>');
define('kit_start_newsletter', '<h2>nieuwsbrief versturen</h2><p>Opstellen en versturen van gepersonaliseerde nieuwsbriefs met KeepInTouch.</p>');
define('kit_start_config', '<h2>Instellingen</h2><p>Algemene Instellingen, Lijsten aanpassen, Het beheer van service providers alsmede gegevens importeren en exporteren.</p>');
define('kit_start_help', '<h2>Help & Documentatie</h2><p>Help en Documentatie voor KeepInTouch.</p>');

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

define('kit_tab_cfg_array', 'Lijsten aanpassen');
define('kit_tab_cfg_export', 'Export');
define('kit_tab_cfg_general', 'Algemeen');
define('kit_tab_cfg_import', 'Import');
define('kit_tab_cfg_provider', 'Dienstverlener');
define('kit_tab_config', 'Instellingen');
define('kit_tab_contact', 'Contact bewerken');
define('kit_tab_cronjobs_active', 'Huidige opdrachten');
define('kit_tab_cronjobs_protocol', 'Protocol');
define('kit_tab_email', 'Email Groepen');
define('kit_tab_help', '?');
define('kit_tab_list', 'Alle Contacten');
define('kit_tab_newsletter', 'nieuwsbrief versturen');
define('kit_tab_nl_create', 'nieuwsbrief opstellen');
define('kit_tab_nl_template', 'Sjablonen beheren');
define('kit_tab_start', 'Start');

define('kit_text_as_email_type', 'als E-Mail Type:');
define('kit_text_calendar_delete', 'Datum verwijderen');
define('kit_text_calendar_select', 'Datum kiezen');
define('kit_text_colon_separated', 'gescheiden door een dubbele punt');
define('kit_text_comma_separated', 'gescheiden door een komma');
define('kit_text_from_massmail_group', 'Groep importeren:');
define('kit_text_new_id', '- nieuw -');
define('kit_text_pipe_separated', 'met | (Pipe) gescheiden');
define('kit_text_please_select', '- maak een keuze -');
define('kit_text_process_execute', '<b>uitvoeren</b>');
define('kit_text_process_simulate', '<i>simuleren</i>');
define('kit_text_records', 'Records');
define('kit_text_semicolon_separated', 'gescheiden door een puntkomma');
define('kit_text_tabulator_separated', 'gescheiden door een tab');
define('kit_text_to_category', 'in de KIT nieuwsbrief:');
define('kit_text_unknown', '- onbekend -');

?>