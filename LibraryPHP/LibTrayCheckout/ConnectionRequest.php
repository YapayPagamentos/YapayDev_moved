<?php
class ConnectionRequest{
	private $url;
	private $fields;

	public function __construct($url, $fields){
		$this->url = $url;
		$this->fields = $fields;		
	}

	
	/**	
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return the $fields
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @param field_type $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @param field_type $fields
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

	public function curlPost() {
		$ch = curl_init ( $this->getUrl() );

		curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->getFields());
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Connection: Close' ) );
		
		if (! ($res = curl_exec ( $ch ))) {
			echo "Erro na execucao!";
			curl_close ( $ch );
			exit ();
		}
		$httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

		if ($httpCode != "200") {
			http::httpError("Erro de requisicao em: $urlPost");
			echo ("Erro ao conectar em: $url");
		}
		if(curl_errno($ch)){
			http::httpError("Erro de conexão: " . curl_error($ch));
			echo ("Erro de conexão: " . curl_error($ch));
		}
		curl_close ( $ch );
		return $res;
	}
}
	