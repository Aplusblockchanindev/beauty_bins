<?php
defined('_JEXEC') or die('Restricted access');
$subscriptionproducts = $vars->subscriptionproduct;
$config = J2Store::config();

$subscription_period_units = isset($subscriptionproducts->subscription_period_units)? $subscriptionproducts->subscription_period_units: 1;
$subscription_period = isset($subscriptionproducts->subscription_period)? $subscriptionproducts->subscription_period: 'D';
$subscription_length = isset($subscriptionproducts->subscription_length)? $subscriptionproducts->subscription_length: 0;
$subscription_recurring_type = isset($subscriptionproducts->recurring_type)? $subscriptionproducts->recurring_type: 'multiple';
$subscription_signup_fee = isset($subscriptionproducts->subscription_signup_fee)? $subscriptionproducts->subscription_signup_fee: 0;
$apply_same_coupon = isset($subscriptionproducts->apply_same_coupon)? $subscriptionproducts->apply_same_coupon: '';

$subscription_period_string = $vars->model->getPeriodText($subscription_period);
$subscription_period_units_text = $vars->model->getPeriodUnitsText($subscription_period_units);
$subscription_period_string_text = $vars->model->getPeriodTextString($subscription_period);

$plugin = JPluginHelper::getPlugin('j2store', 'app_subscriptionproduct');
$subscription_params = new JRegistry($plugin->params);
$showDuration = $subscription_params->get('show_duration', 1);
$showNextRenewal = $subscription_params->get('show_next_renewal_cart', 1);
$showNonRecurringTotal = $subscription_params->get('show_non_recurring_total_cart', 1);
$showRecurringTotal = 1;
if(!$showNonRecurringTotal && $subscription_recurring_type == 'single'){
    $showRecurringTotal = 0;
}
if($showRecurringTotal){
?>
<style>
    .subscriptionproducts_cart_recurring {
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }
    .subscriptionproducts_cart_recurring:last-child {
        border-bottom: 0px solid #ddd;
        margin-bottom: 0px;
        padding-bottom: 0px;
    }
</style>
<div class="subscriptionproducts_cart_recurring">
    <?php
    if($vars->first_renewal != '' && $showNextRenewal){
        ?>
        <div class="subscriptionproducts_next_renewal">
            <?php
            $first_renewal = \J2Store\Subscription\Helper\ExpiryControl::getInstance()->getFormattedDateNextRenewal($vars->first_renewal);
            echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?>: <?php echo $first_renewal; ?>
        </div>
    <?php }
    if($vars->show_recurring_amount_in_cart){
    ?>
    <div class="subscriptionproducts_description">
        <span class="j2store-subscription_duration_amount_text">
            <?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_AMOUNT'); ?>
        </span>
        <span class="j2store-subscription_duration_text">
            <?php
            $productAmount = (($vars->order_item->orderitem_price + $vars->order_item->orderitem_option_price) * $vars->order_item->orderitem_quantity);
//            if ($vars->first_renewal != ''){
                if(!empty($vars->subscription)){
                    $productAmount = $vars->model->getRenewalAmount($vars->subscription);
                    echo J2Store::currency()->format($productAmount['renewal_amount'], $vars->order->currency_code, $vars->order->currency_value);
                } else {
                    $productAmount = $vars->model->calculateRenewalAmount($vars, $productAmount);
                   // echo J2Store::product()->displayPrice($productAmount, $vars->product);
                    echo J2Store::currency()->format($productAmount['renewal_amount'], $vars->order->currency_code, $vars->order->currency_value);
                }
//            } else {
//                echo J2Store::currency()->format($productAmount);
//            }

            if($showDuration){
             ?>
            <?php if($subscription_length >= 0){ ?>
            <?php
                if($subscription_recurring_type != 'single'){
                    echo $subscription_period_units_text; ?> <?php echo $subscription_period_string;
                }
                if($subscription_length > 0) {
                    echo ' ' . JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_FOR') . ' ';
                }
                if($subscription_length > 1){
                    echo ' '.$subscription_length.' '.JText::_($subscription_period_string_text.'_PLURAL');
                } else if($subscription_length == 1){
                    echo ' '.$subscription_length.' '.JText::_($subscription_period_string_text);
                }
            ?>
        <?php } else {
                    ?>
                    <?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_FOR_LIFE_TIME') . ' ';
                    ?>
                    <?php
                    if ($subscription_signup_fee) {
                        echo ' ' . JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_AND_A') . ' ';
                        echo J2Store::product()->displayPrice($subscription_signup_fee, $vars->product);
                        echo ' ' . JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGN_UP_FEE');
                    }
                }
                if($vars->recurring_change != ''){
                    echo ' '.$vars->recurring_change;
                }
            ?>
            <?php
        }?>
            </span>
    </div>
    <?php
    }
    if($vars->show_recurring_discount_in_cart && $vars->first_renewal != ''){
        if($apply_same_coupon == '1'){
            echo $vars->model->getRenewalDiscountText($productAmount['renewal_discount'], $vars->subscription);
        } else {
            echo $vars->model->getRenewalDiscountText('global', $vars->subscription);
        }
    }
    ?>
</div>
<?php } ?>