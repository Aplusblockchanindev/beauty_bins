<?php
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appmodel.php');
require_once(JPATH_SITE.'/plugins/j2store/app_subscriptionproduct/app_subscriptionproduct/Helper/subscriptionmeta.php');
use J2Store\Subscription\Helper\SubscriptionMeta;

class J2StoreModelAppSubscriptionProducts extends J2StoreAppModel
{
    public $_element = 'app_subscriptionproduct';

    /**
     * Plans to Groups to Add mapping
     *
     * @var  array
     */
    protected $addGroups = array();

    /**
     * Plans to Groups to Remove mapping
     *
     * @var  array
     */
    protected $removeGroups = array();

    function getpluginParams(){
        $plugin_data = JPluginHelper::getPlugin('j2store', $this->_element);
        $params = new JRegistry;
        $params->loadString($plugin_data->params);
        return $params;
    }

    function getAppDatails(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__extensions');
        $query->where('folder ='.$db->q('j2store'));
        $query->where('type ='.$db->q('plugin'));
        $query->where('element ='.$db->q($this->_element));
        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get First Renewal Date
     * */
    public function getFirstRenewalDate($item){
        $registry = new JRegistry();
        $registry->loadString($item->params);
        $subscriptionproduct = $registry->get('subscriptionproduct');
        $startDate = $this->getCurrentDate();

        $period = isset($subscriptionproduct->subscription_period)? $subscriptionproduct->subscription_period: 'D';
        $period_units = isset($subscriptionproduct->subscription_period_units)? $subscriptionproduct->subscription_period_units: 0;
        $length = isset($subscriptionproduct->subscription_length)? $subscriptionproduct->subscription_length: 1;
        $subscription_free_trial = isset($subscriptionproduct->subscription_free_trial)? $subscriptionproduct->subscription_free_trial: 0;
        $subscription_trial_period = isset($subscriptionproduct->subscription_trial_period)? $subscriptionproduct->subscription_trial_period: 'D';
        if($subscription_free_trial){
            $endate = $this->createSubscriptionEndDate($startDate, $subscription_trial_period, $subscription_free_trial, $subscription_free_trial);
            return $endate;
        }
        if($period_units && $length > $period_units){
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
            $endate = $this->getDateTime($endate);
        } else if($length == 0){
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $period_units);
            $endate = $this->getDateTime($endate);
        } else {
            $endate = '';
        }
        return $endate;
    }

    /**
     * Current date and time
     * */
    public function getCurrentDate(){
        $tz = \JFactory::getConfig()->get('offset');
        $date = \JFactory::getDate(date('Y-m-d H:i:s'), $tz);
        return date('Y-m-d H:i:s', strtotime($date));
    }

    /**
     * get Only date
     * */
    protected function getDate($data){
        return date('Y-m-d', strtotime($data));
    }

    /**
     * get Only date
     * */
    protected function getDateTime($data){
        return date('Y-m-d H:i:s', strtotime($data));
    }

    /**
     * create End date and time
     * */
    protected function createSubscriptionEndDate($startDate, $period, $period_units, $length){
        if($length > 0){
            $total_days = $this->getDurationInDays($period, $period_units);
            return $this->addDaysToTheDate($total_days, $startDate);
        } else {
            return '';
        }
    }

    /**
     * Get days from period and units
     * */
    protected function getDurationInDays($period, $unit){
        switch($period){
            case 'W':
                $unit = $unit*7;
                break;
            case 'M':
                $unit = $unit*30;
                break;
            case 'Y':
                $unit = $unit*365;
                break;
        }
        return $unit;
    }

    /**
     * Add days to a date
     * */
    protected function addDaysToTheDate($days, $date){
        return date("Y-m-d H:i:s", strtotime($date." +".$days." days"));
    }

    /**
     * Get Period Text
     * */
    public function getPeriodText($subscription_period){
        $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS');
        switch ($subscription_period){
            case 'D':
                $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS');
                break;
            case 'W':
                $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS');
                break;
            case 'M':
                $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS');
                break;
            case 'Y':
                $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR');
                break;
        }

        return $subscription_period_string;
    }

    /**
     * Get Period Text String
     * */
    public function getPeriodTextString($subscription_period){
        $subscription_period_string = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS';
        switch ($subscription_period){
            case 'D':
                $subscription_period_string = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS';
                break;
            case 'W':
                $subscription_period_string = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS';
                break;
            case 'M':
                $subscription_period_string = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS';
                break;
            case 'Y':
                $subscription_period_string = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR';
                break;
        }

        return $subscription_period_string;
    }

    /**
     * Get Period Units Text
     * */
    public function getPeriodUnitsText($subscription_period_units){
        $subscription_period_units_text = $subscription_period_units;
        switch ($subscription_period_units){
            case '1':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY');
                break;
            case '2':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY2');
                break;
            case '3':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY3');
                break;
            case '4':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY4');
                break;
            case '5':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY5');
                break;
            case '6':
                $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY6');
                break;
        }

        return $subscription_period_units_text;
    }

    /**
     * Save new order subscription
     * */
    public function saveOrderSubscriptions($item, $subscription_id){
        $product = J2Store::product()->setId($item->product_id)->getProduct();
        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
        $variant_table->load(array(
            'j2store_variant_id' => $item->variant_id
        ));
        $registry = new JRegistry();
        $registry->loadString($variant_table->params);
        $subscriptionproduct = $registry->get('subscriptionproduct',array());

        $period = isset($subscriptionproduct->subscription_period)? $subscriptionproduct->subscription_period: 'D';
        $period_units = isset($subscriptionproduct->subscription_period_units)? $subscriptionproduct->subscription_period_units: 0;
        $length = isset($subscriptionproduct->subscription_length)? $subscriptionproduct->subscription_length: 1;
        $subscription_free_trial = isset($subscriptionproduct->subscription_free_trial)? $subscriptionproduct->subscription_free_trial: 0;
        $subscription_trial_period = isset($subscriptionproduct->subscription_trial_period)? $subscriptionproduct->subscription_trial_period: 'D';
        $startDate = $this->getCurrentDate();
        if($period_units && $length >= 0) {
        } else {
            $length = 1;
            $period_units = 100;
            $period = 'Y';
        }
        if($subscription_free_trial){
            $endate = $this->createSubscriptionEndDate($startDate, $subscription_trial_period, $subscription_free_trial, $subscription_free_trial);
        } else {
            if($length == 0){
                $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $period_units);
            } else {
                $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
            }
        }

        $order = F0FTable::getInstance('Ordersubscription', 'J2StoreTable')->getClone();
        $order->load(array('orderitem_id' => $item->j2store_orderitem_id));
        $order->orderitem_id = $item->j2store_orderitem_id;

        $order->order_id = $item->order_id;
        $order->term_start_on = $startDate;
        $order->term_end_on = $endate;
        $order->billing_cycle = 1;
        $order->schedule_next_payment = $endate;
        $order->subscription_id = $subscription_id;
        $order->store();
    }

    /**
     * Save renewal order subscription
     * */
    public function saveRenewalOrderSubscriptions($new_orderItem, $subscription){
        $period = $subscription->period;
        $period_units = $subscription->period_units;
        $length = $subscription->subscription_length;
        $startDate = $subscription->next_payment_on;
        if($period_units && $length >= 0) {
        } else {
            $length = 1;
            $period_units = 100;
            $period = 'Y';
        }
        if($length == 0){
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $period_units);
        } else {
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
        }

        $order = F0FTable::getInstance('Ordersubscription', 'J2StoreTable')->getClone();
        $order->load(array('orderitem_id' => $new_orderItem->j2store_orderitem_id));
        $order->orderitem_id = $new_orderItem->j2store_orderitem_id;

        $order->order_id = $new_orderItem->order_id;
        $order->term_start_on = $startDate;
        $order->term_end_on = $endate;
        $order->billing_cycle = $subscription->current_billing_cycle+1;
        $order->schedule_next_payment = $endate;
        $order->subscription_id = $subscription->j2store_subscription_id;
        return $order->store();
    }

    /**
     * Delete Order subscription
     * */
    function deleteOrderSubscriptions($oid){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)->delete('#__j2store_ordersubscriptions')->where('order_id = '.$db->q($oid));
        try {
            $db->setQuery($query)->execute();
        }catch (Exception $e) {
            //do nothing. Because this is not harmful even if it fails.
        }
        return true;
    }

    /**
     * Create New subscription
     * */
    function createNewSubscription($item, $order){
        $app = JFactory::getApplication();
        $product = J2Store::product()->setId($item->product_id)->getProduct();
        F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);
        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
        $variant_table->load(array(
            'j2store_variant_id' => $item->variant_id
        ));

        //process pricing. returns an object
        $product_helper = J2Store::product();
        $product->pricing = $product_helper->getPrice($variant_table, $item->orderitem_quantity);

        $registry = new JRegistry();
        $registry->loadString($variant_table->params);
        $subscriptionproduct = $registry->get('subscriptionproduct',array());

        $period = isset($subscriptionproduct->subscription_period)? $subscriptionproduct->subscription_period: 'D';
        $period_units = isset($subscriptionproduct->subscription_period_units)? $subscriptionproduct->subscription_period_units: 1;
        $length = isset($subscriptionproduct->subscription_length)? $subscriptionproduct->subscription_length: 0;
        $recurringType = isset($subscriptionproduct->recurring_type)? $subscriptionproduct->recurring_type: 'multiple';
        $apply_same_coupon = isset($subscriptionproduct->apply_same_coupon)? $subscriptionproduct->apply_same_coupon: '';
        $subscription_free_trial = isset($subscriptionproduct->subscription_free_trial)? $subscriptionproduct->subscription_free_trial: 0;
        $subscription_trial_period = isset($subscriptionproduct->subscription_trial_period)? $subscriptionproduct->subscription_trial_period: 'D';
        $startDate = $this->getCurrentDate();
        //For changing start date for non recurring subscription if has active subscription
        if($recurringType == 'single'){
            $newStartDate = $this->getStartDateIfHasActiveSubscription($item->product_id, $item->variant_id, $order->user_id);
            if($newStartDate != ''){
                $startDate = $newStartDate;
            }
        }
        if($subscription_free_trial){
            $trialStartDate = $startDate;
            $trialEndDate = $this->createSubscriptionEndDate($startDate, $subscription_trial_period, $subscription_free_trial, $subscription_free_trial);
        } else {
            $trialStartDate = '0000-00-00 00:00:00';
            $trialEndDate = '0000-00-00 00:00:00';
        }
        if($subscription_free_trial){
            $next_payment_on = $trialEndDate;
        } else {
            if($length == 0) {
                $next_payment_on = $this->createSubscriptionEndDate($startDate, $period, $period_units, $period_units);
            } else {
                $next_payment_on = $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
            }
        }

        if($length == 0){
            $endDays = $this->getDurationInDays($period, $period_units);
        } else {
            $endDays = $this->getDurationInDays($period, ($length/$period_units)*$period_units);
        }
        $endDate = $this->createSubscriptionEndDate($startDate, 'D', $endDays, 1);
        if($subscription_free_trial){
            $endDate = $this->createSubscriptionEndDate($endDate, $subscription_trial_period, $subscription_free_trial, $subscription_free_trial);
        }

        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('orderitem_id' => $item->j2store_orderitem_id));
        if(!empty($subscription->j2store_subscription_id)){
            if($app->isAdmin()) {
                return $subscription->j2store_subscription_id;
            }
        }
        $subscription->orderitem_id = $item->j2store_orderitem_id;
        $subscription->order_id = $item->order_id;
        $subscription->product_id = $item->product_id;
        $subscription->variant_id = $item->variant_id;
        $subscription->start_on = $startDate;
        $subscription->trial_start_on = $trialStartDate;
        $subscription->trial_end_on = $trialEndDate;
        if($length == 0){
            $subscription->billing_cycle = 360000;
            $subscription->end_on = '0000-00-00 00:00:00';
        } else {
            if($subscription_free_trial){
                $subscription->billing_cycle = ($length/$period_units)+1;
            } else {
                $subscription->billing_cycle = $length/$period_units;
            }
            $subscription->end_on = $endDate;
        }
        $subscription->current_billing_cycle = 1;
        $subscription->subscription_length = $length;
        $subscription->next_payment_on = $next_payment_on;
        $subscription->payment_method = $order->orderpayment_type;
        $renewalAmount = $item->orderitem_price + $item->orderitem_option_price;
        $subscription->renewal_amount = $renewalAmount;
        $subscription->status = 'new';
        $subscription->user_id = $order->user_id;
        $subscription->period = $period;
        $subscription->period_units = $period_units;
        $subscription->store();
        $this->updateSubscriptionHistory($subscription->j2store_subscription_id, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_CREATED_SUBSCRIPTION'));

        //Create a copy of order for Subscription
        $this->createACopyOfOrderForSubscription($subscription, $item, $subscriptionproduct, $renewalAmount);

        return $subscription->j2store_subscription_id;
    }

    /**
     * Delete subscription
     * */
    function deleteSubscriptions($oid){
        $subscription = $this->getSubscriptionFromOrderItem($oid);
        $j2StorePlugin = J2Store::plugin();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)->delete('#__j2store_subscriptions')->where('orderitem_id = '.$db->q($oid));
        if(!empty($subscription)){
            $j2StorePlugin->event('BeforeDeleteSubscription', array($subscription));
        }
        try {
            $db->setQuery($query)->execute();
            if(!empty($subscription)){
                $j2StorePlugin->event('AfterDeleteSubscription', array($subscription));
            }
        }catch (Exception $e) {
            //do nothing. Because this is not harmful even if it fails.
        }
        return true;
    }

    /**
     * Generate order id
     *
     * @param $order_primary_key int
     * @param $order object
     * @return string
     * */
    protected function generateOrderId($order_primary_key, $order){
        $order_id = time().$order->j2store_order_id;
        J2Store::plugin()->event('BeforeOrderIdGeneration',array($order, $order_primary_key, &$order_id));
        return $order_id;
    }

    /**
     * create a copy of order for subscription
     * */
    protected function createACopyOfOrderForSubscription($subscription, $item, $subscriptionproduct, $renewalAmount){
        $app = JFactory::getApplication();
        $apply_same_coupon = isset($subscriptionproduct->apply_same_coupon)? $subscriptionproduct->apply_same_coupon: '';
        $config = J2Store::config();
        $j2StorePlugin = J2Store::plugin();

        // Copy Order for subscription
        $order = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
        $order->load(array('order_id' => $item->order_id));

        $orderSubscription = clone $order;
        $orderSubscription->resetSameOrder();
        $orderSubscription->j2store_order_id = 0;
        $orderSubscription->order_id = time();
        $orderSubscription->is_update = 1;
        $orderSubscription->order_fees = 0;
        $orderSubscription->parent_id = $order->j2store_order_id;
        $orderSubscription->subscription_id = $subscription->j2store_subscription_id;
        $orderSubscription->transaction_id = '';
        $orderSubscription->order_state_id = 5;
        $orderSubscription->created_on = $this->getCurrentDate();

        if($orderSubscription->store()){
            if($orderSubscription->is_update == 1) {
                $orderSubscription->is_update = 0;
                $orderSubscription->order_id = $this->generateOrderId($orderSubscription->j2store_order_id, $orderSubscription);//time().$orderSubscription->j2store_order_id;
            }
        }
        $orderSubscription->user_email = $order->user_email;
        $orderSubscription->store();

        // To save subscription order_id
        $subscription->subscription_order_id = $orderSubscription->order_id;
        $subscription->store();

        // Copy order info for subscription
        $orderInfo = F0FTable::getInstance('OrderInfo' ,'J2StoreTable')->getClone();
        $orderInfo->load(array('order_id' => $order->order_id));
        $newOrderInfo = clone $orderInfo;
        $newOrderInfo->j2store_orderinfo_id = 0;
        $newOrderInfo->order_id = $orderSubscription->order_id;
        $newOrderInfo->store();

        // Copy order item for subscription
        $orderItem = F0FTable::getInstance('OrderItem' ,'J2StoreTable')->getClone();
        $orderItem->load(array('j2store_orderitem_id' => $item->j2store_orderitem_id));
        $orderItem->orderitem_type = 'subscription';
        $orderItem->orderitem_discount = 0;
        $orderItem->orderitem_discount_tax = 0;
        $orderItemAttributes = $this->getSubscriptionOrderItemAttributes($item->j2store_orderitem_id);
        if(count($orderItemAttributes)){
            $orderItem->orderitemattributes = $orderItemAttributes;
        }

        $orderSubscription->resetOrderID($orderSubscription->order_id);
        $orderSubscription->order_type = 'subscription';

        // To handle coupon
        $coupon_model = F0FModel::getTmpInstance ( 'Coupons', 'J2StoreModel' );
        $hasCouponInOrder = $coupon_model->has_coupon();
        $resetCoupon = 0;
        $fixedDiscountValue = 0;
        if($hasCouponInOrder){
            $orderCouponCode = $coupon_model->get_coupon();
            if($apply_same_coupon == 1 && $item->orderitem_discount > 0){
                $coupon_model->init();
                if($coupon_model->coupon->value_type == 'fixed_cart'){
                    if($config->get ( 'config_including_tax', 0 )){
                        $coupon_model->coupon->value = $item->orderitem_discount+$item->orderitem_discount_tax;
                        $fixedDiscountValue = $item->orderitem_discount+$item->orderitem_discount_tax;
                    } else {
                        $coupon_model->coupon->value = $item->orderitem_discount;
                        $fixedDiscountValue = $item->orderitem_discount;
                    }
                }
                //echo "<pre>";print_r($coupon_model->coupon);echo "</pre>";
                // Nothing to do
            } else {
                $coupon_model->remove_coupon();
                $resetCoupon = 1;
//                $coupon_model->set_coupon();
            }
        }

        // To handle Voucher
        $voucher_model = F0FModel::getTmpInstance ( 'Vouchers', 'J2StoreModel' );
        $hasVoucherInOrder = $voucher_model->has_voucher();
        $resetVoucher = 0;
        if($hasVoucherInOrder){
            $orderVoucherCode = $voucher_model->get_voucher();
            $voucher_model->remove_voucher();
            $resetVoucher = 1;
        }

        $addShippingInSubscription = 1;
        $j2StorePlugin->event('ChangeShippingOptionBeforeCreateSubscriptionOrder', array($orderSubscription, $item, &$addShippingInSubscription));
        if($addShippingInSubscription == 0){
            $orderSubscription->order_shipping = 0;
            $orderSubscription->order_shipping_tax = 0;
        }

        $couponApp = array();
        $j2StorePlugin->event('HandleCouponBeforeCreateSubscriptionOrder', array($apply_same_coupon, &$couponApp));

        $orderSubscription->addItem($orderItem);

        if(!$app->isAdmin()){
            $orderSubscription->getTotals();
        }

        $orderSubscription->store();

        $orderSubscription->saveOrderItems ();

        $orderSubscription->saveOrderInfo ();

        if($addShippingInSubscription){
            $orderSubscription->saveOrderShipping ();
        }

        $orderSubscription->saveOrderFees ();

        $orderSubscription->saveOrderTax ();

        $orderSubscription->saveOrderDiscounts();

        $orderSubscription->saveOrderFiles ();

        $orderSubscription->store();

        if($app->isAdmin()) {
            $orderSubscriptionNew = F0FTable::getInstance('Order','J2StoreTable')->getClone();
            $orderSubscriptionNew->load($orderSubscription->j2store_order_id);
            $orderSubscriptionNew->resetSameOrder();
            $orderSubscriptionNew->resetOrderID($orderSubscription->order_id);
            $orderSubscriptionNew->getAdminTotals(true);
        }

        if($resetCoupon){
            $coupon_model->set_coupon($orderCouponCode);
        }
        if($resetVoucher){
            $voucher_model->set_voucher($orderVoucherCode);
        }

        $j2StorePlugin->event('HandleCouponAfterCreateSubscriptionOrder', array($apply_same_coupon, &$couponApp));

        /* Update Discount Table to recalculate discount for null discount_value_type */
        $this->updateDiscountTableForPromotionApps($orderSubscription, $item->orderitem_taxprofile_id, $fixedDiscountValue);
        unset($subscriptionproduct);
        unset($apply_same_coupon);
        unset($couponApp);
    }

    /**
     * Update Discount Table to recalculate discount for null discount_value_type / discount_value
     * */
    protected function updateDiscountTableForPromotionApps($orderSubscription, $tax_profile_id, $fixedDiscountValue){
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderdiscounts');
        $query->where('order_id = '.$db->q($orderSubscription->order_id));
        $db->setQuery($query);
        $orderDiscounts = $db->loadObjectList();
        if(count($orderDiscounts)){
            foreach ($orderDiscounts as $orderDiscount){
                if($orderDiscount->discount_value == '' || $orderDiscount->discount_value_type == ''){
                    $orderDiscountTable = F0FTable::getInstance('OrderDiscount' ,'J2StoreTable')->getClone();
                    $orderDiscountTable->load($orderDiscount->j2store_orderdiscount_id);
                    $orderDiscountTable->discount_value = $orderDiscount->discount_amount;
                    $orderDiscountTable->discount_value_type = 'fixed_cart';
                    $orderDiscountTable->store();
                }
                if($orderDiscount->discount_value_type == 'fixed_cart'){
                    if($orderDiscount->discount_value != ($orderDiscount->discount_amount+$orderDiscount->discount_tax)){
                        $config = J2Store::config();
                        $discountTax = 0;
                        $taxModel =  F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
                        if($app->isAdmin()){
                            $storeaddress = J2Store::storeProfile ();
                            $orderinfo = F0FTable::getInstance ( 'Orderinfo', 'J2StoreTable' )->getClone();
                            $orderinfo->load ( array( 'order_id' => $orderSubscription->order_id ) );
                            $taxModel->setShippingAddress($orderinfo->shipping_country_id, $orderinfo->shipping_zone_id, $orderinfo->shipping_zip);
                            $taxModel->setBillingAddress($orderinfo->billing_country_id, $orderinfo->billing_zone_id, $orderinfo->billing_zip);
                            $taxModel->setStoreAddress($storeaddress->get('country_id'), $storeaddress->get('zone_id'), $storeaddress->get('store_zip'));
                        }
                        $TaxRates = $taxModel->getTaxwithRates($fixedDiscountValue, $tax_profile_id, $config->get ( 'config_including_tax', 0 ));
                        if(isset($TaxRates->taxtotal)){
                            $discountTax = $TaxRates->taxtotal;
                        }
                        $orderDiscountTable = F0FTable::getInstance('OrderDiscount' ,'J2StoreTable')->getClone();
                        $orderDiscountTable->load($orderDiscount->j2store_orderdiscount_id);
                        $orderDiscountTable->discount_value = $orderDiscount->discount_amount+$orderDiscount->discount_tax;
                        $orderDiscountTable->discount_tax = $discountTax;
                        $orderDiscountTable->store();
                    }
                }
            }
        }
    }

    /**
     * Remove all subscription data
     * */
    public function removeSubscriptionDatas($subscription){
        $this->deleteQuery('j2store_metafields', array('owner_id' => $subscription->j2store_subscription_id, 'owner_resource' => 'subscriptions'));
        if(isset($subscription->subscription_order_id) && $subscription->subscription_order_id != ''){
            $order = F0FTable::getInstance('Order' ,'J2StoreTable')->getClone();
            $order->load(array('order_id' => $subscription->subscription_order_id));
            if(isset($order->j2store_order_id) && $order->j2store_order_id){
                $order->delete($order->j2store_order_id);
            }
        }
    }

    protected function deleteQuery($table, $where){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)->delete('#__'.$table);
        foreach ($where as $key => $value){
            $query->where($key.' = '.$db->q($value));
        }

        try {
            return $db->setQuery($query)->execute();
        }catch (Exception $e) {
            return false;
            //do nothing. Because this is not harmful even if it fails.
        }
    }

    /**
     * To update subscription Order meta
     * */
    public function updateSubscriptionOrderMeta($subscription_id, $key, $value, $scope){
        $subscriptionMeta = SubscriptionMeta::getInstance();
        $subscriptionMeta->updateSubscriptionMeta($subscription_id, $key, $value, 'subscription_order', $scope, 'json');
    }

    /**
     * To add subscription Order meta
     * */
    public function addSubscriptionOrderMeta($subscription_id, $key, $value, $scope){
        $subscriptionMeta = SubscriptionMeta::getInstance();
        $subscriptionMeta->addSubscriptionMeta($subscription_id, $key, $value, 'subscription_order', $scope, 'json');
    }

    /**
     * calculate Renewal amount
     * */
    public function calculateRenewalAmount($vars, $productAmount){
        $config = J2Store::config();
        $renewalAmount = $productAmount;
        $productTax = $discount = $discountTax = $shippingCost = 0;
        $product = $vars->product;
        $subscriptionproducts = $vars->subscriptionproduct;
        $apply_same_coupon = isset($subscriptionproducts->apply_same_coupon)? $subscriptionproducts->apply_same_coupon: '';

        $taxModel =  F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
        $TaxRates = $taxModel->getTaxwithRates($productAmount, $product->taxprofile_id, $config->get ( 'config_including_tax', 0 ));
        if(isset($TaxRates->taxtotal)){
            $productTax = $TaxRates->taxtotal;
        }

        if(!$config->get ( 'config_including_tax', 0 )) {
            $renewalAmount += $productTax;
        }
        if($apply_same_coupon == 1){
            $order = F0FModel::getTmpInstance('Orders', 'J2StoreModel')->initOrder()->getOrder();
            $orderItems = $order->getItems();
            F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);
            $variant_id = $product->variant->j2store_variant_id;
            foreach($orderItems as $orderItem){
                if($variant_id == $orderItem->variant_id){
                    $discount = $orderItem->orderitem_discount;
                    $discountTax = $orderItem->orderitem_discount_tax;
                    $shippingCost = $order->order_shipping+$order->order_shipping_tax;
                    $renewalAmount = $orderItem->orderitem_finalprice;
                    break;
                }
            }
            if($discount > 0){
                $discountBeforeTax = $discount;
                $discount = $discount+$discountTax;
                $renewalAmount -= $discount;
                if(!$config->get('checkout_price_display_options', 1)){
                    $discount = $discountBeforeTax;
                }
            }
        } else {
            $discount = $this->getRenewalDiscountAmount($renewalAmount);
            if($discount > 0){
                $renewalAmount -= $discount;
                $discountTaxRates = $taxModel->getTaxwithRates($discount, $product->taxprofile_id, $config->get ( 'config_including_tax', 0 ));
                if(isset($discountTaxRates->taxtotal)){
                    $discountTax = $discountTaxRates->taxtotal;
                    if($config->get('checkout_price_display_options', 1)){
                        $discount = $discount + $discountTax;
                    }
                }
            }
        }
        // Add shipping cost
        $renewalAmount = $renewalAmount+$shippingCost;
        $returnData['renewal_amount'] = $renewalAmount;
        $returnData['renewal_discount'] = $discount;
        return $returnData;
    }

    /**
     * get Renewal discount
     * */
    public function getRenewalDiscountText($discountPrice = 'global', $subscription){
        $config = J2Store::config();
        $discountText = '';
        if(!empty($subscription)){

        } else {
            if($discountPrice !== 'global'){
                if($discountPrice > 0){
                    $discountText = '('.JText::_('J2STORE_SUBSCRIPTIONAPP_RENEWAL_SETTINGS_RENEWAL_DISCOUNT_PRICE').': '.J2Store::currency()->format('-'.$discountPrice).')';
                }
            } else {
                $params = $this->getpluginParams();
                $renewalDiscount = (int)$params->get('renewal_discount_percent', 0);
                if($renewalDiscount > 0 && $renewalDiscount < 100){
                    $discountText = '('.JText::_('J2STORE_SUBSCRIPTIONAPP_RENEWAL_SETTINGS_RENEWAL_DISCOUNT_PERCENT').': '.$renewalDiscount.'%)';
                }
            }
        }

        return $discountText;
    }

    /**
     * check has Renewal discount
     * */
    public function hasRenewDiscount($apply_same_coupon, $item){
        $config = J2Store::config();
        if($apply_same_coupon == 1){
            $discount = $item->orderitem_discount;
            if($discount)
                $discountTax = $item->orderitem_discount_tax;
            if($config->get ( 'config_including_tax', 0 )) {
                $discount = ($discount+$discountTax);
            }
//            if($discount > 0){
//                $discount = $discount+$discountTax;
//            }
            return $discount;
        } else {
            $params = $this->getpluginParams();
            $renewalDiscount = (int)$params->get('renewal_discount_percent', 0);
            if($renewalDiscount > 0 && $renewalDiscount < 100){
                return $renewalDiscount;
            }
            return false;
        }
    }

    /**
     * calculate Renewal discount for subscription
     * */
    public function getRenewalDiscountForSubscription($subscription, $renewalAmount){
        $discounts = $this->getSubscriptionRenewCoupons($subscription->j2store_subscription_id);
        $discountAmount = 0;
        if(count($discounts)){
            foreach ($discounts as $key => $discount){
                $values = $discount->metavalue;
                $discountObj = json_decode($values);
                if(is_object($discountObj)){
                    if($discountObj->discount_value_type == 'percentage_product'){
                        $discountAmount += round((($discountObj->discount_value / 100) * $renewalAmount), 2);
                    } else {
                        if($discountObj->discount_value < ($renewalAmount-$discountAmount)){
                            $discountAmount += $discountObj->discount_value;
                        }
                    }
                }
            }
        }

        return $discountAmount;
    }

    /**
     * get Renewal discount amount
     * */
    public function getRenewalDiscountAmount($renewalAmount){
        $discount = 0;
        $params = $this->getpluginParams();
        $renewalDiscount = (int)$params->get('renewal_discount_percent', 0);
        if($renewalDiscount > 0 && $renewalDiscount < 100){
            $discount = round((($renewalDiscount / 100) * $renewalAmount), 2);
        }
        return $discount;
    }

    /**
     * Can Update card
     * */
    public function showLast4Digits($subscription){
        // $subscription->j2store_subscription_id = 30;
        $subscription->meta = $this->getAllSubscriptionMetaData($subscription->j2store_subscription_id);

        // var_dump($subscription->meta['stripe_customer_id']['metavalue']);
        if(isset($subscription->meta['stripe_customer_id']['metavalue'])){
            echo "*****"; 
            $customer_id = $subscription->meta['stripe_customer_id']['metavalue'];
            J2Store::plugin()->event('GetLastCardNumDigits',array($customer_id));
        }
        
    }    
    /**
     * Can Update card
     * */
    public function isDisplayCardUpdate($subscription){
        $display = false;
        if(!empty($subscription) && isset($subscription->j2store_subscription_id) && !empty($subscription->j2store_subscription_id)){
            if(in_array($subscription->status, array('active'))){
                $params = $this->getpluginParams();
                $display = (int)$params->get('allow_card_update', 0);
            }
        }

        return $display;
    }

    /**
     * has trial support
     * */
    public function hasTrialSupport($payment_method){
        $app = JFactory::getApplication();
        $support_trial = false;
        $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionCardUpdate", array($payment_method) );
        if (in_array(true, $results)) {
            $support_trial = true;
        }

        return $support_trial;
    }

    /**
     * get renewal Amount
     * */
    public function getRenewalAmount($subscription, $renewalAmount = 0){
        $discountAmount = 0;
        $config = J2Store::config();
        $orderSubscription = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
        $orderSubscription->load(array('order_id' => $subscription->subscription_order_id));
        if(isset($orderSubscription->j2store_order_id) && $orderSubscription->j2store_order_id){
            $renewalAmount = $orderSubscription->order_total;
            $discountAmount = $orderSubscription->order_discount;

            if($config->get('checkout_price_display_options', 1)) {
                $discountAmount = $orderSubscription->order_discount + $orderSubscription->order_discount_tax;
            }
        }

        $returnData['renewal_amount'] = $renewalAmount;
        $returnData['renewal_discount'] = $discountAmount;
        return $returnData;
    }

    /**
     * get renewal tax Amount
     * */
    public function getRenewalTaxAmount($subscription, $renewal_amount){
        $taxrates = $this->getRenewalTaxDetails($subscription, $renewal_amount);
        $tax_amount = 0;
        if(isset($taxrates->taxtotal)){
            $tax_amount = $taxrates->taxtotal;
        }
        return $tax_amount;
    }

    /**
     * get renewal tax Amount
     * */
    public function getRenewalTaxDetails($subscription, $renewal_amount){
        $config = J2Store::config();
        $storeaddress = J2Store::storeProfile ();
        $orderItem = F0FTable::getInstance('OrderItem' ,'J2StoreTable')->getClone();
        $orderItem->load(array('j2store_orderitem_id' => $subscription->orderitem_id));
        $orderinfo = F0FTable::getInstance ( 'Orderinfo', 'J2StoreTable' )->getClone();
        $orderinfo->load ( array( 'order_id' => $orderItem->order_id ) );
        $taxModel =  F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
        $taxModel->setShippingAddress($orderinfo->shipping_country_id, $orderinfo->shipping_zone_id, $orderinfo->shipping_zip);
        $taxModel->setBillingAddress($orderinfo->billing_country_id, $orderinfo->billing_zone_id, $orderinfo->billing_zip);
        $taxModel->setStoreAddress($storeaddress->get('country_id'), $storeaddress->get('zone_id'), $storeaddress->get('store_zip'));
        $taxrates = $taxModel->getTaxwithRates($renewal_amount, $orderItem->orderitem_taxprofile_id, $config->get ( 'config_including_tax', 0 ));

        return $taxrates;
    }

    /**
     * add Subscription History
     * */
    function updateSubscriptionHistory($subsriptionId, $status, $comment, $notify_customer = 0, $scope = ''){
        $this->addSubscriptionHistoryMeta($subsriptionId, $comment, $status, $notify_customer, $scope);
    }

    /**
     * Changed subscription status
     * */
    function changeSubscriptionStatus($subsriptionId, $status, $notify_customer = true){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subsriptionId));
        if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
            if($subscription->status != $status){
                if($status == 'active'){
                    $now = strtotime($this->getCurrentDate());
                    $startTime = strtotime($subscription->start_on);
                    if($startTime > $now){
                        $status = 'future';
                    } else if($subscription->trial_start_on != '0000-00-00 00:00:00' && $subscription->trial_end_on != '0000-00-00 00:00:00'){
                        $trialStartTime = strtotime($subscription->trial_start_on);
                        $trialEndTime = strtotime($subscription->trial_end_on);
                        if($trialStartTime <= $now && $trialEndTime >= $now){
                            $status = 'in_trial';
                        }
                    }
                }
                $subscription->status = $status;
                if($subscription->store()){
                    $this->refreshUserGroups($subscription->user_id);
                    $this->updateSubscriptionHistory($subscription->j2store_subscription_id, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_CHANGED_SUBSCRIPTION_STATUS'));
                }
                if($notify_customer){
                    $this->sendEmailOnStatusChange($subsriptionId, $status);
                }

                //Event after changing the subscription status
                $j2StorePlugin = J2Store::plugin();
                $j2StorePlugin->event('SubscriptionStatusChanged', array($subsriptionId, $status));
            }
        }
    }

    /**
     * Refresh User groups
     * */
    public function refreshUserGroups($user_id){
        $addGroups = array(); $removeGroups = array();
        $this->loadUserGroups($user_id, $addGroups, $removeGroups);
        if (empty($addGroups) && empty($removeGroups)) {
            return;
        }

        // Get DB connection
        $db = JFactory::getDBO();

        // Add to Joomla! groups
        if (!empty($addGroups)) {
            // 1. Delete existing assignments
            $groupSet = array();

            foreach ($addGroups as $group)
            {
                $groupSet[] = $db->q($group);
            }

            $query = $db->getQuery(true)
                ->delete($db->qn('#__user_usergroup_map'))
                ->where($db->qn('user_id') . ' = ' . $user_id)
                ->where($db->qn('group_id') . ' IN (' . implode(', ', $groupSet) . ')');

            $db->setQuery($query);
            $db->execute();

            // 2. Add new assignments
            $query = $db->getQuery(true)
                ->insert($db->qn('#__user_usergroup_map'))
                ->columns(array(
                    $db->qn('user_id'),
                    $db->qn('group_id'),
                ));

            foreach ($addGroups as $group)
            {
                $query->values($db->q($user_id) . ', ' . $db->q($group));
            }

            $db->setQuery($query);
            $db->execute();
        }

        // Remove from Joomla! groups
        if (!empty($removeGroups)) {
            $query    = $db->getQuery(true)
                ->delete($db->qn('#__user_usergroup_map'))
                ->where($db->qn('user_id') . ' = ' . $db->q($user_id));

            $groupSet = array();

            foreach ($removeGroups as $group)
            {
                $groupSet[] = $db->q($group);
            }

            $query->where($db->qn('group_id') . ' IN (' . implode(', ', $groupSet) . ')');
            $db->setQuery($query);
            $db->execute();
        }
        J2Store::plugin()->event('AfterRefreshUserGroup',array($user_id));
    }

    /**
     * Load the groups to add / remove for a user
     *
     * @param   int     $user_id              The Joomla! user ID
     * @param   array   $addGroups            Array of groups to add (output)
     * @param   array   $removeGroups         Array of groups to remove (output)
     * @param   string  $addGroupsVarName     Property name of the map of the groups to add
     * @param   string  $removeGroupsVarName  Property name of the map of the groups to remove
     *
     * @return  void  We modify the $addGroups and $removeGroups arrays directly
     */
    protected function loadUserGroups($user_id, array &$addGroups, array &$removeGroups, $addGroupsVarName = 'addGroups', $removeGroupsVarName = 'removeGroups')
    {
        $this->loadGroupAssignments();

        // Make sure we're configured
        if (empty($this->$addGroupsVarName) && empty($this->$removeGroupsVarName)) {
            return;
        }

        // Get all of the user's subscriptions
        $subscriptions = $this->getAllSubscriptionByUser($user_id);

        // Make sure there are subscriptions set for the user
        if (!count($subscriptions)) {
            return;
        }

        // Get the initial list of groups to add/remove from
        /** @var Subscriptions $sub */
        foreach ($subscriptions as $sub)  {
            $product_id = $sub->variant_id;

            if (in_array($sub->status, array('active', 'in_trial', 'canceled')))
            {
                // Enabled subscription, add groups
                if (empty($this->$addGroupsVarName))
                {
                    continue;
                }

                if (!array_key_exists($product_id, $this->$addGroupsVarName))
                {
                    continue;
                }

                $addGroupsVar = $this->$addGroupsVarName;
                $groups       = $addGroupsVar[ $product_id ];

                foreach ($groups as $group)
                {
                    if (!in_array($group, $addGroups))
                    {
                        if (is_numeric($group) && !($group > 0))
                        {
                            continue;
                        }

                        $addGroups[] = $group;
                    }
                }
            }
            else
            {
                // Disabled subscription, remove groups
                if (empty($this->$removeGroupsVarName))
                {
                    continue;
                }

                if (!array_key_exists($product_id, $this->$removeGroupsVarName))
                {
                    continue;
                }

                $removeGroupsVar = $this->$removeGroupsVarName;
                $groups          = $removeGroupsVar[ $product_id ];

                foreach ($groups as $group)
                {
                    if (!in_array($group, $removeGroups))
                    {
                        if (is_numeric($group) && !($group > 0))
                        {
                            continue;
                        }

                        $removeGroups[] = $group;
                    }
                }
            }
        }

        // If no groups are detected, do nothing
        if (empty($addGroups) && empty($removeGroups))
        {
            return;
        }

        // Sort the lists
        asort($addGroups);
        asort($removeGroups);

        // Clean up the remove groups: if we are asked to both add and remove a user
        // from a group, add wins.
        if (!empty($removeGroups) && !empty($addGroups))
        {
            $temp         = $removeGroups;
            $removeGroups = array();

            foreach ($temp as $group)
            {
                if (!in_array($group, $addGroups))
                {
                    $removeGroups[] = $group;
                }
            }
        }
    }

    /**
     * Get Subscription products
     * */
    protected function getSubscriptionProducts(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_products');
        $query->where('product_type IN ('.$db->q('subscriptionproduct').','.$db->q('variablesubscriptionproduct').')');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Get Subscription Variants
     * */
    protected function getSubscriptionVariants(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('v.*')->from('#__j2store_variants as v');
        $query->join('LEFT', '#__j2store_products as p on p.j2store_product_id = v.product_id');
        $query->where('p.product_type IN ('.$db->q('subscriptionproduct').','.$db->q('variablesubscriptionproduct').')');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Get all Subscription by user
     * */
    protected function getAllSubscriptionByUser($user){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('user_id = '.$db->q($user));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Get all Subscription by Order ID
     * */
    public function getAllSubscriptionByOrderID($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('s.*')->from('#__j2store_subscriptions as s');
        $query->select('oi.orderitem_name');
        $query->join('LEFT', '#__j2store_ordersubscriptions as os ON os.orderitem_id = s.orderitem_id');
        $query->join('LEFT', '#__j2store_orderitems as oi ON oi.j2store_orderitem_id = os.orderitem_id');
        $inner_query = '(SELECT subscription_id from #__j2store_ordersubscriptions where order_id = '.$db->q($order_id).')';
        $query->where('s.j2store_subscription_id IN '.$inner_query);

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Load the add / remove group to plan ID map from the subscription plan options
     *
     * @return  void
     */
    protected function loadGroupAssignments()
    {
        $this->addGroups    = array();
        $this->removeGroups = array();
//        $products = $this->getSubscriptionProducts();
        $products = $this->getSubscriptionVariants();
        if (count($products)) {
            foreach ($products as $product) {
                $registry = new JRegistry();
                $registry->loadString($product->params);
                $subscriptionproduct = $registry->get('subscriptionproduct',array());
                if (isset($subscriptionproduct->add_user_groups)) {
                    $content = $subscriptionproduct->add_user_groups;

                    if (is_array($content)) {
                        $content = array_filter($content);
                    }

                    $this->addGroups[ $product->j2store_variant_id ] = $content;
                }

                if (isset($subscriptionproduct->remove_user_groups)) {
                    $content = $subscriptionproduct->remove_user_groups;

                    if (is_array($content)) {
                        $content = array_filter($content);
                    }

                    $this->removeGroups[ $product->j2store_variant_id ] = $content;
                }
            }
        }
    }

    /**
     * Load mail template
     * */
    protected function mailTemplates($value, $vars = array(), $type = 'status'){
        $result['subject'] = '';
        $result['message'] = '';
        switch ($type){
            case 'status':
                if($value == 'active'){ //active
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_STATUS_CHANGED_TO_ACTIVE');
                    $result['message'] = $this->_getMailLayout('active', $vars);
                } else if($value == 'expired'){ //expired
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_STATUS_CHANGED_TO_EXPIRED');
                    $result['message'] = $this->_getMailLayout('expired', $vars);
                } else if($value == 'failed'){ //failed
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_STATUS_CHANGED_TO_FAILED');
                    $result['message'] = $this->_getMailLayout('failed', $vars);
                } else if($value == 'canceled'){ //canceled
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_STATUS_CHANGED_TO_CANCELED');
                    $result['message'] = $this->_getMailLayout('canceled', $vars);
                } else if($value == 'in_trial'){ //in_trial
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_STATUS_CHANGED_TO_IN_TRIAL');
                    $result['message'] = $this->_getMailLayout('in_trial', $vars);
                } else if($value == 'card_expired'){ //card_expired
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NOTIFY_ABOUT_CARD_EXPIRE');
                    $result['message'] = $this->_getMailLayout('notify_card_expire', $vars);
                }
                break;
            case 'notify':
                if($value == 'expire'){ //About expiring
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_EXPIRE_NOTIFY');
                    $result['message'] = $this->_getMailLayout('notify_expire', $vars);
                } else if($value == 'next_renewal'){ // next renewal
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NEXT_RENEWAL_NOTIFY');
                    $result['message'] = $this->_getMailLayout('notify_next_renewal', $vars);
                } else if($value == 'trial_to_renewal'){ // first renewal from trial
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_FIRST_RENEWAL_FROM_TRIAL_NOTIFY');
                    $result['message'] = $this->_getMailLayout('notify_trial_to_renewal', $vars);
                } else if($value == 'card_expire'){ // card_expire
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NOTIFY_ABOUT_CARD_EXPIRE');
                    $result['message'] = $this->_getMailLayout('notify_card_expire', $vars);
                } else if($value == 'on_success_renewal'){ // renewal
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NOTIFY_ABOUT_RENEWAL');
                    $result['message'] = $this->_getMailLayout('notify_on_success_renewal', $vars);
                } else if($value == 'card_updated'){ // renewal
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NOTIFY_ABOUT_CARD_UPDATED');
                    $result['message'] = $this->_getMailLayout('notify_card_updated', $vars);
                } else if($value == 'about_renewal_retry'){ // renewal
                    $result['subject'] = JText::_('COM_J2STORE_SUBSCRIPTION_MAIL_SUBJECT_NOTIFY_ABOUT_RENEWAL_RETRY');
                    $result['message'] = $this->_getMailLayout('notify_about_renewal_retry', $vars);
                }
        }
        $product_name = '';
        if(isset($vars->product)){
            if(isset($vars->product->product_name) && !empty($vars->product->product_name)){
                $product_name = $vars->product->product_name;
            }
        }
        if(isset($result['subject']) && !empty($result['subject'])){
            $result['subject'] = str_replace("[SUBSCRIPTION_PRODUCT_NAME]", $product_name, $result['subject']);
        }

        return $result;
    }

    /**
     * send mail on status change
     * */
    public function sendEmailOnStatusChange($subscriptionID, $status){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
        // $subscription->status instead of $status ==> While changing the status to active if the period was in trial then it automatically change to trail instead of active
        $statusObj = $subsStatusObj->getStatus($subscription->status);
        if(isset($statusObj->notify_customer) && $statusObj->notify_customer){
            $vars = new stdClass();
            $userDetails = JFactory::getUser($subscription->user_id);
            $vars->subscription = $subscription;
            $vars->user = $userDetails;
            $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
            $vars->orderInfo->load(array('order_id' => $subscription->order_id));
            $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
            $mailTemplate = $this->mailTemplates($subscription->status, $vars);
            if($mailTemplate['subject'] != ''){
                $template = new stdClass();
                $template->recipients = array($userDetails->get('email'));
                $template->subject = $mailTemplate['subject'];
                $template->body = $mailTemplate['message'];
                $result = $this->sendEmails($template);
                if($result){
                    $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_STATUS_UPDATE'));
                }
            }
        }
    }

    /**
     * send mail on status update
     * */
    public function sendMailOnStatusChangedToCardExpired($subscriptionID, $renewalDateInTime){
        $notified_key = 'notify_card_expire_'.$renewalDateInTime;
        $notified = $this->getSubscriptionMetaValue($subscriptionID, $notified_key, 0);
        if($notified){
            return ;
        }
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $mailTemplate = $this->mailTemplates('card_expire', $vars, 'notify');
        if($mailTemplate['subject'] != ''){
            $template = new stdClass();
            $template->recipients = array($userDetails->get('email'));
            $template->subject = $mailTemplate['subject'];
            $template->body = $mailTemplate['message'];

            $params = J2Store::config();
            $admin_emails = $params->get ( 'admin_email' );
            $admin_emails = explode ( ',', $admin_emails );
            if(!empty($admin_emails)){
                $template->bcc = $admin_emails;
            }
            $result = $this->sendEmails($template);
            if($result){
                $this->addSubscriptionMeta($subscriptionID, $notified_key, 1);
                $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_CARD_EXPIRE'));
            }
        }
    }

    /**
     * send mail on status update
     * */
    public function sendMailAboutRenewalRetry($subscriptionID, $retryDate, $renewal_retry_count, $renewal_retry_period, $renewal_retry_period_units){
        $notified_key = 'notify_renewal_retry_'.strtotime($retryDate);
        $notified = $this->getSubscriptionMetaValue($subscriptionID, $notified_key, 0);
        if($notified){
            return ;
        }
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $vars->renewal_retry_count = $renewal_retry_count;
        $vars->renewal_retry_period = $renewal_retry_period;
        $vars->renewal_retry_period_units = $renewal_retry_period_units;
        $vars->renewal_retry_on = $retryDate;
        $mailTemplate = $this->mailTemplates('about_renewal_retry', $vars, 'notify');
        if($mailTemplate['subject'] != ''){
            $template = new stdClass();
            $template->recipients = array($userDetails->get('email'));
            $template->subject = $mailTemplate['subject'];
            $template->body = $mailTemplate['message'];

            $params = J2Store::config();
            $admin_emails = $params->get ( 'admin_email' );
            $admin_emails = explode ( ',', $admin_emails );
            if(!empty($admin_emails)){
                $template->bcc = $admin_emails;
            }
            $result = $this->sendEmails($template);
            if($result){
                $this->addSubscriptionMeta($subscriptionID, $notified_key, 1);
                $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_RENEWAL_RETRY'));
            }
        }
    }

    /**
     * send mail on renewal payment completed
     * */
    public function sendMailOnAfterSuccessRenewalPayment($subscriptionID){
        if(!empty($subscriptionID)){
            $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
            $subscription->load(array('j2store_subscription_id' => $subscriptionID));
            $vars = new stdClass();
            $userDetails = JFactory::getUser($subscription->user_id);
            $vars->subscription = $subscription;
            $vars->user = $userDetails;
            $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
            $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
            $vars->orderInfo->load(array('order_id' => $subscription->order_id));
            $mailTemplate = $this->mailTemplates('on_success_renewal', $vars, 'notify');
            if($mailTemplate['subject'] != ''){
                $template = new stdClass();
                $template->recipients = array($userDetails->get('email'));
                $template->subject = $mailTemplate['subject'];
                $template->body = $mailTemplate['message'];

                $result = $this->sendEmails($template);
                if($result){
                    $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_RENEWAL'));
                }
            }
        }
    }

    /**
     * send mail on after card Updated
     * */
    public function sendMailOnAfterCardUpdated($subscriptionID){
        if(!empty($subscriptionID)){
            $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
            $subscription->load(array('j2store_subscription_id' => $subscriptionID));
            $vars = new stdClass();
            $userDetails = JFactory::getUser($subscription->user_id);
            $vars->subscription = $subscription;
            $vars->user = $userDetails;
            $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
            $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
            $vars->orderInfo->load(array('order_id' => $subscription->order_id));
            $mailTemplate = $this->mailTemplates('card_updated', $vars, 'notify');
            if($mailTemplate['subject'] != ''){
                $template = new stdClass();
                $template->recipients = array($userDetails->get('email'));
                $template->subject = $mailTemplate['subject'];
                $template->body = $mailTemplate['message'];

                $result = $this->sendEmails($template);
                if($result){
                    $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_CARD_UPDATED'));
                }
            }
        }
    }

    /**
     * send mail on status change
     * */
    public function sendMailToCustomerNotifyExpire($subscriptionID, $dayBefore){
        $notified_key = 'notify_expire_before_'.$dayBefore;
        $notified = $this->getSubscriptionMetaValue($subscriptionID, $notified_key, 0);
        if($notified){
            return ;
        }
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $mailTemplate = $this->mailTemplates('expire', $vars, 'notify');
        if($mailTemplate['subject'] != ''){
            $template = new stdClass();
            $template->recipients = array($userDetails->get('email'));
            $template->subject = $mailTemplate['subject'];
            $template->body = $mailTemplate['message'];
            $result = $this->sendEmails($template);
            if($result){
                $this->addSubscriptionMeta($subscriptionID, $notified_key, 1);
                $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_EXPIRE'));
            }
        }
    }

    /**
     * send mail to notify next renewal
     * */
    public function sendMailToCustomerNotifyNextRenewal($subscriptionID, $dayBefore){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $next_payment_on_time = strtotime($subscription->next_payment_on);
        $notified_key = 'notify_renewal_before_'.$next_payment_on_time.'_'.$dayBefore;
        $notified = $this->getSubscriptionMetaValue($subscriptionID, $notified_key, 0);
        if($notified){
            return ;
        }
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $mailTemplate = $this->mailTemplates('next_renewal', $vars, 'notify');
        if($mailTemplate['subject'] != ''){
            $template = new stdClass();
            $template->recipients = array($userDetails->get('email'));
            $template->subject = $mailTemplate['subject'];
            $template->body = $mailTemplate['message'];
            $result = $this->sendEmails($template);
            if($result){
                $this->addSubscriptionMeta($subscriptionID, $notified_key, 1);
                $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_NEXT_RENEWAL'));
            }
        }
    }

    /**
     * send mail to notify first auto renewal from trial
     * */
    public function sendMailToCustomerNotifyFirstRenewalFromTrial($subscriptionID, $dayBefore){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscriptionID));
        $next_payment_on_time = strtotime($subscription->next_payment_on);
        $notified_key = 'notify_trial_renewal_before_'.$next_payment_on_time.'_'.$dayBefore;
        $notified = $this->getSubscriptionMetaValue($subscriptionID, $notified_key, 0);
        if($notified){
            return ;
        }
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $mailTemplate = $this->mailTemplates('trial_to_renewal', $vars, 'notify');
        if($mailTemplate['subject'] != ''){
            $template = new stdClass();
            $template->recipients = array($userDetails->get('email'));
            $template->subject = $mailTemplate['subject'];
            $template->body = $mailTemplate['message'];
            $result = $this->sendEmails($template);
            if($result){
                $this->addSubscriptionMeta($subscriptionID, $notified_key, 1);
                $this->updateSubscriptionHistory($subscriptionID, $subscription->status, JText::_('J2STORE_SUBSCRIPTION_HISTORY_NOTIFIED_CUSTOMER_ABOUT_FIRST_RENEWAL_FROM_TRIAL'));
            }
        }
    }

    /**
     * Method to get the mailer object
     * */
    function getMailer(){
        // get the type of mailer configured from component settings
        $mailer_name = 'Joomla' ;

        if ( $mailer_name == 'Joomla' ){
            $mailer = clone JFactory::getMailer();
            $isHTML = true;
            $mailer->IsHTML($isHTML);
            // Required in order not to get broken characters
            $mailer->CharSet = 'UTF-8';
        }
        //implement more mailers like swiftmailer in future

        return $mailer;
    }

    /**
     * Method to send the emails for the supplied event and objects
     * */
    function sendEmails($template ){

        $for_admin = isset($template->for_admin)? $template->for_admin: 0;

        // first get the list of email templates
        $config = JFactory::getConfig();
        // 1 - get the mailer
        $mailer = $this->getMailer();

        // 2 - intialize the mailer with proper sender information
        if(version_compare(JVERSION, '3.0', 'ge')) {
            $mailfrom = $config->get('mailfrom');
            $fromname = $config->get('fromname');
        } else {
            $mailfrom = $config->getValue('config.mailfrom');
            $fromname = $config->getValue('config.fromname');
        }
        $mailer->setSender(array( $mailfrom, $fromname ));

        // 3- set encoding and other information
        $mailer->CharSet = 'UTF-8';

        // 4 - set subject, body
        $mailer->setSubject( $template->subject );

        $lang = JFactory::getLanguage();
        $htmlExtra = '';
        if($lang->isRTL()) {
            $htmlExtra = ' dir="rtl"';
        }
        $body = '<html'.$htmlExtra.'><head>'.
            '<meta http-equiv="Content-Type" content="text/html; charset='.$mailer->CharSet.'">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						</head>'.'<body>'.$template->body.'</body></html>';
        $mailer->setBody($body);

        // 5 - set the recipients
        $send_flag = false;
        if ( count($template->recipients) > 0 ) {
            $mailer->addRecipient( $template->recipients );
            $send_flag = true;
        }

        if (isset($template->cc) && count($template->cc) > 0 ) {
            $mailer->addCC( $template->cc );
            $send_flag = true;
        }

        if(!$for_admin){
            $params = $this->getpluginParams();
            $bcc_with_customer_emails = $params->get('bcc_with_customer_emails', '');
            if(!empty($bcc_with_customer_emails)){
                $bcc_with_customer_emails = explode ( ',', $bcc_with_customer_emails );
                if (isset($template->bcc) && count($template->bcc) > 0 ) {
                    $template->bcc = array_merge($template->bcc, $bcc_with_customer_emails);
                } else {
                    $template->bcc = $bcc_with_customer_emails;
                }
            }
        }

        if (isset($template->bcc) && count($template->bcc) > 0 ) {
            $mailer->addBCC( $template->bcc );
            $send_flag = true;
        }

        // 6 - send the mail
        if ( $send_flag ) {
            return $mailer->Send();
        }
    }

    /**
     * Get mail layout path
     * */
    function _getMailLayout($layout, $vars = false, $plugin = 'app_subscriptionproduct', $group = 'j2store' )
    {
        ob_start();
        $layout = $this->_getMailLayoutPath($plugin, $group, $layout);
        include($layout);
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Get mail template path
     * */
    function _getMailLayoutPath($plugin, $group, $layout = 'default')
    {
        $templateName = $this->getFrontendDefaultTemplate();
        // get the template and default paths for the layout
        $templatePath = JPATH_SITE.'/templates/'.$templateName.'/html/plugins/'.$group.'/'.$plugin.'/mailtemplates/'.$layout.'.php';
        $defaultPath = JPATH_SITE.'/plugins/'.$group.'/'.$plugin.'/'.$plugin.'/mailtemplates/'.$layout.'.php';

        // if the site template has a layout override, use it
        jimport('joomla.filesystem.file');
        if (JFile::exists( $templatePath ))
        {
            return $templatePath;
        }
        else
        {
            return $defaultPath;
        }
    }

    /**
     * Load Frontend default template
     * */
    protected function getFrontendDefaultTemplate(){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('template');
        $query->from('#__template_styles');
        $query->where('client_id = '.$db->quote(0));
        $query->where('home = '.$db->quote(1));
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * To update subscription renew coupon
     * */
    public function addSubscriptionRenewCoupon($subscription_id, $key, $value){
        $data = $this->getSubscriptionRenewCoupon($subscription_id, $key);
        $metaData = new stdClass();
        $metaData->metavalue = $value;
        if($data){
            $metaData->id = $data->id;
            $metaData->updated_at = $this->getCurrentDate();
            $result = JFactory::getDbo()->updateObject('#__j2store_metafields', $metaData, 'id');
        } else {
            $metaData->owner_id = $subscription_id;
            $metaData->metakey = $key;
            $metaData->namespace = 'subscription_coupon';
            $metaData->scope = 'subscription_renew_coupon';
            $metaData->owner_resource = 'subscriptions';
            $metaData->created_at = $this->getCurrentDate();
            $result = JFactory::getDbo()->insertObject('#__j2store_metafields', $metaData);
        }
        return $result;
    }

    /**
     * Get subscriptions renew coupon
     * */
    protected function getSubscriptionRenewCoupon($subscription_id, $key, $default = ''){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote('subscription_coupon'));
        $query->where('scope = '.$db->quote('subscription_renew_coupon'));

        $db->setQuery($query);
        $result = $db->loadObject();
        if(empty($result)){
            return $default;
        }
        return $result;
    }

    /**
     * Get subscriptions renew coupons
     * */
    public function getSubscriptionRenewCoupons($id, $scopes = array(), $frontend = 0){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($id));
        $query->where('namespace = '.$db->quote('subscription_coupon'));
        $query->where('owner_resource = '.$db->quote('subscriptions'));

        if(!empty($scopes)){
            foreach ($scopes as $scope){
                $query->where('scope = '.$db->quote($scope));
            }
        }
        $query->order('created_at');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * To update subscription meta
     * */
    public function addSubscriptionMeta($subscription_id, $key, $value){
        $data = $this->getSubscriptionMetaData($subscription_id, $key);
        $metaData = new stdClass();
        $metaData->metavalue = $value;
        if($data){
            $metaData->id = $data->id;
            $metaData->updated_at = $this->getCurrentDate();
            $result = JFactory::getDbo()->updateObject('#__j2store_metafields', $metaData, 'id');
        } else {
            $metaData->owner_id = $subscription_id;
            $metaData->metakey = $key;
            $metaData->namespace = 'subscription';
            $metaData->scope = 'subscription_data';
            $metaData->owner_resource = 'subscriptions';
            $metaData->created_at = $this->getCurrentDate();
            $result = JFactory::getDbo()->insertObject('#__j2store_metafields', $metaData);
        }
        return $result;
    }

    /**
     * Get subscriptions meta value
     * */
    protected function getSubscriptionMetaValue($subscription_id, $key, $default = ''){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('metavalue');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote('subscription'));
        $query->where('scope = '.$db->quote('subscription_data'));

        $db->setQuery($query);
        $result = $db->loadResult();
        if(empty($result)){
            return $default;
        }
        return $result;
    }

    /**
     * To update subscription meta
     * */
    protected function addSubscriptionHistoryMeta($subscription_id, $comment, $status, $notify_customer = 0, $scope = ''){
        if($scope == ''){
            $scope = 'subscription_history_data';
        }
        $value['comment'] = $comment;
        $value['status'] = $status;
        $db = JFactory::getDbo();
        $metaData = new stdClass();
        $metaData->metavalue = json_encode($value);
        $metaData->owner_id = $subscription_id;
        $metaData->metakey = 'subscription_history';
        $metaData->namespace = 'subscription_history';
        $metaData->scope = $scope;
        $metaData->owner_resource = 'subscriptions';
        $metaData->valuetype = 'json';
        $metaData->created_at = $this->getCurrentDate();
        $result = $db->insertObject('#__j2store_metafields', $metaData);
        return $result;
    }

    /**
     * Get subscription history
     * */
    public function getSubscriptionHistory($id, $scopes = array(), $frontend = 0){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($id));
        $query->where('namespace = '.$db->quote('subscription_history'));

        if(!empty($scopes)){
            foreach ($scopes as $scope){
                $query->where('scope = '.$db->quote($scope));
            }
        } else {
            if($frontend){
                $query->where('scope != '.$db->quote('subscription_history_data_private'));
            }
        }

        $query->where('metakey = '.$db->quote('subscription_history'));
        $query->order('created_at');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Get subscriptions meta
     * */
    protected function getSubscriptionMetaData($subscription_id, $key){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote('subscription'));
        $query->where('scope = '.$db->quote('subscription_data'));

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    /**
     * Get all subscriptions meta
     * */
    protected function getAllSubscriptionMetaData($subscription_id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('namespace = '.$db->quote('subscription'));
        $query->where('scope = '.$db->quote('subscription_data'));
        $query->where('owner_resource = '.$db->quote('subscriptions'));

        $db->setQuery($query);
        return $db->loadAssocList('metakey');
    }

    /**
     * Get user subscriptions
     * */
    public function getUserSubscriptions(){
        $app = JFactory::getApplication();
        $vars = new stdClass();
        $option = 'com_j2store';
        $ns = $option.'.app.'.$this->_element;
        // get Queue list
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        //$model->setState('queue_type', $this->_element);

        $limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
        $limitstart	= $app->getUserStateFromRequest( $ns.'.limitstart', 'limitstart', 0, 'int' );
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $filter_order_Dir =  $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );
        $filter_order	= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'j2store_subscription_id',	'cmd' );
        $search = $app->input->getString('search',  $model->getState('search', ''));
        $model->setState('limit', $limit);
        $model->setState('limitstart', $limitstart);
        $model->setState('filter_order_Dir', $filter_order_Dir);
        $model->setState('filter_order', $filter_order);
        $user = JFactory::getUser();
        $user_id = $user->get('id', 0);
        if($user_id > 0){
            $model->setState('filter_user_id', $user_id);

            //$vars->pagination = $model->getPagination();
            $vars->state =  $model->getState();
            $vars->subscription = $model->getList();
        } else {
            $vars->state =  $model->getState();
            $vars->subscription = array();
        }


        return $vars;
    }

    /**
     * Get subscription by ID
     * */
    public function getSubscriptionById($id){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $id));
        if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
            $subscription->meta = $this->getAllSubscriptionMetaData($subscription->j2store_subscription_id);
            return $subscription;
        } else {
            return array();
        }
    }

    /**
     * Has renewal
     * */
    protected function hasRenewal($subscription){
        $now = $this->getCurrentDate();
        $endDate = $subscription->end_on;
        $renewalDate = $subscription->next_payment_on;
        if($subscription->subscription_length == 0){
            return true;
        }
        if(strtotime($endDate) > strtotime($now) && strtotime($renewalDate) <= strtotime($now)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * For creating new order for renewal
     * */
    protected function createNewOrderForRenewal($subscription){
        if($subscription->subscription_order_id == ''){
            return false;
        }
        $order = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
        $order->load(array('order_id' => $subscription->subscription_order_id));

        if(!(isset($order->j2store_order_id) && $order->j2store_order_id)){
            return false;
        }

        $new_order = clone $order;
        $new_order->j2store_order_id = 0;
        $new_order->order_type = 'normal';
        $new_order->order_id = time();
        $new_order->is_update = 1;
        $new_order->order_fees = 0;
        $new_order->transaction_id = '';
        $new_order->order_state_id = 5;
        $new_order->subscription_id = $subscription->j2store_subscription_id;
        $new_order->created_on = $this->getCurrentDate();
        J2Store::plugin()->event('BeforeCreateNewOrder', array(&$new_order));
        //trigger on before save
        J2Store::plugin()->event('BeforeSaveOrder', array(&$new_order));
        if($new_order->store()){
            if($new_order->is_update == 1) {
                $new_order->order_id = $this->generateOrderId($new_order->j2store_order_id, $new_order);//time().$new_order->j2store_order_id;
                //trigger on before update
                J2Store::plugin()->event('BeforeUpdateOrder', array(&$new_order));
            }
            $new_order->add_history(JText::_('J2STORE_NEW_ORDER_CREATED'));
            //trigger on before update
            J2Store::plugin()->event('AfterCreateNewOrder', array(&$new_order));

            $new_order->generateInvoiceNumber();
            //generate a unique hash
            $new_order->token = JApplicationHelper::getHash($new_order->order_id);
            $new_order->is_update = 0;
            //save again so that the unique order id is saved.
            $new_order->store();
        }

        //Create Order info
        $orderInfoSubscription = F0FTable::getAnInstance('Orderinfo' ,'J2StoreTable')->getClone();
        $orderInfoSubscription->load(array('order_id' => $subscription->subscription_order_id));

        $new_orderInfo = clone $orderInfoSubscription;
        $new_orderInfo->j2store_orderinfo_id = 0;
        $new_orderInfo->order_id = $new_order->order_id;
        $new_orderInfo->store();

        //Create Order item
        $items = $this->getSubscriptionOrderItems($subscription->subscription_order_id);
        foreach ($items as $item){
            $orderItemSubscription = F0FTable::getAnInstance('OrderItem' ,'J2StoreTable')->getClone();
            $orderItemSubscription->load(array('j2store_orderitem_id' => $item->j2store_orderitem_id));

            //Create Order item
            $new_orderItem = clone $orderItemSubscription;
            $new_orderItem->j2store_orderitem_id = 0;
            $new_orderItem->orderitem_type = 'subscription_renewal';
            $new_orderItem->order_id = $new_order->order_id;
            $new_orderItem->store();

            //Create Subscription Order item
            $result = $this->saveRenewalOrderSubscriptions($new_orderItem, $subscription);
            $attributes = $this->getSubscriptionOrderItemAttributes($item->j2store_orderitem_id);

            //save order attributes
            if(count($attributes)) {
                $this->saveOrderItemAttributes($attributes, $new_orderItem);
            }
        }

        // Save Order Shipping
        $orderShippingSubscription = F0FTable::getAnInstance('Ordershippings' ,'J2StoreTable')->getClone();
        $orderShippingSubscription->load(array('order_id' => $subscription->subscription_order_id));

        if(isset($orderShippingSubscription->j2store_ordershipping_id) && $orderShippingSubscription->j2store_ordershipping_id){
            $new_orderShipping = clone $orderShippingSubscription;
            $new_orderShipping->j2store_ordershipping_id = 0;
            $new_orderShipping->order_id = $new_order->order_id;
            $new_orderShipping->store();
        }

        // Save Order Fee
        $orderFees = $this->getSubscriptionOrderFees($subscription->subscription_order_id);
        if(count($orderFees)) {
            foreach ($orderFees as $orderFee) {
                $subscriptionOrderFee = F0FTable::getInstance('OrderFee', 'J2StoreTable')->getClone();
                $subscriptionOrderFee->load($orderFee->j2store_orderfee_id);
                $new_orderFee = clone $subscriptionOrderFee;
                $new_orderFee->j2store_orderfee_id = 0;
                $new_orderFee->order_id = $new_order->order_id;
                $new_orderFee->store();
                unset($new_orderFee);
            }
        }

        // Save Order Discount
        $orderDiscounts = $this->getSubscriptionOrderDiscounts($subscription->subscription_order_id);
        if(count($orderDiscounts)) {
            foreach ($orderDiscounts as $orderDiscount) {
                $subscriptionOrderDiscount = F0FTable::getInstance('Orderdiscounts', 'J2StoreTable')->getClone();
                $subscriptionOrderDiscount->load($orderDiscount->j2store_orderdiscount_id);
                $new_orderDiscount = clone $subscriptionOrderDiscount;
                $new_orderDiscount->j2store_orderdiscount_id = 0;
                $new_orderDiscount->order_id = $new_order->order_id;
                $new_orderDiscount->store();
                unset($new_orderDiscount);
            }
        }

        // Save Order Tax
        $subscriptionTaxes = $this->getSubscriptionOrderTaxes($subscription->subscription_order_id);
        if(count($subscriptionTaxes)){
            foreach ($subscriptionTaxes as $subscriptionTax){
                $subscriptionOrderTax = F0FTable::getInstance('Ordertax', 'J2StoreTable')->getClone();
                $subscriptionOrderTax->load($subscriptionTax->j2store_ordertax_id);
                $new_orderTax = clone $subscriptionOrderTax;
                $new_orderTax->j2store_ordertax_id = 0;
                $new_orderTax->order_id = $new_order->order_id;
                $new_orderTax->store();
                unset($new_orderTax);
            }
        }

        return $new_order->order_id;
    }

    /**
     * Save renewal Coupons
     * */
    protected function saveRenewalSubscriptionsCoupons($new_order, $subscription, $renewalAmount){
        $discounts = $this->getSubscriptionRenewCoupons($subscription->j2store_subscription_id);
        $discountAmount = 0;
        if(count($discounts)){
            foreach ($discounts as $key => $discount){
                $values = $discount->metavalue;
                $discountObj = json_decode($values);
                if(is_object($discountObj)){
                    if($discountObj->discount_value_type == 'percentage_product'){
                        $discountAmount += round((($discountObj->discount_value / 100) * $renewalAmount), 2);
                    } else {
                        if($discountObj->discount_value < ($renewalAmount-$discountAmount)){
                            $discountAmount += $discountObj->discount_value;
                        }
                    }
                }
            }
        }
        if($discountAmount>0){
            $config = J2Store::config();
            $discountTaxAmount = $this->getRenewalTaxAmount($subscription, $discountAmount);
            if(!$config->get ( 'config_including_tax', 0 )) {
                $renewalAmount -= ($discountAmount + $discountTaxAmount);
            } else {
                $renewalAmount -= $discountAmount;
            }
            $orderDiscount = F0FTable::getInstance('Orderdiscount' ,'J2StoreTable')->getClone();
            $orderDiscount->load(array('j2store_orderdiscount_id' => 0));
            $orderDiscount->order_id = $new_order->order_id;
            $orderDiscount->discount_type = $discountObj->discount_type;
            $orderDiscount->discount_title = JText::_($discountObj->discount_title);
            $orderDiscount->discount_code = $discountObj->discount_code;
            $orderDiscount->discount_value = $discountAmount;
            $orderDiscount->discount_value_type = $discountObj->discount_value_type;
            $orderDiscount->user_id = $subscription->user_id;
            $orderDiscount->discount_customer_email = $new_order->user_email;

            $discounts = $this->getFormatedDiscountAndTax($discountAmount, $discountTaxAmount);
            $orderDiscount->discount_amount = $discounts['amount'];
            $orderDiscount->discount_tax = $discounts['tax'];
            $orderDiscount->store();
        }

        return $renewalAmount;
    }

    /**
     * To get the right discount and tax
     * */
    public function getFormatedDiscountAndTax($discountAmount, $discountTaxAmount){
        $config = J2Store::config();
        if($config->get ( 'config_including_tax', 0 )){
            $discount['amount'] = $discountAmount-$discountTaxAmount;
            $discount['tax'] = $discountTaxAmount;
        } else {
            $discount['amount'] = $discountAmount;
            $discount['tax'] = $discountTaxAmount;
        }

        return $discount;
    }

    /**
     * Get order attributes
     * */
    protected function getOrderAttributes($orderitem_id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_orderitemattributes');
        $query->where('orderitem_id = '.$db->quote($orderitem_id));

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * get Next renewal date
     * */
    public function getNextRenewalDateFromOrderItem($orderItemID){
        $subscriptionOrder = $this->getSubscriptionOrder($orderItemID);
        if(isset($subscriptionOrder->subscription_id) && $subscriptionOrder->subscription_id){
            $subscription = $this->getSubscriptionById($subscriptionOrder->subscription_id);

            if(!empty($subscription)) {
                if ($subscription->subscription_length > 0) {
                    if (strtotime($subscriptionOrder->schedule_next_payment) < strtotime($subscription->end_on)) {
                        return $this->getDateTime($subscriptionOrder->schedule_next_payment);
                    }
                } else if ($subscription->subscription_length == 0) {
                    return $this->getDateTime($subscriptionOrder->schedule_next_payment);
                }
            }
        }
        return '';
    }

    /**
     * get Next renewal date
     * */
    public function getNextRenewalDateFromSubscriptionOrder($order){
        if(isset($order->subscription_id) && $order->subscription_id){
            $subscription = $this->getSubscriptionById($order->subscription_id);
            if(!empty($subscription)){
                if($subscription->subscription_length > 0){
                    if(strtotime($subscription->next_payment_on) < strtotime($subscription->end_on)){
                        return $this->getDate($subscription->next_payment_on);
                    }
                } else if($subscription->subscription_length == 0){
                    return $this->getDate($subscription->next_payment_on);
                }
            }
        }
        return '';
    }

    /**
     * get subscription from subscription order
     * */
    public function getSubscriptionFromSubscriptionOrder($order){
        if(isset($order->subscription_id) && $order->subscription_id){
            $subscription = $this->getSubscriptionById($order->subscription_id);
            return $subscription;
        }
        return array();
    }

    /**
     * get subscription from order item
     * */
    public function getSubscriptionFromOrderItem($orderItemID){
        $subscriptionOrder = $this->getSubscriptionOrder($orderItemID);
        if(isset($subscriptionOrder->subscription_id) && $subscriptionOrder->subscription_id){
            $subscription = $this->getSubscriptionById($subscriptionOrder->subscription_id);
            return $subscription;
        }
        return array();
    }

    /**
     * Get subscription order by order item id
     * */
    protected function getSubscriptionOrder($orderItemId){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_ordersubscriptions');
        $query->where('orderitem_id = '.$db->quote($orderItemId));
        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get related renewal orders from parent order ID
     * */
    public function getRelatedRenewalOrders($parent_id, $subscription_id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('o.*');
        $query->from('#__j2store_orders o');
        $query->join('LEFT', '#__j2store_ordersubscriptions as os ON os.order_id = o.order_id');
        $query->where('o.parent_id = '.$db->quote($parent_id));
        $query->where('os.subscription_id = '.$db->quote($subscription_id));
        $query->where('o.order_type <> '.$db->quote('subscription'));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Save order item attributes
     * */
    protected function saveOrderItemAttributes($attributes, $orderitem) {
        foreach ($attributes as $attribute) {
            $orderitemattribute = F0FTable::getAnInstance('OrderItemAttribute', 'J2StoreTable')->getClone();
            $orderitemattribute->bind($attribute);
            $orderitemattribute->j2store_orderitemattribute_id = 0;
            $orderitemattribute->orderitem_id = $orderitem->j2store_orderitem_id;
            $orderitemattribute->store();
            unset($orderitemattribute);
        }
    }

    /**
     * Process subscription renewal
     * */
    public function processSubscriptionRenewal($subscriptionId){
        $subscription = $this->getSubscriptionById($subscriptionId);
        if(!empty($subscription)){
            $hasRenewal = $this->hasRenewal($subscription);
            if($hasRenewal){    
                $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_PROCESSING_RENEWAL');
                $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
                $newOrderId = $this->createNewOrderForRenewal($subscription);
                if($newOrderId){
                    $order = F0FTable::getInstance('Order' ,'J2StoreTable')->getClone();
                    $order->load(array('order_id' => $newOrderId));
                    $order->resetOrderID($order->order_id);
                    if(!empty($subscription->payment_method)){
                        $this->renewSubscription($subscription, $order);
                    } else {
                        $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_PAYMENT_METHOD_DO_NOT_EXIST_TO_PROCESS_RENEWAL_PAYMENT');
                        $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
                        $this->processFailedRenewalPayment($subscription, $order);
                    }
                }
            }
        }
    }

    /**
     * create New Order For Renewal
     * */
    public function createNewOrderForRenewalEvent($subscriptionId){
        $subscription = $this->getSubscriptionById($subscriptionId);
        if(!empty($subscription)){
            $newOrderId = $this->createNewOrderForRenewal($subscription);
            if($newOrderId){
                return $newOrderId;
            }
        }

        return false;
    }

    /**
     * Update new renewal Date
     * */
    protected function updateNewRenewalDate($subscription_data){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscription_data->j2store_subscription_id));
        $period = $subscription->period;
        $period_units = $subscription->period_units;
        $length = $subscription->subscription_length;
        $startDate = $subscription->next_payment_on;
        if($period_units && $length >= 0) {
        } else {
//            $length = 1;
//            $period_units = 100;
//            $period = 'Y';
        }
        if($length == 0){
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $period_units);
        } else {
            $endate = $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
        }
        $subscription->next_payment_on = $endate;
        $subscription->current_billing_cycle = $subscription->current_billing_cycle+1;
        $subscription->store();
    }

    /**
     * Renew a subscription
     * */
    protected function renewSubscription($subscription, $order){
        J2Store::plugin()->event('ProcessRenewalPayment', array($subscription->payment_method, $subscription, $order));
    }

    /**
     * Process success payment - renewal
     * */
    public function SuccessStripeCustomer($customer){
        // echo $customer->last4;
        echo $customer->default_source->last4;
    }

    public function processSuccessRenewalPayment($subscription, $order, $update_renewal_date = true){
        $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_RENEWAL_PAYMENT_COMPLETED');
        $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
        if($subscription->status != 'active'){
            $this->changeSubscriptionStatus($subscription->j2store_subscription_id, 'active');
        }
        J2Store::plugin()->event('DoUpdateRenewalDateOnRenewalSuccess', array(&$update_renewal_date, $subscription, $order));
        if($update_renewal_date === true){
            $this->updateNewRenewalDate($subscription);
        }
    }

    /**
     * Process failed payment - renewal
     * */
    public function processFailedRenewalPayment($subscription, $order){
        $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_RENEWAL_PAYMENT_FAILED');
        $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
        $this->changeSubscriptionStatus($subscription->j2store_subscription_id, 'failed');
    }

    /**
     * Process no response payment - renewal
     * */
    public function processNoResponseRenewalPayment($subscription, $order){
        $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_RENEWAL_PAYMENT_NO_RESPONSE');
        $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
        $params = $this->getpluginParams();
        $max_renewal_retry = $params->get('max_renewal_retry', 5);
        $renewal_retry = $this->updateRenewalRetryCount($subscription);
        if($renewal_retry >= $max_renewal_retry){
            $this->updateCardExpiredOnRenewalPaymentFailedDueToCardError($subscription, $order);
        } else {
            $this->updateNextRenewalRetryDate($subscription->j2store_subscription_id, $renewal_retry);
        }
    }

    /**
     * Update card expire when payment fails due to card error which can't be retried again.
     * */
    public function updateCardExpiredOnRenewalPaymentFailedDueToCardError($subscription, $order){
        $this->changeSubscriptionStatus($subscription->j2store_subscription_id, 'card_expired');
        $this->sendMailOnStatusChangedToCardExpired($subscription->j2store_subscription_id, strtotime($subscription->next_payment_on));
    }

    /**
     * Process Renewal payment - renewal
     * */
    public function processPendingRenewalPayment($subscription, $order){
        $comment = JText::_('J2STORE_SUBSCRIPTION_HISTORY_RENEWAL_PAYMENT_PENDING');
        $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $comment, $subscription->status);
        $this->changeSubscriptionStatus($subscription->j2store_subscription_id, 'pending');
        $this->updateNewRenewalDate($subscription);
    }

    /**
     * Update next renewal retry date
     *
     * @param $subscription_id integer
     * @param $renewal_retry_count integer
     * */
    public function updateNextRenewalRetryDate($subscription_id, $renewal_retry_count){
        $params = $this->getpluginParams();
        $renewal_retry_units = (int)$params->get('renewal_retry_interval_period_units', 1);
        $renewal_retry_period = $params->get('renewal_retry_interval_period', "hour");
        if($renewal_retry_units > 0){
            $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
            $subscription->load(array('j2store_subscription_id' => $subscription_id));
            if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
                $date = $this->getCurrentDate();
                switch ($renewal_retry_period){
                    case "hour":
                        $date = date("Y-m-d H:i:s", strtotime($date." +".$renewal_retry_units." hours"));
                        break;
                    case "day":
                        $date = date("Y-m-d H:i:s", strtotime($date." +".$renewal_retry_units." days"));
                        break;
                    case "week":
                        $date = date("Y-m-d H:i:s", strtotime($date." +".$renewal_retry_units." weeks"));
                        break;
                    case "month":
                        $date = date("Y-m-d H:i:s", strtotime($date." +".$renewal_retry_units." months"));
                        break;
                }
                $subscription->renewal_retry_on = $date;
                if($subscription->store()){
                    $this->updateSubscriptionHistory($subscription->j2store_subscription_id, $subscription->status, sprintf(JText::_('J2STORE_SUBSCRIPTION_HISTORY_NEXT_RENEWAL_RETRY_ON'), $date));
                    $this->sendMailAboutRenewalRetry($subscription->j2store_subscription_id, $date, $renewal_retry_count, $renewal_retry_period, $renewal_retry_units);
                }
            }
        }
    }

    /**
     * Update renewal retry count
     * */
    public function updateRenewalRetryCount($subscription){
        $subscriptionMeta = SubscriptionMeta::getInstance();
        $next_payment_on_time = strtotime($subscription->next_payment_on);
        $key = 'renewal_retry_count_'.$next_payment_on_time;
        $scope = 'renewal_retry';
        $existingData = $subscriptionMeta->getSubscriptionMetaData($subscription->j2store_subscription_id, $key, 'subscription', $scope);
        $value = 1;
        if($existingData){
            $value = $existingData->metavalue+1;
        }
        $subscriptionMeta->updateSubscriptionMeta($subscription->j2store_subscription_id, $key, $value, 'subscription', $scope, 'integer');
        return $value;
    }

    /**
     * Check available sign up fee
     * */
    public function isApplicableSignUpFee($productId, $variant_id = 0){
        $addSignUpFeeOnEachPurchase = $this->addSignUpFeeOnEachPurchase($productId, $variant_id);
        if(!$addSignUpFeeOnEachPurchase){
            $user = JFactory::getUser();
            if($user->get('id')){
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('count(*)');
                $query->from('#__j2store_subscriptions');
                $query->where('product_id = '.$db->quote($productId));
                if($variant_id){
                    $query->where('variant_id = '.$db->quote($variant_id));
                }
                $query->where('user_id = '.$db->quote($user->get('id')));
                $query->where('status IN (\'active\', \'expired\')');
                $db->setQuery($query);
                if($db->loadResult()){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check add sign-up fee is applicable on each purchase
     *
     * @param int $productId
     * @param int $variant_id
     *
     * @return boolean
     * */
    public function addSignUpFeeOnEachPurchase($productId, $variant_id){
        $subscription_product = $this->getSubscriptionProductData($productId, $variant_id);

        return (int) isset($subscription_product->signup_fee_on_each_purchase)? $subscription_product->signup_fee_on_each_purchase: '';
    }

    /**
     * Check get subscription data
     *
     * @param int $productId
     * @param int $variant_id
     *
     * @return object
     * */
    public function getSubscriptionProductData($productId, $variant_id){
        $product = J2Store::product()->setId($productId)->getProduct();
        F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);
        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
        $variant_table->load(array(
            'j2store_variant_id' => $variant_id
        ));

        $registry = new JRegistry();
        $registry->loadString($variant_table->params);
        $subscriptionproduct = $registry->get('subscriptionproduct',array());

        return $subscriptionproduct;
    }

    /**
     * Cancel Subscription
     * */
    public function cancelSubscription($sid, $frontend = 1){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $sid));
        if($subscription->j2store_subscription_id && in_array($subscription->status, array('active', 'in_trial'))){
            $j2StorePlugin = \J2Store::plugin();
            $subscriptionMeta = $this->getAllSubscriptionMetaData($sid);
            if($frontend){
                $user = JFactory::getUser();
                $user_id = $user->get('id');
                if($subscription->user_id != $user_id){
                    return false;
                }
            }
            $subscription->end_on = $subscription->next_payment_on;
            if($subscription->subscription_length == 0){
                $subscription->subscription_length = 1;
            }
            if($subscription->store()){
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($subscription->j2store_subscription_id, 'canceled'));
                if($frontend){
                    $messageForHistory = JText::_('J2STORE_SUBSCRIPTION_HISTORY_SUBSCRIPTION_CANCELED_THROUGH_FRONT_END');
                } else {
                    $messageForHistory = JText::_('J2STORE_SUBSCRIPTION_HISTORY_SUBSCRIPTION_CANCELED_THROUGH_BACK_END');
                }
                $j2StorePlugin->event('AddSubscriptionHistory', array($subscription->j2store_subscription_id, 'canceled', $messageForHistory));
                $j2StorePlugin->event('AfterSubscriptionCanceled', array($subscription->payment_method, $subscription->j2store_subscription_id, $subscriptionMeta));
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Pause Subscription
     * */
    public function pauseSubscription($sid, $msg, $frontend = 1){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $sid));
        if($subscription->j2store_subscription_id && in_array($subscription->status, array('active', 'in_trial'))){
            $j2StorePlugin = \J2Store::plugin();
            $subscriptionMeta = $this->getAllSubscriptionMetaData($sid);
            $user = JFactory::getUser();
            $user_id = $user->get('id');
            if($subscription->user_id != $user_id){
                return -1;
            }
            
            $to = 'info@beautybins.com';
            $subject = 'You received an request to pause the subscription';
            $message = $msg;
            $headers = 'From: '.$user->get('email') . "\r\n" .
                'Reply-To: '. $user->get('email') . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            if(mail($to, $subject, $message, $headers)){
                return true;
            }
            return false;
        }
    }

    public function updateRetryRenewalProcess($sid){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $sid));
        if(!empty($subscription->j2store_subscription_id)){
            $expiryControl = \J2Store\Subscription\Helper\ExpiryControl::getInstance();
            $expiryControl->updateRenewalStatusCompletedManually($subscription->j2store_subscription_id);
            $message = JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATED_RETRY_RENEWAL_PROCESS_MANUALLY');
            $this->addSubscriptionHistoryMeta($subscription->j2store_subscription_id, $message, $subscription->status, 0 ,'subscription_history_data_private');
            $result['status'] = 1;
            $result['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATED_RETRY_RENEWAL_PROCESS_SUCCESSFULLY').'</p>';
        } else {
            $result['status'] = 0;
            $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }

        return $result;
    }

    /**
     * Change Subscription Status
     * */
    public function changeSubscriptionStatusThroughBackend($sid, $status, $notify_customer = true){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $sid));
        if($subscription->j2store_subscription_id && $subscription->status != $status){
            if($status == 'canceled'){
                $this->cancelSubscription($sid, 0);
            }
            if($status == 'active'){
                $end = strtotime($subscription->end_on);
                $now = strtotime($this->getCurrentDate());
                if($end < $now && $subscription->end_on != '0000-00-00 00:00:00'){
                    $result['status'] = 0;
                    $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_CHANGE_SUBSCRIPTION_FALIED_INVALID_END_DATE').'</p>';
                    return $result;
                }
            }
            if($status == 'in_trial'){
                $passed_trial_check = 0;
                if($subscription->trial_start_on != '0000-00-00 00:00:00' && $subscription->trial_end_on != '0000-00-00 00:00:00'){
                    $now = strtotime($this->getCurrentDate());
                    $trialStartTime = strtotime($subscription->trial_start_on);
                    $trialEndTime = strtotime($subscription->trial_end_on);
                    if($trialStartTime <= $now && $trialEndTime >= $now){
                        $passed_trial_check = 1;
                    }
                }
                if(!$passed_trial_check){
                    $result['status'] = 0;
                    $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_CHANGE_SUBSCRIPTION_FALIED_INVALID_TRIAL_DATE').'</p>';
                    return $result;
                }
            }
            $result = array();
            $j2StorePlugin = \J2Store::plugin();
            $j2StorePlugin->event('ValidateSubscriptionStatusBeforeChangeThroughAdmin', array($subscription, $status, &$result));
            if(!empty($result) && isset($result['status']) && !$result['status']){
                return $result;
            }

            $j2StorePlugin->event('ChangeSubscriptionStatus', array($subscription->j2store_subscription_id, $status, $notify_customer));
            $result['status'] = 1;
            $result['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_CHANGE_SUBSCRIPTION_STATUS_SUCCESSFULLY').'</p>';
        } else {
            $result['status'] = 0;
            $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_CHANGE_SUBSCRIPTION_FALIED_SAME_STATUS').'</p>';
        }

        return $result;
    }

    /**
     * re-calculate subscription order
     * */
    public function reCalculateSubscriptionOrderThroughBackend($order_id){
        $coupon_model = F0FModel::getTmpInstance ( 'Coupons', 'J2StoreModel' );
        if ( $coupon_model->has_coupon () ) {
            $coupon_model->remove_coupon();
        }
        $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
        $order->load(array('order_id' => $order_id));
        if(isset($order->j2store_order_id) && $order->j2store_order_id && $order->order_type == 'subscription'){
            $order->getAdminTotals(true);
            $result['status'] = 1;
            $result['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_UPDATED_SUCCESSFULLY').'</p>';
        } else {
            $result['status'] = 0;
            $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_ORDER_ID').'</p>';
        }

        return $result;
    }

    /**
     * update subscription order item price
     * */
    public function updateSubscriptionOrderItemPrice($order_item_id, $order_item_price, $order_item_tax_class_id){
        $orderItem = F0FTable::getInstance('OrderItem', 'J2StoreTable')->getClone();
        $orderItem->load(array('j2store_orderitem_id' => $order_item_id));
        if(isset($orderItem->j2store_orderitem_id) && $orderItem->j2store_orderitem_id && $orderItem->orderitem_type == 'subscription'){
            if(empty($order_item_tax_class_id)) $order_item_tax_class_id = 0;
            $orderItem->orderitem_price = $order_item_price;
            $orderItem->orderitem_taxprofile_id = $order_item_tax_class_id;
            if($orderItem->store()){
                $result['status'] = 1;
                $result['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATED_SUCCESSFULLY').'</p>';
            } else {
                $result['status'] = 0;
                $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATE_FAILED').'</p>';
            }
        } else {
            $result['status'] = 0;
            $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_ORDER_ITEM').'</p>';
        }

        return $result;
    }

    /**
     * update subscription order item quantity
     * */
    public function updateSubscriptionOrderItemQuantity($order_item_id, $order_item_quantity){
        $orderItem = F0FTable::getInstance('OrderItem', 'J2StoreTable')->getClone();
        $orderItem->load(array('j2store_orderitem_id' => $order_item_id));
        if(isset($orderItem->j2store_orderitem_id) && $orderItem->j2store_orderitem_id && $orderItem->orderitem_type == 'subscription'){
            $orderItem->orderitem_quantity = $order_item_quantity;
            if($orderItem->store()){
                $result['status'] = 1;
                $result['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATED_SUCCESSFULLY').'</p>';
            } else {
                $result['status'] = 0;
                $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATE_FAILED').'</p>';
            }
        } else {
            $result['status'] = 0;
            $result['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_ORDER_ITEM').'</p>';
        }

        return $result;
    }

    /**
     * Add subscription history manually
     * */
    public function addSubscriptionHistoryManually($id, $note, $history_type){
        $subscription = $this->getSubscriptionById($id);
        if($subscription){
            if($history_type == 'private'){
                $scope = 'subscription_history_data_private';
            } else {
                $scope = 'subscription_history_data';
            }
            $result = $this->addSubscriptionHistoryMeta($id, $note, $subscription->status, 0, $scope);
            if($result){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ADDED_HISTORY_SUCCESS').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_FAILED_TO_ADD_HISTORY').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTION_INVALID').'</p>';
        }
        return $returnResult;
    }

    /**
     * Update Subscription through backend
     * */
    public function updateSubscriptionThoughBackend(){
        $app = JFactory::getApplication();
        $sub_id = $app->input->get('sid', 0);
        if($sub_id){
            $validated = $this->validateUpdateSubscription();
            if(count($validated['errors'])){
                $returnResult['status'] = 0;
                $returnResult['errors'] = $validated['errors'];
                $errorMessages = '';
                foreach ($returnResult['errors'] as $key => $error){
                    if($key){
                        $errorMessages .= "<br>".$error['message'];
                    } else {
                        $errorMessages .= $error['message'];
                    }

                }
                $returnResult['message'] = '<p class="text-warning">'.$errorMessages.'</p>';
            } else {
                $subscription = $this->getSubscriptionById($sub_id);
                $result = $this->updateSubscription($sub_id, $validated['data']);
                if($result){
                    $edit_note = $app->input->getHtml('subscription_edit_note', '');
                    if($edit_note != ''){
                        $this->updateSubscriptionHistory($sub_id, $subscription->status, $edit_note, $notify_customer = 0, $scope = 'subscription_history_recurring_edit');
                    }
                    $returnResult['status'] = 1;
                    $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_EDIT_SUCCESS').'</p>';
                } else {
                    $returnResult['status'] = 0;
                    $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_EDIT_FAILED').'</p>';
                }
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTION_INVALID').'</p>';
        }

        return $returnResult;
    }

    /**
     * Update Subscription
     * */
    protected function updateSubscription($sub_id, $dates){
        $app = JFactory::getApplication();
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $sub_id));
        if(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id){
            if(in_array($subscription->status, array('new', 'future'))){
                $subscription->start_on = $dates['start_on'];
            }
            $subscription->end_on = $dates['end_on'];
            if($subscription->end_on == '0000-00-00 00:00:00'){
                $subscription->subscription_length = 0;
            } else {
                if($subscription->subscription_length == 0){
                    $subscription->subscription_length = 1;
                }
            }

            $subscription->next_payment_on = $dates['next_payment_on'];
            $subscription->period = $app->input->get('period', $subscription->period);
            $subscription->period_units = $app->input->get('period_units', $subscription->period_units);
            $renewal_amount = $app->input->get('renewal_amount', '');
            if($renewal_amount>0){
                $subscription->renewal_amount = $app->input->get('renewal_amount', '');
            }
            if($subscription->store()){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * validate Subscription date and time before Update
     * */
    protected function validateUpdateSubscription(){
        $app = JFactory::getApplication();
        $error = $data = array();
        $message = array();
        $hoursAndMinutesFields = array('start_on', 'end_on', 'next_payment_on');
        foreach ($hoursAndMinutesFields as $hoursAndMinutesField){
            $start_on_hours = $app->input->get($hoursAndMinutesField.'_hours', 0);
            $valid = $this->validateHours($start_on_hours);
            if($valid != ''){
                $message['key'] = $hoursAndMinutesField.'_hours';
                $message['message'] = $valid;
                $error[] = $message;
            }
            $start_on_minutes = $app->input->get($hoursAndMinutesField.'_minutes', 0);
            $valid = $this->validateMinutes($start_on_minutes);
            if($valid != ''){
                $message['key'] = $hoursAndMinutesField.'_minutes';
                $message['message'] = $valid;
                $error[] = $message;
            }
            $start_on_seconds = $app->input->get($hoursAndMinutesField.'_seconds', 0);
            $valid = $this->validateMinutes($start_on_seconds, 0);
            if($valid != ''){
                $message['key'] = $hoursAndMinutesField.'_seconds';
                $message['message'] = $valid;
                $error[] = $message;
            }
        }
        if(empty($error)){
            $sid = $app->input->getInt('sid');
            if($sid){
                $subscription = $this->getSubscriptionById($sid);
                if(!in_array($subscription->status, array('new', 'future'))){
                    $start_on = $subscription->start_on;
                } else {
                    $start_on_hours = $app->input->get('start_on_hours', '');
                    $start_on_minutes = $app->input->get('start_on_minutes', '');
                    $start_on_seconds = $app->input->get('start_on_seconds', '');
                    $hms = $this->formatHoursMinutesSeconds($start_on_hours, $start_on_minutes, $start_on_seconds);
                    $startOn = $app->input->get('start_on', date('Y-m-d'));
                    if($startOn == ''){
                        $startOn = date('Y-m-d');
                    }
                    $start_on = $startOn.' '.$hms;
                }
                $data['start_on'] = $start_on;
                if($subscription->status == 'future'){
                } else if(strtotime($start_on) > time()){
                    $message['key'] = 'start_on';
                    $message['message'] = JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_START_DATE_TIME_LESS_THAN_CURRENT');
                    $error[] = $message;
                }
                $end_on_hours = $app->input->get('end_on_hours', '');
                $end_on_minutes = $app->input->get('end_on_minutes', '');
                $end_on_seconds = $app->input->get('end_on_seconds', '');
                $hms = $this->formatHoursMinutesSeconds($end_on_hours, $end_on_minutes, $end_on_seconds);
                $endOn = $app->input->get('end_on', '0000-00-00');
                if($endOn == ''){
                    $endOn = '0000-00-00';
                    $hms = '00:00:00';
                }
                $data['end_on'] = $end_on = $endOn.' '.$hms;

                $next_payment_on_hours = $app->input->get('next_payment_on_hours', '');
                $next_payment_on_minutes = $app->input->get('next_payment_on_minutes', '');
                $next_payment_on_seconds = $app->input->get('next_payment_on_seconds', '');
                $hms = $this->formatHoursMinutesSeconds($next_payment_on_hours, $next_payment_on_minutes, $next_payment_on_seconds);
                $nextPayment = $app->input->get('next_payment_on', date('Y-m-d'));
                if($nextPayment == ''){
                    $nextPayment = date('Y-m-d');
                }
                $data['next_payment_on'] = $next_payment_on = $nextPayment.' '.$hms;
                if($end_on != '0000-00-00 00:00:00'){
                    if(strtotime($start_on) >= strtotime($end_on)){
                        $message['key'] = 'end_on';
                        $message['message'] = JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_END_DATE_TIME');
                        $error[] = $message;
                    }
                    if(strtotime($next_payment_on) > strtotime($end_on)){
                        $message['key'] = 'next_payment_on';
                        $message['message'] = JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_NEXT_PAYMENT_DATE_TIME');
                        $error[] = $message;
                    }
                }
                if(strtotime($next_payment_on) < strtotime($start_on)){
                    $message['key'] = 'next_payment_on';
                    $message['message'] = JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_NEXT_PAYMENT_DATE_TIME_LESS_THAN_STARTDATE');
                    $error[] = $message;
                }
            }
        }
        $return['errors'] = $error;
        $return['data'] = $data;
        return $return;
    }

    /**
     * Format the hours:minutes:seconds
     * */
    protected function formatHoursMinutesSeconds($start_on_hours, $start_on_minutes, $start_on_seconds){
        $hours = (int)$start_on_hours;
        if(strlen($hours) == 1){
            $hours = '0'.$hours;
        }
        $minutes = (int)$start_on_minutes;
        if(strlen($minutes) == 1){
            $minutes = '0'.$minutes;
        }
        $seconds = (int)$start_on_seconds;
        if(strlen($seconds) == 1){
            $seconds = '0'.$seconds;
        }
        return $hours.':'.$minutes.':'.$seconds;
    }

    /**
     * Validate Hours
     * */
    protected function validateHours($value){
        if($value > 23){
            return JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_HOURS');
        }
        return '';
    }

    /**
     * Validate Hours
     * */
    protected function validateMinutes($value, $type = 1){
        if($value > 59){
            if($type){
                return JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_MINUTES');
            } else {
                return JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_VALIDATE_INVALID_SECONDS');
            }
        }
        return '';
    }

    /**
     * Get subscriptions based on order id
     * */
    public function getSubscriptionByOrderId($order_id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_subscriptions');
        $query->where('order_id = '.$db->quote($order_id));

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    /**
     * To check has renewal for a subscription (to load renew button)
     * */
    public function hasRenew($subscription){
        $params = $this->getpluginParams();
        if($params->get('show_renew_button', 0)){
            if(!(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id)){
                $subscription  = $this->getSubscriptionById($subscription);
            }
            $productParams = $this->getSubscriptionProductParams($subscription->variant_id);
            $recurringType = isset($productParams->recurring_type)? $productParams->recurring_type: 'multiple';
            if($recurringType == 'single'){
                if($subscription->status == 'active' || $subscription->status == 'expired'){
                    $hasActiveSubscription = $this->hasActiveSubscriptionForTheProduct($subscription, 0);
                    if(!$hasActiveSubscription){
                        return true;
                    }
                    //return true;
                }
            } else {
                if($subscription->status == 'expired'){
                    $hasActiveSubscription = $this->hasActiveSubscriptionForTheProduct($subscription);
                    if(!$hasActiveSubscription){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get subscription product params
     * */
    protected function getSubscriptionProductParams($variant_id){
        $variant_table = F0FTable::getAnInstance('Variant','J2StoreTable')->getClone();
        $variant_table->load(array(
            'j2store_variant_id' => $variant_id
        ));

        $registry = new JRegistry();
        $registry->loadString($variant_table->params);
        $subscriptionproduct = $registry->get('subscriptionproduct',array());

        return $subscriptionproduct;
    }

    /**
     * check has active subscription for the same product
     * */
    protected function hasActiveSubscriptionForTheProduct($subscription, $recurring = 1){
        if(!(isset($subscription->j2store_subscription_id) && $subscription->j2store_subscription_id)){
            $subscription  = $this->getSubscriptionById($subscription);
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(*)')->from('#__j2store_subscriptions');
        $query->where('user_id = '.$db->q($subscription->user_id));
        if($recurring){
            $query->where('(status = '.$db->q('active').' OR status = '.$db->q('future').')');
        } else {
            $query->where('status = '.$db->q('future'));
        }
        $query->where('product_id = '.$db->q($subscription->product_id));
        $query->where('variant_id = '.$db->q($subscription->variant_id));
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * get Start Date If Has Active Subscription
     * */
    protected function getStartDateIfHasActiveSubscription($product_id, $variant_id, $user_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('end_on')->from('#__j2store_subscriptions');
        $query->where('user_id = '.$db->q($user_id));
        $query->where('(status = '.$db->q('active').' OR status = '.$db->q('future').')');
        $query->where('product_id = '.$db->q($product_id));
        $query->where('variant_id = '.$db->q($variant_id));
        $query->order('end_on DESC');
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * Get subscription order Items
     * */
    public function getSubscriptionOrderItems($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderitems');
        $query->where('order_id = '.$db->q($order_id));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get subscription order Item Attributes
     * */
    public function getSubscriptionOrderItemAttributes($order_item_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderitemattributes');
        $query->where('orderitem_id = '.$db->q($order_item_id));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get subscription order Discounts
     * */
    public function getSubscriptionOrderDiscounts($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderdiscounts');
        $query->where('order_id = '.$db->q($order_id));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get subscription order Fee
     * */
    public function getSubscriptionOrderFees($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderfees');
        $query->where('order_id = '.$db->q($order_id));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get subscription order Taxes
     * */
    public function getSubscriptionOrderTaxes($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_ordertaxes');
        $query->where('order_id = '.$db->q($order_id));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get subscription order Additional Fees
     * */
    public function getAdditionalFeesOfSubscriptionOrder($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_orderfees');
        $query->where('order_id = '.$db->q($order_id));
        $query->where('fee_type <> '.$db->q('subscription_signup_fee'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Get Additional fees Ids as an array
     * */
    public function getAdditionalFeesIds($order_id){
        $total_rows = array();
        $fees = F0FModel::getTmpInstance ( 'Orderfees', 'J2StoreModel' )->order_id ( $order_id )->getList ();
        if(count($fees)){
            foreach ( $fees as $fee ) {
                $total_rows['fee_'.F0FInflector::underscore($fee->name)] = $fee->j2store_orderfee_id;
            }
        }
        return $total_rows;
    }

    public function moveAttributeFromParentIfNotExists(){
        $missings = $this->getMissingAttributeOrders();
        echo "<pre>";
        print_r($missings);
        if(!empty($missings)){
            foreach ($missings as $missing){
                $missingAttributes = $this->getMissingAttributes($missing);
                print_r($missingAttributes);
                foreach ($missingAttributes as $missingAttribute){
                    unset( $orderitemattribute );
                    $orderitemattribute = F0FTable::getAnInstance ( 'OrderItemAttribute', 'J2StoreTable' )->getClone ();
                    $orderitemattribute->bind ( $missingAttribute );
                    $orderitemattribute->j2store_orderitemattribute_id = 0;
                    $orderitemattribute->orderitem_id = $missing->j2store_orderitem_id;
                    $orderitemattribute->store ();
                }
            }
        }
    }

    protected function getMissingAttributes($missing){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('oia.*')->from('#__j2store_orders as o');
        $query->where('o.order_type <> '.$db->q('subscription'));
        $query->join('left', '#__j2store_orderitems as oi ON o.order_id = oi.order_id');
        $query->join('left', '#__j2store_orderitemattributes as oia ON oi.j2store_orderitem_id = oia.orderitem_id');
        $query->where('oi.orderitem_option_price <> 0');
        $query->where('o.j2store_order_id = '. $db->q($missing->parent_id));
        $query->where('oi.product_id = '. $db->q($missing->product_id));
        $query->where('oi.variant_id = '. $db->q($missing->variant_id));
        $query->where('oi.orderitem_attributes = '. $db->q($missing->orderitem_attributes));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    protected function getMissingAttributeOrders(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('o.parent_id, oi.order_id, oi.j2store_orderitem_id, oi.product_id, oi.variant_id, oi.orderitem_option_price, oi.orderitem_attributes')->from('#__j2store_orders as o');
        $query->where('o.order_type = '.$db->q('subscription'));
        $query->join('left', '#__j2store_orderitems as oi ON o.order_id = oi.order_id');
        $query->join('left', '#__j2store_orderitemattributes as oia ON oi.j2store_orderitem_id = oia.orderitem_id');
        $query->where('oi.orderitem_option_price <> 0');
        $query->where('oia.orderitem_id IS NULL');
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Delete shipping based on order_id
     * */
    public function deleteOrderShipping($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)->delete('#__j2store_ordershippings')->where('order_id = '.$db->q($order_id));
        try {
            return $db->setQuery($query)->execute();
        } catch (Exception $e) {
            return false;
            //do nothing. Because this is not harmful even if it fails.
        }
    }

    /**
     * Get other subscription html
     * */
    public function getOtherSubscriptionsAsHTML($sid, $user_id){
        $subscriptions = $this->getOtherSubscriptions($sid, $user_id);
        $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
        $html = '';
        $app = JFactory::getApplication();
        $app_id = $app->input->getInt('id');
        foreach ($subscriptions as $subscription){
            $html .= '<div class="item">';
            $html .= '<a href="index.php?option=com_j2store&view=app&task=view&appTask=viewSubscription&sid='.$subscription->j2store_subscription_id.'&id='.$app_id.'" target="_blank">
                        <span class="subscription-order_name-text">'.$subscription->orderitem_name.'</span>
                        </a> ';
            $status = $subsStatusObj->getStatus($subscription->status);
            $html .= '<span class="label '.$status->status_cssclass.' order-state-label">'.JText::_($status->status_name);
            $html .= '</span>';
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Get other subscriptions
     * */
    protected function getOtherSubscriptions($sid, $user_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('s.*')->from('#__j2store_subscriptions as s');

        $query->select($db->qn('#__j2store_orders.user_email'));
        $query->join('LEFT OUTER', '#__j2store_orders ON #__j2store_orders.order_id = s.order_id');

        $query->select('oi.orderitem_name');
        $query->join('LEFT', '#__j2store_ordersubscriptions as os ON os.orderitem_id = s.orderitem_id');
        $query->join('LEFT', '#__j2store_orderitems as oi ON oi.j2store_orderitem_id = os.orderitem_id');

        $query->where('s.j2store_subscription_id <> '.$db->q($sid));
        $query->where('s.user_id = '.$db->q($user_id));
        $query->order('s.j2store_subscription_id DESC');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * To calculate end date
     * */
    public function calculateSubscriptionEndDate($startDate, $period, $period_units, $length){
        return $this->createSubscriptionEndDate($startDate, $period, $period_units, $length);
    }

    /**
     * Accepted product type
     * */
    public function acceptedProductType(){
        return array('subscriptionproduct', 'variablesubscriptionproduct');
    }

    /**
     * check has subscription and trial in order
     * */
    public function checkHasSubscriptionAndTrialInOrder($order){
        $hasSubProductWithTrial = $hasSubProduct = 0;
        $items = $order->getItems();
        $accpetedProductTypes = $this->acceptedProductType();
        foreach ($items as $key => $item){
            if(in_array($item->product_type, $accpetedProductTypes)){
                $hasSubProduct = 1;
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
                    $hasSubProductWithTrial = 1;
                    break;
                }
            }
        }
        if($hasSubProduct){
            $j2StorePlugin = J2Store::plugin();
            $j2StorePlugin->event('AfterCheckHasSubscriptionAndTrialInOrder', array(&$hasSubProductWithTrial, $order));
        }

        return array('has_subscription_product' => $hasSubProduct, 'has_subscription_product_with_trial' => $hasSubProductWithTrial);
    }

    /**
     * Check expired card for any subscription
     * */
    public function hasExpiredCardForAnySubscription(){
        $user = JFactory::getUser();
        $user_id = $user->get('id');
        $has_expired_available_card_update = false;
        if($user_id){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('s.j2store_subscription_id, s.payment_method')->from('#__j2store_subscriptions as s');
            $query->where('s.user_id = '.$db->q($user_id));
            $query->where('s.status = '.$db->q('card_expired'));
            $db->setQuery($query);
            $subscriptions = $db->loadObjectList();
            foreach ($subscriptions as $subscription){
                $support_trial = $this->hasTrialSupport($subscription->payment_method);
                if($support_trial){
                    $has_expired_available_card_update = true;
                    break;
                }
            }
        }

        return $has_expired_available_card_update;
    }

    /**
     * Get billing cycle
     * */
    public function getBillingCycleOfSubscriptionOrderItem($order_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('os.billing_cycle')->from('#__j2store_ordersubscriptions as os');
        $query->select('s.billing_cycle as total_billing_cycle, s.subscription_length');
        $query->join('LEFT', '#__j2store_subscriptions as s ON s.j2store_subscription_id = os.subscription_id');
        $query->where('os.order_id = '.$db->q($order_id));
        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get subscription product name from subscription order
     * @param $subscription_id integer
     *
     * @return string
     * */
    public function getProductNameFromSubscriptionOrder($subscription_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('oi.*')->from('#__j2store_subscriptions as s');
        $query->join('LEFT', '#__j2store_orderitems as oi ON oi.order_id = s.subscription_order_id');
        $query->where('s.j2store_subscription_id = '.$db->q($subscription_id));
        $query->order('oi.j2store_orderitem_id ');
        $db->setQuery($query);
        $order_item = $db->loadObject();
        if(!empty($order_item->orderitem_name)){
            return $order_item->orderitem_name;
        }

        return '';
    }
}