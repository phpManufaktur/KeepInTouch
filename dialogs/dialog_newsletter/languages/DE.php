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
  
  $Id: DE.php 30 2010-05-24 15:48:21Z ralf $
  
	IMPORTANT NOTE:

  If you are editing this file or creating a new language file
  you must ensure that you SAVE THIS FILE UTF-8 ENCODED.
  Otherwise all special chars will be destroyed and displayed improper!

	It is NOT NECESSARY to mask special chars as HTML entities!

	Translated to German (Original Source) by Ralf Hertsch

**/

define('kit_dialog_nl_newsletter_intro',							'Bleiben Sie stets informiert! Melden Sie sich bei unserem Newsletter an, wir informieren Sie regelmäßig.');
define('kit_dialog_nl_captcha_invalid',								'Der übermittelte Wert stimmt nicht mit dem Captcha überein.');
define('kit_dialog_nl_email_empty',										'Sie haben vergessen, eine E-Mail Adresse anzugeben');
define('kit_dialog_nl_email_invalid',									'Die E-Mail Adresse <b>%s</b> ist ungültig!');
define('kit_dialog_nl_mail_subject',									'Bestätigung Ihrer Newsletter Anmeldung');
define('kit_dialog_nl_mail_subject_unsubscribe',			'Bestätigung Ihrer Abmeldung vom Newsletter');
define('kit_dialog_nl_error_mail',										'<p>Die E-Mail an <b>%s</b> konnte nicht versendet werden, Fehler:<br /><b>%s</b></p><p>Der Datensatz wurde gesperrt, bitte melden Sie sich erneut für das Newsletter Abonnement an.</p>');
define('kit_dialog_nl_no_newsletter',									'Es ist kein Newsletter definiert, bitte informieren Sie den Systemadministrator!');
define('kit_dialog_nl_error_no_provider',							'Es ist kein Dienstleister für den Newsletterversand definiert, bitte informieren Sie den Systemadministrator!');
define('kit_dialog_nl_message',												'KeepInTouch Meldung');
define('kit_dialog_nl_service',												'KeepInTouch &copy 2010 by <a href="http://phpmanufaktur.de" target="_blank">phpManufaktur</a>, Berlin (Germany)');
define('kit_dialog_nl_no_action',											'Für die ausgewählte Aktion <b>%s</b> ist keine Meldung definiert.');
define('kit_dialog_nl_message_key_already_send',			'<p>Sie sind bereits für den Newsletter angemeldet, haben den Versand allerdings noch nicht mit dem <b>Aktivierungslink</b> bestätigt, den wir Ihnen per E-Mail zugesendet haben.</p><p>Wir haben Ihnen den Aktivierungslink noch einmal zugesendet, bitte prüfen Sie Ihren E-Mail Eingang.</p>');
define('kit_dialog_nl_message_account_locked',				'<p>Ihr Benutzerkonto ist gesperrt, bitte nehmen Sie Kontakt mit dem Systemadministrator auf.</p>');
define('kit_dialog_nl_account_active',								'<p>Sie sind bereits als Newsletter Abonnent registriert.</p><p><b><a href="%s">Bitte melden Sie sich an Ihrem Benutzerkonto an</a></b>, um Einstellungen zu prüfen und zu ändern.</p>');
define('kit_dialog_nl_no_newsletter_selected',				'<p>Bitte wählen Sie einen oder mehrere Newsletter aus, die Sie gerne abonnieren möchten.</p>');
define('kit_dialog_nl_no_account_for_unsubscribe',		'<p>Für die E-Mail Adresse <b>%s</b> existiert kein Konto und es ist auch kein Newsletter abonniert.</p>');
define('kit_dialog_nl_unsubscribe_intro',							'<p>Bitte wählen Sie die Newsletter aus, die Sie abbestellen möchten.</p><p>Wir senden Ihnen eine E-Mail mit einem Bestätigungslink zu.</p>');
define('kit_dialog_nl_no_newsletter_for_unsubscribe',	'<p>Für die E-Mail Adresse <b>%s</b> ist kein Newsletter abonniert!</p>');
define('kit_dialog_nl_unsubscribe_no_selection',			'<p>Sie haben keinen Newsletter ausgewählt, den Sie abbestellen möchten.</p>');
define('kit_dialog_nl_invalid_account',								'<p>Der Account mit der <b>ID %05d</b> wurde nicht gefunden!</p>');
define('kit_dialog_nl_unsubscribe_key_send',					'<p>Wir haben Ihnen einen Bestätigungslink an die E-Mail Adresse <b>%s</b> gesendet, bitte kontrollieren Sie Ihr Postfach.</p>');
define('kit_dialog_nl_error_mail_send',								'<p>Die E-Mail an die Adresse <b>%s</b> konnte leider nicht versendet werden.</p>');
?>