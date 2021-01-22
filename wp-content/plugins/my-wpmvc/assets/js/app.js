/**
 * my-js JavaScript asset.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

function show_info(){
    document.getElementById('info').style.display = 'block';
}

function submit_qty_form($id){
    document.getElementById('quantity-form-' + $id).submit();
}