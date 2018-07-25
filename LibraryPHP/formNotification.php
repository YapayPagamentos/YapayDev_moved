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
$order_number = isset ( $_POST ['transaction'] ['order_number'] ) ? $_POST ['transaction'] ['order_number'] : 29;
?>
<h1>Formulário que simula a rotina de notificação automática de status
realizada pelo sistema TrayCheckout</h1>


<div>
<p><b>Dados de notificação:</b></p>
<form name="form1" method="post" action="Example/notification.php">

<p><label>Token da transação </label> <input type="text"
	name="transaction[transaction_token]"
	id="transaction[transaction_token]"
	value="<?php
	echo $transaction_token;
	?>"> Token de teste : ac05b8f75afd91317a6772079a2eefa9</p>
<p><label>Numero do pedido </label><input type="text"
	name="transaction[order_number]" id="transaction[order_number]"
	value="<?php
	echo $order_number;
	?>"> Pedido teste: 29</p>
<p><label> <input type="submit" name="button" id="button" value="Enviar"></label></p>

</form>
</div>

</body>
</html>
