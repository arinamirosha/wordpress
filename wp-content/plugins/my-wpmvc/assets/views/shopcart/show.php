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
    <div class="col-md-3">Наименование</div>
    <div class="col-md-2">Количество</div>
    <div class="col-md-2">Стоимость</div>
    <div class="col-md-2">Всего</div>
</div>

<hr />

<?php foreach ($shopcarts as $shopcart) { ?>
    <div class="row">
        <div class="col-md-3"><a href="<?php the_permalink($shopcart->product_id); ?>"><?php echo $shopcart->product->title ?></a></div>
        <div class="col-md-2"><?php echo $shopcart->quantity ?> шт.</div>
        <div class="col-md-2"><?php echo $shopcart->product->price ?> руб.</div>
        <div class="col-md-2"><?php echo $shopcart->quantity * $shopcart->product->price ?> руб.</div>
        <div class="col-md-3">Удалить</div>
    </div>
<?php } ?>

<hr />

<div class="row h5">
    <div class="col-md-5"></div>
    <div class="col-md-2">Итого</div>
    <div class="col-md-5"><?php echo $total ?> руб.</div>
</div>