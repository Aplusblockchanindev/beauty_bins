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
if($this->vars->process_request){
    ?>
    <form action="<?php echo JRoute::_( "index.php?option=com_j2store&view=checkout" ); ?>" method="post" id="stripe-payment-form">
        <input type='hidden' name='order_id' value='<?php echo $this->vars->order_id; ?>' />
        <input type='hidden' name='orderpayment_type' value='<?php echo $this->vars->orderpayment_type; ?>' />
        <input type='hidden' name='option' value='com_j2store' />
        <input type='hidden' name='view' value='checkout' />
        <input type='hidden' name='task' value='confirmPayment'>
        <input type='hidden' name='paction' value='process_intent_authentication'>
        <?php
        echo JHTML::_( 'form.token' );
        ?>
        <span class="plugin_error_instruction"></span>
    </form>
    <button id="card-button" class="btn btn-primary order_auth_confirm_btn" data-secret="<?php echo $this->vars->stripe_client_secret; ?>">
        <?php echo JText::_('J2STORE_STRIPE_AUTHENTICATE_PAYMENT_BTN_LABEL_IN_AUTHENTICATION_PAGE'); ?>
    </button>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function ($) {
            var stripe = Stripe('<?php echo $this->vars->publish_key; ?>');

            var cardButton = button = document.getElementById('card-button');
            var clientSecret = cardButton.dataset.secret;
            var form = $('#stripe-payment-form');
            cardButton.addEventListener('click', function(ev) {
                $(button).attr('disabled', 'disabled');
                stripe.handleCardPayment(
                    clientSecret,
                    {
                        payment_method: '<?php echo $this->vars->stripe_payment_method_id; ?>',
                    }
                ).then(function(result) {
                    if (result.error) {
                        form.find('.plugin_error_instruction').after('<br /><span class="j2error">'+result.error.message+'</span>');
                        //plugin_error_instruction
                        // Display error.message in your UI.
                    } else {
                        //get all form values
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
                        // The payment has succeeded. Display a success message.
                    }
                });
            });
        })(j2store.jQuery)
    </script>
<?php
} else {
    echo JText::_('J2STORE_STRIPE_AUTHENTICATE_PAYMENT_INVALID_REQUEST');
}
?>
