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
/** ensure this file is being included by a parent file */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/version.php');

if (version_compare ( J2STORE_VERSION, '3.0.0', 'ge' )) {
	// we are using latest version.
	require_once (JPATH_SITE . '/plugins/j2store/payment_stripe/stripev3.php');	
} else {
	require_once (JPATH_SITE . '/plugins/j2store/payment_stripe/stripev2.php');	
}