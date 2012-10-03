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
    'Department'
      => 'Abteilung',
    'Distribution'
      => 'Verteiler',
    'First name'
      => 'Vorname',
    'Function'
      => 'Funktion',
    'Information'
      => 'Information',
    'kit_categories'
      => 'Kategorien',
    'kit_city'
      => 'Ort',
    'kit_email'
      => 'E-Mail',
    'kit_first_name'
      => 'Vorname',
    'kit_id'
      => 'ID',
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
    'kit_status'
      => 'Status',
    'kit_street'
      => 'Straße',
    'kit_zip'
      => 'PLZ',
    'Last name'
      => 'Nachname',
    'Map'
      => 'Karte',
    'Notes'
      => 'Notizen',
    'Person'
      => 'Person',
    'Phone'
      => 'Telefon',
    'private'
      => 'Privat',
    'statusActive'
      => 'Aktiv',
    'statusLocked'
      => 'Gesperrt',
    'statusDeleted'
      => 'Gelöscht',
    'Street'
      => 'Straße',
    'Title'
      => 'Anrede',
    'ZIP, City'
      => 'PLZ, Stadt',
    );
