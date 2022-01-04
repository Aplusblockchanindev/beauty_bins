<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appcontroller.php');
require_once('Helper/subscription_status.php');
class J2StoreControllerAppSubscriptionproduct extends J2StoreAppController
{
    var $_element = 'app_subscriptionproduct';

    function __construct($config = array())
    {
        parent::__construct($config);
        //there is problem in loading of language
        //this code will fix the language loading problem
        $language = JFactory::getLanguage();
        $extension = 'plg_j2store' . '_' . $this->_element;
        $language->load($extension, JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load($extension, JPATH_ADMINISTRATOR, null, true);
    }

    protected function onBeforeGenericTask($task)
    {
        
        return $this->allowedTasks($task);
    }

    function allowedTasks($task){
        $allowed = array(
            'viewMySubscription',
            'manageSubscription',
            'viewSubscription',
            'cancelSubscription',
            'pauseSubscription',
            'changeSubscriptionStatus',
            'updateSubscription',
            'addSubscriptionHistory',
            'reCalculateSubscriptionOrder',
            'updateSubscriptionOrderItem',
            'updateSubscriptionOrderItemQuantity',
            'updateSubscriptionOrderItemName',
            'addAdditionalFeeForSubscriptionOrder',
//            'addShippingForSubscriptionOrder',
            'removeAddtionalFeeFromSubscriptionOrder',
            'removeShippingFromSubscriptionOrder',
            'updateSubscriptionShippingName',
            'updateSubscriptionOrderItemAttributes',
            'removeAttributeFromSubscriptionOrder',
            'moveAttributeFromParent',
            'getOtherSubscriptions',
            'export',
            'export_subscription_orders',
            'processSubscriptionPaymentCardUpdate',
            'updateSubscriptionPaymentCard',
            'updateRetryRenewalProcess'
        );
        $status = false;
        if(in_array($task, $allowed)){
            $status = true;
        }
        return $status;
    }

    /**
     * Load other subscriptions
     * */
    public function getOtherSubscriptions(){
        $html = '';
        $app = JFactory::getApplication();
        if($app->isAdmin()){
            $sid = $app->input->getInt('sid');
            $user_id = $app->input->getInt('user_id');
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $html = $model->getOtherSubscriptionsAsHTML($sid, $user_id);
        }
        echo $html;exit;
    }

    public function moveAttributeFromParent(){
        $app = JFactory::getApplication();
        if($app->isAdmin()){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->moveAttributeFromParentIfNotExists();
            echo "came";exit;
        }
    }

    /**
     * Change subscription status
     * */
    public function changeSubscriptionStatus(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('sid');
        $notify_customer = $app->input->getInt('notify_customer');
        $status = $app->input->getVar('status');
        if($app->isAdmin() && $id && $status != ''){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->changeSubscriptionStatusThroughBackend($id, $status, $notify_customer);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * Change subscription status
     * */
    public function updateRetryRenewalProcess(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('sid');
        if($app->isAdmin() && $id){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->updateRetryRenewalProcess($id);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * re-calculate subscription order
     * */
    public function reCalculateSubscriptionOrder(){
        $app = JFactory::getApplication();
        $order_id = $app->input->get('order_id', '');
        if($app->isAdmin() && $order_id != ''){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->reCalculateSubscriptionOrderThroughBackend($order_id);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * update subscription Order Item Price
     * */
    public function updateSubscriptionOrderItem(){
        $app = JFactory::getApplication();
        $order_item_id = $app->input->get('order_item_id', '');
        $order_item_price = $app->input->get('order_item_price', '');
        $order_item_tax_class_id = $app->input->get('order_item_tax_class_id', '');
        if($app->isAdmin() && $order_item_id){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->updateSubscriptionOrderItemPrice($order_item_id, $order_item_price, $order_item_tax_class_id);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * update subscription Order Item quantity
     * */
    public function updateSubscriptionOrderItemQuantity(){
        $app = JFactory::getApplication();
        $order_item_id = $app->input->get('order_item_id', '');
        $order_item_quantity = $app->input->get('order_item_quantity', 1);
        if($app->isAdmin() && $order_item_id){
            $order_item_quantity = (int)$order_item_quantity;
            if($order_item_quantity < 1) $order_item_quantity = 1;
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $returnResult = $model->updateSubscriptionOrderItemQuantity($order_item_id, $order_item_quantity);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * update subscription Order Item Name
     * */
    public function updateSubscriptionOrderItemName(){
        $app = JFactory::getApplication();
        $order_item_id = $app->input->getInt('order_item_id', '');
        $order_item_name = $app->input->getVar('order_item_name', '');
        if($app->isAdmin() && $order_item_id && $order_item_name != ''){
            $orderItem = F0FTable::getInstance('OrderItem', 'J2StoreTable')->getClone();
            $orderItem->load(array('j2store_orderitem_id' => $order_item_id));
            $orderItem->orderitem_name = $order_item_name;
            if($orderItem->store()){
                if($orderItem->store()){
                    $returnResult['status'] = 1;
                    $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATED_SUCCESSFULLY').'</p>';
                } else {
                    $returnResult['status'] = 0;
                    $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATE_FAILED').'</p>';
                }
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * update subscription Order Item Attributes
     * */
    public function updateSubscriptionOrderItemAttributes(){
        $app = JFactory::getApplication();
        $order_item_id = $app->input->getInt('order_item_id', '');
        $order_item_attribute_ids = $app->input->get('orderitem_attribute_id', array(), 'array');

        if($app->isAdmin() && $order_item_id && !empty($order_item_attribute_ids)){
            $order_item_attribute_names = $app->input->get('orderitem_attribute_name', array(), 'array');
            $order_item_attribute_values = $app->input->get('orderitem_attribute_value', array(), 'array');
            $order_item_attribute_prefixs = $app->input->get('orderitem_attribute_prefix', array(), 'array');
            $order_item_attribute_prices = $app->input->get('orderitem_attribute_price', array(), 'array');
            foreach ($order_item_attribute_ids as $order_item_attribute_id){
                $id = $order_item_attribute_id;
                if(!empty($order_item_attribute_names[$id]) && !empty($order_item_attribute_values[$id]) && !empty($order_item_attribute_prefixs[$id]) && !empty($order_item_attribute_prices[$id])){
                    $orderItemAttribute = F0FTable::getInstance('OrderItemAttribute', 'J2StoreTable')->getClone();
                    $orderItemAttribute->load($order_item_attribute_id);
                    $orderItemAttribute->orderitemattribute_name = $order_item_attribute_names[$id];
                    $orderItemAttribute->orderitemattribute_value = $order_item_attribute_values[$id];
                    $orderItemAttribute->orderitemattribute_prefix = $order_item_attribute_prefixs[$id];
                    $orderItemAttribute->orderitemattribute_price = $order_item_attribute_prices[$id];
                    $orderItemAttribute->store();
                }
            }

            $result = $this->recalculateAttributeValues($order_item_id);
            if($result){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATED_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATE_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * for recalculating attribute values
     * */
    protected function recalculateAttributeValues($order_item_id){
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $attributes = $model->getSubscriptionOrderItemAttributes($order_item_id);
        $totalValue = 0;
        if(!empty($attributes)){
            foreach ($attributes as $attribute){
                $totalValue = $totalValue+($attribute->orderitemattribute_prefix.$attribute->orderitemattribute_price);
            }
        }
        $orderItem = F0FTable::getInstance('OrderItem', 'J2StoreTable')->getClone();
        $orderItem->load($order_item_id);
        $orderItem->orderitem_option_price = $totalValue;
        return $orderItem->store();
    }

    /**
     * Remove subscription Attribute
     * */
    public function removeAttributeFromSubscriptionOrder(){
        $app = JFactory::getApplication();
        $attribute_id = $app->input->getInt('attribute_id', '');
        if($app->isAdmin() && $attribute_id){
            $orderItemAttribute = F0FTable::getInstance('OrderItemAttribute', 'J2StoreTable')->getClone();
            $orderItemAttribute->load($attribute_id);
            $order_item_id = $orderItemAttribute->orderitem_id;
            if($orderItemAttribute->delete()){
                $result = $this->recalculateAttributeValues($order_item_id);
                if($result){
                    $returnResult['status'] = 1;
                    $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATED_SUCCESSFULLY').'</p>';
                } else {
                    $returnResult['status'] = 0;
                    $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDERITEM_UPDATE_FAILED').'</p>';
                }
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_ATTRIBUTE_DELETE_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * Remove subscription additional fee
     * */
    public function removeAddtionalFeeFromSubscriptionOrder(){
        $app = JFactory::getApplication();
        $fee_id = $app->input->getInt('fee_id', '');
        if($app->isAdmin() && $fee_id){
            $orderFee = F0FTable::getInstance('OrderFee', 'J2StoreTable')->getClone();
            $orderFee->load($fee_id);
            if($orderFee->delete()){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_FEE_DELETED_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_FEE_DELETE_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * Remove Shipping
     * */
    public function removeShippingFromSubscriptionOrder(){
        $app = JFactory::getApplication();
        $order_id = $app->input->getVar('order_id', '');
        if($app->isAdmin() && $order_id != ''){
            $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
            $order->load(array('order_id' => $order_id));

            //Remove shipping from orderShipping table
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $removedShipping = $model->deleteOrderShipping($order_id);

            if($removedShipping){
                //Update shipping details in order table
                $order->load(array('order_id' => $order_id));
                $order->order_shipping = 0;
                $order->order_shipping_tax = 0;
                $order->store();

                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_SHIPPING_DELETED_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_SHIPPING_DELETE_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * Update Shipping name
     * */
    public function updateSubscriptionShippingName(){
        $app = JFactory::getApplication();
        $order_id = $app->input->getVar('order_id', '');
        $shipping_name = $app->input->getVar('shipping_name', '');
        if($app->isAdmin() && $order_id != '' && $shipping_name != ''){
            $orderShipping = F0FTable::getInstance('OrderShipping', 'J2StoreTable')->getClone();
            $orderShipping->load(array('order_id' => $order_id));
            $orderShipping->ordershipping_name = $shipping_name;

            if($orderShipping->store()){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_SHIPPING_UPDATED_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_SHIPPING_UPDATED_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * add subscription Shipping
     * */
    public function addShippingForSubscriptionOrder(){
        $app = JFactory::getApplication();
        $order_id = $app->input->get('order_id', '');
        $type = $app->input->getVar('type', '');
        $name = $app->input->getVar('name', '');
        $price = $app->input->getVar('price', 0);
        $extra = $app->input->getVar('extra', 0);
        $tax_class_id = $app->input->getInt('tax_class_id', 0);
        if($app->isAdmin() && $price > 0 && $name != '' && $order_id != ''){
            $config = J2Store::config();
            $ordershipping_tax = 0;
            if($tax_class_id > 0) {
                $taxModel = F0FModel::getTmpInstance('TaxProfiles', 'J2StoreModel');
                $TaxRates = $taxModel->getTaxwithRates(($price + $extra), $tax_class_id, $config->get('config_including_tax', 0));
                if (isset($TaxRates->taxtotal)) {
                    $ordershipping_tax = $TaxRates->taxtotal;
                }
            }

            $shipping_table = F0FTable::getInstance ( 'OrderShipping', 'J2StoreTable' )->getClone ();
            $order = F0FTable::getInstance ( 'Order', 'J2StoreTable' )->getClone ();

            //Remove shipping from orderShipping table before adding new
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $model->deleteOrderShipping($order_id);

            $shipping_table->load(0);
            $shipping_table->order_id = $order_id;
            $shipping_table->ordershipping_type = $type;
            $shipping_table->ordershipping_name = $name;
            $shipping_table->ordershipping_price = $price;
            $shipping_table->ordershipping_extra = $extra;
            $shipping_table->ordershipping_tax = $ordershipping_tax;
            if($shipping_table->store ()){
                //Update shipping details in order table
                $order->load(array('order_id' => $order_id));
                $order->order_shipping = $price + $extra;
                $order->order_shipping_tax = $ordershipping_tax;
                $order->store();
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_ADDED_SHIPPING_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_ADDED_SHIPPING_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * add subscription additional fee
     * */
    public function addAdditionalFeeForSubscriptionOrder(){
        $app = JFactory::getApplication();
        $order_id = $app->input->get('order_id', '');
        $name = $app->input->getVar('name', '');
        $amount = $app->input->getVar('amount', 0);
        $tax_class_id = $app->input->getInt('tax_class_id', 0);
        if($app->isAdmin() && $amount > 0 && $name != '' && $order_id != ''){
            $fee_table = F0FTable::getInstance ( 'Orderfee', 'J2StoreTable' )->getClone ();
            $fee_table->load(0);
            $fee_table->order_id = $order_id;
            $fee_table->name = $name;
            $fee_table->amount = $amount;
            $fee_table->tax_class_id = $tax_class_id;
            $fee_table->taxable = ($tax_class_id)? 1: 0;
            $fee_table->tax_data = json_encode(array());
            $fee_table->fee_type = '';
            if($fee_table->store ()){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_ADDED_FEE_SUCCESSFULLY').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_ORDER_ADDED_FEE_FAILED').'</p>';
            }
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * add subscription history
     * */
    public function addSubscriptionHistory(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('sid');
        $note = $app->input->getHtml('note', '');
        $history_type = $app->input->getVar('history_type', '');
        if($app->isAdmin() && $id && $note != ''){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            if($history_type == ''){
                $history_type = 'private';
            } else {
                $history_type = 'customer';
            }
            $returnResult = $model->addSubscriptionHistoryManually($id, $note, $history_type);
        } else {
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_INVALID_ACCESS').'</p>';
        }
        echo json_encode($returnResult);exit;
    }

    /**
     * Update Subscription
     * */
    public function updateSubscription(){
        $app = JFactory::getApplication();
        if($app->isSite()){
            return ;
        }
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $result = $model->updateSubscriptionThoughBackend();
        echo json_encode($result);exit;
    }

    /**
     * Cancel the subscription
     * */
    public function cancelSubscription(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('sid');
        if($id){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $result = $model->cancelSubscription($id);
            if($result){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_CANCELED_SUCCESS').'</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_CANCELED_FALIED').'</p>';
            }

            echo json_encode($returnResult);exit;
        }
    }

    /**
     * Pause the subscription - request email
     * */
    public function pauseSubscription(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('sid');
        $msg_pause = $app->input->getString("msg_pause");
        
        if($id){
            F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            $result = $model->pauseSubscription($id,$msg_pause);

            if($result){
                $returnResult['status'] = 1;
                $returnResult['message'] = '<p class="text-success">Pause request has been sent successfully!</p>';
            } else {
                $returnResult['status'] = 0;
                $returnResult['message'] = '<p class="text-warning">Unable to send request to the Admin</p>';
            }

            // $returnResult['status'] = 1;
            // $returnResult['message'] = $msg_pause;

            echo json_encode($returnResult);exit;
        }
    }

    /**
     * For update subscription payment card
     * */
    public function updateSubscriptionPaymentCard(){
        $app = \JFactory::getApplication();
        $id = $app->input->getInt('sid');
        $app_id = $app->input->getInt('id');
        $document = JFactory::getDocument();
        $document->addScript(JUri::root().'media/plg_j2store_app_subscriptionproduct/js/script.js');
        if($id) {
            F0FModel::addIncludePath(JPATH_SITE . '/plugins/j2store/' . $this->_element . '/' . $this->_element . '/models');
            $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');

            $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
            $subscription->load(array('j2store_subscription_id' => $id));
            $update_card  = $model_app->isDisplayCardUpdate($subscription);
            if($subscription->j2store_subscription_id && (in_array($subscription->status, array('card_expired')) || $update_card)){
                $user = JFactory::getUser();
                $user_id = $user->get('id');
                if($subscription->user_id != $user_id){
                    echo '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTION_INVALID').'</p>';
                } else {
                    $order = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
                    $order->load(array('order_id' => $subscription->subscription_order_id));

                    if(!(isset($order->j2store_order_id) && $order->j2store_order_id)){
                        echo '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_SUBSCRIPTION_ORDER').'</p>';
                    } else {
                        $vars = new stdClass();
                        $vars->subscription = $subscription;
                        $vars->app_id = $app_id;
                        $vars->order = $order;
                        $view = $this->getView( 'Apps', 'html' );
                        $view->setModel($model_app, true );
                        $view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
                        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/j2store/'.$this->_element;
                        $view->addTemplatePath($templatePath);
                        $view->set('vars',$vars);
                        $view->setLayout('update_card_form');
                        $view->display();
                    }
                }
            } else {
                echo '<p class="text-warning">'.JText::_('J2STORE_SUBSCRIPTION_INVALID').'</p>';
            }
        }
    }

    /**
     * For process subscription payment card update
     * */
    public function processSubscriptionPaymentCardUpdate(){
        $app = \JFactory::getApplication();
        $id = $app->input->getInt('sid');
        $app_id = $app->input->getInt('id');
        $values = $app->input->getArray($_POST);
        $json = array();
        if($id) {
            F0FModel::addIncludePath(JPATH_SITE . '/plugins/j2store/' . $this->_element . '/' . $this->_element . '/models');
            $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');

            $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
            $subscription->load(array('j2store_subscription_id' => $id));
            $update_card  = $model_app->isDisplayCardUpdate($subscription);
            if($subscription->j2store_subscription_id && (in_array($subscription->status, array('card_expired')) || $update_card)){
                $user = JFactory::getUser();
                $user_id = $user->get('id');
                if($subscription->user_id != $user_id){
                    $json['error'] = JText::_('J2STORE_SUBSCRIPTION_INVALID');
                } else {
                    $order = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
                    $order->load(array('order_id' => $subscription->subscription_order_id));
                    //$app->setUserState( 'j2store.order_id', $order->order_id );
                    $app->setUserState( 'j2store.order_id', '');
                    if(!(isset($order->j2store_order_id) && $order->j2store_order_id)){
                        $json['error'] = JText::_('J2STORE_SUBSCRIPTIONAPP_INVALID_SUBSCRIPTION_ORDER');
                    } else {
                        //validate the selected payment
                        try {
                            $this->validateSelectPayment($subscription->payment_method, $values);
                        } catch (Exception $e) {
                            $json['error'] = $e->getMessage();
                        }
                    }
                }
            } else {
                $json['error'] = JText::_('J2STORE_SUBSCRIPTION_INVALID');
            }
        }
        if(isset($json['error']) && !empty($json['error'])){
            $returnResult['status'] = 0;
            $returnResult['message'] = '<p class="text-warning">'.$json['error'].'</p>';
        } else {
            $returnResult['status'] = 1;
            $values = array();
            $values['order_id'] = $order->order_id;
            $values['orderpayment_id'] = $order->j2store_order_id;
            $values['orderpayment_amount'] = 0;
            $values['order'] = $order;

            $results = $app->triggerEvent( "onJ2StorePrePayment", array( $subscription->payment_method, $values));

            // Display whatever comes back from Payment Plugin for the onPrePayment
            $html = "";
            for ($i=0; $i<count($results); $i++)
            {
                $html .= $results[$i];
            }

            $returnResult['message'] = $html;
        }
        echo json_encode($returnResult);
        $app->close();
    }

    function validateSelectPayment($payment_plugin, $values) {
        $response = array ();
        $response ['msg'] = '';
        $response ['error'] = '';

        $app = JFactory::getApplication ();
        JPluginHelper::importPlugin ( 'j2store' );

        // verify the form data
        $results = array ();
        $results = $app->triggerEvent ( "onJ2StoreGetPaymentFormVerify", array (
            $payment_plugin,
            $values
        ) );

        for($i = 0; $i < count ( $results ); $i ++) {
            $result = $results [$i];
            if (! empty ( $result->error )) {
                $response ['msg'] = $result->message;
                $response ['error'] = '1';
            }
        }
        if ($response ['error']) {
            throw new Exception ( $response ['msg'] );
            return false;
        } else {
            return true;
        }
        return false;
    }

    function viewMySubscription(){
        $app = JFactory::getApplication();
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $modelApp = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');
        $app_details = $modelApp->getAppDatails();
        $vars = new stdClass();
        $vars->id = $app_details->extension_id;
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        $vars->subscription = $model->getItem($app->input->get('sid'));
        $vars->order_item = $model->getOrderItem($app->input->get('sid'));
        $user_id = JFactory::getUser()->get('id');
        if($vars->subscription->user_id && $vars->subscription->user_id != $user_id){
            $vars->subscription = array();
        }
        if(isset($vars->subscription->product_id)){
            $vars->product = J2Store::product()->setId($vars->subscription->product_id)->getProduct();

            $vars->order = F0FTable::getAnInstance('Order','J2StoreTable');
            $vars->order->load(array(
                'order_id' => $vars->subscription->order_id
            ));

            $subscriptionOrder = F0FTable::getAnInstance('Order','J2StoreTable')->getClone();
            $subscriptionOrder->load(array(
                'order_id' => $vars->subscription->subscription_order_id
            ));
            $vars->subscriptionOrder = $subscriptionOrder;

            if(isset($vars->order->j2store_order_id) && $vars->order->j2store_order_id){
                $vars->relatedOrders = $modelApp->getRelatedRenewalOrders($vars->order->j2store_order_id, $vars->subscription->j2store_subscription_id);
            }

            $vars->subscriptionHistory = $this->getSubscriptionHistory($app->input->get('sid'), 1);
        }

        $view = $this->getView( 'Apps', 'html' );
        $view->setModel($model_app, true );
        $view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/j2store/'.$this->_element;
        $view->addTemplatePath($templatePath);
        $view->set('vars',$vars);
        $view->setLayout('view_mysusbcription');
        $view->display();
    }

    function getSearchproducts(){
        $app = JFactory::getApplication();
        $q = $app->input->post->get('q');
        //index.php?option=com_j2store&view=apps&task=view&layout=view&id=10053
        $json = array();
        $json = $this->getProducts($q);
        echo json_encode($json);
        $app->close();
    }

    public function getProducts($q){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('#__j2store_products.j2store_product_id');
        $query->from('#__j2store_products');
        $query->join('left','#__j2store_variants ON #__j2store_products.j2store_product_id = #__j2store_variants.product_id');
        $query->where('LOWER(#__j2store_variants.sku) LIKE '.$db->Quote( '%'.$db->escape( $q, true ).'%', false ));
        $query->where("#__j2store_products.product_type IN ('simple' , 'configurable','downloadable')");
        $query->where('#__j2store_products.enabled=1');
        $query->where('#__j2store_products.visibility=1');
        $query->where('#__j2store_variants.is_master=1');
        //experimental. Load only products that do not have options.
        $query->join('LEFT', '#__j2store_product_options ON #__j2store_products.j2store_product_id = #__j2store_product_options.product_id')
            ->where('#__j2store_product_options.product_id IS NULL')
        ;
        // $query->where('#__j2store_products.has_options IS NULL');
        $db->setQuery($query);
        $products = $db->loadObjectList();

        //print_r($products);
        $products = $this->processProducts($products);
        return $products;
    }

    /**
     * Get Subscription History
     * */
    function getSubscriptionHistory($id, $frontend = 0){
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        return $model->getSubscriptionHistory($id, array(), $frontend);
    }

    function processProducts($products){
        $proc_product = array();
        foreach($products as $key=>$product){
            $prod = J2Store::product()->setId($product->j2store_product_id)->getProduct();
            $proc_product[$key]['product_name'] = $prod->product_name;
            $proc_product[$key]['j2store_product_id'] = $prod->j2store_product_id;
        }
        return $proc_product;
    }

    /**
     * View Subscription
     * */
    function viewSubscription(){
        $app = JFactory::getApplication();
        if($app->isSite()){
            return false;
        }
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $modelApp = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');
        $vars = new stdClass();
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        $vars->subscription = $model->getItem($app->input->get('sid'));
        $vars->order_item = $model->getOrderItem($app->input->get('sid'));
        $vars->model = $modelApp;
        if(isset($vars->subscription->product_id)){
            $vars->product = J2Store::product()->setId($vars->subscription->product_id)->getProduct();

            $vars->order = F0FTable::getAnInstance('Order','J2StoreTable')->getClone();
            $vars->order->load(array(
                'order_id' => $vars->subscription->order_id
            ));

            $subscriptionOrder = F0FTable::getAnInstance('Order','J2StoreTable')->getClone();
            $subscriptionOrder->load(array(
                'order_id' => $vars->subscription->subscription_order_id
            ));
            $vars->subscriptionOrder = $subscriptionOrder;

            if(isset($vars->order->j2store_order_id) && $vars->order->j2store_order_id){
                $vars->relatedOrders = $modelApp->getRelatedRenewalOrders($vars->order->j2store_order_id, $vars->subscription->j2store_subscription_id);
            }
            $vars->subscriptionHistory = $this->getSubscriptionHistory($app->input->get('sid'));
            $vars->fee_ids = $modelApp->getAdditionalFeesIds($vars->subscription->subscription_order_id);
        }

        $applayout = "index.php?option=com_j2store&view=app&task=view&id=".$app->input->get('id');
        $listinglayout = "index.php?option=com_j2store&view=app&task=view&appTask=manageSubscription&id=".$app->input->get('id');
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_MANAGE_SUBSCRIPTION', $listinglayout);
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', $applayout);

        $view = $this->getView( 'Apps', 'html' );
        $view->setModel($model_app, true );
        $view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/j2store/'.$this->_element;
        $view->addTemplatePath($templatePath);
        $view->set('vars',$vars);
        $view->setLayout('view_susbcription');
        $view->display();
    }

    /**
     * Subscription listing
     * */
    function manageSubscription(){

        $app = JFactory::getApplication();
        if($app->isSite()){
            return false;
        }
        $vars = new stdClass();
        $data = $app->input->getArray($_POST);

        $option = 'com_j2store';
        $ns = $option.'.app.'.$this->_element;
        //form
        $form = array();
        $form['action'] = "index.php?option=com_j2store&view=app&task=view&id={$data['id']}";
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');

        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        // get Queue list
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        //$model->setState('queue_type', $this->_element);

        $limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
        $limitstart	= $app->getUserStateFromRequest( $ns.'.limitstart', 'limitstart', 0, 'int' );
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $filter_order_Dir =  $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );
        $filter_order	= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'j2store_subscription_id',	'cmd' );
        $search = $app->input->getString('search',  $model->getState('search', ''));
        $subscription_count = $app->input->getString('subscription_count',  $model->getState('subscription_count', ''));
        $model->setState('limit', $limit);
        $model->setState('limitstart', $limitstart);
        $model->setState('filter_order_Dir', $filter_order_Dir);
        $model->setState('filter_order', $filter_order);
        $model->setState('search', $search);
        $model->setState('subscription_count', $subscription_count);
        $filter_status = $app->input->getString('status',  $model->getState('status', ''));
        $model->setState('status', $filter_status);
        $vars->pagination = $model->getPagination();
        $vars->state =  $model->getState();
        $vars->subscription = $model->getList();
        $vars->last_renewals = $model->getLast7Renewals();
        $vars->upcoming_renewals = $model->getUpComingRenewals();

        $view = $this->getView( 'Apps', 'html' );
        $view->setModel($model_app, true );
        $view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/j2store/'.$this->_element;
        $view->addTemplatePath($templatePath);
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', $form['action']);
        JToolBarHelper::custom('export', 'upload', 'upload', JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_EXPORT'), false);
        JToolBarHelper::custom('export_subscription_orders', 'upload', 'upload', JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_ORDER_EXPORT'), false);

        $vars->form2 =  $form;
        $vars->limit = $limit;
        $vars->limitstart = $limitstart;
        $vars->id = $data['id'];
        $view->set('vars',$vars);
        $view->setLayout('manage_susbcription');
        $view->display();
    }

    /**
     * Export the subscriptions
     * */
    public function export(){
        $app = JFactory::getApplication();
        if($app->isSite()){
            return false;
        }
        $data = $app->input->getArray($_POST);

        $option = 'com_j2store';
        $ns = $option.'.app.'.$this->_element;
        //form
        $form = array();
        $form['action'] = "index.php?option=com_j2store&view=app&task=view&id={$data['id']}";
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');

        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        // get Queue list
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        $filter_order_Dir =  $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );
        $filter_order	= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'j2store_subscription_id',	'cmd' );
        $search = $app->input->getString('search',  $model->getState('search', ''));
        $subscription_count = $app->input->getString('subscription_count',  $model->getState('subscription_count', ''));
        $model->setState('filter_order_Dir', $filter_order_Dir);
        $model->setState('filter_order', $filter_order);
        $model->setState('search', $search);
        $model->setState('subscription_count', $subscription_count);
        $filter_status = $app->input->getString('status',  $model->getState('status', ''));
        $model->setState('status', $filter_status);
        $subscriptions = $model->getList();
        $filename = "subscription_csv_".time().'.csv';
        $fp = fopen('php://output', 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        $dataForCSV = $this->subscriptionExportFields($subscriptions);
        fputcsv($fp, $dataForCSV['headers']);
        if(is_array($dataForCSV['content'])){
            foreach ($dataForCSV['content'] as $content){
                fputcsv($fp, $content);
            }
        }
        exit;
    }

    /**
     * Export the subscriptions
     * */
    public function export_subscription_orders(){
        $app = JFactory::getApplication();
        if($app->isSite()){
            return false;
        }
        $data = $app->input->getArray($_POST);

        $option = 'com_j2store';
        $ns = $option.'.app.'.$this->_element;
        //form
        $form = array();
        $form['action'] = "index.php?option=com_j2store&view=app&task=view&id={$data['id']}";
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');

        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        // get Queue list
        $model = F0FModel::getTmpInstance('Subscriptions', 'J2StoreModel');
        $filter_order_Dir =  $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );
        $filter_order	= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'j2store_subscription_id',	'cmd' );
        $search = $app->input->getString('search',  $model->getState('search', ''));
        $subscription_count = $app->input->getString('subscription_count',  $model->getState('subscription_count', ''));
        $model->setState('filter_order_Dir', $filter_order_Dir);
        $model->setState('filter_order', $filter_order);
        $model->setState('search', $search);
        $model->setState('subscription_count', $subscription_count);
        $filter_status = $app->input->getString('status',  $model->getState('status', ''));
        $model->setState('status', $filter_status);
        $subscriptions = $model->getList();
        $filename = "subscription_orders_csv_".time().'.csv';
        $fp = fopen('php://output', 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        $dataForCSV = $this->subscriptionExportFieldsWithOrders($subscriptions);
        fputcsv($fp, $dataForCSV['headers']);
        if(is_array($dataForCSV['content'])){
            foreach ($dataForCSV['content'] as $content){
                fputcsv($fp, $content);
            }
        }
        exit;
    }

    // Get Trash pickup data
    public function get_trash_pickup_info($order){
        $orderinfo = $order->getOrderInformation();
        // var_dump($$order->order_id);
        // var_dump($orderinfo->all_billing);
        $trash_pickup = "";
        if(isset(json_decode($orderinfo->all_billing)->dayoftrashpickup->value))
        {
            $trash_pickup = json_decode($orderinfo->all_billing)->dayoftrashpickup->value;
        }        
        return $trash_pickup;

    }

    // Get Number of Bins
    public function get_number_of_bins($order){
        $number_bins = "";
        $item_ind = 0;
        // var_dump("this is for the item: ".$item_ind);
        // var_dump($$order);
        $items = $order->getItems();
        foreach($items as $item){
            $item_ind++;
            if(isset($item->orderitemattributes))
            {
                $number_bins = $item->orderitemattributes[0]->orderitemattribute_value; 
            }
        }
        return $number_bins;

    }

    /**
     * get Subscription Export fields with orders
     * */
    protected function subscriptionExportFieldsWithOrders($subscriptions){
        $data = array();
        $data['headers'] = array('j2store_subscription_id' => JText::_('J2STORE_SUBSCRIPTION_ID'),
            'orderitem_name' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'),
            'status' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS'),
            'order_id' => JText::_('J2STORE_EMAILTEMPLATE_TAG_ORDERID'),
            'order_type' => JText::_('J2STORE_SUBSCRIPTION_RELATIONSHIP'),
            'created_on' => JText::_('J2STORE_SUBSCRIPTION_DATE'),
            'order_state_id' => JText::_('J2STORE_EMAILTEMPLATE_ORDERSTATUS'),
            'order_total' => JText::_('J2STORE_CART_GRANDTOTAL'),
            'trash_pickup' => 'Day of Trash Pickup',
            'number_bins' => 'Number of Bins',
       );
        if(count($subscriptions)){
            $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
            $tz = JFactory::getConfig()->get('offset');
            $j2_params = J2Store::config();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            foreach ($subscriptions as $key => $subscription){
                $parentOrder = F0FTable::getAnInstance('Order','J2StoreTable');
                $parentOrder->load(array(
                    'order_id' => $subscription->order_id
                ));
                $row_key = $key.'_'.$parentOrder->j2store_order_id;

                // Day of Trash pickup, Number of Bins - Edited by DC web
                
                $trash_pickup = $this->get_trash_pickup_info($parentOrder);
                $number_bins = $this->get_number_of_bins($parentOrder);


                foreach ($data['headers'] as $field => $fieldHeader){
                    if(in_array($field, array('j2store_subscription_id', 'orderitem_name', 'status'))){
                        if($field == 'status') {
                            $status = $subsStatusObj->getStatus($subscription->$field);
                            $data['content'][$row_key][] = JText::_($status->status_name);
                        } else {
                            $data['content'][$row_key][] = $subscription->$field;
                        }
                    } else {
                        if($field == 'order_type') {
                            if($parentOrder->parent_id){
                                $data['content'][$row_key][] = JText::_('J2STORE_SUBSCRIPTION_RENEWAL_ORDER');
                            } else {
                                $data['content'][$row_key][] = JText::_('J2STORE_SUBSCRIPTION_PARENT_ORDER');
                            }
                        } else if($field == 'created_on') {
                            $date = JFactory::getDate($parentOrder->$field, $tz);
                            $data['content'][$row_key][] = $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                        } else if($field == 'order_state_id') {
                            $orderStatus = F0FTable::getAnInstance('OrderStatus','J2StoreTable')->getClone();
                            $orderStatus->load(array(
                                'j2store_orderstatus_id' => $parentOrder->$field
                            ));
                            $data['content'][$row_key][] = JText::_($orderStatus->orderstatus_name);
                        } else if($field == 'order_total') {
                            $data['content'][$row_key][] = J2Store::currency()->format($parentOrder->$field, $parentOrder->currency_code, $parentOrder->currency_value);
                        } else if($field == 'trash_pickup'){
                            $data['content'][$row_key][] = $trash_pickup;
                        } else if($field == 'number_bins'){
                            $data['content'][$row_key][] = $number_bins;
                        } else {
                            $data['content'][$row_key][] = $parentOrder->$field;
                        }
                    }
                }
                $relatedOrders = $model->getRelatedRenewalOrders($parentOrder->j2store_order_id, $subscription->j2store_subscription_id);
                foreach ($relatedOrders as $relatedOrder){
                    // $orderinfo = $relatedOrder->getOrderInformation();
                    
                    $row_key = $key.'_'.$relatedOrder->j2store_order_id;
                    foreach ($data['headers'] as $field => $fieldHeader){
                        if(in_array($field, array('j2store_subscription_id', 'orderitem_name', 'status'))){
                            /*if($field == 'status') {
                                $status = $subsStatusObj->getStatus($subscription->$field);
                                $data['content'][$row_key][] = JText::_($status->status_name);
                            } else {
                                $data['content'][$row_key][] = $subscription->$field;
                            }*/
                            $data['content'][$row_key][] = '';
                        } else {
                            if($field == 'order_type') {
                                if($relatedOrder->parent_id){
                                    $data['content'][$row_key][] = JText::_('J2STORE_SUBSCRIPTION_RENEWAL_ORDER');
                                } else {
                                    $data['content'][$row_key][] = JText::_('J2STORE_SUBSCRIPTION_PARENT_ORDER');
                                }
                            } else if($field == 'created_on') {
                                $date = JFactory::getDate($relatedOrder->$field, $tz);
                                $data['content'][$row_key][] = $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                            } else if($field == 'order_state_id') {
                                $orderStatus = F0FTable::getAnInstance('OrderStatus','J2StoreTable')->getClone();
                                $orderStatus->load(array(
                                    'j2store_orderstatus_id' => $relatedOrder->$field
                                ));
                                $data['content'][$row_key][] = JText::_($orderStatus->orderstatus_name);
                            } else if($field == 'order_total') {
                                $data['content'][$row_key][] = J2Store::currency()->format($relatedOrder->$field, $relatedOrder->currency_code, $relatedOrder->currency_value);
                            } else if($field == 'trash_pickup'){
                                $data['content'][$row_key][] = $trash_pickup;
                            } else if($field == 'number_bins'){
                                $data['content'][$row_key][] = $number_bins;
                            } else {
                                    $data['content'][$row_key][] = $relatedOrder->$field;
                            }
                        }
                    }
                }
                // $parentOrder->_orderinfo = null;
            }

        } else {
            $data['content'] = JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_NO_SUBSCRIPTION_FOUND');
        }

        return $data;
    }

    /**
     * get Subscription Export fields
     * */
    protected function subscriptionExportFields($subscriptions){
        $data = array();
        $data['headers'] = array('j2store_subscription_id' => JText::_('J2STORE_SUBSCRIPTION_ID'),
            'orderitem_name' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'),
            'status' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS'),
            'user_name' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_USER'),
            'user_email' => JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_EMAIL'),
            'start_on' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'),
            'end_on' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'),
            'trial_start_on' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_TRIAL_START_ON'),
            'trial_end_on' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_TRIAL_END_ON'),
            'next_payment_on' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'),
            'renewal_amount' => JText::_('J2STORE_SUBSCRIPTION_RENEWAL_AMOUNT'),
            'trash_pickup' => 'Day of Trash Pickup',
            'number_bins' => 'Number of Bins',
            );
        if(count($subscriptions)){
            $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
            $tz = JFactory::getConfig()->get('offset');
            $j2_params = J2Store::config();
            $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
            foreach ($subscriptions as $key => $subscription){
                $parentOrder = F0FTable::getAnInstance('Order','J2StoreTable');
                $parentOrder->load(array(
                    'order_id' => $subscription->order_id
                ));
                $trash_pickup = $this->get_trash_pickup_info($parentOrder);
                $number_bins = $this->get_number_of_bins($parentOrder);

                foreach ($data['headers'] as $field => $fieldHeader){
                    if($field == 'status') {
                        $status = $subsStatusObj->getStatus($subscription->$field);
                        $data['content'][$key][] = JText::_($status->status_name);
                    } else if(in_array($field, array('start_on', 'end_on', 'next_payment_on', 'trial_start_on', 'trial_end_on'))){
                        if($subscription->$field == '0000-00-00 00:00:00'){
                            $data['content'][$key][] = '-';
                        } else {
                            $date = JFactory::getDate($subscription->$field, $tz);
                            $data['content'][$key][] = $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                        }
                    } else if($field == 'user_name')
                    {
                        $userDetails = JFactory::getUser($subscription->user_id);
                        $data['content'][$key][] = $userDetails->get('name');
                    } else if($field == 'renewal_amount'){
                        $renewal_amount = $model->getRenewalAmount($subscription);
                        $data['content'][$key][] = J2Store::currency()->format($renewal_amount['renewal_amount'], $subscription->currency_code, $subscription->currency_value);
                    } else if($field == 'trash_pickup'){
                        $data['content'][$key][] = $trash_pickup;
                    } else if($field == 'number_bins'){
                        $data['content'][$key][] = $number_bins;
                    } else {
                        $data['content'][$key][] = $subscription->$field;
                    }
                }
            }

        } else {
            $data['content'] = JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_NO_SUBSCRIPTION_FOUND');
        }

        return $data;
    }
}