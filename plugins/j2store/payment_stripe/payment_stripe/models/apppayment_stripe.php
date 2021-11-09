<?php
/**
 * @package 	J2Store
 * @author      Ashlin, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2017 J2Store . All rights reserved.
 * @license 	GNU GPL v3 or later
 */
// No direct access to this file
defined('_JEXEC') or die;

/* class J2StoreModelShippingMethods extends J2StoreModelBase */
require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appmodel.php');
class J2StoreModelAppPayment_stripe extends J2StoreAppModel
{
	var $_element = 'payment_stripe';

    /**
     * Get app details
     * */
    public function getAppDetails(){
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
     * Send Email to customer about authentication required to complete payment
     *
     * @param $subscription object
     * @param $order object
     * @return boolean
     * */
    public function sendEmailToCustomerAboutAuthenticationRequiredForPayment($subscription, $order){
        $vars = new stdClass();
        $userDetails = JFactory::getUser($subscription->user_id);
        $vars->subscription = $subscription;
        $vars->user = $userDetails;
        $vars->order = $order;
        $vars->product = J2Store::product()->setId($subscription->product_id)->getProduct();
        $vars->orderInfo = F0FTable::getInstance('Orderinfo' ,'J2StoreTable');
        $vars->orderInfo->load(array('order_id' => $subscription->order_id));
        $vars->app_id = JPluginHelper::getPlugin('j2store', $this->_element)->id;
        $template = new stdClass();
        $template->recipients = array($userDetails->get('email'));
        $template->subject = JText::_('J2STORE_STRIPE_PAYMENT_INTENT_PAYMENT_REQUIRED_AUTHENTICATION_TO_COMPLETE_EMAIL_SUBJECT');
        $template->subject = str_replace("[ORDER_ID]", $order->order_id, $template->subject);
        $template->body = $this->_getMailLayout('notify_authentication_required_for_payment', $vars);
        $result = $this->sendEmails($template);
        if($result){
            $order->add_history(JText::_('J2STORE_STRIPE_PAYMENT_INTENT_EMAIL_SENT_TO_NOTIFY_AUTHENTICATION_REQUIRED_FOR_PAYMENT'));
        }

        return $result;
    }

    /**
     * Get plugin params
     * */
    function getpluginParams(){
        $plugin_data = JPluginHelper::getPlugin('j2store', $this->_element);
        $params = new JRegistry;
        $params->loadString($plugin_data->params);
        return $params;
    }

    public function checkEligibleForPaymentAuthentication($order, $params){
        $result = $this->checkEligibleForPaymentAuthenticationWithMeta($order, $params);

        return $result['status'];
    }

    public function checkEligibleForPaymentAuthenticationWithMeta($order, $params){
        $result['status'] = false;
        if($order->order_state_id == 4) {
            $form_type = $params->get('form_type', 'normal');//inbuilt
            $is_payment_intent = $params->get('is_payment_intent', 0);
            if ($form_type == "inbuilt" && $is_payment_intent == "1") {
                $stripe_payment_authenticated = $this->getMetaData($order->j2store_order_id, 'stripe_payment_authenticated', 'order');
                if($stripe_payment_authenticated != "1"){
                    $stripe_client_secret = $this->getMetaData($order->j2store_order_id, 'stripe_client_secret', 'order');
                    $stripe_payment_method_id = $this->getMetaData($order->j2store_order_id, 'stripe_payment_method_id', 'order');
                    if (!empty($stripe_client_secret) && !empty($stripe_payment_method_id)) {
                        $result['status'] = true;
                        $result['stripe_client_secret'] = $stripe_client_secret;
                        $result['stripe_payment_method_id'] = $stripe_payment_method_id;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * To update order meta
     * */
    public function updateOrderMeta($order_id, $key, $value, $namespace = 'order', $scope = 'order_data', $valuetype = '')
    {
        $date = JFactory::getDate('now');
        $data = $this->getMetaData($order_id, $key, $namespace, $scope);
        $metaData = new \stdClass();
        $metaData->metavalue = $value;
        if($data && !empty($data->id)){
            $metaData->id = $data->id;
            $metaData->updated_at = $date->toSql();
            $result = \JFactory::getDbo()->updateObject('#__j2store_metafields', $metaData, 'id');
        } else {
            $metaData->owner_id = $order_id;
            $metaData->metakey = $key;
            $metaData->namespace = $namespace;
            $metaData->scope = $scope;
            $metaData->owner_resource = $namespace;
            $metaData->valuetype = $valuetype;
            $metaData->created_at = $date->toSql();
            $result = \JFactory::getDbo()->insertObject('#__j2store_metafields', $metaData);
        }
        return $result;
    }

    /**
     * Get last pending order from subscription Id
     *
     * @param $subscription_id integer
     * @return object
     * */
    public function getLastPendingOrderFromSubscriptionId($subscription_id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_orders');
        $query->where('subscription_id = ' . $db->quote($subscription_id));
        $query->where('order_state_id = ' . $db->quote(4));
        $query->order('j2store_order_id DESC');

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    /**
     * Get meta values
     * */
    public function getMetaData($id, $key, $namespace, $default = ''){
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_metafields');
        $query->where('owner_id = '.$db->quote($id));
        $query->where('metakey = '.$db->quote($key));
        $query->where('namespace = '.$db->quote($namespace));

        $db->setQuery($query);
        $result = $db->loadObject();
        if(!empty($result) && !empty($result->id)){
            return $result->metavalue;
        } else {
            return $default;
        }
    }

    /**
     * Get subscriptions based on id
     * */
    public function getSubscriptionById($id)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__j2store_subscriptions');
        $query->where('j2store_subscription_id = ' . $db->quote($id));

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
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
    function _getMailLayout($layout, $vars = false, $plugin = 'payment_stripe', $group = 'j2store' )
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
}
