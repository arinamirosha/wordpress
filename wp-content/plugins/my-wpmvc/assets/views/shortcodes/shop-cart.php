<?php
/**
 * shortcodes.shop-cart view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */


use MyWpmvc\Controllers\OrderController;
use MyWpmvc\Controllers\ShopCartController;

if (isset($_POST) && !empty($_POST)) {
    if ($_POST['delete_cart_item']) {
        ShopCartController::remove_cart_item();
    } elseif ($_POST['change_qty']) {
        ShopCartController::change_quantity();
    } elseif ($_POST['checkout']) {
        OrderController::save();
    }
}

ShopCartController::show();
?>
