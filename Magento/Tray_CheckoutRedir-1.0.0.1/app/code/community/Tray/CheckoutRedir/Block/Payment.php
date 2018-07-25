<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte@tray.net.br so we can send you a copy immediately.
 *
 * @category   Tray
 * @package    Tray_CheckoutRedir
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Tray_CheckoutRedir_Block_Payment extends Mage_Core_Block_Template
{
    
    protected function getPayment()
    {
        $standard = Mage::getModel('checkoutredir/'.$this->getRequest()->getParam("type"));
                
        $form = new Varien_Data_Form();

        $form->setAction($standard->getCheckoutRedirUrl())
            ->setId('form_tc')
            ->setName('form_tc')
            ->setMethod('POST')
            ->setUseContainer(true);

       	$xx= 0;
        foreach ($standard->getCheckoutFormFields() as $field => $value)
        {
	         if($field =="transaction_products"){
	         	$xx= $value;
	         }
			 $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }

        $html = $form->toHtml();
        for($yy=0; $yy< $xx; $yy++){
        	$html = str_replace("transaction_product[$yy]", "transaction_product[]", $html);
        }
        echo utf8_decode($html);
    }
}