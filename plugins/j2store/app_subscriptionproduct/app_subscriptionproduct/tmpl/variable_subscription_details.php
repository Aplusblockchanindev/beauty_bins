<?php
defined('_JEXEC') or die('Restricted access');
$subscriptionproducts = $vars->subscriptionproduct;

$subscription_period_units = isset($subscriptionproducts->subscription_period_units)? $subscriptionproducts->subscription_period_units: 1;
$subscription_period = isset($subscriptionproducts->subscription_period)? $subscriptionproducts->subscription_period: 'D';
$subscription_length = isset($subscriptionproducts->subscription_length)? $subscriptionproducts->subscription_length: 0;
$subscription_signup_fee = isset($subscriptionproducts->subscription_signup_fee)? $subscriptionproducts->subscription_signup_fee: 0;
$subscription_recurring_type = isset($subscriptionproducts->recurring_type)? $subscriptionproducts->recurring_type: 'multiple';
$subscription_free_trial = isset($subscriptionproducts->subscription_free_trial)? $subscriptionproducts->subscription_free_trial: 0;
$subscription_trial_period = isset($subscriptionproducts->subscription_trial_period)? $subscriptionproducts->subscription_trial_period: 'D';

$subscription_period_string = $vars->model->getPeriodText($subscription_period);
$subscription_period_units_text = $vars->model->getPeriodUnitsText($subscription_period_units);
$subscription_period_string_text = $vars->model->getPeriodTextString($subscription_period);
$subscription_trial_period_string_text = $vars->model->getPeriodTextString($subscription_trial_period);
$plugin = JPluginHelper::getPlugin('j2store', 'app_subscriptionproduct');
$subscription_params = new JRegistry($plugin->params);
$showDuration = $subscription_params->get('show_duration', 1);
if($showDuration){
?>
<div class="afterDisplayPrice">
    <div class="subscriptionproducts">
        <span class="j2store-subscription_duration_text"><b>
            <?php if($subscription_length >= 0){?>
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
                if($subscription_free_trial > 0){
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_WITH_A').' ';
                    if($subscription_free_trial > 1){
                        echo ' '.$subscription_free_trial.' '.JText::_($subscription_trial_period_string_text.'_PLURAL');
                    } else if($subscription_free_trial == 1){
                        echo ' '.$subscription_free_trial.' '.JText::_($subscription_trial_period_string_text);
                    }
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_FREE_TRIAL');
                }
                if($vars->hasSignUpFee && $subscription_signup_fee){
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_AND_A').' ';
                    echo J2Store::product()->displayPrice($subscription_signup_fee, $vars->product);
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGN_UP_FEE');
                }
                ?>
            <?php } else {
                ?>
                <?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_FOR_LIFE_TIME').' ';
                ?>
                <?php
                if($subscription_free_trial > 0){
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_WITH_A').' ';
                    if($subscription_free_trial > 1){
                        echo ' '.$subscription_free_trial.' '.JText::_($subscription_trial_period_string_text.'_PLURAL');
                    } else if($subscription_free_trial == 1){
                        echo ' '.$subscription_free_trial.' '.JText::_($subscription_trial_period_string_text);
                    }
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_FREE_TRIAL');
                }
                if($vars->hasSignUpFee && $subscription_signup_fee){
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_TEXT_AND_A').' ';
                    echo J2Store::product()->displayPrice($subscription_signup_fee, $vars->product);
                    echo ' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGN_UP_FEE');
                }
                ?>
                <?php
            }?>
            </b></span>
    </div>
</div>
<?php } ?>