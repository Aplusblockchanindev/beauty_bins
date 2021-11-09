<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;
//load the language file
JFactory::getLanguage ()->load ( 'plg_j2store_app_subscriptionproduct', JPATH_ADMINISTRATOR );
$this->variant = $this->item->variants;

//lengths
$this->lengths = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[length_class_id]')
    ->value($this->variant->length_class_id)
    ->setPlaceHolders(array(''=>JText::_('J2STORE_SELECT_OPTION')))
    ->hasOne('Lengths')
    ->setRelations(
        array (
            'fields' => array (
                'key'=>'j2store_length_id',
                'name'=>'length_title'
            )
        )
    )->getHtml();

//weights

$this->weights = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[weight_class_id]')
    ->value($this->variant->weight_class_id)
    ->setPlaceHolders(array(''=>JText::_('J2STORE_SELECT_OPTION')))
    ->hasOne('Weights')
    ->setRelations(
        array (
            'fields' => array (
                'key'=>'j2store_weight_id',
                'name'=>'weight_title'
            )
        )
    )->getHtml();

//backorder
$this->allow_backorder = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[allow_backorder]')
    ->value($this->variant->allow_backorder)
    ->setPlaceHolders(
        array('0' => JText::_('COM_J2STORE_DO_NOT_ALLOW_BACKORDER'),
            '1' => JText::_('COM_J2STORE_DO_ALLOW_BACKORDER'),
            '2' => JText::_('COM_J2STORE_ALLOW_BUT_NOTIFY_CUSTOMER')
        ))
    ->getHtml();

$this->availability =J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[availability]')
    ->value($this->variant->availability)
    ->default(1)
    ->setPlaceHolders(
        array('0' => JText::_('COM_J2STORE_PRODUCT_OUT_OF_STOCK') ,
            '1'=> JText::_('COM_J2STORE_PRODUCT_IN_STOCK') ,
        )
    )
    ->getHtml();
$this->subscription_data = $this->item->params->get('subscriptionproduct', '');
$subscription_period = isset($this->subscription_data->subscription_period)? $this->subscription_data->subscription_period: 'D';
$subscription_length = isset($this->subscription_data->subscription_length)? $this->subscription_data->subscription_length: 0;
$this->subscription_periods = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[params][subscriptionproduct][subscription_period]')
    ->value($subscription_period)
    ->default('D')
    ->setPlaceHolders(
        array('D' => JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS') ,
            'M'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS') ,
            'W'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS') ,
            'Y'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR') ,
        )
    )
    ->getHtml();

$subscription_trial_period = isset($this->subscription_data->subscription_trial_period)? $this->subscription_data->subscription_trial_period: 'D';
$this->subscription_trial_periods = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[params][subscriptionproduct][subscription_trial_period]')
    ->value($subscription_trial_period)
    ->default('D')
    ->setPlaceHolders(
        array('D' => JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS') ,
            'M'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS') ,
            'W'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS') ,
            'Y'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR') ,
        )
    )
    ->getHtml();


$subscription_period_unit = isset($this->subscription_data->subscription_period_units)? $this->subscription_data->subscription_period_units: '1';
$this->subscription_period_units = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[params][subscriptionproduct][subscription_period_units]')
    ->value($subscription_period_unit)
    ->default('1')
    ->setPlaceHolders(
        array('1' => JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY') ,
            '2'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY2') ,
            '3'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY3') ,
            '4'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY4') ,
            '5'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY5') ,
            '6'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY6') ,
        )
    )
    ->getHtml();

$subscription_length_array = array();
for($l = 1 ; $l < 91; $l++){
    $subscription_length_array[$l] = $l.' '.JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_EXPIRE_TIME');
}
$this->subscription_length = J2Html::select()->clearState()
    ->type('genericlist')
    ->name($this->form_prefix.'[params][subscriptionproduct][subscription_length]')
    ->value($subscription_length)
    ->default(0)
    ->setPlaceHolders(
        $subscription_length_array
    )
    ->getHtml();

?>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#generalTab" data-toggle="tab"><i class="fa fa-home"></i>
                        <?php echo JText::_('J2STORE_PRODUCT_TAB_GENERAL'); ?>
                    </a>
                </li>
                <li><a href="#pricingTab" data-toggle="tab"><i class="fa fa-dollar"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_PRICE'); ?></a></li>
                <li><a href="#inventoryTab" data-toggle="tab"><i class="fa fa-signal"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_INVENTORY'); ?></a></li>
                <li><a href="#imagesTab" data-toggle="tab"><i class="fa fa-file-image-o"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_IMAGES'); ?></a></li>
                <li><a href="#shippingTab" data-toggle="tab"><i class="fa fa-truck"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_SHIPPING'); ?></a></li>
                <li><a href="#optionsTab" data-toggle="tab"><i class="fa fa-sitemap"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_OPTIONS'); ?></a></li>
                <li><a href="#filterTab" data-toggle="tab"><i class="fa fa-filter"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_FILTER'); ?></a></li>
                <li><a href="#relationsTab" data-toggle="tab"><i class="fa fa-group"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_RELATIONS'); ?></a></li>
                <li><a href="#appsTab" data-toggle="tab"><i class="fa fa-group"></i> <?php echo JText::_('J2STORE_PRODUCT_TAB_APPS'); ?></a></li>

            </ul>
            <!-- / Tab content starts -->
            <div class="tab-content">
                <div class="tab-pane active" id="generalTab">
                    <input type="hidden" name="<?php echo $this->form_prefix.'[j2store_variant_id]'; ?>" value="<?php echo $this->variant->j2store_variant_id; ?>" />
                    <?php echo $this->loadTemplate('general');?>
                </div>
                <div class="tab-pane" id="pricingTab">
                    <?php  echo $this->loadTemplate('subscriptionpricing');?>
                </div>
                <div class="tab-pane" id="inventoryTab">
                    <?php  echo $this->loadTemplate('inventory');?>
                </div>

                <div class="tab-pane" id="imagesTab">
                    <?php  echo $this->loadTemplate('images');?>
                </div>
                <div class="tab-pane" id="shippingTab">
                    <?php  echo $this->loadTemplate('shipping');?>
                </div>
                <div class="tab-pane" id="optionsTab">
                    <?php  echo $this->loadTemplate('options');?>
                </div>
                <div class="tab-pane" id="filterTab">
                    <?php  echo $this->loadTemplate('filters');?>
                </div>
                <div class="tab-pane" id="relationsTab">
                    <?php echo $this->loadTemplate('relations');?>
                </div>
                <div class="tab-pane" id="appsTab">
                    <?php echo $this->loadTemplate('apps');?>
                </div>

            </div>
            <!-- / Tab content Ends -->
        </div> <!-- /tabbable -->
    </div>
</div>
