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
require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/library/plugins/payment.php');

class plgJ2StorePayment_stripe extends J2StorePaymentPlugin
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element = 'payment_stripe';
    var $_isLog = false;
    var $_j2version = null;

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param    array $config An array that holds the plugin configuration
     * @since 1.5
     */
    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage('', JPATH_ADMINISTRATOR);
        if ($this->params->get('debug', 0)) {
            $this->_isLog = true;
        }

        if ($this->params->get('sandbox', 0)) {
            // get sandbox credentials
            $this->secret_key = trim($this->_getParam('stripe_test_secret_key'));
            $this->publish_key = trim($this->_getParam('stripe_test_publish_key'));
        } else {
            $this->secret_key = trim($this->_getParam('stripe_secret_key'));
            $this->publish_key = trim($this->_getParam('stripe_publish_key'));
        }
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
    }

    public function setStripAppVersion()
    {
        //require_once (JPath::clean ( dirname ( __FILE__ ) . "/library/Stripe.php" ));
        require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
        // initialise stripe object
        Stripe\Stripe::setApiKey($this->secret_key);
        Stripe\Stripe::setAppInfo("Stripe", "1.27", "https://www.j2store.org");
    }

    /**
     * verify Accept Subscription payment before payment
     * */
    function onJ2StoreAcceptSubscriptionPayment($element)
    {
        if ($this->_isMe($element)) {
            return true;
        } else {
            return null;
        }
    }

    /**
     * verify Accept Subscription payment with trial before payment
     * */
    function onJ2StoreAcceptSubscriptionPaymentWithTrial($element)
    {
        if ($this->_isMe($element)) {
            return true;
        } else {
            return null;
        }
    }

    /**
     * verify Accept Subscription card update
     * */
    function onJ2StoreAcceptSubscriptionCardUpdate($element)
    {
        if ($this->_isMe($element)) {
            return true;
        } else {
            return null;
        }
    }

    function onJ2StoreBeforeCheckout($order)
    {
        $document = JFactory::getDocument();
        $form_type = $this->params->get('form_type', 'normal');
        if ($form_type == "normal") {
            $document->addScript('https://js.stripe.com/v2/');
        } else if ($form_type == 'popup') {
            $document->addScript('https://checkout.stripe.com/checkout.js');
        } else {
            $document->addScript('https://js.stripe.com/v3/');
        }

    }

    function onJ2StoreCalculateFees($order)
    {
        //is customer selected this method for payment ? If yes, apply the fees
        $payment_method = $order->get_payment_method();

        if ($payment_method == $this->_element) {
            $total = $order->order_subtotal + $order->order_shipping + $order->order_shipping_tax;
            $surcharge = 0;
            $surcharge_percent = $this->params->get('surcharge_percent', 0);
            $surcharge_fixed = $this->params->get('surcharge_fixed', 0);
            if ((float)$surcharge_percent > 0 || (float)$surcharge_fixed > 0) {
                //percentage
                if ((float)$surcharge_percent > 0) {
                    $surcharge += ($total * (float)$surcharge_percent) / 100;
                }

                if ((float)$surcharge_fixed > 0) {
                    $surcharge += (float)$surcharge_fixed;
                }

                $name = $this->params->get('surcharge_name', JText::_('J2STORE_CART_SURCHARGE'));
                $tax_class_id = $this->params->get('surcharge_tax_class_id', '');
                $taxable = false;
                if ($tax_class_id && $tax_class_id > 0) $taxable = true;
                if ($surcharge > 0) {
                    $order->add_fee($name, round($surcharge, 2), $taxable, $tax_class_id);
                }
            }
        }
    }

    /**
     * Prepares variables and
     * Renders the form for collecting payment info
     *
     * @return string
     */
    function _renderForm($data)
    {
        $vars = new JObject ();
        $vars->prepop = array();
        $vars->version = $this->_j2version;
        $vars->onselection_text = $this->params->get('onselection', '');
        $vars->form_type = $this->params->get('form_type', 'normal');
        $html = $this->_getLayout('form', $vars);
        return $html;
    }

    /**
     * Verifies that all the required form fields are completed
     * if any fail verification, set
     * $object->error = true
     * $object->message .= '<li>x item failed verification</li>'
     *
     * @param $submitted_values     array   post data
     * @return object
     */
    function _verifyForm($submitted_values)
    {
        $object = new JObject ();
        $object->error = false;
        $object->message = '';
        $user = JFactory::getUser();
        $form_type = $this->params->get('form_type', 'normal');
        if ($form_type != "normal") {
            return $object;
        }

        foreach ($submitted_values as $key => $value) {
            switch ($key) {

                case "cardholder" :
                    if (!isset ($submitted_values [$key]) || !JString::strlen($submitted_values [$key])) {
                        $object->error = true;
                        $object->message .= "<li>" . JText::_("J2STORE_STRIPE_VALIDATION_ENTER_CARDHOLDER_NAME") . "</li>";
                    }
                    break;
                case "cardnum" :
                    if (!isset ($submitted_values [$key]) || !JString::strlen($submitted_values [$key])) {
                        $object->error = true;
                        $object->message .= "<li>" . JText::_("J2STORE_STRIPE_MESSAGE_CARD_NUMBER_INVALID") . "</li>";
                    }
                    break;
                case "month" :
                    if (!isset ($submitted_values [$key]) || !JString::strlen($submitted_values [$key])) {
                        $object->error = true;
                        $object->message .= "<li>" . JText::_("J2STORE_STRIPE_MESSAGE_CARD_EXPIRATION_DATE_INVALID") . "</li>";
                    }
                    break;
                case "year" :
                    if (!isset ($submitted_values [$key]) || !JString::strlen($submitted_values [$key])) {
                        $object->error = true;
                        $object->message .= "<li>" . JText::_("J2STORE_STRIPE_MESSAGE_CARD_EXPIRATION_DATE_INVALID") . "</li>";
                    }
                    break;
                case "cardcvv" :
                    if (!isset ($submitted_values [$key]) || !JString::strlen($submitted_values [$key])) {
                        $object->error = true;
                        $object->message .= "<li>" . JText::_("J2STORE_STRIPE_MESSAGE_CARD_CVV_INVALID") . "</li>";
                    }
                    break;
                default :
                    break;
            }
        }

        return $object;
    }


    /**
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment($data)
    {
        // initialise
        $app = JFactory::getApplication();
        //JFactory::getDocument ()->addScript ( 'https://js.stripe.com/v2/stripe.js' );
        // prepare the payment form
        $vars = new JObject ();
        $vars->url = JRoute::_("index.php?option=com_j2store&view=carts");
        $vars->order_id = $data ['order_id'];
        $vars->orderpayment_id = $data ['orderpayment_id'];
        $vars->orderpayment_amount = $data ['orderpayment_amount'];
        $vars->orderpayment_type = $this->_element;

        $vars->publish_key = $this->publish_key;

        $vars->display_name = $this->params->get('display_name', 'PLG_J2STORE_PAYMENT_STRIPE');
        $vars->onbeforepayment_text = $this->params->get('onbeforepayment', '');
        $vars->button_text = $this->params->get('button_text', 'J2STORE_PLACE_ORDER');
        $vars->card_update_button_text = $this->params->get('card_update_button_text', 'J2STORE_PLACE_ORDER');

        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));
        $orderinfo = $order->getOrderInformation();

        $vars->is_card_update = false;
        if ($order->order_type == 'subscription') {
            $vars->is_card_update = true;
        }
        //latest change
        $vars->address_city = $orderinfo->billing_city;
        $vars->address_country = $this->getCountryById($orderinfo->shipping_country_id)->country_isocode_2;
        $vars->address_line1 = $orderinfo->billing_address_1;
        $vars->address_line2 = $orderinfo->billing_address_2;

        $vars->address_state = $this->getZoneById($orderinfo->shipping_zone_id)->zone_name;
        $vars->address_zip = $orderinfo->billing_zip;
        $vars->first_name = $orderinfo->billing_first_name;
        $vars->last_name = $orderinfo->billing_last_name;
        $language = JFactory::getLanguage()->getTag();
        $vars->language = substr($language, 0, 2);
        if (empty($vars->language)) {
            $vars->language = 'auto';
        }

        $currency = J2Store::currency();
        $currency_values = $this->getCurrency($order);
        $vars->currency_code = $currency_values['currency_code'];
        //$vars->amount = 100 * $currency->format($order->order_total, $currency_values['currency_code'], $currency_values['currency_value'], false);

        $amount = $currency->format($order->order_total, $currency_values['currency_code'], $currency_values['currency_value'], false);
        $check_currency = $this->checkCurrency($currency_values['currency_code']);
        if ($check_currency) {
            $amount = round($amount, 0);

        } else {
            $amount = $amount * 100;
        }
        $vars->amount = $amount;
        
        $store_name = J2Store::config()->get('store_name', '');
        $vars->company_name = $this->params->get('company_name', $store_name);

        $form_type = $this->params->get('form_type', 'normal');
        $vars->allow_remember_me = $this->params->get('allow_remember_me', 1);
        $component = $app->input->get('option','');
        $vars->is_easy_checkout = 0;
        if($component == 'com_easycheckout'){
            $component_params = JComponentHelper::getParams( 'com_easycheckout' );
            $show_date = $component_params->get( 'easycheckout_type',1 );
            if($show_date){
                $vars->is_easy_checkout = 1;
            }
        }

        if ($form_type == "normal") {
            $vars->cardname = $app->input->getString("cardholder");
            $vars->cardnum = $app->input->getString("cardnum");
            $vars->cardmonth = $app->input->getString("month");
            $vars->cardyear = $app->input->getString("year");
            $card_exp = $vars->cardmonth . '' . $vars->cardyear;
            $vars->cardexp = $card_exp;
            $vars->cardcvv = $app->input->getString("cardcvv");
            $vars->cardnum_last4 = substr($app->input->getString("cardnum"), -4);
            $vars->name = $vars->cardname;
            $html = $this->_getLayout('prepayment', $vars);
        } else {
            $vars->description = $this->params->get('form_description', '');
            $vars->zipCode = $this->params->get('enable_zip', 0);
            $vars->email = $order->user_email;

            $vars->display_amount = ($order->order_type == 'subscription')? false : true;

            $image = $this->params->get('display_image', '');
            if ($image) {
                $vars->image = JUri::root() . JPath::clean($image);
            }
            $vars->image = "";
            $vars->enable_bitcoin = $this->params->get('enable_bitcoin', 0);
            $vars->enable_card_holder_name = $this->params->get('enable_card_holder_name', 1);
            $vars->disable_zip_code_in_inbuilt_form = $this->params->get('disable_zip_code_in_inbuilt_form', 0);
            $vars->is_payment_intent = $this->params->get('is_payment_intent', 0);

            if($form_type == "popup"){
                $html = $this->_getLayout('checkout_prepayment', $vars);
            }elseif ($form_type == 'inbuilt' && $vars->is_payment_intent){
                $hasSubscriptionProduct = $this->checkHasSubscriptionProductFromOrderID($data['order_id']);
                $invoice_number = $order->getInvoiceNumber();
                $description = JText::sprintf("J2STORE_STRIPE_ORDER_DESCRIPTION", $invoice_number);
                $charge = array(
                    "amount" => $vars->amount,
                    "currency" => strtolower($vars->currency_code),
                    "description" => $description,
                    "metadata" => array("invoice_number" => $invoice_number),
                    'receipt_email' => $order->user_email
                );
                $send_shipping = $this->params->get('send_shipping', 0);
                if ($send_shipping) {
                    $zone_name = '';
                    if (!empty($orderinfo->shipping_zone_id)) {
                        $zone_name = $this->getZoneById($orderinfo->shipping_zone_id)->zone_name;
                    }
                    if(empty($zone_name)){
                        $zone_name = $this->getZoneById($orderinfo->billing_zone_id)->zone_name;
                    }
                    $country_code = '';
                    if (!empty($orderinfo->shipping_country_id)) {
                        $country_code = $this->getCountryById($orderinfo->shipping_country_id)->country_isocode_2;
                    }
                    if(empty($country_code)){
                        $country_code = $this->getCountryById($orderinfo->billing_country_id)->country_isocode_2;
                    }
                    $address = array(
                        "line1" => !empty($orderinfo->shipping_address_1) ? $orderinfo->shipping_address_1 : $orderinfo->billing_address_1,
                        "line2" => !empty($orderinfo->shipping_address_2) ? $orderinfo->shipping_address_2 : $orderinfo->billing_address_2,
                        "city" => !empty($orderinfo->shipping_city) ? $orderinfo->shipping_city : $orderinfo->billing_city,
                        "state" => $zone_name,
                        "postal_code" => !empty($orderinfo->shipping_zip) ? $orderinfo->shipping_zip : $orderinfo->billing_zip,
                        "country" => $country_code
                    );
                    $name = '';
                    if(!empty($orderinfo->shipping_first_name) || !empty($orderinfo->shipping_first_name)){
                        $name = $orderinfo->shipping_first_name . " " . $orderinfo->shipping_last_name;
                    }
                    if(empty($name)){
                        $name = $orderinfo->billing_first_name . " " . $orderinfo->billing_last_name;
                    }
                    $shippingDetails = array(
                        "name" => $name,
                        "address" => $address
                    );
                    $charge['shipping'] = $shippingDetails;

                }

                require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
                \Stripe\Stripe::setApiKey($this->secret_key);
                $vars->setup_intent = false;
                $create_payment_intent = true;
                if($hasSubscriptionProduct){
                    if($vars->is_card_update){
                        $amount = 0;
                    }
                    $charge['payment_method_types'] = array("card");
                    $charge['setup_future_usage'] = 'off_session';
                    if($amount <= 0){
                        $vars->setup_intent = true;
                        $charge = array('usage' => 'off_session');
                    }
                }else{
                    $capture = $this->params->get('stripe_charge_mode', 1) ? true : false;
                    if(!$capture){
                        $charge['capture_method'] = 'manual';//automatic
                    }
                }

                try{
                    if($create_payment_intent){
                        if($vars->setup_intent){
                            $vars->intent = \Stripe\SetupIntent::create($charge);
                        } else {
                            $vars->intent = \Stripe\PaymentIntent::create($charge);
                        }
                    }
                }catch (Exception $e){
                    $vars->error = $e->getMessage();
                }

                $html = $this->_getLayout('stripe_intent_prepayment', $vars);
            }else{
                $html = $this->_getLayout('stripe_inbuilt_prepayment', $vars);
            }
        }
        return $html;
    }

    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _postPayment($data)
    {
        // Process the payment
        $vars = new JObject ();

        $app = JFactory::getApplication();
        $paction = $app->input->getString('paction');

        switch ($paction) {
            case 'display' :
                $html = JText::_($this->params->get('onafterpayment', ''));
                $html .= $this->_displayArticle();
                break;
            case 'process_intent':
                $result = $this->_process_intent();
                $json = json_encode($result);
                echo $json;
                $app->close();
                break;
            case 'process_intent_authentication':
                $result = $this->_process_intent_authentication();
                $json = json_encode($result);
                echo $json;
                $app->close();
                break;
            case 'process' :
                $result = $this->_process();
                $json = json_encode($result);
                echo $json;
                $app->close();
                break;
            default :
                $vars->message = JText::_($this->params->get('onerrorpayment', ''));
                $html = $this->_getLayout('message', $vars);
                break;
        }

        return $html;
    }

    /**
     * Handle error for stripe while process payment
     * */
    protected function handleStripeCatchException($e, $subscription, $order, $j2StorePlugin){
        // Since it's a decline, Stripe_CardError will be caught
        $body = $e->getJsonBody();
        $retry_payment = true;
        if (isset($body['error']) && isset($body['error']['decline_code'])) {
            $decline_codes = array('lost_card', 'not_permitted', 'pickup_card', 'fraudulent', 'stolen_card');
            if(in_array($body['error']['decline_code'], $decline_codes)){
                $retry_payment = false;
                $order->update_status(3);
                $message = sprintf(JText::_('J2STORE_STRIPE_RENEWALPAYMENT_FAILED_DUE_TO_CARD_ERROR'), $body['error']['decline_code']);
                $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $message));
                $j2StorePlugin->event('UpdateCardExpiredForSubscription', array($subscription, $order));
            } else {
                $message = sprintf(JText::_('J2STORE_STRIPE_RENEWALPAYMENT_FAILED_DUE_TO_CARD_ERROR'), $body['error']['decline_code']);
                $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $message));
            }
        }
        if($retry_payment === true){
            if (isset($body['error']) && isset($body['error']['type']) && isset($body['error']['param'])) {
                if ($body['error']['type'] == 'invalid_request_error' && $body['error']['param'] == 'customer') {
                    if(isset($body['error']['message'])){
                        $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $body['error']['message']));
                    }
                    $j2StorePlugin->event('NoResponseForRenewalPayment', array($subscription, $order));
                } elseif ($body['error']['type'] == 'card_error' && $body['error']['param'] == 'card') {
                    $j2StorePlugin->event('NoResponseForRenewalPayment', array($subscription, $order));
                } else {
                    if(isset($body['error']['message'])){
                        $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $body['error']['message']));
                    }
                    $j2StorePlugin->event('NoResponseForRenewalPayment', array($subscription, $order));
                }
            } else if(isset($body['error'])){
                if(isset($body['error']['message'])){
                    $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $body['error']['message']));
                }
                $j2StorePlugin->event('NoResponseForRenewalPayment', array($subscription, $order));
            } else {
                $j2StorePlugin->event('NoResponseForRenewalPayment', array($subscription, $order));
            }
        }
        $this->_log($this->_getFormattedTransactionDetails($body));
    }

    /**
     * Get Customer Object using Customer_id
     * */
    protected function byGetLastCardNumDigits($customer_id)
    {
        // try {
            $app = JFactory::getApplication();
            $params = J2Store::config();
            $currency = J2Store::currency();
            $error = '';
            $j2StorePlugin = J2Store::plugin();
            
            $customer = \Stripe\Customer::Retrieve(
                array("id" => $customer_id, "expand" => array('default_source'))
            );
            // var_dump($customer);
            $j2StorePlugin->event('SuccessStripeCustomer', array($customer));

        // } catch(Exception $e) {
        // }
        
    }

    /**
     * Get payment for renewal using billing ID
     * */
    protected function byRefference($subscription, $order)
    {
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $error = '';
        $j2StorePlugin = J2Store::plugin();
        if (isset($subscription->meta['stripe_customer_id']['metavalue']) && $subscription->meta['stripe_customer_id']['metavalue'] != '') {
            $is_intent_method = false;
            if (isset($subscription->meta['stripe_payment_id']['metavalue']) && $subscription->meta['stripe_payment_id']['metavalue'] != '') {
                $payment_id = $subscription->meta['stripe_payment_id']['metavalue'];
                $is_intent_method = true;
            }
            $customer_id = $subscription->meta['stripe_customer_id']['metavalue'];
            require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
            // initialise stripe object
            $this->setStripAppVersion();

            $currency_values = $this->getCurrency($order);
            $amount = $currency->format($order->order_total, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert']);
            $check_currency = $this->checkCurrency($currency_values ['currency_code']);
            if ($check_currency) {
                $amount = round($amount, 0);

            } else {
                $amount = $amount * 100;
            }

            $invoice_number = $order->getInvoiceNumber();
            $description = $this->params->get('subscription_renewal_desc', 'J2STORE_STRIPE_ORDER_DESCRIPTION_RENEWAL_PAYMENT');
            if(empty($description)){
                $description = 'J2STORE_STRIPE_ORDER_DESCRIPTION_RENEWAL_PAYMENT';
            }
            $description = JText::sprintf($description, $invoice_number);
            $product_name = '';
            $j2StorePlugin->event('GetSubscriptionProductName', array( &$product_name, $subscription->j2store_subscription_id ) );
            $description = str_replace(array('[INVOICE_NUMBER]', '[PRODUCT_NAME]'), array($invoice_number, $product_name), $description);

            $charge = array(
                "amount" => $amount,
                "currency" => $currency_values ['currency_code'],
                //"card" => $data ['stripeToken'],
                "description" => $description,
                "metadata" => array("invoice_number" => $invoice_number),
                "customer" => $customer_id
            );
            if($is_intent_method){
                $charge["payment_method"] = $payment_id;
                $charge["off_session"] = "true";
                $charge["payment_method_types"] = ['card'];
                $charge["confirm"] = "true";

            }
            $transaction_details = '';
            $model = F0FModel::getTmpInstance('AppPayment_stripe', 'J2StoreModel');
            try {
                $ssl_version = $this->params->get('ssl_version_v1', 0);
                if ($ssl_version) {
                    $curl = new \Stripe\HttpClient\CurlClient(array(CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1));
                    Stripe\ApiRequestor::setHttpClient($curl);
                }
                if($is_intent_method){
                    try{
                        $response = \Stripe\PaymentIntent::create($charge);
                    } catch (Exception $e){
                        $error = $e->getMessage();
                        $this->_log($error, 'Renewal Payment Gateway Exception');
                        $error_body = $e->getJsonBody();
                        $this->_log(json_encode($error_body), 'Renewal Payment Gateway Exception');
                        $handled_error = false;
                        if(isset($error_body["error"]) && isset($error_body["error"]["decline_code"])){
                            if(!empty($error_body["error"]["decline_code"]) && $error_body["error"]["decline_code"] == "authentication_required"){
                                if(!empty($error_body["error"]["payment_intent"]["client_secret"])){
                                    if(!empty($error_body["error"]["payment_method"]["id"])){
                                        $handled_error = true;
                                        $model->updateOrderMeta($order->j2store_order_id, 'stripe_client_secret', $error_body["error"]["payment_intent"]["client_secret"]);
                                        $model->updateOrderMeta($order->j2store_order_id, 'stripe_payment_method_id', $error_body["error"]["payment_method"]["id"]);
                                        $model->updateOrderMeta($order->j2store_order_id, 'stripe_payment_intent', $error_body["error"]["payment_intent"]["id"]);
                                        $order->add_history($error_body['error']['message']);
                                        $order->update_status(4);
                                        $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, $subscription->status, $error_body['error']['message']));
                                        $j2StorePlugin->event('PendingRenewalPayment', array($subscription, $order));
                                        $model->sendEmailToCustomerAboutAuthenticationRequiredForPayment($subscription, $order);
                                        return true;
                                    }
                                }
                            }
                        }
                        if($handled_error === false){
                            $this->handleStripeCatchException($e, $subscription, $order, $j2StorePlugin);
                        }
                        $response = false;
                    }
                } else {
                    try{
                        //$response = Stripe_Charge::create ( $charge );
                        $response = Stripe\Charge::create($charge);
                    } catch (Exception $e){
                        $this->handleStripeCatchException($e, $subscription, $order, $j2StorePlugin);
                        $response = false;
                        $error = $e->getMessage();
                        $this->_log($error, 'Renewal Payment Gateway Exception');
                    }
                }

                if ($response) {
                    $order->add_history('Response received from Stripe');
                    $transaction_id = $response->id;
                    $transaction_details = $this->_getFormattedTransactionDetails($response->__toArray());
                    //$transaction_details = $this->_getFormattedTransactionDetails ( Stripe_Util::convertStripeObjectToArray ( $response ) );//Stripe_Util::convertStripeObjectToArray ( $response )
                    $this->_log($transaction_details, 'Payment Gateway Renewal Response');

                    $order->transaction_id = $transaction_id;
                    $status = 0;
                    if($is_intent_method){
                        $status = 1;
                        $this->_log($this->_getFormattedTransactionDetails ($response), 'Payment Gateway Renewal Response');
                    } else {
                        if (isset ($response->paid)) {
                            $status = $response->paid;
                        }
                    }
                    $order->transaction_status = $status;

                    if (isset ($status) && $status == 1) {
                        if ($order->store()) {
                            $j2StorePlugin->event('SuccessRenewalPayment', array($subscription, $order));
                        }
                        // change order status
                        $order->payment_complete();
                    } else {
                        // order failed.
                        $order->update_status(3);
                        $j2StorePlugin->event('FailedRenewalPayment', array($subscription, $order));
                        if(isset($response ['failure_code']) && isset($response ['failure_message'])){
                            $error = $response ['failure_code'] . $response ['failure_message'];
                            $order->add_history($error);
                            $transaction_details .= $error;
                        }
                    }
                    $order->transaction_details = $transaction_details;
                    $order->store();
                }
            } catch (Exception $e) {
                $this->handleStripeCatchException($e, $subscription, $order, $j2StorePlugin);
            }
        } else {
            $order->update_status(3);
            $j2StorePlugin->event('FailedRenewalPayment', array($subscription, $order));
            $returnVal = "Payment failed due to empty customer ID";
            $this->_log($returnVal);
        }
    }

    /**
     * Process subscription checkout for update card
     * */
    protected function processSubscriptionCheckoutForUpdateCard($data)
    {
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $error = '';
        $transaction_details = '';
        // get order information from table
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));
        $subscription = array();
        $process = false;
        $j2StorePlugin = J2Store::plugin();
        $j2StorePlugin->event('ProcessSubscriptionCardUpdateCheckout', array($order->subscription_id, &$subscription, &$process));
        if ($process) {
            require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
            // initialise stripe object
            $this->setStripAppVersion();

            $currency_values = $this->getCurrency($order);
            $amount = $currency->format(0, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert']);
            $check_currency = $this->checkCurrency($currency_values ['currency_code']);
            if ($check_currency) {
                $amount = round($amount, 0);

            } else {
                $amount = $amount * 100;
            }
            $create_customer = 1;
            if(!empty($subscription)){
                if (isset($subscription->meta['stripe_customer_id']['metavalue']) && $subscription->meta['stripe_customer_id']['metavalue'] != '') {
                    $customer_id = $subscription->meta['stripe_customer_id']['metavalue'];
                    try {
                        $customer = \Stripe\Customer::Retrieve(
                            array("id" => $customer_id, "expand" => array('default_source'))
                        );
                    } catch(Exception $e) {
                    }
                    if (isset($customer->id) && !empty($customer->id)) {
                        try {
                            $customer = \Stripe\Customer::update(
                                            $customer_id, // stored in your application
                                            array(
                                                'source' => $data['stripeToken'] // obtained with Checkout
                                                )
                                        );
                            $create_customer = 0;
                        } catch(\Stripe\Error\Card $e) {

                            // Use the variable $error to save any errors
                            // To be displayed to the customer later in the page
                            $body = $e->getJsonBody();
                            $err  = $body['error'];
                            $error = $err['message'];
                            $json ['error'] = $error;
                            return $json;
                        }
                    }
                }
            }
            if($create_customer){
                $order_info = $order->getOrderInformation();
                $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
                // Create a Customer:
                $customer = \Stripe\Customer::create(array(
                    "name" => $name,
                    "email" => $order->user_email,
                    "source" => $data ['stripeToken'],
                ));
            }

            //for handling trial subscription
            if ($amount <= 0) {
                if (isset($customer->id) && !empty($customer->id)) {
                    $this->updateStripeCustomerId($order->order_id, $customer->id, 1, $subscription);
                    $j2StorePlugin->event('CardUpdateSuccess', array($subscription));
                    // clear cart
                    $order->empty_cart();
                    $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                    $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display', false);
                } else {
                    $json ['error'] = JText::_('J2STORE_STRIPE_FAILED_TO_CREATE_CUSTOMER');
                }
                return $json;
            }
            $order->empty_cart();
        } else {
            $error = JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_UNABLE_TO_PROCESS_CARD_UPDATE_CHECKOUT');
        }

        if ($error) {
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $sitename = $params->get('sitename');
            } else {
                $sitename = $params->getValue('config.sitename');
            }
            /*$subject = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);
            //$body = JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', 'Administrator', $sitename, JURI::root (), $error, $transaction_details);
            $receivers = $this->_getAdmins();
            foreach ($receivers as $receiver) {
                $body = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', $receiver->name, $sitename, JURI::root(), $error, $transaction_details);
                //J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
            }*/
            $json ['error'] = $error;
        }
        return $json;
    }

    protected function processSubscriptionCheckoutForUpdateCardPaymentIntents($data)
    {
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $error = '';
        $transaction_details = '';
        // get order information from table
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));
        $subscription = array();
        $process = false;
        $j2StorePlugin = J2Store::plugin();
        $j2StorePlugin->event('ProcessSubscriptionCardUpdateCheckout', array($order->subscription_id, &$subscription, &$process));
        if ($process) {
            require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
            // initialise stripe object
            $this->setStripAppVersion();

            try{
                $intent = \Stripe\SetupIntent::retrieve($data ['stripeToken']);

                $this->_log(json_encode($intent), 'Payment Gateway Response');
                if(isset($intent->id) && !empty($intent->id)){
                    $payment_id = $intent->payment_method;
                    $order->transaction_id = $intent->id;
                    $order->transaction_status = $intent->status;

                    if (isset ($intent->status) && $intent->status == 'succeeded') {
                        $create_customer = true;
                        if(!empty($subscription)){
                            if (isset($subscription->meta['stripe_customer_id']['metavalue']) && $subscription->meta['stripe_customer_id']['metavalue'] != '') {
                                $customer_id = $subscription->meta['stripe_customer_id']['metavalue'];
                                try {
                                    $customer = \Stripe\Customer::Retrieve(
                                        array("id" => $customer_id, "expand" => array('default_source'))
                                    );
                                } catch(Exception $e) {
                                }
                                if (isset($customer->id) && !empty($customer->id)) {
                                    $create_customer = false;
                                    $customer_id = $customer->id;
                                }
                            }
                            if($create_customer === true){
                                $order_info = $order->getOrderInformation();
                                $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
                                $customer = \Stripe\Customer::create(array(
                                    "name" => $name,
                                    'payment_method' => $payment_id,
                                    "email" => $order->user_email
                                ));

                                if(!empty($customer) && !empty($customer->id)){
                                    $customer_id = $customer->id;
                                }
                            } else {
                                $payment_method = \Stripe\PaymentMethod::retrieve($payment_id);
                                $payment_method->attach(array('customer' => $customer_id));
                            }

                            if (!empty($payment_id)) {
                                if (!empty($customer_id)) {
                                    $this->updateStripeCustomerId($order->order_id, $customer_id, 1, $subscription);
                                    $this->updateStripePaymentId($order->order_id, $payment_id, 1, $subscription);
                                    $j2StorePlugin->event('CardUpdateSuccess', array($subscription));
                                    // clear cart
                                    $order->empty_cart();
                                    $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                                    $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display', false);
                                } else {
                                    $json ['error'] = JText::_('J2STORE_STRIPE_FAILED_TO_RECEIVE_CUSTOMER_ID');
                                    $this->_log($this->_getFormattedTransactionDetails($intent));
                                }
                            } else {
                                $this->_log($this->_getFormattedTransactionDetails($intent));
                            }
                            $order->empty_cart();
                        }
                    } else {
                        $json ['error'] = JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_UNABLE_TO_PROCESS_CARD_UPDATE_CHECKOUT');
                    }
                } else{
                    $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_PAYMENT_INTENT_ID');
                }
            }catch (Exception $e){
                $json ['error'] = $e->getMessage();
            }
            if(!empty($json ['error'])){
                $this->_log($this->_getFormattedTransactionDetails($json));
            }

            return $json;
        } else {
            $error = JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_UNABLE_TO_PROCESS_CARD_UPDATE_CHECKOUT');
        }

        if ($error) {
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $sitename = $params->get('sitename');
            } else {
                $sitename = $params->getValue('config.sitename');
            }
            /*$subject = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);
            //$body = JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', 'Administrator', $sitename, JURI::root (), $error, $transaction_details);
            $receivers = $this->_getAdmins();
            foreach ($receivers as $receiver) {
                $body = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', $receiver->name, $sitename, JURI::root(), $error, $transaction_details);
                //J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
            }*/
            $json ['error'] = $error;
        }
        return $json;
    }

    /**
     * Process first payment
     * */
    protected function processCheckout($data)
    {
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $error = '';

        // get order information from table
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));

        require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
        // initialise stripe object
        $this->setStripAppVersion();

        $currency_values = $this->getCurrency($order);
        $amount = $currency->format($order->order_total, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert']);
        $check_currency = $this->checkCurrency($currency_values ['currency_code']);
        if ($check_currency) {
            $amount = round($amount, 0);

        } else {
            $amount = $amount * 100;
        }

        $invoice_number = $order->getInvoiceNumber();
        $description = JText::sprintf("J2STORE_STRIPE_ORDER_DESCRIPTION", $invoice_number);

        $capture = $this->params->get('stripe_charge_mode', 1) ? true : false;
        $order_info = $order->getOrderInformation();
        $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
        // Create a Customer:
        $customer = \Stripe\Customer::create(array(
            "name" => $name,
            "email" => $order->user_email,
            "source" => $data ['stripeToken'],
        ));

        //for handling trial subscription
        if ($amount <= 0) {
            if (isset($customer->id) && !empty($customer->id)) {
                $order->payment_complete();
                $this->updateStripeCustomerId($order->order_id, $customer->id);
                // clear cart
                $order->empty_cart();
                $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
            } else {
                $json ['error'] = JText::_('J2STORE_STRIPE_FAILED_TO_CREATE_CUSTOMER');
            }
            return $json;
        }
        //shipping_address_enable

        $charge = array(
            "amount" => $amount,
            "currency" => $currency_values ['currency_code'],
            "description" => $description,
            "metadata" => array("invoice_number" => $invoice_number),
            "customer" => $customer->id
        );

        $transaction_details = '';
        try {
            $ssl_version = $this->params->get('ssl_version_v1', 0);
            if ($ssl_version) {
                $curl = new \Stripe\HttpClient\CurlClient(array(CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1));
                Stripe\ApiRequestor::setHttpClient($curl);
            }


            //$response = Stripe_Charge::create ( $charge );
            $response = Stripe\Charge::create($charge);
            if ($response) {
                $order->add_history('Response received from Stripe');
                $transaction_id = $response->id;
                $transaction_details = $this->_getFormattedTransactionDetails($response->__toArray());
                $this->_log($transaction_details, 'Payment Gateway Response');

                $order->transaction_id = $transaction_id;
                $order->transaction_status = $response->paid;

                if (isset ($response->paid) && $response->paid == 1) {
                    // change order status
                    $order->payment_complete();

                    $this->updateStripeCustomerId($order->order_id, $customer->id);

                    // remove items from cart
                    // clear cart
                    $order->empty_cart();
                } else {
                    // order failed.
                    $order->update_status(3);
                    $this->updateSubscriptionPaymentFailed($order->order_id);
                    $error = $response ['failure_code'] . $response ['failure_message'];
                    $order->add_history($error);
                    $transaction_details .= $error;
                }
                $order->transaction_details = $transaction_details;

                if ($order->store()) {
                    if (isset ($order->order_state_id) && $order->order_state_id == 1) {

                        $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                        $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
                    }
                } else {
                    $error = JText::_('J2STORE_STRIPE_ERROR_UPDATING_ORDER');
                }
            }

        } catch (Exception $e) {
            // Since it's a decline, Stripe_CardError will be caught
            $body = $e->getJsonBody();
            $error = $body ['error'] ['message'];
            $this->_log($this->_getFormattedTransactionDetails($body ['error']));
        }

        if ($error) {
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $sitename = $params->get('sitename');
            } else {
                $sitename = $params->getValue('config.sitename');
            }
            /*$subject = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);
            //$body = JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', 'Administrator', $sitename, JURI::root (), $error, $transaction_details);
            $receivers = $this->_getAdmins();
            foreach ($receivers as $receiver) {
                $body = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', $receiver->name, $sitename, JURI::root(), $error, $transaction_details);
                //J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
            }*/
            $json ['error'] = $error;
        }
        return $json;
    }

    /**
     * Process first payment through payment intents
     * */
    protected function processCheckoutPaymentIntents($data)
    {
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $error = '';

        // get order information from table
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));

        require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
        // initialise stripe object
        $this->setStripAppVersion();
        $currency_values = $this->getCurrency($order);
        $amount = $currency->format($order->order_total, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert']);
        try{
            if($amount <= 0){
                $intent = \Stripe\SetupIntent::retrieve($data ['stripeToken']);
            } else {
                $intent = \Stripe\PaymentIntent::retrieve($data ['stripeToken']);
            }

            $this->_log(json_encode($intent), 'Payment Gateway Response');
            if(isset($intent->id) && !empty($intent->id)){
                $payment_id = $intent->payment_method;
                $order->transaction_id = $intent->id;
                $order->transaction_status = $intent->status;

                if (isset ($intent->status) && $intent->status == 'succeeded') {
                    // change order status
                    $order->payment_complete();
                    // remove items from cart
                    // clear cart
                    $order->empty_cart();
                    $order_info = $order->getOrderInformation();
                    $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
                    $customer = \Stripe\Customer::create(
                        array(
                            "name" => $name,
                            'payment_method' => $payment_id,
                            "email" => $order->user_email
                        )
                    );

                    if(!empty($customer) && !empty($customer->id)){
                        $customer_id = $customer->id;
                    }

                    if (!empty($payment_id)) {
                        if (!empty($customer_id)) {
                            $this->updateStripePaymentId($order->order_id, $payment_id);
                            $this->updateStripeCustomerId($order->order_id, $customer_id);
                        } else {
                            $order->add_history(JText::_('J2STORE_STRIPE_FAILED_TO_RECEIVE_CUSTOMER_ID'));
                            $this->_log($this->_getFormattedTransactionDetails($intent));
                        }
                    } else {
                        $order->add_history(JText::_('J2STORE_STRIPE_FAILED_TO_RECEIVE_PAYMENT_ID'));
                        $this->_log($this->_getFormattedTransactionDetails($intent));
                    }
                } else {
                    // order failed.
                    $order->update_status(3);
                    $this->updateSubscriptionPaymentFailed($order->order_id);
                    $order->add_history($intent->last_payment_error);
                    $json ['error'] = empty($intent->last_payment_error) ? JText::_($this->params->get('onerrorpayment', '')): $intent->last_payment_error;
                }

                $order->transaction_details = json_encode($intent);
                if ($order->store()) {
                    if (isset ($order->order_state_id) && $order->order_state_id == 1) {
                        $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                        $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
                    }
                } else {
                    $json ['error'] = JText::_('J2STORE_STRIPE_ERROR_UPDATING_ORDER');
                }
            }else{
                $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_PAYMENT_INTENT_ID');
            }
        }catch (Exception $e){
            $json ['error'] = $e->getMessage();
        }
        if(!empty($json ['error'])){
            $this->_log($this->_getFormattedTransactionDetails($json));
        }

        return $json;
    }

    /**
     * Authenticate the pending payment
     * */
    protected function _process_intent_authentication(){
        $app = JFactory::getApplication();
        $json = array();
        // get the post data
        $data = $app->input->getArray($_POST);
        if (!JRequest::checkToken()) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_TOKEN');
        }
        // run initial checks
        if (!isset($data ['order_id']) || empty ($data ['order_id'])) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_ORDER');
        }

        if(!$json && isset($data ['order_id']) && !empty($data ['order_id']) ){
            // get order information from table
            F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
            $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
            $order->load(array(
                'order_id' => $data ['order_id']
            ));
            if(!empty($order) && !empty($order->j2store_order_id)){
                $model = F0FModel::getTmpInstance('AppPayment_stripe', 'J2StoreModel');
                $payment_intent_id = $model->getMetaData($order->j2store_order_id, 'stripe_payment_intent', 'order');
                if(!empty($payment_intent_id)){
                    try{
                        // See your keys here: https://dashboard.stripe.com/account/apikeys
                        require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
                        $this->setStripAppVersion();
                        $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
                        $this->_log(json_encode($intent), 'Payment Gateway Response');
                        if(isset($intent->id) && !empty($intent->id)){
                            $order->transaction_id = $intent->id;
                            $order->transaction_status = $intent->status;
                            $subscription = '';
                            $j2StorePlugin = J2Store::plugin();
                            if($order->subscription_id){
                                $subscription = $model->getSubscriptionById($order->subscription_id);
                            }
                            if (isset ($intent->status) && $intent->status == 'succeeded') {
                                // change order status
                                $order->payment_complete();
                                if(!empty($subscription)){
                                    $j2StorePlugin->event('SuccessRenewalPayment', array($subscription, $order, false));
                                }
                                // remove items from cart
                                // clear cart
                                $order->empty_cart();
                            } else if(isset ($intent->status) && $intent->status == 'requires_source'){
                                $order->add_history($intent->last_payment_error);
                                $json ['error'] = empty($intent->last_payment_error) ? JText::_($this->params->get('onerrorpayment', '')): $intent->last_payment_error;
                            } else {
                                // order failed.
                                /**
                                 * Note: We are not marking the status as failed because the customer cannot retry the same order again if we mark as failed
                                 * Also we have changed the renewal date of subscription while thr payment marked as pending.
                                 * The site owner might unaware of renewal date update, in case if he marked the subscription as active to retry the renewal.
                                 * */
                                /*$order->update_status(3);
                                if(!empty($subscription)){
                                    $j2StorePlugin->event('FailedRenewalPayment', array($subscription, $order));
                                }*/
                                $order->add_history($intent->last_payment_error);
                                $json ['error'] = empty($intent->last_payment_error) ? JText::_($this->params->get('onerrorpayment', '')): $intent->last_payment_error;
                            }

                            $order->transaction_details = json_encode($intent);
                            if ($order->store()) {
                                if (isset ($order->order_state_id) && $order->order_state_id == 1) {
                                    $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                                    $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
                                }
                            } else {
                                $json ['error'] = JText::_('J2STORE_STRIPE_ERROR_UPDATING_ORDER');
                            }
                        }else{
                            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_PAYMENT_INTENT_ID');
                        }
                    }catch (Exception $e){
                        $json ['error'] = $e->getMessage();
                    }
                } else {
                    $json ['error'] = JText::_('J2STORE_STRIPE_PAYMENT_INTENT_ID_NOT_FOUND');
                }
            } else {
                $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_ORDER');
            }
        }

        return $json;
    }

    protected function _process_intent(){
        /*
         * perform initial checks
         */
        $app = JFactory::getApplication();
        $json = array();
        // get the post data
        $data = $app->input->getArray($_POST);

        if (!JRequest::checkToken()) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_TOKEN');
        }

        // run initial checks
        if (!isset($data ['order_id']) || empty ($data ['order_id'])) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_ORDER');
        }

        if (empty ($data ['stripeToken'])) {
            $json ['error'] = JText::_('J2STORE_STRIPE_TOKEN_MISSING');
        }
        if(!$json && isset($data ['order_id']) && !empty($data ['order_id']) ){
            // get order information from table
            F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
            $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
            $order->load(array(
                'order_id' => $data ['order_id']
            ));

            if (isset($data['order_id']) && $data['order_id']) {
                $hasSubscriptionProduct = $this->checkHasSubscriptionProductFromOrderID($data['order_id']);
                if ($order->order_type == 'subscription') {
                    return $this->processSubscriptionCheckoutForUpdateCardPaymentIntents($data);
                } else if ($hasSubscriptionProduct) {
                    return $this->processCheckoutPaymentIntents($data);
                }
            }

            try{
                // See your keys here: https://dashboard.stripe.com/account/apikeys
                require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
                $this->setStripAppVersion();
                $intent = \Stripe\PaymentIntent::retrieve($data ['stripeToken']);
                $this->_log(json_encode($intent), 'Payment Gateway Response');
                if(isset($intent->id) && !empty($intent->id)){
                    $order->transaction_id = $intent->id;
                    $order->transaction_status = $intent->status;

                    if (isset ($intent->status) && in_array($intent->status,array('succeeded','requires_capture'))) {
                        // change order status
                        $order->payment_complete();
                        // remove items from cart
                        // clear cart
                        $order->empty_cart();
                    } else {
                        // order failed.
                        $order->update_status(3);
                        $order->add_history($intent->last_payment_error);
                        $json ['error'] = empty($intent->last_payment_error) ? JText::_($this->params->get('onerrorpayment', '')): $intent->last_payment_error;
                    }

                    $order->transaction_details = json_encode($intent);
                    if ($order->store()) {
                        if (isset ($order->order_state_id) && $order->order_state_id == 1) {
                            $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                            $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
                        }
                    } else {
                        $json ['error'] = JText::_('J2STORE_STRIPE_ERROR_UPDATING_ORDER');
                    }
                }else{
                    $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_PAYMENT_INTENT_ID');
                }
            }catch (Exception $e){
                $json ['error'] = $e->getMessage();
            }

        }
        return $json;
    }

    /**
     * Processes the payment
     *
     * This method process only real time (simple) payments
     *
     * @return string
     * @access protected
     */
    function _process()
    {
        /*
         * perform initial checks
         */
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $currency = J2Store::currency();
        $json = array();
        $error = '';
        // get the post data
        $data = $app->input->getArray($_POST);

        if (!JRequest::checkToken()) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_TOKEN');
        }

        // get order information from table
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_j2store/tables');
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array(
            'order_id' => $data ['order_id']
        ));

        // run initial checks
        if (empty ($order->order_id)) {
            $json ['error'] = JText::_('J2STORE_STRIPE_INVALID_ORDER');
        }

        if (empty ($data ['stripeToken'])) {
            $json ['error'] = JText::_('J2STORE_STRIPE_TOKEN_MISSING');
        }

        if (!$json) {

            if (isset($data['order_id']) && $data['order_id']) {
                $hasSubscriptionProduct = $this->checkHasSubscriptionProductFromOrderID($data['order_id']);
                if ($order->order_type == 'subscription') {
                    return $this->processSubscriptionCheckoutForUpdateCard($data);
                } else if ($hasSubscriptionProduct) {
                    return $this->processCheckout($data);
                }
            }

            //require_once (JPath::clean ( dirname ( __FILE__ ) . "/library/Stripe.php" ));
            require_once JPATH_SITE . "/plugins/j2store/" . $this->_element . "/library/vendor/autoload.php";
            // initialise stripe object
            $this->setStripAppVersion();

            //$stripe = Stripe::setApiKey ( $this->secret_key );

            $currency_values = $this->getCurrency($order);
            $amount = $currency->format($order->order_total, $currency_values ['currency_code'], $currency_values ['currency_value'], $currency_values ['convert']);
            $check_currency = $this->checkCurrency($currency_values ['currency_code']);
            if ($check_currency) {
                $amount = round($amount, 0);

            } else {
                $amount = $amount * 100;
            }

            $invoice_number = $order->getInvoiceNumber();
            $description = JText::sprintf("J2STORE_STRIPE_ORDER_DESCRIPTION", $invoice_number);

            $capture = $this->params->get('stripe_charge_mode', 1) ? true : false;
            $order_info = $order->getOrderInformation();
            //shipping_address_enable

            $charge = array(
                "amount" => $amount,
                "currency" => $currency_values ['currency_code'],
                //"card" => $data ['stripeToken'],
                "description" => $description,
                "capture" => $capture,
                //"source" => $data ['stripeToken']
            );
            $payment_form_type = $this->params->get('form_type','normal');
            if($payment_form_type == 'inbuilt'){
                $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
                $charge['receipt_email'] = $order->user_email;
                $customer = \Stripe\Customer::create(
                    array(
                        "name" => $name,
                        "email" => $order->user_email,
                        "source" => $data ['stripeToken']
                    )
                );
                if(isset($customer->id) && $customer->id){
                    $charge['customer'] = $customer->id;
                }
            }else{
                $charge['source'] = $data ['stripeToken'];
            }

            $send_shipping = $this->params->get('send_shipping', 0);
            if ($send_shipping) {
                $zone_name = '';
                if (!empty($order_info->shipping_zone_id)) {
                    $zone_name = $this->getZoneById($order_info->shipping_zone_id)->zone_name;
                }
                if(empty($zone_name)){
                    $zone_name = $this->getZoneById($order_info->billing_zone_id)->zone_name;
                }
                $country_code = '';
                if (!empty($order_info->shipping_country_id)) {
                    $country_code = $this->getCountryById($order_info->shipping_country_id)->country_isocode_2;
                }
                if(empty($country_code)){
                    $country_code = $this->getCountryById($order_info->billing_country_id)->country_isocode_2;
                }
                $address = array(
                    "line1" => !empty($order_info->shipping_address_1) ? $order_info->shipping_address_1 : $order_info->billing_address_1,
                    "line2" => !empty($order_info->shipping_address_2) ? $order_info->shipping_address_2 : $order_info->billing_address_2,
                    "city" => !empty($order_info->shipping_city) ? $order_info->shipping_city : $order_info->billing_city,
                    "state" => $zone_name,
                    "postal_code" => !empty($order_info->shipping_zip) ? $order_info->shipping_zip : $order_info->billing_zip,
                    "country" => $country_code
                );
                $name = '';
                if(!empty($order_info->shipping_first_name) || !empty($order_info->shipping_first_name)){
                    $name = $order_info->shipping_first_name . " " . $order_info->shipping_last_name;
                }
                if(empty($name)){
                    $name = $order_info->billing_first_name . " " . $order_info->billing_last_name;
                }
                $shippingDetails = array(
                    "name" => $name,
                    "address" => $address
                );
                $charge['shipping'] = $shippingDetails;
            }


            $transaction_details = '';
            try {
                $ssl_version = $this->params->get('ssl_version_v1', 0);
                if ($ssl_version) {
                    $curl = new \Stripe\HttpClient\CurlClient(array(CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1));
                    Stripe\ApiRequestor::setHttpClient($curl);
                }


                //$response = Stripe_Charge::create ( $charge );
                $response = Stripe\Charge::create($charge);
                if ($response) {
                    $order->add_history('Response received from Stripe');
                    $transaction_id = $response->id;
                    $transaction_details = $this->_getFormattedTransactionDetails($response->__toArray());
                    //$transaction_details = $this->_getFormattedTransactionDetails ( Stripe_Util::convertStripeObjectToArray ( $response ) );//Stripe_Util::convertStripeObjectToArray ( $response )
                    $this->_log($transaction_details, 'Payment Gateway Response');

                    $order->transaction_id = $transaction_id;
                    $order->transaction_status = $response->paid;

                    if (isset ($response->paid) && $response->paid == 1) {
                        // change order status
                        $order->payment_complete();

                        // remove items from cart
                        // clear cart
                        $order->empty_cart();
                    } else {
                        // order failed.
                        $order->update_status(3);
                        $error = $response ['failure_code'] . $response ['failure_message'];
                        $order->add_history($error);
                        $transaction_details .= $error;
                        $html = JText::_($this->params->get('onerrorpayment', ''));
                    }
                    $order->transaction_details = $transaction_details;

                    if ($order->store()) {
                        if (isset ($order->order_state_id) && $order->order_state_id == 1) {

                            $json ['success'] = JText::_($this->params->get('onafterpayment', ''));
                            $json ['redirect'] = JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=' . $this->_element . '&paction=display',false);
                        }
                    } else {
                        $error = JText::_('J2STORE_STRIPE_ERROR_UPDATING_ORDER');
                    }
                }

            } catch (Exception $e) {
                // Since it's a decline, Stripe_CardError will be caught
                $body = $e->getJsonBody();
                if (isset($body['error']['code'])) {
                    $code = 'J2STORE_' . strtoupper($body['error']['code']);
                    $error = addslashes(JText::_($code));
                } else {
                    $error = $body ['error'] ['message'];
                }
                $transaction_details = $this->_getFormattedTransactionDetails($body);
                $this->_log($this->_getFormattedTransactionDetails($body ['error']));
            }

            if ($error) {

                if (version_compare(JVERSION, '3.0', 'ge')) {
                    $sitename = $params->get('sitename');
                } else {
                    $sitename = $params->getValue('config.sitename');
                }
                $subject = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);
                //$body = JText::sprintf ( 'J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', 'Administrator', $sitename, JURI::root (), $error, $transaction_details);
               // $receivers = $this->_getAdmins();
                /*foreach ($receivers as $receiver) {
                    $body = JText::sprintf('J2STORE_STRIPE_EMAIL_PAYMENT_FAILED_BODY', $receiver->name, $sitename, JURI::root(), $error, $transaction_details);
                    //J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
                    J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
                }*/
                $json ['error'] = $error;
            }
        }
        if (!empty ($order->order_id) && isset($json ['error'])) {
            $transaction_details = $json ['error'];
            $order->transaction_details .= $transaction_details;
            $order->store();
        }

        return $json;
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

    // change array of data to string
    function _getFormattedTransactionDetails($data)
    {
        return json_encode($data);
    }

    /**
     * Gets a value of the plugin parameter
     *
     * @param string $name
     * @param string $default
     * @return string
     * @access protected
     */
    function _getParam($name, $default = '')
    {
        $sandbox_param = "sandbox_$name";
        $sb_value = $this->params->get($sandbox_param);

        if ($this->params->get('sandbox') && !empty ($sb_value)) {
            $param = $this->params->get($sandbox_param, $default);
        } else {
            $param = $this->params->get($name, $default);
        }

        return $param;
    }

    function checkCurrency($code)
    {
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

        if (in_array($code, $zero_decimal_currency)) {
            return true;
        }
        return false;
    }

    /**
     * Check subscription product available for an order
     * */
    protected function checkHasSubscriptionProductFromOrderID($order_id)
    {
        $j2StorePlugin = J2Store::plugin();
        $hasSubProduct = 0;
        $j2StorePlugin->event('CheckHasSubscriptionProductFromOrderID', array('app_subscriptionproduct', $order_id, &$hasSubProduct));
        return $hasSubProduct;
    }

    /**
     * Update Payment Failed
     * */
    protected function updateSubscriptionPaymentFailed($order_id)
    {
        $subscriptions = $this->getSubscriptionByOrderId($order_id);
        $j2StorePlugin = J2Store::plugin();
        if (is_array($subscriptions) && count($subscriptions)) {
            foreach ($subscriptions as $susb) {
                $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_PAYMENT_FAILED');
                $j2StorePlugin->event('AddSubscriptionHistory', array($susb->j2store_subscription_id, $susb->status, $comment));
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($susb->j2store_subscription_id, 'failed'));
            }
        }
    }

    /**
     * Update Stripe customer Id
     * */
    protected function updateStripeCustomerId($order_id, $customerID, $updateCard = 0, $subscription = array())
    {
        if ($updateCard) {
            $subscriptions[] = $subscription;
        } else {
            $subscriptions = $this->getSubscriptionByOrderId($order_id);
        }

        $j2StorePlugin = J2Store::plugin();
        if (is_array($subscriptions) && count($subscriptions)) {
            foreach ($subscriptions as $susb) {
                if ($updateCard) {
                    $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_PAYMENT_COMPLETED_FOR_UPDATE_CARD');
                } else {
                    $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_PAYMENT_COMPLETED');
                }

                $j2StorePlugin->event('AddSubscriptionHistory', array($susb->j2store_subscription_id, $susb->status, $comment));
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($susb->j2store_subscription_id, 'active'));
                $j2StorePlugin->event('RefreshUserGroups', array($susb->user_id));

                $this->addSubscriptionMeta($susb->j2store_subscription_id, 'stripe_customer_id', $customerID);
            }
        }

    }

    /**
     * Update Stripe customer Id
     * */
    protected function updateStripePaymentId($order_id, $paymentID, $updateCard = 0, $subscription = array())
    {
        if ($updateCard) {
            $subscriptions[] = $subscription;
        } else {
            $subscriptions = $this->getSubscriptionByOrderId($order_id);
        }

        $j2StorePlugin = J2Store::plugin();
        if (is_array($subscriptions) && count($subscriptions)) {
            foreach ($subscriptions as $susb) {
                $this->addSubscriptionMeta($susb->j2store_subscription_id, 'stripe_payment_id', $paymentID);
            }
        }

    }

    /**
     * To update subscription meta
     * */
    protected function addSubscriptionMeta($subscription_id, $key, $value)
    {
        $j2StorePlugin = J2Store::plugin();
        $j2StorePlugin->event('AddSubscriptionMeta', array($subscription_id, $key, $value));
    }

    /**
     * Get subscriptions based on order id
     * */
    protected function getSubscriptionByOrderId($order_id)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_subscriptions');
        $query->where('order_id = ' . $db->quote($order_id));

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    /**
     * Get subscriptions meta
     * */
    protected function getSubscriptionMetaData($subscription_id, $key)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = ' . $db->quote($subscription_id));
        $query->where('metakey = ' . $db->quote($key));
        $query->where('namespace = ' . $db->quote('subscription'));

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    /**
     * To process renewal payment
     * */
    function onJ2StoreProcessRenewalPayment($paymentType, $subscription, $order)
    {
        if ($paymentType == $this->_element) {
            $this->byRefference($subscription, $order);
        }
    }

    
    function onJ2StoreGetLastCardNumDigits($customer_id)
    {
        $this->setStripAppVersion();
        $this->byGetLastCardNumDigits($customer_id);

    }

    public function onJ2StoreDisplayAdditionalContentAfterEachRelatedOrderStatusInSubscription($order){
        $html = '';
        if($order->orderpayment_type == $this->_element){
            $html = $this->loadCompletePaymentButton($order);
        }

        return $html;
    }

    protected function loadCompletePaymentButton($order){
        $html = '';
        $model = F0FModel::getTmpInstance('AppPayment_stripe', 'J2StoreModel');
        $need_authentication = $model->checkEligibleForPaymentAuthentication($order, $this->params);
        if($need_authentication){
            $vars = new JObject();
            $vars->version = $this->_j2version;
            $vars->params = $this->params;
            $vars->order = $order;
            $vars->app_id = JPluginHelper::getPlugin('j2store', $this->_element)->id;
            $html = $this->_getLayout('authenticate_card_button', $vars);
        }

        return $html;
    }

    /**
     * Display additional button on subscription listing
     *
     * @param $subscription_order object
     * @param $parent_order object
     * @param $subscription object
     * @return string
     * */
    public function onJ2StoreDisplayAdditionalActionInSubscription($subscription_order, $parent_order, $subscription){
        $html = '';
        if ($subscription->payment_method == $this->_element) {
            $model = F0FModel::getTmpInstance('AppPayment_stripe', 'J2StoreModel');
            $order = $model->getLastPendingOrderFromSubscriptionId($subscription->j2store_subscription_id);
            if(!empty($order)){
                $html = $this->loadCompletePaymentButton($order);
            }
        }

        return $html;
    }

    /**
     * To load subscription card update form
     * */
    function onJ2StoreLoadSubscriptionPaymentCardUpdateForm($subscription, $order, $app_id)
    {
        if ($subscription->payment_method == $this->_element) {
            $document = JFactory::getDocument();
            $form_type = $this->params->get('form_type', 'normal');
            if ($form_type == "normal") {
                $document->addScript('https://js.stripe.com/v2/');
            } else if ($form_type == 'popup') {
                $document->addScript('https://checkout.stripe.com/checkout.js');
            } else {
                $document->addScript('https://js.stripe.com/v3/');
            }

            if($form_type != "normal"){
                $script = '(function($) {';
                $script .= ' $(document).ready(function(){';
                $script .=      '$("#button-subscription_update_card").hide();';
                $script .=      '$("#button-subscription_update_card").trigger("click");';
                $script .= ' });';
                $script .= '})(j2store.jQuery);';
                $document->addScriptDeclaration($script);
            }
            $stripeForm = $this->_renderForm(array());
            return $stripeForm;
        }
    }
}