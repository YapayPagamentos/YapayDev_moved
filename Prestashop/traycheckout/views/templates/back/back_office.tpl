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
<img src="../modules/traycheckout/imagens/traycheckout.png" style="float:left; margin-right:15px;" width="250" heigth="250" />
		<b>{$arrContent['msg_aceite_traycheckout']}.</b><br /><br />
		{$arrContent['msg_cliente_escolher_traycheckout']}.<br />
		{$arrContent['msg_configurar_email']}.
		<br /><br /><br />
	<form action="{$action_form}" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />{$arrContent['configuracoes']}</legend>
			
			<label>{$arrContent['token']}:</label>
			<div class="margin-form"><input type="text" size="33" name="token_store" value="{$token_store}" /></div>
			<br />
			
			<label>{$arrContent['url_notification']}:</label>
			<div class="margin-form"><input type="text" size="33" name="url_notification" value="{$url_notification}" /></div>
			<br />
			
			<label>{$arrContent['prefixo_pedido']}:</label>
			<div class="margin-form"><input type="text" size="33" name="prefixo" value="{$prefixo}" /></div>
			<br />
			
			<p class="description">{$arrContent['atencao_ativar_teste']}: <br>
				{$arrContent['msg1_ativar_teste']}<br>
				{$arrContent['msg2_ativar_teste']}<br>
				{$arrContent['msg3_ativar_teste']}<br>
			</p>
			<label>{$arrContent['ativar_teste']}:</label>
			<div class="margin-form">
			<select  name="sandbox">
			  <option value="0" {$sandbox_selected['opt_sandbox_inativo']}>Não</option>
			  <option value="1" {$sandbox_selected['opt_sandbox_ativo']}>Sim</option>
			</select>
			</div>
			<br />
			<label>{$arrContent['ativar_redirect']}:</label>
			<div class="margin-form">
			<select  name="redirect">
			  <option value="0" {$redirect_selected['opt_redirect_inativo']}>Não</option>
			  <option value="1" {$redirect_selected['opt_redirect_ativo']}>Sim</option>
			</select>
			</div>
			<br />
			<center><input type="submit" name="submittraycheckout" value="{$arrContent['atualizar']}" class="button" /></center>
		</fieldset>
		</form>
		