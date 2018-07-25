<?php
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
*  @author Igor Cicotoste <ifredi@tray.net.br>
*  @copyright  Yapay
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */
class TrayCheckoutReturnModuleFrontController extends ModuleFrontController{
    
    public $ssl = true;
    
    public function initContent() {
        $cur_page_url = $this->curPageURL();
        if(!Tools::getValue('self_redirect') && !Configuration::get('traycheckout_REDIRECT')){  
			$this->display_column_left = false;
	    	$this->display_column_right = false;
	        $this->display_header= false;
	        $this->display_footer= false;
	    	$this->context->smarty->assign(array(
	        	'cur_page_url' 	=> $cur_page_url
			));
	        $this->setTemplate('self_redirect.tpl');
	    }else{
	        parent::initContent();
	        $order_number = (int)Tools::getValue('id_order');
	        $action = Tools::getValue('action');
	        $DadosOrder 		= new Order($order_number);
	        $currency 			= new Currency($DadosOrder->id_currency);
	        $total_paid			= number_format( Tools::convertPrice( $DadosOrder->total_paid, $currency), 2, '.', '');
			$prefixo = Configuration::get ( 'traycheckout_PREFIXO' );
	        
	        $this->context->smarty->assign(array(
				'action' 		=> $action, 
				'order_number' 		=> $prefixo . $order_number,
	        	'reference' 		=> $DadosOrder->reference,
	        	'order' 		=> $order_number,
	        	'cur_page_url' 		=> $cur_page_url,
				'total_paid' 	=> Tools::displayPrice($total_paid, $currency)
				
			));
	        $this->setTemplate('return.tpl');
	    } 
    }
	
	private function curPageURL() {
		$pageURL = 'http';
		if ($_SERVER ["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER ["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		}
		return $pageURL;
	}
}
