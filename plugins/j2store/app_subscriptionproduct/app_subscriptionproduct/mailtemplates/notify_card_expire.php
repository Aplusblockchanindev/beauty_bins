<?php
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$siteApp = $app->getInstance('site');
$siteRouter = $siteApp->getRouter();
$newURI = 'index.php?option=com_j2store&view=products&task=view&id='.$vars->product->j2store_product_id;
$myProfileURI = 'index.php?option=com_j2store&view=myprofile';
$baseURL = JURI::base();
$baseURLNew = str_replace('/administrator', '', $baseURL);
$generatedURL = JURI::root( false, $siteRouter->build($newURI));
$productURL = str_replace($baseURL, $baseURLNew, $generatedURL);
$generatedProfileURL = JURI::root( false, $siteRouter->build($myProfileURI));
$profileURL = str_replace($baseURL, $baseURLNew, $generatedProfileURL);
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
<br />
Your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> is failed for renewal due to card expire.
<br />
For continue, please update your card by clicking <b>Update card</b> in your subscription listing.
<br />
Click here to view <a href="<?php echo $profileURL; ?>">My Profile</a>
<br />
<br />
Thank you.
