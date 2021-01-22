<?php
/**
 * shopcart.add-to-cart-button view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
?>

<form action='' method="post">
    <input class="form-input--10" type="number" min="1" name="quantity" value="1"> шт.
    <input type="checkbox" class="hidden" name="add_to_cart" checked>
    <button type="submit">Добавить в корзину</button>
</form>