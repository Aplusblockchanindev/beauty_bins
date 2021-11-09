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
$authenticate_url = $baseURLNew.'index.php?option=com_j2store&view=app&task=view&appTask=authenticateSubscriptionPayment&id='.$vars->app_id.'&order_id='.$vars->order->order_id;
?>
<div>
    Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
Your order (#<?php echo $vars->order->order_id; ?>) for the Product <b><?php echo $vars->product->product_name; ?></b> payment is pending.
<br />
Click here to <a href="<?php echo $authenticate_url; ?>">Complete payment</a>
<br />
<br />
Thank you.
