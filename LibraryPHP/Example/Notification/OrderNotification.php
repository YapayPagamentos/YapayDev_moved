<?php
include '../LibTrayCheckout/TrayCheckoutGetTransaction.php';
include 'OrderStatus.php';
class OrderNotification {
	private $order_number;
	private $transaction_token ;	
	private $order;
	private $transaction;
	private $error;
	private $xmlResponse; 
	public $html;
	
	public function __construct($transaction_token, $order_number) {
		$this->error = array ();
		$this->transaction_token = $transaction_token;
		$this->order_number = $order_number;
	}
	
	public function setResponse($xmlResponse) {
		$this->xmlResponse = $xmlResponse;
		if (isset ( simplexml_load_string ( $xmlResponse )->data_response->transaction ))
			$this->transaction = simplexml_load_string ( $xmlResponse )->data_response->transaction;
	}
	
	public function setError($code = "", $message = "") {
		$this->error [$code] = $message;
	}
	
	public function getError() {
		return $this->error;
	}
	
	public function validateResponse() {
		if (isset(simplexml_load_string ( $this->xmlResponse )->error_response)) {
			$this->setError ( "0004", "Erro ao consultar TrayCheckout!" );
			return false;
		}
		return true;
	}
	
	public function validateId() {
		if ($this->order_number != $this->transaction->order_number) {
			$this->setError ( "0001", "Pedido: {$this->order_number} não corresponte com a pedido consultado: {$this->transaction->order_number}!" );
		}
	}
	
	private function validatePriceOriginal() {
		if ($this->transaction->price_original != $this->order->price_original) {
			$this->setError ( "0002", 'Total pago à Tray é diferente do valor original!' );
		}
	}
	
	private function validateTotalPaid() {
		if ($this->order->total_paid >= $this->transaction->price_original) {
			$this->setError ( "0003", "Já existe um pagamento confirmado para o pedido: " . $this->order_number . "! " );
		}
	}
	
	private function validateOrderTransaction() {
		$this->validateId ();
		$this->validatePriceOriginal ();
		$this->validateTotalPaid ();
		return (count ( $this->error )) ? false : true;
	}
	
	private function loadOrder() {
		// Pedido de testes
		$this->order->order_number = 29;
		$this->order->price_original = 387.12;
		$this->order->total_paid = 0; // Campo com a valor total que já foi pago
		$this->order->status_name = 'Aguardando Pagamento';
		$this->order->email = 'emailcomprador@sualoja.net.br';
	}
	
	public function setErrorsHtml() {
		$this->html .= "<br><br><b>Lista de erros:</b><br> ";
		foreach ($this->error as $key => $value) {
			$this->html .= "$key - $value <br> ";
		}
		return $this->html;
	}
	
	public function setXmlHtml() {
		$this->html .= "<br><br><b>Xml retornado:</b><br> ";
		$this->html .= "<textarea rows='30' cols='120'>";
		$this->html .= $this->xmlResponse;
		$this->html .= "</textarea>";
		return $this->html;
	}
	
	public function notification() {
		try {
			/*  Criando o objeto para consultar os dados da transação */
			$notifier = new TrayCheckoutGetTransaction ( $this->transaction_token );
			$xmlResponse = $notifier->request ();
			$this->setResponse ( $xmlResponse );
			if ($this->validateResponse ()) {
				$this->loadOrder ();
				if ($this->validateOrderTransaction ()) {
					$orderStatus = new OrderStatus ( $this->order, $this->transaction );
					$this->html .= $orderStatus->update ();
					return true;
				}
			}
		} catch ( Exception $e ) {
			http::httpError ( $e );
		}
		return false;
	}
}