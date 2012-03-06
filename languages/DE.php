<?php

/**
 * KeepInTouch
 *
 * @author Ralf Hertsch <ralf.hertsch@phpmanufaktur.de>
 * @link http://phpmanufaktur.de
 * @copyright 2012 - phpManufaktur by Ralf Hertsch
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License (GPL)
 * @version $Id$
 *
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
  if (defined('LEPTON_VERSION')) include (WB_PATH . '/framework/class.secure.php');
}
else {
  $oneback = "../";
  $root = $oneback;
  $level = 1;
  while (($level < 10) && (!file_exists($root . '/framework/class.secure.php'))) {
    $root .= $oneback;
    $level += 1;
  }
  if (file_exists($root . '/framework/class.secure.php')) {
    include ($root . '/framework/class.secure.php');
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
    'Academic title'
      => 'Titel',
    'Additional'
      => 'Zusatz',
    'Addresses'
      => 'Adressen',
    'Birthday'
      => 'Geburtstag',
    'Check to set this address as default'
      => 'Aktivieren, um diese Adresse als Standard zu setzen',
    'Check to set this email address as default'
      => 'Aktivieren, um diese E-Mail Adresse als Standard zu setzen',
    'Check to set this phone number as default'
      => 'Aktivieren, um diese Telefonnummer als Standard zu setzen',
    'City'
      => 'Stadt',
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
    'First name'
      => 'Vorname',
    'Function'
      => 'Funktion',
    'Information'
      => 'Information',
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
    'Street'
      => 'Straße',
    'Title'
      => 'Anrede',
    'ZIP, City'
      => 'PLZ, Stadt',
    );
