<?php
/*
  traycheckout.php 05/12/2013
  Modulo TrayCheckout - osCommerce 2.2
  Arquivo principal do modulo (Instalacao, Selecao e Configuracao na plataforma)
*/

  class traycheckout {
    var $code, $title, $description, $enabled, $image;

    // classe construtora
    function traycheckout() {
      global $order;

      $this->code = 'traycheckout';
      $this->title = MODULE_PAYMENT_TRAYC_TITLE;
      $this->description = MODULE_PAYMENT_TRAYC_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_TRAYC_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_TRAYC_STATUS == 'True') ? true : false);
      $this->image = './ext/modules/payment/traycheckout/images/banner_comlogo_formas.jpg';

      //if (is_object($order)) $this->update_status();

    }

    // metodos
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TRAYC_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_TRAYC_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
     return array('id' => $this->code,
                   'module' => $this->title.'<br /><img src="'.$this->image.'" border="0"> '."<br /><span style=\"font-weight: normal; \">".$this->description."</span>");

    }

    function pre_confirmation_check() {
    }

    function confirmation() {
      global $order;
      $confirmation = array('title' => $this->title,
                            'fields' => array(array('title' => MODULE_PAYMENT_TRAYC_FINISH
                                                    )));
      return $confirmation;
    }

    function process_button() {
     return false;
    }

    function before_process() {
     return false;
    }

    function after_process() {
     global $order, $cart, $insert_id;

     $html  = '<html>';
     $html .= '<body onload="document.checkout_traycheckout.submit();">';
     $process_form =  tep_draw_form('checkout_traycheckout', 'checkout_traycheckout.php', 'post', '').
                               tep_draw_hidden_field('orders_id', $insert_id);
     $html .= $process_form;
     $html .= '<noscript>';
     $html .= MODULE_PAYMENT_TRAYC_JS_FINISH;
     $html .= ' <input type="submit" value="'.MODULE_PAYMENT_TRAYC_BTN_NEXT.'" name="pagamento_bb" style="cursor: hand;">';
     $html .= '</noscript>';
     $html .= '</form>';
     $html .= '</body>';
     $html .= '</html>';

     echo $html;
     exit;

     return false;

    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_TRAYC_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, date_added".
                   ") values (".
                   "'TrayCheckout', 'MODULE_PAYMENT_TRAYC_STATUS', 'True', ".
                   "'Ativar pagamento TrayCheckout?', '0', '1', ".
                   "'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "date_added".
                   ") values (".
                   "'Token da Conta', 'MODULE_PAYMENT_TRAYC_TOKEN', '', ".
                   "'Token da Conta no TrayCheckout', '6', '8', ".
                   "now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "date_added".
                   ") values (".
                   "'Tipo de Integra&ccedil;&atilde;o', 'MODULE_PAYMENT_TRAYC_INTEGRATION_TYPE', 'REDIRECT', ".
                   "'Tipo de Integra&ccedil;&atilde;o. (REDIRECT, FRAME ou MODAL)', '6', '8', ".
                   "now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, date_added".
                   ") values (".
                   "'Notifica&ccedil;&atilde;o Automm&aacute;tica de Status (NAS)', 'MODULE_PAYMENT_TRAYC_URL_NOTIFICATION', 'True', ".
                   "'Ativar recurso de atualiza&ccedil;&aacute;o autom&atilde;tica do status dos pedidos?', '0', '1', ".
                   "'tep_cfg_select_option(array(\'True\', \'False\') , ' , now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "date_added".
                   ") values (".
                   "'Prefixo do pedido', 'MODULE_PAYMENT_TRAYC_ORDER_PREFIX', '', ".
                   "'Prefixo enviado ao TrayCheckout para o n&uacute;mero do pedido', '6', '8', ".
                   "now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "date_added".
                   ") values (".
                   "'Ordem de exibi&ccedil;&atilde;o', 'MODULE_PAYMENT_TRAYC_SORT_ORDER', '0', ".
                   "'Determina a ordem de exibi&ccedil;&atilde;o do meio de pagamento.', '6', '8', ".
                   "now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, date_added".
                   ") values (".
                   "'Ambiente de Teste', 'MODULE_PAYMENT_TRAYC_SANDBOX', 'True', ".
                   "'Utilizar o ambiente de teste?', '0', '1', ".
                   "'tep_cfg_select_option(array(\'True\', \'False\') , ', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "date_added".
                   ") values (".
                   "'Meios de Pagamento Disponiveis', 'MODULE_PAYMENT_TRAYC_PAYMENTS_ABLES', '2,3,4,5,6,7,14,15,16,18,19,22,23', ".
                   "'C&oacute;digos dos meios de pagamento que deseja utilizar ".
                   "(2-Diners, 3-Visa, 4-Mastercard, 5-American Express,  6-Boleto Banc&aacute;rio, 7-TEF Ita&uacute;, ".
                   "14-Peela, 15-Discovery, 16-Elo, 18-Aura, 19-JCB, 22-TEF Bradesco, 23-TEF Banco do Brasil)', '6', '8', ".
                   "now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Pendente', 'MODULE_PAYMENT_TRAYC_STATUS_PENDING', '0', ".
                   "'Defina o status quando o pedido est&aacute; Aguardando Pagamento no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Em Processamento', 'MODULE_PAYMENT_TRAYC_STATUS_PROCCESSING', '0', ".
                   "'Defina o status quando o pedido est&aacute; Em Processamento no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Aprovado', 'MODULE_PAYMENT_TRAYC_STATUS_APPROVED', '0', ".
                   "'Defina o status quando o pedido est&aacute; Aprovado no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Cancelado', 'MODULE_PAYMENT_TRAYC_STATUS_CANCELED', '0', ".
                   "'Defina o status quando o pedido est&aacute; Cancelado no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Em Contesta&ccedil;&atilde;o', 'MODULE_PAYMENT_TRAYC_STATUS_CONTESTATION', '0', ".
                   "'Defina o status quando o pedido est&aacute; Em Contesta&ccedil;&atilde;o no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Em Monitoramento', 'MODULE_PAYMENT_TRAYC_STATUS_MONITORING', '0', ".
                   "'Defina o status quando o pedido est&aacute; Em Monitoramento no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Em Recupera&ccedil;&atilde;o', 'MODULE_PAYMENT_TRAYC_STATUS_RECOVERY', '0', ".
                   "'Defina o status quando o pedido est&aacute; Em Recupera&ccedil;&atilde;o  no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (".
                   "configuration_title, configuration_key, configuration_value, ".
                   "configuration_description, configuration_group_id, sort_order, ".
                   "set_function, use_function, date_added".
                   ") values (".
                   "'Status do Pedido Reprovado', 'MODULE_PAYMENT_TRAYC_STATUS_FAIL', '0', ".
                   "'Defina o status quando o pedido est&aacute; Reprovado no TrayCheckout.', '6', '0', ".
                   "'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }
    
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_TRAYC_STATUS', 'MODULE_PAYMENT_TRAYC_TOKEN',  
                   'MODULE_PAYMENT_TRAYC_INTEGRATION_TYPE', 'MODULE_PAYMENT_TRAYC_URL_NOTIFICATION',
                   'MODULE_PAYMENT_TRAYC_ORDER_PREFIX', 'MODULE_PAYMENT_TRAYC_SORT_ORDER',
                   'MODULE_PAYMENT_TRAYC_SANDBOX','MODULE_PAYMENT_TRAYC_PAYMENTS_ABLES',
                   'MODULE_PAYMENT_TRAYC_STATUS_PENDING', 'MODULE_PAYMENT_TRAYC_STATUS_PROCCESSING',
                   'MODULE_PAYMENT_TRAYC_STATUS_APPROVED', 'MODULE_PAYMENT_TRAYC_STATUS_CANCELED',
                   'MODULE_PAYMENT_TRAYC_STATUS_CONTESTATION', 'MODULE_PAYMENT_TRAYC_STATUS_MONITORING', 
                   'MODULE_PAYMENT_TRAYC_STATUS_RECOVERY', 'MODULE_PAYMENT_TRAYC_STATUS_FAIL');
    }
  }
?>