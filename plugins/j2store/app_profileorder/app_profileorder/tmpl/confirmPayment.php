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
defined( '_JEXEC' ) or die( 'Restricted access' );

?>

<div class="j2store">
    <?php if(!isset($this->error)): ?>
        <!--    ORDER SUMMARY   -->
        <?php if(isset($vars->order)): ?>
            <div class="j2storeOrderSummary">
                <?php $this->_getLayout('cartsummery',$vars);//echo $this->loadAnyTemplate('site:com_j2store/checkout/default_cartsummary'); ?>
            </div>
        <?php endif; ?>

        <?php echo J2Store::plugin()->eventWithHtml('BeforeCheckoutConfirm', array($vars)); ?>

        <?php if(isset($vars->plugin_html)): ?>
            <!--    PAYMENT METHOD   -->
            <h3>
                <?php echo JText::_("J2STORE_PAYMENT_METHOD"); ?>
            </h3>

            <div class="payment">
                <?php echo $vars->plugin_html; ?>
            </div>
        <?php endif; ?>
        <?php if(isset($vars->free_redirect) && JString::strlen($vars->free_redirect) > 5): ?>

            <form action="<?php echo JRoute::_('index.php?option=com_j2store&view=checkout&task=confirmPayment') ?>" method="post" >
                <input type="submit" class="btn btn-primary" value="<?php echo JText::_('J2STORE_PLACE_ORDER'); ?>" />

                <input type="hidden" name="option" value="com_j2store" />
                <input type="hidden" name="view" value="checkout" />
                <input type="hidden" name="task" value="confirmPayment" />
            </form>
        <?php endif;?>
    <?php else: ?>
        <?php echo $vars->error; ?>
    <?php endif; ?>
    <?php echo J2Store::plugin()->eventWithHtml('AfterCheckoutConfirm', array($vars)); ?>
</div>