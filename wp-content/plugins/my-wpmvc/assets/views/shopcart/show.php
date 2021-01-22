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
        <div class="col-sm-2"><?php echo $shopcart->quantity ?> шт.
<!--            <form action="" method="post">-->
<!--                <input type="hidden" name="shopcart_id" value="--><?php //echo $shopcart->ID ?><!--" readonly>-->
<!--                <input class="form-input--50" type="number" name="quantity" value="--><?php //echo $shopcart->quantity ?><!--"> шт.-->
<!--            </form>-->
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

<hr />

<div class="row h5">
    <div class="col-sm-5"></div>
    <div class="col-sm-2">Итого</div>
    <div class="col-sm-5"><?php echo $total ?> руб.</div>
</div>