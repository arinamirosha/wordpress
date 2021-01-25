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

<?php $form_errors = $GLOBALS['form_errors']; ?>

<form action="" method="post">
    <div class="row">
        <div class="col-sm-2"><label for="first_name">Имя</label></div>
        <div class="col-sm-6">
            <input type="text" name="first_name" id="first_name" value="<?php echo $_POST['first_name'] ?>">
            <?php if ( ! empty($form_errors) && $form_errors['first_name'] ) : ?>
                <span class="danger">
                    <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                    <?php echo $form_errors['first_name'] ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2"><label for="last_name">Фамилия</label></div>
        <div class="col-sm-6">
            <input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name'] ?>">
            <?php if ( ! empty($form_errors) && $form_errors['last_name'] ) : ?>
                <span class="danger">
                    <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                    <?php echo $form_errors['last_name'] ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2"><label for="patronymic">Отчество</label></div>
        <div class="col-sm-6">
            <input type="text" name="patronymic" id="patronymic" value="<?php echo $_POST['patronymic'] ?>">
            <?php if ( ! empty($form_errors) && $form_errors['patronymic'] ) : ?>
                <span class="danger">
                    <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                    <?php echo $form_errors['patronymic'] ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2"><label for="phone_number">Тел. <i class="fa fa-phone"></i></label></div>
        <div class="col-sm-6">
            <input type="text" name="phone_number" id="phone_number" value="<?php echo $_POST['phone_number'] ?>">
            <?php if ( ! empty($form_errors) && $form_errors['phone_number'] ) : ?>
                <span class="danger">
                    <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                    <?php echo $form_errors['phone_number'] ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2"><label for="pickup">Самовывоз</label></div>
        <div class="col-sm-7"><input type="checkbox" id="pickup" name="pickup" <?php if ( ! $_POST['checkout'] || $_POST['pickup']) echo 'checked' ?>></div>
    </div>

    <div class="row pickup">
        <div class="col-sm-2"><strong>Адрес</strong></div>
        <div class="col-sm-6"><?php echo esc_attr( get_option('address', '') ) ?></div>
    </div>
    <div class="row pickup">
        <div class="col-sm-2"><strong>График</strong></div>
        <div class="col-sm-6"><?php echo esc_attr( get_option('schedule', '') ) ?></div>
    </div>

    <div class="row delivery">
        <div class="col-sm-2"><label for="address">Адрес</label></div>
        <div class="col-sm-6"><input type="text" name="address" id="address" value="<?php echo $_POST['address'] ?>">
            <?php if ( ! empty($form_errors) && $form_errors['address'] ) : ?>
                <span class="danger">
                    <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                    <?php echo $form_errors['address'] ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="row delivery">
        <div class="col-sm-2"><strong>Доставка</strong></div>

        <?php
        $free_delivery_from = esc_attr( get_option('free_delivery_from', 0) );
        $delivery_price = esc_attr( get_option('delivery_price', 0) );
        ?>

        <?php if ( $delivery_price && ( (! $free_delivery_from) || ( $free_delivery_from && $total < $free_delivery_from )) ) : ?>

            <div class="col-sm-6"><?php echo $delivery_price ?> руб.
                <?php if ( $free_delivery_from ) : ?>
                    (бесплатно от <?php echo $free_delivery_from ?> руб.)
                <?php endif; ?>
            </div>

        <?php else : ?>

            <div class="col-sm-6">Бесплатно</div>

        <?php endif; ?>

    </div>

    <hr />

    <div class="row text-center">
        <input type="checkbox" class="hidden" name="checkout" checked>
        <div class="col-sm-12"><button type="submit">Оформить заказ</button></div>
    </div>
</form>