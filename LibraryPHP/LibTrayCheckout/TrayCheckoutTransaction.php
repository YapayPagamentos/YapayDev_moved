<?php
/*
* 2013 Tray
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Igor Cicotoste <integracao@traycheckout.com.br>
*  @copyright  TrayCheckout
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/
include 'class/TransactionProduct.php';
include 'class/Address.php';
include 'class/Customer.php';
include 'class/Transaction.php';
include 'class/Payment.php';
include_once 'Config.php';
require_once 'ConnectionRequest.php';
require_once 'TrayCheckoutService.php';


class TrayCheckoutTransaction extends TrayCheckoutService
{
    protected $products;
    protected $addresses;
    protected $contacts;
    protected $customer;
    protected $transaction;
    protected $payment;
	
	public function __construct() {
	}

	public function addProduct($code, $quantity = null, $price_unit = null, $description = null, $url_img = null, $sku_code = null, $extra = null) {
		if ($this->products == null) {
			$this->products = Array ();
		}
		array_push ( $this->products, new TransactionProduct ( $code, $quantity, $price_unit, $description, $url_img, $sku_code, $extra ) );
	}
	
	public function addAddress($type_address, $postal_code, $street, $number, $neighborhood, $completion, $city, $state) {
		if ($this->addresses == null) {
			$this->addresses = Array ();
		}
		array_push ( $this->addresses, new Address ( $type_address, $postal_code, $street, $number, $neighborhood, $completion, $city, $state ) );
	}
	
	public function addContact($type_contact = "H", $number_contact) {
		$contact = new stdClass ();
		$contact->type_contact = $type_contact;
		$contact->number_contact = $number_contact;
		
		if ($this->contacts == null) {
			$this->contacts = Array ();
		}
		array_push ( $this->contacts, $contact );
	}
	
	public function setCustomer($name, $cpf, $birth_date, $gender, $relationship, $email) {
		$this->customer = new Customer ( $name, $cpf, $birth_date, $gender, $relationship, $email);
	}
	
	public function setTransaction($order_number, $shipping_type, $shipping_price, $price_discount, $price_additional, $total_paid, $url_notification) {
		$this->transaction = new Transaction ( $order_number, $shipping_type, $shipping_price, $price_discount, $price_additional, $total_paid, $url_notification );
	
	}
	
	public function setPayment($payment_method_id, $split = 1, $card = "") {
		$this->payment = new Payment ( $payment_method_id, $split, $card);
	}
	
	
	public function getTotalPaid() {
		$total_paid = 0;
		if (isset ( $this->products )) {
			foreach ( $this->products as $transactionProduct ) {
				$quantity = $transactionProduct->getQuantity ();
				$price_unit = $transactionProduct->getPriceUnit ();
				$total_paid = number_format ( $total_paid, 2, '.', '' ) + ($quantity * number_format ( $price_unit, 2, '.', '' ));
			}
		}
		return $total_paid;
	}
    
	protected function postFields() {
		$fields= array(
			'token_account' 	=> Config::$TOKEN,
			'transaction[order_number]' 	=> $this->transaction->getOrderNumber(),
			'transaction[price_discount]' 	=> number_format( $this->transaction->getPriceDiscount(), 2, '.', ''),
			'transaction[price_additional]' 	=> number_format( $this->transaction->getPriceAdditional(), 2, '.', ''),
			'transaction[shipping_price]' 	=> number_format( $this->transaction->getShippingPrice(), 2, '.', ''),
			'transaction[shipping_type]' 	=> $this->transaction->getShippingPrice(),		
			'transaction[url_notification]' 	=> $this->transaction->getUrlNotification()
		);
		
		/** OS DADOS CLIENTE **/
		$fields['customer[name]'] 	= $this->customer->getName();
		$fields['customer[cpf]'] 	= $this->customer->getCpf();
		$fields['customer[email]'] 	= $this->customer->getEmail();
		
		/** DADOS DE ENDEREÇO **/
		foreach($this->addresses as $key => $address) {
			   $fields['customer[addresses]['.$key.'][type_address]'] = $address->getTypeAddress();
			   $fields['customer[addresses]['.$key.'][postal_code]']	= $address->getPostalCode();
			   $fields['customer[addresses]['.$key.'][street]'] 		= $address->getStreet();
			   $fields['customer[addresses]['.$key.'][number]']		= $address->getNumber();
			   $fields['customer[addresses]['.$key.'][completion]']	= $address->getCompletion();
			   $fields['customer[addresses]['.$key.'][neighborhood]'] = $address->getNeighborhood();
			   $fields['customer[addresses]['.$key.'][city]'] 		= $address->getCity();
			   $fields['customer[addresses]['.$key.'][state]']		= $address->getState();
		}
		
		foreach ( $this->contacts as $key => $contact ) {
			$fields ['customer[contacts]['.$key.'][type_contact]'] 	= $contact->type_contact;
			$fields ['customer[contacts]['.$key.'][number_contact]'] 	= $contact->number_contact;
		}
		
		/** ADICIONA OS PRODUTOS AO ARRAY **/
		foreach($this->products as $key => $prod) {
			$fields['transaction_product['.$key.'][code]']        = $prod->getCode(); 
			$fields['transaction_product['.$key.'][description]'] = $prod->getDescription();
			$fields['transaction_product['.$key.'][quantity]'] 	 = $prod->getQuantity();
			$fields['transaction_product['.$key.'][price_unit]']  = $prod->getPriceUnit();
		}
		
		$fields['payment[payment_method_id]'] 	= $this->payment->getPaymentMethodId();
	    $fields['payment[split]'] 				= $this->payment->getSplit();
	    $fields['payment[card_name]'] 			= $this->payment->getCardName();
	    $fields['payment[card_number]'] 			= $this->payment->getCardNumber();
	    $fields['payment[card_expdate_month]']	= $this->payment->getCardExpdateMonth();
	    $fields['payment[card_expdate_year]'] 	= $this->payment->getCardExpdateYear();
	    $fields['payment[card_cvv]'] 			= $this->payment->getCardCvv();
	    $this->setFields($fields);
		return;
    }
	
	protected function process() {
		$response = $this->curlRequestConnection();
		return $this->setXmlResponse( $response );
	}
	
	public function request(){
		$this->postFields ();
		$this->process ();
		$sucess = ( !$this->errorResponse() ) ? true : false;
		return $sucess;
	}
	
	protected function curlRequestConnection() {
		$url =  $this->getUrl();
		$fields = $this->getFields();
		$connection = new ConnectionRequest($url, $fields);
		return $connection->curlPost();
	}

	protected function getUrl() {
		return Config::getUrl('create_transaction');
	}

	
	public function getErrors() {
		$errorList = array();
		if(isset(simplexml_load_string ( $this->xmlResponse )->error_response)){
			if(isset(simplexml_load_string ( $this->xmlResponse )->error_response->general_errors->general_error))
				$errorList['general_errors']= simplexml_load_string ( $this->xmlResponse )->error_response->general_errors->general_error;

			if(isset(simplexml_load_string ( $this->xmlResponse )->error_response->validation_errors->validation_error))
				$errorList['validation_errors']= simplexml_load_string ( $this->xmlResponse )->error_response->validation_errors->validation_error;
		}
		return $errorList;
	}

	public function getPaymentResponse() {
		$payment = new stdClass();
		$payment_response = simplexml_load_string ( $this->xmlResponse )->data_response->transaction->payment;
		
		$payment->payment_method_id = $payment_response->payment_method_id;
		$payment->payment_method_name = $payment_response->payment_method_name;
		$payment->price_payment = $payment_response->price_payment;
		$payment->split = $payment_response->split;
		
		if(in_array($payment_response->payment_method_id , array(6, 7, 17)) ){ //Boleto - Itaushopline - HSBC
			if($payment_response->payment_method_id == 6) 
				$payment->linha_digitavel=  $payment_response->linha_digitavel;
			$payment->url_payment = $payment_response->url_payment;
		}else{
			$payment->tid= $payment_response->tid;
		}
		return $payment;
	}

	public function getTransactionResponse() {
		$transaction = new stdClass();
		$response_transaction = simplexml_load_string ( $this->xmlResponse )->data_response->transaction;
		$transaction->transaction_id = $response_transaction ->transaction_id;
		$transaction->token_transaction = $response_transaction ->token_transaction;
		$transaction->status_id = $response_transaction ->status_id;
		$transaction->status_name = $response_transaction ->status_name;
		return $transaction;
	}
}
?>