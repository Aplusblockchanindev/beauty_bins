if(typeof(j2store) == 'undefined') {
    var j2store = {};
}
if(typeof(jQuery) != 'undefined') {
    jQuery.noConflict();
}
if(typeof(j2store.jQuery) == 'undefined') {
    j2store.jQuery = jQuery.noConflict();
}

/**
 * Edit Subscription
* */
function editSubscription(){
    (function($) {
        $('.j2store_subscription_detail_view .can_edit').hide();
        $('.j2store_subscription_detail_view .edit_item').show();
    })(j2store.jQuery);
}

/**
 * Cancel edit Subscription
 * */
function cancelEditSubscription(){
    (function($) {
        $('.j2store_subscription_detail_view .edit_item').hide();
        $('.j2store_subscription_detail_view .can_edit').show();
    })(j2store.jQuery);
}

/**
 * Update subscription
* */
function updateSubscription(){
    (function($) {
        var fields = $( "#j2store_subscription_detail_form" ).serializeArray();
        $('#j2store_subscription_detail_form input').removeClass("invalid");
        $.ajax({
            type : 'post',
            url :  'index.php',
            data : fields,
            cache: false,
            async : false,
            dataType : 'json',
            success : function(data) {
                if(data.status == '1'){
                    $('.j2store_susbcription_edit_message').html(data.message);
                    $('.j2store_susbcription_edit_message').show();
                    location.reload();
                } else {
                    if(data.errors != undefined){
                        $.each(data.errors, function( index, value ) {
                            $('#j2store_subscription_detail_form input[name='+value.key+']').addClass("invalid");
                        });
                    }
                    $('.j2store_susbcription_edit_message').html(data.message);
                    $('.j2store_susbcription_edit_message').show();
                }
            }
        });
    })(j2store.jQuery);
}

/**
 * Add subscription history
 * */
function addSubscriptionHistory(){
    (function($) {
        var note = $( "#subscription_history_note" ).val();
        var history_type = $( "#subscription_history_type" ).val();
        if(note == ''){
            $( "#subscription_history_note" ).addClass('invalid');
            return false;
        }
        $( "#subscription_history_note" ).removeClass('invalid');
        $.ajax({
            type : 'post',
            url :  'index.php',
            data : {
                'option': 'com_j2store',
                'view': 'app',
                'task': 'view',
                'appTask': 'addSubscriptionHistory',
                'id': $( "#subscription_history_id" ).val(),
                'sid': $( "#subscription_history_sid" ).val(),
                'note': note,
                'history_type': history_type
            },
            cache: false,
            async : false,
            dataType : 'json',
            success : function(data) {
                if(data.status == '1'){
                    $('.j2store_susbcription_history_message').html(data.message);
                    $('.j2store_susbcription_history_message').show();
                    location.reload();
                } else {
                    $('.j2store_susbcription_history_message').html(data.message);
                    $('.j2store_susbcription_history_message').show();
                }
            }
        });
    })(j2store.jQuery);
}

function updateSubscriptionOrderItemName(order_item_id, appId){
    (function($) {
        if (order_item_id != undefined && order_item_id != '' && appId != undefined && appId != '') {
            var order_item_name = $('#orderitem_name_' + order_item_id).val();
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'updateSubscriptionOrderItemName',
                    'id': appId,
                    'order_item_id': order_item_id,
                    'order_item_name': order_item_name
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
    })(j2store.jQuery);
}

function updateSubscriptionOrderItemAttributes(order_item_id, appId){
    (function($) {
        if (order_item_id != undefined && order_item_id != '' && appId != undefined && appId != '') {
            var order_item_attributes = $('#edit_orderitem_attribute_cont_'+ order_item_id+' .edit_orderitem_attribute');
            var data = {
                'option': 'com_j2store',
                'view': 'app',
                'task': 'view',
                'appTask': 'updateSubscriptionOrderItemAttributes',
                'id': appId,
                'order_item_id': order_item_id
            };
            $.each( order_item_attributes, function( key, field ) {
                var fieldObject = $(field);
                if (!(fieldObject.attr('name') in data) ){
                    data[fieldObject.attr('name')] = fieldObject.val();
                }
            });

            $.ajax({
                type: 'post',
                url: 'index.php',
                data: data,
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
    })(j2store.jQuery);
}

function addShippingForSubscriptionOrder(order_id, appId){
    (function($) {
        if (order_id != undefined && order_id != '' && appId != undefined && appId != '') {
            $('.add_shipping_con #shipping_name, .add_shipping_con #shipping_price, .add_shipping_con #shipping_extra').removeClass('invalid');
            var shipping_type = $('.add_shipping_con #shipping_type').val();
            var shipping_name = $('.add_shipping_con #shipping_name').val();
            var shipping_price = $('.add_shipping_con #shipping_price').val();
            var shipping_extra = $('.add_shipping_con #shipping_extra').val();
            var shipping_tax_class_id = $('.add_shipping_con #j2store_shipping_tax_class_id').val();
            if(shipping_name == ''){
                $('.add_shipping_con #shipping_name').addClass('invalid');
                return false;
            }
            if(shipping_price == ''){
                $('.add_shipping_con #shipping_price').addClass('invalid');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'addShippingForSubscriptionOrder',
                    'id': appId,
                    'order_id': order_id,
                    'type': shipping_type,
                    'name': shipping_name,
                    'price': shipping_price,
                    'extra': shipping_extra,
                    'tax_class_id': shipping_tax_class_id
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
    })(j2store.jQuery);
}

function addAddtionalFeeForSubscriptionOrder(order_id, appId){
    (function($) {
        if (order_id != undefined && order_id != '' && appId != undefined && appId != '') {
            $('.add_fee_con #fee_name, .add_fee_con #fee_amount').removeClass('invalid');
            var fee_name = $('.add_fee_con #fee_name').val();
            var fee_amount = $('.add_fee_con #fee_amount').val();
            var fee_tax_class_id = $('.add_fee_con #j2store_fee_tax_class_id').val();
            if(fee_name == ''){
                $('.add_fee_con #fee_name').addClass('invalid');
                return false;
            }
            if(fee_amount == ''){
                $('.add_fee_con #fee_amount').addClass('invalid');
                return false;
            }
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'addAdditionalFeeForSubscriptionOrder',
                    'id': appId,
                    'order_id': order_id,
                    'name': fee_name,
                    'amount': fee_amount,
                    'tax_class_id': fee_tax_class_id
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
    })(j2store.jQuery);
}

function removeShippingFromSubscriptionOrder(order_id, appId){
    (function($) {
        if (order_id != undefined && order_id != '' && appId != undefined && appId != '') {
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'removeShippingFromSubscriptionOrder',
                    'id': appId,
                    'order_id': order_id
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
    })(j2store.jQuery);
}

function removeAddtionalFeeFromSubscriptionOrder(fee_id, appId){
    (function($) {
        if (fee_id != undefined && fee_id != '' && appId != undefined && appId != '') {
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'removeAddtionalFeeFromSubscriptionOrder',
                    'id': appId,
                    'fee_id': fee_id
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
    })(j2store.jQuery);
}

function removeAttributeFromSubscriptionOrder(attribute_id, appId){
    (function($) {
        if (attribute_id != undefined && attribute_id != '' && appId != undefined && appId != '') {
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'removeAttributeFromSubscriptionOrder',
                    'id': appId,
                    'attribute_id': attribute_id
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
    })(j2store.jQuery);
}

function updateSubscriptionShippingName(order_id, appId){
    (function($) {
        var shipping_name = $('#edit_shipping_name').val();
        if (order_id != undefined && order_id != '' && appId != undefined && appId != '' && shipping_name != '') {
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {
                    'option': 'com_j2store',
                    'view': 'app',
                    'task': 'view',
                    'appTask': 'updateSubscriptionShippingName',
                    'id': appId,
                    'shipping_name': shipping_name,
                    'order_id': order_id
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
    })(j2store.jQuery);
}

function validateSubscriptionPaymentCardChangeForm(obj){
    (function($) {
        $.ajax({
            type: 'post',
            url: 'index.php',
            data: $('#subscription_update_card_details').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status == '1') {
                    $('#subscription_update_card_process').html(data.message);
                    $('#subscription_update_card_process').removeClass('hide').show();
                    $('#subscription_update_card_details').hide();
                } else {
                    $('#subscription_update_card_process').html(data.message);
                    $('#subscription_update_card_process').removeClass('hide').show();
                }
            }
        });
    })(j2store.jQuery);
}

j2store.jQuery(document).ready(function($) {
    $('#button-subscription_update_card').click(function () {
        validateSubscriptionPaymentCardChangeForm($(this));
    });
    /* Edit order item name */
    $(".edit_shipping_name").click(function () {
        var cont = $(this).closest('.edit_shipping_name_out');
        cont.next('.shipping-name').hide();
        cont.closest('tr').find('.remove_order_shipping').hide();
        cont.find('.edit_shipping_name_cont').css({'display':'inline-block'});
    });

    $(".edit_orderitem_name").click(function () {
        var cont = $(this).closest('.edit_orderitem_name_out');
        cont.next('.cart-product-name').hide();
        cont.find('.edit_orderitem_name_cont').css({'display':'inline-block'});
    });

    /* Cancel edit order item name */
    $(".cancel_edit_orderitem_name").click(function () {
        var cont = $(this).closest('.edit_orderitem_name_out');
        cont.find('.edit_orderitem_name_cont').hide();
        cont.next('.cart-product-name').css({'display':'inline-block'});
    });

    $(".cancel_edit_shipping_name").click(function () {
        var cont = $(this).closest('.edit_shipping_name_out');
        cont.next('.shipping-name').css({'display':'inline-block'});
        cont.closest('tr').find('.remove_order_shipping').css({'display':'inline-block'});
        cont.find('.edit_shipping_name_cont').hide();
    });

    /* Edit order item attributes */
    $(".edit_orderitem_attributes").click(function () {
        var cont = $(this).closest('.edit_orderitem_attributes_out');
        cont.prev('.cart-item-options').hide();
        cont.find('.edit_orderitem_attribute_cont').css({'display': 'block'});
    });

    /* Cancel edit order attributes */
    $(".cancel_orderitem_attribute").click(function () {
        var cont = $(this).closest('.edit_orderitem_attributes_out');
        cont.find('.edit_orderitem_attribute_cont').hide();
        cont.prev('.cart-item-options').css({'display': 'block'});
    });

    /* Edit order item price */
    $(".edit_orderitem_price").click(function () {
        var cont = $(this).closest('.cart-line-subtotal');
        cont.find('.orderitem_price_text_cont').hide();
        cont.find('.orderitem_price_edit_cont').css({'display':'inline-block'});
    });
    
    /* Cancel edit order item */
    $(".cancel_edit_orderitem_price").click(function () {
        var cont = $(this).closest('.cart-line-subtotal');
        cont.find('.orderitem_price_edit_cont').hide();
        cont.find('.orderitem_price_text_cont').css({'display':'inline-block'});
    });

    /* Edit order item quantity */
    $(".edit_orderitem_quantity").click(function () {
        var cont = $(this).closest('.cart-line-quantity');
        cont.find('.orderitem_quantity_cont').hide();
        cont.find('.orderitem_quantity_edit_cont').css({'display':'inline-block'});
    });

    /* Cancel edit item quantity */
    $(".cancel_edit_orderitem_quantity").click(function () {
        var cont = $(this).closest('.cart-line-quantity');
        cont.find('.orderitem_quantity_edit_cont').hide();
        cont.find('.orderitem_quantity_cont').css({'display':'inline-block'});
    });

    /* add/Cancel */
    $(".ac_btn_subscription").click(function () {
        var targetRemove = '';
        var targetAdd = '';
        if($(this).data('target_add') != undefined)
            targetAdd = $(this).data('target_add');
        if($(this).data('target_remove') != undefined)
            targetRemove = $(this).data('target_remove');
        if(targetAdd != '')
            $('#'+targetAdd).show();
        if(targetRemove != '')
            $('#'+targetRemove).hide();
    });

    /* Update subscription order item quantity */
    $(".update_orderitem_quantity").click(function () {
        var itemid = $(this).attr('data-itemid');
        updateSubscriptionOrderItemQuantity(itemid);
    });

    /* Update subscription order item */
    $(".update_orderitem_price").click(function () {
        var itemid = $(this).attr('data-itemid');
        updateSubscriptionOrderItem(itemid);
    });

    $(".update_shipping_name").click(function () {
        var order_id = $(this).attr('data-order_id');
        var appId = $(this).attr('data-appid');
        updateSubscriptionShippingName(order_id, appId);
    });

    /* Update subscription order item name */
    $(".update_orderitem_name").click(function () {
        var itemid = $(this).attr('data-itemid');
        var appId = $(this).attr('data-appid');
        updateSubscriptionOrderItemName(itemid, appId);
    });

    /* Update subscription order item attributes */
    $(".update_orderitem_attribute").click(function () {
        var itemid = $(this).attr('data-itemid');
        var appId = $(this).attr('data-appid');
        updateSubscriptionOrderItemAttributes(itemid, appId);
    });

    /* Remove attributes */
    $(".remove_orderitem_attribute").click(function () {
        var attribute_id = $(this).data('orderitem_attribute');
        var appId = $(this).data('appid');
        var confirmText = $(this).data('confirm_text');
        var confirmResult = confirm(confirmText);
        if(!confirmResult) {
            return false;
        }
        removeAttributeFromSubscriptionOrder(attribute_id, appId);
    });

    /* Calculate Order Total */
    $("#calculate_total").click(function () {
        calculateSubscriptionOrderTotal();
    });

    /* Add additional fee    */
    $("#confirm_add_additional_fee").click(function () {
        var orderid = $(this).data('orderid');
        var appId = $(this).data('appid');
        addAddtionalFeeForSubscriptionOrder(orderid, appId);
    });

    /* Remove additional fee */
    $(".remove_order_fee").click(function () {
        var fee_id = $(this).data('fee_id');
        var appId = $(this).data('appid');
        removeAddtionalFeeFromSubscriptionOrder(fee_id, appId);
    });

    /* Add Shipping in order */
    $("#confirm_add_shipping").click(function () {
        var orderid = $(this).data('orderid');
        var appId = $(this).data('appid');
        addShippingForSubscriptionOrder(orderid, appId);
    });

    /* Remove Shipping from order */
    $(".remove_order_shipping").click(function () {
        var orderid = $(this).data('order_id');
        var appId = $(this).data('appid');
        removeShippingFromSubscriptionOrder(orderid, appId);
    });

    $(".subs_update_status_form_con select.j2s_sbs_update_status").change(function () {
        var container = $(this).parent(".subs_update_status_form_con");
        var status = $(this).val();
        var has_notification = container.find("select.j2s_sbs_update_status option[value='"+status+"']").attr('notify-customer');
        if(has_notification === "1"){
            container.find(".subs_update_status_notify_option").show();
        } else {
            container.find(".subs_update_status_notify_option").hide();
        }
    });
    $(".subs_update_status_form_con select.j2s_sbs_update_status").trigger('change');
});