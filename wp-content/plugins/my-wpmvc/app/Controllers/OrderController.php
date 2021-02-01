<?php

namespace MyWpmvc\Controllers;

use Carbon\Carbon;
use MyWpmvc\Models\Order;
use MyWpmvc\Models\Promocode;
use MyWpmvc\Models\ShopCart;
use WPMVC\MVC\Controllers\ModelController as Controller;
use WPMVC\Request;
use WPMVC\Response;
use function Automattic\Jetpack\Extensions\Eventbrite\get_current_url;

/**
 * OrderController
 * WordPress MVC automated model controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class OrderController extends Controller
{
    /**
     * Property model.
     * @since 1.0.0
     *
     * @var string
     */
    protected $model = 'MyWpmvc\\Models\\Order';
    /**
     * Save new order
     * @throws \Exception
     */
    public function save()
    {
        $request_data = Request::all( 'sanitize_text_field' );
        $form_errors = sanitize_order_request( $request_data );
        if ( ! empty( $form_errors ) ) {
            $GLOBALS['form_errors'] = $form_errors;
        } else {
            $shopcarts = get_shopcart_items();

            foreach ( $shopcarts as $shopcart ) {
                if ( get_post_status( $shopcart->product->ID ) != 'publish' ) {
                    $shopcart->delete();
                    $is_not_publish = true;
                }
            }
            if ($is_not_publish) {
                $GLOBALS['message'] = 'Некоторые товары в корзине были удалены до оформления заказа';
                return;
            }

            $total = 0;
            $ids = [];
            $qty = 0;
            foreach ( $shopcarts as $shopcart ) {
                $shopcart->order_status = ShopCart::ORDERED;
                $shopcart->save();
                array_push($ids, $shopcart->ID);
                $total += $shopcart->total_price;
                $qty += $shopcart->quantity;
            }
            $promocode_id = ( int ) $request_data['promocode_id'];
            if ( $promocode_id ) {
                $promocode = Promocode::find( $promocode_id );
                $newTotal = $promocode->type_discount == Promocode::PERCENT ? $total * ( 1 - $promocode->discount / 100 ) : $total - $promocode->discount;
                if ( $newTotal > 0 ) {
                    $promocode->number_of_uses++;
                    $promocode->save();
                    $promocode_discount = round( $newTotal - $total );
                    $total = $newTotal;
                }
            }
            if ( $request_data['pickup'] ) {
                $delivery_or_pickup = Order::PICKUP;
            } else {
                $delivery_or_pickup = Order::DELIVERY;
                $address = $request_data['address'];
                $delivery_price = get_option( 'delivery_price', 0 );
                $free_from = get_option( 'free_delivery_from', 0 );
                if ( $delivery_price
                    && ( !$free_from
                        || $free_from
                        && $total < $free_from
                    )
                ) {
                    $total += $delivery_price;
                    $delivery_p = $delivery_price;
                }
            }
            $order = new Order();
            $order->title = Carbon::now()->format( 'dmYHis' ) . get_current_user_id();
            $order->shopcart_ids = $ids;
            $order->to_pay = round( $total );
            $order->first_name = $request_data['first_name'];
            $order->last_name = $request_data['last_name'];
            $order->patronymic = $request_data['patronymic'];
            $order->phone = $request_data['phone_number'];
            $order->promocode_discount = $promocode_discount ? $promocode_discount : 0;
            $order->delivery_or_pickup = $delivery_or_pickup;
            $order->address = $address ? $address : '';
            $order->delivery_price = $delivery_p ? $delivery_p : 0;
            $order->order_status = Order::WAIT;
            $order->p_status = 'publish';
            $order->save();
        }
    }
    /**
     * Show user's orders
     */
    public function index()
    {
        $builder = wp_query_builder();
        $orders = $builder->select( '*' )
                          ->from( 'posts' )
                          ->where( [
                              'post_status' => 'publish',
                              'post_type' => 'shopcart_order',
                              'post_author' => get_current_user_id(),
                          ] )->order_by( 'post_date', 'desc' )
                          ->get( ARRAY_A, function ( $row ) {
                              return new Order( $row );
                          } );
        require_once ABSPATH . 'wp-content/plugins/my-wpmvc/assets/views/orders/index.php';
    }
    /**
     * Show order
     */
    public function show()
    {
        $order = Order::find( Request::input( 'post' ) );
        require_once ABSPATH . 'wp-content/plugins/my-wpmvc/assets/views/orders/show.php';
    }
    /**
     * Update order status
     */
    public function update()
    {
        $order      = Order::find( Request::input( 'post', 0, true, 'intval' ) );
        $new_status = Request::input( 'order_status', 0, true, 'intval' );
        $statuses   = [ Order::PROCESS, Order::DELIVER, Order::READY, Order::FINISHED ];

        if ( $order && in_array( $new_status, $statuses ) ) {
            $order->order_status = $new_status;
            $order->save();
            if ( $new_status == Order::FINISHED) {
                foreach ($order->shopcarts as $shopcart) {
                    $shopcart->order_status = ShopCart::FINISHED;
                    $shopcart->save();
                }
            }
        }
    }
    /**
     * Delete order
     */
    public function delete()
    {
        $order = Order::find( Request::input( 'post', 0, true, 'intval' ) );
        if ( $order ) {
            foreach ( $order->shopcarts as $shopcart ) {
                $shopcart->delete();
            }
            $order->delete();
        }
    }

    // FILTERS AND ACTIONS

    /**
     * @since 1.0.0
     *
     * @hook request
     *
     * @return
     */
    public function custom_filter_for_shopcart_order( $vars )
    {
        global $pagenow;
        global $post_type;
        $start_in_post_types = array( 'shopcart_order' );
        if ( !empty( $pagenow )
             && $pagenow == 'edit.php'
             && in_array( $post_type, $start_in_post_types )
        ) {
            if ( !empty( $_GET['meta_filter'] ) ) {
                switch ( intval( $_GET['meta_filter'] ) ) {
                    case 1:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::WAIT,
                            "compare" => "=",
                        ) );
                        break;
                    case 2:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::PROCESS,
                            "compare" => "=",
                        ) );
                        break;
                    case 3:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::DELIVER,
                            "compare" => "=",
                        ) );
                        break;
                    case 4:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::READY,
                            "compare" => "=",
                        ) );
                        break;
                    case 5:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::FINISHED,
                            "compare" => "=",
                        ) );
                        break;
                    case 6:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => Order::FINISHED,
                            "compare" => "!=",
                        ) );
                        break;
                }
            }
        }
        return $vars;
    }
    /**
     * @since 1.0.0
     *
     * @hook restrict_manage_posts
     *
     * @return
     */
    public function custom_action_for_shopcart_order_html()
    {
        global $pagenow;
        global $post_type;
        $start_in_post_types = array( 'shopcart_order' );
        if ( !empty( $pagenow )
             && $pagenow == 'edit.php'
             && in_array( $post_type, $start_in_post_types )
        ) {
            ?>
            <label for="filter-by-field" class="screen-reader-text">Мой фильтр</label>
            <select name="meta_filter" id="filter-by-field">
                <?php $options = array(
                    'Не применять фильтр',
                    'Ожидание',
                    'Формируется',
                    'Доставляется',
                    'Готов к выдаче',
                    'Завершен',
                    'Не завершен',
                );?>
                <?php foreach ($options as $key => $val) : ?>
                    <option<?php
                    if ( !isset( $_GET['meta_filter'] ) || $_GET['meta_filter'] == $key ) {
                        echo " selected";
                    }
                    ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
            <?php
        }
    }
    /**
     * @since 1.0.0
     *
     * @hook manage_shopcart_order_posts_columns
     *
     * @return
     */
    public function custom_filter_add_columns( $columns )
    {
        $new_columns = [
            'delivery_or_pickup' => 'Доставка/самовывоз',
            'order_status'       => 'Статус заказа',
        ];
        return array_slice( $columns, 0, 1 ) + $new_columns + $columns;
    }
    /**
     * @since 1.0.0
     *
     * @hook manage_shopcart_order_posts_custom_column
     *
     * @return
     */
    public function custom_action_fill_columns( $column_name )
    {
        if ( $column_name === 'delivery_or_pickup' ) {
            switch ( get_post_meta( get_the_ID(), 'delivery_or_pickup', true ) ) {
                case Order::DELIVERY:
                    echo 'Доставка';
                    break;
                case Order::PICKUP:
                    echo 'Самовывоз';
                    break;
            }
        }
        if ( $column_name === 'order_status' ) {
            switch ( get_post_meta( get_the_ID(), 'order_status', true ) ) {
                case Order::WAIT:
                    echo 'Ожидание';
                    break;
                case Order::PROCESS:
                    echo 'Формируется';
                    break;
                case Order::DELIVER:
                    echo 'Доставляется';
                    break;
                case Order::READY:
                    echo 'Готов к выдаче';
                    break;
                case Order::FINISHED:
                    echo 'Завершен';
                    break;
            }
        }
    }


}