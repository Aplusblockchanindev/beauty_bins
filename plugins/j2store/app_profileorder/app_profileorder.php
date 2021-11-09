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
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/app.php');

class plgJ2StoreApp_profileorder extends J2StoreAppPlugin
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element = 'app_profileorder';
    var $_wishlist = null;

    /**
     * Overriding
     *
     * @param $options
     * @return unknown_type
     */
    function onJ2StoreGetAppView($row)
    {

        if (!$this->_isMe($row)) {
            return null;
        }

        $html = $this->viewList();


        return $html;
    }

    /**
     * Validates the data submitted based on the suffix provided
     * A controller for this plugin, you could say
     *
     * @param $task
     * @return html
     */
    function viewList()
    {
        $app = JFactory::getApplication();
        $option = 'com_j2store';
        $ns = $option . '.app.' . $this->_element;
        $html = "";
        JToolBarHelper::title(JText::_('J2STORE_APP') . '-' . JText::_('PLG_J2STORE_' . strtoupper($this->_element)), 'j2store-logo');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', 'index.php?option=com_j2store&view=apps');
        JToolBarHelper::back('J2STORE_BACK_TO_DASHBOARD', 'index.php?option=com_j2store');
        $vars = new JObject();
        //model should always be a plural
        $this->includeCustomModel('AppProfileorders');

        $model = F0FModel::getTmpInstance('AppProfileorders', 'J2StoreModel');

        $data = $this->params->toArray();
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;

        $this->includeCustomTables();

        $id = $app->input->getInt('id', '0');
        $vars->id = $id;
        $vars->action = "index.php?option=com_j2store&view=app&task=view&id={$id}";
        $html = $this->_getLayout('default', $vars);
        return $html;
    }

    function onJ2StoreAfterDisplayOrder($order){

        $status = false;
        $os = $this->params->get('orderstatuses', '*');
        if(!is_array($os)) {
            $os_array = explode(',', $os);
        }else {
            $os_array = $os;
        }

        if(in_array('*', $os_array)) {
            $status = true;
        }elseif(in_array($order->order_state_id, $os_array)) {
            $status = true;
        }
        
        if($status) {

            $vars = new stdClass();
            $vars->order_id = $order->order_id;
            $this->includeCustomModel('AppProfileorders');
            $order_interval_time = $this->params->get('order_interval_time',0);
            $time = $order_interval_time;

            $days = floor($time / (24*60*60));
            $hours = floor(($time - ($days*24*60*60)) / (60*60));
            $minutes = floor(($time - ($days*24*60*60)-($hours*60*60)) / 60);
            $seconds = ($time - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) % 60;
            $date_string = $order->created_on." ".$days." day ".$hours." hour ".$minutes." minutes ".$seconds." seconds";
            $after_date = new JDate($date_string);
            $current_date = new JDate('now');
            $diff = $after_date->diff($current_date);//$current_date - $after_date;
            // reorder enable after 10 mints
            $time_interval_status = false;
            if($diff->invert == 0 && ($diff->y >0 || $diff->m > 0 || $diff->d > 0 || $diff->h > 0 || $diff->i > 0 || $diff->s > 0)){
                $time_interval_status = true;
            }
            $order_expired_date = $this->params->get('order_expired_date',0);
            $expired_string = $order->created_on." +".$order_expired_date." day ";
            $after_date = new JDate($expired_string);
            $expire_diff = $after_date->diff($current_date);

            //check reorder expired date
            $expired_status = false;
            if($expire_diff->invert == 1){
                $expired_status = true;
            }
            
            $html = '';
            if($time_interval_status && $expired_status){
                $model = F0FModel::getTmpInstance('AppProfileorders', 'J2StoreModel');
                $vars->app_id = $model->getPlugin()->extension_id;
                $vars->button_text = JText::_('J2STORE_APP_MYPROFILE_ORDER_PAY_NOW');
                $html = $this->_getLayout('profile_paylink', $vars);
            }
            return $html;
        }
    }

    
}