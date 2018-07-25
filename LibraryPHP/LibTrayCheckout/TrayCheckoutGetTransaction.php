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

require_once 'ConnectionRequest.php';
require_once 'TrayCheckoutService.php';
require_once 'Config.php';

class TrayCheckoutGetTransaction extends TrayCheckoutService{
	private $transaction_token;

	/**
	 * @return the $transaction_token
	 */
	public function __construct( $transaction_token ) {
		$this->transaction_token = $transaction_token ;
	}

	protected function process() {
		$xmlResponse= $this->curlRequestConnection ();
		return $this->setXmlResponse( $xmlResponse );
	}
	
	public function request(){
		$this->postFields ();
		$this->process ();
		return $this->xmlResponse;
	}
	
	protected function postFields() {
		$fields = array ("token" => trim ( $this->transaction_token )) ;
		return $this->setFields($fields);				
	}
	
	protected function getUrl($sandbox=false){
		return Config::getUrl('get_by_token');
	}
	
	public function getErrors() {
		$errorList = array();
		if(isset(simplexml_load_string ( $this->xmlResponse )->error_response)){
			if(isset(simplexml_load_string ( $this->xmlResponse )->error_response->errors))
				$errorList['general_errors']= simplexml_load_string ( $this->xmlResponse )->error_response->errors;
		}
		return $errorList;
	}
	
	protected function curlRequestConnection () {
		$url =  $this->getUrl();
		$fields = $this->getFields();
		$connection = new ConnectionRequest($url, $fields);
		return $connection->curlPost();
	}
}