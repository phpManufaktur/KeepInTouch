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

// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

define('kit_dialog_acc_key_no_longer_valid',				'<p>Der Aktivierungskey für die E-Mail Adresse <b>%s</b> wurde bereits verwendet und ist ungültig.</p><p>Bitte melden Sie sich an Ihrem Benutzerkonto an, um Einstellungen zu ändern.</p>');
define('kit_dialog_acc_key_invalid',								'<p>Der übermittelte Aktivierungskey ist ungültig.</p>');
define('kit_dialog_acc_mail_subject_welcome',				'Herzlich Willkommen!');
define('kit_dialog_acc_error_mail',									'<p>Die E-Mail an <b>%s</b> konnte nicht versendet werden, Fehler:<br /><b>%s</b></p>');
define('kit_dialog_acc_error_provider_missing',			'<p>Es ist kein Dienstleister definiert!</p<p>Bitte verständigen Sie den Systemadministrator.</p>');
define('kit_dialog_acc_log_contact_created',				'Datensatz durch Benutzer angelegt');
define('kit_dialog_acc_log_newsletter_registered',	'Benutzer abonniert die Newsletter: %s');
define('kit_dialog_acc_error_no_action_defined',		'<b>Für die Anforderung <b>%s</b> ist keine Aktion definiert, bitte informieren Sie den Systemadministrator.</p>');
define('kit_dialog_acc_intro_login',								'Bitte melden Sie sich mit Ihrem Benutzernamen (E-Mail Adresse) und Ihrem Passwort an.');
define('kit_dialog_acc_label_username',							'Benutzername');
define('kit_dialog_acc_label_password',							'Passwort');
define('kit_dialog_acc_login_incomplete',						'Ihre Angaben sind unvollständig, bitte geben Sie ihren Benutzernamen und ihr Passwort ein.');
define('kit_dialog_acc_login_invalid',							'Benutzername und/oder Passwort stimmen nicht.');
define('kit_dialog_acc_login_locked',								'<p><b>Ihr Konto ist gesperrt</b> (<i>Passwort zu oft falsch eingegeben?</i>).</p><p>Bitte nehmen Sie Kontakt mit dem Support auf, um ihr Konto wieder freischalten zu lassen.</p>');
define('kit_dialog_acc_log_login_locked',						'KONTO GESPERRT, der Benutzer hat zuviele fehlerhafte Login Versuche unternommen.');
define('kit_dialog_acc_password_hint',							'<p>Passwort vergessen? Lassen Sie sich das <a href="%s">Passwort erneut zusenden</a>!</p>');
define('kit_dialog_acc_intro_password_needed',			'Bitte geben Sie Ihre E-Mail Adresse ein, wir senden Ihnen das Passwort dann direkt zu.');
define('kit_dialog_acc_mail_invalid',								'Die angegebene E-Mail Adresse ist ungültig.');
define('kit_dialog_acc_mail_not_exists',						'Die E-Mail Adresse <b>%s</b> ist hier nicht registriert.');
define('kit_dialog_acc_log_password_send',					'Der Benutzer hat ein neues Passwort per E-Mail erhalten');
define('kit_dialog_acc_mail_subject_password',			'Ihre Zugangsdaten');
define('kit_dialog_acc_captcha_invalid',						'Der übermittelte Wert stimmt nicht mit dem Captcha überein.');
define('kit_dialog_acc_password_send',							'Wir haben Ihnen ein neues Passwort an Ihre E-Mail Adresse <b>%s</b> gesendet. Bitte überprüfen Sie ihren Posteingang.');
define('kit_dialog_acc_intro_account',							'Bitte prüfen Sie die Angaben Ihres Benutzerkonto.');
define('kit_dialog_acc_error_account_id',						'Das Benutzerkonto mit der <b>ID %05d</b> wurde nicht gefunden. Bitte informieren Sie den Systemadministrator.');
define('kit_dialog_acc_error_contact_id',						'Der dem Benutzerkonto <b>%05d</b> zugenordnete Kontaktdatensatz mit der <b>ID %05d</b> wurde nicht gefunden. Bitte informieren Sie den Systemadministrator.');
define('kit_dialog_acc_label_logout',								'Abmelden');
define('kit_dialog_acc_log_account_update',					'Der Benutzer hat den Datensatz aktualisiert.');
define('kit_dialog_acc_account_update_success',			'Der Datensatz wurde erfolgreich aktualisiert.');
define('kit_dialog_acc_account_update_skipped',			'Es wurden keine Daten verändert.');
define('kit_dialog_acc_unsubscribe_invalid',				'Der übermittelte Deaktivierungsbefehl ist unvollständig und wurde ignoriert.');
define('kit_dialog_acc_unsubscribe_success',				'Sie wurden erfolgreich aus dem Newsletter Abonnement <b>%s</b> entfernt.');
define('kit_dialog_acc_intro_change_password',			'Aus Sicherheitsgründen müssen Sie Ihr Passwort ändern. Bitte legen Sie ein neues Passwort fest!');
define('kit_dialog_acc_log_password_changed',				'Der Benutzer hat das Passwort geändert.');

?>