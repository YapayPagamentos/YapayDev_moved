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
<h1>Formulário que simula a criação de Transação via API TrayCheckout</h1>
<div>
<p><b>Exemplo de dados para teste: </b></p>
<p><b>Número do pedido: </b>ped_01</p>
<p><b>Forma de pagamento: </b>Pagamento com Boleto</p>
<form action="Example/createTransaction.php" method="POST">
		<?php
		$order = isset ( $_POST ['order'] ) ? $_POST ['order'] : 'ped_14';
		?>
		Pedido: <input type="text" name="order" value="<?php
		echo $order;
		?>"><br>
Forma de pagamento: <select name="payment">
	<option value='boleto'
		<?php
		if ($_POST ['payment'] == 'boleto')
			echo "selected";
		?>>Pagamento
	com Boleto</option>
	<option value='transf'
		<?php
		if ($_POST ['payment'] == 'transf')
			echo "selected";
		?>>Pagamento
	com Transferência</option>
	<option value='card'
		<?php
		if ($_POST ['payment'] == 'card')
			echo "selected";
		?>>Pagamento com
	Cartão</option>
</select> <input type="submit" name='Enviar' value="Enviar"></form>
</div>



</body>
</html>