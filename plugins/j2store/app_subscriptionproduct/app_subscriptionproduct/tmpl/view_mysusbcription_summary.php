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
?>
<h3><?php echo JText::_('J2STORE_SUBSCRIPTION_ORDER_SUMMARY')?></h3>
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

                <?php echo $order->get_formatted_lineitem_name($item);?>

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
            <td><?php echo $item->orderitem_quantity; ?></td>
            <td class="cart-line-subtotal">
                <?php echo $currency->format($order->get_formatted_lineitem_total($item, $j2_params->get('checkout_price_display_options', 1)), $order->currency_code, $order->currency_value ); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

    <tfoot class="cart-footer">
    <?php if($totals = $order->get_formatted_order_totals()): ?>
        <?php foreach($totals as $total): ?>
            <tr>
                <th scope="row" colspan="2"> <?php echo $total['label']; ?></th>
                <td><?php echo $total['value']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tfoot>
</table>