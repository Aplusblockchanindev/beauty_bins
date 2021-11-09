<?php
/**
 * @package 	J2Store
 * @author      Ashlin, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2017 J2Store . All rights reserved.
 * @license 	GNU GPL v3 or later
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appcontroller.php');
class J2StoreControllerAppPayment_stripe extends J2StoreAppController
{
    var $_element = 'payment_stripe';

    /**
     * To accept tasks
     * */
    protected function onBeforeGenericTask($task) {
        return $this->allowedTasks($task);
    }

    /**
     * Allowed tasks
     * */
    public function allowedTasks($task){
        $allowed = array(
            'authenticateSubscriptionPayment'
        );
        $status = false;
        if(in_array($task, $allowed)){
            $status = true;
        }
        return $status;
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

    public function authenticateSubscriptionPayment(){
        $app = JFactory::getApplication();
        $app_id = $app->input->getInt('id');
        $order_id = $app->input->getVar('order_id');
        $vars = new JObject ();
        $vars->process_request = false;
        $vars->app_id = $app_id;
        F0FModel::addIncludePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/models');
        $modelApp = F0FModel::getTmpInstance('AppPayment_stripe', 'J2StoreModel');
        if(!empty($order_id)){
            $order = F0FTable::getInstance('Order', 'J2StoreTable')->getClone();
            $order->load(array(
                'order_id' => $order_id
            ));
            if(!empty($order) && !empty($order->j2store_order_id)){
                $params = $this->getpluginParams();
                $result = $modelApp->checkEligibleForPaymentAuthenticationWithMeta($order, $params);
                if($result['status']){
                    $vars->process_request = true;
                    $vars->stripe_client_secret = $result['stripe_client_secret'];
                    $vars->stripe_payment_method_id = $result['stripe_payment_method_id'];
                    $vars->order_id = $order_id;
                    $vars->order = $order;
                    if ($params->get('sandbox', 0)) {
                        // get sandbox credentials
                        $vars->publish_key = trim($params->get('stripe_test_publish_key'));
                    } else {
                        $vars->publish_key = trim($params->get('stripe_publish_key'));
                    }
                    $vars->orderpayment_type = $this->_element;
                }
            }
        }
        $model_app = F0FModel::getTmpInstance('Apps','J2StoreModel');
        $view = $this->getView( 'Apps', 'html' );
        $view->setModel($model_app, true );
        $view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
        $templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/plugins/j2store/'.$this->_element;
        $view->addTemplatePath($templatePath);
        $view->set('vars',$vars);
        $view->setLayout('authenticate_card');
        $view->display();
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
