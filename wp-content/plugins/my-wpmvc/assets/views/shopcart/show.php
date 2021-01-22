<?php
/**
 * shopcart.show view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
?>

<div class="row h5">
    <div class="col-sm-3">Наименование</div>
    <div class="col-sm-2">Количество</div>
    <div class="col-sm-2">Стоимость</div>
    <div class="col-sm-2">Всего</div>
</div>

<hr />

<?php foreach ($shopcarts as $shopcart) { ?>
    <div class="row shopcart-item">
        <div class="col-sm-3"><a href="<?php the_permalink($shopcart->product_id); ?>"><?php echo $shopcart->product->title ?></a></div>
        <div class="col-sm-2">
            <form action="" method="post" id="quantity-form-<?php echo $shopcart->ID ?>">
                <input type="hidden" name="shopcart_id" value="<?php echo $shopcart->ID ?>" readonly>
                <input type="checkbox" class="hidden" name="change_qty" checked>
                <input class="form-input--50" type="number" name="quantity" min="1" step="1" value="<?php echo $shopcart->quantity ?>"
                       onchange="submit_qty_form(<?php echo $shopcart->ID ?>)"
                       onkeypress="show_info()"> шт.
            </form>
        </div>
        <div class="col-sm-2"><?php echo $shopcart->product->price ?> руб.</div>
        <div class="col-sm-2"><?php echo $shopcart->quantity * $shopcart->product->price ?> руб.</div>
        <div class="col-sm-3">
            <form action="" method="post">
                <input type="hidden" name="shopcart_id" value="<?php echo $shopcart->ID ?>" readonly>
                <input type="checkbox" class="hidden" name="delete_cart_item" checked>
                <button type="submit" class="del-button--no-style"><i class="fa fa-trash"></i></button>
            </form>
        </div>
    </div>
<?php } ?>

<span id="info" class="display-none">Нажмите Enter, чтобы применить</span>

<hr />

<div class="row h5">
    <div class="col-sm-5"></div>
    <div class="col-sm-2">Итого</div>
    <div class="col-sm-5"><?php echo $total ?> руб.</div>
</div>

<hr />

<form action="">
    <div class="row">
        <div class="col-sm-2"><label for="name">Имя</label></div>
        <div class="col-sm-6"><input type="text" name="name" required></div>
    </div>
    <div class="row">
        <div class="col-sm-2"><label for="surname">Фамилия</label></div>
        <div class="col-sm-6"><input type="text" name="surname" required></div>
    </div>
    <div class="row">
        <div class="col-sm-2"><label for="patronymic">Отчество</label></div>
        <div class="col-sm-6"><input type="text" name="patronymic" required></div>
    </div>
    <div class="row">
        <div class="col-sm-2"><label for="pickup">Самовывоз</label></div>
        <div class="col-sm-7"><input type="checkbox" id="pickup" name="pickup" checked>
            <span id="shop_address">Здесь указан адрес магазина и график работы Здесь указан адрес магазина и график работы</span>
        </div>
    </div>
    <div class="row delivery">
        <div class="col-sm-2"><label for="address">Адрес</label></div>
        <div class="col-sm-6"><input type="text" name="address"></div>
    </div>
    <div class="row delivery">
        <div class="col-sm-2"><label for="phone_number">Тел. <i class="fa fa-phone"></i></label></div>
        <div class="col-sm-6"><input type="text" name="phone_number"></div>
    </div>

    <hr />

    <div class="row text-center">
        <div class="col-sm-12"><button type="submit">Оформить заказ</button></div>
    </div>
</form>