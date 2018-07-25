<?php
class Customer{
	private $name;
	private $cpf;
	private $birth_date;
	private $gender;
	private $relationship;
	private $email;

	public function __construct($name, $cpf, $birth_date, $gender,  $relationship, $email) {
		$this->name= isset($name) ? $name : null;
		$this->cpf= isset($cpf) ? $cpf : null;
		$this->birth_date= isset($birth_date) ? $birth_date : null;
		$this->gender= isset($gender) ? $gender : null;
		$this->relationship= isset($relationship) ? $relationship : null;
		$this->email= isset($email) ? $email : null;
	}
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $cpf
	 */
	public function getCpf() {
		return $this->cpf;
	}

	/**
	 * @return the $birth_date
	 */
	public function getBirthDate() {
		return $this->birth_date;
	}

	/**
	 * @return the $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return the $relationship
	 */
	public function getRelationship() {
		return $this->relationship;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $cpf
	 */
	public function setCpf($cpf) {
		$this->cpf = $cpf;
	}

	/**
	 * @param field_type $birth_date
	 */
	public function setBirthDate($birth_date) {
		$this->birth_date = $birth_date;
	}

	/**
	 * @param field_type $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @param field_type $relationship
	 */
	public function setRelationship($relationship) {
		$this->relationship = $relationship;
	}

	/**
	 * @param field_type $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}		
}