<div class="wrap">

    <?php use MyWpmvc\Models\Order;

    if ($order) : ?>

        <h1>№ <?php echo $order->title; ?> &mdash;
            <?php switch ($order->meta_order_status) {
                case Order::WAIT:     echo 'Ожидание'; break;
                case Order::PROCESS:  echo 'Формируется'; break;
                case Order::DELIVER:  echo 'Доставляется'; break;
                case Order::READY:    echo 'Готов к выдаче'; break;
                case Order::FINISHED: echo 'Завершен'; break;
            } ?>

            <form action="" method="post" id="cancel-order-form">
                <input type="hidden" name="post" value="<?php echo $order->ID ?>">
                <input type="hidden" name="delete">
                <button class="button" type="submit" id="cancel-button">Отменить</button>
            </form>
        </h1>


        <table class="widefat striped">

            <thead>
                <tr>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Стоимость 1 шт.</th>
                    <th>Итого</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($order->shopcarts as $shopcart) : ?>

                <tr>
                    <td><a class="row-title" href="<?php the_permalink($shopcart->product->ID) ?>"><?php echo $shopcart->product->title; ?></a></td>
                    <td><?php echo $shopcart->quantity; ?> шт.</td>
                    <td><?php echo round($shopcart->product->price); ?></td>
                    <td><?php echo $shopcart->total_price; ?></td>
                </tr>

            <?php endforeach; ?>
            </tbody>

        </table>

        <?php if ($order->promocode_discount) : ?>
            <h2>Скидка по промокоду: <?php echo $order->promocode_discount ?> руб.</h2>
            <hr />
        <?php endif; ?>


        <?php switch ($order->delivery_or_pickup) {

            case Order::PICKUP: ?>
                <h2>САМОВЫВОЗ</h2>
                <h2><?php echo $order->address ?></h2>
                <?php break;

            case Order::DELIVERY: ?>
                <h2>ДОСТАВКА:
                    <?php echo $order->delivery_price ? $order->delivery_price . ' руб.' : 'бесплатно' ?>
                </h2>
                <h2><?php echo $order->address ?></h2>
                <?php break;
        } ?>

        <hr />
        <h2><?php echo $order->buyer_full_name ?></h2>
        <h2>Тел. <?php echo $order->phone ?></h2>
        <hr />
        <h1>К ОПЛАТЕ: <?php echo $order->to_pay ?> руб.</h1>
        <hr />

        <?php if ( $order->order_status != Order::FINISHED ) : ?>
            <form action="" method="post">
                <input type="hidden" name="post" value="<?php echo $order->ID ?>">
                <input type="hidden" name="update">

                <?php switch ($order->order_status ) {
                    case Order::WAIT:
                        $val = Order::PROCESS;
                        break;
                    case Order::PROCESS:
                        $val = $order->delivery_or_pickup == Order::PICKUP ? Order::READY : Order::DELIVER;
                        break;
                    case Order::READY:
                    case Order::DELIVER:
                        $val = Order::FINISHED;
                        break;
                } ?>

                <input type="hidden" name="order_status" value="<?php echo $val; ?>">

                <button class="button" type="submit">
                    <?php switch ( $order->order_status ) {
                        case Order::WAIT:
                            echo 'Начать формировать';
                            break;
                        case Order::PROCESS:
                            echo $order->delivery_or_pickup == Order::PICKUP ? 'Готов к выдаче' : 'Начать доставку';
                            break;
                        case Order::READY:
                        case Order::DELIVER:
                            echo 'Завершить';
                            break;
                    } ?>
                </button>
            </form>
        <?php endif; ?>

    <?php else : ?>

        <h1>Такого заказа нет | Удален</h1>

    <?php endif; ?>

</div>