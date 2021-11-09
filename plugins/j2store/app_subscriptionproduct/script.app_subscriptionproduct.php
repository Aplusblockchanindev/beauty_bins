<?php
/**
 * --------------------------------------------------------------------------------
 *  Subscription Products
 * --------------------------------------------------------------------------------
 * @package     Joomla 3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2016 J2Store . All rights reserved.
 * @license     GNU GPL v3 or later
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
class plgJ2StoreApp_subscriptionproductInstallerScript
{
    function preflight($type, $parent)
    {
        if (!JComponentHelper::isEnabled('com_j2store')) {
            Jerror::raiseWarning(null, 'J2Store not found. Please install J2Store before installing this plugin');
            return false;
        }
        jimport('joomla.filesystem.file');
        $version_file = JPATH_ADMINISTRATOR . '/components/com_j2store/version.php';
        if (JFile::exists($version_file)) {
            require_once($version_file);
            if (version_compare(J2STORE_VERSION, '3.2.27', 'lt')) {
                Jerror::raiseWarning(null, 'You need at least J2Store version 3.2.27 for this plugin to work');
                return false;
            }
        } else {
            Jerror::raiseWarning(null, 'J2Store not found or the version file is not found. Make sure that you have installed J2Store before installing this plugin');
            return false;
        }

        $db = JFactory::getDbo();
        // get the table list
        $tables = $db->getTableList();
        // get prefix
        $prefix = $db->getPrefix();

        if (in_array($prefix . 'j2store_orders', $tables)) {
            $fields = $db->getTableColumns('#__j2store_orders');
            if (!array_key_exists('order_type', $fields) || !array_key_exists('subscription_id', $fields)) {
                Jerror::raiseWarning(null, 'You need latest version of J2Store for this plugin to work');
                return false;
            }
        }

        if (!in_array($prefix . 'j2store_subscriptions', $tables)) {
            $query = "CREATE TABLE IF NOT EXISTS `#__j2store_subscriptions` (
                          `j2store_subscription_id` int(10) NOT NULL AUTO_INCREMENT,
                          `orderitem_id` int(10) NOT NULL,
                          `order_id` varchar(255) NOT NULL,
                          `subscription_order_id` varchar(255) NOT NULL,
                          `product_id` int(10) NOT NULL,
                          `variant_id` int(10) NOT NULL,
                          `start_on` datetime NOT NULL,
                          `end_on` datetime NOT NULL,
                          `trial_start_on` datetime NOT NULL,
                          `trial_end_on` datetime NOT NULL,
                          `billing_cycle` int(10) NOT NULL,
                          `period` varchar(100) NOT NULL,
                          `period_units` int(10) NOT NULL,
                          `subscription_length` int(10) NOT NULL,
                          `current_billing_cycle` int(10) NOT NULL,
                          `next_payment_on` datetime NOT NULL,
                          `payment_method` varchar(255) NOT NULL,
                          `renewal_amount` float(12,4) NOT NULL,
                          `created_on` datetime NOT NULL,
                          `created_by` int(10) NOT NULL,
                          `modified_on` datetime NOT NULL,
                          `modified_by` int(10) NOT NULL,
                          `status` varchar(100) NOT NULL,
                          `user_id` int(10) NOT NULL,
                          `renewal_process` varchar(100) NOT NULL,
                          `renewal_retry_on` TIMESTAMP NULL,
                          PRIMARY KEY (`j2store_subscription_id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
            $this->_executeQuery($query);
        }

        if (!in_array($prefix . 'j2store_ordersubscriptions', $tables)) {
            $query = "CREATE TABLE IF NOT EXISTS `#__j2store_ordersubscriptions` (
                          `j2store_ordersubscription_id` int(10) NOT NULL AUTO_INCREMENT,
                          `orderitem_id` int(10) NOT NULL,
                          `order_id` varchar(255) NOT NULL,
                          `term_start_on` datetime NOT NULL,
                          `term_end_on` datetime NOT NULL,
                          `trial_start_on` datetime NOT NULL,
                          `trial_end_on` datetime NOT NULL,
                          `billing_cycle` int(10) NOT NULL,
                          `schedule_next_payment` datetime NOT NULL,
                          `subscription_id` int(10) NOT NULL,
                          PRIMARY KEY (`j2store_ordersubscription_id`)
                       ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
            $this->_executeQuery($query);
        }

        //check for subscription_order_id
        if (in_array($prefix . 'j2store_subscriptions', $tables)) {
            $fields = $db->getTableColumns('#__j2store_subscriptions');
            if (!array_key_exists('subscription_order_id', $fields)) {
                $query = "ALTER TABLE #__j2store_subscriptions ADD `subscription_order_id` varchar(255) NOT NULL AFTER `order_id`";
                $this->_executeQuery($query);
            }

            //modify j2store_subscriptions table
            if (!array_key_exists('renewal_process', $fields)) {
                $query = "ALTER TABLE #__j2store_subscriptions ADD `renewal_process` varchar(100) NOT NULL AFTER `user_id`";
                $this->_executeQuery($query);
            }

            if (!array_key_exists('renewal_retry_on', $fields)) {
                $query = "ALTER TABLE #__j2store_subscriptions ADD `renewal_retry_on` TIMESTAMP NULL AFTER `renewal_process`";
                $this->_executeQuery($query);
            }
        }

        return true;
    }

    public function postflight($type, $parent){
        $this->_moveSource($parent);
        $this->_migrateDataToV2($parent);
    }
    /**
     * Method to move source files into
     * Products/view
     * @param object $parent
     */
    public function _moveSource($parent){
        $src = $parent->getParent()->getPath('source');
        //have to move the files in the path
        $source_path = $src.'/source/';
        if (is_dir($source_path)){
            //destination path
            $files = JFolder::files($source_path);
            $folders = JFolder::folders($source_path);
            foreach($folders as $folder){
                $current_folder = $source_path.$folder;
                if($folder == 'admin'){
                    $destination_path = JPATH_ADMINISTRATOR.'/components/com_j2store/';
                    $this->getAdminFolders($current_folder, $destination_path ,$parent );
                }
                if($folder == 'site'){
                    $destination_path = JPATH_SITE.'/components/com_j2store/';
                    $this->getSiteFolders($current_folder,$destination_path ,$parent);
                }
            }
        }
    }

    public function getAdminFolders($current_folder,$destination_path,$parent){
        $sfiles = JFolder::files($current_folder);
        $sfolders = JFolder::folders($current_folder);
        foreach($sfolders as $sfolder){
            if($sfolder == 'models') {
                $mdestination_path = $destination_path . 'models/';
                $mcurrent_folder = $current_folder . '/' . $sfolder . '/';
                $mfolders = JFolder::folders($mcurrent_folder);
                foreach($mfolders as $mfolder){
                    $mdestination_path .= $mfolder.'/';
                    $mcurrent_folder .= $mfolder.'/';
                    $bfiles = JFolder::files($mcurrent_folder);
                    foreach($bfiles as $bfile){
                        if (!JFile::move($mcurrent_folder.$bfile, $mdestination_path.$bfile) ) {
                            $parent->getParent()->abort('Could not move folder '.$mdestination_path.'Check permissions.');
                            return true;
                        }
                    }
                }

            }

            if($sfolder == 'views' ){
                $vdestination_path = $destination_path.'views/';
                $vcurrent_folder = $current_folder .'/'.$sfolder.'/';
                $mfolders = JFolder::folders($vcurrent_folder);
                foreach($mfolders as $mfolder){
                    $vmdestination_path =$vdestination_path.$mfolder.'/';
                    $vmcurrent_folder = $vcurrent_folder.$mfolder.'/';
                    $bfolders = JFolder::folders($vmcurrent_folder);
                    foreach($bfolders as $bfolder){
                        $vdestination_path =$vmdestination_path.$bfolder.'/';
                        $vcurrent_folder = $vmcurrent_folder.$bfolder.'/';
                        $bfiles = JFolder::files($vcurrent_folder);
                        foreach($bfiles as $bfile){
                            if (!JFile::move($vcurrent_folder.$bfile, $vdestination_path.$bfile) ) {
                                $parent->getParent()->abort('Could not move folder '.$vdestination_path.'Check permissions.');
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }

    public function getSiteFolders($current_folder,$destination_path ,$parent){

        $sfiles = JFolder::files($current_folder);
        $sfolders = JFolder::folders($current_folder);

        foreach($sfolders as $sfolder){
            if($sfolder =='views'){

                // make sure only product view is edited
                //if(in_array($sfolder, array('product'))){

                $vdestination_path = $destination_path.'views/';
                $vcurrent_folder = $current_folder .'/'.$sfolder.'/';
                $vfolders = JFolder::folders($vcurrent_folder);

                foreach($vfolders as $vfold){
                    $vsdestination_path =$vdestination_path .$vfold.'/';
                    $vscurrent_folder =$vcurrent_folder.$vfold.'/';
                    $vsfolders = JFolder::folders($vscurrent_folder);

                    foreach($vsfolders as $vsfold)
                        $vstdestination_path = $vsdestination_path.$vsfold.'/';
                    $vstcurrent_folder =	$vscurrent_folder.$vsfold.'/';
                    $vstfiles = JFolder::files($vstcurrent_folder);
                    foreach($vstfiles as $vfile){
                        if (!JFile::move($vstcurrent_folder.$vfile, $vstdestination_path.$vfile) ) {
                            $parent->getParent()->abort('Could not move folder '.$vstdestination_path.'Check permissions.');
                            return true;
                        }
                    }

                }
                //}
            }


            if($sfolder == 'templates' ){
                $tdestination_path = $destination_path.'templates/';
                $tcurrent_folder = $current_folder .'/'.$sfolder.'/';
                $tfolders = JFolder::folders($tcurrent_folder);

                foreach($tfolders as $tsfold){
                    $destination_folder = $tdestination_path.$tsfold.'/';
                    $tscurrent_folder = $tcurrent_folder.$tsfold.'/';
                    $tfiles = JFolder::files($tscurrent_folder);
                    foreach($tfiles as $tfile){
                        if (!JFile::move($tscurrent_folder.$tfile, $destination_folder.$tfile) ) {
                            $parent->getParent()->abort('Could not move folder '.$destination_folder.'Check permissions.');
                            return true;
                        }
                    }

                }
            }
        }
    }

    private function _executeQuery($query) {
        $db = JFactory::getDbo ();
        $db->setQuery ( $query );
        try {
            $db->execute ();
        } catch ( Exception $e ) {
            // do nothing. we dont want to fail the install process.
            echo $e;
        }
    }
    private function _exeselect($query) {
        $db = JFactory::getDbo ();
        $db->setQuery ( $query );
        $db->setQuery ( $query );
        $admins = $db->loadObjectList ();
        return $admins;
    }

    /**
     * To migrate the v1 data to v2
     * */
    protected function _migrateDataToV2($parent){
        new MigrationV2(true);
    }
}

/**
 * To migrate the v1 data to v2
 * */
class MigrationV2{
    public function __construct($migrate = false) {
        if($migrate){
            $this->migrateSubscriptionData();
        }
    }

    /**
     * migrate subscription data
     * */
    protected function migrateSubscriptionData(){
        $subscriptionsToMigrate = $this->getSubscriptionsNeedsToMigrate();
        if(count($subscriptionsToMigrate)){
            foreach ($subscriptionsToMigrate as $subscription){
                $order_id = $this->createNewSubscriptionOrder($subscription);
                if($order_id != ''){
                    $subscriptionForUpdate = F0FTable::getAnInstance('Subscription' ,'J2StoreTable')->getClone();
                    $subscriptionForUpdate->load(array('j2store_subscription_id' => $subscription->j2store_subscription_id));
                    $subscriptionForUpdate->subscription_order_id = $order_id;
                    $subscriptionForUpdate->store();
                    unset($subscriptionForUpdate);
                }
            }
        }
    }

    /**
     * Get subscription which needs to migrate
     * */
    private function getSubscriptionsNeedsToMigrate(){
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('subscription_order_id = \'\'');
        $db->setQuery($query);
        return $db->loadObjectList();
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
     * For creating new subscription order
     * */
    protected function createNewSubscriptionOrder($subscription){
        $config = J2Store::config();
        $order = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
        $order->load(array('order_id' => $subscription->order_id));

        $new_order = clone $order;

        $new_order->j2store_order_id = 0;
        $new_order->order_id = time();
        $new_order->is_update = 1;
        $new_order->order_fees = 0;
        $new_order->parent_id = $order->j2store_order_id;
        $new_order->subscription_id = $subscription->j2store_subscription_id;
        $new_order->order_type = 'subscription';
        $new_order->order_total = $subscription->renewal_amount;
        $new_order->order_subtotal = $subscription->renewal_amount;
        $new_order->order_subtotal_ex_tax = $subscription->renewal_amount;
        $new_order->order_tax = 0;
        $new_order->transaction_id = '';
        $new_order->order_state_id = 5;
        $new_order->created_on = $this->getCurrentDate();
        if($new_order->store()){
            if($new_order->is_update == 1) {
                $new_order->order_id = $this->generateOrderId($new_order->j2store_order_id, $new_order);
            }

            $new_order->generateInvoiceNumber();
            //generate a unique hash
            $new_order->token = JApplicationHelper::getHash($new_order->order_id);
            $new_order->is_update = 0;
            //save again so that the unique order id is saved.
            $new_order->store();
        }

        //Create Order info
        $orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable')->getClone();
        $orderInfo->load(array('order_id' => $order->order_id));
        $new_orderInfo = clone $orderInfo;
        $new_orderInfo->j2store_orderinfo_id = 0;
        $new_orderInfo->order_id = $new_order->order_id;
        $new_orderInfo->store();

        //Create Order item
        $items = $order->getItems();
        $hasTax = $taxTotal = 0;
        $discount = $this->getRenewalDiscountForSubscription($subscription, $subscription->renewal_amount);
        $this->saveRenewalSubscriptionsCoupons($new_order, $subscription, $subscription->renewal_amount);
        $discountedAmount = $subscription->renewal_amount-$discount;
        foreach ($items as $item){
            if($item->product_id == $subscription->product_id && $item->variant_id == $subscription->variant_id){
                $orderItem = F0FTable::getInstance('OrderItem' ,'J2StoreTable')->getClone();
                $orderItem->load(array('j2store_orderitem_id' => $item->j2store_orderitem_id));

                $new_orderItem = clone $orderItem;
                $new_orderItem->j2store_orderitem_id = 0;
                $new_orderItem->orderitem_type = 'subscription';
                $new_orderItem->order_id = $new_order->order_id;
                $new_orderItem->orderitem_discount = 0;
                $new_orderItem->orderitem_discount_tax = 0;
                $new_orderItem->store();
                if($new_orderItem->orderitem_taxprofile_id){
                    // For calculating tax
                    $storeaddress = J2Store::storeProfile ();
                    $taxModel =  F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
                    $taxModel->setShippingAddress($new_orderInfo->shipping_country_id, $new_orderInfo->shipping_zone_id, $new_orderInfo->shipping_zip);
                    $taxModel->setBillingAddress($new_orderInfo->billing_country_id, $new_orderInfo->billing_zone_id, $new_orderInfo->billing_zip);
                    $taxModel->setStoreAddress($storeaddress->get('country_id'), $storeaddress->get('zone_id'), $storeaddress->get('store_zip'));
                    $taxrates = $taxModel->getTaxwithRates($discountedAmount, $new_orderItem->orderitem_taxprofile_id);

                    if(count($taxrates->taxes)){
                        foreach($taxrates->taxes as $taxrate){
                            $orderItemTaxes = F0FTable::getInstance('OrderTax' ,'J2StoreTable')->getClone();
                            $orderItemTaxes->load(array('j2store_ordertax_id' => 0));
                            $orderItemTaxes->order_id = $new_order->order_id;
                            $orderItemTaxes->ordertax_title = $taxrate['name'];
                            $orderItemTaxes->ordertax_percent = $taxrate['rate'];
                            $orderItemTaxes->ordertax_amount = $taxrate['amount'];
                            $orderItemTaxes->store();
                            $hasTax = 1;
                        }
                        $taxTotal = $taxrates->taxtotal;
                    }
                }

                $attributes = $this->getOrderAttributes($item->j2store_orderitem_id);
                //save order attributes
                if(count($attributes)) {
                    $this->saveOrderItemAttributes($attributes, $new_orderItem);
                }
                break;
            }
        }
        $renewalAmount = $subscription->renewal_amount;
        if($hasTax){
            if(!$config->get ( 'config_including_tax', 0 )){
                $new_order->order_subtotal_ex_tax = $renewalAmount;
            } else {
                $new_order->order_subtotal_ex_tax = $renewalAmount-$taxTotal;
            }
            $new_order->order_tax = $taxTotal;
        }
        $renewalAmount = $discountedAmount+$taxTotal;


        $new_order->order_total = $renewalAmount;
        $new_order->order_subtotal = $renewalAmount;
        $new_order->store();

        return $new_order->order_id;
    }

    /**
     * Current date and time
     * */
    protected function getCurrentDate(){
        $tz = \JFactory::getConfig()->get('offset');
        $date = \JFactory::getDate(date('Y-m-d H:i:s'), $tz);
        return date('Y-m-d H:i:s', strtotime($date));
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
            $orderDiscount->discount_value = $discountObj->discount_value;
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
}