//:Display a dialog for subscribe and unsubscribe to newsletter(s)
//:Usage: [[kit_newsletter]]
/**
 * KeepInTouch, Newsletter Dialog
 * (c) 2010 by Ralf Hertsch
 * ralf.hertsch@phpmanufaktur.de - http://phpManufaktur.de
 *
 * $Id: kit_newsletter.php 35 2010-05-26 16:35:03Z ralf $
 */
if (file_exists(WB_PATH.'/modules/kit/class.dialogs.php')) {
  require_once(WB_PATH.'/modules/kit/class.dialogs.php');
  $dialog = new kitDialog(true);
  $prompt = $dialog->dialog_newsletter();
  return $prompt;
}
else {
  return "KeepInTouch (KIT) wurde nicht gefunden!";
}