<?php
/**
 * --------------------------------------------------------------------------------
 * Payment Plugin - Stripe
 * --------------------------------------------------------------------------------
 * @package     Joomla 2.5 -  3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2014-19 J2Store . All rights reserved.
 * @license     GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */

//no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php
$image = $this->params->get('display_image', '');
?>
<style>
    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 20px;
        height: 20px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<?php if(!empty($image)): ?>
    <span class="j2store-payment-image">
				<img class="payment-plugin-image payment_2checkout" src="<?php echo JUri::root().JPath::clean($image); ?>" />
			</span>
    <br />
<?php endif; ?>

<?php echo JText::_($vars->display_name); ?>
<br />
<?php echo JText::_($vars->onbeforepayment_text); ?>


<form id="stripe-payment-form" action="<?php echo JRoute::_( "index.php?option=com_j2store&view=checkout" ); ?>" method="post" name="adminForm" enctype="multipart/form-data">

    <input type='hidden' name='address_city' value='<?php echo $vars->address_city; ?>' />
    <input type='hidden' name='address_country' value='<?php echo $vars->address_country; ?>' />
    <input type='hidden' name='address_line1' value='<?php echo $vars->address_line1; ?>' />
    <input type='hidden' name='address_line2' value='<?php echo $vars->address_line2; ?>' />
    <input type='hidden' name='address_state' value='<?php echo $vars->address_state; ?>' />
    <input type='hidden' name='address_zip' value='<?php echo $vars->address_zip; ?>' />
    <div class="loader"></div>
    <?php
    $button_label = $vars->button_text;
    if($vars->is_card_update){
        $button_label = $vars->card_update_button_text;
    }
    ?>
    <input type="button" onclick="j2storeStripeSubmit(this)" style="display: none" id="stripe-submit-button" class="button btn btn-primary" value="<?php echo JText::_($button_label); ?>" />

    <input type='hidden' name='order_id' value='<?php echo $vars->order_id; ?>' />
    <input type='hidden' name='orderpayment_id' value='<?php echo $vars->orderpayment_id; ?>' />
    <input type='hidden' name='orderpayment_type' value='<?php echo $vars->orderpayment_type; ?>' />
    <input type='hidden' name='option' value='com_j2store' />
    <input type='hidden' name='view' value='checkout' />
    <input type='hidden' name='task' value='confirmPayment'>
    <input type='hidden' name='paction' value='process'>

    <?php echo JHTML::_( 'form.token' ); ?>

    <br />
    <div class="stripe-payment-errors"></div>
    <br />
    <div class="plugin_error_div">
        <span class="plugin_error"></span>
        <span class="plugin_error_instruction"></span>
    </div>

</form>


<?php

//prepare data for Stripe.

/*$data = array(
    'name' => $vars->cardname,
    'number' => $vars->cardnum,
    'exp_month' => $vars->cardmonth,
    'exp_year' => $vars->cardyear,
    'cvc' => $vars->cardcvv
);*/

if($this->params->get('send_customer_address', 0)) {
    $data['address_country'] =  JText::_($vars->address_country);
    $data['address_line1'] =  $vars->address_line1;
    $data['address_line2'] =  $vars->address_line2;
    $data['address_state'] =  $vars->address_state;
    $data['address_zip'] =  $vars->address_zip;
}

?>
<script data-cfasync="false" type="text/javascript">

    var handler = StripeCheckout.configure({
        key: '<?php echo $vars->publish_key; ?>',
        image: '<?php echo $vars->image;?>',
        locale: '<?php echo $vars->language;?>',
        <?php if(isset($vars->enable_bitcoin) && !empty($vars->enable_bitcoin)){ ?>
        bitcoin: 'true',
        <?php } ?>
        currency: '<?php echo $vars->currency_code;?>',
        allowRememberMe: <?php echo (isset($vars->allow_remember_me) && $vars->allow_remember_me) ? 'true':'false';?>,
        email: '<?php echo $vars->email;?>',
        token: function(response) {

            // You can access the token ID with `token.id`.
            // Get the token ID to your server-side code for use.
            if(response.error) logResponse(response.error);
            if (response.error) {

                var version = '<?php echo JVERSION;?>';
                //alert(version);
                if(version >= "3.0")
                {
                    j2store.jQuery(".stripe-payment-errors").addClass('alert alert-error');
                }
                else
                {
                    j2store.jQuery(".stripe-payment-errors").addClass('error');
                }
                j2store.jQuery(".stripe-payment-errors").text(response.error.message);
                //log error
                doErrorRequest(response.error);
            }
            else
            {
                var token = response.id;
                j2store.jQuery('#stripe-payment-form').append(j2store.jQuery('<input type="hidden" id="stripe-token" name="stripeToken" />').val(token));
                doSendRequest();
            }
        }
    });

    if(typeof(j2store) == 'undefined') {
        var j2store = {};
    }
    if(typeof(j2store.jQuery) == 'undefined') {
        j2store.jQuery = jQuery.noConflict();
    }
    var easycheckout_stripe = '<?php echo $vars->is_easy_checkout;?>';
    if(easycheckout_stripe == 1){
        j2store.jQuery(window).load(function() {
            j2store.jQuery("#stripe-submit-button").show();
            j2store.jQuery('.loader').remove();
        });
    }else {
        j2store.jQuery("#stripe-submit-button").show();
        j2store.jQuery('.loader').remove();
    }

    function j2storeStripeSubmit(button) {

        (function($) {
            //$(button).attr('disabled', 'disabled');
            // $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_PROCESSING_PLEASE_WAIT')); ?>');
            var result = doStripeToken();
        })(j2store.jQuery);
    }

    function doStripeToken() {
        (function($) {

            try {
                handler.open({
                    name: '<?php echo $vars->company_name;?>',
                    description: '<?php echo $vars->description;?>',
                    zipCode: '<?php echo empty($vars->zipCode) ? 'false': 'true';?>',
                    <?php if($vars->display_amount) { ?>
                    amount: '<?php echo $vars->amount;?>'
                    <?php } ?>
                });
            } catch(e) {
                $(".stripe-payment-errors").text(e);
                logResponse(e.message);
            }
            return false;

        })(j2store.jQuery);
    }



    function doSendRequest() {

        (function($) {

            var button = j2store.jQuery('#stripe-submit-button');

            //token created. But check again
            var token = j2store.jQuery('#stripe-payment-form #stripe-token').val();
            if(token.length == 0) {
                //token is empty
                $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
            } else {
                //get all form values
                var form = $('#stripe-payment-form');
                var values = form.serializeArray();

                //submit the form using ajax
                var jqXHR =	$.ajax({
                    url: '<?php echo JRoute::_('index.php'); ?>',
                    type: 'post',
                    data: values,
                    dataType: 'json',
                    beforeSend: function() {
                        $(button).attr('disabled', 'disabled');
                        $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_PROCESSING_PLEASE_WAIT')); ?>');
                        $(button).after('<span class="wait">&nbsp;<img src="/media/j2store/images/loader.gif" alt="" /></span>');
                    }
                });

                jqXHR.done(function(json) {
                    form.find('.j2success, .j2warning, .j2attention, .j2information, .j2error').remove();
                    //console.log(json);
                    if (json['error']) {
                        form.find('.plugin_error').after('<span class="j2error">' + json['error']+ '</span>');
                        form.find('.plugin_error_instruction').after('<br /><span class="j2error"><?php echo addslashes(JText::_('J2STORE_STRIPE_ON_ERROR_INSTRUCTIONS')); ?></span>');
                        $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
                    }

                    if (json['redirect']) {
                        $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_COMPLETED_PROCESSING'))?>');
                        window.location.href = json['redirect'];
                    }

                });

                jqXHR.fail(function() {
                    $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
                });

                jqXHR.always(function() {
                    $('.wait').remove();
                });
            }
        })(j2store.jQuery);
    }

    function doErrorRequest(payment_error) {
        var data = {
            error_message: 	payment_error.message,
            type: payment_error.type,
            code: payment_error.code,
            order_id: '<?php echo $vars->order_id; ?>'
        };
        (function ($) {
            $.ajax({
                url : 'index.php?option=com_j2store&view=callback&task=callback&method=payment_stripe&paction=log',
                method:'post',
                data : data,
                dataType: 'json',
                success: function(json){
                }

            });
        })(j2store.jQuery);
    }

    function logResponse(res)
    {
        // create console.log to avoid errors in old IE browsers
        if (!window.console) console = {log:function(){}};
        console.log(res);
    }
</script>
