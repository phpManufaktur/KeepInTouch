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

if ('á' != "\xc3\xa1") {
	// important: language files must be saved as UTF-8 (without BOM)
	trigger_error('The language file <b>'.basename(__FILE__).'</b> is damaged, it must be saved <b>UTF-8</b> encoded!', E_USER_ERROR);
}

// Deutsche Modulbeschreibung
$module_description = 'dbKeepInTouch (KIT) ist eine zentrale Adress- und Kontaktverwaltung, die unterschiedlichen Anwendungen Kontaktdaten zur Verfuegung stellt.';

// name of the person(s) who translated and edited this language file
$module_translation_by = 'Ralf Hertsch (phpManufaktur)';

$LANG = array(
    '- new contact -'
      => '- neuer Kontakt -',
    '- please select -'
      => '- bitte auswählen -',
    'Abort'
      => 'Abbruch',
    'Academic title'
      => 'Titel',
    'academicNone'
      => '',
    'Additional'
      => 'Zusatz',
    'Addresses'
      => 'Adressen',
    'Birthday'
      => 'Geburtstag',
    'business'
      => 'Dienstlich',
    'Categories'
      => 'Kategorien',
    'Check to set this address as default'
      => 'Aktivieren, um diese Adresse als Standard zu setzen',
    'Check to set this email address as default'
      => 'Aktivieren, um diese E-Mail Adresse als Standard zu setzen',
    'Check to set this phone number as default'
      => 'Aktivieren, um diese Telefonnummer als Standard zu setzen',
    'City'
      => 'Ort',
    'Communication'
      => 'Kommunikation',
    'Company'
      => 'Firma',
    'Contact'
      => 'Kontakt',
    'Contact access'
      => 'Kontakt Freigabe',
    'Contact ID'
      => 'KIT ID',
    'Contact identifier'
      => 'KIT Bezeichner',
    'Contact language'
      => 'Kontakt Sprache',
    'Contact since'
      => 'Kontakt seit',
    'Contact status'
      => 'Kontakt Status',
    'Contact type'
      => 'Kontakt Typ',
    'Create download link'
      => 'Download Link erzeugen',
    'Department'
      => 'Abteilung',
    'Distribution'
      => 'Verteiler',
    'File Upload'
      => 'Datenübertragung',
    'First name'
      => 'Vorname',
    '<p>For the special functions kitform must be installed!</p>'
      => '<p><em>- Für die speziellen Funktionen wird kitForm benötigt! -</em></p>',
    'Function'
      => 'Funktion',
    'Information'
      => 'Information',
    'kit_address_type'
      => 'Adresstyp',
    'kit_birthday'
      => 'Geburtstag',
    'kit_categories'
      => 'Kategorien',
    'kit_city'
      => 'Ort',
    'kit_company'
      => 'Firma',
    'kit_contact_language'
      => 'Sprache',
    'kit_contact_since'
      => 'Kontakt seit',
    'kit_country'
      => 'Land',
    'kit_department'
      => 'Abteilung',
    'kit_email'
      => 'E-Mail',
    'kit_fax'
      => 'Telefax',
    'kit_first_name'
      => 'Vorname',
    'kit_free_field_1'
      => 'Freies Feld 1',
    'kit_free_field_2'
      => 'Freies Feld 2',
    'kit_free_field_3'
      => 'Freies Feld 3',
    'kit_free_field_4'
      => 'Freies Feld 4',
    'kit_free_field_5'
      => 'Freies Feld 5',
    'kit_id'
      => 'ID',
    'kit_identifier'
      => 'Kontakt Bezeichner',
    'kit_intern'
      => 'Intern',
    'kit_last_name'
      => 'Nachname',
    'kit_newsletter'
      => 'Newsletter',
    'kit_note'
      => 'Anmerkungen',
    'kit_phone'
      => 'Telefon',
    'kit_phone_mobile'
      => 'Mobil',
    'kit_status'
      => 'Status',
    'kit_street'
      => 'Straße',
    'kit_title'
      => 'Anrede',
    'kit_title_academic'
      => 'Titel',
    'kit_zip'
      => 'PLZ',
    'Last name'
      => 'Nachname',
    'Map'
      => 'Karte',
    'Notes'
      => 'Notizen',
    'permanent link'
      => 'Permanent-Link',
    'Person'
      => 'Person',
    'Phone'
      => 'Telefon',
    '<p>Please select a page with a kitForm Upload script!</p>'
      => 'Bitte wählen Sie eine Seite mit einem kitForm Upload Script aus!</p>',
    'private'
      => 'Privat',
    '<p>Select a page where a droplet with a referrer to kitForm Upload Script reside. This page may be hidden.</p><p>KIT generate a link which enable the contact to upload a file into the protected file area.</p>'
      => '<p>Wählen Sie eine Seite aus, auf der sich ein Droplet mit einem Verweis auf ein kitForm Upload Script befindet. Die Seite kann versteckt sein.<br />KIT erzeugt einen Link, der es dem Kontakt ermöglicht eine Datei in das von KIT geschützte Verzeichnis zu übertragen.',
    'Select option'
      => 'Option auswählen',
    'Select the page'
      => 'Seite auswählen',
    'Special'
      => 'Spezielle Funktionen',
    'statusActive'
      => 'Aktiv',
    'statusLocked'
      => 'Gesperrt',
    'statusDeleted'
      => 'Gelöscht',
    'Street'
      => 'Straße',
    '<p>The upload link was created, please check at the special functions!</p>'
      => '<p>Der Upload Link wurde erzeugt, bitte prüfen Sie den Link bei den <em>Speziellen Funktionen</em></p>',
    'Title'
      => 'Anrede',
    'throw-away link'
      => 'Wegwerf-Link',
    'ZIP, City'
      => 'PLZ, Stadt',
    );
