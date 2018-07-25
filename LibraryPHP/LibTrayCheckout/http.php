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
class http{
	const HTTP_OK= 200;
	const HTTP_= 202;
	const BAD_REQUEST= 400; // Erro de dados
	const UNAUTHORIZED= 401; //Credenciais inválidas.
	const FORBIDDEN= 403; // Sem permissão para utilizar o serviço.
	const NOT_FOUND= 404; // Requisição inválida
	const INTERNAL_SERVER_ERROR= 500; // Erro de processamento

	
	public static function httpError( $msg, $code = self::BAD_REQUEST ) {
		header ( "HTTP/1.1 " . $code );
	}
}