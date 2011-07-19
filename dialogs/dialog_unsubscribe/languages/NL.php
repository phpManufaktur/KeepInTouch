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

define('kit_dialog_unsub_error_no_email',					'<p>Er is geen E-Mail adres gevonden op ID <b>%05d</b>, neem contact op met de webmanager over dit probleem!</p>');

define('kit_dialog_unsub_log_distribution',				'De contact deelt via een E-Mail Link mee, dat de contact geen verdere mailings meer wil ontvangen.');
define('kit_dialog_unsub_log_newsletter',					'De contact deelt via een E-Mail Link mee, dat de contact Nieuwsbrief %s niet langer meer wil ontvangen.');

define('kit_dialog_unsub_mail_subject_dist',			'Abonnee uitschrijven uit de distributie!');
define('kit_dialog_unsub_mail_subject_nl',				'Abonnee ontkoppeld uit Nieuwsbrief');
define('kit_dialog_unsub_mail_text_dist',					'<p>Het abonnement <b>%s</b> met het KIT ID <b>%05d</b> en de E-Mail Adres <b>%s</b> wil in de toekomst niet meer worden aangeschreven.</p><p>Controleer de mailing lijst en zorg ervoor dat de wens van de abonnees wordt uitgevoerd.</p><p>-- Dit is een automatische bericht door KeepInTouch --</p>');
define('kit_dialog_unsub_mail_text_nl',						'<p>Het abonnement <b>%s</b> met het KIT ID <b>%05d</b> en het E-Mail Adres <b>%s</b> heeft zich van de nieuwsbrieven <b>%s</b> afgemeld.</p><p>De database wordt automatisch bijgewerkt.</p><p>-- Dit is een automatische bericht door KeepInTouch --</p>');

define('kit_dialog_unsub_text_abort',							'<p>U heeft er voor gekozen om zich niet af te melden van de mailinglijst.</p>');
define('kit_dialog_unsub_text_distribution',			'<p>U bent met uw E-Mail Adres <b>%s</b> in onze malinglist ingeschreven.</p><p>Wilt u in de toekomst geen informatie meer ontvangen van <b>%s</b>?</p>');
define('kit_dialog_unsub_text_dist_confirm',			'<p>U wordt uitgeschreven van de mailinglijst.</p>');
define('kit_dialog_unsub_text_newsletter_single',	'<p>U bent met uw E-Mail Adres <b>%s</b> voor nieuwsbrief <b>%s</b> in onze mailinglijst ingeschreven.</p><p>Weet u het zeker dat u zich wilt van deze nieuwsbrief?</p>');
define('kit_dialog_unsub_text_newsletter_multi',	'<p>U bent met uw E-Mail Adres <b>%s</b> voor nieuwsbrief <b>%s</b> in onze mailinglijst ingeschreven.</p><p>Weet u het zeker dat u zich wilt van deze nieuwsbrief?</p>');
define('kit_dialog_unsub_text_nl_confirm',				'<p>Wij hebben u uitgeschreven uit de verzendlijst voor het versturen van de nieuwsbrief <b>%s</b>.</p>');
define('kit_dialog_unsub_text_unknown_user',			'<ONBEKEND>');

?> 