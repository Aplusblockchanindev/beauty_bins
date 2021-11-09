<?php
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$productURI = 'index.php?option=com_j2store&view=products&task=view&id='.$vars->product->j2store_product_id;
$myProfileURI = 'index.php?option=com_j2store&view=myprofile';
if($app->isAdmin()){
    $siteApp = $app->getInstance('site');
    $siteRouter = $siteApp->getRouter();
    $baseURL = JURI::base();
    $baseURLNew = str_replace('/administrator', '', $baseURL);
    $generatedURL = JURI::root( false, $siteRouter->build($productURI));
    $productURL = str_replace($baseURL, $baseURLNew, $generatedURL);
    $generatedProfileURL = JURI::root( false, $siteRouter->build($myProfileURI));
    $profileURL = str_replace($baseURL, $baseURLNew, $generatedProfileURL);
} else {
    $rootURL = rtrim(JURI::base(),'/');
    $subpathURL = JURI::base(true);
    if(!empty($subpathURL) && ($subpathURL != '/')) {
        $rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
    }
    $productURL = $rootURL.JRoute::_($productURI);
    $profileURL = $rootURL.JRoute::_( $myProfileURI);
}
$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$renewalDate = JFactory::getDate($vars->subscription->next_payment_on, $tz);
$renewalDate = $renewalDate->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
$next_payment_on = $vars->subscription->next_payment_on;
$end_on = $vars->subscription->end_on;
?>
<div>
    Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
<br />
Your subscription for the product <b><?php echo $vars->product->product_name; ?></b> has been renewed.
<br />
<?php if($next_payment_on == $end_on){
    ?>
    Your subscription ends on <?php echo $end_on; ?>
    <?php
} else {
    ?>
    Your next renewal will be on <?php echo $renewalDate; ?>
    <?php
} ?>
<br />
You can check the status of your subscription and view your <a href="<?php echo $profileURL; ?>">billing history</a> at any time by logging into your account.
<br />
<br />
Thank you.
<br />
<?php echo $j2_params->get('store_name'); ?>
