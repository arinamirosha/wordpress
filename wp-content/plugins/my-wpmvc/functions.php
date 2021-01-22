<?php

use MyWpmvc\Models\ShopCart;

function get_shopcart_item( $shopcart_id ) {
    $shopcart_item = ShopCart::find($shopcart_id);
    if ($shopcart_item &&
        $shopcart_item->author == get_current_user_id() &&
        $shopcart_item->order_status == ShopCart::IN_CART &&
        $shopcart_item->p_status == 'publish') {
        return $shopcart_item;
    }
    return false;
}