<?php
class Address{
	private $type_address;
	private $postal_code;
	private $street;
	private $number;
	private $neighborhood;
	private $completion;
	private $city;
	private $state;
	
	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param field_type $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return the $type_address
	 */
	public function getTypeAddress() {
		return $this->type_address;
	}

	/**
	 * @return the $postal_code
	 */
	public function getPostalCode() {
		return $this->postal_code;
	}

	/**
	 * @return the $street
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @return the $number
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @return the $neighborhood
	 */
	public function getNeighborhood() {
		return $this->neighborhood;
	}

	/**
	 * @return the $completion
	 */
	public function getCompletion() {
		return $this->completion;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param field_type $type_address
	 */
	public function setTypeAddress($type_address) {
		$this->type_address = $type_address;
	}

	/**
	 * @param field_type $postal_code
	 */
	public function setPostalCode($postal_code) {
		$this->postal_code = $postal_code;
	}

	/**
	 * @param field_type $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @param field_type $number
	 */
	public function setNumber($number) {
		$this->number = $number;
	}

	/**
	 * @param field_type $neighborhood
	 */
	public function setNeighborhood($neighborhood) {
		$this->neighborhood = $neighborhood;
	}

	/**
	 * @param field_type $completion
	 */
	public function setCompletion($completion) {
		$this->completion = $completion;
	}

	/**
	 * @param field_type $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	public function __construct($type_address, $postal_code, $street, $number,  $neighborhood, $completion, $city, $state) {
		$this->type_address= isset($type_address) ? $type_address : null;
		$this->postal_code= isset($postal_code) ? $postal_code : null;
		$this->street= isset($street) ? $street : null;
		$this->number= isset($number) ? $number : null;
		$this->neighborhood= isset($neighborhood) ? $neighborhood : null;
		$this->completion= isset($completion) ? $completion : null;
		$this->city= isset($city) ? $city : null;
		$this->state= isset($state) ? $state : null;
		
	}
		
}