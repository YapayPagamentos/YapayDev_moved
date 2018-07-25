<?php
header ( 'Content-Type: text / html; charset = UTF-8' );
ini_set('display_errors', 1);

?>
<html>
<head>
<style type="text/css">
	div{ background-color: #CCC; }
	.error { color: #FF0000; }
	.suss{ color: #00DD00; }
</style>
</head>
<body>
<?php
 
if($_POST){
	require_once 'Create/CreateTransaction.php';
	$store = new CreateTransaction($_POST['order'], $_POST['payment']); //Pedido de sua loja
	$store->createTransaction(); 
	echo $store->html;
}

?>
</body>
</html>
