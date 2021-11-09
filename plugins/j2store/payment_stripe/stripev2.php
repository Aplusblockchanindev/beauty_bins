<?php
/**
 * --------------------------------------------------------------------------------
 * Payment Plugin - Stripe
 * --------------------------------------------------------------------------------
 * @package     Joomla 2.5 -  3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2014-19 J2Store . All rights reserved.
 * @license     GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/payment.php');

class plgJ2StorePayment_stripe extends J2StorePaymentPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element    = 'payment_stripe';
    var $_isLog      = false;
    var $_j2version = null;
    private $publish_key;
    private $secret_key;

    function __construct(& $subject, $config) {
		parent::__construct ( $subject, $config );
		$this->loadLanguage ( '', JPATH_ADMINISTRATOR );
		$this->_j2version = $this->getVersion ();
		if ($this->params->get ( 'debug', 0 )) {
			$this->_isLog = true;
		}
		
		if ($this->params->get ( 'sandbox', 0 )) {
			// get sandbox credentials
			$this->secret_key = trim ( $this->_getParam ( 'stripe_test_secret_key' ) );
			$this->publish_key = trim ( $this->_getParam ( 'stripe_test_publish_key' ) );
		} else {
			$this->secret_key = trim ( $this->_getParam ( 'stripe_secret_key' ) );
			$this->publish_key = trim ( $this->_getParam ( 'stripe_publish_key' ) );
		}
	}

	function _beforePayment($order) {
		//get surcharge if any
		$surcharge = 0;
	
		$surcharge_percent = $this->params->get('surcharge_percent', 0);
		$surcharge_fixed = $this->params->get('surcharge_fixed', 0);
		if((float) $surcharge_percent > 0 || (float) $surcharge_fixed > 0) {
	
			//percentage
			if((float) $surcharge_percent > 0) {
				$surcharge += ($order->order_total * (float) $surcharge_percent) / 100;
			}
	
			if((float) $surcharge_fixed > 0) {
				$surcharge += (float) $surcharge_fixed;
			}
			//make sure it is formated to 2 decimals
	
			$order->order_surcharge = round($surcharge, 2);
			$order->calculateTotals();
		}
	
	}
	
    /**
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment($data) {
		// initialise
		$app = JFactory::getApplication ();
		JFactory::getDocument ()->addScript ( 'https://js.stripe.com/v2/stripe.js' );
		// prepare the payment form
		$vars = new JObject ();
		$vars->url = JRoute::_ ( "index.php?option=com_j2store&view=mycart" );
		$vars->order_id = $data ['order_id'];
		$vars->orderpayment_id = $data ['orderpayment_id'];
		$vars->orderpayment_amount = $data ['orderpayment_amount'];
		$vars->orderpayment_type = $this->_element;
		$vars->cardname = $app->input->getString ( "cardholder" );
		$vars->cardnum = $app->input->getString ( "cardnum" );
		
		$vars->cardmonth = $app->input->getString ( "month" );
		$vars->cardyear = $app->input->getString ( "year" );
		$card_exp = $vars->cardmonth . '' . $vars->cardyear;
		$vars->cardexp = $card_exp;
		
		$vars->cardcvv = $app->input->getString ( "cardcvv" );
		$vars->cardnum_last4 = substr ( $app->input->getString ( "cardnum" ), - 4 );
		
		$vars->publish_key = $this->publish_key;
		
		$vars->display_name = $this->params->get ( 'display_name', 'PLG_J2STORE_PAYMENT_STRIPE' );
		$vars->onbeforepayment_text = $this->params->get ( 'onbeforepayment', '' );
		$vars->button_text = $this->params->get ( 'button_text', 'J2STORE_PLACE_ORDER' );
		
		$html = $this->_getLayout ( 'prepayment', $vars );
		return $html;
	}
	
	/**
	 * Processes the payment form
	 * and returns HTML to be displayed to the user
	 * generally with a success/failed message
	 *
	 * @param $data array
	 *        	form post data
	 * @return string HTML to display
	 */
	function _postPayment($data) {
		// Process the payment
		$vars = new JObject ();
		
		$app = JFactory::getApplication ();
		$paction = $app->input->getString ( 'paction' );
		
		switch ($paction) {
			case 'display' :
				$html = JText::_ ( $this->params->get ( 'onafterpayment', '' ) );
				$html .= $this->_displayArticle ();
				break;
			case 'process' :
				$result = $this->_process ();
				echo json_encode ( $result );
				$app->close ();
				break;
			default :
				$vars->message = JText::_ ( $this->params->get ( 'onerrorpayment', '' ) );
				$html = $this->_getLayout ( 'message', $vars );
				break;
		}
		
		return $html;
	}

    /**
     * Prepares variables and
     * Renders the form for collecting payment info
     *
     * @return unknown_type
     */
    function _renderForm($data) {
		$vars = new JObject ();
		$vars->prepop = array ();
		$vars->onselection_text = $this->params->get ( 'onselection', '' );
		$vars->version = $this->_j2version;
		$html = $this->_getLayout ( 'form', $vars );
		
		return $html;
	}
	
	/**
	 * Verifies that all the required form fields are completed
	 * if any fail verification, set
	 * $object->error = true
	 * $object->message .
	 * = '<li>x item failed verification</li>'
	 *
	 * @param $submitted_values array
	 *        	post data
	 * @return unknown_type
	 */
	function _verifyForm($submitted_values) {
		$object = new JObject ();
		$object->error = false;
		$object->message = '';
		$user = JFactory::getUser ();
		
		foreach ( $submitted_values as $key => $value ) {
			switch ($key) {
				
				case "cardholder" :
					if (! isset ( $submitted_values [$key] ) || ! JString::strlen ( $submitted_values [$key] )) {
						$object->error = true;
						$object->message .= "<li>" . JText::_ ( "J2STORE_STRIPE_VALIDATION_ENTER_CARDHOLDER_NAME" ) . "</li>";
					}
					break;
				case "cardnum" :
					if (! isset ( $submitted_values [$key] ) || ! JString::strlen ( $submitted_values [$key] )) {
						$object->error = true;
						$object->message .= "<li>" . JText::_ ( "J2STORE_STRIPE_MESSAGE_CARD_NUMBER_INVALID" ) . "</li>";
					}
					break;
				case "month" :
					if (! isset ( $submitted_values [$key] ) || ! JString::strlen ( $submitted_values [$key] )) {
						$object->error = true;
						$object->message .= "<li>" . JText::_ ( "J2STORE_STRIPE_MESSAGE_CARD_EXPIRATION_DATE_INVALID" ) . "</li>";
					}
					break;
				case "year" :
					if (! isset ( $submitted_values [$key] ) || ! JString::strlen ( $submitted_values [$key] )) {
						$object->error = true;
						$object->message .= "<li>" . JText::_ ( "J2STORE_STRIPE_MESSAGE_CARD_EXPIRATION_DATE_INVALID" ) . "</li>";
					}
					break;
				case "cardcvv" :
					if (! isset ( $submitted_values [$key] ) || ! JString::strlen ( $submitted_values [$key] )) {
						$object->error = true;
						$object->message .= "<li>" . JText::_ ( "J2STORE_STRIPE_MESSAGE_CARD_CVV_INVALID" ) . "</li>";
					}
					break;
				default :
					break;
			}
		}
		
		return $object;
	}


	/**
	 * Gets a value of the plugin parameter
	 *
	 * @param string $name
	 * @param string $default
	 * @return string
	 * @access protected
	 */
	function _getParam($name, $default = '') {
		$sandbox_param = "sandbox_$name";
		$sb_value = $this->params->get ( $sandbox_param );
		
		if ($this->params->get ( 'sandbox' ) && ! empty ( $sb_value )) {
			$param = $this->params->get ( $sandbox_param, $default );
		} else {
			$param = $this->params->get ( $name, $default );
		}
		
		return $param;
	}

	/**
	 * Processes the payment
	 *
	 * This method process only real time (simple) payments
	 *
	 * @return string
	 * @access protected
	 */
	function _process() {
		/*
		 * perform initial checks
		 */
		$app = JFactory::getApplication ();
		$json = array ();
		$error = '';
		// get the post data
		$data = $app->input->getArray ( $_POST );
		
		if (! JRequest::checkToken ()) {
			$json ['error'] = JText::_ ( 'J2STORE_STRIPE_INVALID_TOKEN' );
		}
		
		// get order information
		JTable::addIncludePath ( JPATH_ADMINISTRATOR . '/components/com_j2store/tables' );
		$order = JTable::getInstance ( 'Orders', 'Table' );
		$order->load ( $data ['orderpayment_id'] );
		
		// run initial checks
		if (empty ( $order->order_id )) {
			$json ['error'] = JText::_ ( 'J2STORE_STRIPE_INVALID_ORDER' );
		}
		
		if (empty ( $data ['stripeToken'] )) {
			$json ['error'] = JText::_ ( 'J2STORE_STRIPE_TOKEN_MISSING' );
		}
		
		if (! $json) {
			

			
			// get the order info from order info table
			$orderinfo = $this->_getOrderInfo ( $order->order_id );

			//require_once (JPath::clean ( dirname ( __FILE__ ) . "/library/Stripe.php" ));
			require_once JPATH_SITE."/plugins/j2store/".$this->_element."/library/vendor/autoload.php";
			// initialise stripe object
			Stripe\Stripe::setApiKey($this->secret_key);
			Stripe\Stripe::setAppInfo("Stripe", "1.20", "https://www.j2store.org");
			
			$currency_values = $this->getCurrency ( $order );
			$amount = $this->getAmount ( $order->orderpayment_amount, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert'] );

			$check_currency = $this->checkCurrency($currency_values ['currency_code']);
			if($check_currency){
				$amount = round($amount,0);
			}else{
				$amount = $amount * 100;
			}

			if (isset ( $order->invoice_number ) && $order->invoice_number > 0) {
				$invoice_number = $order->invoice_prefix . $order->invoice_number;
			} else {
				$invoice_number = $order->id;
			}
			
			$description = JText::sprintf ( "J2STORE_STRIPE_ORDER_DESCRIPTION", $invoice_number );
			
			$capture = $this->params->get ( 'stripe_charge_mode', 1 ) ? true : false;

			//$order_info = $order->getOrderInformation();
			$charge = array (
				"amount" => $amount,
				"currency" => $currency_values ['currency_code'],
				"source" => $data ['stripeToken'],
				"description" => $description,
				"capture" => $capture
			);

			$send_shipping = $this->params->get('send_shipping',0);
			if($send_shipping){
				$country_code = null;
				if(!empty($data['orderinfo']['shipping_country_id'])){
					$country_code = $this->_getCountryCode($data['orderinfo']['shipping_country_id'])->country_isocode_2;
				}

				$address = array(
					"line1" => !empty($data['orderinfo']['shipping_address_1']) ? $data['orderinfo']['shipping_address_1'] : null,//$order_info->shipping_address_1,
					"line2" => !empty($data['orderinfo']['shipping_address_2']) ? $data['orderinfo']['shipping_address_2'] : null,//$order_info->shipping_address_2,
					"city" => !empty($data['orderinfo']['shipping_city']) ? $data['orderinfo']['shipping_city'] : null,//$order_info->shipping_city,
					"state" => !empty($data['orderinfo']['shipping_zone_name']) ? $data['orderinfo']['shipping_zone_name']: null,//$this->getZoneById($order_info->shipping_zone_id)->zone_name,
					"postal_code" => !empty($data['orderinfo']['shipping_zip']) ? $data['orderinfo']['shipping_zip'] : null,//$order_info->shipping_zip,
					"country" => $country_code//$this->getCountryById($order_info->shipping_country_id)->country_isocode_2
				);
				$shippingDetails = array(
					"name" =>  $data['orderinfo']['shipping_first_name']." ".$data['orderinfo']['shipping_last_name'],
					"address" => $address
				);

				$charge['shipping'] = $shippingDetails;
			}

			$transaction_details = '';
			try {
				$ssl_version = $this->params->get('ssl_version_v1',0);
				if($ssl_version){
					$curl = new \Stripe\HttpClient\CurlClient(array(CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1));
					Stripe\ApiRequestor::setHttpClient($curl);
				}
				//$response = Stripe_Charge::create ( $charge );
				$response = Stripe\Charge::create($charge);
				if ($response) {
					
					$transaction_id = $response->id;
					$transaction_details = $this->_getFormattedTransactionDetails ( $response->__toArray());
					//$transaction_details = $this->_getFormattedTransactionDetails ( Stripe_Util::convertStripeObjectToArray ( $response ) );
					$this->_log ( $transaction_details, 'Payment Gateway Response' );
					
					$order->transaction_id = $transaction_id;
					$order->transaction_status = $response->paid;
					
					if (isset ( $response->paid ) && $response->paid == 1) {
						$order->order_state = JText::_ ( 'CONFIRMED' );
						$order->order_state_id = 1; // CONFIRMED
						                            // remove items from cart
						JLoader::register ( 'J2StoreHelperCart', JPATH_SITE . '/components/com_j2store/helpers/cart.php' );
						J2StoreHelperCart::removeOrderItems ( $order->id );
					} else {
						// order failed.
						$order->order_state = JText::_ ( 'FAILED' );
						$order->order_state_id = 3; // FAILED
						$error = $response ['failure_code'] . $response ['failure_message'];
						$transaction_details .= $error;
						$html = JText::_ ( $this->params->get ( 'onerrorpayment', '' ) );
					}
					$order->transaction_details = $transaction_details;
					
					if ($order->store ()) {
						// if successful send an email.
						if (isset ( $order->order_state_id ) && $order->order_state_id == 1) {
							
							$json ['success'] = JText::_ ( $this->params->get ( 'onafterpayment', '' ) );
							$json ['redirect'] = JRoute::_ ( 'index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display' );
							
							require_once (JPATH_SITE . '/components/com_j2store/helpers/orders.php');
							J2StoreOrdersHelper::sendUserEmail ( $order->user_id, $order->order_id, $order->transaction_status, $order->order_state, $order->order_state_id );
						}
					} else {
						$error = JText::_ ( 'J2STORE_STRIPE_ERROR_UPDATING_ORDER' );
					}
				}
			} catch ( Exception $e ) {
				// Since it's a decline, Stripe_CardError will be caught
				$body = $e->getJsonBody ();
                if(isset($body['error']['code'])) {
                    $code = 'J2STORE_'.strtoupper($body['error']['code']);
                    $error = addslashes(JText::_($code));
                }else {
                    $error = $body ['error'] ['message'];
                }
				$this->_log ( $this->_getFormattedTransactionDetails ( $body ['error'] ) );
			}
			
			if ($error) {
				$this->_sendErrorEmails ( $error, $transaction_details );
				$json ['error'] = $error;
			}
		}
		if (!empty ( $order->order_id ) && $json ['error']) {
			$transaction_details = $json ['error'];
			$order->transaction_details .= $transaction_details;
			$order->store ();
		}
		return $json;
	}


    /**
     * Simple logger
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    function _log($text, $type = 'message')
    {
        if ($this->_isLog) {
            $file = JPATH_ROOT . "/cache/{$this->_element}.log";
            $date = JFactory::getDate();

            $f = fopen($file, 'a');
            fwrite($f, "\n\n" . $date->format('Y-m-d H:i:s'));
            fwrite($f, "\n" . $type . ': ' . $text);
            fclose($f);
        }
    }

    function _getOrderInfo($order_id) {

    	$db = JFactory::getDBO();
    	$query = 'SELECT * FROM #__j2store_orderinfo WHERE order_id='.$db->quote($order_id);
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    
    // change array of data to string
	function _getFormattedTransactionDetails($data) {
		return json_encode($data);
	}


    // send error email
    function _sendErrorEmails($message, $paymentData) {
		$mainframe = JFactory::getApplication ();
		
		// grab config settings for sender name and email
		$config = JComponentHelper::getParams ( 'com_j2store' );
		$mailfrom = $config->get ( 'emails_defaultemail', $mainframe->getCfg ( 'mailfrom' ) );
		$fromname = $config->get ( 'emails_defaultname', $mainframe->getCfg ( 'fromname' ) );
		$sitename = $config->get ( 'sitename', $mainframe->getCfg ( 'sitename' ) );
		$siteurl = $config->get ( 'siteurl', JURI::root () );
		
		$recipients = $this->_getAdmins ();
		
		$subject = JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename );
		
		foreach ( $recipients as $recipient ) {
			$mailer = JFactory::getMailer ();
			$mailer->addRecipient ( $recipient->email );
			
			$mailer->setSubject ( $subject );
			$mailer->setBody ( JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', $recipient->name, $sitename, $siteurl, $message, $paymentData ) );
			$mailer->setSender ( array (
					$mailfrom,
					$fromname 
			) );
			$sent = $mailer->send ();
		}
		
		return true;
	}

    /**
     * Gets admins data
     *
     * @return array|boolean
     * @access protected
     */
    function _getAdmins()
    {
    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('u.name,u.email');
    	$query->from('#__users AS u');
    	$query->join('LEFT', '#__user_usergroup_map AS ug ON u.id=ug.user_id');
    	$query->where('u.sendEmail = 1');
    	$query->where('ug.group_id = 8');

    	$db->setQuery($query);
    	$admins = $db->loadObjectList();
    	if ($error = $db->getErrorMsg()) {
    		JError::raiseError(500, $error);
    		return false;
    	}
    	return $admins;
    }
    
	//get country code from table
    function _getCountryCode($country_id) {
		$db = JFactory::getDBO ();
		$query = 'SELECT * FROM #__j2store_countries WHERE country_id=' . $db->quote ( $country_id );
		$db->setQuery ( $query );
		$row = $db->loadObject ();
		return $row;
	}
    
    // get currency code and value from order
	function getCurrency($order) {
		$results = array ();
		$convert = false;
		$params = JComponentHelper::getParams ( 'com_j2store' );
		if (version_compare ( $this->_j2version, '2.6.7', 'lt' )) {
			$currency_code = $params->get ( 'currency_code', 'USD' );
			$currency_value = 1;
		} else {
			$currency_code = $order->currency_code;
			$currency_value = $order->currency_value;
		}
		$results ['currency_code'] = $currency_code;
		$results ['currency_value'] = $currency_value;
		$results ['convert'] = $convert;
		
		return $results;
	}
    
    //amount conversion
	function getAmount($value, $currency_code, $currency_value, $convert = false) {
		if (version_compare ( $this->_j2version, '2.6.7', 'lt' )) {
			return J2StoreUtilities::number ( $value, array (
					'thousands' => '',
					'num_decimals' => '2',
					'decimal' => '.' 
			) );
		} else {
			include_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/base.php');
			$currencyObject = J2StoreFactory::getCurrencyObject ();
			$amount = $currencyObject->format ( $value, $currency_code, $currency_value, false );
			return $amount;
		}
	}
	
	//get j2store version
    function getVersion() {
		if (is_null ( $this->_j2version )) {
			$xmlfile = JPATH_ADMINISTRATOR . '/components/com_j2store/manifest.xml';
			$xml = JFactory::getXML ( $xmlfile );
			$this->_j2version = ( string ) $xml->version;
		}
		return $this->_j2version;
	}

	function checkCurrency($code){
		$zero_decimal_currency = array(
			'BIF',
			'CLP',
			'DJF',
			'GNF',
			'JPY',
			'KMF',
			'KRW',
			'MGA',
			'PYG',
			'RWF',
			'VND',
			'VUV',
			'XAF',
			'XOF',
			'XPF'
		);

		if(in_array($code,$zero_decimal_currency)){
			return true;
		}
		return false;
	}

}
