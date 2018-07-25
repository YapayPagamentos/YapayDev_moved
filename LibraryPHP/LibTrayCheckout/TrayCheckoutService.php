<?php

abstract class TrayCheckoutService{
	protected $fields;
    protected $xmlResponse;
	
	protected function getFields() {
		return $this->fields;
	}

	protected function setFields($fields) {
		$this->fields = $fields;
	}
	
	/**
	 * @return the $xmlResponse
	 */
	public function getXmlResponse() {
		return $this->xmlResponse;
	}

	/**
	 * @param field_type $xmlResponse
	 */
	public function setXmlResponse($xmlResponse) {
		$this->xmlResponse = $xmlResponse;
	}
	
	protected function errorResponse() {
		if(!isset($this->xmlResponse) || isset(simplexml_load_string ( $this->xmlResponse )->error_response)){
			return true;
		}
		return false;
	}
	
	abstract protected function getUrl();
	abstract protected function getErrors();
	abstract protected function postFields();
	abstract protected function curlRequestConnection();
	abstract public function request();
}