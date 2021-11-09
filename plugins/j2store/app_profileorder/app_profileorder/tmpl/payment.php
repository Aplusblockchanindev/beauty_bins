<?php
/**
 * --------------------------------------------------------------------------------
 *  APP - Profile Order
 * --------------------------------------------------------------------------------
 * @package     Joomla 3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2016 J2Store . All rights reserved.
 * @license     GNU GPL v3 or later
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */
defined('_JEXEC') or die('Restricted access');
$ajax_base_url = 'index.php?option=com_j2store&view=apps&task=view&layout=view';
?>
<div id='onCheckoutPayment_wrapper'>
    <h3>
        <?php echo JText::_('J2STORE_SELECT_A_PAYMENT_METHOD'); ?>
    </h3>
    <div class="checkout-content">

    </div>
    <?php if ($vars->plugins): ?>

        <?php foreach ($vars->plugins as $plugin): ?>

            <?php
            $params= new JRegistry;
            $params->loadString($plugin->params);

            $image = $params->get('display_image', '');
            ?>
            <?php echo J2Store::plugin()->eventWithHtml('BeforeDisplayPaymentMethod',array($plugin->element, $vars->order)); ?>
            <label class="payment-plugin-image-label <?php echo $plugin->element; ?>" >
                <input value="<?php echo $plugin->element; ?>" class="payment_plugin"
                       name="payment_plugin" type="radio"
                       onclick="j2storeGetPaymentForm('<?php echo $plugin->element; ?>', 'payment_form_div');"
                    <?php echo (!empty($plugin->checked)) ? "checked" : ""; ?>
                       title="<?php echo JText::_('J2STORE_SELECT_A_PAYMENT_METHOD'); ?>" />

                <?php if(!empty($image)): ?>
                    <img class="payment-plugin-image <?php echo $plugin->element; ?>" src="<?php echo JUri::root().JPath::clean($image); ?>" />
                <?php endif; ?>
                <?php
                $title = $params->get('display_name', '');
                if(!empty($title)) {
                    echo JText::_($title);
                } else {
                    echo JText::_($plugin->name );
                }
                ?>
            </label>

            <?php echo J2Store::plugin()->eventWithHtml('AfterDisplayPaymentMethod',array($plugin->element, $vars->order)); ?>
            <?php echo J2Store::plugin()->eventWithHtml('CheckoutShippingPayment', array($vars->order)); ?>

        <?php endforeach; ?>
    <?php endif; ?>

<div class="j2error"></div>
<div id='payment_form_div' style="padding-top: 10px;">
    <?php
    if (!empty($vars->payment_form_div))
    {
        echo $vars->payment_form_div;
    }
    ?>

</div>
<?php
//custom fields
$html = $vars->storeProfile->get('store_payment_layout');

//first find all the checkout fields
preg_match_all("^\[(.*?)\]^",$html,$checkoutFields, PREG_PATTERN_ORDER);

$allFields = $vars->fields;
?>
<?php foreach ($vars->fields as $fieldName => $oneExtraField): ?>
    <?php
    $onWhat='onchange'; if($oneExtraField->field_type=='radio') $onWhat='onclick';
    //echo $vars->fieldsClass->display($oneExtraField,@$vars->address->$fieldName,$fieldName,false);
    if(property_exists($vars->address, $fieldName)) {
        $html = str_replace('['.$fieldName.']',$vars->fieldsClass->getFormatedDisplay($oneExtraField,$vars->address->$fieldName, $fieldName,false, $options = '', $test = false, $allFields, $allValues = null),$html);
    }
    ?>
<?php endforeach; ?>

<?php
//check for unprocessed fields. If the user forgot to add the fields to the checkout layout in store profile, we probably have some.
$unprocessedFields = array();
foreach($vars->fields as $fieldName => $oneExtraField) {
    if(!in_array($fieldName, $checkoutFields[1])) {
        $unprocessedFields[$fieldName] = $oneExtraField;
    }
}

//now we have unprocessed fields. remove any other square brackets found.
preg_match_all("^\[(.*?)\]^",$html,$removeFields, PREG_PATTERN_ORDER);
foreach($removeFields[1] as $fieldName) {
    $html = str_replace('['.$fieldName.']', '', $html);
}

?>

<?php echo $html; ?>


<?php if(count($unprocessedFields)): ?>
    <div class="<?php echo $J2gridRow;?>">
        <div class="<?php echo $J2gridCol;?>12">
            <?php $uhtml = '';?>
            <?php foreach ($unprocessedFields as $fieldName => $oneExtraField): ?>
                <?php
                $onWhat='onchange'; if($oneExtraField->field_type=='radio') $onWhat='onclick';
                //echo $vars->fieldsClass->display($oneExtraField,@$vars->address->$fieldName,$fieldName,false);
                if(property_exists($vars->address, $fieldName)) {
                    $uhtml .= $vars->fieldsClass->getFormatedDisplay($oneExtraField,$vars->address->$fieldName, $fieldName,false, $options = '', $test = false, $allFields, $allValues = null);
                    $uhtml .='<br />';
                }
                ?>
            <?php endforeach; ?>
            <?php echo $uhtml; ?>
        </div>
    </div>
<?php endif; ?>

<?php if($vars->params->get('show_customer_note', 1)): ?>
    <div class="customer-note">
        <h3>
            <?php echo JText::_('J2STORE_CUSTOMER_NOTE'); ?>
        </h3>
        <textarea name="customer_note" rows="3" cols="40"></textarea>
    </div>

<?php endif; ?>

<?php if($vars->params->get('show_terms', 1)):?>
    <?php $tos = $vars->params->get('termsid', ''); ?>
    <div id="checkbox_tos">
        <?php if($vars->params->get('terms_display_type', 'link') =='checkbox' ):?>
            <input type="checkbox" class="required" name="tos_check"
                   title="<?php echo JText::_('J2STORE_AGREE_TO_TERMS_VALIDATION'); ?>" />
            <label for="tos_check" id="tos_check">

                <?php echo JText::_('J2STORE_TERMS_AND_CONDITIONS_AGREE_TO'); ?>

                <?php if(!empty($tos)): ?>
                    <a href="#j2store-tos-modal" class="link" data-toggle="modal"><?php echo JText::_('J2STORE_TERMS_AND_CONDITIONS'); ?></a>
                <?php else: ?>
                    <?php echo JText::_('J2STORE_TERMS_AND_CONDITIONS'); ?>
                <?php endif; ?>

            </label>
            <div class="j2error"></div>

        <?php else: ?>

            <?php echo JText::_('J2STORE_TERMS_AND_CONDITION_PRETEXT'); ?>

            <?php if(!empty($tos)): ?>
                <a href="#j2store-tos-modal" class="link" data-toggle="modal"><?php echo JText::_('J2STORE_TERMS_AND_CONDITIONS'); ?></a>
            <?php else: ?>
                <?php echo JText::_('J2STORE_TERMS_AND_CONDITIONS'); ?>
            <?php endif; ?>
        <?php endif;?>
    </div>
<?php endif; ?>

<?php /****** To get App term  html ********/?>
<?php echo J2Store::plugin()->eventWithHtml('AfterDisplayShippingPayment',array($vars->order)); ?>

<div class="buttons">
    <div class="left">
        <input type="button"
               value="<?php echo JText::_('J2STORE_CHECKOUT_CONTINUE'); ?>"
               id="button-payment-method" class="button btn btn-primary" />
    </div>
</div>
<!-- index.php?option=com_j2store&view=apps&task=view&layout=view&id=10127 -->
<input type="hidden" name="appTask" value="paymentValidate" />
<input type="hidden" name="id" value="<?php echo $vars->app_id;?>" />
<input type="hidden" name="layout"  value="view" />
<input type="hidden" name="task"    value="view" />
<input type="hidden" name="option" value="com_j2store" />
<input type="hidden" name="view" value="apps" />
    <input type="hidden" name="order_id" value="<?php echo isset($vars->order->order_id) ? $vars->order->order_id: 0 ;?>" />

<div class="j2store-modal">
    <div id="j2store-tos-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="j2store-tos-modal-label" aria-hidden="true" style="display:none;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
            <?php if(is_numeric($tos)): ?>
                <p><?php echo J2Store::article()->display($tos); ?></p>
            <?php endif;?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"
                    aria-hidden="true"><?php echo JText::_('J2STORE_CLOSE'); ?></button>

        </div>
    </div>
</div>

</div>
<script type="text/javascript">
    <!--
    function j2storeGetPaymentForm(element, container) {
        var url = '<?php echo JRoute::_('index.php'); ?>';
        var data = 'option=com_j2store&view=checkout&task=getPaymentForm&tmpl=component&payment_element='+ element;
        j2storeDoTask(url, container, document.adminForm, '', data);
    }
    //-->

    //shipping and payment methods
    (function($) {
        $(document).on('click', '#button-payment-method', function() {
            //var sss = $('#onCheckoutPayment_wrapper');
            var data = {};
            $('#onCheckoutPayment_wrapper input[type=\'text\'],#onCheckoutPayment_wrapper input[type=\'hidden\'],#onCheckoutPayment_wrapper input[type=\'radio\']:checked,#onCheckoutPayment_wrapper input[type=\'checkbox\']:checked,#onCheckoutPayment_wrapper select,#onCheckoutPayment_wrapper textarea').each(function(index, item){
                data[$(this).attr('name')] = $(this).val();

            });
            console.log(data);

            $.ajax({
                url: '<?php echo $ajax_base_url; ?>',
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#button-payment-method').attr('disabled', true);
                    $('#button-payment-method').after('<span class="wait">&nbsp;<img src="<?php echo JUri::root(true); ?>/media/j2store/images/loader.gif" alt="" /></span>');
                    $('#onCheckoutPayment_wrapper .checkout-content').html('');
                },
                complete: function() {

                },
                success: function(json) {
                    $('.warning, .j2error').remove();

                    if (json['redirect']) {
                        window.location = json['redirect'];
                        //location = json['redirect'];
                    } else if (json['error']) {
                        if (json['error']['shipping']) {
                            $('#shipping_error_div').html('<span class="j2error">' + json['error']['shipping'] + '</span>');

                        }

                        if (json['error']['warning']) {
                            $('#onCheckoutPayment_wrapper .checkout-content').prepend('<div class="warning alert alert-danger" >' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');
                        }

                        $.each( json['error'], function( key, value ) {
                            if (value) {
                                $('#onCheckoutPayment_wrapper #'+key).after('<br class="j2error" /><span class="j2error">' + value + '</span>');
                            }
                        });

                    }
                    $('#button-payment-method').attr('disabled', false);
                    $('.wait').remove();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

        });
    })(j2store.jQuery);
</script>
