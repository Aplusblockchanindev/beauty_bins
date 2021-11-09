<?php
defined('_JEXEC') or die('Restricted access');
$j2_params = J2Store::config();
$tz = JFactory::getConfig()->get('offset');
echo J2Store::plugin()->eventWithHtml('DisplayAdditionalContentBeforeRelatedOrdersForAdmin', array($this->vars->subscription) );
?>
<div class="subscription_related_orders_con">
    <div class="panel-heading">
        <h4>
            <?php echo JText::_('J2STORE_SUBSCRIPTION_RELATED_ORDERS'); ?>
        </h4>
    </div>
    <div>
        <table class="j2store-cart-table table table-bordered">
            <thead>
                <tr>
                    <th><?php echo JText::_('J2STORE_EMAILTEMPLATE_TAG_ORDERID'); ?></th>
                    <th><?php echo JText::_('J2STORE_SUBSCRIPTION_RELATIONSHIP'); ?></th>
                    <th><?php echo JText::_('J2STORE_SUBSCRIPTION_DATE'); ?></th>
                    <th><?php echo JText::_('J2STORE_EMAILTEMPLATE_ORDERSTATUS'); ?></th>
                    <th><?php echo JText::_('J2STORE_CART_GRANDTOTAL'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <a href="index.php?option=com_j2store&view=order&id=<?php echo $this->vars->order->j2store_order_id?>">
                        <?php echo $this->vars->order->order_id; ?>
                        </a>
                    </td>
                    <td>
                        <?php
                        if($this->vars->order->parent_id){
                            echo JText::_('J2STORE_SUBSCRIPTION_RENEWAL_ORDER');
                        } else {
                            echo JText::_('J2STORE_SUBSCRIPTION_PARENT_ORDER');
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $date = JFactory::getDate($this->vars->order->created_on, $tz);
                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                        ?>
                    </td>
                    <td>
                        <?php
                        $orderStatus = F0FTable::getAnInstance('OrderStatus','J2StoreTable')->getClone();
                        $orderStatus->load(array(
                            'j2store_orderstatus_id' => $this->vars->order->order_state_id
                        ));
                        ?>
                        <span class="label <?php echo $orderStatus->orderstatus_cssclass;?> order-state-label">
                            <?php echo JText::_($orderStatus->orderstatus_name);?>
                        </span>
                    </td>
                    <td>
                        <?php echo J2Store::currency()->format($this->vars->order->order_total, $this->vars->order->currency_code, $this->vars->order->currency_value); ?>
                    </td>
                </tr>
            <?php
            $relatedOrders = $this->vars->relatedOrders;
            foreach($relatedOrders as $relatedOrder):
            ?>
                <tr>
                    <td>
                        <a href="index.php?option=com_j2store&view=order&id=<?php echo $relatedOrder->j2store_order_id?>">
                            <?php echo $relatedOrder->order_id; ?>
                        </a>
                    </td>
                    <td>
                        <?php
                        if($relatedOrder->parent_id){
                            echo JText::_('J2STORE_SUBSCRIPTION_RENEWAL_ORDER');
                        } else {
                            echo JText::_('J2STORE_SUBSCRIPTION_PARENT_ORDER');
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $date = JFactory::getDate($relatedOrder->created_on, $tz);
                        echo $date->format($j2_params->get('date_format', JText::_('DATE_FORMAT_LC1')), true);
                        ?>
                    </td>
                    <td>
                        <?php
                        $orderStatus = F0FTable::getAnInstance('OrderStatus','J2StoreTable')->getClone();
                        $orderStatus->load(array(
                            'j2store_orderstatus_id' => $relatedOrder->order_state_id
                        ));
                        ?>
                        <span class="label <?php echo $orderStatus->orderstatus_cssclass;?> order-state-label">
                            <?php echo JText::_($orderStatus->orderstatus_name);?>
                        </span>
                    </td>
                    <td>
                        <?php
                        echo J2Store::currency()->format($relatedOrder->order_total, $relatedOrder->currency_code, $relatedOrder->currency_value); ?>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
</div>
