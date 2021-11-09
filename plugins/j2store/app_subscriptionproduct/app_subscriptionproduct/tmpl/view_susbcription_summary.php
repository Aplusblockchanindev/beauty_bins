<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
// No direct access to this file
defined ( '_JEXEC' ) or die ();
$order = $this->vars->subscriptionOrder;
$items = $order->getItems();
$currency = J2Store::currency();
$j2_params = J2Store::config();
$app = JFactory::getApplication();
$orderTaxProfileId = 0;
//$shippingPlugins = J2Store::plugin()->getPluginsWithEvent( 'onJ2StoreGetShippingPlugins' );
$hasShippingPlugins = 0;
?>
<h3><?php echo JText::_('J2STORE_SUBSCRIPTION_ORDER_SUMMARY')?></h3>
<div class="alert alert-danger"><?php echo JText::_ ( 'J2STORE_APP_SUBSCRIPTIONPRODUCT_EDIT_SUMMARY_TOTAL_WARRING_MESSAGE' );?></div>
<table class="j2store-cart-table table table-bordered">
    <thead>
    <tr>
        <th><?php echo JText::_('J2STORE_CART_LINE_ITEM'); ?></th>
        <th><?php echo JText::_('J2STORE_CART_LINE_ITEM_QUANTITY'); ?></th>
        <th><?php echo JText::_('J2STORE_CART_LINE_ITEM_TOTAL'); ?></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($items as $item): ?>
        <?php
        $registry = new JRegistry;
        $registry->loadString($item->orderitem_params);
        $item->params = $registry;
        $thumb_image = $item->params->get('thumb_image', '');
        if($item->orderitem_taxprofile_id && $orderTaxProfileId == 0){
            $orderTaxProfileId = $item->orderitem_taxprofile_id;
        }
        ?>
        <tr>
            <td>
                <?php if($j2_params->get('show_thumb_cart', 1) && !empty($thumb_image)): ?>
                    <span class="cart-thumb-image">
								<?php if(JFile::exists(JPATH_SITE.'/'.$thumb_image)): ?>
                                    <img src="<?php echo JUri::root(true). '/'.$thumb_image; ?>" >
                                <?php endif;?>
							</span>
                <?php endif; ?>
                <div class="edit_orderitem_name_out">
                    <span class="fa fa-pencil edit_orderitem_name" title="<?php echo JText::_('COM_J2STORE_SUBSCRIPTION_EDIT_ORDER_ITEM_NAME'); ?>"></span>
                    <div class="edit_orderitem_name_cont hide">
                        <input type="text" class="edit_orderitem_name" name="orderitem_name_<?php echo $item->j2store_orderitem_id; ?>" value="<?php echo $item->orderitem_name; ?>" id="orderitem_name_<?php echo $item->j2store_orderitem_id; ?>"/>
                        <button class="btn btn-success update_orderitem_name" data-itemid="<?php echo $item->j2store_orderitem_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                        <button class="btn btn-warning cancel_edit_orderitem_name"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                    </div>
                </div>
                <?php echo $order->get_formatted_lineitem_name($item);
                $orderItemAttributes = $this->vars->model->getSubscriptionOrderItemAttributes($item->j2store_orderitem_id);
                if(!empty($orderItemAttributes)){
                    ?>
                    <div class="edit_orderitem_attributes_out">
                        <span class="fa fa-pencil edit_orderitem_attributes" title="<?php echo JText::_('COM_J2STORE_SUBSCRIPTION_EDIT_ORDER_ITEM_ATTRIBUTES'); ?>"></span>
                        <div class="edit_orderitem_attribute_cont hide" id="edit_orderitem_attribute_cont_<?php echo $item->j2store_orderitem_id; ?>">
                            <?php foreach ($orderItemAttributes as $key => $orderItemAttribute){ ?>
                                <div class="edit_orderitem_attribute_list">
                                    <input type="hidden" class="edit_orderitem_attribute" name="orderitem_attribute_id[<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>]" value="<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>" id="orderitem_attribute_id_<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>"/>
                                    <input type="text" class="edit_orderitem_attribute" name="orderitem_attribute_name[<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>]" value="<?php echo $orderItemAttribute->orderitemattribute_name; ?>" id="orderitem_attribute_name_<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>"/>
                                    <input type="text" class="edit_orderitem_attribute" name="orderitem_attribute_value[<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>]" value="<?php echo $orderItemAttribute->orderitemattribute_value; ?>" id="orderitem_attribute_value_<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>"/>
                                    <select id="orderitem_attribute_prefix_<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>" name="orderitem_attribute_prefix[<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>]" class="input-small edit_orderitem_attribute">
                                        <option value="+"<?php echo ($orderItemAttribute->orderitemattribute_prefix == '+')? ' selected="selected"': ''; ?>>+</option>
                                        <option value="-"<?php echo ($orderItemAttribute->orderitemattribute_prefix == '-')? ' selected="selected"': ''; ?>>-</option>
                                    </select>
                                    <input type="text" class="edit_orderitem_attribute input-small" name="orderitem_attribute_price[<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>]" value="<?php echo $orderItemAttribute->orderitemattribute_price; ?>" id="orderitem_attribute_price_<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>"/>
                                    <span class="fa fa-remove remove_orderitem_attribute" data-orderitem_attribute="<?php echo $orderItemAttribute->j2store_orderitemattribute_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>" data-confirm_text="<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_REMOVE_ATTRIBUTE_CONFIRM_TEXT'); ?>"></span>
                                </div>
                            <?php } ?>
                            <button class="btn btn-success update_orderitem_attribute" data-itemid="<?php echo $item->j2store_orderitem_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                            <button class="btn btn-warning cancel_orderitem_attribute"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <?php if($j2_params->get('show_price_field', 1)): ?>

                    <span class="cart-product-unit-price">
								<span class="cart-item-title"><?php echo JText::_('J2STORE_CART_LINE_ITEM_UNIT_PRICE'); ?></span>
								<span class="cart-item-value">
									<?php echo $currency->format($order->get_formatted_order_lineitem_price($item, $j2_params->get('checkout_price_display_options', 1)), $order->currency_code, $order->currency_value);?>
								</span>
							</span>
                <?php endif; ?>

                <?php if(!empty($item->orderitem_sku)): ?>
                    <br />
                    <span class="cart-product-sku">
								<span class="cart-item-title"><?php echo JText::_('J2STORE_CART_LINE_ITEM_SKU'); ?></span>
								<span class="cart-item-value"><?php echo $item->orderitem_sku; ?></span>
							</span>

                <?php endif; ?>
            </td>
            <td class="cart-line-quantity">
                <div class="orderitem_quantity_cont">
                    <span class="cart-item-value">
                        <?php echo $item->orderitem_quantity; ?>
                    </span>
                    <span class="fa fa-pencil edit_orderitem_quantity"></span>
                </div>
                <div class="orderitem_quantity_edit_cont hide">
                    <input type="number" min="1" class="orderitem_quantity_field" name="orderitem_quantity_<?php echo $item->j2store_orderitem_id; ?>" id="orderitem_quantity_<?php echo $item->j2store_orderitem_id; ?>" value="<?php echo $item->orderitem_quantity; ?>"/>
                    <button class="btn btn-success update_orderitem_quantity" data-itemid="<?php echo $item->j2store_orderitem_id; ?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                    <button class="btn btn-warning cancel_edit_orderitem_quantity"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                </div>
            </td>
            <td class="cart-line-subtotal">
                <?php echo $currency->format($order->get_formatted_lineitem_total($item, $j2_params->get('checkout_price_display_options', 1)), $order->currency_code, $order->currency_value ); ?>
                <?php
                if($item->orderitem_taxprofile_id){
                    if($j2_params->get('checkout_price_display_options', 1)){
                        echo '('.JText::_('J2STORE_CONF_INCLUDING_TAX').')';
                    } else {
                        echo '('.JText::_('J2STORE_CONF_EXCLUDING_TAX').')';
                    }
                }
                ?><br>
                <span class="cart-item-title"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_LINE_ITEM_ORIGINAL_PRICE'); ?></span>
                <div class="orderitem_price_text_cont">
                    <span class="cart-item-value">
                        <?php echo $currency->format($item->orderitem_price); ?>
                    </span>
                    <span class="fa fa-pencil edit_orderitem_price"></span>
                </div>
                <div class="orderitem_price_edit_cont hide">
                    <input class="orderitem_price_field" name="orderitem_price_<?php echo $item->j2store_orderitem_id; ?>" id="orderitem_price_<?php echo $item->j2store_orderitem_id; ?>" value="<?php echo $item->orderitem_price; ?>"/>
                    <?php
                    echo J2Html::select()->clearState()
                        ->type('genericlist')
                        ->name('orderitem_tax_class_id_'.$item->j2store_orderitem_id)
                        ->value($item->orderitem_taxprofile_id)
                        ->setPlaceHolders(array(''=>JText::_('J2STORE_NOT_TAXABLE')))
                        ->hasOne('Taxprofiles')
                        ->setRelations(
                            array (
                                'fields' => array (
                                    'key'=>'j2store_taxprofile_id',
                                    'name'=>'taxprofile_name'
                                )
                            )
                        )->getHtml();
                    ?>
                    <button class="btn btn-success update_orderitem_price" data-itemid="<?php echo $item->j2store_orderitem_id; ?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                    <button class="btn btn-warning cancel_edit_orderitem_price"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

    <tfoot class="cart-footer">
    <?php if($totals = $order->get_formatted_order_totals()): ?>
        <?php foreach($totals as $key => $total): ?>
            <tr>
                <th scope="row" colspan="2">
                    <?php
                    if($key === 'shipping'){
                        ?>
                        <div class="edit_shipping_name_out">
                            <span class="fa fa-pencil edit_shipping_name" title="<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_EDIT_SHIPPING_NAME_TITLE');?>"></span>
                            <div class="edit_shipping_name_cont hide">
                                <input class="edit_shipping_name" name="edit_shipping_name" value="<?php echo $total['label']; ?>" id="edit_shipping_name" type="text">
                                <button class="btn btn-success update_shipping_name" data-order_id="<?php echo $order->order_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_STATUS'); ?></button>
                                <button class="btn btn-warning cancel_edit_shipping_name"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                            </div>
                        </div>
                        <?php
                        echo "<span class='shipping-name'>";
                        echo $total['label'];
                        echo "</span>";
                    } else {
                        echo $total['label'];
                    }
                    ?>
                    <?php if (strpos($key, 'fee_') !== false) {
                        if(isset($this->vars->fee_ids[$key])) {
                            ?>
                            <span class="fa fa-remove remove_order_fee" data-fee_id="<?php echo $this->vars->fee_ids[$key]; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"></span>
                            <?php
                        }
                    } else if($key === 'shipping'){
                        ?>
                        <span class="fa fa-remove remove_order_shipping" data-order_id="<?php echo $order->order_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"></span>
                        <?php
                    }
                    ?>
                </th>
                <td><?php echo $total['value']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td colspan="3" >
            <span class="pull-right">
                <button id="add_additional_fee" class="btn ac_btn_subscription" data-target_add="add_fee_tr"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_ADDITIONAL_FEE_BUTTON');?></button>
                <?php if($hasShippingPlugins){ ?>
                    <button id="add_shipping" class="btn ac_btn_subscription" data-target_add="add_shipping_tr"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_BUTTON');?></button>
                <?php } ?>
                <button id="calculate_total" class="btn btn-warning"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_CALCULATE_TOTAL');?></button>
            </span>
        </td>
    </tr>
    <tr id="add_fee_tr" class="hide">
        <td colspan="3" >
            <b><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_ADDITIONAL_HEADING')?></b>
            <div class="add_fee_con">
                <input type="text" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_ADDITIONAL_FEE_NAME_PLACEHOLDER'); ?>" name="fee_name" id="fee_name"/>
                <input type="text" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_ADDITIONAL_FEE_AMOUNT_PLACEHOLDER'); ?>" name="fee_amount" id="fee_amount"/>
                <?php
                echo J2Html::select()->clearState()
                    ->type('genericlist')
                    ->name('fee_tax_class_id')
                    ->value($orderTaxProfileId)
                    ->setPlaceHolders(array(''=>JText::_('J2STORE_NOT_TAXABLE')))
                    ->hasOne('Taxprofiles')
                    ->setRelations(
                        array (
                            'fields' => array (
                                'key'=>'j2store_taxprofile_id',
                                'name'=>'taxprofile_name'
                            )
                        )
                    )->getHtml();
                ?>
                <button id="confirm_add_additional_fee" class="btn btn-success" data-orderid="<?php echo $order->order_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_ADDITIONAL_FEE_BUTTON');?></button>
                <button class="btn btn-warning ac_btn_subscription" data-target_remove="add_fee_tr"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
            </div>
        </td>
    </tr>
    <?php if($hasShippingPlugins){ ?>
        <tr id="add_shipping_tr" class="hide">
            <td colspan="3" >
                <b><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_HEADING')?></b>
                <div class="add_shipping_con">
                    <select id="shipping_type" name="shipping_type">
                    <?php
                    foreach ($shippingPlugins as $shippingPlugin){
                        ?>
                            <option value="<?php echo $shippingPlugin->element; ?>"><?php echo $shippingPlugin->name; ?></option>
                    <?php
                    }
                    ?>
                    </select>
                    <input type="text" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_NAME_PLACEHOLDER'); ?>" name="shipping_name" id="shipping_name"/>
                    <input type="text" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_PRICE_PLACEHOLDER'); ?>" name="shipping_price" id="shipping_price"/>
                    <input type="text" placeholder="<?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_EXTRA_PLACEHOLDER'); ?>" name="shipping_extra" id="shipping_extra"/>
                    <?php
                    echo J2Html::select()->clearState()
                        ->type('genericlist')
                        ->name('shipping_tax_class_id')
                        ->value($orderTaxProfileId)
                        ->setPlaceHolders(array(''=>JText::_('J2STORE_NOT_TAXABLE')))
                        ->hasOne('Taxprofiles')
                        ->setRelations(
                            array (
                                'fields' => array (
                                    'key'=>'j2store_taxprofile_id',
                                    'name'=>'taxprofile_name'
                                )
                            )
                        )->getHtml();
                    ?>
                    <button id="confirm_add_shipping" class="btn btn-success" data-orderid="<?php echo $order->order_id; ?>" data-appid="<?php echo $app->input->getInt('id'); ?>"><?php echo JText::_('J2STORE_APP_SUBSCRIPTIONPRODUCT_ADD_SHIPPING_BUTTON');?></button>
                    <button class="btn btn-warning ac_btn_subscription" data-target_remove="add_shipping_tr"><?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_CANCEL'); ?></button>
                </div>
            </td>
        </tr>
    <?php } ?>
    </tfoot>
</table>
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

    function calculateSubscriptionOrderTotal(){
        (function($) {
            var confirmResult = confirm("<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_ORDDER_CONFIRM_MESSAGE'); ?>");
            if(confirmResult){
                $.ajax({
                    type : 'post',
                    url :  'index.php',
                    data : {
                        'option': 'com_j2store',
                        'view': 'app',
                        'task': 'view',
                        'appTask': 'reCalculateSubscriptionOrder',
                        'id': '<?php echo $app->input->getInt('id'); ?>',
                        'order_id': '<?php echo $order->order_id; ?>'
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
            }
        })(j2store.jQuery);
    }

    function updateSubscriptionOrderItem(order_item_id){
        (function($) {
            var confirmResult = confirm("<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_ORDDER_CONFIRM_MESSAGE'); ?>");
            if(confirmResult) {
                if (order_item_id != undefined && order_item_id != '') {
                    var order_item_price = $('#orderitem_price_' + order_item_id).val();
                    var order_item_tax_class_id = $('#j2store_orderitem_tax_class_id_' + order_item_id).val();
                    $.ajax({
                        type: 'post',
                        url: 'index.php',
                        data: {
                            'option': 'com_j2store',
                            'view': 'app',
                            'task': 'view',
                            'appTask': 'updateSubscriptionOrderItem',
                            'id': '<?php echo $app->input->getInt('id'); ?>',
                            'order_item_id': order_item_id,
                            'order_item_tax_class_id': order_item_tax_class_id,
                            'order_item_price': order_item_price
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == '1') {
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                                location.reload();
                            } else {
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                            }
                        }
                    });
                }
            }
        })(j2store.jQuery);
    }

    function updateSubscriptionOrderItemQuantity(order_item_id){
        (function($) {
            var confirmResult = confirm("<?php echo JText::_('J2STORE_SUBSCRIPTIONAPP_UPDATE_ORDER_ITEM_QUANTITY_CONFIRM_MESSAGE'); ?>");
            if(confirmResult) {
                if (order_item_id != undefined && order_item_id != '') {
                    var order_item_quantity = $('#orderitem_quantity_' + order_item_id).val();
                    $.ajax({
                        type: 'post',
                        url: 'index.php',
                        data: {
                            'option': 'com_j2store',
                            'view': 'app',
                            'task': 'view',
                            'appTask': 'updateSubscriptionOrderItemQuantity',
                            'id': '<?php echo $app->input->getInt('id'); ?>',
                            'order_item_id': order_item_id,
                            'order_item_quantity': order_item_quantity
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == '1') {
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                                location.reload();
                            } else {
                                $('.j2store_susbcription_message').html(data.message);
                                $('.j2store_susbcription_message').show();
                            }
                        }
                    });
                }
            }
        })(j2store.jQuery);
    }
</script>