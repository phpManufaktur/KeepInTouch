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
    'ACTIVE'
      => 'Aktiv',
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
    'Create'
      => 'Erstellen',
    'Create download link'
      => 'Download Link erzeugen',
    'Create upload link'
      => 'Upload Link erzeugen',
    'Created'
      => 'Angelegt',
    'Delete'
      => 'Löschen',
    'DELETED'
      => 'Gelöscht',
    'Department'
      => 'Abteilung',
    'Distribution'
      => 'Verteiler',
    'File Download'
      => 'Dateien vom Server herunterladen',
    'File Upload'
      => 'Dateien auf den Server übertragen',
    'First name'
      => 'Vorname',
    '<p>For the special functions <b>kitform</b> must be installed!</p>'
      => '<p><em>- Für die speziellen Funktionen wird <b>kitForm</b> benötigt! -</em></p>',
    'Function'
      => 'Funktion',
    'Information'
      => 'Information',
    '[ {{ line }} ] Invalid call, missing GUID!'
      => '[ {{ line }} ] Ungueltiger Aufruf, es wurde keine GUID uebergeben!',
    '[ {{ line }} ] Invalid GUID, please contact the webmaster.'
      => '[ {{ line }} ] Ungueltige GUID, bitte kontaktieren Sie den Webmaster!',
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
    'LOCKED'
      => 'Gesperrt',
    'Map'
      => 'Karte',
    'Notes'
      => 'Notizen',
    '[ {{ line }} ] Oooops, missing the requested file. Please contact the webmaster!'
      => '[ {{ line }} ] Uuuups, die angeforderte Datei steht nicht mehr zur Verfügung. Bitte nehmen Sie Kontakt mit dem Webmaster auf.',
    'PERMANENT'
      => 'Permanent',
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
    'Select the file'
      => 'Datei auswählen',
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
    '<p>The contact can download files from the protected area <strong>/media/kit_protected</strong>. Just select the file, set the option and email the link to the contact. By default the files from the <strong>/admin</strong> and the <strong>/user</strong> section of the active contact are available, you can add additional paths at the KIT settings.</p>'
      => '<p>Der Kontakt kann Dateien aus dem geschützten Bereich <strong>/media/kit_protected</strong> herunterladen. Hierzu wählen Sie die gewünschte Datei aus, setzen die Option und senden dem Kontakt den Link per E-Mail. In der Voreinstellung werden die Dateien aus dem <strong>/admin</strong> und dem <strong>/user</strong> Verzeichnis aus dem geschützten Bereich des Kontakts angezeigt. Sie können über die KIT Einstellungen weitere Verzeichnisse hinzufügen.</p>',
    'The contact has downloaded the file <b>{{ file }}</b> with the GUID <b>{{ guid }}</b>.'
      => 'Der Kontakt hat die Datei <b>{{ file }}</b> mit der GUID <b>{{ guid }}</b> heruntergeladen.',
    '<p>The download link was created, please check at the special functions!</p>'
      => '<p>Der Download Link wurde angelegt, bitte prüfen Sie den Link bei den <em>Speziellen Funktionen</em>.</p>',
    '<p>The link with the GUID <b>{{ guid }}</b> was deleted.</p>'
      => '<p>Der Link mit der GUID <b>{{ guid }}</b> wurde gelöscht!</p>',
    '<p>The upload link was created, please check at the special functions!</p>'
      => '<p>Der Upload Link wurde angelegt, bitte prüfen Sie den Link bei den <em>Speziellen Funktionen</em></p>',
    '[ {{ line }} ] This download link is no longer valid, please contact the webmaster to get a new one!'
      => '[ {{ line }} ] Dieser Download Link steht nicht mehr zur Verfuegung, bitte kontaktieren Sie den Webmaster um einen neuen Link zu erhalten!',
    '[ {{ line }} ] This is no valid download link, please contact the webmaster.'
      => '[ {{ line }} ] Dies ist kein gueltiger Download Link, bitte nehmen Sie Kontakt mit dem Webmaster auf!',
    'Timestamp'
      => 'Letzter Aufruf',
    'Title'
      => 'Anrede',
    'THROW-AWAY'
      => 'Einweg',
    'throw-away link'
      => 'Wegwerf-Link',
    'Type'
      => 'Typ',
    'UPLOAD'
      => 'Upload',
    'ZIP, City'
      => 'PLZ, Stadt',
    );
