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



	{capture name=path}{l s='Confirmação de pagamento' mod='traycheckout'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}

	<h3>{l s='Confirmação de pagamento' mod='traycheckout'}</h3>

<div class="paiement_block">
<div id="order-detail-content" class="table_block">

	<p><h3>
	{if $action=='sucess'}
	{l s='Seu pedido no' mod='traycheckout'} <span class="bold">{$shop_name}</span> {l s='está completo.' mod='traycheckout'}
	{else}
	{l s='Seu pedido no' mod='traycheckout'} <span class="bold">{$shop_name}</span> {l s='está sendo processado.' mod='traycheckout'}
	{/if}
	</h3>

	{if $order}
		<h4>{l s='Detalhes do pedido' mod='traycheckout'}</h4>
		<br />
		<table id="cart_summary" class="std">
	
			<tfoot>
				<tr class="cart_total_price">
					<td colspan="1">{l s='Total da transação:' mod='traycheckout'}</td>
					<td ><span class="bold">{$total_paid}</span></td>
				</tr>
				<tr class="cart_total_price">
					<td >{l s='ID do seu pedido:' mod='traycheckout'}</td>
					<td ><span class="bold">{$order_number}</span></td>
				</tr>			
				<tr class="cart_total_price">
					<td >{l s='Código do pedido:' mod='traycheckout'}</td>
					<td ><span class="bold">{$reference}</span></td>
				</tr>			
						
			</tfoot>
		</table>
	
	{/if}
	<br /><h4>{l s='Meio de pagamento escolhido: TrayCheckout.' mod='traycheckout'}</h3>
	<br />
	<img src="modules/traycheckout/imagens/cartoesblog.png" width='480' height='150' ="Single Image"/> <br>
<br />
	{if $is_guest}
		<a href="{$link->getPageLink('guest-tracking.php', true)}?id_order={$order.id_order}" title="{l s='Meus Pedidos' mod='traycheckout'}" data-ajax="false"><img src="{$img_dir}icon/order.gif" alt="{l s='Meus Pedidos'}" class="icon" /></a>
		<a href="{$link->getPageLink('guest-tracking.php', true)}?id_order={$order.id_order}" title="{l s='Meus Pedidos' mod='traycheckout'}" data-ajax="false">{l s='Meus Pedidos' mod='traycheckout'}</a>
	{else}
		<a href="{$link->getPageLink('history.php', true)}" title="{l s='Meus Pedidos' mod='traycheckout'}" data-ajax="false"><img src="{$img_dir}icon/order.gif" alt="{l s='Meus Pedidos'}" class="icon" /></a>
		<a href="{$link->getPageLink('history.php', true)}" title="{l s='Meus Pedidos' mod='traycheckout'}" data-ajax="false">{l s='Meus Pedidos' mod='traycheckout'}</a>
	{/if}
	
</div>
	
</div>

	