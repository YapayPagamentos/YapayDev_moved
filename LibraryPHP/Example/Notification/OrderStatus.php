<?php
class OrderStatus{
	private $order;
	private $transaction;
	private static $status = array(
			'4'=> 'Aguardando Pagamento',
			'5'=>'Em Processamento',
			'87'=>'Em Monitoramento',
			'6' =>'Aprovada',
			'7' =>'Cancelada',
			'89' =>'Reprovada',
			'88'=>'Em Recuperação',
			'24' => 'Em Contestação'
		);
	
	const STATUS_APROVADA = 6;
	const STATUS_CANCELADA = 7;
	const STATUS_REPROVADA = 89;
	
	public function __construct($order, $transaction){
		$this->order = $order;
		$this->transaction = $transaction;
	}
	
	/*
	 * Implementar a confirmação do pagamento em seu sistema
	 */
	public function confirmPayment (){
		// Pagamento confirmado com Sucesso , a partir deste ponto o pedido poderá ser faturado e enviado ao cliente
		$this->html .=  $this->statusInformation ();
		//$this->save();		
	}

	/*
	 * Implementar cancelamento de pedido em seu sistema
	 */
	public function cancelOrder (){
		$this->html .=  $this->statusInformation ();
		// $this->save();			
	}
	
	/*
	 * Implementar alteração do histórico dos status do pedido
	 */
	public function updateHistoryStatus (){
		$this->html .=  $this->statusInformation ();
		//$this->save();			
	}
	
	public function update () {

		if ($this->transaction ->status_id == self::STATUS_APROVADA) {
			$this->confirmPayment ();
		
		} elseif (in_array ( $this->transaction ->status_id, array (self::STATUS_CANCELADA, self::STATUS_REPROVADA ) )) {
			$this->cancelOrder ();
		
		}else{
			$this->updateHistoryStatus ();
		}
		$this->sendMail ();
		return $this->html;
	}
	
	/*
	 * Implementar a notificação de email para seu cliente
	 */
	private function sendMail(){
		$comment  = (isset ( $this->transaction ->status_id ))   ? $this->transaction ->status_id . " - " : "";
		$comment .= (isset ( $this->transaction ->status_name )) ? $this->transaction ->status_name       : "";

		// The message
		$message = "Status alterado para: $comment \nId transação: " . $this->transaction ->transaction_id;
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70);
		// Send
		$email = mail($this->order->email, 'Change status transaction', $message);
		$this->html .= "<br>Retorno do envio de email: ($email)";
	}
	
	private function statusInformation() {
		$comment  = (isset ( $this->transaction ->status_id ))   ? $this->transaction ->status_id . " - " : "";
		$comment .= (isset ( $this->transaction ->status_name )) ? $this->transaction ->status_name       : "";
		$html = "<div><h3 class='suss'>Notificação realizada com sucesso!</h3>";
		$html .= "Pedido: {$this->order->order_number} <br>Status alterado para: $comment <br>";
		$html .= "Data da notificação:" . date("Y-m-d") . " <br>Id transação: " . $this->transaction ->transaction_id. " <br>";
		$html .= "Valor de " .$this->transaction ->price_original . "</div>";
		return $html; 
	}
}