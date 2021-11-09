<?php
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$rootURL = rtrim(JURI::base(),'/');
$subpathURL = JURI::base(true);
if(!empty($subpathURL) && ($subpathURL != '/')) {
    $rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
}
$productURI = 'index.php?option=com_j2store&view=products&task=view&id='.$vars->product->j2store_product_id;
$myProfileURI = 'index.php?option=com_j2store&view=myprofile';
$productURL = $rootURL.JRoute::_($productURI);
$profileURL = $rootURL.JRoute::_( $myProfileURI);
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
<br />
Your card for the subscription Product <b><?php echo $vars->product->product_name; ?></b> is updated successfully.
<br />
Click here to view <a href="<?php echo $profileURL; ?>">My Profile</a>
<br />
<br />
Thank you.
