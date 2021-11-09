<?php
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$siteApp = $app->getInstance('site');
$siteRouter = $siteApp->getRouter();
$newURI = 'index.php?option=com_j2store&view=products&task=view&id='.$vars->product->j2store_product_id;
$baseURL = JURI::base();
$baseURLNew = str_replace('/administrator', '', $baseURL);
$generatedURL = JURI::root( false, $siteRouter->build($newURI));
$productURL = str_replace($baseURL, $baseURLNew, $generatedURL);
$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$renewalDate = JFactory::getDate($vars->subscription->next_payment_on, $tz);
$renewalDate = $renewalDate->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
We wanted to let you know that your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> trial period ends on <b><?php echo $renewalDate; ?></b> and will be renewed automatically on <?php echo $renewalDate; ?>.
<br />
<br />
Thank you.
