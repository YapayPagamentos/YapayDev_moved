<?php
/*
  traycheckout.php 05/12/2013
  Modulo Yapay - osCommerce 2.2
  Classe Yapay para resgatar as informacoes de configuracao na pagina de checkout.
*/
  
  class TrayCheckout {
    
    var $token;
    var $integrationType;
    var $urlNotification;
    var $orderPrefix;
    var $sandbox;
    var $paymentsAbles;
    var $status;
    
    var $database;
    
    function TrayCheckout() {
      
      $this->connect = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
      $this->database = mysql_select_db(DB_DATABASE, $this->connect);
      
      $query = 'select 
                  configuration_key, 
                  configuration_value 
                from 
                  configuration 
                where 
                  configuration_key like "MODULE_PAYMENT_TRAYC%"';
      $select = mysql_query($query);
      while($show = mysql_fetch_array($select)) {
        switch($show['configuration_key']) {
          case 'MODULE_PAYMENT_TRAYC_TOKEN': $this->token = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_INTEGRATION_TYPE': $this->integrationType = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_URL_NOTIFICATION': $this->urlNotification = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_ORDER_PREFIX': $this->orderPrefix = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_SANDBOX': $this->sandbox = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_PAYMENTS_ABLES': $this->paymentsAbles = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_PENDING': $this->status["pending"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_PROCCESSING': $this->status["proccessing"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_APPROVED': $this->status["approved"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_CANCELED': $this->status["canceled"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_CONTESTATION': $this->status["contestation"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_MONITORING': $this->status["monitoring"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_RECOVERY': $this->status["recovery"] = $show['configuration_value'];break;
          case 'MODULE_PAYMENT_TRAYC_STATUS_FAIL': $this->status["fail"] = $show['configuration_value'];break;
        }
      }
            
    }
    
    function getToken() {
      return $this->token;
    }
    
    function getIntegrationType() {
      return $this->integrationType;
    }
    
    function getUrlNotification() {
        if($this->urlNotification == "True"){
            return HTTP_SERVER.DIR_WS_CATALOG."ext/modules/payment/traycheckout/notification.php";
        }else{
            return "";
        }
    }
    
    function getUrlSuccess() {
        return HTTP_SERVER.DIR_WS_CATALOG."checkout_success.php";
    }
    
    function getOrderPrefix() {
      return $this->orderPrefix;
    }
    
    function getSandbox() {
      return $this->sandbox;
    }
    
    function getPaymentsAbles() {
      return $this->paymentsAbles;
    }
    
    function getStatus() {
      return $this->status;
    }
    
    function getLanguageDir() {
      $query = "select
                  l.directory
                from
                  languages l,
                  configuration c
                where
                  c.configuration_value=l.code and
                  c.configuration_key='DEFAULT_LANGUAGE'";
      $select = mysql_query($query, $this->connect);
      $show = mysql_fetch_array($select);
      
      return $show['directory'];
    }
    
    
    function getUrlPost(){
        if ($this->sandbox == "True"){
            return "http://checkout.sandbox.tray.com.br/payment/transaction";
        }else{
            return "https://checkout.tray.com.br/payment/transaction";
        }
    }
    
    function getUrlSearch(){
        if ($this->sandbox == "True"){
            return "http://api.sandbox.traycheckout.com.br/v2/transactions/get_by_token";
        }else{
            return "https://api.traycheckout.com.br/v2/transactions/get_by_token ";
        }
    }
    
  }
?>
