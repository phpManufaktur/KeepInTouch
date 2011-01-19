<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2010, Ralf Hertsch
  Contact me: ralf.hertsch@phpManufaktur.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id$
  
	IMPORTANT NOTE:

  If you are editing this file or creating a new language file
  you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
  Otherwise all special chars will be destroyed and displayed improper!

	It is NOT NECESSARY to mask special chars as HTML entities!
 
	Translated to German (Original Source) by Ralf Hertsch

**/

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