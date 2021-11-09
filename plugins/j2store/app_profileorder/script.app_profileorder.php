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

class plgJ2StoreApp_profileorderInstallerScript
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
            if (version_compare(J2STORE_VERSION, '3.2.14', 'lt')) {
                Jerror::raiseWarning(null, 'You need at least J2Store version 3.2.14 for this plugin to work');
                return false;
            }
        } else {
            Jerror::raiseWarning(null, 'J2Store not found or the version file is not found. Make sure that you have installed J2Store before installing this plugin');
            return false;
        }
    }
}