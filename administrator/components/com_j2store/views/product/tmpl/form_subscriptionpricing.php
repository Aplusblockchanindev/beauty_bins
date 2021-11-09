<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;
//pricing options
$pricing_calculator = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[pricing_calculator]')
    ->value($this->variant->pricing_calculator)
    ->setPlaceHolders(J2Store::product()->getPricingCalculators())
    ->getHtml();

$subscription_free_trial = isset($this->subscription_data->subscription_free_trial)? $this->subscription_data->subscription_free_trial: 0;
$subscription_signup_fee = isset($this->subscription_data->subscription_signup_fee)? $this->subscription_data->subscription_signup_fee: '';
$subscription_length = isset($this->subscription_data->subscription_length)? $this->subscription_data->subscription_length: 0;
$add_user_groups = isset($this->subscription_data->add_user_groups)? $this->subscription_data->add_user_groups: array();
$remove_user_groups = isset($this->subscription_data->remove_user_groups)? $this->subscription_data->remove_user_groups: array();

$add_user_groups_field_name = $this->form_prefix.'[params][subscriptionproduct][add_user_groups][]';
$add_user_groups = JHtml::_('access.usergroup', $add_user_groups_field_name, $add_user_groups, array(
    'multiple' => 'multiple',
    'size'     => 8,
    'class'    => 'input-large'
), false);
$remove_user_groups_field_name = $this->form_prefix.'[params][subscriptionproduct][remove_user_groups][]';
$remove_user_groups = JHtml::_('access.usergroup', $remove_user_groups_field_name, $remove_user_groups, array(
    'multiple' => 'multiple',
    'size'     => 8,
    'class'    => 'input-large'
), false);
?>

<div class="j2store-product-pricing">
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_REGULAR_PRICE'), 'price' ,array('class'=>'control-label')); ?>
        <?php echo J2Html::price($this->form_prefix.'[price]', $this->variant->price, array('class'=>'input')); ?>
        <!--		--><?php //echo J2Html::text($this->form_prefix.'[params][subscriptionproduct][subscription_period_units]', $subscription_period_units,array('class'=>'input-small ')); ?>
        <?php echo $this->subscription_period_units; ?>
        <?php echo $this->subscription_periods; ?>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_LENGTH'), 'subscription_length',array('class'=>'control-label')); ?>
        <?php echo $this->subscription_length; ?>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_ADD_USER_GROUPS'), 'add_user_groups',array('class'=>'control-label')); ?>
        <?php echo $add_user_groups; ?>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_REMOVE_USER_GROUPS'), 'remove_user_groups',array('class'=>'control-label')); ?>
        <?php echo $remove_user_groups; ?>
    </div>

    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGN_UP_FEE'), 'subscription_signup_fee',array('class'=>'control-label')); ?>
        <?php echo J2Html::price($this->form_prefix.'[params][subscriptionproduct][subscription_signup_fee]', $subscription_signup_fee, array('class'=>'input')); ?>
        <div class="controls">
            <?php
            $field = new stdClass();
            $field->value = 1;
            $field->text = JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGNUP_FEE_ON_EACH_PURCHASE');
            $data = array($field);
            $selected = isset($this->subscription_data->signup_fee_on_each_purchase)? $this->subscription_data->signup_fee_on_each_purchase: '';
            $fieldName = $this->form_prefix.'[params][subscriptionproduct][signup_fee_on_each_purchase]';
            echo J2Html::checkboxlist($data, $fieldName, array('class' => 'input'), 'value', 'text', $selected); ?>
            <div class="alert alert-info">
                <p><?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION_SIGNUP_FEE_ON_EACH_PURCHASE_HINT'); ?></p>
            </div>
        </div>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_FREE_TRIAL'), 'subscription_free_trial',array('class'=>'control-label')); ?>
        <?php echo J2Html::text($this->form_prefix.'[params][subscriptionproduct][subscription_free_trial]', $subscription_free_trial, array('class'=>'input')); ?>
        <?php echo $this->subscription_trial_periods; ?>
        <div class="controls">
            <br/>
            <div class="alert alert-info">
                <?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION_FREE_TRIAL_HINT'); ?>
            </div>
        </div>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SUBSCRIPTION_RENEWAL_COUPON_LABEL'), 'subscription_apply_same_coupon',array('class'=>'control-label')); ?>
        <div class="input-checkbox">
            <?php
            $field = new stdClass();
            $field->value = 1;
            $field->text = JText::_('J2STORE_PRODUCT_SUBSCRIPTION_RENEWAL_COUPON_APPLY_SAME_COUPON');
            $data = array($field);
            $selected = isset($this->subscription_data->apply_same_coupon)? $this->subscription_data->apply_same_coupon: '';
            $fieldName = $this->form_prefix.'[params][subscriptionproduct][apply_same_coupon]';
            echo J2Html::checkboxlist($data, $fieldName, array('class' => 'input'), 'value', 'text', $selected); ?>
            <div class="alert alert-info">
                <p><?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION_RENEWAL_COUPON_APPLY_SAME_COUPON_HINT'); ?></p>
            </div>
        </div>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_SET_ADVANCED_PRICING'), 'sale_price',array('class'=>'control-label')); ?>
        <!-- Link to advanced pricing options. Opens as a popup. -->
        <?php echo J2StorePopup::popup( "index.php?option=com_j2store&view=products&task=setproductprice&variant_id=".$this->variant->j2store_variant_id."&layout=productpricing&tmpl=component", JText::_( "J2STORE_PRODUCT_SET_PRICES" ), array('class'=>'btn btn-success'));?>
    </div>
    <div class="control-group">
        <?php echo J2Html::label(JText::_('J2STORE_PRODUCT_PRICING_CALCULATOR'), 'price_calculator',array('class'=>'control-label')); ?>
        <?php //dropdown list: pre-populate it with Standard (to start with). We will extend this at a later point of time ?>
        <?php echo $pricing_calculator;?>
    </div>
</div>

<div class="alert alert-info">
    <h4><?php echo JText::_('J2STORE_QUICK_HELP'); ?></h4>
    <?php echo JText::_('J2STORE_PRODUCT_PRICE_HELP_TEXT'); ?>
</div>

<script type="text/javascript">
    (function($) {
        /**  on load will create footer list **/
        $(document).ready(function(){
            $("#j2store_jformattribsj2storeparamssubscriptionproductsubscription_period_units, #j2store_jformattribsj2storeparamssubscriptionproductsubscription_period")
                .change(function(){
                    var units = $('#j2store_jformattribsj2storeparamssubscriptionproductsubscription_period_units');
                    var period = $('#j2store_jformattribsj2storeparamssubscriptionproductsubscription_period');
                    var lenghtSelectBox = $('#j2store_jformattribsj2storeparamssubscriptionproductsubscription_length');
                    var daysText = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS'); ?>';
                    var daysTextPlural = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS_PLURAL'); ?>';
                    var weeksText = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS'); ?>';
                    var weeksTextPlural = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS_PLURAL'); ?>';
                    var monthsText = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS'); ?>';
                    var monthsTextPlural = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS_PLURAL'); ?>';
                    var yearsText = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR'); ?>';
                    var yearsTextPlural = '<?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR_PLURAL'); ?>';
                    var unitsValue = units.val();
                    var optionData = '';
                    if(period.val() == 'D'){
                        var maxlength = 90;
                        optionData = getLengthOptions(unitsValue, maxlength, daysText, daysTextPlural);
                    } else if(period.val() == 'W'){
                        var maxlength = 52;
                        optionData = getLengthOptions(unitsValue, maxlength, weeksText, weeksTextPlural);
                    } else if(period.val() == 'M'){
                        var maxlength = 24;
                        optionData = getLengthOptions(unitsValue, maxlength, monthsText, monthsTextPlural);
                    } else {
                        var maxlength = 5;
                        optionData = getLengthOptions(unitsValue, maxlength, yearsText, yearsTextPlural);
                    }
                    lenghtSelectBox.html(optionData);
                    lenghtSelectBox.trigger("liszt:updated");
                });
            $("#j2store_jformattribsj2storeparamssubscriptionproductsubscription_period_units").trigger('change');
            $('#j2store_jformattribsj2storeparamssubscriptionproductsubscription_length').val('<?php echo $subscription_length; ?>').trigger("liszt:updated");

        });

        function getLengthOptions(unitsValue, maxlength, singularText, pluralText){
            var optionData = '<option value="0"><?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_NEVER_EXPIRE'); ?></option>';
            for(var i = 1; i < maxlength; i++){
                var multiply = i*unitsValue;
                if(multiply <= maxlength){
                    if(multiply == 1){
                        optionData += '<option value="'+multiply+'">'+multiply+' '+singularText+'</option>';
                    } else {
                        optionData += '<option value="'+multiply+'">'+multiply+' '+pluralText+'</option>';
                    }
                } else {
                    break;
                }
            }

            return optionData;
        }
    })(j2store.jQuery);
</script>