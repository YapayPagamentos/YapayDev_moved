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
define('TC_TEST', TRUE);
class Config{
	public static $TOKEN = '8bfe5ddcb77207b';
	
	public static $URL_SANDBOX= array('create_transaction'=> 'https://api.sandbox.traycheckout.com.br/v2/transactions/pay_complete',
							  'get_by_token'=> 'http://api.sandbox.checkout.tray.com.br/api/v1/transactions/get_by_token');
	
	public static $URL_PRODUCTION= array('create_transaction'=> 'https://api.traycheckout.com.br/v2/transactions/pay_complete',
							  'get_by_token'=> 'http://api.checkout.tray.com.br/api/v1/transactions/get_by_token');
	
	public static function getUrl ($key){
		if(TC_TEST){
			return self::$URL_SANDBOX[$key];
		}else{
			return self::$URL_PRODUCTION[$key];
		}
	}
}