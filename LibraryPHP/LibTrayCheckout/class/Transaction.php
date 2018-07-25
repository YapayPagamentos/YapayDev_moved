<?php
class Transaction{
	private $order_number;	
	private $shipping_type;		
	private $shipping_price;		
	private $price_discount;		
	private $price_additional;	
	private $total_paid;	
	private $url_notification;		
	private $free;		
	private $sub_store;		
	private $url_css;		
	private $url_success;	
	private $url_cancel;	
	private $url_process;

	public function __construct($order_number, $shipping_type, $shipping_price, $price_discount,  $price_additional, $total_paid, $url_notification) {
		$this->order_number= isset($order_number) ? $order_number : null;	
		$this->shipping_type= isset($shipping_type) ? $shipping_type : null;		
		$this->shipping_price= isset($shipping_price) ? $shipping_price : null;		
		$this->price_discount= isset($price_discount) ? $price_discount : null;		
		$this->price_additional= isset($price_additional) ? $price_additional : null;
		$this->total_paid	= isset($total_paid) ? $total_paid : null;		
		$this->url_notification= isset($url_notification) ? $url_notification : null;
	}
	/**
	 * @return the $total_paid
	 */
	public function getTotalPaid() {
		return $this->total_paid;
	}

	/**
	 * @param field_type $total_paid
	 */
	public function setTotalPaid($total_paid) {
		$this->total_paid = $total_paid;
	}

	/**
	 * @return the $order_number
	 */
	public function getOrderNumber() {
		return $this->order_number;
	}

	/**
	 * @return the $shipping_type
	 */
	public function getShippingType() {
		return $this->shipping_type;
	}

	/**
	 * @return the $shipping_price
	 */
	public function getShippingPrice() {
		return $this->shipping_price;
	}

	/**
	 * @return the $price_discount
	 */
	public function getPriceDiscount() {
		return $this->price_discount;
	}

	/**
	 * @return the $price_additional
	 */
	public function getPriceAdditional() {
		return $this->price_additional;
	}

	/**
	 * @return the $url_notification
	 */
	public function getUrlNotification() {
		return $this->url_notification;
	}

	/**
	 * @return the $free
	 */
	public function getFree() {
		return $this->free;
	}

	/**
	 * @return the $sub_store
	 */
	public function getSubStore() {
		return $this->sub_store;
	}

	/**
	 * @return the $url_css
	 */
	public function getUrlCss() {
		return $this->url_css;
	}

	/**
	 * @return the $url_success
	 */
	public function getUrlSuccess() {
		return $this->url_success;
	}

	/**
	 * @return the $url_cancel
	 */
	public function getUrlCancel() {
		return $this->url_cancel;
	}

	/**
	 * @return the $url_process
	 */
	public function getUrlProcess() {
		return $this->url_process;
	}

	/**
	 * @param field_type $order_number
	 */
	public function setOrderNumber($order_number) {
		$this->order_number = $order_number;
	}

	/**
	 * @param field_type $shipping_type
	 */
	public function setShippingType($shipping_type) {
		$this->shipping_type = $shipping_type;
	}

	/**
	 * @param field_type $shipping_price
	 */
	public function setShippingPrice($shipping_price) {
		$this->shipping_price = $shipping_price;
	}

	/**
	 * @param field_type $price_discount
	 */
	public function setPriceDiscount($price_discount) {
		$this->price_discount = $price_discount;
	}

	/**
	 * @param field_type $price_additional
	 */
	public function setPriceAdditional($price_additional) {
		$this->price_additional = $price_additional;
	}

	/**
	 * @param field_type $url_notification
	 */
	public function setUrlNotification($url_notification) {
		$this->url_notification = $url_notification;
	}

	/**
	 * @param field_type $free
	 */
	public function setFree($free) {
		$this->free = $free;
	}

	/**
	 * @param field_type $sub_store
	 */
	public function setSubStore($sub_store) {
		$this->sub_store = $sub_store;
	}

	/**
	 * @param field_type $url_css
	 */
	public function setUrl_css($url_css) {
		$this->url_css = $url_css;
	}

	/**
	 * @param field_type $url_success
	 */
	public function setUrlSuccess($url_success) {
		$this->url_success = $url_success;
	}

	/**
	 * @param field_type $url_cancel
	 */
	public function setUrlCancel($url_cancel) {
		$this->url_cancel = $url_cancel;
	}

	/**
	 * @param field_type $url_process
	 */
	public function setUrlProcess($url_process) {
		$this->url_process = $url_process;
	}

		
}