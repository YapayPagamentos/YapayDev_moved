<?php
/*
 * 2007-2013 PrestaShop
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2013 PrestaShop SA
 *  @version  Release: $Revision: 13573 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @since 1.5.0
 */

class TrayCheckoutSubmitModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		
		$this->context->controller->addJquery();
		$this->context->controller->addJQueryPlugin('thickbox');
		
        $this->display_column_left = false;
        $this->display_column_right = false;
        $this->display_header= false;
        $this->display_footer= false;
        
        parent::initContent();
		
		$traycheckout = new traycheckout();
		$this->context = Context::getContext();
		$params = $this->displayHook();
		$id_order = (int)Tools::getValue('id_order');
		$form = $traycheckout->formPost($params, $id_order);
		
		
		if ((int)Configuration::get('traycheckout_SANDBOX') == 1)
			$post_url = 'https://checkout.sandbox.tray.com.br/payment/transaction';
		else
			$post_url = 'https://checkout.tray.com.br/payment/transaction';

		$this->context->smarty->assign(array(
			'status' 		=> 'ok', 
			'secure_key' 	=> $params['objOrder']->secure_key,
			'id_module' 	=> $this->id,
			'total_paid' 	=> $total_paid,
			'post_url' => $post_url,
			'PAYMENT_FIELDS' => $form
		));
		
		
		$this->setTemplate('form_tc.tpl');
	}

	private function displayHook()
	{
		if (Validate::isUnsignedId($this->id_order) )
		{
			$order = new Order((int)$this->id_order);
			$currency = new Currency((int)$order->id_currency);

			if (Validate::isLoadedObject($order))
			{
				$params['objOrder'] = $order;
				$params['currencyObj'] = $currency;
				$params['currency'] = $currency->sign;
				$params['total_to_pay'] = $order->getOrdersTotalPaid();

				return $params;
			}
		}

		return false;
	}

}
