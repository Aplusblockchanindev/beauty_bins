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
require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appmodel.php');
class J2StoreModelAppProfileorders extends J2StoreAppModel
{
    public $_element = 'app_profileorder';

    function getPlugin(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__extensions');
        $query->where('folder='.$db->q('j2store'));
        $query->where('element='.$db->q('app_profileorder'));
        $query->where('type='.$db->q('plugin'));
        $db->setQuery($query);
        return $db->loadObject();
    }
}