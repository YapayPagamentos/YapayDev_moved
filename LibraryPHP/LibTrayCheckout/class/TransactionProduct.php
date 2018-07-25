<?php
class TransactionProduct{
	private $code;
	private $quantity;
	private $price_unit;
	private $description;
	private $url_img;
	private $sku_code;
	private $extra;
	
	public function __construct($code, $quantity, $price_unit, $description,  $url_img, $sku_code, $extra) {
		$this->code = isset($code) ? $code : null;
		$this->quantity = isset($quantity) ? $quantity: null;
		$this->price_unit = isset($price_unit) ? $price_unit: null;
		$this->description = isset($description) ? $description: null;
		$this->url_img = isset($url_img) ? $url_img: null;
		$this->sku_code = isset($sku_code) ? $sku_code: null;
		$this->extra = isset($extra) ? $extra: null;
	}
	/**
	 * @return the $code
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return the $quantity
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @return the $price_unit
	 */
	public function getPriceUnit() {
		return $this->price_unit;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $url_img
	 */
	public function getUrl_img() {
		return $this->url_img;
	}

	/**
	 * @return the $sku_code
	 */
	public function getSku_code() {
		return $this->sku_code;
	}

	/**
	 * @return the $extra
	 */
	public function getExtra() {
		return $this->extra;
	}

	/**
	 * @param field_type $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @param field_type $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	 * @param field_type $price_unit
	 */
	public function setPriceUnit($price_unit) {
		$this->price_unit = $price_unit;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param field_type $url_img
	 */
	public function setUrl_img($url_img) {
		$this->url_img = $url_img;
	}

	/**
	 * @param field_type $sku_code
	 */
	public function setSku_code($sku_code) {
		$this->sku_code = $sku_code;
	}

	/**
	 * @param field_type $extra
	 */
	public function setExtra($extra) {
		$this->extra = $extra;
	}

	
}