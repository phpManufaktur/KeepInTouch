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

define('kit_dialog_unsub_error_no_email',					'<p>Zu der ID <b>%05d</b> wurde keine E-Mail Adresse gefunden, bitte informieren Sie den Systemadministrator über dieses Problem!</p>');

define('kit_dialog_unsub_log_distribution',				'Der Kontakt teilt über einen E-Mail Link mit, dass er keine weiteren Zusendungen mehr wünscht.');
define('kit_dialog_unsub_log_newsletter',					'Der Kontakt teilt über einen E-Mail Link mit, dass er den/die Newsletter %s künftig nicht mehr erhalten möchte.');

define('kit_dialog_unsub_mail_subject_dist',			'Bitte Abonnent aus Verteiler austragen!');
define('kit_dialog_unsub_mail_subject_nl',				'Abonnent aus Newsletter ausgetragen');
define('kit_dialog_unsub_mail_text_dist',					'<p>Der Abonnent <b>%s</b> mit der KIT ID <b>%05d</b> und der E-Mail Adresse <b>%s</b> möchte künftig nicht mehr angeschrieben werden.</p><p>Bitte prüfen Sie den Verteiler und stellen Sie sicher, dass der Wunsch dieses Abonnenten erfüllt wird.</p><p>-- dies ist eine automatische Benachrichtigung durch KeepInTouch --</p>');
define('kit_dialog_unsub_mail_text_nl',						'<p>Der Abonnent <b>%s</b> mit der KIT ID <b>%05d</b> und der E-Mail Adresse <b>%s</b> hat sich von dem/den Newsletter(n) <b>%s</b> abgemeldet.</p><p>Die Datenbank wurde automatisch aktualisiert.</p><p>-- dies ist eine automatische Benachrichtigung durch KeepInTouch --</p>');

define('kit_dialog_unsub_text_abort',							'<p>Vielen Dank, dass Sie sich gegen eine Abmeldung aus unserem Verteiler entschieden haben.</p>');
define('kit_dialog_unsub_text_distribution',			'<p>Sie sind mit der E-Mail Adresse <b>%s</b> in unserem Verteiler eingetragen.</p><p>Möchten Sie künftig wirklich keine Informationen mehr durch <b>%s</b> erhalten?</p>');
define('kit_dialog_unsub_text_dist_confirm',			'<p>Wir respektieren Ihren Wunsch und werden Sie aus unserem Verteiler entfernen.</p>');
define('kit_dialog_unsub_text_newsletter_single',	'<p>Sie sind mit der E-Mail Adresse <b>%s</b> für den Newsletter <b>%s</b> in unserem Verteiler eingetragen.</p><p>Möchten Sie diesen Newsletter wirklich abbestellen?</p>');
define('kit_dialog_unsub_text_newsletter_multi',	'<p>Sie sind mit der E-Mail Adresse <b>%s</b> für die Newsletter <b>%s</b> in unserem Verteiler eingetragen.</p><p>Möchten Sie diese Newsletter wirklich abbestellen?</p>');
define('kit_dialog_unsub_text_nl_confirm',				'<p>Wir respektieren Ihren Wunsch und haben Sie aus dem Verteiler für den/die Newsletter(n) <b>%s</b> entfernt.</p>');
define('kit_dialog_unsub_text_unknown_user',			'<UNBEKANNT>');

?> 