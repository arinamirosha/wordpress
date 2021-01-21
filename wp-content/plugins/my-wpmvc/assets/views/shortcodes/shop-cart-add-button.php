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

?>
<!-- сдлеать условаие if logged in and product has price, хотя второе вроде и так работает -->
<form action='' method="post">
    <input type="checkbox" class="hidden" name="add_to_cart" checked>
    <button type="submit">Добавить в корзину</button>
</form>