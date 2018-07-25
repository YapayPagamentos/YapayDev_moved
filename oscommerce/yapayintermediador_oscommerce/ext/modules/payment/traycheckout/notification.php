<?php
/*
  traycheckout.php 05/12/2013
  Modulo TrayCheckout - osCommerce 2.2
  Arquivo para o NAS e atualizacao automatica do pedido na plataforma
*/
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
    chdir('../../../../');
    require_once("includes/configure.php");
    require_once("includes/database_tables.php");
    require_once("includes/functions/database.php");
    require_once("ext/modules/payment/traycheckout/includes/classes/TrayCheckout.class.php");
    $cfgTrayCheckout = new TrayCheckout();
  
    $parameters = 'cmd=_notify-validate';
 
    $languageDir = $cfgTrayCheckout->getLanguageDir();
    
    require_once("includes/languages/".$languageDir."/modules/payment/traycheckout.php");
    
    $token_transaction = $_POST['token_transaction'];
    
    $data["token_account"] = $cfgTrayCheckout->getToken();
    $data["token_transaction"] = $_POST['token_transaction'];
    
    $ch = curl_init ( $cfgTrayCheckout->getUrlSearch() );
    
    curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
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
    
    $xml = simplexml_load_string($res);
    $statusTc = $cfgTrayCheckout->getStatus();
    $data_array = null;
    if($xml->message_response->message == "success"){
        
        $query = "select 
              o.orders_id,
              o.customers_name,
              o.customers_email_address,
              o.customers_telephone,
              o.delivery_street_address,
              o.delivery_suburb,
              o.delivery_city,
              o.delivery_state,
              o.delivery_postcode,
              o.billing_street_address,
              o.billing_suburb,
              o.billing_city,
              o.billing_state,
              o.billing_postcode,
              ot.value ,
              ot.title
            from
              orders o,
              orders_total ot
            where
              o.orders_id='".$_GET['orders_id']."' and
              o.orders_id=ot.orders_id and
              ot.class='ot_total'";
        tep_db_connect();
        $selectOrder = tep_db_query($query);
        $showOrder = tep_db_fetch_array($selectOrder);
        tep_db_close();
        if(floatval($showOrder["value"]) == floatval($xml->data_response->transaction->payment->price_original )){
            var_dump();
            $statusIdUpdateTc = "";
            
            switch (intval($xml->data_response->transaction->status_id)) {
                case 4:
                        $statusIdUpdateTc = $statusTc["pending"];
                    break;
                case 5:
                        $statusIdUpdateTc = $statusTc["proccessing"];
                    break;
                case 6:
                        $statusIdUpdateTc = $statusTc["approved"];
                    break;
                case 7:
                        $statusIdUpdateTc = $statusTc["canceled"];
                    break;
                case 24:
                        $statusIdUpdateTc = $statusTc["contestation"];
                    break;
                case 87:
                        $statusIdUpdateTc = $statusTc["monitoring"];
                    break;
                case 88:
                        $statusIdUpdateTc = $statusTc["recovery"];
                    break;
                case 89:
                        $statusIdUpdateTc = $statusTc["fail"];
                    break;
                default :
                        $statusIdUpdateTc = $statusTc["pending"];
                    break;
            }
            tep_db_connect();
            tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . $statusIdUpdateTc . "', last_modified = now() where orders_id = '" . $_GET['orders_id'] . "'");
            tep_db_close();
            
            $data_array = array('orders_id' => $_GET['orders_id'],
                                'date_added' => 'now()',
                                'orders_status_id' => $statusIdUpdateTc,
                                'customer_notified' => '1',
                                'comments' => "Pedido consta como ".$xml->data_response->transaction->status_name);
            
        }else{
            $data_array = array('orders_id' => $_GET['orders_id'],
                                'date_added' => 'now()',
                                'orders_status_id' => $statusTc["pending"],
                                'customer_notified' => '0',
                                'comments' => 'Erro ao atualizar o status do pedido - Valor da compra diferente do valor no TrayCheckout');
        }
    }else{
        $data_array = array('orders_id' => $_GET['orders_id'],
                                'date_added' => 'now()',
                                'orders_status_id' => $statusTc["pending"],
                                'customer_notified' => '0',
                                'comments' => 'Erro ao atualizar o status do pedido - ' . $xml->error_response->errors[0]->error->code . ' - ' .$xml->error_response->errors[0]->error->message);
    }
    tep_db_connect();
    tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "  (orders_id, date_added, orders_status_id, customer_notified, comments) values ".
                 "(".$data_array["orders_id"].", ".$data_array["date_added"].", ".$data_array["orders_status_id"].", '".$data_array["customer_notified"]."', '".$data_array["comments"]."')");
    tep_db_close();
    
?>
<script>
    <?php if(($cfgTrayCheckout->getIntegrationType() == 'FRAME') || ($cfgTrayCheckout->getIntegrationType() == 'MODAL')){?>
    window.parent.location = '<?php echo $cfgTrayCheckout->getUrlSuccess(); ?>';
    <?php 
    
    } else {
    ?>
    window.opener.location = '<?php echo $cfgTrayCheckout->getUrlSuccess(); ?>';
    window.close();
    <?php 
    }
    ?>
    
</script>
