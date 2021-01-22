<?php
/**
 * shortcodes.shop-cart-add-button view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

use MyWpmvc\Controllers\ShopCartController;
use MyWpmvc\Models\Product;
use MyWpmvc\Models\ShopCart;
use MyWpmvc\Models\User;

if (isset($_POST) && !empty($_POST)) {
    ShopCartController::add_to_cart();
}

ShopCartController::show_add_to_cart_button();

?>
