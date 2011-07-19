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

define('kit_dialog_nl_account_active',								'<p>Sie sind bereits als Newsletter Abonnent registriert.</p><p><b><a href="%s">Bitte melden Sie sich an Ihrem Benutzerkonto an</a></b>, um Einstellungen zu prüfen und zu ändern.</p>');
define('kit_dialog_nl_captcha_invalid',								'Der übermittelte Wert stimmt nicht mit dem Captcha überein.');
define('kit_dialog_nl_email_empty',										'Sie haben vergessen, eine E-Mail Adresse anzugeben');
define('kit_dialog_nl_email_invalid',									'Die E-Mail Adresse <b>%s</b> ist ungültig!');
define('kit_dialog_nl_error_mail',										'<p>Die E-Mail an <b>%s</b> konnte nicht versendet werden, Fehler:<br /><b>%s</b></p><p>Der Datensatz wurde gesperrt, bitte melden Sie sich erneut für das Newsletter Abonnement an.</p>');
define('kit_dialog_nl_error_mail_send',								'<p>Die E-Mail an die Adresse <b>%s</b> konnte leider nicht versendet werden.</p>');
define('kit_dialog_nl_error_no_provider',							'Es ist kein Dienstleister für den Newsletterversand definiert, bitte informieren Sie den Systemadministrator!');
define('kit_dialog_nl_invalid_account',								'<p>Der Account mit der <b>ID %05d</b> wurde nicht gefunden!</p>');
define('kit_dialog_nl_mail_subject',									'Bestätigung Ihrer Newsletter Anmeldung');
define('kit_dialog_nl_mail_subject_unsubscribe',			'Bestätigung Ihrer Abmeldung vom Newsletter');
define('kit_dialog_nl_message',												'KeepInTouch Meldung');
define('kit_dialog_nl_message_account_locked',				'<p>Ihr Benutzerkonto ist gesperrt, bitte nehmen Sie Kontakt mit dem Systemadministrator auf.</p>');
define('kit_dialog_nl_message_key_already_send',			'<p>Sie sind bereits für den Newsletter angemeldet, haben den Versand allerdings noch nicht mit dem <b>Aktivierungslink</b> bestätigt, den wir Ihnen per E-Mail zugesendet haben.</p><p>Wir haben Ihnen den Aktivierungslink noch einmal zugesendet, bitte prüfen Sie Ihren E-Mail Eingang.</p>');
define('kit_dialog_nl_newsletter_intro',							'Bleiben Sie stets informiert! Melden Sie sich bei unserem Newsletter an, wir informieren Sie regelmäßig.');
define('kit_dialog_nl_no_account_for_unsubscribe',		'<p>Für die E-Mail Adresse <b>%s</b> existiert kein Konto und es ist auch kein Newsletter abonniert.</p>');
define('kit_dialog_nl_no_action',											'Für die ausgewählte Aktion <b>%s</b> ist keine Meldung definiert.');
define('kit_dialog_nl_no_newsletter',									'Es ist kein Newsletter definiert, bitte informieren Sie den Systemadministrator!');
define('kit_dialog_nl_no_newsletter_for_unsubscribe',	'<p>Für die E-Mail Adresse <b>%s</b> ist kein Newsletter abonniert!</p>');
define('kit_dialog_nl_no_newsletter_selected',				'<p>Bitte wählen Sie einen oder mehrere Newsletter aus, die Sie gerne abonnieren möchten.</p>');
define('kit_dialog_nl_service',												'KeepInTouch &copy 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>, Berlin (Germany)');
define('kit_dialog_nl_unsubscribe_intro',							'<p>Bitte wählen Sie die Newsletter aus, die Sie abbestellen möchten.</p><p>Wir senden Ihnen eine E-Mail mit einem Bestätigungslink zu.</p>');
define('kit_dialog_nl_unsubscribe_no_selection',			'<p>Sie haben keinen Newsletter ausgewählt, den Sie abbestellen möchten.</p>');
define('kit_dialog_nl_unsubscribe_key_send',					'<p>Wir haben Ihnen einen Bestätigungslink an die E-Mail Adresse <b>%s</b> gesendet, bitte kontrollieren Sie Ihr Postfach.</p>');
?>