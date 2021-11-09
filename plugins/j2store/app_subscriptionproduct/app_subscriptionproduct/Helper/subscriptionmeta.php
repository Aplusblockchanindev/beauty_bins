<?php
namespace J2Store\Subscription\Helper;
defined('_JEXEC') or die('Restricted access');

Class SubscriptionMeta{
    public static $instance = null;

    /**
     * To create object
     * */
    public static function getInstance(array $config = array()){
        if (!self::$instance){
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * To update subscription meta
     * */
    public function updateSubscriptionMeta($subscription_id, $key, $value, $namespace = 'subscription', $scope = 'subscription_data', $valuetype = ''){
        $data = $this->getSubscriptionMetaData($subscription_id, $key, $namespace, $scope);
        $metaData = new \stdClass();
        $metaData->metavalue = $value;
        if($data){
            $metaData->id = $data->id;
            $metaData->updated_at = $this->getCurrentDate();
            $result = \JFactory::getDbo()->updateObject('#__j2store_metafields', $metaData, 'id');
        } else {
            $metaData->owner_id = $subscription_id;
            $metaData->metakey = $key;
            $metaData->namespace = $namespace;
            $metaData->scope = $scope;
            $metaData->owner_resource = 'subscriptions';
            $metaData->valuetype = $valuetype;
            $metaData->created_at = $this->getCurrentDate();
            $result = \JFactory::getDbo()->insertObject('#__j2store_metafields', $metaData);
        }
        return $result;
    }

    /**
     * To add subscription meta
     * */
    public function addSubscriptionMeta($subscription_id, $key, $value, $namespace = 'subscription', $scope = 'subscription_data', $valuetype = ''){
        $metaData = new \stdClass();
        $metaData->metavalue = $value;
        $metaData->owner_id = $subscription_id;
        $metaData->metakey = $key;
        $metaData->namespace = $namespace;
        $metaData->scope = $scope;
        $metaData->owner_resource = 'subscriptions';
        $metaData->valuetype = $valuetype;
        $metaData->created_at = $this->getCurrentDate();
        
        return \JFactory::getDbo()->insertObject('#__j2store_metafields', $metaData);
    }

    /**
     * Get subscriptions meta
     * */
    public function getSubscriptionMetaData($subscription_id, $key, $namespace, $scope){
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote($namespace));
        $query->where('scope = '.$db->quote($scope));

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    /**
     * Get all subscriptions meta
     * */
    public function getAllSubscriptionMetaData($subscription_id, $key, $namespace, $scope){
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($subscription_id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote($namespace));
        $query->where('scope = '.$db->quote($scope));

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    /**
     * Current date and time
     * */
    protected function getCurrentDate(){
        $tz = \JFactory::getConfig()->get('offset');
        $date = \JFactory::getDate(date('Y-m-d H:i:s'), $tz);
        return date('Y-m-d H:i:s', strtotime($date));
    }
}