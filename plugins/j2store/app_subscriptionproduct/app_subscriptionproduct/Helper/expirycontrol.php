<?php
namespace J2Store\Subscription\Helper;
defined('_JEXEC') or die('Restricted access');
use F0FTable;
Class ExpiryControl{
    public static $instance = null;
    private $cron = false;
    public $params;
    public $doLog = true;

    public function __construct($properties=null) {
        $plugin = \JPluginHelper::getPlugin('j2store', 'app_subscriptionproduct');
        $this->params = new \JRegistry($plugin->params);
        $this->doLog = $this->params->get('enable_cron_log', true);
    }

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
     * Execute Expiration control though plugin deprecated
     * */
    public function executeExpirationControlThoughPlugin(){}

    /**
     * Execute Expiration control though cron
     * */
    public function executeExpirationControlThoughCron()
    {
        $app = \JFactory::getApplication();
        $j2store_config = \J2Store::config();
        $cron_key = $app->input->get('cron_secret', '');
        $j2Store_cron_key = $j2store_config->get('queue_key');

        if($cron_key != '' && $j2Store_cron_key == $cron_key){
            $this->cron = true;
        }

        // Check if we need to run
        if (!$this->doIHaveToRun())
        {
            return;
        }
        $this->_log('-------------------------------------------------------------------------------------------', 0, 'blank');
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TRIGGERED_CRON'));

        $this->executeExpirationControl();

        // Update the last run info and quit
        $this->setLastRunTimestamp();
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TRIGGERED_CRON_COMPLETED'));
        $this->_log('-------------------------------------------------------------------------------------------', 0, 'blank');

        if($this->cron){
            echo \JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_EXECUTED_SUSCESS');$app->close();
        }
    }

    /**
     * "Do I have to run?" - the age old question. Let it be answered by checking the
     * last execution timestamp, stored in the component's configuration.
     */
    private function doIHaveToRun()
    {
        //return true;
        if($this->cron){
            return true;
        }
        $lastRunUnix = $this->params->get('expirycontrol_last_run_at', 0);
        $nextRunUnix = $lastRunUnix;
        $nextRunUnix += $this->params->get('execute_by_each_hours', 12) * 3600;
        $now = time();
        return ($now >= $nextRunUnix);
    }

    /**
     * Saves the timestamp of this plugin's last run
     */
    private function setLastRunTimestamp() {
        $lastRun = time();

        // Get extension table class
        $extensionTable = \JTable::getInstance('extension');
        // Find plugin id, in my case it was plg_ajax_ajaxhelpary
        $pluginId = $extensionTable->find( array('element' => 'app_subscriptionproduct', 'type' => 'plugin') );
        $extensionTable->load($pluginId);

        // Get joomla default object
        $params = new \JRegistry;
        $params->loadString($extensionTable->params, 'JSON'); // Load my plugin params.

        $params->set('expirycontrol_last_run_at', $lastRun); // Set to parameters
        $extensionTable->bind( array('params' => $params->toString()) ); // Bind to extension table

        // check and store
        if (!$extensionTable->check()) {
            $this->setError($extensionTable->getError());
        }
        if (!$extensionTable->store()) {
            $this->setError($extensionTable->getError());
        }
    }

    /**
     * Execute Expiration control
     * */
    protected function executeExpirationControl(){
        if(!defined('RUNNING_J2STORE_SUBSCRIPTION_RENEWAL_CRON')){
            define('RUNNING_J2STORE_SUBSCRIPTION_RENEWAL_CRON', true);
        }

        $j2StorePlugin = \J2Store::plugin();
        $j2StorePlugin->event('BeforeRunSubscriptionExpireControl', array());

        //To process the future subscription which is in Active period
        $this->processFutureToActive();

        //To process the active subscription which is in Expire period
        $this->processActiveToExpire();

        //To process the cancel subscription which is in Expire period
        $this->processCancelToExpire();

        // To send notification mail to customer about trial to first renewal
        $this->processMailToIntimateTrialToFirstRenewal();

        // To send notification mail to customer about next renewal
        $this->processMailToIntimateNextRenewal();

        // To process Renewal
        $this->processRenewal();

        // To send notification mail to customer about expire
        $this->processMailToIntimateExpire();
    }

    /**
     * To process the active subscription which is in Expire period
     * */
    protected function processFutureToActive(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_EXPIRY_FUTURE_TO_ACTIVE_SUBSCRIPTION'));
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('status = '.$db->q('future'));
        $query->where('start_on <= '.$db->q($this->getCurrentDate()));
        $db->setQuery($query);
        $subscriptions = $db->loadObjectList();
        $j2StorePlugin = \J2Store::plugin();
        if(count($subscriptions)){
            foreach ($subscriptions as $key => $subscription){
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($subscription->j2store_subscription_id, 'active'));
                $j2StorePlugin->event('RefreshUserGroups', array($subscription->user_id));
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_FUTURE_TO_ACTIVE_SUBSCRIPTION'), $subscription->j2store_subscription_id), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_FUTURE_TO_ACTIVE_SUBSCRIPTION_COUNT'), count($subscriptions)), 1);
    }

    /**
     * To process the active subscription which is in Expire period
     * */
    protected function processActiveToExpire(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_EXPIRY_ACTIVE_TO_EXPIRE_SUBSCRIPTION'));
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('status = '.$db->q('active'));
        $query->where('end_on <= '.$db->q($this->getCurrentDate()));
        $query->where('subscription_length <> 0');
        $db->setQuery($query);
        $subscriptions = $db->loadObjectList();
        $j2StorePlugin = \J2Store::plugin();
        if(count($subscriptions)){
            foreach ($subscriptions as $key => $subscription){
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($subscription->j2store_subscription_id, 'expired'));
                $j2StorePlugin->event('RefreshUserGroups', array($subscription->user_id));
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_ACTIVE_TO_EXPIRE_SUBSCRIPTION'), $subscription->j2store_subscription_id), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_ACTIVE_TO_EXPIRE_SUBSCRIPTION_COUNT'), count($subscriptions)), 1);
    }

    /**
     * To process the cancel subscription which is in Expire period
     * */
    protected function processCancelToExpire(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_EXPIRY_CANCEL_TO_EXPIRE_SUBSCRIPTION'));
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('status = '.$db->q('canceled'));
        $query->where('end_on <= '.$db->q($this->getCurrentDate()));
        $query->where('subscription_length <> 0');
        $db->setQuery($query);
        $subscriptions = $db->loadObjectList();
        $j2StorePlugin = \J2Store::plugin();
        if(count($subscriptions)){
            foreach ($subscriptions as $key => $subscription){
                $j2StorePlugin->event('ChangeSubscriptionStatus', array($subscription->j2store_subscription_id, 'expired'));
                $j2StorePlugin->event('RefreshUserGroups', array($subscription->user_id));
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_CANCEL_TO_EXPIRE_SUBSCRIPTION'), $subscription->j2store_subscription_id), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_EXPIRY_CANCEL_TO_EXPIRE_SUBSCRIPTION_COUNT'), count($subscriptions)), 1);
    }

    /**
     * To process the renewal subscription
     * */
    protected function processRenewal(){
        $current_date = $this->getCurrentDate();
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_SUBSCRIPTION_RENEWAL'));
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('(status = '.$db->q('active').' OR status = '.$db->q('in_trial').')');
        $query->where('(end_on > '.$db->q($current_date).' OR subscription_length = 0)');
        $query->where('next_payment_on <= '.$db->q($current_date));
        $query->where('(next_payment_on < end_on OR subscription_length = 0)');
        $query->where('(renewal_retry_on IS NULL OR renewal_retry_on = '.$db->q('0000-00-00 00:00:00').' OR renewal_retry_on <= '.$db->q($current_date).')');
        $query->where('renewal_process <> '.$db->q('processing'));
        $db->setQuery($query);
        $subscriptions = $db->loadObjectList();
        $j2StorePlugin = \J2Store::plugin();
        if(count($subscriptions)){
            foreach ($subscriptions as $key => $subscription){
                $doIHaveToProcessRenewal = $this->doIHaveToProcessRenewal($subscription->j2store_subscription_id);
                if($doIHaveToProcessRenewal){
                    $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_SUBSCRIPTION_RENEWAL_FOR'), $subscription->j2store_subscription_id), 1);
                    $this->updateRenewalStatusProcessing($subscription->j2store_subscription_id);
                    $j2StorePlugin->event('BeforeProcessSubscriptionRenewal', array($subscription->j2store_subscription_id));
                    $j2StorePlugin->event('ProcessSubscriptionRenewal', array($subscription->j2store_subscription_id));
                    $this->updateRenewalStatusCompleted($subscription->j2store_subscription_id);
                    $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_SUBSCRIPTION_RENEWAL_FOR'), $subscription->j2store_subscription_id), 1);
                }
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSED_SUBSCRIPTION_RENEWAL'), count($subscriptions)), 1);
    }

    /**
     * To check before renewal // To avoid duplicate renewal(If cron runs per seconds)
     * */
    protected function doIHaveToProcessRenewal($subscription_id){
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__j2store_subscriptions');
        $query->where('(status = '.$db->q('active').' OR status = '.$db->q('in_trial').')');
        $query->where('j2store_subscription_id = '.$db->q($subscription_id));
        $query->where('(end_on > '.$db->q($this->getCurrentDate()).' OR subscription_length = 0)');
        $query->where('next_payment_on <= '.$db->q($this->getCurrentDate()));
        $query->where('(next_payment_on < end_on OR subscription_length = 0)');
        $query->where('renewal_process <> '.$db->q('processing'));
        $db->setQuery($query);
        $subscription = $db->loadObject();
        if(empty($subscription)){
            return false;
        } else {
            return true;
        }
    }

    /**
     * To update renewal status to Processing
     * */
    protected function updateRenewalStatusProcessing($subscription_id){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscription_id));
        $subscription->renewal_process = 'processing';
        $subscription->store();
    }

    /**
     * To update renewal status to Completed
     * */
    protected function updateRenewalStatusCompleted($subscription_id){
        $subscription = F0FTable::getInstance('Subscription', 'J2StoreTable')->getClone();
        $subscription->load(array('j2store_subscription_id' => $subscription_id));
        $subscription->renewal_process = 'completed';
        $subscription->store();
    }

    /**
     * To process the active subscription which is in Expire period
     * */
    protected function processMailToIntimateExpire(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_MAIL_TO_NOTIFY_SUBSCRIPTION_EXPIRE'));
        $beforeDays = $this->params->get('notify_expire', '');
        $processed_total = 0;
        if($beforeDays != ''){
            $beforeDaysArray = explode(',', $beforeDays);
            foreach ($beforeDaysArray as $beforeDay){
                $today = $this->getCurrentDate();
                $addedDate = $this->addDaysToTheDate($beforeDay, $today);
                $addedDate = $this->getDate($addedDate);
                $db = \JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')->from('#__j2store_subscriptions');
                $query->where('status = '.$db->q('active'));
                $query->where('end_on LIKE '.$db->q($addedDate.'%'));
                $db->setQuery($query);
                $subscriptions = $db->loadObjectList();
                $j2StorePlugin = \J2Store::plugin();
                if(count($subscriptions)){
                    foreach ($subscriptions as $key => $subscription){
                        $j2StorePlugin->event('SendMailToCustomerNotifyExpire', array($subscription->j2store_subscription_id, $beforeDay));
                        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_SUBSCRIPTION_EXPIRE_BEFORE'), $beforeDay, $subscription->j2store_subscription_id), 1);
                    }
                }
                $processed_total += count($subscriptions);
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_SUBSCRIPTIONS_EXPIRE_BEFORE'), $beforeDay, count($subscriptions)), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_SUBSCRIPTION_EXPIRE'), $processed_total), 1);
    }

    /**
     * To process the trial subscription which is in renewal period
     * */
    protected function processMailToIntimateTrialToFirstRenewal(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_MAIL_TO_NOTIFY_FIRST_RENEWAL_FROM_TRIAL_SUBSCRIPTION'));
        $notifyRenewal = $this->params->get('trial_renewal_notify_email', 0);
        $beforeDays = $this->params->get('trial_renewal_notify_email_on', '');
        $processed_total = 0;
        if($notifyRenewal && $beforeDays != ''){
            $beforeDaysArray = explode(',', $beforeDays);
            foreach ($beforeDaysArray as $beforeDay){
                $today = $this->getCurrentDate();
                $addedDate = $this->addDaysToTheDate($beforeDay, $today);
                $addedDate = $this->getDate($addedDate);
                $db = \JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')->from('#__j2store_subscriptions');
                $query->where('status = '.$db->q('in_trial'));
                $query->where('next_payment_on LIKE '.$db->q($addedDate.'%'));
                $query->where('(`end_on` > `next_payment_on` OR `end_on` = \'0000-00-00 00:00:00\')');
                $db->setQuery($query);
                $subscriptions = $db->loadObjectList();
                $j2StorePlugin = \J2Store::plugin();
                if(count($subscriptions)){
                    foreach ($subscriptions as $key => $subscription){
                        $j2StorePlugin->event('SendMailToCustomerNotifyFirstRenewalFromTrial', array($subscription->j2store_subscription_id, $beforeDay));
                        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_FIRST_RENEWAL_FROM_TRIAL_SUBSCRIPTION_FOR_BEFORE'), $beforeDay, $subscription->j2store_subscription_id), 1);
                    }
                }
                $processed_total += count($subscriptions);
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_FIRST_RENEWAL_FROM_TRIAL_SUBSCRIPTIONS_FOR_BEFORE'), $beforeDay, count($subscriptions)), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_FIRST_RENEWAL_FROM_TRIAL_SUBSCRIPTION'), $processed_total), 1);
    }

    /**
     * To process the active subscription which is in renewal period
     * */
    protected function processMailToIntimateNextRenewal(){
        $this->_log(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_PROCESSING_MAIL_TO_NOTIFY_RENEWAL_SUBSCRIPTION'));
        $notifyRenewal = $this->params->get('renewal_notify_email', 0);
        $beforeDays = $this->params->get('renewal_notify_email_on', '');
        $processed_total = 0;
        if($notifyRenewal && $beforeDays != ''){
            $beforeDaysArray = explode(',', $beforeDays);
            foreach ($beforeDaysArray as $beforeDay){
                $today = $this->getCurrentDate();
                $addedDate = $this->addDaysToTheDate($beforeDay, $today);
                $addedDate = $this->getDate($addedDate);
                $db = \JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')->from('#__j2store_subscriptions');
                $query->where('status = '.$db->q('active'));
                $query->where('next_payment_on LIKE '.$db->q($addedDate.'%'));
                $query->where('(`end_on` > `next_payment_on` OR `end_on` = \'0000-00-00 00:00:00\')');
                $db->setQuery($query);
                $subscriptions = $db->loadObjectList();
                $j2StorePlugin = \J2Store::plugin();
                if(count($subscriptions)){
                    foreach ($subscriptions as $key => $subscription){
                        $j2StorePlugin->event('SendMailToCustomerNotifyNextRenewal', array($subscription->j2store_subscription_id, $beforeDay));
                        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_RENEWAL_SUBSCRIPTION_FOR_BEFORE'), $beforeDay, $subscription->j2store_subscription_id), 1);
                    }
                }
                $processed_total += count($subscriptions);
                $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_RENEWAL_SUBSCRIPTIONS_FOR_BEFORE'), $beforeDay, count($subscriptions)), 1);
            }
        }
        $this->_log(sprintf(\JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_LOG_TOTAL_PROCESSED_MAIL_TO_NOTIFY_RENEWAL_SUBSCRIPTION'), $processed_total), 1);
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
     * Add days to a date
     * */
    public function addDaysToTheDate($days, $date){
        return date("Y-m-d H:i:s", strtotime($date." +".$days." days"));
    }

    /**
     * subtract days to a date
     * */
    public function subtractDaysToTheDate($days, $date){
        return date("Y-m-d H:i:s", strtotime($date." -".$days." days"));
    }

    /**
     * Add days to a minutes
     * */
    public function addMinutesToTheDate($minutes, $date){
        return date("Y-m-d H:i:s", strtotime($date." +".$minutes." minutes"));
    }

    /**
     * get Only date
     * */
    public function getDate($data){
        return date('Y-m-d', strtotime($data));
    }

    /**
     * To display formatted date
     * */
    public function getFormattedDateNextRenewal($date){
        $tz = \JFactory::getConfig()->get('offset');
        $date = \JFactory::getDate($date, $tz);
        //$date->setTimezone(new \DateTimeZone($tz));
        //\JText::_('DATE_FORMAT_LC1')
        return $date->format($this->params->get('date_format_next_renewal_cart', 'Y-m-d'), true);
    }

    /**
     * To check is the subscription is stopped in middle of renewal process
     * */
    public function isStoppedInMiddleOfRenewalProcess($subscription){
        $stopped = false;
        if($subscription->renewal_process == "processing"){
            $current_date = $this->getCurrentDate();
            $modified_on_with_additional_mins = $this->addMinutesToTheDate(5, $subscription->modified_on);
            if(strtotime($modified_on_with_additional_mins) <= strtotime($current_date)){
                $stopped = true;
            }
        }

        return $stopped;
    }

    /**
     * To update renewal status to Completed
     * */
    public function updateRenewalStatusCompletedManually($subscription_id){
        $this->updateRenewalStatusCompleted($subscription_id);
    }

    /**
     * Simple logger
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    public function _log($text, $tab = 0, $type = 'message') {
        if ($this->doLog) {
            $file = JPATH_ROOT . "/cache/j2store_subscription_cron.log";
            $date = \JFactory::getDate ();
            $formatedDate = ' ['.$date->format ( 'Y-m-d H:i:s' ).']';

            $f = fopen ( $file, 'a' );
            $tab_string = "";
            if($tab) $tab_string = "\t\t";
            if($type == 'blank'){
                fwrite ( $f, "\n" . $tab_string . $text );
            } else {
                fwrite ( $f, "\n\n" .$tab_string . $text. "\t" . $formatedDate );
            }

            fclose ( $f );
        }
    }
}