<?php

include_once '../LibTrayCheckout/TrayCheckoutTransaction.php';

class CreateTransaction{
	protected $order_id;
	protected $payment_method_id;
	public $html;
	const URL_NOTIFICATION = 'http://minhaloja.com.br/Example/notification.php';
	
	public function __construct($order_id, $payment_method_id){
		$this->order_id = (isset($order_id) ) ? $order_id : 'ped_01';
		$this->payment_method_id = (isset($payment_method_id) ) ? $payment_method_id : '6';
		$this->html = "";
	}
	
	public function createTransaction(){
		
		$order = new TrayCheckoutTransaction();
		
		// Add products
		$order->addProduct('sku01', 1, 150.00, 'HD Externo' );
		$order->addProduct( 'sku02', 1, 990.50, 'Galaxy S3' );
		
		// Customer Address
		$order->addAddress ( 'B', '17519255', 'Av. das Esmeraldas', '1500', 'Jardim Teste', 'apto. 25', 'Marília', 'SP' );
		
		// Phone Customer
		$order->addContact ( 'H', '1434221155' );

		// Customer information
		$order->setCustomer ( 'Nome Comprador', '63863368363', '10/10/1985', 'M', '', 'emailcomprador@comprador.net.br' );
		
		$order->setTransaction ( $this->order_id, 'SEDEX', 5.00, 0, 0, $order->getTotalPaid (), self::URL_NOTIFICATION );
		
		$methodPayment= $this->methodPayment ();		
		$order->setPayment($methodPayment['method_id'], $methodPayment['split'], $methodPayment['card']);

		try {
			if ($order->request()) {
				$this->save($order->getTransactionResponse (), $order->getPaymentResponse ());				
				$this->setSucessHtml($order->getTransactionResponse (), $order->getPaymentResponse ());
			}else{
				$this->setErrorsHtml ( $order->getErrors () );
			}
			$this->setXmlHtml($order->getXmlResponse());		
		} catch ( Exception $e ) {
			die($e->getMessage());
		}
	}
	
	protected function save($transaction, $payment){
		$this->html .=  "<br><div><br>***** IMPLEMENTAR MÉTODO PARA GRAVAR DADOS RETORNADOS DO TRAYCHECKOUT: \$transaction e \$payment *****<br>";
	}
	
	protected function setSucessHtml($transaction, $payment){
		$this->html .=  "<h3 class='suss'>Transação criada com sucesso.</h3> ";
		$this->html .=  "<b>Id pedido:</b> " . $this->order_id;
		$this->html .=  "<br><b>Id transação TrayCheckout:</b> " . $transaction->transaction_id;
		$this->html .=  "<br><b>Status:</b> " . $transaction->status_id . " - " . $transaction->status_name;
		$this->html .=  "<br><b>Meio de Pagamento:</b> " . $payment->payment_method_id . " - " . $payment->payment_method_name;
		$this->html .=  "<br><b>Valor pago:</b> " . $payment->price_payment . " (" . $payment->split . "x)";
		if($payment->linha_digitavel)
			$this->html .=  "<br><b>Linha digitavel do boleto:</b> " . $payment->linha_digitavel;

		$this->linkPayment($payment->url_payment, $payment->payment_method_name);
		$this->html .=  "</div><hr>";		
	}
	
	protected function setXmlHtml($xml){
		$this->html .=  "<br><b>Xml retornado:</b><br> " ;
		$this->html .=  "<textarea rows='30' cols='120'>";
		$this->html .=  $xml;
		$this->html .=  "</textarea>";
	}

	private function methodPayment() {
		if($this->payment_method_id=='card')
			return array('method_id' => 3, 'split' => 5, 'card' => $this->getCard());
		elseif($this->payment_method_id=='transf')
			return array('method_id' => 7, 'split' => 1, 'card' => '');
		else //boleto
			return array('method_id' => 6, 'split' => 1, 'card' => ''); 
	}

	private function getCard (){
		$card = new stdClass();
		$card->card_name = "Nome impresso no cartao";
		$card->card_number = '5555666677778884';
		$card->card_expdate_month = '01';
		$card->card_expdate_year = '2017';
		$card->card_cvv = '123';
		return $card;
	}
	 
	private function setErrorsHtml($errorList) {
		$this->html .=  "<div class='error'>Erro para o pedido: " . $this->order_id . " <BR><pre>";
		if (isset ( $errorList ['general_errors'] )) {
			foreach ( $errorList ['general_errors'] as $error ) {
				$this->html .=  "<br>General Error: [" . $error->code . "] - " . $error->message;
			}
		}
		if (isset ( $errorList ['validation_errors'] )) {
			foreach ( $errorList ['validation_errors'] as $error ) {
				$this->html .=  "<br>Validation Error: [" . $error->code . "] - " . $error->message_complete . "(" . $error->field . ")";
			}
			$this->html .=  "<br></div>";
		}
	}
	
	private function linkPayment ($link, $method_name){
		if ($link) {
			$this->html .=  "<br><br><h3>Link para concluir pagamento:</h3>";
			$this->html .=  "<h3><a href='#'";
			$this->html .=  "onclick=\"window.open('". $link . "', 'Pagina', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=10, LEFT=10, WIDTH=770, HEIGHT=400');\">";
			$this->html .=  "Clique aqui para pagar com <b>$method_name</b>.</a><h3><br>";
		}
	}
}