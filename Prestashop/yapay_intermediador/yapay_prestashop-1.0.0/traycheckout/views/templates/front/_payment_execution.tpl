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
{capture name=path}{l s='Pagamento via Yapay Intermediador' mod='traycheckout'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Resumo da compra' mod='traycheckout'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}
		<script type="text/javascript">
 <![CDATA[
	ThickboxI18nClose = "{l s='Close' mod='referralprogram'}";
	ThickboxI18nOrEscKey = "{l s='or Esc key' mod='referralprogram'}";
	tb_pathToImage = "{$img_ps_dir}loadingAnimation.gif";
	]]>
</script>

<a class="fancybox" rel="group" href="big_image_1.jpg"><img src="small_image_1.jpg" alt="" /></a>
<a class="fancybox" rel="group" href="big_image_2.jpg"><img src="small_image_2.jpg" alt="" /></a>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Seu carrinho de compras está vazio.'}</p>
{else}

<h3>{l s='Pagamento via Yapay Intermediador' mod='traycheckout'}</h3>
<form action="{$link->getModuleLink('traycheckout', 'validation', [], true)}" method="post">
	<p>
		<img src="http://ifredi.commerce.dev.tray.intranet/prestashop/modules/traycheckout/imagens/comprar_traycheckout.png" alt="{l s='traycheckout' mod='traycheckout'}"  style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='Você escolheu efetuar o pagamento via Yapay Intermediador' mod='traycheckout'}
		<br/><br />
		{l s='Breve resumo da sua compra:' mod='traycheckout'}
	</p>
	<p style="margin-top:20px;">
		- {l s='O valor total de sua compra é ' mod='traycheckout'}
		<span id="amount" class="price">{displayPrice price=$total}</span>
		{if $use_taxes == 1}
			{l s='(tax incl.)' mod='traycheckout'}
		{/if}
	</p>
	<p>
		{l s='Aceitamos a seguinte moeda para efetuar seu pagamento via Yapay Intermediador: ' mod='traycheckout'}&nbsp;<b>{$currencies.0.name}</b>
                <input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}" />
	</p>
	<p>
		<br /><br />
		<b>{l s='Por favor, confirme sua compra clicando no botão \'Confirmo minha compra\'' mod='traycheckout'}.</b>
	</p>
	<p class="cart_navigation">
		<input type="submit" name="submit" value="{l s='Confirmo minha compra' mod='traycheckout'}" class="exclusive_large" />
		<a href="{$link->getPageLink('order', true, NULL, "step=3")}" class="button_large">{l s='Outros formas de pagamento' mod='traycheckout'}</a>
	</p>
</form>
{/if}
