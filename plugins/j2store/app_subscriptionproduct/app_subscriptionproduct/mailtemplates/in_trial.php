<?php
defined('_JEXEC') or die('Restricted access');

$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$renewalDate = JFactory::getDate($vars->subscription->next_payment_on, $tz);
$renewalDate = $renewalDate->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
$trialEndDate = JFactory::getDate($vars->subscription->trial_end_on, $tz);
$trialEndDate = $trialEndDate->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
Your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> is activated for trial.
<br />
Your subscription trial ends on <?php echo $trialEndDate; ?>
<br />
Your subscription first renewal on <?php echo $renewalDate; ?>
<br />
<br />
Thank you.
