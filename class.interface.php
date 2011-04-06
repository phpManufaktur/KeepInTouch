<?php
/**
 * KeepInTouch (KIT)
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id$
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die('invalid call of '.$_SERVER['SCRIPT_NAME']);

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

global $kitContactInterface;

if (!is_object($kitContactInterface)) $kitContactInterface = new kitContactInterface();

class kitContactInterface {
	
	const kit_title								= 'kit_title';
	const kit_title_academic			= 'kit_title_academic';
	const kit_first_name					= 'kit_first_name';
	const kit_last_name						= 'kit_last_name';
	const kit_company							= 'kit_company';
	const kit_department					= 'kit_department';
	const kit_address_type				= 'kit_address_type';
	const kit_street							= 'kit_street';
	const kit_zip									= 'kit_zip';
	const kit_city								= 'kit_city';
	const kit_zip_city						= 'kit_zip_city';
	const kit_phone								= 'kit_phone';
	const kit_phone_mobile				= 'kit_phone_mobile';
	const kit_fax									= 'kit_fax';
	const kit_email								= 'kit_email';
	const kit_newsletter					= 'kit_newsletter';
	
	public $field_array = array(
		self::kit_title							=> kit_label_person_title,
		self::kit_title_academic		=> kit_label_person_title_academic,
		self::kit_first_name				=> kit_label_person_first_name,
		self::kit_last_name					=> kit_label_person_last_name,
		self::kit_company						=> kit_label_company_name,
		self::kit_department				=> kit_label_company_department,
		self::kit_address_type			=> kit_label_address_type,
		self::kit_street						=> kit_label_address_street,
		self::kit_zip								=> kit_label_address_zip,
		self::kit_city							=> kit_label_address_city,
		self::kit_zip_city					=> kit_label_address_zip_city,
		self::kit_phone							=> kit_label_contact_phone,
		self::kit_phone_mobile			=> kit_label_contact_phone_mobile,
		self::kit_fax								=> kit_label_contact_fax,
		self::kit_email							=> kit_label_contact_email,
		self::kit_newsletter				=> kit_label_newsletter
	);
	
	public $index_array = array(
		self::kit_title							=> 10,
		self::kit_title_academic		=> 11,
		self::kit_first_name				=> 12,
		self::kit_last_name					=> 13,
		self::kit_company						=> 14,
		self::kit_department				=> 15,
		self::kit_address_type			=> 16,
		self::kit_street						=> 17,
		self::kit_zip								=> 18,
		self::kit_city							=> 19,
		self::kit_zip_city					=> 20,
		self::kit_phone							=> 21,
		self::kit_phone_mobile			=> 22,
		self::kit_fax								=> 23,
		self::kit_email							=> 24,
		self::kit_newsletter				=> 25
	);
	
	const address_type_private		= 'private';
	const address_type_business		= 'business';
	
	public $address_type_array = array(
		self::address_type_private	=> kit_label_address_type_private,
		self::address_type_business	=> kit_label_address_type_business
	);
	
	/**
	 * Return the person title array (Mr., Mrs. ...) for usage in kitForm
	 * @param reference ARRAY $title_array
	 * @return BOOL
	 */
	public function getFormPersonTitleArray(&$title_array = array()) {
		global $dbContact;
		$titles = $dbContact->person_title_array;
		$title_array = array();
		foreach ($titles as $key => $value) {
			$title_array[] = array(
				'value'			=> $key,
				'text'			=> $value,
				'checked'		=> ($key == dbKITcontact::person_title_mister) ? 1 : 0
			);
		}
		return true;	
	} // getFormPersonTitleArray()
	
	public function getFormPersonTitleAcademicArray(&$academic_array = array()) {
		global $dbContact;
		$academics = $dbContact->person_title_academic_array;
		$academic_array = array();
		foreach ($academics as $key => $value) {
			$academic_array[] = array(
				'value'			=> $key,
				'text'			=> $value,
				'checked'		=> ($key == dbKITcontact::person_title_academic_none) ? 1 : 0
			);
		}
		return true;
	} // getFormPersonTitleAcademicArray()
	
	public function getFormAddressTypeArray(&$address_type_array = array()) {
		global $dbContact;
		$address_types = $this->address_type_array;
		$address_type_array = array();
		foreach ($address_types as $key => $value) {
			$address_type_array[] = array(
				'value'			=> $key,
				'text'			=> $value,
				'checked'		=> ($key == self::address_type_private) ? 1 : 0
			);
		}
		return true;
	} // getFormAddressTypeArray()
	
	public function getFormNewsletterArray(&$newsletter_array = array()) {
		global $dbContact;
		$newsletters = $dbContact->newsletter_array;
		$newsletter_array = array();
		foreach ($newsletters as $key => $value) {
			$newsletter_array[] = array(
				'value'		=> $key,
				'text'		=> $value,
				'checked'	=> 0
			);
		}
		return true;
	} // getFormNewsletterArray()
	
} // class kitContactInterface

?>