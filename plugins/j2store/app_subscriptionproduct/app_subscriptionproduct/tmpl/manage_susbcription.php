<?php
defined('_JEXEC') or die('Restricted access');
unset ( $listOrder );
$listOrder = $this->vars->state->get ( 'filter_order', 'tbl.user_id' );
$listDirn = $this->vars->state->get ( 'filter_order_Dir' );
$form = $this->vars->form2;
$items = $this->vars->subscription;
$j2_params = J2Store::config();
$app = JFactory::getApplication();
$subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
$allSubsStatus = $subsStatusObj->getAllSubscriptionStatus();
$statusSelect = array();
$statusSelect[''] = JText::_('J2STORE_SUBSCRIPTION_FILTER_SELECT_STATUS');
foreach ($allSubsStatus as $subsStatus){
    $statusSelect[$subsStatus->id] = JText::_($subsStatus->status_name);
}
$model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
$document = JFactory::getDocument();
$document->addScript(JUri::root().'media/plg_j2store_app_subscriptionproduct/js/script.js');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(pressbutton) {
        if(pressbutton == 'export' || pressbutton == 'export_subscription_orders') {
            document.adminForm.task ='view';
            document.getElementById('appTask').value = pressbutton;
        }

        Joomla.submitform('view');
    }
</script>
<div class="manage-quickbook-logs">
    <form class="form-horizontal" method="post" action="<?php echo $form['action'];?>" name="adminForm" id="adminForm" >
        <?php echo JText::_( 'J2STORE_FILTER_SEARCH' ); ?>
        <?php $search = isset($this->vars->state->search) ? htmlspecialchars($this->vars->state->search):"";?>
        <?php echo  J2Html::text('search',$search,array('id'=>'search' ,'class'=>'input j2store-order-filters','placeholder' => JText::_('J2STORE_SUBSCRIPTION_SEARCH_BY_EMAIL_PLACEHOLDER')));?>
        <?php echo  J2Html::button('go',JText::_( 'J2STORE_FILTER_GO' ) ,array('class'=>'btn btn-success' ,'onclick'=>'document.getElementById(\'appTask\').value=\'manageSubscription\';this.form.submit();'));?>
        <?php echo  J2Html::button('reset',JText::_( 'J2STORE_FILTER_RESET' ),array('id'=>'reset-filter-search','class'=>'btn btn-inverse','onclick'=>"document.getElementById('search').value='';document.getElementById('appTask').value='manageSubscription';this.form.submit();"));?>
        <br/><br/>
        <div class="tabbable tabs">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#subscription-tab" data-toggle="tab">
                        <?php echo JText::_('J2STORE_PRODUCT_SUBSCRIPTIONS'); ?>
                    </a>
                </li>
                <li>
                    <a href="#subscription-last_renewal-tab" data-toggle="tab">
                        <?php echo JText::_('J2STORE_SUBSCRIPTION_RENEWALS_MADE_BY_LAST_7DAYS'); ?>
                    </a>
                </li>
                <li>
                    <a href="#subscription-upcoming_renewal-tab" data-toggle="tab">
                        <?php echo JText::_('J2STORE_SUBSCRIPTION_UPCOMING_RENEWALS'); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="subscription-tab" class="tab-pane active">
                <span class="pull-right">
                <?php echo $this->vars->pagination->getLimitBox();?>
                </span>
                <span class="pull-right">
                <?php
                $statusValue = isset($this->vars->state->status) ? htmlspecialchars($this->vars->state->status): "";
                echo J2Html::select()->clearState()
                    ->type('genericlist')
                    ->name("status")
                    ->attribs(array('class' => 'input', 'onchange'=>'document.getElementById(\'appTask\').value=\'manageSubscription\';this.form.submit();'))
                    ->value($statusValue)
                    ->setPlaceHolders($statusSelect)
                    ->getHtml(); ?>
                </span>
                <span class="pull-right">
                    <?php $subscriptionCountValue = isset($this->vars->state->subscription_count) ? htmlspecialchars($this->vars->state->subscription_count): ""; ?>
                    <select id="j2store_subscription_count" name="subscription_count" class="input" onchange="this.form.submit();">
                        <option value=""<?php echo ($subscriptionCountValue == '')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_SELECT');?></option>
                        <option value="1"<?php echo ($subscriptionCountValue == '1')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_LESS_THAN_1');?></option>
                        <option value="2"<?php echo ($subscriptionCountValue == '2')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_LESS_THAN_2');?></option>
                        <option value="3"<?php echo ($subscriptionCountValue == '3')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_LESS_THAN_3');?></option>
                        <option value="4"<?php echo ($subscriptionCountValue == '4')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_LESS_THAN_4');?></option>
                        <option value="5"<?php echo ($subscriptionCountValue == '5')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_LESS_THAN_5');?></option>
                        <option value="6"<?php echo ($subscriptionCountValue == '6')? ' selected="selected"': ''; ?>><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT_GREATER_THAN_5');?></option>
                    </select>
                </span>
                <br/>
                <br />
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="5%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ID'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS'); ?>
                        </th>
                        <th width="15%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'); ?>
                        </th>
                        <th width="15%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_USER'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?>
                        </th>
                        <th width="20%"><?php echo JText::_('J2STORE_ACTIONS'); ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($items)):
                        foreach ($items as $item):?>
                            <tr>
                                <td>
                                    <a href="index.php?option=com_j2store&view=app&task=view&appTask=viewSubscription&sid=<?php echo $item->j2store_subscription_id; ?>&id=<?php echo $app->input->get('id'); ?>">
                                        <?php echo $item->j2store_subscription_id;?>
                                    </a>
                                </td>
                                <td><?php
                                    $status = $subsStatusObj->getStatus($item->status);
                                        ?>
                                        <span class="label <?php echo $status->status_cssclass; ?> order-state-label">
                                        <?php echo JText::_($status->status_name); ?>
                                        </span>
                                    </td>
                                <td><?php
                                    echo $item->orderitem_name;
                                    ?></td>
                                <td><?php
                                    $userDetails = JFactory::getUser($item->user_id);
                                    echo $userDetails->get('name');
                                    echo "<br>";
                                    echo $userDetails->get('email');
                                    echo "<br>";
                                    echo JText::_('J2STORE_SUBSCRIPTIONAPP_SUBSCRIPTION_COUNT');
                                    if($item->subscription_count > 0){
                                        ?>
                                        <span class="label label-warning order-state-label">
                                            <?php
                                            echo '('.($item->subscription_count).')';
                                            ?>
                                        </span>
                                        <?php
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    $tz = JFactory::getConfig()->get('offset');
                                    $date = JFactory::getDate($item->start_on, $tz);
                                    echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    ?>
                                </td>
                                <td><?php
                                    if($item->subscription_length > 0) {
                                        $tz = JFactory::getConfig()->get('offset');
                                        $date = JFactory::getDate($item->end_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    } else {
                                        echo JText::_('COM_J2STORE_PRODUCT_SUBSCRIPTION_NEVER_EXPIRE');
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    if($item->next_payment_on < $item->end_on || $item->subscription_length == 0){
                                        $tz = JFactory::getConfig()->get('offset');
                                        $date = JFactory::getDate($item->next_payment_on, $tz);
                                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                        echo "<br>";
                                        $renewal_amount = $model->getRenewalAmount($item);
                                        echo J2Store::currency()->format($renewal_amount['renewal_amount'], $item->currency_code, $item->currency_value);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td class="subs_update_status_form_con">
                                    <div class="j2store_susbcription_message_<?php echo $item->j2store_subscription_id; ?> hide">
                                    </div>
                                    <?php
                                    $attributes['id'] = 'update_status_'.$item->j2store_subscription_id;
                                    $attributes['class'] = 'j2s_sbs_update_status';
                                    echo $subsStatusObj->getUpdateStatusSelectBox('update_status_'.$item->j2store_subscription_id, $item->status, $attributes);
                                    ?>
                                    <button type="button" class="btn btn-success" onclick="changeSubscriptionStatus('<?php echo $item->j2store_subscription_id; ?>')"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                                    <div class="subs_update_status_notify_option">
                                        <label><input type="checkbox" name="notify_customer" id="notify_customer_<?php echo $item->j2store_subscription_id; ?>" value="1" checked> <?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_NOTIFY_CUSTOMER_WHILE_UPDATE_SUBSCRIPTION_STATUS'); ?></label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="8"><?php echo JText::_('J2STORE_NO_ITEMS_FOUND');?></td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>

                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                <input type="hidden" id="appTask" name="appTask" value="manageSubscription" />
                <input type="hidden" name="task" value="view" />
                <?php echo $this->vars->pagination->getListFooter(); ?>
                <?php echo J2Html::hidden('boxchecked',0);?>
                <input type="hidden" name="id" value="<?php echo $this->vars->id; ?>" />
            </div>
            <div id="subscription-last_renewal-tab" class="tab-pane">
                <?php echo $this->loadTemplate('last_renewals'); ?>
            </div>
            <div id="subscription-upcoming_renewal-tab" class="tab-pane">
                <?php echo $this->loadTemplate('upcoming_renewals'); ?>
            </div>
        </div>
    </form>
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

        function changeSubscriptionStatus(id){
            (function($) {
                var status = $('select#update_status_'+id).val();
                var notify_customer = 0;
                if($('input#notify_customer_'+id).prop("checked") == true){
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
                        'sid': id,
                        'status': status,
                        'notify_customer': notify_customer
                    },
                    dataType : 'json',
                    success : function(data) {
                        if(data.status == '1'){
                            $('.j2store_susbcription_message_'+id).html(data.message);
                            $('.j2store_susbcription_message_'+id).show();
                            location.reload();
                        } else {
                            $('.j2store_susbcription_message_'+id).html(data.message);
                            $('.j2store_susbcription_message_'+id).show();
                        }
                    }
                });
            })(j2store.jQuery);
        }
    </script>
</div>