<?php

namespace MyWpmvc\Controllers;

use Carbon\Carbon;
use MyWpmvc\Models\Order;
use MyWpmvc\Models\Promocode;
use MyWpmvc\Models\ShopCart;
use WPMVC\MVC\Controllers\ModelController as Controller;
use WPMVC\Request;
use WPMVC\Response;

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
        $request_data = Request::all('sanitize_text_field' );
        $form_errors = sanitize_order_request( $request_data );

        if ( ! empty($form_errors) ) {
            $GLOBALS['form_errors'] = $form_errors;
        } else {
            $shopcarts = get_shopcart_items();
            $total     = 0;
            $ids       = [];
            $qty       = 0;
            foreach ( $shopcarts as $shopcart ) {
                $shopcart->order_status = ShopCart::ORDERED;
                $shopcart->save();
                array_push($ids, $shopcart->ID );
                $total += $shopcart->total_price;
                $qty   += $shopcart->quantity;
            }
            $ids = implode(',', $ids );

            $promocode_id = (int) $request_data['promocode_id'];
            if ( $promocode_id ) {
                $promocode = Promocode::find( $promocode_id );

                $newTotal = $promocode->type_discount == Promocode::PERCENT ?
                    round($total * ( 1 - $promocode->discount / 100 ) ) :
                    $total - $promocode->discount;

                if ( $newTotal > 0 ) {
                    $total = $newTotal;
                    $promocode->number_of_uses++;
                    $promocode->save();
                }
            }

            if ( $request_data['pickup'] ) {
                $address_or_pickup = Order::PICKUP;
            } else {
                $delivery_price = get_option('delivery_price', 0);
                $free_from = get_option('free_delivery_from', 0);
                if ( $delivery_price && ( (! $free_from) || ( $free_from && $total < $free_from )) ) {
                    $total += $delivery_price;
                }
                $address_or_pickup = $request_data['address'];
            }

            $order = new Order();
            $order->title             = Carbon::now()->format('dmYHis') . get_current_user_id();
            $order->shopcart_ids      = $ids;
            $order->to_pay            = $total;
            $order->first_name        = $request_data['first_name'];
            $order->last_name         = $request_data['last_name'];
            $order->patronymic        = $request_data['patronymic'];
            $order->phone             = $request_data['phone_number'];
            $order->address_or_pickup = $address_or_pickup;
            $order->p_status          = 'publish';
            $order->save();
        }
    }
}