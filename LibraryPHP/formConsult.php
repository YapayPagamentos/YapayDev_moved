<?php
header ( 'Content-Type: text / html; charset = UTF-8' );
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

?>
<html>
<head>
<style type="text/css">
div {
	background-color: #CCC;
}
</style>
</head>
<body>
<?php
//Dados de teste
$transaction_token = isset ( $_POST ['transaction'] ['transaction_token'] ) ? $_POST ['transaction'] ['transaction_token'] : 'ac05b8f75afd91317a6772079a2eefa9';
?>
<h1>Formulário que consulta uma transação através do Token recebido pelo
TrayCheckout</h1>

<div>
<p><b>Dados da consulta:</b></p>
<form name="form1" method="post" action="Example/consultTransaction.php">

<p><label>Token da transação </label><input type="text"
	name="transaction_token" value="<?php
	echo $transaction_token;
	?>"> Token de teste : ac05b8f75afd91317a6772079a2eefa9</p>
<p><label> <input type="submit" name="button" id="button" value="Enviar"></label></p>

</div>
</form>

</body>
</html>
