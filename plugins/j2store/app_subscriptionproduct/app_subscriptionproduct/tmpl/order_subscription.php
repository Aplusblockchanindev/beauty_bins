<?php
defined('_JEXEC') or die('Restricted access');
$items = $vars->subscriptions;
$j2_params = J2Store::config();
$app = JFactory::getApplication();
$subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
$model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
?>
<div class="manage-susbcription-product">
    <h3><?php echo JText::_('J2STORE_SUBSCRIPTION_RELATED_SUBSCRIPTIONS'); ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ID'); ?>
            </th>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_STATUS'); ?>
            </th>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NAME'); ?>
            </th>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_START_ON'); ?>
            </th>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_END_ON'); ?>
            </th>
            <th><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_NEXT_RENEWAL_ON'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($items)):
            foreach ($items as $item):?>
                <tr>
                    <td>
                        <a href="index.php?option=com_j2store&view=app&task=view&appTask=viewSubscription&sid=<?php echo $item->j2store_subscription_id; ?>&id=<?php echo $vars->id; ?>">
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
                            echo J2Store::currency()->format($renewal_amount['renewal_amount'], $vars->order->currency_code, $vars->order->currency_value);
                        } else {
                            echo '-';
                        }
                        ?>
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
</div>