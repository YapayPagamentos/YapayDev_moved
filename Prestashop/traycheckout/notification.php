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
*  @copyright  TrayCheckout
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');

include_once(_PS_MODULE_DIR_.'traycheckout/traycheckout.php');

/*
 * Instant payment notification class.
 */
class TrayCheckoutNotifier extends traycheckout
{

	public function __construct()
	{
		parent::__construct();
	}

	public function confirmOrder($custom)
	{
		$token = Configuration::get ( 'traycheckout_TOKEN' );
		$prefixo = Configuration::get ( 'traycheckout_PREFIXO' );
	
		$order_number_conf = utf8_encode ( str_replace ( $prefixo, '', $_POST ['transaction'] ['order_number'] ) );
		$transaction_token = $_POST ['transaction'] ['transaction_token'];

		$transaction = $this->getResult($transaction_token);

		$order_number = str_replace ( $prefixo, '', $transaction ['order_number'] );
		if ($order_number != $order_number_conf) {
			$this->httpError ("Pedido: $order_number_conf não corresponte com a pedido consultado: $order_number!");
		}
		$order = new Order ( intval ( $order_number ) );
		if ($transaction ['price_original'] != $order->total_paid) {
			$this->httpError ('Total pago à Tray é diferente do valor original.');
		} 
		
		$comment = (isset ( $transaction ['status_id'] )) ? $transaction ['status_id'] : "";
		$comment .= (isset ( $transaction ['status_name'] ))? " - " . $transaction ['status_name'] : "";
		echo "Pedido: $order_number - $comment - ID: " . $transaction ['transaction_id'];
		
		/********************** DEVE MANTER ATÉ QUE A PRESTASHOP 1.5 SEJA CORRIGIDA *************************/
		//CONDIÇÃO PARA TRATAR ERRO DE ENVIO DE EMAIL (getPageLink()) 
		if (empty ( Context::getContext ()->link ))
			Context::getContext()->link = new Link ();
		
		$orderStatus = $this->getOrderStatus ( $transaction ['status_id'] );
		
		if ($orderStatus == Configuration::get ( 'traycheckout_STATUS_1' )) {
			if ($order->getTotalPaid () >= $order->total_paid) {
				echo " Já existe um pagamento confirmado! ";
			} else {
				$cart = Cart::getCartByOrderId ( $order_number );
				$curr = new Currency ( $cart->id_currency );
				$payment_method = null;
				$date = null;
				$retPay = $order->addOrderPayment ( Tools::ps_round ( $order->total_paid, 2 ), $payment_method, $transaction ['transaction_id'], $curr, $date );
			
			}
		}
		
		echo " Alterado para status: $orderStatus ";
		/** /ENVIO DO EMAIL **/
		$history = new OrderHistory ();
		$history->id_order = intval ( $order_number );
		$history->changeIdOrderState ( $orderStatus, $order, true );
		$history->addWithemail ();
		$history->save ();
	}
	
	/**
	 * Métod de retorno que indica algum erro de processamento ou 
	 */
	protected function httpError( $msg ) 
	{
		header("HTTP/1.1 202 ");
		echo  $msg;
		exit;
	}

	/**
	 * Retorna o status do pedido
	 * @param transaction
	 */
	private function getOrderStatus($status_id) 
	{
		
		switch ($status_id) {
			case ('4') :
			case ('5') :
			case ('87') :
				$nomestatus = "traycheckout_STATUS_0";
				break;
			case '6' :
				$nomestatus = "traycheckout_STATUS_1";
				break;
			case '7' :
			case '89' :
				$nomestatus = "traycheckout_STATUS_2";
				break;
			case '88' :
				$nomestatus = "traycheckout_STATUS_3"; 
				break;
			case '24' : //Contestação
				$nomestatus = "traycheckout_STATUS_4"; //CRIAR ERRO
				break;
		}
		return Configuration::get ( $nomestatus );
	}

	public function getResult($transaction_token)
	{
		$url =  $this->getUrl();

		$request = array ("token" => trim ( $transaction_token ), "type_response" => "J" ) ;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		if( !($res = curl_exec($ch)))
		{
			curl_close($ch);
			exit;
		}
		$httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		if ($httpCode != "200") {
			$this->httpError ("Não foi possível conectar em: $urlPost");
		} 
		curl_close($ch);
		$arrResponse = json_decode ( $res, TRUE );
		return $arrResponse['data_response'] ['transaction'];
	}
	
	private function getUrl(){
		if ((int)Configuration::get('traycheckout_SANDBOX') == 1)
			$urlPost = 'http://api.sandbox.checkout.tray.com.br/api/v1/transactions/get_by_token';
		else
			$urlPost = 'http://api.checkout.tray.com.br/api/v1/transactions/get_by_token';
		
		return $urlPost;
	}
}

if ($_POST) {		
	$notifier = new TrayCheckoutNotifier();
	$notifier->confirmOrder($result);
} else {
	header("HTTP/1.1 202 Post invalido");
	echo "Post invalido!";
}