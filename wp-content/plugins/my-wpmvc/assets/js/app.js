/**
 * my-js JavaScript asset.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

function show_info() {
    jQuery('info').style.display = 'block';
}

function submit_qty_form($id) {
    jQuery('#quantity-form-' + $id).submit();
}

jQuery('document').ready(function () {
    let pickup = jQuery('#pickup');
    if (pickup.attr('checked')) {
        jQuery('.delivery').hide();
    } else {
        jQuery('.pickup').hide();
    }
    pickup.change(function () {
        jQuery('.delivery').toggle();
        jQuery('.pickup').toggle();
    })

    jQuery("#phone_number").mask("8(999) 999-9999");

    var sum = 0;
    jQuery(".total-price").each(function() {
        sum += Number(jQuery(this).text());
    });
    jQuery("#total").text(sum);
})