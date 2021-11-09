<?php
defined('_JEXEC') or die('Restricted access');
if(count($this->vars->last_renewals)){
    $j2_params = J2Store::config();
    $app = JFactory::getApplication();
    $subsStatusObj = \J2Store\Subscription\Helper\SubscriptionStatus::getInstance();
    $allSubsStatus = $subsStatusObj->getAllSubscriptionStatus();
    $model = F0FModel::getTmpInstance('AppSubscriptionProducts', 'J2StoreModel');
    ?>
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
            <th width="10%"><?php echo JText::_('J2STORE_SUBSCRIPTION_RENEWED_ON'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->vars->last_renewals as $item):?>
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
                    $tz = JFactory::getConfig()->get('offset');
                    $date = JFactory::getDate($item->renewed_on, $tz);
                    echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php } else {
    echo JText::_('J2STORE_SUBSCRIPTION_NO_RENEWALS_MADE_BY_LAST_7DAYS');
} ?>