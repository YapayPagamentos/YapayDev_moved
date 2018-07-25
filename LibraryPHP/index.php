<?php
header ( 'Content-Type: text / html; charset = UTF-8' );
?>
<html>
<head>
<style type="text/css">
	div{ background-color: #CCC; }
	.error { color: #FF0000; }
	.suss{ color: #0000FF; }
</style>
</head>
<body>
	<h1>Exemplos de integração via API com TrayCheckout</h1>

	<p><a href="formCreateTransaction.php"> Criar transação no TrayCheckout</a></p>
	<p><a href="formNotification.php"> Simular atualização de status realizada pelo sistema TrayCheckout</a></p>
	 <p><a href="formConsult.php"> Consultar transação através do Token da Transação</a></p>
	 
	 
	 <hr>
<p><label><b>Informações importantes</b></label></p>
<ol>
	<li>
	<p><label>Por padrão é utilizado o ambiente de Sandbox nas requisições.</label></p>
	</li>
	<li>
	<p><label>Para criar uma conta de testes no Sandbox do TrayCheckout,
	acesse: http://sandbox.traycheckout.com.br/.</label></p>
	</li>
	<li>
	<p><label>O token configurado na biblioteca PHP é de uma loja de testes TrayCheckout, para configurar a API com sua loja substitua a linha "public static $TOKEN = 'TOKEN_DA_SUA_LOJA';" com o token de sua loja do arquivo "Config.php".</label></p>	</li>

	<li>
	<p><label>Após finalizar os testes em Sandbox, será necessário apontar
	para as URLs do ambiente de produção TrayCheckout.</label></p>
	</li>
	<li>
	<p><label>Para isso, abra o arquivo Config.php (/LibTrayCheckout/Config.php) da biblioteca PHP e altere o define "TC_TEST" para "false".</label></p>	</li>
</ol>
</body>
</html>