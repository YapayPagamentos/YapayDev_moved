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
*  @copyright  Yapay Intermediador
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
	<br><br><br><br><br>
	<br><br><br><br><br>
	
	<center>
		<img src="modules/traycheckout/icons/loadingAnimation.gif" alt="Single Image"/> <br>
		Carregando Yapay Intermediador...
	</center>
	<form   id="foo"  action="{$post_url}" method="post">
		{$PAYMENT_FIELDS}
  	</form>
	<script type="text/javascript">
	    function myfunc () {
	        var frm = document.getElementById("foo");
	        frm.submit();
	    }
	    window.onload = myfunc;
	</script>
