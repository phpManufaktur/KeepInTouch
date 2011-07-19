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


define('kit_dialog_acc_key_no_longer_valid',				'<p>De activering van het e-mailadres <b>%s</b> is al gebruikt en is niet geldig.</p><p>Log in op uw account om uw instellingen te wijzigen.</p>');
define('kit_dialog_acc_key_invalid',								'<p>De verstuurde activatiecode is ongeldig.</p>');
define('kit_dialog_acc_mail_subject_welcome',				'Hartelijk Welkom!');
define('kit_dialog_acc_error_mail',									'<p>De E-Mail naar <b>%s</b> kan niet verstuurd worden, Fout:<br /><b>%s</b></p>');
define('kit_dialog_acc_error_provider_missing',			'<p>Er is geen service gedefinieerd!</p<p>Neem contact op met de Webbeheerder.</p>');
define('kit_dialog_acc_log_contact_created',				'Data aagemaakt door gebruikers');
define('kit_dialog_acc_log_newsletter_registered',	'Gebruikers abonneren zich op de nieuwsbrief: %s');
define('kit_dialog_acc_error_no_action_defined',		'<b>Voor de vereiste <b>%s</b> is geen actie gedefineerd, neem contact op met de webbeheerder.</p>');
define('kit_dialog_acc_intro_login',								'Gelieve in te loggen met uw gebruikersnaam (e-mailadres) en wachtwoord.');
define('kit_dialog_acc_label_username',							'Gebruikersnaam');
define('kit_dialog_acc_label_password',							'Wachtwoord');
define('kit_dialog_acc_login_incomplete',						'Uw informatie is onvolledig, vul uw gebruikersnaam en wachtwoord in.');
define('kit_dialog_acc_login_invalid',							'Gebruikersnaam of wachtwoord is niet correct.');
define('kit_dialog_acc_login_locked',								'<p><b>Uw account is gelockt </b> (<i>wachtwoord te vaak foutief ingegeven?</i>).</p><p>Neem contact op met de webbeheerder om uw account vrij te geven.</p>');
define('kit_dialog_acc_log_login_locked',						'ACCOUNT GELOCKT, de gebruiker heeft te veel onjuiste login pogingen uitgevoerd.');
define('kit_dialog_acc_password_hint',							'<p>Wachtwoord vergeten? Verzoek om nogmaals het <a href="%s">wachtwoord te versturen</a>!</p>');
define('kit_dialog_acc_intro_password_needed',			'Geeft u uw E-Mail Adres, wij sturen uw wachtwoord gelijk door.');
define('kit_dialog_acc_mail_invalid',								'De opgegevens E-Mail Adres is niet geldig.');
define('kit_dialog_acc_mail_not_exists',						'Het E-Mail Adres <b>%s</b> is niet (meer) geregistreerd.');
define('kit_dialog_acc_log_password_send',					'De gebruiker ontvangt een nieuw wachtwoord via de E-mail ');
define('kit_dialog_acc_mail_subject_password',			'Uw toegang');
define('kit_dialog_acc_captcha_invalid',						'De ingediende waarde komt niet overeenkomt met de Captcha .');
define('kit_dialog_acc_password_send',							'Wij hebben u een nieuw wachtwoord naar uw e-mail adres <b>%s</b> gestuurd. Controleer uw inbox. ');
define('kit_dialog_acc_intro_account',							'Controleer de details van uw account.');
define('kit_dialog_acc_error_account_id',						'Het gebruikersaccount met het <b>ID %05d</b> is niet gevonden. Neem contact op met de webbeheerder.');
define('kit_dialog_acc_error_contact_id',						'Van de useraccount <b>%05d</b> is het gekoppelde contact <b>ID %05d</b> niet gevonden. Neem contact op met de webbeheerder.');
define('kit_dialog_acc_label_logout',								'Afmelden');
define('kit_dialog_acc_log_account_update',					'De gebruiker heeft het account geactualiseerd.');
define('kit_dialog_acc_account_update_success',			'De gegevens zijn succesvol geactualiseerd.');
define('kit_dialog_acc_account_update_skipped',			'Geen data is veranderd.');
define('kit_dialog_acc_unsubscribe_invalid',				'De verzonden deactiverings commando is niet volledig en wordt genegeerd.');
define('kit_dialog_acc_unsubscribe_success',				'U bent succesvol verwijderd van uw nieuwsbrief abonnement <b>%s</b>.');
define('kit_dialog_acc_intro_change_password',			'Aus Sicherheitsgründen müssen Sie Ihr Passwort ändern. Bitte legen Sie ein neues Passwort fest!');
define('kit_dialog_acc_log_password_changed',				'De gebruiker heeft het wachtwoord veranderd.');

?>