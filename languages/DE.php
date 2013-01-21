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
    '- not assigned -'
      => '- nicht zugeordnet -',
    '- new contact -'
      => '- neuer Kontakt -',
    '- not installed -'
      => '- nicht installiert -',
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
    '<p>Added <b>{{ add }}</b> records, updated <b>{{ update }}</b> records.</p>'
      => '<p><b>{{ add }}</b> Datensätze hinzugefügt, <b>{{ update }}</b> Datensätze aktualisiert.</p>',
    'Additional'
      => 'Zusatz',
    'Address extra'
      => 'Adresszusatz',
    'Addresses'
      => 'Adressen',
    'Assign fields'
      => 'Datenfelder zuordnen',
    'Assign the CSV fields to the KIT fields'
      => 'Ordnen Sie die CSV Datenfelder den ensprechenden KIT Datenfeldern zu',
    'Assign the fields'
      => 'Datenfelder zuordnen',
    '<p>At minimum you must assign an email address to <b>kit_email</b> or assign a valid <b>ID</b> to <b>kit_id</b>. Can\'t start the import!</p>'
      => '<p>Die Mindestanforderung besteht in der Zuweisung einer E-Mail Adresse an das Feld <b>kit_email</b> oder der Zuweisung einer gültigen <b>ID</b> an das <b>kit_id</b> Datenfeld. Der Import kann nicht gestartet werden!</p>',

    'Birthday'
      => 'Geburtstag',
    'business'
      => 'Dienstlich',

    '<p>Can\'t move the uploaded file to the destination directory!</p>'
      => '<p>Konnte die hochgeladene Datei nicht in das Zielverzeichnis verschieben!</p>',
    '<p>Can\'t open the file {{ file }}!</p>'
      => '<p>Konnte die Datei {{ file }} nicht öffnen!</p>',
    'Categories'
      => 'Kategorien',
    'Charset'
      => 'Zeichensatz',
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
    'Comma'
      => 'Komma',
    'Create'
      => 'Erstellen',
    'Create download link'
      => 'Download Link erzeugen',
    'Create upload link'
      => 'Upload Link erzeugen',
    'Created'
      => 'Angelegt',
    'CSV file'
      => 'CSV Datei',

    'Delete'
      => 'Löschen',
    'DELETED'
      => 'Gelöscht',
    'Department'
      => 'Abteilung',
    'Distribution'
      => 'Verteiler',

    'Edit'
      => 'Bearbeiten',
    'E-Mail Address'
      => 'E-Mail Adresse',
    '<p>Error reading the CSV file {{ file }}!</p>'
      => '<p>Fehler beim Einlesen der CSV Datei {{ file }}!</p>',
    'Export CSV file'
      => 'Exportiere CSV Datei',
    'Export CSV file from selected source'
      => 'CSV Datei aus einer ausgewählten Quelle exportieren',
    '<p>Exported {{ count }} {{ export }} records as CSV file.</p><p>Please download <a href="{{ url }}">{{ file }}</a>.</p>'
      => '<p>Es wurden {{ count }} {{ export }} Datensätze als CSV Datei exportiert.</p><p>Bitte laden Sie sich die Datei <a href="{{ url }}">{{ file }}</a> zur weiteren Verarbeitung herunter.</p>',

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

    'Import CSV file'
      => 'CSV Datei importieren',
    '<p>Import CSV files i.e. from Excel into KeepInTouch.</p><p>If you are using excel, the preselection with <i>semicolon</i> and <i>ANSI</i> is well, otherwise set the correct values.</p><p>The import expect the field names as first line!</p>'
      => '<p>CSV Dateien z.B. aus Excel heraus nach KeepInTouch importieren.</p><p>Falls Sie Excel verwenden können Sie die Voreinstellungen mit <i>Semikolon</i> und <i>ANSI</i> übernehmen, andernfalls setzen Sie bitte die korrekten Werte.</p><p>Der Import erwartet, dass sich in der ersten Zeile der CSV Datei die Feldbezeichner befinden!</p>',

    '<p>In this dialog you assign the CSV fields to the KIT fields.</p><p>At least you must assign an <i>email address</i> to the KIT field <i>kit_email</i> as unique identifier.</p><p>The import can add and update KIT records. If an email adress already exists the KIT record will be updated. You can also use the <i>KIT ID</i>, assigned to the field <i>kit_id</i> to update existing KIT records.</p><p>Please check the <a href="https://addons.phpmanufaktur.de/de/name/keepintouch/documentation.php">KIT documentation</a> for further informations!</p>'
      => '<p>Mit diesem Dialog weisen Sie die Felder der CSV Datei den entsprechenden KIT Datenfeldern zu.</p><p>Letztendlich müssen Sie mindestens eine <i>E-Mail Adresse</i> dem KIT Datenfeld <i>kit_email</i> als eindeutige Kennzeichnung zuweisen.</p><p>Der CSV Datenimport kann KIT Datensätze hinzfügen und aktualisieren. Falls eine E-Mail Adresse bereits existiert, wird der entsprechende Datensatz in KIT mit den CSV Daten aktualisiert. Sie können darüber hinaus die <i>KIT ID</i> verwenden und diese <i>kit_id</i> zuweisen um bereits existierende Datensätze zu aktualisieren.</p><p>Bitte nutzen Sie die <a href="https://addons.phpmanufaktur.de/de/name/keepintouch/documentation.php">KeepInTouch Dokumentation</a> für weitere Informationen!</p>',
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

    '<p>No CSV file uploaded!</p>'
      => '<p>Es wurde keine CSV Datei übermittelt!</p>',
    '<p>No data source selected!</p>'
      => '<p>Es wurde keine Datenquelle ausgewählt!</p>',
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
    'Preview'
      => 'Vorschau',
    'private'
      => 'Privat',

    'Region or area'
      => 'Region, Kanton, Bereich',

    'Save'
      => 'Speichern',
    '<p>Select a page where a droplet with a referrer to kitForm Upload Script reside. This page may be hidden.</p><p>KIT generate a link which enable the contact to upload a file into the protected file area.</p>'
      => '<p>Wählen Sie eine Seite aus, auf der sich ein Droplet mit einem Verweis auf ein kitForm Upload Script befindet. Die Seite kann versteckt sein.<br />KIT erzeugt einen Link, der es dem Kontakt ermöglicht eine Datei in das von KIT geschützte Verzeichnis zu übertragen.',
    'Select option'
      => 'Option auswählen',
    'Select the file'
      => 'Datei auswählen',
    'Select the page'
      => 'Seite auswählen',
    'Semicolon'
      => 'Semikolon',
    'Send'
      => 'Übermitteln',
    'Separator'
      => 'Trennzeichen',
    'Special'
      => 'Spezielle Funktionen',
    '<p>Skipped invalid date <b>{{ date }}</b> for <i>kit_birthday</i> in line <i>{{ line }}</i>.</p>'
      => '<p>Ungültiges Datum <b>{{ date }}</b> für <i>kit_birthday</i> in Zeile <i>{{ line }}</i> übersprungen.</p>',
    '<p>Skipped invalid entry <b>{{ newsletter }}</b> for <i>kit_newsletter</i> in line <i>{{ line }}</i>.</p>'
      => '<p>Ungültiger Eintrag <b>{{ newsletter }}</b> für <i>kit_newsletter</i> in Zeile <i>{{ line }}</i> übersprungen.</p>',
    '<p>Skipped line {{ line }} because the number of columns differ from the CSV definition!</p>'
      => '<p>Zeile {{ line }} übersprungen, die Anzahl der Spalten weicht von der CSV Definition ab!</p>',
    '<p>Skipped line <i>{{ line }}</i>, the KIT ID <b>{{ kit_id }}</b> does not exists!</p>'
      => '<p>Zeile <i>{{ line }}</i> übersprungen, die KIT ID <b>{{ kit_id }}</b> existiert nicht!</p>',
    'Source data'
      => 'Quelldaten',
    'Start export'
      => 'Export starten',
    'Start import'
      => 'Import starten',
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
    '<p>The CSV export for {{ export }} is not supported!</p>'
      => '<p>Der CSV Export für {{ export }} wird nicht unterstützt!</p>',
    '<p>The CSV file {{ file }} does not have any columns, please check the file!</p>'
      => '<p>Die CSV Datei {{ file }} enthält keine Spalten, bitte prüfen Sie die Datei!</p>',
    '<p>The download link was created, please check at the special functions!</p>'
      => '<p>Der Download Link wurde angelegt, bitte prüfen Sie den Link bei den <em>Speziellen Funktionen</em>.</p>',
    '<p>The field <b>{{ field }}</b> was assigned twice, please assign each field only once!</p>'
      => 'Das Datenfeld <b>{{ field }}</b> wurde doppelt zugewiesen, bitte weisen Sie jedes Feld nur einmal zu!</p>',
    '<p>The first row of the CSV file {{ file }} is empty, expected header informations!</p>'
      => '<p>Die erste Zeile der CSV Datei {{ file }} ist leer, es wurden dort die Spaltenbezeichner erwartet!</p>',
    '<p>The link with the GUID <b>{{ guid }}</b> was deleted.</p>'
      => '<p>Der Link mit der GUID <b>{{ guid }}</b> wurde gelöscht!</p>',
    '<p>The upload link was created, please check at the special functions!</p>'
      => '<p>Der Upload Link wurde angelegt, bitte prüfen Sie den Link bei den <em>Speziellen Funktionen</em></p>',
    '<p>There are no data records to export for {{ export }}.</p>'
      => '<p>Für <b>{{ export }}</b> sind keine Datensätze zum exportieren vorhanden.</p>',
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

    '<p>You can export all your contact and address data records from different addons in comma separated textfiles (CSV).</p><p>You can open and edit these CSV files with any texteditor but also with programs like Excel.</p><p>Additional, KeepInTouch support the import of CSV files.</p>'
      => '<p>Mit Hilfe dieses Dialog können Sie die Adress- und Kontaktdaten von verschiedenen WebsiteBaker bzw. LEPTON Addons als Komma getrennte Textdateien (CSV Format) exportieren.</p><p>Dateien im CSV Format können mit jedem beliebigen Texteditor oder im Tabellenformat mit Programmen wie Microsoft Excel oder OpenOffice Calc geöffnet und bearbeitet werden.</p><p>Über die Import Funktion können Sie CSV Dateien in KeepInTouch einlesen.</p><p>Falls Sie Microsoft Excel für die Kontrolle und Weiterverarbeitung der CSV Dateien verwenden, übernehmen Sie bitte die Voreinstellungen <i>Semikolon</i> und <i>ANSI</i>, ansonsten passen Sie die Voreinstellungen nach Bedarf an.</p>',

    'ZIP, City'
      => 'PLZ, Stadt',
    );
