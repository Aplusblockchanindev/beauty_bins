<?php
/**
 * --------------------------------------------------------------------------------
 *  APP - Profile Order
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
defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_ADMINISTRATOR . '/components/com_j2store/library/appcontroller.php');

class J2StoreControllerAppProfileorder extends J2StoreAppController
{
    var $_element = 'app_profileorder';

    function __construct($config = array())
    {
        parent::__construct($config);
        //there is problem in loading of language
        //this code will fix the language loading problem
        $language = JFactory::getLanguage();
        $extension = 'plg_j2store' . '_' . $this->_element;
        $language->load($extension, JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load($extension, JPATH_ADMINISTRATOR, null, true);
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        F0FModel::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_j2store/models');
        //F0FTable::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tables');
        F0FTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_j2store/tables');
    }


    protected function onBeforeGenericTask($task)
    {
        $this->configProvider->get(
            $this->component . '.views.' .
            F0FInflector::singularize($this->view) . '.acl.' . $task, ''
        );

        return $this->allowedTasks($task);
    }

    function allowedTasks($task){
        $allowed = array(
            'payment',
            'paymentValidate',
            'confirmPayment'
        );
        $status = false;
        if(in_array($task, $allowed)){
            $status = true;
        }
        return $status;
    }

    /**
     * display payment render form page
     *
    */
    public function payment(){
        $app = JFactory::getApplication();
        $id = $app->input->getInt('id',0);
        $order_id = (int)$app->input->getString('order_id',0);
        $params = J2Store::config();
        $order = F0FTable::getAnInstance('Order', 'J2StoreTable');
        $vars = new stdClass();
        $vars->app_id = $id;
        if($order_id && $id > 0 && $order->load(array( 'order_id' => $order_id ))){
            JPluginHelper::importPlugin ('j2store');
            //custom fields
            $selectableBase = J2Store::getSelectableBase();
            $vars->fieldsClass = $selectableBase;
            $address_table = F0FTable::getAnInstance('Address', 'J2StoreTable');
            $fields = $selectableBase->getFields('payment',$address_table,'address');
            $vars->fields = $fields;
            $vars->address = $address_table;

            //get layout settings
            $vars->storeProfile = J2Store::storeProfile();

            $payment_plugins = J2Store::plugin()->getPluginsWithEvent( 'onJ2StoreGetPaymentPlugins' );
            $default_method = $params->get('default_payment_method', '');
            //process payment plugins
            $showPayment = true;
            if ((float)$order->order_total == (float)'0.00'  )
            {
                if(isset($order->show_payment_method) && $order->show_payment_method == 1){
                    $showPayment = true;
                }else{
                    $showPayment = false;
                }

            }
            $vars->showPayment = $showPayment;

            $plugins = array();
            if ($payment_plugins)
            {
                foreach ($payment_plugins as $plugin)
                {
                    $results = $app->triggerEvent("onJ2StoreGetPaymentOptions", array( $plugin->element, $order ) );
                    if (!in_array(false, $results, false))
                    {
                        if(!empty($default_method) && $default_method == $plugin->element) {
                            $plugin->checked = true;
                            $html = $this->getPaymentForm( $plugin->element, true);
                        }
                        $plugins[] = $plugin;
                    }
                }
            }

            if (count($plugins) == 1)
            {
                $plugins[0]->checked = true;
                $html = $this->getPaymentForm( $plugins[0]->element, true);
            }


            $vars->plugins = $plugins;
            $vars->payment_form_div = $html;
            $vars->order = $order;
            $vars->params = $params;
            $this->_getLayout('payment',$vars);
        }
    }


    function getPaymentForm($element = '', $plain_format = false)
    {
        $app = JFactory::getApplication();
        $values = $app->input->getArray($_REQUEST);
        $html = "";
        $element = $this->_element;
        JPluginHelper::importPlugin('j2store');

        $results = $app->triggerEvent("onJ2StoreGetPaymentForm", array(
            $element,
            $values
        ));
        for ($i = 0; $i < count($results); $i++) {
            $result = $results [$i];
            $html .= $result;
        }
        return $html;
    }



    function paymentValidate(){
        $app = JFactory::getApplication();
        $values = $app->input->getArray($_REQUEST);
        $order_id = $app->input->get('order_id',0);
        $id = $app->input->get('id',0);
        $session = JFactory::getSession();
        $user = JFactory::getUser();
        $params = J2Store::config();
        $address_model = F0FModel::getTmpInstance('Addresses', 'J2StoreModel');
//first validate custom fields
        $selectableBase = J2Store::getSelectableBase();
        $json = $selectableBase->validate($values, 'payment', 'address');
        $profile_order_id = $session->get('profile_order_id',null,'j2store');
        $order = F0FModel::getTmpInstance('Orders', 'J2StoreModel')->initOrder($profile_order_id)->getOrder();
        if(!$json) {
            J2Store::plugin()->eventWithArray('CheckoutValidateShippingPayment',array($values,$order,&$json));
        }
        if (!$json) {
            //$order = F0FModel::getTmpInstance('Orders', 'J2StoreModel')->initOrder($profile_order_id)->getOrder();
            $order = F0FTable::getAnInstance('Order', 'J2StoreTable');
            $order->load(
                array(
                    'order_id'=>$order_id
                )
            );
            $showPayment = true;
            if ((float)$order->order_total == (float)'0.00')
            {
                $showPayment = false;
            }

            if($showPayment) {
                $payment_plugin = $app->input->getString('payment_plugin');
                if (!isset($payment_plugin)) {
                    $json['error']['warning'] = JText::_('J2STORE_CHECKOUT_ERROR_PAYMENT_METHOD');
                } elseif (!isset($payment_plugin )) {
                    $json['error']['warning'] = JText::_('J2STORE_CHECKOUT_ERROR_PAYMENT_METHOD');
                }
                //validate the selected payment
                try {
                    $this->validateSelectPayment($payment_plugin, $values);
                } catch (Exception $e) {
                    $json['error']['warning'] = $e->getMessage();
                }

            }

            if($params->get('show_terms', 0) && $params->get('terms_display_type', 'link') =='checkbox' ) {
                $tos_check = $app->input->get('tos_check');
                if (!isset($tos_check)) {
                    $json['error']['tos_check'] = JText::_('J2STORE_CHECKOUT_ERROR_AGREE_TERMS');
                }
            }
        }


        if (!$json) {

            $payment_plugin = $app->input->getString('payment_plugin');
            //set the payment plugin form values in the session as well.
            $session->set('payment_values', $values, 'j2store');
            $session->set('payment_method', $payment_plugin, 'j2store');
            $session->set('customer_note', strip_tags($app->input->getString('customer_note')), 'j2store');
        }

        if(!$json){
            $json['redirect'] = 'index.php?option=com_j2store&view=apps&task=view&layout=view&appTask=confirmPayment&id='.$id.'&order_id='.$order_id;
        }
        echo json_encode($json);
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


    public function confirmPayment(){

        J2Store::utilities()->nocache();
        $app = JFactory::getApplication ();
        $user = JFactory::getUser();
        $params = J2Store::config();
        $errors = array();
        $session = JFactory::getSession();
        JPluginHelper::importPlugin ('j2store');
        $order_id = $app->input->get('order_id',0);
        //get the payment plugin form values set in the session.
        if($session->has('payment_values', 'j2store')) {
            $values = $session->get('payment_values', array(), 'j2store');
            //backward compatibility. TODO: change the way the plugin gets its data
            foreach($values as $name=>$value) {
                $app->input->set($name, $value);
            }
        }
        try{
            $order = F0FTable::getAnInstance('Order', 'J2StoreTable');
            $order->load(array(
               'order_id' => $order_id
            ));
            $orders_model = F0FModel::getTmpInstance('Orders', 'J2StoreModel');
           // $orders_model->validateOrder($order);
            //plugin trigger
            $app->triggerEvent( "onJ2StoreAfterOrderValidate", array($order) );
            $vars=new stdClass();
            $vars->order = $order;
            J2Store::plugin()->event('BeforeCheckout',array($vars->order,&$vars));
        }catch (Exception $e){
            $errors[]= $e->getMessage();
        }

        //Extra watch fix
        if(!$session->has('payment_method', 'j2store')) {
            $payment_values = $session->get('payment_values', array(), 'j2store');
            $payment_method = isset($payment_values['payment_plugin']) ? $payment_values['payment_plugin'] : '';
            $session->set('payment_method', $payment_method, 'j2store');
        }

        //showPayment
        $showPayment = true;
        if ((float)$order->order_total == (float)'0.00')
        {
            $showPayment = false;
        }
        $vars = new stdClass();
        $vars->showPayment = $showPayment;

        // Validate if payment method has been set.
        if ($showPayment == true && !$session->has('payment_method', 'j2store')) {
            $errors[] = JText::_('J2STORE_CHECKOUT_ERROR_PAYMENT_METHOD_NOT_SELECTED');
        }

        if(!$errors) {
            $orderpayment_type = $session->get('payment_method', '', 'j2store');

            // in the case of orders with a value of 0.00, use custom values
            if ( (float) $order->order_total == (float)'0.00' )
            {
                $orderpayment_type = 'free';
                $transaction_status = JText::_( "J2STORE_COMPLETE" );
            }

            $order->orderpayment_type = $orderpayment_type;

            try{
                $order->store();
                $vars->order = $order;
                $app->setUserState( 'j2store.order_id', $order->order_id );
                $app->setUserState( 'j2store.orderpayment_id', $order->j2store_order_id );
                $app->setUserState( 'j2store.order_token', $order->token);

                // in the case of orders with a value of 0.00, we redirect to the confirmPayment page
                if ( (float) $order->order_total == (float)'0.00' )
                {
                    $free_redirect = JRoute::_( 'index.php?option=com_j2store&view=checkout&task=confirmPayment' );
                    $vars->free_redirect = $free_redirect;
                }

                $values = array();
                $values['order_id'] = $order->order_id;
                $values['orderpayment_id'] = $order->j2store_order_id;
                $values['orderpayment_amount'] = $order->order_total;
                $values['order'] = $order;

                $results = $app->triggerEvent( "onJ2StorePrePayment", array( $orderpayment_type, $values));

                // Display whatever comes back from Payment Plugin for the onPrePayment
                $html = "";
                for ($i=0; $i<count($results); $i++)
                {
                    $html .= $results[$i];
                }
                $vars->plugin_html = $html;
            }catch (Exception $e){
                $errors[] = $e->getMessage();
            }
        }

        if(count($errors)) {
            $vars->error = implode('/n', $errors);
        }
        $vars->params = J2Store::config();
        $this->_getLayout('confirmPayment',$vars);
    }
    /**
     * Gets the parsed layout file
     *
     * @param string $layout The name of  the layout file
     * @param object $vars Variables to assign to
     * @param string $plugin The name of the plugin
     * @param string $group The plugin's group
     * @return string
     * @access protected
     */
    function _getLayout($layout, $vars = false, $plugin = '', $group = 'j2store' )
    {

        if (empty($plugin))
        {
            $plugin = $this->_element;
        }

        ob_start();
        $layout = $this->_getLayoutPath( $plugin, $group, $layout );
        include($layout);
        $html = ob_get_contents();
        ob_end_clean();

        echo $html;
    }


    /**
     * Get the path to a layout file
     *
     * @param   string  $plugin The name of the plugin file
     * @param   string  $group The plugin's group
     * @param   string  $layout The name of the plugin layout file
     * @return  string  The path to the plugin layout file
     * @access protected
     */
    function _getLayoutPath($plugin, $group, $layout = 'default')
    {
        $app = JFactory::getApplication();

        // get the template and default paths for the layout
        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/'.$group.'/'.$plugin.'/'.$layout.'.php';
        $defaultPath = JPATH_SITE.'/plugins/'.$group.'/'.$plugin.'/'.$plugin.'/tmpl/'.$layout.'.php';

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
}
