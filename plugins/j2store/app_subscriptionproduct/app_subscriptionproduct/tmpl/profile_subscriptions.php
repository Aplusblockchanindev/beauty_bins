<?php
defined('_JEXEC') or die('Restricted access');
unset ( $listOrder );
$listOrder = $vars->data->state->get ( 'filter_order', 'tbl.user_id' );
$listDirn = $vars->data->state->get ( 'filter_order_Dir' );
$items = $vars->data->subscription;
$j2_params = J2Store::config();
$app = JFactory::getApplication();
$subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
?>
<div class="tab-pane active" id="subscription-tab">
    <div class="j2store_subscriptions_con">
        <div class="j2store_susbcription_message hide">
        </div>
        <form class="form-horizontal" method="post" action="" name="adminForm" id="adminForm" >
            <?php /*echo JText::_( 'J2STORE_FILTER_SEARCH' );*/ ?>
            <?php //$search = isset($this->vars->state->search) ? htmlspecialchars($this->vars->state->search):"";?>
            <?php //echo  J2Html::text('search',$search,array('id'=>'search' ,'class'=>'input j2store-order-filters'));?>
            <?php  //echo  J2Html::button('go',JText::_( 'J2STORE_FILTER_GO' ) ,array('class'=>'btn btn-success' ,'onclick'=>'this.form.submit();'));?>
            <?php  //echo  J2Html::button('reset',JText::_( 'J2STORE_FILTER_RESET' ),array('id'=>'reset-filter-search','class'=>'btn btn-inverse'));?>
<!---->
<!--            <span class="pull-right">-->
<!--		--><?php //echo $vars->data->pagination->getLimitBox();?>
<!--		</span>-->

            <br/>
            <br />
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_USER'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?>
                    </th>
                    <th><?php echo JText::_('J2STORE_ACTIONS'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($items)):
                    foreach ($items as $item):?>
                        <tr>
                            <td>
                                <?php $viewUrl = JUri::base().'index.php?option=com_j2store&view=app&task=view&appTask=viewMySubscription&tmpl=component&id='.$vars->id.'&sid='.$item->j2store_subscription_id; ?>
                                <?php //fa fa-list-alt
                                echo J2StorePopup::popup($viewUrl, $item->j2store_subscription_id, array('class'=>'')); ?>
                            </td>
                            <td><?php
                                $status = $subsStatusObj->getStatus($item->status);
                                if(isset($status->status_name)){
                                    ?>
                                    <span class="label <?php echo $status->status_cssclass; ?> order-state-label">
						        <?php echo JText::_($status->status_name); ?>
                                </span>
                                    <?php
                                } else {
                                    echo $item->status;
                                }
                                ?></td>
                            <td><?php
                                echo $item->orderitem_name;
                                echo J2Store::plugin()->eventWithHtml('DisplayAdditionalContentInSubscriptionList', array($item) );
                                ?>
                            </td>
                            <td><?php
                                $userDetails = JFactory::getUser($item->user_id);
                                echo $userDetails->get('name');
                                echo "<br>";
                                echo $userDetails->get('email');
                                ?></td>
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
                                if ($item->next_payment_on < $item->end_on || $item->subscription_length == 0) {
                                    $tz = JFactory::getConfig()->get('offset');
                                    $date = JFactory::getDate($item->next_payment_on, $tz);
                                    echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                                    echo "<br>";
                                    $renewal_amount = $vars->model->getRenewalAmount($item);
                                    echo J2Store::currency()->format($renewal_amount['renewal_amount'], $item->currency_code, $item->currency_value);
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td data-app_id="<?php echo $vars->id; ?>">
                            <div style="display:flex;">
                                <?php
                                $support_trial = $vars->model->hasTrialSupport($item->payment_method);
                                $display_card_update = $vars->model->isDisplayCardUpdate($item);
                                if(($item->status == 'card_expired' || $display_card_update) && $support_trial){
                                    ?>
                                <?php $link = JURI::root().'index.php?option=com_j2store&view=app&task=view&appTask=updateSubscriptionPaymentCard&id='.$vars->id.'&sid='.$item->j2store_subscription_id; ?>
                                    <a class="btn btn-primary subscription_update_card_btn" href="<?php echo $link; ?>">
                                        <?php echo JText::_('J2STORE_SUBSCRIPTION_UPDATE_CARD_DETAILS_BUTTON_LABEL'); ?>
                                        <?php $vars->model->showLast4Digits($item); ?>
                                    </a>
                                    <?php
                                }
                                if($item->status == 'active' || $item->status == 'in_trial'){
                                    ?>
                                    <!-- <button type="button" class="btn btn-warning" onclick="cancelSubscription('<?php echo $item->j2store_subscription_id; ?>')"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button> -->
                                    <button type="button" class="btn btn-warning" onclick="pauseSubscription('<?php echo $item->j2store_subscription_id; ?>')">Pause Services</button>
                                    <?php
                                }
                                $showRenewalBtn = $vars->model->hasRenew($item);
                                if($showRenewalBtn)
                                {
                                    ?>
                                    <?php $link = JRoute::_('index.php?option=com_j2store&view=myprofile&profileTask=renew&sid='.$item->j2store_subscription_id); ?>
                                    <a class="btn btn-primary" style="padding-top:14px;" href="<?php echo $link;?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_RENEW'); ?></a>
                                <?php
                                }
                                // To load additional action through plugin
                                $subscriptionOrder = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
                                $subscriptionOrder->load(array('order_id' => $item->subscription_order_id));
                                $parentOrder = F0FTable::getAnInstance('Order' ,'J2StoreTable')->getClone();
                                $parentOrder->load(array('order_id' => $item->order_id));
                                echo J2Store::plugin()->eventWithHtml('DisplayAdditionalActionInSubscription', array( $subscriptionOrder, $parentOrder, $item ) ); //$item => Subscription
                                ?>
                                <?php //var_dump($item->status) ?>
                            </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="4"><?php echo JText::_('J2STORE_NO_ITEMS_FOUND');?></td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>

            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <input type="hidden" name="task" value="view" />
<!--            --><?php //echo $vars->data->pagination->getListFooter(); ?>
        </form>
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

        function doPauseSubscription(id)
        {
            (function($){
                var msg_pause = $("#txt_message_pause").val();
                if(msg_pause=="")
                {
                    alert("Please type your reasons");
                    return;
                }
                $.ajax({
                    type : 'post',
                    url :  '<?php echo JUri::root(); ?>index.php',
                    data : {
                        'option': 'com_j2store',
                        'view': 'apps',
                        'task': 'view',
                        'appTask': 'pauseSubscription',
                        'id': '<?php echo $vars->id; ?>',
                        'sid': id,
                        'msg_pause':msg_pause
                    },
                    dataType : 'json',
                    success : function(data) {
                        // alert(data.message);
                        if(data.status == '1'){
                            $("#pause_div").remove();
                            $('.j2store_susbcription_message').html(data.message);
                            $('.j2store_susbcription_message').show();
                        } else {
                            $('.j2store_susbcription_message').html(data.message);
                            $('.j2store_susbcription_message').show();
                        }
                    }
                });

            })(j2store.jQuery);
        }

        function pauseSubscription(id){
            (function($) {
                if($("#subscription-tab").children("#pause_div").length==0){
                    $("#subscription-tab").prepend("<div id='pause_div'><textarea id='txt_message_pause' style='display:block;width:50%;' placeholder='Type your reason here.'></textarea><button class='btn primary' onclick='doPauseSubscription("+ id +")'>Request to Pause</button></div>");
                }
            })(j2store.jQuery);
        }
        function cancelSubscription(id){
            (function($) {
                $.ajax({
                    type : 'post',
                    url :  '<?php echo JUri::root(); ?>index.php',
                    data : {
                        'option': 'com_j2store',
                        'view': 'apps',
                        'task': 'view',
                        'appTask': 'cancelSubscription',
                        'id': '<?php echo $vars->id; ?>',
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
            })(j2store.jQuery);
        }
    </script>
</div>