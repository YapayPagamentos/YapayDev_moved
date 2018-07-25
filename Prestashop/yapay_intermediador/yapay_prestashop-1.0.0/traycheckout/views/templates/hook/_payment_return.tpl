{*
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
*  @author Igor Cicotoste <ifredi@tray.net.br>
*  @copyright  Yapay
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
	// <![CDATA[
		ThickboxI18nClose = "{l s='Close' mod='referralprogram'}";
		ThickboxI18nOrEscKey = "{l s='or Esc key' mod='referralprogram'}";
		tb_pathToImage = "modules/traycheckout/icons/loadingAnimation.gif";
	//]]>
</script>

{if $status == 'ok'}

	<h3>{l s='Parabéns! Seu pedido foi gerado com sucesso.' mod='traycheckout'}</h3> <br />
	<p>{l s='O valor da sua compra é de:' mod='traycheckout'} <span class="price">{$total_paid}</span></p>
	<p>{l s='Voce será redirecionado para Yapay Intermediador, caso não ocorra automaticamente utilize o botão Efetuar Pagamento.'  mod='traycheckout' }.</p>
	<br />
	 
	<form   id="foo"  action="{$post_url}" method="post">
		{$PAYMENT_FIELDS}

  
<!--		<a id="loading" href="modules/traycheckout/icons/loadingAnimation.gif?keepThis=true&TB_iframe=true&height=500&width=1000" title="Redirecionando para Yapay Intermediador" class="thickbox">-->
<!--		<img src="modules/traycheckout/icons/loadingAnimation.gif" alt="Single Image"/></a>-->
	
		<br>
		<a id= "bt1" href="index.php?fc=module&module=traycheckout&controller=submit&id_lang=2&id_cart=100&id_module=98&id_order=100&key=a0be7dd167a1e7af71ba80f1eaa1197b&keepThis=true&TB_iframe=true&height=500&width=1000"  class="exclusive_large thickbox">Efetuar Pagamento</a>  
	</form>
	<br> 
	<br>
	<center>
		&nbsp;<img src="modules/traycheckout/imagens/traycheckout_banner.png" alt="{l s='Pague com Yapay Intermediador' mod='traycheckout'}" />
	</center>
	
	<script type="text/javascript">
		$(document).ready(function() {
			//$("#loading").trigger("click");
			$("#bt1").trigger("click");
			
		});
	</script>
	{else}
	<p class="warning">
	{l s='Houve alguma falha no envio do seu pedido. Por Favor entre em contato com o nosso Suporte' mod='traycheckout'} 
	<a href="{$base_dir}contact-form.php">{l s='customer support' mod='traycheckout'}</a>.
	</p>
{/if}
