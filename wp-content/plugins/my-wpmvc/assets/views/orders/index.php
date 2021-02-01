<?php
/**
 * orders.show view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

use MyWpmvc\Models\Order;

?>

<?php if ($orders) : ?>

    <?php foreach ($orders as $order) : ?>

        <div class="row">
            <div class="col-sm-9">
                <span class="h5">№ <?php  echo $order->title ?></span>,
                <?php echo $order->buyer_full_name ?>,
                <?php echo $order->phone ?>
            </div>
            <div class="col-sm-3 text-right">
                <span class="<?php echo $order->order_status == Order::FINISHED ? 'danger' : 'success'; ?>">
                    <?php
                    switch ( $order->order_status ) {
                        case Order::WAIT:     echo 'Ожидание'; break;
                        case Order::PROCESS:  echo 'Формируется'; break;
                        case Order::DELIVER:  echo 'Доставляется'; break;
                        case Order::READY:    echo 'Готов к выдаче'; break;
                        case Order::FINISHED: echo 'Завершен'; break;
                    }
                    ?>
                </span>
            </div>
        </div>

        <?php foreach ($order->shopcarts as $shopcart) : ?>
            <div class="row">
                <div class="col-sm-8"><?php echo $shopcart->title ?></div>
                <div class="col-sm-2"><?php echo $shopcart->quantity ?> шт.</div>
                <div class="col-sm-2 text-right"><?php echo $shopcart->total_price ?></div>
            </div>
        <?php endforeach; ?>

        <?php if ( $order->promocode_discount ) : ?>
            <div class="row">
                <div class="col-sm-10">Скидка по промокоду</div>
                <div class="col-sm-2 text-right"><?php echo $order->promocode_discount ?></div>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if ( $order->delivery_or_pickup == Order::PICKUP ) : ?>
                <div class="col-sm-12">
                    Самовывоз:
                    <?php echo esc_attr( get_option('address', '') ) ?>,
                    <?php echo esc_attr( get_option('schedule', '') ) ?>
                </div>
            <?php else : ?>
                <div class="col-sm-10">Доставка: <?php echo $order->address; ?></div>
                <div class="col-sm-2 text-right"><?php echo $order->delivery_price ? $order->delivery_price : 'Бесплатно'; ?></div>
            <?php endif; ?>
        </div>

        <div class="row h5">
            <div class="col-sm-10">К оплате</div>
            <div class="col-sm-2 text-right"><?php  echo $order->to_pay ?></div>
        </div>

        <hr />

    <?php endforeach; ?>

<?php else : ?>

    <div class="row h3 text-center">
        Пусто
    </div>

<?php endif; ?>
