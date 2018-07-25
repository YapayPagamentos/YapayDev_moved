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

class Tray_CheckoutRedir_Block_Redirect extends Mage_Core_Block_Abstract
{
    
    protected function _toHtml()
    {
        $standard = Mage::getModel('checkoutredir/'.$this->getRequest()->getParam("type"));
                
        $form = new Varien_Data_Form();
        
        $form->setAction($standard->getCheckoutRedirUrl())
            ->setId('checkoutredir_payment_checkout')
            ->setName('checkoutredir_payment_checkout')
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

        $xhtml =  $form->toHtml();
    	for($yy=0; $yy< $xx; $yy++){
        	$xhtml = str_replace("transaction_product[$yy]", "transaction_product[]", $xhtml);
        }
        
        $html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $html .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Language" content="pt-br" />';
        $html .= '<meta name="language" content="pt-br" />';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head>';
        $html .= '<body>';
        $html .= '<div align="center">';
        $html .= '<font size="4">Sua compra está em processo de finalização.<br /><br />';
        $html .= ''.$this->__('Aguarde ... você será redirecionado para o TrayCheckout em <span id="tempo">5</span> segundos.</font>');
        $html .= '<div>';
        $html .=$xhtml;
        $html .= '<script type="text/javascript">
                    function setTempo(){
                        var tempo = eval(document.getElementById("tempo").innerHTML);
                        if (tempo - 1 < 0){
                            document.getElementById("checkoutredir_payment_checkout").submit();
                        }else{
                            document.getElementById("tempo").innerHTML = tempo - 1;
                            setTimeout("setTempo()",1000);
                        }

                    }
                    setTimeout("setTempo()",1000);
                  </script>';
        $html .= '</body></html>';

        return utf8_decode($html);
    }
}