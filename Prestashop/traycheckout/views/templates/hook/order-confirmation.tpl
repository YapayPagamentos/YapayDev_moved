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
*  @copyright  TrayCheckout
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


<script type="text/javascript">
	// <![CDATA[
		ThickboxI18nClose = "{l s='Fechar' mod='referralprogram'}";
		ThickboxI18nOrEscKey = "{l s='ou tecla Esc' mod='referralprogram'}";
		tb_pathToImage = "modules/traycheckout/icons/loadingAnimation.gif";
	//]]>
</script>

{if $status == 'ok'}

	<form   id="foo"  action="{$post_url}" method="post" class="thickbox">
	<table>
		<tr> 
			<td>
			<h3>{l s='Parabéns! Seu pedido foi gerado com sucesso.' mod='traycheckout'}</h3> <br />
			</td>
		</tr>
		<tr> 
			<td>
			<h4><p>{l s='O valor da sua compra é de:' mod='traycheckout'} <span class="price">{$total_paid}</span></p></h4>
			</td>
		</tr>
		<tr> 
			<td>
			<h4><p>{l s='Voce será redirecionado para TrayCheckout, caso não ocorra automaticamente utilize o botão Efetuar Pagamento.'  mod='traycheckout' }</p></h4>
			<br />
			</td>
		</tr>
	 	<tr> 
	 		<td align="right">
			{$PAYMENT_FIELDS}
			<br>
			{if $redirect}
				<input id="bt_redirect" type="submit" value="Efetuar Pagamento" class="exclusive_large" />
			{else}		
				<a id= "bt_modal" href="index.php?fc=module&module=traycheckout&controller=submit&id_cart=&&id_order={$id_order}&key={$secure_key}&keepThis=true&TB_iframe=true&height=600&width=1000"  class="exclusive_large thickbox">Efetuar Pagamento</a>
			{/if}
			<br />
			</td>
		</tr>
		<tr> 
			<td align="center">
			<br />
			<img src="modules/traycheckout/imagens/traycheckout_banner.png" alt="{l s='Pague com TrayCheckout' mod='traycheckout'}" />
			<br />
			</td>
		</tr>
	</table>  
	</form>
	
	{if $redirect}
		<script type="text/javascript">
			$(document).ready( function() {
				$("#bt_redirect").trigger("click");
			});
		</script>
	{else}
		<script type="text/javascript">
			$(document).ready(function() {
				$("#bt_modal").trigger("click");
				
			});
		</script>
	{/if}
	
{else}
	<p class="warning">
	{l s='Houve alguma falha no envio do seu pedido. Por Favor entre em contato com o nosso Suporte' mod='traycheckout'} 
	<a href="{$base_dir}contact-form.php">{l s='customer support' mod='traycheckout'}</a>.
	</p>
{/if}

