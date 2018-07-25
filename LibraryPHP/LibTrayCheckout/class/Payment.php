<?php
class Payment {
	private $payment_method_id;
	private $split;
	private $card_name;
	private $card_number;
	private $card_expdate_month;
	private $card_expdate_year;
	private $card_cvv;
	
	public function __construct($payment_method_id, $split, $card="") {
		
		$this->payment_method_id = isset ( $payment_method_id ) ? $payment_method_id : null;
		$this->split = isset ( $split ) ? $split : null;
		$this->card_name = isset ( $card->card_name ) ? $card->card_name : null;
		$this->card_number = isset ( $card->card_number ) ? $card->card_number : null;
		$this->card_expdate_month = isset ( $card->card_expdate_month ) ? $card->card_expdate_month : null;
		$this->card_expdate_year = isset ( $card->card_expdate_year ) ? $card->card_expdate_year : null;
		$this->card_cvv = isset ( $card->card_cvv ) ? $card->card_cvv : null;
	
	}
	/**
	 * @return the $payment_method_id
	 */
	public function getPaymentMethodId() {
		return $this->payment_method_id;
	}

	/**
	 * @return the $split
	 */
	public function getSplit() {
		return $this->split;
	}

	/**
	 * @return the $card_name
	 */
	public function getCardName() {
		return $this->card_name;
	}

	/**
	 * @return the $card_number
	 */
	public function getCardNumber() {
		return $this->card_number;
	}

	/**
	 * @return the $card_expdate_month
	 */
	public function getCardExpdateMonth() {
		return $this->card_expdate_month;
	}

	/**
	 * @return the $card_expdate_year
	 */
	public function getCardExpdateYear() {
		return $this->card_expdate_year;
	}

	/**
	 * @return the $card_cvv
	 */
	public function getCardCvv() {
		return $this->card_cvv;
	}

	/**
	 * @param field_type $PaymentMethodId
	 */
	public function setPaymentMethodId($payment_method_id) {
		$this->payment_method_id = $payment_method_id;
	}

	/**
	 * @param field_type $split
	 */
	public function setSplit($split) {
		$this->split = $split;
	}

	/**
	 * @param field_type $card_name
	 */
	public function setCardName($card_name) {
		$this->card_name = $card_name;
	}

	/**
	 * @param field_type $card_number
	 */
	public function setCardNumber($card_number) {
		$this->card_number = $card_number;
	}

	/**
	 * @param field_type $card_expdate_month
	 */
	public function setCardExpdateMonth($card_expdate_month) {
		$this->card_expdate_month = $card_expdate_month;
	}

	/**
	 * @param field_type $card_expdate_year
	 */
	public function setCardExpdateYear($card_expdate_year) {
		$this->card_expdate_year = $card_expdate_year;
	}

	/**
	 * @param field_type $card_cvv
	 */
	public function setCardCvv($card_cvv) {
		$this->card_cvv = $card_cvv;
	}


}