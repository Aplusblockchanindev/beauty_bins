<?php
/**
 * --------------------------------------------------------------------------------
 * Payment Plugin - Stripe
 * --------------------------------------------------------------------------------
 * @package     Joomla 2.5 -  3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2014-19 J2Store . All rights reserved.
 * @license     GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class plgJ2StorePayment_stripeInstallerScript {

	function preflight( $type, $parent ) {
		if(!JComponentHelper::isEnabled('com_j2store')) {
			Jerror::raiseWarning(null, 'J2Store not found. Please install J2Store before installing this plugin');
			return false;
		}
		
		jimport('joomla.filesystem.file');
		$version_file = JPATH_ADMINISTRATOR.'/components/com_j2store/version.php';
		if (JFile::exists ( $version_file )) {
			require_once($version_file);
			// abort if the current J2Store release is older
			if (version_compare ( J2STORE_VERSION, '2.7.3', 'lt' )) {
				Jerror::raiseWarning ( null, 'You are using an old version of J2Store. Please upgrade to the latest version' );
				return false;
			}
		} else {
			Jerror::raiseWarning ( null, 'J2Store not found or the version file is not found. Make sure that you have installed J2Store before installing this plugin' );
			return false;
		}

        //check subscription app exists
        $subscriptionApp = JPluginHelper::getPlugin('j2store', 'app_subscriptionproduct');
        if($subscriptionApp){
            //check subscription app version
            $subscriptionExtension = $this->getVersionOfPlugin('app_subscriptionproduct');
            if (version_compare($subscriptionExtension->version, '2.0.40', 'lt')) {
                Jerror::raiseWarning(null, 'You need at least J2Store Subscription App version 2.0.40 for this plugin to work');
                return false;
            }
        }
	}

    /**
     * To get extension version
     * */
    protected function getVersionOfPlugin($element){
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__extensions');
        $query->where('type = '.$db->quote('plugin'));
        $query->where('folder = '.$db->quote('j2store'));
        $query->where('element = '.$db->quote($element));
        $db->setQuery($query);
        $result = $db->loadObject();
        return json_decode($result->manifest_cache);
    }

}