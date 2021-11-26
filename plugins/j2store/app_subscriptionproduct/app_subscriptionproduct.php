<?php
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/app.php');
require_once(JPATH_ADMINISTRATOR .'/components/com_j2store/helpers/j2html.php');
require_once('app_subscriptionproduct/Helper/subscription_status.php');
require_once('app_subscriptionproduct/Helper/expirycontrol.php');
use J2Store\Subscription\Helper\ExpiryControl;

class plgJ2StoreApp_subscriptionproduct extends J2StoreAppPlugin
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element = 'app_subscriptionproduct';

    function __construct ( &$subject, $config )
    {
        $this->includeCustomModel ( 'AppSubscriptionProducts' );
        parent::__construct ( $subject, $config );
        JFactory::getLanguage ()->load ( 'plg_j2store_' . $this->_element, JPATH_ADMINISTRATOR );
        $document = JFactory::getDocument();
        $document->addStyleSheet(JUri::root().'media/plg_j2store_app_subscriptionproduct/css/style.css');
    }

    /**
     * Overriding
     *
     * @param $options
     * @return unknown_type
     */
    function onJ2StoreGetAppView ( $row )
    {
        if ( !$this->_isMe ( $row ) ) {
            return null;
        }
        $html = $this->viewList ();
        
        return $html;
    }

    /**
     * After display payment option
     * */
    function onJ2StoreAfterDisplayShippingPayment($order)
    {
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $hasSubscription = $model->checkHasSubscriptionAndTrialInOrder($order);
        $hasSubProductWithTrial = $hasSubscription['has_subscription_product_with_trial'];
        $hasSubProduct = $hasSubscription['has_subscription_product'];

        if($hasSubProduct){
            $plugins = $this->checkSubscriptionPaymentExist($order);
            $acceptedPayments = array_keys($plugins);
            $outerId = '#onCheckoutPayment_wrapper label.payment-plugin-image-label';
            $element = '';
            $elementForTrialSupport = '';
            $app = JFactory::getApplication();
            $selected_payment_methods = $this->params->get('payment_methods', array());
            foreach ($acceptedPayments as $acceptedPayment){
                if(!empty($selected_payment_methods) && !(in_array($acceptedPayment, $selected_payment_methods))) continue;
                $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionPaymentWithTrial", array( $acceptedPayment) );
                if (in_array(true, $results))
                {
                    $elementForTrialSupport .= $outerId.'.'.$acceptedPayment.',';
                }
                $element .= $outerId.'.'.$acceptedPayment.',';
            }
            $element = trim($element, ',');
            $elementForTrialSupport = trim($elementForTrialSupport, ',');

            $script = '<script type="text/javascript">
                    if(typeof(j2store) == "undefined") {
                        var j2store = {};
                    }
                    if(typeof(jQuery) != "undefined") {
                        jQuery.noConflict();
                    }
                    if(typeof(j2store.jQuery) == "undefined") {
                        j2store.jQuery = jQuery.noConflict();
                    }
                    (function($) {
                        $("#onCheckoutPayment_wrapper label.payment-plugin-image-label").hide();
                        $("' . $element . '").show().addClass("support_subscription_payment");';
            if($elementForTrialSupport != ''){
                $script .= '$("' . $elementForTrialSupport . '").show().addClass("support_subscription_payment_with_trial");';
            }
            $subscription_class = 'support_subscription_payment';
            if ((float)$order->order_total == (float)'0.00') {
                if($hasSubProductWithTrial){
                    $script .= '$("' . $outerId . '.support_subscription_payment").hide();';
                    $script .= '$("' . $outerId . '.support_subscription_payment_with_trial").show();';
                    $subscription_class = 'support_subscription_payment_with_trial';
                }
            }
            $script .= '$("#onCheckoutPayment_wrapper label.payment-plugin-image-label.'.$subscription_class.' input:radio:last").attr("checked", true).trigger("click");
                    })(j2store.jQuery);
                    </script>';
            echo $script;
        }
    }

    /**
     * load menu in j2store
     * */
    function onJ2StoreAddDashboardMenuInJ2Store(&$menus){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $params = $model->getAppDatails();
        $menus[] = array (
            'name' => 'Subscription',
            'icon' => 'fa fa-link',
            'link' => 'index.php?option=com_j2store&view=app&task=view&appTask=manageSubscription&id='.$params->extension_id
        );
    }

    /**
     * to allow product type
     * */
    function onJ2StoreIsProductTypeAllowed($product_type, $allowed_product_types, $context, &$status){
        if($product_type == 'subscriptionproduct'){
            if($context == 'importAttributeFromProduct'){
                $status = true;
            }
        }
    }

    /**
     * Validates the data submitted based on the suffix provided
     * A controller for this plugin, you could say
     *
     * @param $task
     * @return html
     */
    function viewList ()
    {
        $app = JFactory::getApplication();
        if($app->input->get('apptask') == 'manageSubscription'){
            //return $this->manageSubscription();
        }

        $option = 'com_j2store';
        $ns = $option.'.app.'.$this->_element;
        $html = "";
        JToolBarHelper::title(JText::_('J2STORE_APP').'-'.JText::_('PLG_J2STORE_'.strtoupper($this->_element)),'j2store-logo');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', 'index.php?option=com_j2store&view=apps');
        JToolBarHelper::back('J2STORE_BACK_TO_DASHBOARD', 'index.php?option=com_j2store');
        JToolBarHelper::link('index.php?option=com_j2store&view=app&task=view&appTask=manageSubscription&id='.$app->input->get('id'), 'J2STORE_PRODUCT_SUBSCRIPTIONS', 'subscriptions');
        $vars = new JObject();
        //model should always be a plural
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');

        $data = $this->params->toArray();
        
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;

        $id = $app->input->getInt('id', '0');
        $vars->id = $id;
        $vars->action = "index.php?option=com_j2store&view=app&task=view&id={$id}";
        $html = $this->_getLayout('default', $vars);
        return $html;
    }

    /**
     * Method to list j2store product type
     * @param $types j2store product type
    */
    public function	onJ2StoreGetProductTypes(&$types){
        $types['subscriptionproduct'] = JText::_('J2STORE_PRODUCT_TYPE_SUBSCRIPTIONPRODUCT');
        $types['variablesubscriptionproduct'] = JText::_('J2STORE_PRODUCT_TYPE_VARIABLESUBSCRIPTIONPRODUCT');
    }

    //display option in product
    function onJ2StoreAfterDisplayProductForm($a,$item){
        $html = '';

        if($item->product_type == 'subscriptionproduct'){
            $registry = new JRegistry();
            $registry->loadString($item->params);
            $vars = new stdClass();
            $vars->bundleproduct = $registry->get('subscriptionproduct',array()) ;
            $vars->form_prefix = $a->form_prefix;
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $params = $model->getAppDatails();
            $vars->id = $params->extension_id;
            $html = $this->_getLayout('form', $vars);
        }
        return $html;

    }

    /**
     * Display subscription details in Variable subscription product
     * */
    public function onJ2StoreAfterRenderingProductPrice($product){
        $html = '';
        if($product->product_type == 'variablesubscriptionproduct'){
            $variant = $product->variant;
            $params = $variant->params;
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $registry = new JRegistry();
            $registry->loadString($params);
            $vars = new stdClass();
            $vars->subscriptionproduct = $registry->get('subscriptionproduct',array());
            $vars->variant = $variant;
            $vars->product = $product;
            $vars->params = $variant->params;
            $vars->model = $model;
            $vars->hasSignUpFee = $model->isApplicableSignUpFee($product->j2store_product_id, $product->variant->j2store_variant_id);
            $html = $this->_getLayout('variable_subscription_details', $vars);
        }
        return $html;
    }

    /**
     * Display subscription details in Variable subscription product while changing options through ajax
     * */
    public function onJ2StoreUpdateProductResponse(&$res_data, $model, $product){
        $html = '';
        if($product->product_type == 'variablesubscriptionproduct') {
            $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable');
            $variant_table->load(array(
                'j2store_variant_id' => $res_data['variant_id']
            ));
            $variant = $variant_table;
            $params = $variant->params;
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $registry = new JRegistry();
            $registry->loadString($params);
            $vars = new stdClass();
            $vars->subscriptionproduct = $registry->get('subscriptionproduct',array());
            $vars->variant = $variant;
            $vars->product = $product;
            $vars->params = $variant->params;
            $vars->model = $model;
            $vars->hasSignUpFee = $model->isApplicableSignUpFee($product->j2store_product_id, $variant->j2store_variant_id);
            $html = $this->_getLayout('variable_subscription_details', $vars);
        }
        $res_data['afterDisplayPrice'] = $res_data['afterDisplayPrice'].$html;
    }

    /**
     * Display content after price in cart
     * */
    function onJ2StoreAfterDisplayLineItemTitle($item, $order, $param){
        $html = '';
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
            $product = J2Store::product()->setId($item->product_id)->getProduct();
            if(!empty($product)){
                $registry = new JRegistry();
                $vars = new stdClass();
                $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable');
                $variant_table->load(array(
                    'j2store_variant_id' => $item->variant_id
                ));
                $registry->loadString($variant_table->params);
                $vars->params = $variant_table->params;

                $vars->subscriptionproduct = $registry->get('subscriptionproduct',array());
                $vars->product = $product;
                $vars->model = $model;
                $vars->hasSignUpFee = $model->isApplicableSignUpFee($item->product_id, $item->variant_id);
                $html = $this->_getLayout('cart_content', $vars);
            }

        }
        return $html;
    }

    /**
     * Display content after price in cart
     * */
    function onJ2StoreGetFormattedOrderTotals($order, &$total_row){
        $showRecurringTotal = $this->params->get('show_recurring_total_cart', 1);
        if($showRecurringTotal){
            $orderItems = $order->getItems();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $html = '';
            foreach ($orderItems as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $product = J2Store::product()->setId($item->product_id)->getProduct();
                    $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
                    $variant_table->load(array(
                        'j2store_variant_id' => $item->variant_id
                    ));
                    $recurring_change = '';
                    if(isset($item->j2store_orderitem_id)){
                        if($item->orderitem_type == 'subscription'){
                            $first_renewal = $model->getNextRenewalDateFromSubscriptionOrder($order);
                            $subscription = $model->getSubscriptionFromSubscriptionOrder($order);
                        } else {
                            $first_renewal = $model->getNextRenewalDateFromOrderItem($item->j2store_orderitem_id);
                            $subscription = $model->getSubscriptionFromOrderItem($item->j2store_orderitem_id);
                        }
                        if(!empty($subscription)){
                            $recurring_change_array = $model->getSubscriptionHistory($subscription->j2store_subscription_id, array('subscription_history_recurring_edit'));
                            if(count($recurring_change_array)){
                                foreach ($recurring_change_array as $recurring_change_value){
                                    $comment = json_decode($recurring_change_value->metavalue);
                                    $recurring_change .= "<br>".$comment->comment;
                                }
                            }
                        }
                    } else {
                        $subscription = array();
                        if($item->product_type == 'subscriptionproduct') {
                            $first_renewal = $model->getFirstRenewalDate($product);
                        } else {
                            $first_renewal = $model->getFirstRenewalDate($variant_table);
                        }
                    }
                    $vars = new stdClass();
                    $registry = new JRegistry();
                    $registry->loadString($variant_table->params);
                    $vars->params = $variant_table->params;
                    $vars->subscriptionproduct = $registry->get('subscriptionproduct',array());
                    $vars->product = $product;
                    $vars->order = $order;
                    $vars->order_item = $item;
                    $vars->model = $model;
                    $vars->first_renewal = $first_renewal;
                    $vars->recurring_change = $recurring_change;
                    $vars->subscription = $subscription;
                    $vars->show_recurring_amount_in_cart = $this->params->get('show_recurring_amount_in_cart', 1);
                    $vars->show_recurring_discount_in_cart = $this->params->get('show_recurring_discount_in_cart', 1);
                    $html .= $this->_getLayout('cart_total_content', $vars);
                }
            }
            if($html != ''){
                $total_row['subscription'] = array(
                    'label' => JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_RECURRING_TOTAL'),
                    'value' => $html
                );
            }
        }
    }

    /**
     * add setup fee
     * */
    function onJ2StoreCalculateFees($order) {
        if($order->order_type == 'subscription'){
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $additionalFees = $model->getAdditionalFeesOfSubscriptionOrder($order->order_id);
            if(count($additionalFees)){
                foreach ($additionalFees as $additionalFee){
                    $order->add_fee($additionalFee->name, $additionalFee->amount, $additionalFee->taxable, $additionalFee->tax_class_id);
                }
            }
        } else {
            $orderItems = $order->getItems();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            foreach ($orderItems as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $hasSignUpFee = $model->isApplicableSignUpFee($item->product_id, $item->variant_id);
                    if($hasSignUpFee && $order->order_type != 'subscription'){
                        $product = J2Store::product()->setId($item->product_id)->getProduct();
                        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable');
                        $variant_table->load(array(
                            'j2store_variant_id' => $item->variant_id
                        ));
                        $registry = new JRegistry();
                        $registry->loadString($variant_table->params);
                        $subscriptionproduct = $registry->get('subscriptionproduct',array());
                        if(isset($subscriptionproduct->subscription_signup_fee) && $subscriptionproduct->subscription_signup_fee){
                            $fee_name = JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGN_UP_FEE').' ('.$product->product_name.')';

                            $multiplyQuantity = 0;
                            $j2StorePlugin = J2Store::plugin();
                            $j2StorePlugin->event('MultiplyQuantityWithSubscriptionSignUpFee', array(&$multiplyQuantity, $item));
                            if($multiplyQuantity){
                                $fee_value = $subscriptionproduct->subscription_signup_fee * $item->orderitem_quantity;
                            } else {
                                $fee_value = $subscriptionproduct->subscription_signup_fee;
                            }

                            //$tax_class_id = $this->params->get('fee_tax_class_id', '');
                            $tax_class_id = $product->taxprofile_id;
                            $taxable = false;
                            if($tax_class_id){
                                $taxable = true;
                            }
                            if($tax_class_id && $tax_class_id > 0) $taxable = true;
                            if($fee_value > 0 ) {
                                $order->add_fee($fee_name, round($fee_value, 2), $taxable, $tax_class_id, 'subscription_signup_fee');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * While displaying cart Item to add the sign up fee to the price
     * */
    /*function onJ2StoreDisplayCartItem($i, $item){

    }*/

    /**
     * On before checkout to check the supported payment plugin exists
     * */
    function onJ2StoreBeforeCheckout($order){
        $app = JFactory::getApplication();
        $j2store_config = J2Store::config();

        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $hasSubscription = $model->checkHasSubscriptionAndTrialInOrder($order);
        $hasSubProductWithTrial = $hasSubscription['has_subscription_product_with_trial'];
        $availableSubscriptionProduct = $hasSubscription['has_subscription_product'];
        if ((float)$order->order_total == (float)'0.00') {
            if($hasSubProductWithTrial){
                $plugins = $this->checkSubscriptionPaymentWithTrialSupportExist($order);
                if(empty($plugins)){
                    $link = JRoute::_('index.php?option=com_j2store&view=carts');
                    $app->enqueueMessage(JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NO_SUBSCRIPTION_PAYMENT_PLUGIN_WITH_TRIAL_SUPPORT_AVAILABLE'), 'notice');
                    $app->redirect($link);
                    return false;
                }
            }
        }
        if($availableSubscriptionProduct){
            $user = JFactory::getUser();
            if(!$user->get('id')){
                $show_login_form = $j2store_config->get('show_login_form', 1);
                $allow_registration = $j2store_config->get('allow_registration', 1);
                $allow_guest_checkout = $j2store_config->get('allow_guest_checkout', 0);
                if($show_login_form == 0 && $allow_registration == 0 && $allow_guest_checkout == 1){
                    $link = JRoute::_('index.php?option=com_j2store&view=carts');
                    $app->enqueueMessage(JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_PLEASE_LOGIN_TO_BUY_SUBSCRIPTION_PRODUCT'), 'notice');
                    $app->redirect($link);
                } else {
                    $script = '
                    if(typeof(j2store) == "undefined") {
                        var j2store = {};
                    }
                    if(typeof(jQuery) != "undefined") {
                        jQuery.noConflict();
                    }
                    if(typeof(j2store.jQuery) == "undefined") {
                        j2store.jQuery = jQuery.noConflict();
                    }
                    (function($) {
                        $(document).ready(function(){
                            $( "body" ).on( "after_login_response", function(e){
                                if($("#j2store-checkout-content input#guest").length){
                                    var guest = $("#j2store-checkout-content input#guest");
                                    if($("#j2store-checkout-content input#register").length){
                                        var register = $("#j2store-checkout-content input#register");
                                        register.attr("checked", "checked");
                                        guest.attr("disabled", "disabled").parent("label").hide();
                                    } else {
                                        guest.attr("disabled", "disabled").parent("label").hide();
                                        guest.attr("disabled", "disabled").parent("label").parent(".span6.left").hide();
                                    }
                                    
                                }
                            });
                        });
                    })(j2store.jQuery);';
                    $doc = JFactory::getDocument();
                    $doc->addScriptDeclaration($script);
                }
            }
            $plugins = $this->checkSubscriptionPaymentExist($order);
            if(empty($plugins)){
                $link = JRoute::_('index.php?option=com_j2store&view=carts');
                $app->enqueueMessage(JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NO_SUBSCRIPTION_PLUGIN_AVAILABLE'), 'notice');
                $app->redirect($link);
            }
        }
        return true;
    }

    /**
     * Check Subscription payment plugins exists
     * */
    function checkSubscriptionPaymentExist($order){
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $payment_plugins = J2Store::plugin()->getPluginsWithEvent( 'onJ2StoreGetPaymentPlugins' );
        $plugins = array();
        if ($payment_plugins)
        {
            foreach ($payment_plugins as $plugin)
            {
                $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionPayment", array( $plugin->element) );
                if (in_array(true, $results))
                {
                    $plugins[$plugin->element] = $plugin;
                }
            }
        }
        return $plugins;
    }

    /**
     * Check Subscription payment plugins with trial support exists
     * */
    function checkSubscriptionPaymentWithTrialSupportExist($order){
        $app = JFactory::getApplication();
        $params = J2Store::config();
        $payment_plugins = J2Store::plugin()->getPluginsWithEvent( 'onJ2StoreGetPaymentPlugins' );
        $plugins = array();
        if ($payment_plugins)
        {
            foreach ($payment_plugins as $plugin)
            {
                $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionPaymentWithTrial", array( $plugin->element) );
                if (in_array(true, $results))
                {
                    $plugins[$plugin->element] = $plugin;
                }
            }
        }
        return $plugins;
    }

    /**
     * On after save order
     * */
    function onJ2StoreAfterSaveOrder($order) {
        if(isset($order->order_id)){
            $order_table = F0FTable::getAnInstance('Order','J2StoreTable')->getClone();
            $order_table->load(array(
                'order_id' => $order->order_id
            ));
            $items = $order_table->getItems();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            foreach ($items as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $subscriptionId = $model->createNewSubscription($item, $order);
                    $model->saveOrderSubscriptions($item, $subscriptionId);
                }
            }
        }
    }
    
    /**
     * After Summary save Order / adding a product through backend
     * */
    function onJ2StoreAfterSummarySaveOrder($order){
        if(isset($order->order_id)){
            $order_table = F0FTable::getAnInstance('Order','J2StoreTable');
            $order_table->load(array(
                'order_id' => $order->order_id
            ));
            $items = $order_table->getItems();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            foreach ($items as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $subscriptionId = $model->createNewSubscription($item, $order);
                    $model->saveOrderSubscriptions($item, $subscriptionId);
                }
            }
        }
    }
    
    /**
     * After reset order Item
     * */
    function onJ2StoreAfterResetOrderItem($item){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        if(isset($item->orderitem_type) && $item->orderitem_type != 'subscription_renewal'){
            if(isset($item->j2store_orderitem_id)){
                $model->deleteSubscriptions($item->j2store_orderitem_id);
                //For recreate the order subscription
                $model->deleteOrderSubscriptions($item->order_id);
            }
        }
    }

    /**
     * After delete subscription
     * */
    function onJ2StoreAfterDeleteSubscription($subscription){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->removeSubscriptionDatas($subscription);
        $j2StorePlugin = J2Store::plugin();
        $j2StorePlugin->event('RefreshUserGroups', array($subscription->user_id));
    }

    /**
     * update subscription history
     * */
    function onJ2StoreAddSubscriptionHistory($subsriptionId, $status, $comment, $notify_customer = 0){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->updateSubscriptionHistory($subsriptionId, $status, $comment, $notify_customer);
    }

    /**
     * change subscription status
     * */
    function onJ2StoreChangeSubscriptionStatus($subscriptionId, $status, $notify_customer = true){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->changeSubscriptionStatus($subscriptionId, $status, $notify_customer);
        //$model->sendEmailOnStatusChange($subscriptionId, $status);
    }

    /**
     * Refresh user group
     * */
    function onJ2StoreRefreshUserGroups($user_id){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->refreshUserGroups($user_id);
    }

    /**
     * end mail to customer to notify expire
     * */
    function onJ2StoreSendMailToCustomerNotifyExpire($subscriptionId, $dayBefore){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->sendMailToCustomerNotifyExpire($subscriptionId, $dayBefore);
    }

    /**
     * Mail to customer to notify Next Renewal 
     * */
    function onJ2StoreSendMailToCustomerNotifyNextRenewal($subscriptionId, $dayBefore){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->sendMailToCustomerNotifyNextRenewal($subscriptionId, $dayBefore);
    }

    /**
     * Mail to customer to notify First auto Renewal from trial
     * */
    function onJ2StoreSendMailToCustomerNotifyFirstRenewalFromTrial($subscriptionId, $dayBefore){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->sendMailToCustomerNotifyFirstRenewalFromTrial($subscriptionId, $dayBefore);
    }


    /**
     * Method to display MyProfile Tab
     * @return html  */
    public function onJ2StoreAddMyProfileTab(){
        $html = $this->_getLayout('profile_tab');
        return $html;
    }

    /**
     * Method to display MyProfile Content
     * @param unknown $order
     * @return unknown  */
    public function onJ2StoreAddMyProfileTabContent($order){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $vars = new stdClass();
        $vars->data = $model->getUserSubscriptions();
        $app_details = $model->getAppDatails();
        $vars->id = $app_details->extension_id;
        $vars->params = $this->params;
        $vars->model = $model;
        $html = $this->_getLayout('profile_subscriptions', $vars);
        return $html;
    }

    /**
     * Refresh user group
     * */
    function onJ2StoreAddSubscriptionMeta($subscription_id, $key, $value){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->addSubscriptionMeta($subscription_id, $key, $value);
    }

    /**
     * Process subscription renewal
     * */
    function onJ2StoreProcessSubscriptionRenewal($subscription_id){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->processSubscriptionRenewal($subscription_id);
    }

    /**
     * On success Renewal payment
     * */
    
    function onJ2StoreSuccessStripeCustomer($customer){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->SuccessStripeCustomer($customer);
    }
    function onJ2StoreSuccessRenewalPayment($subscription, $order, $update_renewal_date = true){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->processSuccessRenewalPayment($subscription, $order, $update_renewal_date);
        $model->refreshUserGroups($subscription->user_id);
        $send_email_on_renewal_success = true;
        J2Store::plugin()->event('SendEmailOnRenewalSuccess', array(&$send_email_on_renewal_success, $subscription, $order));
        if($send_email_on_renewal_success === true){
            $model->sendMailOnAfterSuccessRenewalPayment($subscription->j2store_subscription_id);
        }
    }

    /**
     * On Renewal payment failed
     * */
    function onJ2StoreFailedRenewalPayment($subscription, $order){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->processFailedRenewalPayment($subscription, $order);
        $model->refreshUserGroups($subscription->user_id);
    }

    /**
     * On Renewal payment - no response from payment gate way / may be cart expired or customer details deleted from payment gateway
     * */
    function onJ2StoreNoResponseForRenewalPayment($subscription, $order){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->processNoResponseRenewalPayment($subscription, $order);
    }

    /**
     * On fail Renewal payment - due to card errors
     * */
    function onJ2StoreUpdateCardExpiredForSubscription($subscription, $order){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->updateCardExpiredOnRenewalPaymentFailedDueToCardError($subscription, $order);
    }

    /**
     * On after card updated
     * */
    function onJ2StoreCardUpdateSuccess($subscription){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        if(isset($subscription->j2store_subscription_id) && !empty($subscription->j2store_subscription_id)){
            $model->sendMailOnAfterCardUpdated($subscription->j2store_subscription_id);
        }
    }

    /**
     * On Renewal payment pending
     * */
    function onJ2StorePendingRenewalPayment($subscription, $order){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model->processPendingRenewalPayment($subscription, $order);
        $model->refreshUserGroups($subscription->user_id);
    }

    /**
     * verify Chosen payment is acceptable
     * */
    function onJ2StoreGetPaymentFormVerify( $element, $data )
    {
        $order = F0FModel::getTmpInstance('Orders', 'J2StoreModel')->initOrder()->getOrder();
        $items = $order->getItems();
        $availableSubscriptionProduct = 0;
        foreach ($items as $key => $item){
            if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                $availableSubscriptionProduct = 1;
                break;
            }
        }
        if($availableSubscriptionProduct){
            $app = JFactory::getApplication();
            $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionPayment", array( $element ) );
            if (empty($results) || !in_array(true, $results)) {
                $object = new JObject ();
                $object->error = true;
                $object->message = "<li>" .JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_PAYMENT_NOT_SUPPORTED'). "</li>";
                return $object;
            }
        }

        if (!$this->_isMe($element)) {
            return null;
        }

        $html = $this->_verifyForm( $data );

        return $html;
    }

    /**
     * Display Subscription Details in order page
     * */
    function onJ2StoreAdminOrderAfterGeneralInformation($item){
        $html = '';
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $vars = new stdClass();
        $vars->order = $item->order;
        $vars->subscriptions = $model->getAllSubscriptionByOrderID($item->item->order_id);
        if(count($vars->subscriptions)){
            $app_details = $model->getAppDatails();
            $vars->id = $app_details->extension_id;
            $html = $this->_getLayout('order_subscription', $vars);
        }
        echo $html;
    }

    /**
     * Check subscription product available for an order
     * */
    function onJ2StoreCheckHasSubscriptionProductFromOrderID($element, $order_id, &$hasSubProduct){
        if($element == $this->_element){
            $order_table = F0FTable::getAnInstance('Order','J2StoreTable');
            $order_table->load(array(
                'order_id' => $order_id
            ));

            $items = $order_table->getItems();
            foreach ($items as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $hasSubProduct = 1;
                    break;
                }
            }
        }
    }

    /**
     * To run cron job
     * */
    public function onJ2StoreProcessCron($command){
        if($command === 'appsubscriptionproduct'){
            ExpiryControl::getInstance()->executeExpirationControlThoughCron();
        }
    }

    /**
     * On before display cart item
     * */
    public function onJ2StoreDisplayCartItem($i, $item){
        if($item->product_qty > 1){
            if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                $quantityLimit = 1;
                $j2StorePlugin = J2Store::plugin();
                $j2StorePlugin->event('ChangeQuantityLimitForSubscriptionProduct', array(&$quantityLimit, $item));
                if($quantityLimit){
                    $item->product_qty = 1;
                    $table = F0FTable::getInstance ( 'Cartitems', 'J2StoreTable' );
                    $table->load(array(
                        'j2store_cartitem_id' => $item->j2store_cartitem_id
                    ));
                    $table->product_qty = 1;
                    $table->store();
                }
            }
        }
    }

    /**
     * Trigger for on after confirming free product
     * */
    public function onJ2StoreAfterConfirmFreeProduct($order){
        if(( float ) $order->order_total == ( float ) '0.00'){
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $subscriptions = $model->getSubscriptionByOrderId($order->order_id);
            if(is_array($subscriptions) && count($subscriptions)){
                $j2StorePlugin = J2Store::plugin();
                foreach ($subscriptions as $susb){
                    $j2StorePlugin->event('ChangeSubscriptionStatus', array($susb->j2store_subscription_id, 'active'));
                    $j2StorePlugin->event('RefreshUserGroups', array($susb->user_id));
                }
            }
        }
    }

    /**
     * On before save order fees
     * */
    function onJ2StoreOnBeforeSaveOrderFees($order, &$fees){
        if($order->order_type == 'subscription'){
            foreach ( $fees as $key => $fee ) {
                // To remove sign up fee for renewal subscription
                if($fee->fee_type == 'subscription_signup_fee'){
                    unset($fees[$key]);
                }
            }
        }
    }

    /**
     * add orderitem to cart and cartitem  table for renew subscription
     * @param object $orders
     * @return html  */
    function onJ2StoreAfterDisplayOrder($orders) {
        $view = 'checkout';//$this->params->get('redirect','carts');
        $app = JFactory::getApplication ();
        $session = JFactory::getSession ();
        $db = JFactory::getDBO ();
        if ($app->isAdmin ())
            return true;
        $user = JFactory::getUser ();
        $user_id = $user->id;
        $data = $app->input->getArray ( $_POST );
        $coupon_code = $app->input->getVar('coupon');
        $sid = 0;
        if(isset($data['sid'])){
            $sid=$data['sid'];
        }
        if (isset ( $data ['profileTask'] )) {
            if ($data ['profileTask'] == "renew") {
                if ( $sid) {
                    $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
                    $subscription->load(array('j2store_subscription_id' => $sid));

                    $orderItems = F0FTable::getInstance('OrderItems' ,'J2StoreTable')->getClone();
                    $orderItems->load(array('j2store_orderitem_id' => $subscription->orderitem_id));
                    if(isset($orderItems->j2store_orderitem_id) && $orderItems->j2store_orderitem_id){
                        if(!$user_id) {
                            //guest user
                            J2Store::cart()->deleteSessionCartItems($session->getId());
                        }else {
                            $cart_table = F0FTable::getAnInstance ( 'Cart', 'J2StoreTable' )->getClone ();
                            $cart_table->load(array('user_id'=>$user_id));
                            if(!empty($cart_table->j2store_cart_id)){
                                $query = $db->getQuery(true);
                                $query = 'DELETE FROM #__j2store_cartitems where cart_id ='.$cart_table->j2store_cart_id;
                                $db->setQuery ( $query );
                                $db->execute ();
                            }

                            $del_qry = $db->getQuery ( true );
                            // delete the old cart items for that user
                            $del_qry = 'DELETE FROM #__j2store_carts WHERE user_id=' . $user_id;
                            $db->setQuery ( $del_qry );
                            $result = $db->execute ();
                        }
                        $cart = F0FTable::getAnInstance ( 'Cart', 'J2StoreTable' )->getClone ();
                        $cart_data = array();
                        $cart_data['user_id'] = $user_id;
                        $cart_data['session_id'] = $session->getId ();
                        $cart_data['cart_type'] = 'cart';
                        $cart_data['customer_ip'] = $_SERVER['REMOTE_ADDR'];
                        $browser = JBrowser::getInstance();
                        $cart_data['cart_browser'] = $browser->getBrowser();
                        $cart_data['cart_analysis'] = json_encode(array(
                            'is_mobile'=>$browser->isMobile() ));
                        $cart->bind ( $cart_data );
                        if ($cart->store ()) {
                            if(!empty($coupon_code)){
                                $coupon_model = F0FModel::getTmpInstance ( 'Coupons', 'J2StoreModel' );
                                $coupon_model->set_coupon($coupon_code);
                            }

                            // add the items to the cart
                            $cart_item = new JObject ();
                            $cart_item->cart_id = $cart->j2store_cart_id;
                            // $cart_item->session_id = $session->getId ();
                            $cart_item->product_id = $orderItems->product_id;
                            $cart_item->vendor_id = $orderItems->vendor_id;
                            $cart_item->variant_id = $orderItems->variant_id;
                            $cart_item->product_type = $orderItems->product_type;
                            $cart_item->product_options = $orderItems->orderitem_attributes;
                            $cart_item->product_qty = $orderItems->orderitem_quantity;
                            $cart_item_table = F0FTable::getAnInstance ( 'Cartitems', 'J2StoreTable' )->getClone ();
                            $cart_item_table->bind ( $cart_item );
                            $cart_item_table->store ();
                        }
                        $app->redirect(JRoute::_("index.php?option=com_j2store&view=".$view));//checkout or cart
                    }
                }
            }
        }
        $res = '';
        return $res;
    }

    /**
     * it calculate discount amount
     * @param float $price
     * @param object $item
     * @param unknown $add_totals
     * @param object $order  */
    function onJ2storeGetDiscountedPrice(&$price, &$item, $add_totals, &$order){
        $app = JFactory::getApplication();
        if($item->orderitem_type != 'subscription'){
            return ;
        }

        $product = J2Store::product()->setId($item->product_id)->getProduct();
        F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);
        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
        $variant_table->load(array(
            'j2store_variant_id' => $item->variant_id
        ));

        $registry = new JRegistry();
        $registry->loadString($variant_table->params);
        $subscriptionproduct = $registry->get('subscriptionproduct',array());
        $apply_same_coupon = isset($subscriptionproduct->apply_same_coupon)? $subscriptionproduct->apply_same_coupon: '';
        $discount_price = $addQuantity = 0;
        $is_cron = false;
        if(defined('RUNNING_J2STORE_SUBSCRIPTION_RENEWAL_CRON')){
            if(RUNNING_J2STORE_SUBSCRIPTION_RENEWAL_CRON == true){
                $is_cron = true;
            }
        }
        if($app->isAdmin() || $is_cron){
            $session = JFactory::getSession();
            $session->set('renewal_discount', 0, 'j2store');
            if($order->subscription_id){
                $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
                $orderDiscounts = $model->getSubscriptionOrderDiscounts($order->order_id);
                foreach ($orderDiscounts as $orderDiscount){
                    if($orderDiscount->discount_value_type == 'percentage_product' || $orderDiscount->discount_value_type == 'percentage_cart'){
                        $discount_price = $orderDiscount->discount_value * ($price / 100);
                    } else if($orderDiscount->discount_value_type == 'fixed_product' || $orderDiscount->discount_value_type == 'fixed_cart'){
                        $discount_price = $orderDiscount->discount_value;
                    }
                    $total_discount = $discount_price;
                    if ($add_totals) {
                        if(in_array($orderDiscount->discount_value_type, array('percentage_product', 'percentage_cart', 'fixed_product'))){
                            $total_discount = $discount_price * $item->orderitem_quantity;
                            $addQuantity = 1;
                        }
                    }
                    $this->addDiscountedPrice($price, $item, $add_totals, $order, $total_discount, $addQuantity);
                }
            }
        } else {
            if($apply_same_coupon == "1"){
                return ;
            }
            $renewalDiscount = (int)$this->params->get('renewal_discount_percent', 0);
            if($renewalDiscount > 0 && $renewalDiscount < 100){
                $discount_price = round((($renewalDiscount / 100) * $price), 2);
                $order->renewal_discount_value = $renewalDiscount;
                $session = JFactory::getSession();
                $session->set('renewal_discount', 1, 'j2store');
                if ($add_totals) {
                    $discount_price = $discount_price * $item->orderitem_quantity;
                    $addQuantity = 1;
                }
                $this->addDiscountedPrice($price, $item, $add_totals, $order, $discount_price, $addQuantity);
            }
        }
    }

    function addDiscountedPrice(&$price, &$item, $add_totals, &$order, $discount_price, $addQuantity){
        if(!($discount_price>0)){
            return ;
        }
        $params = J2Store::config ();

        $discount_amount = $discount_price;//$session->get('discount_price',0,'j2store');
        // $discount_amount = $this->get_discount_amount ( $price, $item, $order, $single = true ,$coupon_amount);

        // Store the totals for DISPLAY in the cart
        if ($add_totals) {
//            $total_discount = $discount_amount * $item->orderitem_quantity;
            $total_discount_tax = 0;
            $total_discount = $discount_amount;
            // calculate discount price and tax
            if ($item->orderitem_taxprofile_id) {
                $taxModel = F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
                $tax_rates = $taxModel->getTaxwithRates($discount_amount, $item->orderitem_taxprofile_id, $params->get('config_including_tax', 0));
                if($addQuantity){
                    $total_discount_tax = $tax_rates->taxtotal * $item->orderitem_quantity;
                } else {
                    $total_discount_tax = $tax_rates->taxtotal;
                }
                //Discount total is always without tax.
                $total_discount = ($params->get('config_including_tax', 0)) ? $discount_amount - $total_discount_tax : $discount_amount;
            }
            
            // renewal discount set in order object
            if (isset($order->renewal_discount)) {
                $order->renewal_discount += $total_discount;
            } else {
                $order->renewal_discount = $total_discount;
            }
            // reward discount tax set in order object
            if (isset($order->renewal_discount_tax)) {
                $order->renewal_discount_tax += $total_discount_tax;
            } else {
                $order->renewal_discount_tax = $total_discount_tax;
            }            
            $price = max($price - $discount_amount, 0);
            $item->orderitem_discount = $total_discount;
            $item->orderitem_discount_tax = $total_discount_tax;
            $order->discount_cart += $total_discount;
            $order->discount_cart_tax += $total_discount_tax;
            $order->increase_coupon_discount_amount(JText::_('J2STORE_SUBSCRIPTIONAPP_RENEWAL_COUPON_TITLE'), $total_discount, $total_discount_tax);
        }
    }

    /**
     * this function calculate per item discount
     * @param float $discounting_amount
     * @param object $cartitem
     * @param object $order
     * @param string $single
     * @param float $coupon_amount
     * @return Ambigous <number, mixed>  */
    public function get_discount_amount($discounting_amount, $cartitem, $order, $single=true,$coupon_amount,$coupon_for_product=0) {
        //Fixed product coupon. So apply it on the product.
        $discount = min( $coupon_amount, $discounting_amount );
        //Hotfix. Divide this by quantity
        $discount = min( ($discount / $cartitem->orderitem_quantity), $discounting_amount);
        return $discount;
    }

    /**
     * Method to update discount price and get data of other_total
     * @param unknown $order  */
    public function onJ2StoreCalculateDiscountTotals($order)
    {
        $session = JFactory::getSession();
        $label = JText::_('J2STORE_SUBSCRIPTIONAPP_RENEWAL_COUPON_TITLE');
        $discountCode = 'RENEWAL_COUPON';
        if ($session->get('renewal_discount', 0, 'j2store') && isset($order->renewal_discount)) {
            $order_discount = $order->renewal_discount;
            $discount = new stdClass();
            $discount->discount_type = 'subscription_renewal';
            $discount->discount_entity_id = '';
            $discount->discount_title = $label;
            $discount->discount_code = $discountCode;
            $discount->discount_value = $order->renewal_discount_value;
            $discount->discount_value_type = 'percentage_product';
            $discount->discount_amount = $order_discount;
            $discount->discount_tax = isset($order->renewal_discount_tax) ? $order->renewal_discount_tax : 0;
            $order->addOrderDiscounts($discount);
        }
    }

    /**
     * To remove coupon after apply discount
     * */
    public function onJ2StoreHandleCouponAfterCreateSubscriptionOrder(){
        $session = JFactory::getSession();
        $session->set('renewal_discount', 0, 'j2store');
    }

    /**
     * Create new subscription order event
     * */
    public function onJ2StoreCreateNewSubscriptionOrderForRenewal(&$order_id, $subscription){
        if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $order_id = $model->createNewOrderForRenewalEvent($subscription->j2store_subscription_id);
        }
    }

    /**
     * To calculate subscription end date through event
     * */
    public function onJ2StoreCalculateSubscriptionEndDate(&$endOn, $startDate, $period, $period_units, $length){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $endOn = $model->calculateSubscriptionEndDate($startDate, $period, $period_units, $length);
    }

    /**
     * To calculate subscription end date through event
     * */
    public function onJ2StoreGetCurrentDate(&$now){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $now = $model->getCurrentDate();
    }

    /**
     * AfterCalculateBasePriceInProductTotal - To change the price if has trial
     * */
    public function onJ2StoreAfterCalculateBasePriceInProductTotal(&$item, $order, &$base_price){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $accpetedProductTypes = $model->acceptedProductType();
        if(in_array($item->product_type, $accpetedProductTypes) && $item->orderitem_type == 'normal'){
            $product = J2Store::product()->setId($item->product_id)->getProduct();
            F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);
            $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
            $variant_table->load(array(
                'j2store_variant_id' => $item->variant_id
            ));

            $registry = new JRegistry();
            $registry->loadString($variant_table->params);
            $subscriptionproduct = $registry->get('subscriptionproduct',array());
            $subscription_free_trial = isset($subscriptionproduct->subscription_free_trial)? $subscriptionproduct->subscription_free_trial: 0;
            if($subscription_free_trial > 0){
                $base_price = round(0, 2);
            }
        }
    }

    /**
     * Change ShowPayment OnTotalZero - while having trial
     * */
    public function onJ2StoreChangeShowPaymentOnTotalZero($order, &$showPayment){
        if($order->order_type == 'subscription'){
            $showPayment = true;
        } else if ((float)$order->order_total == (float)'0.00') {
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $hasSubscription = $model->checkHasSubscriptionAndTrialInOrder($order);
            $hasSubProductWithTrial = $hasSubscription['has_subscription_product_with_trial'];
            if($hasSubProductWithTrial) $showPayment = true;
        }
    }

    /**
     * get subscription
     * */
    public function onJ2StoreGetSubscription($subscriptionId, &$result){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $result = $model->getSubscriptionById($subscriptionId);
    }

    /**
     * Process Subscription Card Update Checkout
     * */
    public function onJ2StoreProcessSubscriptionCardUpdateCheckout($subscriptionId, &$subscription, &$process){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $subscription = $model->getSubscriptionById($subscriptionId);

        if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
            $support_trial = $model->hasTrialSupport($subscription->payment_method);
            $update_card  = $model->isDisplayCardUpdate($subscription);
            if(($subscription->status == 'card_expired' || $update_card) && $support_trial){
                $process = true;
            }
        }
    }

    /**
     * Display messages above myprofile
     * */
    public function onJ2StoreAddMessagesToMyProfileTop($orders){
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $hasExpiredCard = $model->hasExpiredCardForAnySubscription();
        if($hasExpiredCard){
            return '<div class="alert alert-warning"><p>'.JText::_('J2STORE_SUBSCRIPTION_TEXT_YOU_HAVE_A_SUBSCRIPTION_TO_UPDATE_CARD').' <a href="#subscription-tab" class="btn btn-warning" data-toggle="tab">'.JText::_('J2STORE_PRODUCT_SUBSCRIPTIONS').'</a></p></div>';
        } else {
            return '';
        }
    }

    /**
     * To process additional short-codes
     * */
    public function onJ2StoreAfterProcessTags(&$text, $order, $tags){
        $hasSubProduct = 0;
        if(!empty($order->subscription_id)){
            $hasSubProduct = 1;
        } else {
            $items = $order->getItems();
            foreach ($items as $key => $item){
                if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                    $hasSubProduct = 1;
                    break;
                }
            }
        }

        if($hasSubProduct){
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $billingCycleData = $model->getBillingCycleOfSubscriptionOrderItem($order->order_id);
            if(!empty($billingCycleData)){
                if($billingCycleData->subscription_length){
                    $text = str_replace('[SUBSCRIPTION_BILLING_CYCLE]', $billingCycleData->billing_cycle."/".$billingCycleData->total_billing_cycle, $text);
                } else {
                    $text = str_replace('[SUBSCRIPTION_BILLING_CYCLE]', $billingCycleData->billing_cycle, $text);
                }
            }
        }
        
    }

    /**
     * Disable guest check out for Subscription products
     * */
    public function onJ2StoreBeforeCheckoutView(&$view){
        $order = F0FModel::getTmpInstance('Orders', 'J2StoreModel')->initOrder()->getOrder();
        $items = $order->getItems();
        foreach ($items as $key => $item){
            if($item->product_type == 'subscriptionproduct' || $item->product_type == 'variablesubscriptionproduct'){
                $view->storeProfile->set('allow_guest_checkout', false);
                break;
            }
        }
    }

    /**
     * J2Store on before order notification
     *
     * @param $order object
     * @param $mailer object
     * */
    public function onJ2StoreBeforeOrderNotification($order, &$mailer){
        $renewal_completed_order_email = $this->params->get('renewal_completed_order_email', 1);
        if(!$renewal_completed_order_email){
            if(!empty($order->parent_id) && !empty($order->subscription_id)){
                if((int)$order->subscription_id > 0){
                    if(in_array($order->order_state_id, array(1))){
                        $mailer->clearAllRecipients();
                    }
                }
            }
        }
    }

    /**
     * Get Subscription product name
     *
     * @param $name string
     * @param $subscription_id integer
     * */
    public function onJ2StoreGetSubscriptionProductName(&$name, $subscription_id){
        if(!empty($subscription_id) && $subscription_id){
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $name = $model->getProductNameFromSubscriptionOrder($subscription_id);
        }
    }
}
