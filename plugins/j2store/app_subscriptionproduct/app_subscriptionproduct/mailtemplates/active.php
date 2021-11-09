<?php
defined('_JEXEC') or die('Restricted access');
?>
<div>
Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
<br />
Your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> is activated.
<br />
Your subscription ends on <?php echo $vars->subscription->end_on; ?>
<br />
<br />
Thank you.
