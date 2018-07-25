<?php
/*
  traycheckout.php 05/12/2013
  Modulo Yapay Intermediador - osCommerce 2.2
  Pagina de finalizacao de compra na plataforma com o Yapay Intermediador
*/
 error_reporting(0);
 ini_set('display_errors', 0);
 
  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
 /* if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }*/

   if (!$_POST['orders_id']) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  } else {
    $cart->reset(true);
    //tep_session_unregister('sendto');
    //tep_session_unregister('billto');
    //tep_session_unregister('shipping');
    //tep_session_unregister('payment');
    //tep_session_unregister('comments');
  }
  
  require_once('./ext/modules/payment/traycheckout/includes/classes/TrayCheckout.class.php');
  $cfgTrayCheckout = new TrayCheckout();
  
  /**
  * Seleciona Dados da Venda
  */
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
              ot.value as frete_value,
              ot.title as frete_name
            from
              orders o,
              orders_total ot
            where
              o.orders_id='".$_POST['orders_id']."' and
              o.orders_id=ot.orders_id and
              ot.class='ot_shipping'";
  $selectOrder = tep_db_query($query);
  $showOrder = tep_db_fetch_array($selectOrder);
  
  /**
  * Seleciona Produtos da Venda
  */
  $query = "select
              products_id,
              products_name,
              final_price,
              products_quantity
            from
              orders_products
            where
              orders_id='".$_POST['orders_id']."'";
  $selectProduct = tep_db_query($query);
  $products = array();
  while ($showProduct = tep_db_fetch_array($selectProduct)) {
    $products[] = array('products_id' => $showProduct['products_id'],
                        'products_name' => $showProduct['products_name'],
                        'final_price' => $showProduct['final_price'],
                        'products_quantity' => $showProduct['products_quantity'],
                        );
  }
  
?>

<?php
    switch ($cfgTrayCheckout->getIntegrationType()) {
        case 'FRAME':
              $txtFinish = "Sua compra est&aacute; em processo de finaliza&ccedil;&atilde;o.";
              $wTcFrame = "800";
              $hTcFrame = "1200";
              $targetForm = "_frame";
              $displayModal = "none";
            break;
        case 'MODAL':
              $txtFinish = "Sua compra est&aacute; em processo de finaliza&ccedil;&atilde;o.<br />Caso a p&aacute;gina de finaliza&ccedil;&atilde;o de pagamento n&atilde;o se inicie automaticamente, <a href='javascript:void(0)' id='tc_lightbox'>Clique Aqui</a>";
              $wTcFrame = "0";
              $hTcFrame = "0";
              $targetForm = "_modal";
              $displayModal = "block";
            break;
        case 'REDIRECT':
        default:
              $txtFinish = "Sua compra est&aacute; em processo de finaliza&ccedil;&atilde;o.<br />Voc&ecirc; ser&aacute; redirecionado para o Tray Checkout. Caso a p&aacute;gina de finaliza&ccedil;&atilde;o de pagamento n&atilde;o se inicie automaticamente, <a href='javascript:void(0)' id='tc_lightbox'>Clique Aqui</a>";
              $wTcFrame = "0";
              $hTcFrame = "0";
              $targetForm = "_redir";
              $displayModal = "none";
            break;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onload="document.formTrayCheckout.submit()">

<div id="overlay-tc" style="position: fixed;background: url('./ext/modules/payment/traycheckout/images/overlay.png');display: <?php echo $displayModal;?>; width: 100%; height:100%">
	<center>
		<div style="padding: 10px;background-color: #fff;width: 980px;margin-top: 25px;height:560px;" id="lightbox">
			<img style="position: relative;margin-left: 970px;margin-top: -20px;" src="./ext/modules/payment/traycheckout/images/close.png">
			<iframe src="./ext/modules/payment/traycheckout/loading.php" width="100%" height="550px" style="border:0px;margin-top: -10px;" name="traycheckout_modal"></iframe>
		</div>
	</center>
</div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
        
          <!-- CONTEUDO DO PAGAMENTO -->
          <h2 class="modulo_pagamento">Yapay Intermediador</h2>
          <table width="100%" cellspadding="0" cellspacing="0">
            <tr>
              <td class="main"><?php echo $txtFinish; ?></td>
            </tr>
            <tr>
              <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            </tr>
            <tr>
              <td style="height: 30px;"></td>
            </tr>
            <tr>
              <td style="height: 30px;"></td>
            </tr>
            <tr>
              <td style="text-align: center;" class="main">
                <div id="pdStep1">                       
                  <form name="formTrayCheckout" id="formTrayCheckout" method="post" action="<?php echo $cfgTrayCheckout->getUrlPost(); ?>" target="traycheckout<?php echo $targetForm;?>">
                    <input type="hidden" name="token_account" value="<?php echo $cfgTrayCheckout->getToken(); ?>" />
                    <input type="hidden" name="free" value="OSCOMMERCE_v1.1" />
                    <?
                    for ($i=0, $total=count($products); $i<$total; $i++) {
                    ?>
                      <input type="hidden" name="transaction_product[][code]" value="<?php echo $products[$i]['products_id']; ?>" />
                      <input type="hidden" name="transaction_product[][description]" value="<?php echo utf8_encode($products[$i]['products_name']); ?>" />
                      <input type="hidden" name="transaction_product[][quantity]" value="<?php echo $products[$i]['products_quantity']; ?>" />
                      <input type="hidden" name="transaction_product[][price_unit]" value="<?php echo number_format($products[$i]['final_price'], 2, '.', ''); ?>" />
                    <?
                    }
                    ?>
                   
                    <input type="hidden" name="order_number" value="<?php echo $cfgTrayCheckout->getOrderPrefix().$showOrder['orders_id']; ?>" />
                    <?php
                        if (floatval($showOrder['frete_value']) > 0){
                    ?>
                    <input type="hidden" name="shipping_type" value="<?php echo utf8_encode($showOrder['frete_name']); ?>" />
                    <input type="hidden" name="shipping_price" value="<?php echo number_format($showOrder['frete_value'], 2, '.', ''); ?>" />
                    <?php
                        }
                    ?>
                    <input type="hidden" name="customer[name]" value="<?php echo utf8_encode($showOrder['customers_name']); ?>" />
                    <input type="hidden" name="customer[email]" value="<?php echo $showOrder['customers_email_address']; ?>" />
                    <input type="hidden" name="cpf" value="" />
                    
                    <input type="hidden" name="customer[contacts][][type_contact]" id="telefone" value="H" />
                    <input type="hidden" name="customer[contacts][][number_contact]" value="<?php echo $showOrder['customers_telephone']; ?>" />
                    
                    <input type="hidden" name="customer[addresses][][type_address]" value="D" />
                    <input type="hidden" name="customer[addresses][][postal_code]" value="<?php echo $showOrder['delivery_postcode']; ?>" />
                    <input type="hidden" name="customer[addresses][][street]" value="<?php echo utf8_encode($showOrder['delivery_street_address']); ?>" />
                    <input type="hidden" name="customer[addresses][][neighborhood]" value="<?php echo utf8_encode($showOrder['delivery_suburb']); ?>" />
                    <input type="hidden" name="customer[addresses][][city]" value="<?php echo utf8_encode($showOrder['delivery_city']); ?>" />
                    <input type="hidden" name="customer[addresses][][state]" value="<?php echo utf8_encode($showOrder['delivery_state']); ?>" />
                    
                    <input type="hidden" name="customer[addresses][][type_address]" value="B" />
                    <input type="hidden" name="customer[addresses][][postal_code]" value="<?php echo $showOrder['billing_postcode']; ?>" />
                    <input type="hidden" name="customer[addresses][][street]" value="<?php echo utf8_encode($showOrder['billing_street_address']); ?>" />
                    <input type="hidden" name="customer[addresses][][neighborhood]" value="<?php echo utf8_encode($showOrder['billing_suburb']); ?>" />
                    <input type="hidden" name="customer[addresses][][city]" value="<?php echo utf8_encode($showOrder['billing_city']); ?>" />
                    <input type="hidden" name="customer[addresses][][state]" value="<?php echo utf8_encode($showOrder['billing_state']); ?>" />
                    
                    <input type="hidden" name="url_process" value="<?php echo $cfgTrayCheckout->getUrlNotification(); ?>?orders_id=<?php echo $cfgTrayCheckout->getOrderPrefix().$showOrder['orders_id']; ?>" />
                    <input type="hidden" name="url_success" value="<?php echo $cfgTrayCheckout->getUrlNotification(); ?>?orders_id=<?php echo $cfgTrayCheckout->getOrderPrefix().$showOrder['orders_id']; ?>" />
                    
                    <input type="hidden" name="url_notification" value="<?php echo $cfgTrayCheckout->getUrlNotification(); ?>?orders_id=<?php echo $cfgTrayCheckout->getOrderPrefix().$showOrder['orders_id']; ?>" />
                    <input type="hidden" name="available_payment_methods" value="<?php echo $cfgTrayCheckout->getPaymentsAbles(); ?>" />
                    
                  </form>
                </div>                  
                <iframe src="./ext/modules/payment/traycheckout/loading.php" name="traycheckout_frame" width="<?php echo $wTcFrame;?>" height="<?php echo $hTcFrame;?>" style="border:0px" ></iframe>
              </td>
            </tr>
            <tr>
              <td style="height: 30px;"></td>
            </tr>
          </table>
        <!-- // CONTEUDO DO PAGAMENTO -->        
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
<?php
    switch ($cfgTrayCheckout->getIntegrationType()) {
        case 'FRAME':
            break;
        case 'MODAL':
              ?>
<script type="text/javascript">
    document.getElementById('overlay-tc').onclick = function (){
        this.style.display = "none";
    };
    document.getElementById('tc_lightbox').onclick = function (){
        document.getElementById('overlay-tc').style.display = "block";
    };
    //document.getElementById('traycheckout_frame').onload = function(){alert("Ok")};//parent.document.getElementById('loading_traycheckout_frame').style.display = 'none'
    
</script>
              <?php
            break;
        case 'REDIRECT':
        default:
              ?>
<script type="text/javascript">
    document.getElementById('tc_lightbox').onclick = function (){
        document.formTrayCheckout.submit();
    };
</script>
              <?php
            break;
    }
?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
