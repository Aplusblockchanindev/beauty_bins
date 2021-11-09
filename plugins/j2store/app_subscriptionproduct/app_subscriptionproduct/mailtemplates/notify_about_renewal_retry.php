<?php
/**
 * $vars->renewal_retry_count integer
 * $vars->renewal_retry_period string (hour/day/week/month)
 * $vars->renewal_retry_period_units integer
 * $vars->renewal_retry_on date
 * */
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$rootURL = rtrim(JURI::base(),'/');
$subpathURL = JURI::base(true);
if(!empty($subpathURL) && ($subpathURL != '/')) {
    $rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
}
$productURI = 'index.php?option=com_j2store&view=products&task=view&id='.$vars->product->j2store_product_id;
$productURL = $rootURL.JRoute::_($productURI);

$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$renewal_retry_on = JFactory::getDate($vars->renewal_retry_on, $tz);
$renewal_retry_on = $renewal_retry_on->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
We could not process the payment for the renewal of subscription to <b><?php echo $vars->product->product_name; ?></b>. We will retry the process in <?php echo $vars->renewal_retry_count; ?> <?php echo $vars->renewal_retry_period; ?>(s).
<br />
<br />
Thank you.