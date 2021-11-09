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
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
<br />
Your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> is near to expire date.
<br />
Your subscription ends on <?php echo $vars->subscription->end_on; ?>
<br />
For continue, purchase a new subscription by clicking here <a href="<?php echo $productURL; ?>"><?php echo $vars->product->product_name; ?></a>
<br />
<br />
Thank you.
