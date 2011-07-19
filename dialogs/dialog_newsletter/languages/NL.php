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

define('kit_dialog_nl_account_active',								'<p>U bent reeds geregistreerd als nieuwsbrief abonnee.</p><p><b><a href="%s">Log in </a></b>om uw account te controleren en instellingen wijzigen.</p>');
define('kit_dialog_nl_captcha_invalid',								'De ingevoerde waarde komt niet overeenkomt met de Captcha (afbeelding).');
define('kit_dialog_nl_email_empty',										'U bent vergeten een e-mail adres op te geven');
define('kit_dialog_nl_email_invalid',									'Het E-Mail Adres <b>%s</b> is ongeldig!');
define('kit_dialog_nl_error_mail',										'<p>De E-Mail van <b>%s</b> kan niet verstuurd worden, Fout:<br /><b>%s</b></p><p>De gegevens zijn geblokkeerd, wilt u zich opnieuw aanmelden voor het nieuwsbrief abonnement.</p>');
define('kit_dialog_nl_error_mail_send',								'<p>De E-mail naar adres <b>%s</b>, kan helaas niet worden verzonden.</p>');
define('kit_dialog_nl_error_no_provider',							'Er is geen service voor het versturen van nieuwsbrieven gedefinieerd, neem contact op met de webmanager!');
define('kit_dialog_nl_invalid_account',								'<p>Het account met <b>ID %05d</b> is niet gevonden!</p>');
define('kit_dialog_nl_mail_subject',									'Bevestig uw nieuwsbrief registratie');
define('kit_dialog_nl_mail_subject_unsubscribe',			'Bevestiging van uw abonnement op de nieuwsbrief');
define('kit_dialog_nl_message',												'KeepInTouch Melding');
define('kit_dialog_nl_message_account_locked',				'<p>Uw account is geblokkeerd, neem contact op met de webmanager.</p>');
define('kit_dialog_nl_message_key_already_send',			'<p>U bent al aangemeld voor de nieuwsbrief, maar heeft uw account nog niet geactiveerd middels de <b>activatie link</b> die reeds verstuurd is per email.</p><p>We hebben een nieuwe E-mail met activatie link verstuurd.</p>');
define('kit_dialog_nl_newsletter_intro',							'Wilt u op de hoogte blijven, schrijf je in voor onze nieuwsbrief. Wij houden je regelmatig op de hoogte.');
define('kit_dialog_nl_no_account_for_unsubscribe',		'<p>Geen account gegevens gevonden voor E-Mail Adres <b>%s</b> en is niet geabonneerd op een nieuwsbrief.</p>');
define('kit_dialog_nl_no_action',											'Voor de geselecteerde actie <b>%s</b> is geen melding gedefinieerd.');
define('kit_dialog_nl_no_newsletter',									'Er is geen nieuwsbrief gedefineerd!, neem contact op met de webmanager');
define('kit_dialog_nl_no_newsletter_for_unsubscribe',	'<p>E-Mail Adres <b>%s</b> is niet gekoppeld aan een nieuwsbrief!</p>');
define('kit_dialog_nl_no_newsletter_selected',				'<p>Selecteer een of meerdere nieuwsbrieven waar u op wilt abonneren.</p>');
define('kit_dialog_nl_service',												'KeepInTouch &copy 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>, Berlin (Germany)');
define('kit_dialog_nl_unsubscribe_intro',							'<p>Selecteer de nieuwsbrief waarvoor u zich wilt afmelden.</p><p>We sturen u een e-mail met een deactivatie link.</p>');
define('kit_dialog_nl_unsubscribe_no_selection',			'<p>U heeft geen nieuwsbrief geselecteerd waar u zich van af wilt melden.</p>');
define('kit_dialog_nl_unsubscribe_key_send',					'<p>Wij hebben u ​​een e-mail met een bevestiging link naar <b>%s</b> gestuurd, controleer uw mailbox.</p>');
?>