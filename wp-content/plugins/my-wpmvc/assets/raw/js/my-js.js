/**
 * my-js JavaScript asset.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

const PERCENT = 1;
const MONEY   = 2;

function show_info() {
    jQuery('info').style.display = 'block';
}

function submit_qty_form($id) {
    jQuery('#quantity-form-' + $id).submit();
}

jQuery('document').ready(function () {
    jQuery('#cancel-button').on('click', function () {
        return confirm('Вы уверены, что хотите отменить? Заказ будет удален.');
    })

    let pickup = jQuery('#pickup');
    if (pickup.attr('checked')) {
        jQuery('.delivery').hide();
    } else {
        jQuery('.pickup').hide();
    }
    pickup.change(function () {
        jQuery('.delivery').toggle();
        jQuery('.pickup').toggle();

        if (pickup.attr('checked')) {
            pickup.removeAttr('checked');
        } else {
            pickup.attr( 'checked', true);
        }
        count_to_pay();
    })

    count_to_pay();

    function count_to_pay() {
        let total = parseFloat(jQuery('#total').text());
        let toPay = total;
        if ( ! pickup.attr('checked') ) {
            let free = jQuery('#free');
            let delivery = free.text() ? 0 : parseFloat(jQuery('#delivery-price').text());
            toPay = (total + delivery).toFixed();
        }
        jQuery('#to-pay').text(toPay);
    }

    jQuery("#phone_number").mask("8(999) 999-9999");

    jQuery("#promocode-form").submit(function (e) {
        e.preventDefault();
        form = jQuery(this);
        jQuery.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            beforeSend: function() {
                jQuery('#message').text('Проверка...');
            },
            success: function (data) {
                let message = jQuery('#message');
                if (data.error) {
                    message.html('<span class="danger">' + data.errors.promocode + '</span>');
                } else {
                    let promocode   = data.data;
                    let total       = jQuery('#total');
                    let total_fixed = parseFloat(total.text());

                    let newTotal = promocode.type_discount === PERCENT ?
                        (total_fixed * (1 - promocode.discount / 100)).toFixed() :
                        total_fixed - promocode.discount;

                    if (newTotal <= 0) {
                        message.html('<span class="danger">' + 'Сумма покупки должна быть > 0' + '</span>');
                    } else {
                        jQuery('#submit-promocode').remove();
                        jQuery('#promocode').prop('readonly', true);
                        let message_text = promocode.type_discount === PERCENT ?
                            data.message + ' (' + (newTotal - total_fixed) + ' руб.)':
                            data.message;
                        message.text(message_text);
                        total.text(newTotal);

                        let free = jQuery('#free');
                        if (free.text()) {
                            let deliveryPrice = parseFloat(jQuery('#delivery-price').text());
                            let freeFrom = parseFloat(jQuery('#free-from').text());

                            if ( deliveryPrice && ( (! freeFrom) || ( freeFrom && newTotal < freeFrom )) ) {
                                let delivery = deliveryPrice +  ' руб.';
                                if ( freeFrom ) {
                                    delivery += ' (бесплатно от ' + freeFrom + ' руб.)';
                                }
                                free.text(delivery);
                                free.removeAttr('id');
                            }
                        }

                        count_to_pay();

                        let pId = '<input type="hidden" name="promocode_id"  value="' + promocode.ID + '">';
                        jQuery('#promocode-applied').html(pId);
                    }
                }
            },
            error: function (data) {
                console.log('error');
            }
        });
    });
})