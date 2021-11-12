<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal', 'a.modal');
$item = $this->vars->subscription;
$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
$app = JFactory::getApplication();
if($item){
    $document = JFactory::getDocument();
    $document->addScript(JUri::root().'media/plg_j2store_app_subscriptionproduct/js/script.js');

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

    $subscription_periods = J2Html::select()->clearState()
        ->type('genericlist')
        ->name('period')
        ->value($item->period)
        ->default('D')
        ->setPlaceHolders(
            array('D' => JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_DAYS') ,
                'M'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_MONTHS') ,
                'W'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WEEKS') ,
                'Y'=> JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_YEAR') ,
            )
        )
        ->getHtml();

    $subscription_period_units = J2Html::select()->clearState()
        ->type('genericlist')
        ->name('period_units')
        ->value($item->period_units)
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
    <?php
    $expiryControl = \J2Store\Subscription\Helper\ExpiryControl::getInstance();
    $is_stopped_in_middle_of_renewal = $expiryControl->isStoppedInMiddleOfRenewalProcess($item);
    if($is_stopped_in_middle_of_renewal){
        ?>
        <div class="j2store_subscription_renewal_stopped_msg">
            <div class="alert alert-warning"><?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WARNING_RENEWAL_STOPPED_IN_THE_MIDDLE'); ?> <button id="retry_renewal_process_btn" type="button" onclick="retryRenewalProcess()" class="btn btn-warning"><?php echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_WARNING_RENEWAL_STOPPED_IN_THE_MIDDLE_RETRY_RENEWAL_BTN'); ?></button></div>
        </div>
    <?php
    }
    ?>
    <div class="j2store_subscription_title">
        <h4><?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTION').' #'.$item->j2store_subscription_id.' '.JText::_('J2STORE_PRODUCT_SUBSCRIPTION_DETAILS'); ?></h4>
    </div>
    <div class="j2store_subscription_content">
        <div class="row-fluid">
            <div class="span8">
                <div class="span6">
                    <form name="j2store_subscription_detail_view" class="j2store_subscription_detail_form" id="j2store_subscription_detail_form" method="POST">
                        <div class="panel panel-solid-success order-general-information">
                            <div class="panel-body j2store_subscription_detail_view">
                                <?php
                                $calenderAttribute = array('class' => 'calender_subscription', 'placeholder' => JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_CALENDER_PLACEHOLDER'))
                                ?>
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
                                    <dt><?php echo JText::_('J2STORE_PAYMENT_METHOD'); ?></dt>
                                    <dd><?php echo JText::_($order_table->orderpayment_type); ?></dd>
                                </dl>
                                <div class="j2store_subscription_edit_button">
                                    <h3><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_BILLING_SCHEDULE'); ?></h3>
                                    <button type="button" onclick="editSubscription()" class="btn btn-small can_edit">
                                        <?php echo JText::_('J2STORE_SUBSCRIPTION_EDIT_BUTTON'); ?>
                                    </button>
                                    <button type="button" onclick="cancelEditSubscription()" class="btn btn-small edit_item hide">
                                        <?php echo JText::_('J2STORE_SUBSCRIPTION_CANCEL_EDIT_BUTTON'); ?>
                                    </button>
                                </div>
                                <div class="j2store_susbcription_edit_message hide">
                                </div>
                                <dl class="dl-horizontal">
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_RECURRING'); ?></dt>
                                    <dd class="can_edit">
                                        <?php
                                        echo $subscription_period_units_text.' '.$subscription_period_string;
                                        ?>
                                    </dd>
                                    <dd class="edit_item hide">
                                        <?php
                                        echo $subscription_period_units;
                                        echo $subscription_periods;
                                        ?>
                                    </dd>
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'); ?></dt>
                                    <dd class="<?php echo (in_array($item->status, array('new', 'future')))? 'can_edit': '' ?>">
                                        <?php
                                        $date = JFactory::getDate($item->start_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                        ?>
                                    </dd>
                                    <?php if(in_array($item->status, array('new', 'future'))){ ?>
                                    <dd class="edit_item hide">
                                        <?php
                                        echo JHtml::calendar($item->start_on, 'start_on', 'start_on', '%Y-%m-%d', $calenderAttribute);
                                        $hours = $minutes = $seconds = '';
                                        if($item->start_on != '' && $item->start_on != '0000-00-00 00:00:00'){
                                            $hours = date('H', strtotime($item->start_on));
                                            $minutes = date('i', strtotime($item->start_on));
                                            $seconds = date('s', strtotime($item->start_on));
                                        }
                                        ?>
                                        <input type="text" name="start_on_hours" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_HH_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $hours; ?>"/>:
                                        <input type="text" name="start_on_minutes" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_MM_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $minutes; ?>"/>:
                                        <input type="text" name="start_on_seconds" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_SS_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $seconds; ?>"/>
                                    </dd>
                                    <?php } ?>
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'); ?></dt>
                                    <dd class="can_edit">
                                        <?php
                                        if($item->subscription_length > 0){
                                            if($item->end_on == '0000-00-00 00:00:00'){
                                                echo '0000-00-00 00:00:00';
                                            } else {
                                                $date = JFactory::getDate($item->end_on, $tz);
                                                echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                            }
                                        } else {
                                            echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_NEVER_EXPIRE');
                                        }
                                        ?>
                                    </dd>
                                    <dd class="edit_item hide">
                                        <?php
                                        $hours = $minutes = $seconds = '';
                                        if($item->end_on != '' && $item->end_on != '0000-00-00 00:00:00'){
                                            $hours = date('H', strtotime($item->end_on));
                                            $minutes = date('i', strtotime($item->end_on));
                                            $seconds = date('s', strtotime($item->end_on));
                                        }
                                        echo JHtml::calendar($item->end_on, 'end_on', 'end_on', '%Y-%m-%d', $calenderAttribute);
                                        ?>
                                        <input type="text" name="end_on_hours" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_HH_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $hours; ?>"/>:
                                        <input type="text" name="end_on_minutes" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_MM_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $minutes; ?>"/>:
                                        <input type="text" name="end_on_seconds" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_SS_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $seconds; ?>"/>
                                        <br/>
                                        <small class="muted"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON_DESC'); ?></small>
                                    </dd>
                                    <dt><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?></dt>
                                    <dd class="can_edit">
                                        <?php
                                        if($item->next_payment_on < $item->end_on || $item->subscription_length == 0){
                                            $date = JFactory::getDate($item->next_payment_on, $tz);
                                            echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </dd>                                    
                                    <dd class="edit_item hide">
                                        <?php
                                        $hours = $minutes = $seconds = '';
                                        if($item->next_payment_on != '' && $item->next_payment_on != '0000-00-00 00:00:00'){
                                            $hours = date('H', strtotime($item->next_payment_on));
                                            $minutes = date('i', strtotime($item->next_payment_on));
                                            $seconds = date('s', strtotime($item->next_payment_on));
                                        }
                                        echo JHtml::calendar($item->next_payment_on, 'next_payment_on', 'next_payment_on', '%Y-%m-%d', $calenderAttribute);
                                        ?>
                                        <input type="text" name="next_payment_on_hours" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_HH_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $hours; ?>"/>:
                                        <input type="text" name="next_payment_on_minutes" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_MM_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $minutes; ?>"/>:
                                        <input type="text" name="next_payment_on_seconds" class="calender_hours_subscription" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_SS_PLACEHOLDER'); ?>" maxlength="2" value="<?php echo $seconds; ?>"/>
                                        <br/>
                                        <small class="muted"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON_DESC'); ?></small>
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
                                    } ?>
                                    <dt class="edit_item hide"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_EDIT_NOTE'); ?></dt>
                                    <dd class="edit_item hide">
                                        <textarea type="textarea" name="subscription_edit_note" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_EDIT_NOTE_PLACEHOLDER'); ?>" ></textarea>
                                        <br/>
                                        <small class="muted"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_EDIT_NOTE_DESC'); ?></small>
                                    </dd>
                                    <dt class="edit_item hide"></dt>
                                    <dd class="edit_item hide"><button type="button" class="btn btn-success" onclick="updateSubscription()">Update</button></dd>
                                </dl>
                            </div>
                        </div>
                        <input type="hidden" name="option" value="com_j2store" />
                        <input type="hidden" name="view" value="app" />
                        <input type="hidden" name="task" value="view" />
                        <input type="hidden" name="appTask" value="updateSubscription" />
                        <input type="hidden" name="sid" value="<?php echo $item->j2store_subscription_id; ?>" />
                        <input type="hidden" name="id" value="<?php echo $app->input->getInt('id'); ?>" />
                    </form>
                </div>
                <div class="span6">
                    <div class="panel-heading">
                        <h4>
                            <?php echo JText::_('J2STORE_CUSTOMER_INFORMATION'); ?>
                        </h4>
                    </div>
                    <table class="table table-bordered addresses">
                        <tr>
                            <th width="50%">
                                <?php echo JText::_('J2STORE_BILLING_ADDRESS');?>
                                <?php echo J2StorePopup::popupAdvanced("index.php?option=com_j2store&view=orders&task=setOrderinfo&order_id=".$subscriptionOrder->order_id."&address_type=billing&layout=address&tmpl=component",'',array('class'=>'fa fa-pencil','update'=>true,'width'=>700,'height'=>600));?>
                            </th>
                            <th width="50%">
                                <?php echo JText::_('J2STORE_SHIPPING_ADDRESS');?>
                                <?php echo J2StorePopup::popupAdvanced("index.php?option=com_j2store&view=orders&task=setOrderinfo&order_id=".$subscriptionOrder->order_id."&address_type=shipping&layout=address&tmpl=component",'',array('class'=>'fa fa-pencil','update'=>true,'width'=>700,'height'=>600));?>
                            </th>
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
                                <?php echo convert_phone_number($orderinfo->billing_phone_1);
                                echo convert_phone_number($orderinfo->billing_phone_2) ? '<br/> '.convert_phone_number($orderinfo->billing_phone_2) : "<br/> ";
                                echo '<br/> ';
                                echo '<a href="mailto:'.$userDetails->get('email').'">'.$userDetails->get('email').'</a>';
                                echo '<br/> ';
                                echo $orderinfo->billing_company ? JText::_('J2STORE_ADDRESS_COMPANY_NAME').':&nbsp;'.$orderinfo->billing_company."</br>" : "";
                                echo $orderinfo->billing_tax_number ? JText::_('J2STORE_ADDRESS_TAX_NUMBER').':&nbsp;'.$orderinfo->billing_tax_number."</br>" : "";
                                ?>
                                <?php echo J2Store::getSelectableBase()->getFormatedCustomFields($orderinfo, 'customfields', 'billing'); ?>
                            </td>
                            <td>
                                <?php
                                if(isset($orderinfo->j2store_orderinfo_id) && $orderinfo->j2store_orderinfo_id > 0 && !empty($orderinfo->shipping_country_id) /*&& !empty($this->orderinfo->shipping_zone_id)*/):?>
                                    <?php echo '<strong>'.$orderinfo->shipping_first_name." ".$orderinfo->shipping_last_name."</strong><br/>"; ?>
                                    <?php echo $orderinfo->shipping_address_1;?>
                                    <?php echo $orderinfo->shipping_address_2 ? $orderinfo->shipping_address_2 : "<br/>";?>
                                    <?php echo $orderinfo->shipping_city;?><br />
                                    <?php echo $orderinfo->shipping_zone_name ? $orderinfo->shipping_zone_name.'<br />' : "";?>
                                    <?php echo !empty($orderinfo->shipping_zip) ? $orderinfo->shipping_zip.'<br />': '';?>
                                    <?php echo $orderinfo->shipping_country_name." <br/> ".JText::_('J2STORE_TELEPHONE').":";?>
                                    <?php echo convert_phone_number($orderinfo->shipping_phone_1);
                                    echo $orderinfo->shipping_phone_2 ? '<br/> '.convert_phone_number($orderinfo->shipping_phone_2) : "<br/> ";
                                    echo '<br/> ';
                                    echo $orderinfo->shipping_company ? JText::_('J2STORE_ADDRESS_COMPANY_NAME').':&nbsp;'.$orderinfo->shipping_company."</br>" : "";
                                    echo $orderinfo->shipping_tax_number ? JText::_('J2STORE_ADDRESS_TAX_NUMBER').':&nbsp;'.$orderinfo->shipping_tax_number."</br>" : "";
                                    ?>
                                    <?php echo J2Store::getSelectableBase()->getFormatedCustomFields($orderinfo, 'customfields', 'shipping'); ?>
                                <?php endif;?>
                            </td>
                        </tr>
                    </table>
                    <div class="span12 subs_update_status_form_con">
                        <h4><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_SUBSCRIPTION_CHNAGE_STATUS_TITLE'); ?></h4>
                        <?php
                        $attributes['id'] = 'update_status';
                        $attributes['class'] = 'j2s_sbs_update_status';
                        echo $subsStatusObj->getUpdateStatusSelectBox('update_status', $item->status, $attributes);
                        ?>
                        <button type="button" class="btn btn-success" onclick="changeSubscriptionStatus()"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_SUBSCRIPTION_STATUS'); ?></button>
                        <div class="subs_update_status_notify_option">
                            <label><input type="checkbox" name="notify_customer" id="notify_customer" value="1" checked> <?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_NOTIFY_CUSTOMER_WHILE_UPDATE_SUBSCRIPTION_STATUS'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="span12">
                    <div class="j2store_susbcription_message hide">
                    </div>
                    <?php echo $this->loadTemplate('summary'); ?>
                </div>
                <div class="span12">
                    <?php  echo $this->loadTemplate('orders');?>
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
                        <?php foreach($subscriptionHistory as $history):
                            ?>
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
                    <div class="add-subscription-history_con">
                        <h4><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_HISTORY_TITLE'); ?></h4>
                        <div class="j2store_susbcription_history_message hide">
                        </div>
                        <textarea name="subscription_history_note" id="subscription_history_note"></textarea>
                        <br/>
                        <select name="subscription_history_type" id="subscription_history_type">
                            <option value=""><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_HISTORY_TYPE_PRIVATE'); ?></option>
                            <option value="customer"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_HISTORY_TYPE_CUSTOMER'); ?></option>
                        </select>
                        <input type="hidden" id="subscription_history_id" value="<?php echo $app->input->getInt('id'); ?>"/>
                        <input type="hidden" id="subscription_history_sid" value="<?php echo $item->j2store_subscription_id; ?>"/>
                        <button type="button" class="btn btn-success" onclick="addSubscriptionHistory()"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_HISTORY_BUTTON'); ?></button>
                    </div>
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

        function changeSubscriptionStatus(){
            (function($) {
                var status = $('select#update_status').val();
                var notify_customer = 0;
                if($('input#notify_customer').prop("checked") == true){
                    notify_customer = 1;
                }
                $.ajax({
                    type : 'post',
                    url :  'index.php',
                    data : {
                        'option': 'com_j2store',
                        'view': 'app',
                        'task': 'view',
                        'appTask': 'changeSubscriptionStatus',
                        'id': '<?php echo $app->input->getInt('id'); ?>',
                        'sid': '<?php echo $item->j2store_subscription_id; ?>',
                        'status': status,
                        'notify_customer': notify_customer
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
            })(j2store.jQuery);
        }
        <?php
        if($is_stopped_in_middle_of_renewal){
            ?>
        function retryRenewalProcess(){
            (function($) {
                $.ajax({
                    type : 'post',
                    url :  'index.php',
                    data : {
                        'option': 'com_j2store',
                        'view': 'app',
                        'task': 'view',
                        'appTask': 'updateRetryRenewalProcess',
                        'id': '<?php echo $app->input->getInt('id'); ?>',
                        'sid': '<?php echo $item->j2store_subscription_id; ?>'
                    },
                    dataType : 'json',
                    success : function(response) {
                        if(response.status == '1'){
                            $('.j2store_susbcription_message').html(response.message);
                            $('.j2store_susbcription_message').show();
                            location.reload();
                        } else {
                            $('.j2store_susbcription_message').html(response.message);
                            $('.j2store_susbcription_message').show();
                        }
                    }
                });
            })(j2store.jQuery);
        }
        <?php
        }
        ?>
    </script>
<?php } else {
    ?>
    <div class="j2store_subscription_con">
        <?php echo JText::_('J2STORE_SUBSCRIPTION_INVALID'); ?>
    </div>
    <?php
} ?>