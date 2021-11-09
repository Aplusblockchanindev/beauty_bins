<?php
/**
 * --------------------------------------------------------------------------------
 * Payment Plugin - Stripe
 * --------------------------------------------------------------------------------
 * @package     Joomla 2.5 -  3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2014-19 J2Store . All rights reserved.
 * @license     GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */

//no direct access
defined('_JEXEC') or die('Restricted access');
$j2StorePlugin = \J2Store::plugin();
$plugin_data = JPluginHelper::getPlugin('j2store', $this->vars->order->orderpayment_type);
$params = new JRegistry;
$params->loadString($plugin_data->params);
$name = $params->get('display_name', JText::_(strtoupper($this->vars->order->orderpayment_type)));
?>
<div class="subscription_update_card_details_payment_title">
    <h3><?php echo $name; ?></h3>
</div>
<form id="subscription_update_card_details" method="post">
    <?php
    $html = $j2StorePlugin->eventWithHtml('LoadSubscriptionPaymentCardUpdateForm', array($this->vars->subscription, $this->vars->order, $this->vars->app_id));
    echo $html;
    ?>
    <input value="<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CONTINUE_BUTTON_LABEL'); ?>" id="button-subscription_update_card" class="button btn btn-primary" type="button">
    <input name="option" value="com_j2store" type="hidden"/>
    <input name="view" value="app" type="hidden"/>
    <input name="task" value="view" type="hidden"/>
    <input name="appTask" value="processSubscriptionPaymentCardUpdate" type="hidden"/>
    <input name="id" value="<?php echo $this->vars->app_id; ?>" type="hidden"/>
    <input name="sid" value="<?php echo $this->vars->subscription->j2store_subscription_id; ?>" type="hidden"/>
</form>
<div id="subscription_update_card_process" class="hide">
</div>