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

class Tray_CheckoutRedir_Block_Info_Geral extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('tray/checkoutredir/info/geral.phtml');
    }
        
    public function getLinkPayment($order) 
    {
        if ($this->getRequest()->getRouteName() != 'checkout') {
            $_order = $order;
            $incrementid = $_order->getData('increment_id');
            $quoteid = $_order->getData('quote_id');
		
            $hash = Mage::getModel('core/encryption')->encrypt($incrementid . ":" . $quoteid);
			$method = $_order->getPayment()->getMethod();
            switch ($method) {
            	case 'checkoutredir_geral':
	            return '<span>Para efetuar o pagamento, <a href="' . Mage::getBaseUrl() . 'checkoutredir/standard/paymentbackend/order/' . $hash . '">clique aqui</a>.</span>';
	            break;
            }
        }
    }
}