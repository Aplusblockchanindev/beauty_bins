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
<style>
    .StripeElement {
        margin: 10px 5px;
        background-color: white;
        height: 20px;
        width: 60%;
        padding: 10px 12px;
        border-radius: 4px;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    <?php
        if($vars->enable_card_holder_name):
    ?>
    .group {
        background: white;
        border-radius: 4px;
        margin: 15px 5px;
        box-shadow: 0 7px 14px 0 rgba(49,49,93,0.10),
        0 3px 6px 0 rgba(0,0,0,0.08);
    }
    .group label {
        position: relative;
        color: #8898AA;
        font-weight: 300;
        height: 40px;
        line-height: 40px;
        margin-left: 20px;
        display: flex;
        flex-direction: row;
    }
    .group label:not(:last-child) {
        border-bottom: 1px solid #F0F5FA;
    }
    label > span {
        width: 80px;
        text-align: center;
        font-weight: 600;
        font-size: 15px;
    }
    .field {
        background: transparent;
        font-weight: 300;
        border: 0;
        color: #31325F;
        outline: none;
        flex: 1;
        padding-right: 10px;
        padding-left: 10px;
        cursor: text;
    }

    .field::-webkit-input-placeholder { color: #aab7c4;font-size: 16px;  }
    .field::-moz-placeholder { color: #aab7c4;font-size: 16px; }
    <?php
        endif;
    ?>
</style>
<?php if(isset($vars->error)): ?>
    <div class="j2error"><?php echo $vars->error;?></div>
<?php else: ?>
    <form action="<?php echo JRoute::_( "index.php?option=com_j2store&view=checkout" ); ?>" method="post" id="stripe-payment-form">
        <input type='hidden' name='address_city' value='<?php echo $vars->address_city; ?>' />
        <input type='hidden' name='address_country' value='<?php echo $vars->address_country; ?>' />
        <input type='hidden' name='address_line1' value='<?php echo $vars->address_line1; ?>' />
        <input type='hidden' name='address_line2' value='<?php echo $vars->address_line2; ?>' />
        <input type='hidden' name='address_state' value='<?php echo $vars->address_state; ?>' />
        <input type='hidden' name='address_zip' value='<?php echo $vars->address_zip; ?>' />
        <input type='hidden' name='order_id' value='<?php echo $vars->order_id; ?>' />
        <input type='hidden' name='orderpayment_id' value='<?php echo $vars->orderpayment_id; ?>' />
        <input type='hidden' name='orderpayment_type' value='<?php echo $vars->orderpayment_type; ?>' />
        <input type='hidden' name='option' value='com_j2store' />
        <input type='hidden' name='view' value='checkout' />
        <input type='hidden' name='task' value='confirmPayment'>
        <input type='hidden' name='paction' value='process_intent'>
        <?php
        if($vars->enable_card_holder_name):
            ?>
            <div class="group">
                <label>
                    <span><?php echo JText::_('J2STORE_STRIPE_CARD_HOLDER_NAME'); ?></span>
                    <input id="stripe_card_holder_name" class="field" placeholder="<?php echo JText::_('J2STORE_STRIPE_CARD_HOLDER_NAME_PLACEHOLDER'); ?>" />
                </label>
            </div>
            <?php
        endif;
        echo JHTML::_( 'form.token' );
        ?>
        <div class="group">
            <div id="card-element"></div>
        </div>
        <div id="card-errors" class="j2error"></div>
        <span class="plugin_error_instruction"></span>
        <?php
        $button_label = $vars->button_text;
        if($vars->is_card_update){
            $button_label = $vars->card_update_button_text;
        }
        ?>
        <button id="stripe-submit-button" class="button btn btn-primary" data-secret="<?php echo $vars->intent->client_secret ?>" ><?php echo JText::_($vars->button_text);?></button>

    </form>
<script>
    (function ($) {
        var stripe = Stripe('<?php echo $vars->publish_key;?>');
        var elements = stripe.elements();
        var cardElement = elements.create('card', {<?php echo (!empty($vars->disable_zip_code_in_inbuilt_form))?'hidePostalCode:true':''; ?>});
        cardElement.mount('#card-element');

        var button = document.getElementById('stripe-submit-button');
        button.addEventListener('click', function(ev) {
            $(button).attr('disabled', 'disabled');
            ev.preventDefault();
            var clientSecret = button.dataset.secret;
            <?php
            if($vars->enable_card_holder_name):
            ?>
            var cardholderName = document.getElementById('stripe_card_holder_name');
            <?php if($vars->setup_intent){
                ?>
            stripe.handleCardSetup(
            <?php
            } else {
            ?>
            stripe.handleCardPayment(
            <?php
            } ?>
                clientSecret, cardElement, {
                    payment_method_data: {
                        billing_details: {
                            name: cardholderName.value
                        }
                    }
                }
            ).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    // Display result.error.message in your UI.
                } else {
                    <?php if($vars->setup_intent){
                    ?>
                    stripeTokenHandler(result.setupIntent);
                    <?php
                    } else {
                    ?>
                    stripeTokenHandler(result.paymentIntent);
                    <?php
                    } ?>

                    // The payment has succeeded. Display a success message.
                }
            });
            <?php
            else:
            if($vars->setup_intent){
            ?>
            stripe.handleCardSetup(
            <?php
            } else {
            ?>
            stripe.handleCardPayment(
                <?php
                } ?>
                clientSecret, cardElement
            ).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    // Display result.error.message in your UI.
                } else {
                    <?php if($vars->setup_intent){
                    ?>
                    stripeTokenHandler(result.setupIntent);
                    <?php
                    } else {
                    ?>
                    stripeTokenHandler(result.paymentIntent);
                    <?php
                    } ?>
                    // The payment has succeeded. Display a success message.
                }
            });
            <?php
            endif;
            ?>
        });
    })(j2store.jQuery)
    // Handling the generated token
    function stripeTokenHandler(token) {
        var form = document.getElementById('stripe-payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('id', 'stripe-token');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        (function($) {
            var button = j2store.jQuery('#stripe-submit-button');
            var token = j2store.jQuery('#stripe-payment-form #stripe-token').val();
            if(token.length == 0) {
                $(button).text('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
            } else {
                //get all form values
                var form = $('#stripe-payment-form');
                var values = form.serializeArray();
                //submit the form using ajax
                var jqXHR =	$.ajax({
                    url: '<?php echo $ajax_base_url; ?>',
                    type: 'post',
                    data: values,
                    dataType: 'json',
                    beforeSend: function() {
                        $(button).attr('disabled', 'disabled');
                        $(button).text('<?php echo addslashes(JText::_('J2STORE_STRIPE_PROCESSING_PLEASE_WAIT')); ?>');
                        $(button).after('<span class="wait">&nbsp;<img src="/media/j2store/images/loader.gif" alt="" /></span>');
                    }
                });

                jqXHR.done(function(json) {
                    form.find('.j2success, .j2warning, .j2attention, .j2information, .j2error').remove();
                    //console.log(json);
                    if (json['error']) {
                        form.find('#card-errors').after('<span class="j2error">' + json['error']+ '</span>');
                        form.find('.plugin_error_instruction').after('<br /><span class="j2error"><?php echo addslashes(JText::_('J2STORE_STRIPE_ON_ERROR_INSTRUCTIONS')); ?></span>');
                        $(button).text('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
                    }
                    if (json['redirect']) {
                        $(button).text('<?php echo addslashes(JText::_('J2STORE_STRIPE_COMPLETED_PROCESSING'))?>');
                        window.location.href = json['redirect'];
                    }

                });

                jqXHR.fail(function() {
                    $(button).text('<?php echo addslashes(JText::_('J2STORE_STRIPE_ERROR_PROCESSING'))?>');
                });

                jqXHR.always(function() {
                    $('.wait').remove();
                });
            }
        })(j2store.jQuery);
    }
    <?php
    if($vars->enable_card_holder_name):
    ?>
    (function ($) {
        $('#stripe_card_holder_name').change(function () {
            validate_card_holder_name();
        })
    })(j2store.jQuery);

    function validate_card_holder_name() {
        (function ($) {
            var stripe_card_holder_name = $('#stripe_card_holder_name').val();
            var button = $('#stripe-submit-button');
            if (stripe_card_holder_name === "") {
                $(button).prop("disabled", true);
            } else {
                $(button).prop("disabled", false);
            }
        })(j2store.jQuery);
    }

    validate_card_holder_name();
    <?php
    endif;
    ?>
</script>

<?php endif; ?>