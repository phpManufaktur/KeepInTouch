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
  
**/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');

if (DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

$kitRequest = new kitRequest();

global $dbCfg;
global $tools;
global $dbNewsletterPreview;
global $newsletterCommands;
global $dbNewsletterTemplates;

if (!is_object($dbCfg)) $dbCfg = new dbKITcfg();
if (!is_object($tools)) $tools = new kitTools();
if (!is_object($dbNewsletterPreview)) $dbNewsletterPreview = new dbKITnewsletterPreview();
if (!is_object($newsletterCommands)) $newsletterCommands = new kitNewsletterCommands();
if (!is_object($dbNewsletterTemplates)) $dbNewsletterTemplates = new dbKITnewsletterTemplates();

class kitRequest {
	
	const request_account_id				= 'aid';
	const request_action 						= 'act';
	const request_dialog						= 'dlg';
	const request_email							= 'aem';
	const request_id								= 'id';
	const request_key								= 'key';
	const request_language					= 'lg';
	const request_message						= 'msg';
	const request_newsletter				= 'nl';
	const request_type							= 'typ';
	
	const action_activate_key				= 'ack';
	const action_dialog							= 'dlg';
	const action_error							= 'err';
	const action_login							= 'login';
	const action_none								= 'no';
	const action_preview_newsletter	= 'pvn';
	const action_preview_template		= 'pvt';
	const action_type_html					= 'html';
	const action_type_text					= 'txt';
	const action_unsubscribe				= 'unsub';
	
	private $response_url			= '';
	private $error 						= '';
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError
	
  /**
   * Create the KIT Response Page (URL) - /pages/kit.php
   * 
   * @return BOOL
   */
	public function createResponseUrl() {
		global $dbCfg;
		
		require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.pages.php');
		$page_link = '/kit';
		$this->response_url = WB_URL.PAGES_DIRECTORY.$page_link.PAGE_EXTENSION;
		// pruefen, ob Seite bereits existiert
		$dbPages = new db_wb_pages();
		$where = array();
		$where[db_wb_pages::field_link] = $page_link;
		$pages = array();
		if (!$dbPages->sqlSelectRecord($where, $pages)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbPages->getError()));
			return false;
		}
		if (count($pages) > 0) { 
			// seite existiert
			$where = array();
			$where[dbKITcfg::field_name] = dbKITcfg::cfgKITResponsePage;
			$data = array();
			$data[dbKITcfg::field_value] = $page_link.PAGE_EXTENSION;
			$data[dbKITcfg::field_update_by] = 'SYSTEM';
			$data[dbKITcfg::field_update_when] = date('Y-m-d H:i:s');
			if (!$dbCfg->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
				return false;
			}
		}
		else {
			// Seite existiert noch nicht
			$handlePages = new handlePages();
			$page_id = $handlePages->createPage('kit', 0, 'code', 'hidden', array(1), array(1)); 
			if ($page_id === false) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $handlePages->getError()));
				return false;
			}
			// Konfiguration aktualisieren
			$this->response_url = str_replace(WB_URL.PAGES_DIRECTORY, '', $this->response_url);
			$where = array();
			$where[dbKITcfg::field_name] = dbKITcfg::cfgKITResponsePage;
			$data = array();
			$data[dbKITcfg::field_value] = $page_link.PAGE_EXTENSION;
			$data[dbKITcfg::field_update_by] = 'system';
			$data[dbKITcfg::field_update_when] = date('Y-m-d H:i:s');
			if (!$dbCfg->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
				return false;
			}
			// Code Section einfuegen
			$code = 'if (file_exists(WB_PATH.\'/modules/kit/class.response.php\')) {
  require_once(WB_PATH.\'/modules/kit/class.response.php\');
  $response = new kitResponse();
  $response->action();
}
else {
  echo \'Missing KeepInTouch (KIT)!\';
}';
			$dbCode = new db_wb_mod_code();
			$where = array();
			$where[db_wb_mod_code::field_page_id] = $page_id;
			$data = array();
			$data[db_wb_mod_code::field_content] = $code;
			if (!$dbCode->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCode->getError()));
				return false;
			}
		}
		return true;
	}
	
	public function getResponseUrl() {
		global $dbCfg;
		$this->response_url = $dbCfg->getValue(dbKITcfg::cfgKITResponsePage);
		// es existiert kein Eintrag, Standard Response Page anlegen...
		if (empty($this->response_url)) {
			if (!$this->createResponseUrl()) {
				echo sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError());
				exit();
			}
		}
		$url_array = explode(';', $this->response_url);
		// ggf. Eintraege bereinigen
		$update = false;
		$update_str = '';
		$urls = array();
		foreach ($url_array as $url) {
			if ($update_str != '') $update_str .= ';';
			if (strpos($url, WB_URL.PAGES_DIRECTORY) === 0) {
				$url = str_replace(WB_URL.PAGES_DIRECTORY, '', $url);
				$update = true;
			}
			$update_str .= $url;
			$urls[] = $url;
		}
		// Konfiguration aktualisieren
		if ($update) { 
			$where = array();
			$where[dbKITcfg::field_name] = dbKITcfg::cfgKITResponsePage;
			$data = array();
			$data[dbKITcfg::field_value] = $update_str;
			$data[dbKITcfg::field_update_by] = 'SYSTEM';
			$data[dbKITcfg::field_update_when] = date('Y-m-d H:i:s');
			if (!$dbCfg->sqlUpdateRecord($data, $where)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbCfg->getError()));
				return false;
			}
		}
		if (count($urls) > 1) {
			// enthaelt mehrere Eintraege
			if (isset($_REQUEST[kitRequest::request_language])) {
				// Sprachflag gesetzt
				$lang = '/'.strtolower($_REQUEST[kitRequest::request_language]).'/';
				foreach ($urls as $url) {
					if (stripos($url, $lang) === 0) {
						return WB_URL.PAGES_DIRECTORY.$url;
					}
				}
				// kein passender Eintrag, Standardseite waehlen
				return WB_URL.PAGES_DIRECTORY.$urls[0];
			}
			else {
				return WB_URL.PAGES_DIRECTORY.$urls[0];
			}
		}
		else {
			// nur ein Eintrag
			return WB_URL.PAGES_DIRECTORY.$urls[0];
		}
	} // getResponseUrl()
	
	public function getRequestLink($lang='') {
		global $dbCfg;
		
		if (empty($lang)) {
			$lang = strtolower(LANGUAGE);
		}
		else {
			$lang = strtolower($lang);
		}
		
		$request_link = $dbCfg->getValue(dbKITcfg::cfgKITRequestLink);
		if (empty($request_link) || !file_exists(str_replace(WB_URL, WB_PATH, $request_link))) {
			$request_link = WB_URL.'/modules/kit/kit.php';
			$dbCfg->setValueByName($request_link, dbKITcfg::cfgKITRequestLink);
		}
		
		return sprintf('%s?%s=%s', $request_link, kitRequest::request_language, $lang);
	} // getRequestLink
	
	/**
	 * REQUEST handler
	 */
	public function action() { 
		global $tools;
		
		isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = self::action_none;
		$this->response_url = $this->getResponseUrl();
		// get WebsiteBaker settings
		$wb_settings = array();
		$tools->getWBSettings($wb_settings);
		
		// IMPORTANT: Save all $_REQUESTs to $_SESSION to prevent data loss when switching to a new Location...
		$ignore_keys = array('__utma', '__utmz', $wb_settings['app_name'].'_session_id');
		foreach ($_REQUEST as $key => $value) {
			if (!in_array($key, $ignore_keys)) {
				$_SESSION['kit7543_'.$key] = $value;
			}
		}
		
		switch($action):
		case self::action_login:
		case self::action_activate_key:
		case self::action_unsubscribe:
		case self::action_dialog:
			// call dialog response
			header("Location: ".$this->response_url);
			exit();
		case self::action_preview_template:
			// Template Vorschau ausgeben
			$this->showTemplatePreview();
			break;
		case self::action_preview_newsletter:
			// Newsletter Vorschau ausgeben
			$this->showNewsletterPreview();
			break;
		case self::action_none:
			// keine Aktion angefordert
			$param_str = sprintf(	'?%s=%s&%s=%s',
														self::request_action,
														self::action_error,
														self::request_message,
														rawurlencode(kit_error_request_no_action));
			header("Location: ".$this->response_url.$param_str);
			exit();
		default:
			// ungueltige Aktion
			$param_str = sprintf( '?%s=%s&%s=%s',
														self::request_action,
														self::action_error,
														self::request_message,
														rawurlencode(sprintf(kit_error_request_invalid_action, self::request_action, $action)));
			header("Location: ".$this->response_url.$param_str);
			exit();
		endswitch;
		
	} // action()
	
	public function showNewsletterPreview() {
		require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
		global $dbNewsletterPreview;
		global $newsletterCommands;
		global $dbNewsletterTemplates;
		
		if (!isset($_REQUEST[self::request_type]) || !isset($_REQUEST[self::request_id])) {
			echo kit_error_request_parameter_incomplete;
			exit();
		}
		$where = array();
		$where[dbKITnewsletterPreview::field_id] = $_REQUEST[self::request_id];
		$preview = array();
		if (!$dbNewsletterPreview->sqlSelectRecord($where, $preview)) {
			 echo sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError());
			 return false;
		}
		if (count($preview) < 1) {
			// Datensatz nicht gefunden
			echo sprintf(kit_error_item_id, $_REQUEST[self::request_id]);
			return false;
		}
		$prev_array = explode(dbKITnewsletterPreview::array_separator, $preview[0][dbKITnewsletterPreview::field_view]);
		$prev = array();
		foreach ($prev_array as $item) {
			list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
			$prev[$key] = $value;
		}
		if ($_REQUEST[self::request_type] == self::action_type_html) {
			$content = $prev[dbKITnewsletterArchive::field_html];
		}
		else {
			$content = $prev[dbKITnewsletterArchive::field_text];
		}
		// Template auslesen
		$where = array();
		$where[dbKITnewsletterTemplates::field_id] = $prev[dbKITnewsletterArchive::field_template];
		$template = array();
		if (!$dbNewsletterTemplates->sqlSelectRecord($where, $template)) {
			echo sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterTemplates->getError());
			return false;
		}
		if (count($template) < 1) {
			echo sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_item_id, $prev[dbKITnewsletterArchive::field_template]));
			return false;
		}
		$template = $template[0];
		
		if ($newsletterCommands->parseCommands($content, '', -1)) {
			if ($_REQUEST[self::request_type] == self::action_type_text) {
				// NUR TEXT
				header("Cache-Control: no-cache, must-revalidate");
				header('content-type:text/plain; charset=utf-8');
				$tpl = $template[dbKITnewsletterTemplates::field_text];
				if ($newsletterCommands->parseCommands($tpl, $content, -1)) {
					echo $tpl;
				}
				else {
					echo $newsletterCommands->getError();					
				}
			}
			else {
				// HTML
				header("Cache-Control: no-cache, must-revalidate");
				header('content-type:text/html; charset=utf-8');
				$tpl = $template[dbKITnewsletterTemplates::field_html];
				if ($newsletterCommands->parseCommands($tpl, $content, -1)) {
					//echo utf8_encode($tpl);
					echo $tpl;
				}
				else {
					echo $newsletterCommands->getError();
				}
			}				
		}
		else {
			echo $newsletterCommands->getError();
		}	
	} // showNewsletterPreview()
	
	public function showTemplatePreview() {
		require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.newsletter.php');
		global $dbNewsletterPreview;
		global $newsletterCommands;
		
		if (!isset($_REQUEST[self::request_type]) || !isset($_REQUEST[self::request_id])) {
			echo kit_error_request_parameter_incomplete;
			break;
		}
		$where = array();
		$where[dbKITnewsletterPreview::field_id] = $_REQUEST[self::request_id];
		$preview = array();
		if (!$dbNewsletterPreview->sqlSelectRecord($where, $preview)) {
			 echo sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbNewsletterPreview->getError());
			 break;
		}
		if (count($preview) < 1) {
			// Datensatz nicht gefunden
			echo sprintf(kit_error_item_id, $_REQUEST[self::request_id]);
			break;
		}
		$prev_array = explode(dbKITnewsletterPreview::array_separator, $preview[0][dbKITnewsletterPreview::field_view]);
		$prev = array();
		foreach ($prev_array as $item) {
			list($key, $value) = explode(dbKITnewsletterPreview::array_separator_value, $item);
			$prev[$key] = $value;
		}
		$template = '';
		if ($_REQUEST[self::request_type] == self::action_type_html) {
			$template = stripcslashes($prev[dbKITnewsletterTemplates::field_html]);
		}
		else {
			$template = $prev[dbKITnewsletterTemplates::field_text];
		}
		if ($newsletterCommands->parseCommands($template, '', -1)) {
			if ($_REQUEST[self::request_type] == self::action_type_text) {
				// NUR TEXT
				header("Cache-Control: no-cache, must-revalidate");
				header('content-type:text/plain; charset=utf-8');
				echo strip_tags($template);					
			}
			else {
				// HTML
				header("Cache-Control: no-cache, must-revalidate");
				header('content-type:text/html; charset=utf-8');
				echo $template;
			}				
		}
		else {
			echo $newsletterCommands->getError();
		}
	} // showTemplatePreview()
	
} // class kitRequest

?>