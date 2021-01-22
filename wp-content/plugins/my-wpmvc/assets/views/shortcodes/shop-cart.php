<?php
/**
 * shortcodes.shop-cart view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */


use MyWpmvc\Controllers\ShopCartController;

if (isset($_POST) && !empty($_POST)) {
    ShopCartController::remove_cart_item();
}

ShopCartController::show();
?>
