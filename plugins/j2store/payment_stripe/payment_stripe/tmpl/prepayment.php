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
$ajax_base_url = JRoute::_('index.php');
?>
<?php
$image = $this->params->get('display_image', '');
?>
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

    <div class="note">
        <table id="j2store_stripe_form" class="table">
            <tr>
                <td><span id="stripe-card-type-text"></span></td>
                <td><span id="stripe-card-type"></span></td>

            </tr>
            <tr>
                <td class="field_name"><?php echo JText::_( 'J2STORE_STRIPE_CARD_NUMBER' ) ?></td>
                <td>************<?php echo $vars->cardnum_last4; ?></td>
            </tr>
            <tr>
                <td class="field_name"><?php echo JText::_( 'J2STORE_STRIPE_EXPIRATION_DATE' ) ?></td>
                <td><?php echo $vars->cardexp; ?></td>
            </tr>
            <tr>
                <td class="field_name"><?php echo JText::_( 'J2STORE_STRIPE_CARD_CVV' ) ?></td>
                <td>****</td>
            </tr>
        </table>
    </div>
    <input type='hidden' name='cardnum' value='<?php echo @$vars->cardnum; ?>' />
    <input type='hidden' name='cardexp' value='<?php echo @$vars->cardexp; ?>' />
    <input type='hidden' name='cardcvv' value='<?php echo @$vars->cardcvv; ?>' />
    <input type='hidden' name='cardmonth' value='<?php echo @$vars->cardmonth; ?>' />
    <input type='hidden' name='cardyear' value='<?php echo @$vars->cardyear; ?>' />

    <input type='hidden' name='name' value='<?php echo $vars->cardname; ?>' />
    <input type='hidden' name='address_city' value='<?php echo $vars->address_city; ?>' />
    <input type='hidden' name='address_country' value='<?php echo $vars->address_country; ?>' />
    <input type='hidden' name='address_line1' value='<?php echo $vars->address_line1; ?>' />
    <input type='hidden' name='address_line2' value='<?php echo $vars->address_line2; ?>' />
    <input type='hidden' name='address_state' value='<?php echo $vars->address_state; ?>' />
    <input type='hidden' name='address_zip' value='<?php echo $vars->address_zip; ?>' />

    <?php
    $button_label = $vars->button_text;
    if($vars->is_card_update){
        $button_label = $vars->card_update_button_text;
    }
    ?>
    <input type="button" onclick="j2storeStripeSubmit(this)" id="stripe-submit-button" class="button btn btn-primary" value="<?php echo JText::_($button_label); ?>" />

    <input type='hidden' name='order_id' value='<?php echo @$vars->order_id; ?>' />
    <input type='hidden' name='orderpayment_id' value='<?php echo @$vars->orderpayment_id; ?>' />
    <input type='hidden' name='orderpayment_type' value='<?php echo @$vars->orderpayment_type; ?>' />
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

$data = array(
    'name' => $vars->cardname,
    'number' => $vars->cardnum,
    'exp_month' => $vars->cardmonth,
    'exp_year' => $vars->cardyear,
    'cvc' => $vars->cardcvv
);

if($this->params->get('send_customer_address', 0)) {
    $data['address_country'] =  JText::_($vars->address_country);
    $data['address_line1'] =  $vars->address_line1;
    $data['address_line2'] =  $vars->address_line2;
    $data['address_state'] =  $vars->address_state;
    $data['address_zip'] =  $vars->address_zip;
}

?>
<script data-cfasync="false" type="text/javascript">
    Stripe.setPublishableKey('<?php echo $vars->publish_key; ?>');
    var cardtype = Stripe.card.cardType('<?php echo $vars->cardnum; ?>');
    console.log(cardtype);

    if(typeof(j2store) == 'undefined') {
        var j2store = {};
    }
    if(typeof(j2store.jQuery) == 'undefined') {
        j2store.jQuery = jQuery.noConflict();
    }
    if(cardtype){
        j2store.jQuery('#stripe-card-type-text').text('<?php echo addslashes(JText::_('J2STORE_STRIPE_CREDITCARD_TYPE')); ?>');
        j2store.jQuery('#stripe-card-type').text(cardtype);
    }

    function j2storeStripeSubmit(button) {

        (function($) {
            $(button).attr('disabled', 'disabled');
            $(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_PROCESSING_PLEASE_WAIT')); ?>');
            var result = doStripeToken();
        })(j2store.jQuery);
    }

    function doStripeToken() {
        (function($) {
            Stripe.setPublishableKey('<?php echo $vars->publish_key; ?>');

            try {
                Stripe.applePay.checkAvailability(stripeAppleResponseHandler);

            } catch(e) {
                $(".stripe-payment-errors").text(e);
                logResponse(e.message);
            }
            return false;

        })(j2store.jQuery);
    }

    function stripeAppleResponseHandler(availablity) {
        console.log(availablity);
        if(availablity){
            console.log('applepay');
            Stripe.applePay.buildSession({
                countryCode: '<?php echo $vars->address_country;?>',
                currencyCode: '<?php echo $vars->currency_code;?>',
                total: {
                    label: '<?php echo $vars->company_name;?>',
                    amount: '<?php echo $vars->amount;?>'
                }
            }, onSuccessHandler, onErrorHandler);
        }else{
            console.log('cardtoken');
            Stripe.card.createToken(<?php echo json_encode($data); ?>, stripeResponseHandler);
        }
    }

    function onSuccessHandler(result, completion) {
        if(result.token.id){
            var token = result.token.id;
            j2store.jQuery('#stripe-payment-form').append(j2store.jQuery('<input type="hidden" id="stripe-token" name="stripeToken" />').val(token));
            doSendRequest();
        }
    }

    function onErrorHandler(error) {

        if(error.message) logResponse(error.message);
        if (error.message) {
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
            j2store.jQuery(".stripe-payment-errors").text(error.message);
            var button = j2store.jQuery('#stripe-submit-button');
            j2store.jQuery(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING')); ?>');
        }
    }

    function stripeResponseHandler(status, response) {
        //	console.log(response);
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
            if(errorMessages[response.error.code]){
                j2store.jQuery(".stripe-payment-errors").text(errorMessages[response.error.code]);
            }else{
                j2store.jQuery(".stripe-payment-errors").text(response.error.message);
            }
            var button = j2store.jQuery('#stripe-submit-button');
            j2store.jQuery(button).val('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING')); ?>');
        }
        else
        {
            var token = response.id;
            j2store.jQuery('#stripe-payment-form').append(j2store.jQuery('<input type="hidden" id="stripe-token" name="stripeToken" />').val(token));
            doSendRequest();
        }

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
                    url : '<?php echo $ajax_base_url; ?>',
                    type: 'post',
                    data: values,
                    dataType: 'json',
                    beforeSend: function() {
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


    function logResponse(res)
    {
        // create console.log to avoid errors in old IE browsers
        if (!window.console) console = {log:function(){}};
        console.log(res);
    }
    var errorMessages = {
        incorrect_number: "<?php echo addslashes(JText::_('J2STORE_INCORRECT_NUMBER')); ?>",
        invalid_number: "<?php echo addslashes(JText::_('J2STORE_INVALID_NUMBER')); ?>",
        invalid_expiry_month:  "<?php echo addslashes(JText::_('J2STORE_INVALID_EXPIRY_MONTH')); ?>",
        invalid_expiry_year: "<?php echo addslashes(JText::_('J2STORE_INVALID_EXPIRY_YEAR')); ?>",
        invalid_cvc:  "<?php echo addslashes(JText::_('J2STORE_INVALID_CVC')); ?>",
        expired_card:  "<?php echo addslashes(JText::_('J2STORE_EXPIRED_CARD')); ?>",
        incorrect_cvc:  "<?php echo addslashes(JText::_('J2STORE_INCORRECT_CVC')); ?>",
        incorrect_zip: "<?php echo addslashes(JText::_('J2STORE_INCORRECT_ZIP')); ?>",
        card_declined: "<?php echo addslashes(JText::_('J2STORE_CARD_DECLINED')); ?>",
        missing:  "<?php echo addslashes(JText::_('J2STORE_MISSING')); ?>",
        processing_error:  "<?php echo addslashes(JText::_('J2STORE_PROCESSING_ERROR')); ?>",
        invalid_swipe_data:   "<?php echo addslashes(JText::_('J2STORE_INVALID_SWIPE_DATA')); ?>"
    };
</script>
