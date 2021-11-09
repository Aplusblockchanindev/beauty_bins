<?php
/**
 * --------------------------------------------------------------------------------
 *  APP - Profile Order
 * --------------------------------------------------------------------------------
 * @package     Joomla 3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2016 J2Store . All rights reserved.
 * @license     GNU GPL v3 or later
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */
defined('_JEXEC') or die('Restricted access');
?>
<span class="j2store-order-reorder">
		<?php
		  $printUrl = JUri::base().'index.php?option=com_j2store&view=apps&task=view&layout=view&appTask=payment&id='.$vars->app_id.'&order_id='.$vars->order_id;//.'&'.JSession::getFormToken().'=1'
        ?>
    <a href="<?php echo $printUrl;?>"><i class="fa fa-repeat"></i><?php echo  $vars->button_text ; ?></a>
</span>
