<?php
namespace J2Store\Subscription\Helper;
defined('_JEXEC') or die('Restricted access');

Class SubscriptionStatus{
    public static $instance = null;

    public function __construct($properties=null) {

    }

    public static function getInstance(array $config = array())
    {
        if (!self::$instance)
        {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
    
    /**
     * Get subscription status
     * */
    public function getAllSubscriptionStatus(){
        $status = array();
        $status['new'] = new \stdClass();
        $status['new']->id = 'new';
        $status['new']->status_name = 'J2STORE_SUBSCRIPTION_NEW';
        $status['new']->status_cssclass = 'label-warning';
        $status['new']->notify_customer = '0';

        $status['active'] = new \stdClass();
        $status['active']->id = 'active';
        $status['active']->status_name = 'J2STORE_SUBSCRIPTION_ACTIVE';
        $status['active']->status_cssclass = 'label-success';
        $status['active']->notify_customer = '1';

        $status['future'] = new \stdClass();
        $status['future']->id = 'future';
        $status['future']->status_name = 'J2STORE_SUBSCRIPTION_FUTURE';
        $status['future']->status_cssclass = 'label-future';
        $status['future']->notify_customer = '1';

        $status['expired'] = new \stdClass();
        $status['expired']->id = 'expired';
        $status['expired']->status_name = 'J2STORE_SUBSCRIPTION_EXPIRED';
        $status['expired']->status_cssclass = 'label-important';
        $status['expired']->notify_customer = '1';

        $status['canceled'] = new \stdClass();
        $status['canceled']->id = 'canceled';
        $status['canceled']->status_name = 'J2STORE_SUBSCRIPTION_CANCELED';
        $status['canceled']->status_cssclass = 'label-canceled';
        $status['canceled']->notify_customer = '1';

        $status['on_hold'] = new \stdClass();
        $status['on_hold']->id = 'on_hold';
        $status['on_hold']->status_name = 'J2STORE_SUBSCRIPTION_ON_HOLD';
        $status['on_hold']->status_cssclass = 'label-info';
        $status['on_hold']->notify_customer = '0';

        $status['pending'] = new \stdClass();
        $status['pending']->id = 'pending';
        $status['pending']->status_name = 'J2STORE_SUBSCRIPTION_PENDING';
        $status['pending']->status_cssclass = 'label-info';
        $status['pending']->notify_customer = '0';

        $status['failed'] = new \stdClass();
        $status['failed']->id = 'failed';
        $status['failed']->status_name = 'J2STORE_SUBSCRIPTION_PAYMENT_FAILED';
        $status['failed']->status_cssclass = 'label-important';
        $status['failed']->notify_customer = '1';

        $status['in_trial'] = new \stdClass();
        $status['in_trial']->id = 'in_trial';
        $status['in_trial']->status_name = 'J2STORE_SUBSCRIPTION_IN_TRIAL';
        $status['in_trial']->status_cssclass = 'label-in_trial';
        $status['in_trial']->notify_customer = '1';

        $status['card_expired'] = new \stdClass();
        $status['card_expired']->id = 'card_expired';
        $status['card_expired']->status_name = 'J2STORE_SUBSCRIPTION_PAYMENT_CARD_EXPIRED';
        $status['card_expired']->status_cssclass = 'label-card_expired';
        $status['card_expired']->notify_customer = '1';

        $additionalStatus = array();
        $j2StorePlugin = \J2Store::plugin();
        $j2StorePlugin->event('GetSubscriptionAdditionalStatus', array(&$additionalStatus));
        if(!empty($additionalStatus)){
            $status = array_merge($status, $additionalStatus);
        }

        return $status;
    }

    /**
     * Get status
     * */
    public function getStatus($status){
        $allStatus = $this->getAllSubscriptionStatus();
        if(isset($allStatus[$status])){
            return $allStatus[$status];
        } else {
            $statusObj = new \stdClass();
            $statusObj->id = $status;
            $statusObj->status_name = $status;
            $statusObj->status_cssclass = 'label-info';
            $statusObj->notify_customer = '0';
            return $statusObj;
        }
    }

    /**
     * Get update status select box
     * */
    public function getUpdateStatusSelectBox($name, $current, $attributes = array()){
        $status = $this->getAllSubscriptionStatus();
        $notIn = array('new');
        if($current != '' && $current != 'new'){
            $notIn[] = $current;
        }
        if($current != 'active' && $current != 'in_trial'){
            $notIn[] = 'canceled';
        }
        $attr = '';
        if(is_array($attributes))
            foreach ($attributes as $key => $attribute){
                $attr .= $key.'="'.$attribute.'" ';
            }
        $html = '<select name="'.$name.'" '.$attr.'>';
        $html .= '<option value="">'.\JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_CHANGE_STATUS_SELECT').'</option>';
        foreach ($status as $stat){
            if(!in_array($stat->id, $notIn)){
                $html .= '<option notify-customer="'.$stat->notify_customer.'" value="'.$stat->id.'">'.\JText::_($stat->status_name).'</option>';
            }
        }
        $html .= '</select>';

        return $html;
    }
}