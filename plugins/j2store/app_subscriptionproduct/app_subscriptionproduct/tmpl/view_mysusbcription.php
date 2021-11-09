<?php
defined('_JEXEC') or die('Restricted access');
$item = $this->vars->subscription;
$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$app = JFactory::getApplication();

if($item){
    $product = $this->vars->product;
    $order_table = $this->vars->order;
    $orderStatus = F0FTable::getAnInstance('OrderStatus','J2StoreTable');
    $orderStatus->load(array(
        'j2store_orderstatus_id' => $order_table->order_state_id
    ));
    $subscriptionOrder = $this->vars->subscriptionOrder;
    $orderinfo = $subscriptionOrder->getOrderInformation();

    $subscriptionHistory = $this->vars->subscriptionHistory;
    $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();

    $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS');
    switch ($item->period){
        case 'D':
            $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS');
            $subscription_period_string_text = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS';
            break;
        case 'W':
            $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS');
            $subscription_period_string_text = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS';
            break;
        case 'M':
            $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS');
            $subscription_period_string_text = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS';
            break;
        case 'Y':
            $subscription_period_string = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR');
            $subscription_period_string_text = 'COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR';
            break;
    }
    $subscription_period_units_text = $item->period_units;
    switch ($item->period_units){
        case '1':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY');
            break;
        case '2':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY2');
            break;
        case '3':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY3');
            break;
        case '4':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY4');
            break;
        case '5':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY5');
            break;
        case '6':
            $subscription_period_units_text = JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_PERIOD_UNITS_EVERY6');
            break;
    }

    $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
?>
<div class="j2store_subscription_con">
    <div class="j2store_subscription_title">
        <h4><?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION').' #'.$item->j2store_subscription_id.' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_DETAILS'); ?></h4>
    </div>
    <div class="j2store_subscription_content">
        <div class="row-fluid">
            <div class="span8">
                <div class="span6">
                    <div class="panel panel-solid-success order-general-information">
                        <div class="panel-body">

                            <dl class="dl-horizontal">
                                <dt><?php echo JText::_('J2STORE_SUBSCRIPTION_ID'); ?></dt>
                                <dd>
                                    <?php echo $item->j2store_subscription_id; ?>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'); ?></dt>
                                <dd>
                                    <?php
                                    echo $this->vars->order_item->orderitem_name;
                                    ?>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS_SUBSCRIPTION'); ?></dt>
                                <dd>
                                    <?php
                                    $status = $subsStatusObj->getStatus($item->status);
                                    ?>
                                    <span class="label <?php echo $status->status_cssclass; ?> order-state-label">
						            <?php echo JText::_($status->status_name); ?>
                                    </span>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_RECURRING'); ?></dt>
                                <dd class="can_edit">
                                    <?php
                                    echo $subscription_period_units_text.' '.$subscription_period_string;
                                    ?>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'); ?></dt>
                                <dd>
                                    <?php
                                    $date = JFactory::getDate($item->start_on, $tz);
                                    echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    ?>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'); ?></dt>
                                <dd>
                                    <?php
                                    if($item->subscription_length > 0){
                                        $date = JFactory::getDate($item->end_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    } else {
                                        echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_NEVER_EXPIRE');
                                    }                                    
                                    ?>
                                </dd>
                                <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?></dt>
                                <dd>
                                    <?php
                                    if($item->next_payment_on < $item->end_on || $item->subscription_length == 0){
                                        $date = JFactory::getDate($item->next_payment_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </dd>
                                <?php
                                if($item->trial_start_on != '0000-00-00 00:00:00' && $item->trial_end_on != '0000-00-00 00:00:00'){
                                    ?>
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_TRIAL_START_ON'); ?></dt>
                                    <dd>
                                        <?php
                                        $date = JFactory::getDate($item->trial_start_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                        ?>
                                    </dd>
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_TRIAL_END_ON'); ?></dt>
                                    <dd>
                                        <?php
                                        $date = JFactory::getDate($item->trial_end_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                        ?>
                                    </dd>
                                <?php
                                }
                                ?>
                                <dt><?php echo JText::_('J2STORE_PAYMENT_METHOD'); ?></dt>
                                <dd><?php echo JText::_($order_table->orderpayment_type); ?></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="span12">
                        <?php if(in_array($item->status, array('active', 'in_trial'))){
                            ?>
                            <button type="button" class="btn btn-warning" onclick="cancelSubscription('<?php echo $item->j2store_subscription_id; ?>')"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL_SUBSCRIPTION'); ?></button>
                            <?php
                        } ?>
                        <?php
                        $parentOrder = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
                        $parentOrder->load(array('order_id' => $item->order_id));
                        echo J2Store::plugin()->eventWithHtml('DisplayAdditionalActionInSubscriptionDetail', array( $subscriptionOrder, $parentOrder, $item ) );
                        ?>
                        <div class="j2store_susbcription_message hide">
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="panel-heading">
                        <h4>
                            <?php echo JText::_('J2STORE_CUSTOMER_INFORMATION'); ?>
                        </h4>
                    </div>
                    <?php
                    $availableShippingAddress = 0;
                    if(isset($orderinfo->j2store_orderinfo_id) && $orderinfo->j2store_orderinfo_id > 0 && !empty($orderinfo->shipping_country_id) /*&& !empty($this->orderinfo->shipping_zone_id)*/):
                        $availableShippingAddress = 1;
                    endif;
                    ?>
                    <table class="table table-bordered addresses">
                        <tr>
                            <th width="<?php echo ($availableShippingAddress == 1)? "25%": "50%" ?>">
                                <?php echo JText::_('J2STORE_BILLING_ADDRESS');?>
                            </th>
                            <?php
                            if($availableShippingAddress){
                            ?>
                                <th width="25%">
                                    <?php echo JText::_('J2STORE_SHIPPING_ADDRESS');?>
                                </th>
                            <?php
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>
                                <?php $userDetails = JFactory::getUser($item->user_id); ?>
                                <?php echo '<strong>'.$orderinfo->billing_first_name." ".$orderinfo->billing_last_name."</strong>"; ?>
                                <br/>
                                <?php echo $orderinfo->billing_address_1;?> <?php echo $orderinfo->billing_address_2 ? $orderinfo->billing_address_2 : "<br/>";?>
                                <?php echo $orderinfo->billing_city;?><br /> <?php echo $orderinfo->billing_zone_name ? $orderinfo->billing_zone_name.'<br />' : "";?>
                                <?php echo !empty($orderinfo->billing_zip) ? $orderinfo->billing_zip.'<br />': '';?>
                                <?php echo $orderinfo->billing_country_name." <br/> ".JText::_('J2STORE_TELEPHONE').":";?>
                                <?php echo $orderinfo->billing_phone_1;
                                echo $orderinfo->billing_phone_2 ? '<br/> '.$orderinfo->billing_phone_2 : "<br/> ";
                                echo '<br/> ';
                                echo $userDetails->get('email');
                                echo '<br/> ';
                                echo $orderinfo->billing_company ? JText::_('J2STORE_ADDRESS_COMPANY_NAME').':&nbsp;'.$orderinfo->billing_company."</br>" : "";
                                echo $orderinfo->billing_tax_number ? JText::_('J2STORE_ADDRESS_TAX_NUMBER').':&nbsp;'.$orderinfo->billing_tax_number."</br>" : "";
                                ?>
                                <?php echo J2Store::getSelectableBase()->getFormatedCustomFields($orderinfo, 'customfields', 'billing'); ?>
                            </td>
                            <?php
                            if($availableShippingAddress){
                            ?>
                            <td>
                                <?php echo '<strong>'.$orderinfo->shipping_first_name." ".$orderinfo->shipping_last_name."</strong><br/>"; ?>
                                <?php echo $orderinfo->shipping_address_1;?>
                                <?php echo $orderinfo->shipping_address_2 ? $orderinfo->shipping_address_2 : "<br/>";?>
                                <?php echo $orderinfo->shipping_city;?><br />
                                <?php echo $orderinfo->shipping_zone_name ? $orderinfo->shipping_zone_name.'<br />' : "";?>
                                <?php echo !empty($orderinfo->shipping_zip) ? $orderinfo->shipping_zip.'<br />': '';?>
                                <?php echo $orderinfo->shipping_country_name." <br/> ".JText::_('J2STORE_TELEPHONE').":";?>
                                <?php echo $orderinfo->shipping_phone_1;
                                echo $orderinfo->shipping_phone_2 ? '<br/> '.$orderinfo->shipping_phone_2 : "<br/> ";
                                echo '<br/> ';
                                echo $subscriptionOrder->user_email;
                                echo '<br/> ';
                                echo $orderinfo->shipping_company ? JText::_('J2STORE_ADDRESS_COMPANY_NAME').':&nbsp;'.$orderinfo->shipping_company."</br>" : "";
                                echo $orderinfo->shipping_tax_number ? JText::_('J2STORE_ADDRESS_TAX_NUMBER').':&nbsp;'.$orderinfo->shipping_tax_number."</br>" : "";
                                ?>
                                <?php echo J2Store::getSelectableBase()->getFormatedCustomFields($orderinfo, 'customfields', 'shipping'); ?>
                            </td>
                            <?php
                            } ?>
                        </tr>
                    </table>
                </div>
                <div class="span12">
                    <?php echo $this->loadTemplate('summary'); ?>
                </div>
                <div class="span12">
                    <?php  echo $this->loadTemplate('orders'); ?>
                </div>
            </div>
            <div class="span4">
                <div class="panel-heading">
                    <h4><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_SUBSCRIPTION_HISTORY'); ?></h4>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed table-bordered">
                        <thead>
                        <tr>
                            <th><?php echo JText::_("J2STORE_ORDER_DATE"); ?></th>
                            <th><?php echo JText::_("J2STORE_ORDER_COMMENT"); ?></th>
                            <th><?php echo JText::_("J2STORE_ORDER_STATUS"); ?></th>

                        </tr>
                        </thead>
                        <?php foreach($subscriptionHistory as $history):?>
                            <tr>
                                <td>
                                    <?php
                                    $date = JFactory::getDate($history->created_at, $tz);
                                    echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true); ?>
                                </td>
                                <td>
                                    <?php
                                    $history_data = isset($history->metavalue)? json_decode($history->metavalue) : '';
                                    echo isset($history_data->comment)? JText::_($history_data->comment) : '-'; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusID = isset($history_data->status)? JText::_($history_data->status) : 0;
                                    $status = $subsStatusObj->getStatus($statusID);
                                    ?>
                                    <span class="label <?php echo $status->status_cssclass; ?> order-state-label">
						            <?php echo JText::_($status->status_name); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach;?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        if(typeof(j2store) == 'undefined') {
            var j2store = {};
        }
        if(typeof(jQuery) != 'undefined') {
            jQuery.noConflict();
        }
        if(typeof(j2store.jQuery) == 'undefined') {
            j2store.jQuery = jQuery.noConflict();
        }

        function cancelSubscription(id){
            (function($) {
                $(document).ready(function() {
                    $.ajax({
                        type : 'post',
                        url :  'index.php',
                        data : {
                            'option': 'com_j2store',
                            'view': 'apps',
                            'task': 'view',
                            'appTask': 'cancelSubscription',
                            'id': '<?php echo $this->vars->id; ?>',
                            'sid': id
                        },
                        dataType : 'json',
                        success : function(data) {
                            if(data.status == '1'){
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                                location.reload();
                            } else {
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                            }
                        }
                    });
                });
            })(j2store.jQuery);
        }
    </script>
<?php } else {
    ?>
    <div class="j2store_subscription_con">
        <?php echo JText::_('J2STORE_SUBSCRIPTION_INVALID'); ?>
    </div>
    <?php
} ?>