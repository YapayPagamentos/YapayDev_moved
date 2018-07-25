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

	<input id="token_account" name="token_account" value="{$token_account}" type="hidden"/>
	<input id="order_number" name="order_number" value="{$order_number}" type="hidden"/>
	<input id="customer[name]" name="customer[name]" value="{$customer['name']}" type="hidden"/>
	<input id="customer[addresses][][postal_code]" name="customer[addresses][][postal_code]" value="{$customer['addresses']['postal_code']}" type="hidden"/>
	<input id="customer[addresses][][street]" name="customer[addresses][][street]" value="{$customer['addresses']['street']}" type="hidden"/>
	<input id="customer[addresses][][number]" name="customer[addresses][][number]" value="{$customer['addresses']['number']}" type="hidden"/>
	<input id="customer[addresses][][completion]" name="customer[addresses][][completion]" value="{$customer['addresses']['completion']}" type="hidden"/>
	<input id="customer[addresses][][neighborhood]" name="customer[addresses][][neighborhood]" value="{$customer['addresses']['neighborhood']}" type="hidden"/>
	<input id="customer[addresses][][city]" name="customer[addresses][][city]" value="{$customer['addresses']['city']}" type="hidden"/>
	<input id="customer[addresses][][state]" name="customer[addresses][][state]" value="{$customer['addresses']['state']}" type="hidden"/>
	<input id="customer[contacts][][number_contact]" name="customer[contacts][][number_contact]" value="{$customer['contacts']['number_contact']}" type="hidden"/>
	<input id="customer[contacts][][type_contact]" name="customer[contacts][][type_contact]" value="H" type="hidden"/>
	<input id="customer[email]" name="customer[email]" value="{$customer['email']}" type="hidden"/>
	{foreach from=$transaction_product item=product}
	<input id="transaction_product[][code]" name="transaction_product[][code]" value="{$product['code']}" type="hidden"/>
	<input id="transaction_product[][description]" name="transaction_product[][description]" value="{$product['description']}" type="hidden"/>
	<input id="transaction_product[][quantity]" name="transaction_product[][quantity]" value="{$product['quantity']}" type="hidden"/>
	<input id="transaction_product[][price_unit]" name="transaction_product[][price_unit]" value="{$product['price_unit']}" type="hidden"/>
	<input id="transaction_product[][sku_code]" name="transaction_product[][sku_code]" value="" type="hidden"/>
	{/foreach}
	<input id="price_discount" name="price_discount" value="{$price_discount}" type="hidden"/>
	<input id="price_additional" name="price_additional" value="0.00" type="hidden"/>
	<input id="shipping_type" name="shipping_type" value="{$shipping_type}" type="hidden"/>
	<input id="shipping_price" name="shipping_price" value="{$shipping_price}" type="hidden"/>
	<input id="total_paid" name="total_paid" value="{$total_paid}" type="hidden"/>
	<input id="url_notification" name="url_notification" value="{$url_notification}" type="hidden"/>
	<input id="url_success" name="url_success" value="{$url_success}" type="hidden"/>
	<input id="url_process" name="url_process" value="{$url_process}" type="hidden"/>

