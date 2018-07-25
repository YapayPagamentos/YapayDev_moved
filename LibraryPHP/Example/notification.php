<?php
ini_set('display_errors', 1);
header ( 'Content-Type: text / html; charset = UTF-8' );
error_reporting ( E_ALL ); 
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
require_once '../LibTrayCheckout/http.php';
require_once 'Notification/OrderNotification.php';

if ($_POST) {
	/* Campos enviados automaticamente pelo sistema TrayCheckout */
	$transaction_token = $_POST ['transaction'] ['transaction_token'];
	$order_number = $_POST ['transaction'] ['order_number'];
	
	try {
		// Classe que utiliza a API de notificação TrayCheckout
		$store = new OrderNotification ( $transaction_token, $order_number );
		if (! $store->notification ()) {
			http::httpError ( "Falha na notificacao" );
			$store->setErrorsHtml ();
		}
		$store->setXmlHtml ();
		
		echo "<html>
			<head><style type='text/css'> div{ background-color: #CCC; } </style></head>
			<body>";
		echo $store->html;
		echo "</body></html>";
	
	} catch ( Exception $e ) {
		http::httpError ( $e );
	}
	
} else {
	http::httpError ( "Post invalido" );
}

