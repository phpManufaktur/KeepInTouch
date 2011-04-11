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
	
	private $field_assign = array(
		self::kit_title							=> dbKITcontact::field_person_title,
		self::kit_title_academic		=> dbKITcontact::field_person_title_academic,
		self::kit_first_name				=> dbKITcontact::field_person_first_name,
		self::kit_last_name					=> dbKITcontact::field_person_last_name,
		self::kit_company						=> dbKITcontact::field_company_name,
		self::kit_department				=> dbKITcontact::field_company_department,
		self::kit_street						=> dbKITcontactAddress::field_street,
		self::kit_zip								=> dbKITcontactAddress::field_zip,
		self::kit_city							=> dbKITcontactAddress::field_city
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
	
	private $error 				= ''; 
	
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    //$caller = next(debug_backtrace());
  	//$this->error = sprintf('[%s::%s - %s] %s', basename($caller['file']), $caller['function'], $caller['line'], $error);
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
	 * Return the person title array (Mr., Mrs., ...) for usage in kitForm
	 * @param REFERENCE ARRAY $title_array
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
	
	/**
	 * Return the person acadamic title array (Dr., Prof., ...) for usage in kitForm
	 * @param REFERENCE ARRAY $academic_array
	 * @return BOOL
	 */
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
	
	/**
	 * Return the address type array ((private, company, ...) for usage in kitForm 
	 * @param REFERENCE ARRAY $address_type_array
	 * @return BOOL
	 */
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
	
	/**
	 * Return the available newsletters as array for usage with kitForm
	 * @param REFERENCE ARRAY $newsletter_array
	 * @return BOOL
	 */
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
	
	/**
	 * Ueberprueft ob die E-Mail Adresse $email_address als primaere E-Mail Adresse
	 * registriert ist und gibt bei einem Treffer die KIT Contact ID sowie den Status
	 * des Adressdatensatz zurueck.
	 * @param STR $email_address
	 * @param REFERENCE INT $contact_id
	 * @param REFERENCE STR $status
	 * @return BOOL
	 */
	public function isEMailRegistered($email_address, &$contact_id=-1, &$status=dbKITcontact::status_active) {
		global $dbContact;
		$email_address = strtolower($email_address);
		$SQL = sprintf( "SELECT %s,%s,%s,%s FROM %s WHERE (%s LIKE '%%|%s' OR %s LIKE '%%|%s%%') AND %s!='%s'",
										dbKITcontact::field_id,
										dbKITcontact::field_email,
										dbKITcontact::field_email_standard,
										dbKITcontact::field_status,
										$dbContact->getTableName(),
										dbKITcontact::field_email,
										$email_address,
										dbKITcontact::field_email,
										$email_address,
										dbKITcontact::field_status,
										dbKITcontact::status_deleted);
		$result = array();
		if (!$dbContact->sqlExec($SQL, $result)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError())); return false;
		}
		if (count($result) > 0) {
			// Treffer
			foreach ($result as $address) {
				// nach Standard Adresse in den Treffern suchen
				$email_array = explode(';', $address[dbKITcontact::field_email]);
				$i=0;
				foreach ($email_array as $email_item) {
					list($email_type, $email_addr) = explode('|', $email_item);
					if (($email_address == $email_addr) && ($address[dbKITcontact::field_email_standard] == $i)) {
						$contact_id = $address[dbKITcontact::field_id];
						$status = $address[dbKITcontact::field_status];
						return true;
					} 
					$i++;
				}	
			}			
		}
		// kein Treffer
		return false;
	} // isEMailRegistered()
	
	public function updateContact($contact_id, &$contact_array=array()) {
		global $dbContact;
		global $dbContactAddress;
		if ($dbContact->getContactByID($contact_id, $contact)) { 
			// Adresse existiert, Daten vergleichen
			$address = $dbContactAddress->getFields();
			$address[dbKITcontactAddress::field_id] = -1;
			$address[dbKITcontactAddress::field_contact_id] = -1;
			if ($contact[dbKITcontact::field_address_standard] > 0) {
				if (!$dbContact->getAddressByID($contact[dbKITcontact::field_address_standard], $address) && $dbContact->isError()) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
					return false;
				}
			}
			$contact_changed = false;
			$address_changed = false;
			foreach ($this->field_array as $field => $label) {
				switch ($field):
				case self::kit_title:
				case self::kit_title_academic:
					if (isset($contact_array[$field]) && ($contact_array[$field] !== $contact[$this->field_assign[$field]])) {
						$contact[$this->field_assign[$field]] = $contact_array[$field];
						$contact_changed = true;
					}
					break;
				case self::kit_first_name:
				case self::kit_last_name:
				case self::kit_company:
				case self::kit_department:
					if (isset($contact_array[$field]) && !empty($contact_array[$field]) && ($contact_array[$field] !== $contact[$this->field_assign[$field]])) {
						$contact[$this->field_assign[$field]] = $contact_array[$field];
						$contact_changed = true;
					}
					break;
				case self::kit_street:
				case self::kit_city:
				case self::kit_zip:
					if (isset($contact_array[$field]) && !empty($contact_array[$field]) && ($contact_array[$field] !==  $address[$this->field_assign[$field]])) {
						$address[$this->field_assign[$field]] = $contact_array[$field];
						$address_changed = true;
					}
					break;
				case self::kit_phone:
				case self::kit_phone_mobile:
				case self::kit_fax:
					if (isset($contact_array[$field]) && !empty($contact_array[$field])) {
						$new_phone = trim($contact_array[$field]);
						$new_phone = str_replace(' ', '', $new_phone);
						if (strpos($contact[dbKITcontact::field_phone], $new_phone) === false) {
							// Telefonnummer existiert noch nicht, hinzufuegen
							switch ($field):
							case self::kit_phone: 
								$type = dbKITcontact::phone_phone; break;
							case self::kit_phone_mobile: 
								$type = dbKITcontact::phone_handy; break;
							case self::kit_fax: 
								$type = dbKITcontact::phone_fax; break;
							default : 
								$type = dbKITcontact::phone_phone; break;
							endswitch;
							$phone = $contact[dbKITcontact::field_phone];
							if (!empty($phone)) $phone .= ';';
							$phone .= sprintf('%s|%s', $type, $new_phone);
							$contact[dbKITcontact::field_phone] = $phone;
							$contact_changed = true;	
						}
					}
					break;
				case self::kit_newsletter:
					if (isset($contact_array[$field]) && !empty($contact_array[$field])) {
						$new_newsletters = explode(',', $contact_array[$field]);
						$newsletters = explode(',', $contact[dbKITcontact::field_newsletter]);
						foreach ($new_newsletters as $new_newsletter) {
							if (!in_array($new_newsletter, $newsletters)) {
								$newsletters[] = $new_newsletter;
								$contact_changed = true;
							}
						}
						$contact[dbKITcontact::field_newsletter] = implode(',', $newsletters);
					}
					break;
				endswitch;
			}
			if ($address_changed) {
				if ($address[dbKITcontactAddress::field_id] == -1) {
					// es existiert noch Adressdatensatz, Kontakt ID festhalten
					$address[dbKITcontactAddress::field_contact_id] = $contact_id;
					// Adresstyp festlegen
					$address[dbKITcontactAddress::field_type] = ((isset($contact_array[self::kit_address_type])) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private;
					// Adresse anlegen
					if (!$dbContactAddress->sqlInsertRecord($address, $address_id)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
						return false;
					}
					// Kontakt Datensatz aktualisieren
					$address_ids = explode(',', $contact[dbKITcontact::field_address]);
					$address_ids[] = $address_id;
					$contact[dbKITcontact::field_address] = implode(',', $address_ids);
					$contact[dbKITcontact::field_address_standard] = ($contact[dbKITcontact::field_address_standard] > 0) ? $contact[dbKITcontact::field_address_standard] : $address_id;
					$contact_changed = true;
					// Protokoll aktualisieren
					$dbContact->addSystemNotice($contact_id, sprintf(	kit_protocol_ki_address_added, 
																														$address[dbKITcontactAddress::field_street], 
																														$address[dbKITcontactAddress::field_zip], 
																														$address[dbKITcontactAddress::field_city]));
				}
				else {
					// Adressdatensatz aktualisieren
					// Adresstyp festlegen
					$address[dbKITcontactAddress::field_type] = ((isset($contact_array[self::kit_address_type])) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private;
					$where = array(dbKITcontactAddress::field_id => $address[dbKITcontactAddress::field_id]);
					if (!$dbContactAddress->sqlUpdateRecord($address, $where)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
						return false;
					}
					$dbContact->addSystemNotice($contact_id, sprintf( kit_protocol_ki_address_updated,
																														$address[dbKITcontactAddress::field_street],
																														$address[dbKITcontactAddress::field_zip],
																														$address[dbKITcontactAddress::field_city]));			
				}
			}
			if ($contact_changed) {
				$where = array(dbKITcontact::field_id => $contact_id);
				if (!$dbContact->sqlUpdateRecord($contact, $where)) {
					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
					return false;
				}
				$dbContact->addSystemNotice($contact_id, kit_protocol_ki_contact_updated);
			}
			return true;
		}
		elseif ($dbContact->isError()) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		else {
			// Adresse existiert nicht
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact_id)));
			return false;
		}
	} // updateContact()

	/**
	 * Fuegt einen neuen Kontakt Datensatz ein
	 * @param ARRAY $contact_array
	 * @param REFERENCE INT $contact_id
	 * @return BOOL
	 * @todo Die Laenderkennung wird momentan immer auf 'DE' gesetzt!
	 */
	public function addContact($contact_array=array(), &$contact_id=-1) {
		global $dbContact;
		global $dbContactAddress;
		
		if (!isset($contact_array[self::kit_email])) {
			// es wurde keine E-Mail Adresse uebergeben
			$this->setError('[%s - %s] %s', __METHOD__, __LINE__, kit_error_email_missing);
			return false;
		}
		$contact_email = strtolower($contact_array[self::kit_email]);
		if ($this->isEMailRegistered($contact_email, $contact_id)) {
			// es existiert bereits ein Datensatz fuer die angegebene E-Mail Adresse
			$this->setError('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_record_for_email_exists, $contact_id, $contact_email));
			return false;
		}
		
		// neuen Datensatz anlegen
		$contact = $dbContact->getFields();
		foreach ($contact as $key => $value) {
			switch ($key):
			case dbKITcontact::field_address:
			case dbKITcontact::field_address_standard:
				$contact[$key] = -1;
				break;
			case dbKITcontact::field_access:
				$contact[$key] = dbKITcontact::access_internal;
				break;
			case dbKITcontact::field_person_first_name:
			case dbKITcontact::field_person_last_name:
			case dbKITcontact::field_company_name:
			case dbKITcontact::field_company_department:
				$contact[$key] = (isset($contact_array[array_search($key, $this->field_assign)])) ? $contact_array[array_search($key, $this->field_assign)] : ''; 
				break;
			case dbKITcontact::field_company_title:
				$contact[$key] = dbKITcontact::company_title_none;
				break;
			case dbKITcontact::field_contact_identifier:
				$contact[$key] = $contact_email;
				break;
			case dbKITcontact::field_contact_since:
				$contact[$key] = date('Y-m-d H:i:s');
				break;
			case dbKITcontact::field_email:
				$type = (isset($contact_array[self::kit_address_type]) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontact::type_company : dbKITcontact::type_person;
				$contact[$key] = sprintf('%s|%s', $type, $contact_email);
				break;
			case dbKITcontact::field_email_standard:
				$contact[$key] = 0;
				break;
			case dbKITcontact::field_newsletter:
				$contact[$key] = (isset($contact_array[self::kit_newsletter])) ? $contact_array[self::kit_newsletter] : '';
				break;
			case dbKITcontact::field_person_title:
				$contact[$key] = (isset($contact_array[self::kit_title])) ? $contact_array[self::kit_title] : dbKITcontact::person_title_mister;
				break;
			case dbKITcontact::field_person_title_academic:
				$contact[$key] = (isset($contact_array[self::kit_title_academic])) ? $contact_array[self::kit_title_academic] : dbKITcontact::person_title_academic_none;
				break;
			case dbKITcontact::field_phone:
				$contact[$key] = '';
				$types = array(self::kit_phone => dbKITcontact::phone_phone, self::kit_phone_mobile => dbKITcontact::phone_handy, self::kit_fax => dbKITcontact::phone_fax);
				foreach ($types as $type => $kit_type) {
					if (isset($contact_array[$type]) && !empty($contact_array[$type])) {
						if (!empty($contact[$key])) $contact[$key] .= ';';
						$contact[$key] .= sprintf('%s|%s', $kit_type, $contact_array[$type]);
					}
				}
				$contact[dbKITcontact::field_phone_standard] = (!empty($contact[$key])) ? 0 : -1;
				break;
			case dbKITcontact::field_type:
				$contact[$key] = dbKITcontact::type_person;
				break;
			case dbKITcontact::field_status:
				$contact[$key] = dbKITcontact::status_active;
				break;
			default: 
				// nothing to do...
				break;
			endswitch;
		}
		
		$contact[dbKITcontact::field_update_by] = 'SYSTEM';
		$contact[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
		
		if (!$dbContact->sqlInsertRecord($contact, $contact_id)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		
		$dbContact->addSystemNotice($contact_id, kit_protocol_ki_contact_created);
		
		if (isset($contact_array[self::kit_city]) && !empty($contact_array[self::kit_city])) {
			// es muss zumindest die Stadt angegeben sein
			$address = array(
				dbKITcontactAddress::field_city 					=> isset($contact_array[self::kit_city]) ? $contact_array[self::kit_city] : '',
				dbKITcontactAddress::field_contact_id			=> $contact_id,
				dbKITcontactAddress::field_country				=> 'DE',
				dbKITcontactAddress::field_street					=> isset($contact_array[self::kit_street]) ? $contact_array[self::kit_street] : '',
				dbKITcontactAddress::field_zip						=> isset($contact_array[self::kit_zip]) ? $contact_array[self::kit_zip] : '',
				dbKITcontactAddress::field_type						=> (isset($contact_array[self::kit_address_type]) && ($contact_array[self::kit_address_type] == self::address_type_business)) ? dbKITcontactAddress::type_business : dbKITcontactAddress::type_private,
				dbKITcontactAddress::field_status					=> dbKITcontactAddress::status_active,
				dbKITcontactAddress::field_update_by			=> 'SYSTEM',
				dbKITcontactAddress::field_update_when		=> date('Y-m-d H:i:s')				
			);
			if (!$dbContactAddress->sqlInsertRecord($address, $address_id)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
				return false;
			}
			// Kontakt Datensatz aktualisieren
			$contact[dbKITcontact::field_address] = $address_id;
			$contact[dbKITcontact::field_address_standard] = $address_id;
			$contact[dbKITcontact::field_update_by] = 'SYSTEM';
			$contact[dbKITcontact::field_update_when] = date('Y-m-d H:i:s');
			$where = array(dbKITcontact::field_id => $contact_id);
			if (!$dbContact->sqlUpdateRecord($contact, $where))  {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
				return false;
			}
			$dbContact->addSystemNotice($contact_id, sprintf(	kit_protocol_ki_address_added, 
																												$address[dbKITcontactAddress::field_street],
																												$address[dbKITcontactAddress::field_zip],
																												$address[dbKITcontactAddress::field_city]));		
		}
		
		return true;
	} // insertContact()
	
	public function getContact($contact_id, &$contact_array=array()) {
		global $dbContact;
		global $dbContactAddress;
		
		$SQL = sprintf( "SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
										$dbContact->getTableName(),
										dbKITcontact::field_id,
										$contact_id,
										dbKITcontact::field_status,
										dbKITcontact::status_deleted);
		$contact = array();
		if (!$dbContact->sqlExec($SQL, $contact)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContact->getError()));
			return false;
		}
		if (count($contact) < 1) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact_id)));
			return false;
		}
		$contact = $contact[0];
		if ($contact[dbKITcontact::field_address_standard] > 0) {
			// Standard Adresse auslesen
			$where = array(dbKITcontactAddress::field_id => $contact[dbKITcontact::field_address_standard]);
			$address = array();
			if (!$dbContactAddress->sqlSelectRecord($where, $address)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbContactAddress->getError()));
				return false;
			}
			if (count($address) < 1) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(kit_error_invalid_id, $contact[dbKITcontact::field_address_standard])));
				return false;
			}
			$address = $address[0];
		}
		else {
			// kein Eintrag vorhanden, nur die leeren Felder setzen
			$address = $dbContactAddress->getFields();
		}
		// Interface Datenfelder durchlaufen und setzen
		$contact_array = array();
		foreach ($this->field_array as $kit_field => $label) {
			switch ($kit_field):
			case self::kit_title:
				$contact_array[$kit_field] = $dbContact->person_title_array[$contact[dbKITcontact::field_person_title]];
				break;
			case self::kit_title_academic:
				$contact_array[$kit_field] = $dbContact->person_title_academic_array[$contact[dbKITcontact::field_person_title_academic]];
				break;
			case self::kit_first_name:
			case self::kit_last_name:
			case self::kit_company:
			case self::kit_department:
				$contact_array[$kit_field] = $contact[$this->field_assign[$kit_field]];
				break;
			case self::kit_address_type:
				$contact_array[$kit_field] = ($contact[dbKITcontact::field_type] == dbKITcontact::type_person) ? $this->address_type_array[self::address_type_private] : $this->address_type_array[self::address_type_business];
				break;
			case self::kit_street:
			case self::kit_zip:
			case self::kit_city:
				$contact_array[$kit_field] = $address[$this->field_assign[$kit_field]];
				break;
			case self::kit_phone:
			case self::kit_phone_mobile:
			case self::kit_fax:
				$contact_array[$kit_field] = '';
				$phone_array = explode(';', $contact[dbKITcontact::field_phone]);
				$types = array(self::kit_phone => dbKITcontact::phone_phone, self::kit_phone_mobile => dbKITcontact::phone_handy, self::kit_fax => dbKITcontact::phone_fax);
				foreach ($phone_array as $phone) {
					if (strpos($phone, '|') !== false) {
						list($type, $number) = explode('|', $phone);
						if (array_search($type, $types) == $kit_field) {
							$contact_array[$kit_field] = $number;
						}
					}
				}
				break;
			case self::kit_email:
				$email_array = explode(';', $contact[dbKITcontact::field_email]);
				list($type, $email) = explode('|', $email_array[$contact[dbKITcontact::field_email_standard]]);
				$contact_array[$kit_field] = $email;
				break;
			case self::kit_newsletter:
				$news = array();
				$news_array = explode(',', $contact[dbKITcontact::field_newsletter]);
				foreach ($news_array as $nl) {
					if (!empty($nl)) $news[] = $dbContact->newsletter_array[$nl];
				}
				$contact_array[$kit_field] = $news;
				break;
			default:
				// nothing to do...
				break;
			endswitch;
		}
		return true;
	} // getContact()
	
} // class kitContactInterface

?>