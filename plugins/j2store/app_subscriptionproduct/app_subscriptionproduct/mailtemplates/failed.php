<?php
defined('_JEXEC') or die('Restricted access');
?>
<div>
    Hi <?php echo $vars->user->get('name'); ?>,
</div>
<br />
Your subscription for the Product <b><?php echo $vars->product->product_name; ?></b> is Failed.
<br />
<br />
Thank you.
