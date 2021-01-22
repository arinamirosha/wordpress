/**
 * my-js JavaScript asset.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

function show_info() {
    $('info').style.display = 'block';
}

function submit_qty_form($id) {
    $('#quantity-form-' + $id).submit();
}

$('document').ready(function () {
    $('.delivery').hide();
    $('#pickup').change(function () {
        $('.delivery').toggle();
        $('#shop_address').toggle();
    })
})