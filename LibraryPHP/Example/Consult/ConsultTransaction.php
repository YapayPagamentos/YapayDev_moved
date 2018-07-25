<?php
include '../LibTrayCheckout/TrayCheckoutGetTransaction.php';
include 'Notification/OrderStatus.php'; 
class ConsultTransaction{
	private $transaction_token ;	
	
	public function __construct($transaction_token ) {
		$this->transaction_token = $transaction_token;
	}
	
	public function consult() {
		try {
			/*  Criando o objeto para consultar os dados da transação */
			$consult = new TrayCheckoutGetTransaction( $this->transaction_token );
			return $consult->request ();
		} catch ( Exception $e ) {
			http::httpError ( $e );
		}
	}
}