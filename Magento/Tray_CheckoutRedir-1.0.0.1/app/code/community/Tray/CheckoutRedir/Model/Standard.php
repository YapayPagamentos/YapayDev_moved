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

class Tray_CheckoutRedir_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
	const PAYMENT_TYPE_AUTH = 'AUTHORIZATION'; //retirar
    const PAYMENT_TYPE_SALE = 'SALE'; //retirar
    
    protected $_allowCurrencyCode = array('BRL');
    
    /**
     * Availability options
     */
  

    /**
     * Can be edit order (renew order)
     *
     * @return bool
     */
    public function canEdit()
    {
        return false;
    }
    
    /**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('checkoutredir/redirect');
    }
    
     /**
     * Get checkoutredir session namespace
     *
     * @return Tray_CheckoutRedir_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('checkoutredir/session');
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Using for multiple shipping address
     *
     * @return bool
     */
    public function canUseForMultishipping()
    {
        return false;
    }

    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock($_formBlockType, $name)
            ->setMethod('checkoutredir')
            ->setPayment($this->getPayment())
            ->setTemplate('tray/checkoutredir/form.phtml');
        return $block;
    }

    public function validate()
    {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!$currency_code){
            $session = Mage::getSingleton('adminhtml/session_quote');
            $currency_code = $session->getQuote()->getBaseCurrencyCode();            
        } 
        if (!in_array($currency_code,$this->_allowCurrencyCode)) {
            Mage::throwException(Mage::helper('checkoutredir')->__('A moeda selecionada ('.$currency_code.') não é compatível com o Tray'));
        }
        return $this;
    }

    public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment)
    {
       return $this;
    }

    public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment)
    {
        return $this;
    }

    public function canCapture()
    {
        return true;
    }


    public function getCheckoutFormFields() 
    {
        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        
        if (!$orderIncrementId) {
            $quoteidbackend = $this->getCheckout()->getData('checkoutredir_quote_id');
            $order = Mage::getModel('sales/order')->loadByAttribute('quote_id', $quoteidbackend);
            $orderIncrementId = $order->getData('increment_id');
        }
        else {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        }
        
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        
        // Envia email de confirmação ao cliente
        if(!$order->getEmailSent()) {
        	$order->sendNewOrderEmail();
        	$order->setEmailSent(true);
        	$order->save();
        }
            
        $isOrderVirtual = $order->getIsVirtual();
        $a = $isOrderVirtual ? $order->getBillingAddress() : $order->getShippingAddress();
        $currency_code = $order->getBaseCurrencyCode();

        list($items, $totals, $discountAmount, $shippingAmount) = Mage::helper('checkoutredir')->prepareLineItems($order, false, false);

        $postal_code = trim(str_replace("-", "", $a->getPostcode()));
        
        $payment_type = $order->getPayment()->getData('cc_type');
		
		$shipping_description = $order->getData('shipping_description');
        
        $sArr = array();
        $number_contact = substr(str_replace(" ","",str_replace("(","",str_replace(")","",str_replace("-","",$a->getTelephone())))),0,2) . substr(str_replace(" ","",str_replace("-","",$a->getTelephone())),-8);

		$sArr['token_account']= $this->getConfigData('token');
        $sArr['order_number']= $this->getConfigData('prefixo').$orderIncrementId;

    	// Dados de endereço
        if ($this->getConfigData('custom_address_model', $order->getStoreId())) {
            $street 		= $a->getStreet(1);
            $number 		= $a->getStreet(2);
            $completion 	= $a->getStreet(3);
            $neighborhood	= $a->getStreet(4);
        } else {
	        list($street, $number, $completion) = Mage::helper('checkoutredir')->getAdress($a->getStreet(1));
	        
            $neighborhood	= $a->getStreet(2);
        }
                
        $sArr['customer[name]']= $a->getFirstname() . ' ' . str_replace("(pj)", "", $a->getLastname());
        $sArr['customer[addresses][][postal_code]']= $postal_code;
        $sArr['customer[addresses][][street]']= $street;
        $sArr['customer[addresses][][number]']= $number;
        $sArr['customer[addresses][][completion]']= $completion;
        $sArr['customer[addresses][][neighborhood]']=$neighborhood;
        $sArr['customer[addresses][][city]']= $a->getCity();
        $sArr['customer[addresses][][state]']= $a->getRegionCode();
        $sArr['customer[contacts][][number_contact]']= $number_contact;
        $sArr['customer[contacts][][type_contact]']= "H"; 
        $sArr['customer[email]']= $a->getEmail();
        
        if ($items) {
            $i = 0;
            foreach($items as $item) {
            	if ($item->getAmount() > 0) {
					$sArr ["transaction_product[$i][code]"] = $item->getId();
					$i++;
					$sArr ["transaction_product[$i][description]"] =  $item->getName();
					$i++;
					$sArr ["transaction_product[$i][quantity]"] = $item->getQty();
					$i++;
					$sArr ["transaction_product[$i][price_unit]"] = sprintf('%.2f',$item->getAmount());
					$i++;
					$sArr ["transaction_product[$i][sku_code]"] = $item->getId();
					$i++;
            	}
            }
            $sArr ["transaction_products"]=$i;
           	
			$sArr["price_discount"] = is_numeric( $discountAmount ) ? sprintf('%.2f',$discountAmount) : 0;
            $sArr["price_additional"] = is_numeric( $order->getData("base_tax_amount") ) ? sprintf('%.2f',$order->getData("base_tax_amount")) : 0;
        }
        $totalArr = $order->getBaseGrandTotal(); //retirar
        $shipping = sprintf('%.2f',$shippingAmount) ;

        $sArr['shipping_type']= $shipping_description;
        $sArr['shipping_price']= $shipping;        
        
		$sArr = array_merge($sArr, array('url_process' => Mage::getUrl('checkoutredir/standard/return',  array('_secure' => true))));
		$sArr = array_merge($sArr, array('url_success' => Mage::getUrl('checkoutredir/standard/return', array('_secure' => true))));            
      
        $sArr = array_merge($sArr, array('url_notification' => Mage::getUrl('checkoutredir/standard/success', array('_secure' => true, 'type' => 'geral'))));
        
        $sReq = '';
        $rArr = array();
        foreach ($sArr as $k=>$v) {
            $value =  str_replace("&","and",$v);
            $rArr[$k] =  $value;
            $sReq .= '&'.$k.'='.$value;
        }
        return $rArr;
    }

    public function getCheckoutRedirUrl()
    {
         if ($this->getConfigData('sandbox') == '1')
         {
         	return 'http://checkout.sandbox.tray.com.br/payment/transaction';
         } else {
         	return 'https://checkout.tray.com.br/payment/transaction';
         }
          
    }
    
}