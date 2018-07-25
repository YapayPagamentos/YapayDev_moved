<?php
/*
* 2013 Tray
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Igor Cicotoste <ifredi@tray.net.br>
*  @copyright  TrayCheckout
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class traycheckout extends PaymentModule
{
	private $_html 			= '';
    private $_postErrors 	= array();
    public $currencies;
	
	public function __construct()
    {
    	
        $this->name 			= 'traycheckout';
        $this->tab 				= 'payments_gateways';
        $this->version 			= '1.0';

		$this->author 			= 'Ifredi';
        $this->currencies 		= true;
        $this->currencies_mode 	= 'checkbox';

        parent::__construct();

        $this->page 			= basename(__file__, '.php');
        $this->displayName 		= $this->l('TrayCheckout');
        $this->description 		= $this->l('Aceitar pagamentos via TrayCheckout');
		$this->confirmUninstall = $this->l('Tem certeza de que pretende eliminar os seus dados?');
		$this->textshowemail 	= $this->l('Você deve seguir corretamente os procedimentos de pagamento do TrayCheckout, para que sua compra seja validada.');
	}
	
	public function install()
	{
		
		if ( !Configuration::get('traycheckout_STATUS_1') )
			$this->create_states();
		if 
		(
			!parent::install() 
		OR 	!Configuration::updateValue('traycheckout_TOKEN', 	  '')
		OR 	!Configuration::updateValue('traycheckout_URLRETORNO', 	   Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/traycheckout/notification.php')
		OR 	!Configuration::updateValue('traycheckout_SANDBOX',   0)
		OR 	!Configuration::updateValue('traycheckout_REDIRECT',   0)
		OR 	!Configuration::updateValue('traycheckout_PREFIXO',   '')
		OR 	!$this->registerHook('payment') 
		OR 	!$this->registerHook('paymentReturn')
		OR 	!$this->registerHook('orderConfirmation')
		
		)
			return false;
			
		return true;
	}

	
	public function create_states()
	{
		$this->order_state = array(
		array( 'RoyalBlue', '00100', 'TrayCheckout - Transação em Andamento', '' ),
		array( 'LimeGreen', '11110', 'TrayCheckout - Transação Aprovada','payment' ),
		array( 'Crimson',   '01110', 'TrayCheckout - Transação Cancelada', 'order_canceled'	),
		array( '#8f0621',   '00100', 'TrayCheckout - Recuperação de Pagamento', 'payment_error' ),
		array( '#ec2e15',   '00100', 'TrayCheckout - Contestação de Pagamento', 'refund' )
		);
		
		
		$languages = Db::getInstance()->ExecuteS('
		SELECT `id_lang`, `iso_code`
		FROM `'._DB_PREFIX_.'lang`
		');
		
		foreach ($this->order_state as $key => $value)
		{
			Db::getInstance()->Execute
			 ('
				INSERT INTO `' . _DB_PREFIX_ . 'order_state` 
			( `invoice`, `send_email`, `color`, `unremovable`, `logable`, `delivery`) 
				VALUES
			('.$value[1][0].', '.$value[1][1].', \''.$value[0].'\', '.$value[1][2].', '.$value[1][3].', '.$value[1][4].');
			');
			$order_state 	=  Db::getInstance()->Insert_ID();
			
			foreach ( $languages as $language_atual )
			{
				Db::getInstance()->Execute
				('
					INSERT INTO `' . _DB_PREFIX_ . 'order_state_lang` 
				(`id_order_state`, `id_lang`, `name`, `template`)
					VALUES
				('.$order_state .', '.$language_atual['id_lang'].', \''.$value[2].'\', \''.$value[3].'\');
				');
				
			}
			$file = (dirname ( __file__ ) . "/icons/$key.gif");
			$newfile = (dirname ( dirname ( dirname ( __file__ ) ) ) . "/img/os/$order_state.gif");
			if (! copy ( $file, $newfile )) {
				return false;
			}
			Configuration::updateValue ( "traycheckout_STATUS_$key", $order_state );
		}
		
		return true;
		
	}

	public function uninstall()
	{
		if 
		(
			!Configuration::deleteByName('traycheckout_TOKEN')
		OR	!Configuration::deleteByName('traycheckout_URLRETORNO')
		OR	!Configuration::deleteByName('traycheckout_SANDBOX')
		OR	!Configuration::deleteByName('traycheckout_REDIRECT')
		OR	!Configuration::deleteByName('traycheckout_PREFIXO')
		OR 	!parent::uninstall()
		) 
			return false;
		
		return true;
	}

	public function getContent()
	{
		$this->_html = '<h2>TrayCheckout</h2>';
		
		if (isset ( $_POST ['submittraycheckout'] )) {

			if (empty ( $_POST ['token_store'] ))
				$this->_postErrors [] = $this->l ( 'Digite o token para utilizar o módulo de pagamento TrayCheckout' );
				
			if (empty ( $_POST ['url_notification'] ))
				$this->_postErrors [] = $this->l ( 'É necessário configurar a Url de notificação para atualizar o status dos pedidos' );
				
				
			
			if (! sizeof ( $this->_postErrors )) {
				if (! empty ( $_POST ['token_store'] )) {
					Configuration::updateValue ( 'traycheckout_TOKEN', $_POST ['token_store'] );
				}
				if (! empty ( $_POST ['url_notification'] )) {
					Configuration::updateValue ( 'traycheckout_URLRETORNO', $_POST ['url_notification'] );
				}
				if (isset ( $_POST ['sandbox'] )) {
					Configuration::updateValue ( 'traycheckout_SANDBOX', $_POST ['sandbox'] );
				}
				if (isset( $_POST ['redirect'] )) {
					Configuration::updateValue ( 'traycheckout_REDIRECT', $_POST ['redirect'] );
				}
				if (isset ( $_POST ['prefixo'] )) {
					Configuration::updateValue ( 'traycheckout_PREFIXO', $_POST ['prefixo'] );
				}
				
				$this->displayConf ();
			} else
				$this->displayErrors ();
		}

		$this->displayFormSettings();
		return $this->_html;
	}
	
	public function displayConf()
	{
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Configurações atualizadas').'
		</div>';
	}
	
	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
			foreach ($this->_postErrors AS $error)
				$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	public function displayFormSettings()
	{
		$conf= Configuration::getMultiple(
			array(
				'traycheckout_TOKEN',
				'traycheckout_URLRETORNO',
				'traycheckout_SANDBOX',
				'traycheckout_REDIRECT',
				'traycheckout_PREFIXO'
			)
		);
		
		$token_store 	= array_key_exists('token_store', $_POST) ? $_POST['token_store'] : (array_key_exists('traycheckout_TOKEN', $conf) ? $conf['traycheckout_TOKEN'] : '');
		$url_notification	= array_key_exists('url_notification', $_POST) ? $_POST['url_notification'] : (array_key_exists('traycheckout_URLRETORNO', $conf) ? $conf['traycheckout_URLRETORNO'] : '');
		$opt_sandbox	= array_key_exists('sandbox', $_POST) ? $_POST['sandbox'] : (array_key_exists('traycheckout_SANDBOX', $conf) ? $conf['traycheckout_SANDBOX'] : '0');
		$opt_redirect	= array_key_exists('redirect', $_POST) ? $_POST['redirect'] : (array_key_exists('traycheckout_REDIRECT', $conf) ? $conf['traycheckout_REDIRECT'] : '0');
		$prefixo		= array_key_exists('prefixo', $_POST) ? $_POST['prefixo'] : (array_key_exists('traycheckout_PREFIXO', $conf) ? $conf['traycheckout_PREFIXO'] : '');
		
		$opt_sandbox_ativo		= ($opt_sandbox) ? "selected": ""; 
		$opt_sandbox_inativo = ($opt_sandbox) ? "": "selected";
		$opt_redirect_ativo		= ($opt_redirect) ? "selected": ""; 
		$opt_redirect_inativo = ($opt_redirect) ? "": "selected";
		
		/** /FORMULÁRIO DE CONFIGURAÇÃO DO EMAIL E DO TOKEN **/
		$arrContent = array(
			'msg_aceite_traycheckout'=>$this->l('Este módulo permite aceitar pagamentos via TrayCheckout'),
			'msg_cliente_escolher_traycheckout'=>$this->l('Se o cliente escolher o módulo de pagamento, a conta do TrayCheckout será automaticamente creditado'),
			'msg_configurar_email'=>$this->l('Você precisa configurar o seu token do TrayCheckout, para depois usar este módulo'),
			'configuracoes'=>$this->l('Configurações'),
			'email_cobranca'=>$this->l('E-mail para cobrança'),
			'token'=>$this->l('Token'),
			'url_notification'=>$this->l('URL de notificação de status'),
			'prefixo_pedido'=>$this->l('Prefixo pedido'),
			'atencao_ativar_teste'=>'ATENÇÃO AO ATIVAR O MODO TESTE',
			'msg1_ativar_teste'=>'Você ainda não poderá aceitar pagamentos',
			'msg2_ativar_teste'=>'Será necessário criar uma conta no site sandbox TrayCheckout (http://sandbox.traycheckout.com.br/)',
			'msg3_ativar_teste'=>'Será necessário voltar à página do módulo TrayCheckout para configurar com os dados corretos antes de colocar no ar',
			'ativar_teste'=>$this->l('Ativar Teste (Sandbox)'),
			'ativar_redirect'=>$this->l('Ativar TrayCheckout via Redirect'),
			'atualizar'=>$this->l('Atualizar')
		);
		
		$this->smarty->assign(
			array(
				'action_form' => $_SERVER['REQUEST_URI'],
				'arrContent' => $arrContent,
				'token_store' => $token_store,
				'url_notification' => $url_notification,
				'opt_sandbox' => $opt_sandbox,
				'opt_redirect' => $opt_redirect,
				'prefixo' => $prefixo,
				'sandbox_selected' => array('opt_sandbox_ativo'=> $opt_sandbox_ativo, 'opt_sandbox_inativo'=> $opt_sandbox_inativo),	
				'redirect_selected' => array('opt_redirect_ativo'=> $opt_redirect_ativo, 'opt_redirect_inativo'=> $opt_redirect_inativo),
				'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
			)
		);
		
		$this->_html .= $this->display(__FILE__, '/views/templates/back/back_office.tpl');
	}
	
	public function hookPayment($params)
	{		
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;

		$this->smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	public function formPost($params, $order_id=null)
    {
		include(dirname(__FILE__).'/includes/Adress.php');
		$order_number 	= ($params['objOrder']->id) ? $params['objOrder']->id : $order_id;
		$DadosOrder 	= new Order($order_number);
		
		$carrier = new Carrier($DadosOrder->id_carrier);
		$shipping_type = 'Frete com '. $carrier->name;
		
		$currency 			= new Currency($DadosOrder->id_currency);
		$shipping_price		= number_format( Tools::convertPrice( $DadosOrder->total_shipping, $currency), 2, '.', '');
		$price_discount		= number_format( Tools::convertPrice( $DadosOrder->total_discounts, $currency), 2, '.', '');
		$total_paid			= number_format( Tools::convertPrice( $DadosOrder->total_paid, $currency), 2, '.', '');
		$total_products_wt	= number_format( Tools::convertPrice( $DadosOrder->total_products_wt, $currency), 2, '.', '');
		
		$total_paid_conf  		= $total_products_wt + $shipping_price - $price_discount;
		$shipping_discount = number_format( (float)$total_paid_conf , 2, '.', '') - (float)$total_paid;

		$price_additional = 0;
		$shipping_price = number_format( (float)$shipping_price, 2, '.', '') - number_format( (float)$shipping_discount, 2, '.', '');
		
		if($shipping_price <0) $shipping_price  = 0;
		
		
		$ArrayListaProdutos = $DadosOrder->getProducts();
		$Customer           = new Customer(intval($this->context->customer->id));
		$fieldsCustomer		= $Customer->getFields();
		$fieldsCustomerAdress = Db::getInstance()->getRow('SELECT a.*, cl.`name` AS country, s.iso_code AS state
														FROM `'._DB_PREFIX_.'address` a
														LEFT JOIN `'._DB_PREFIX_.'country` c ON a.`id_country` = c.`id_country`
														LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON c.`id_country` = cl.`id_country`
														LEFT JOIN `'._DB_PREFIX_.'state` s ON s.`id_state` = a.`id_state`
														WHERE `id_lang` = '.intval($this->context->language->id).'
														AND `id_customer` = '.intval($this->context->customer->id).'
														AND a.`deleted` = 0');
														
		$url_notification = Configuration::get('traycheckout_URLRETORNO');
		
		/** ADICIONA OS PRODUTOS AO ARRAY **/
		foreach($ArrayListaProdutos as $info) {
			$product = array (
				'code'         => uniqid(), 
				'description'  => $info['product_name'],
				'quantity' => $info['product_quantity'],
				'price_unit'      => $info['product_price_wt']
			);
			$transaction_products[]=$product;
		}
	
		/** ADICIONA OS DADOS CLIENTE **/
		$arrCustomer = array( 'name'   => $fieldsCustomer['firstname'].' '.$fieldsCustomer['lastname'],
							  'email'  => $fieldsCustomer['email'],
		);
			
		
		$Adress = new Adress();
		list($street, $number, $completion) = $Adress->getAdress($fieldsCustomerAdress['address1']);
		$arrCustomer['addresses'] = array (
		   'postal_code'    	=> $fieldsCustomerAdress['postcode'],
		   'street'    			=> $street,
		   'number'    			=> $number,
		   'completion'    		=> $completion,
		   'neighborhood'   	=> $fieldsCustomerAdress['address2'],
		   'city' 				=> $fieldsCustomerAdress['city'],
		   'state' 				=> $fieldsCustomerAdress['state'],
		);
		$arrCustomer['contacts'] = array (
		  'type_contact'    => 'H',
		   'number_contact'    => $fieldsCustomerAdress['phone'],
		);

		$prefixo = Configuration::get ( 'traycheckout_PREFIXO' );
		
		$url_return = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__. '?fc=module&module=traycheckout&controller=return&id_order='.$order_number;
		$url_success =  $url_return.'&action=sucess';
		$url_process =  $url_return.'&action=process';	
		$this->smarty->assign(array(
			'order_number' 		=> $prefixo . $order_number,
			'token_account' => Configuration::get('traycheckout_TOKEN'),
			'customer' 	=> $arrCustomer,
			'transaction_product' 	=> $transaction_products,
			'price_discount' 	=> $price_discount,
			'price_additional' 	=> $price_additional,
			'shipping_price' 	=> $shipping_price,
			'shipping_type' 	=> $shipping_type,		
			'total_paid' 	=> $total_paid,
			'url_notification' => $url_notification,
			'url_success' => Tools::htmlentitiesUTF8($url_success),
			'url_process' => Tools::htmlentitiesUTF8($url_process)
		));
		
		return $this->display(__file__, 'form_post.tpl');
    }
	
	public function hookPaymentReturn($params)
    {
    	$form = $this->formPost($params);
    	$this->context->controller->addJquery();
		$this->context->controller->addJQueryPlugin('thickbox');
		$order_number = $params['objOrder']->id;

		$DadosOrder 		= new Order($order_number);
 
		$total_paid			= number_format( Tools::convertPrice( $DadosOrder->total_paid, $currency), 2, '.', '');
		if ((int)Configuration::get('traycheckout_SANDBOX') == 1)
			$post_url = 'http://checkout.sandbox.tray.com.br/payment/transaction';
		else
			$post_url = 'https://checkout.tray.com.br/payment/transaction';
			
		$this->smarty->assign(array(
			'status' 		=> 'ok', 
			'secure_key' 	=> $params['objOrder']->secure_key,
			'id_order' 		=> $order_number,
			'id_module' 	=> $this->id,
			'total_paid' 	=> Tools::displayPrice($total_paid, $currency),
			'post_url' => $post_url,
			'redirect' => (int)Configuration::get('traycheckout_REDIRECT'),
			'PAYMENT_FIELDS' => $form
		));
		return $this->display(__file__, 'order-confirmation.tpl');
    }
	
    /**
     * Check the currency
     * 
     * @param Cart $cart
     * @return boolean
     */
    public function checkCurrency($cart) {
        $currency_order = new Currency((int) ($cart->id_currency));
        $currencies_module = $this->getCurrency((int) $cart->id_currency);

        if (is_array($currencies_module))
            foreach ($currencies_module as $currency_module)
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
        return false;
    }
	
}
?>