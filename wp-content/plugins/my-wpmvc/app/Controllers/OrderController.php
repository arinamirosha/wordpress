<?php

namespace MyWpmvc\Controllers;

use MyWpmvc\Models\Promocode;
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

    public function save()
    {
        $request_data = Request::all('sanitize_text_field' );
        $form_errors = sanitize_order_request($request_data);

        if ( ! empty($form_errors) ) {
            $GLOBALS['form_errors'] = $form_errors;
        } else {

            // получить товары из корзины
            // создать строку из IDs через ,
            // рассчитать итого

            $promocode_id = (int) $request_data['promocode_id'];
            if ($promocode_id) {
                $promocode = Promocode::find($promocode_id);
                // применить промокод, если можно (итого выходит > 0)
            }

            if (! $request_data['pickup']) {
                // проверить доставку - платно/бесплатно
            }

            // изменить статус в shopcarts

            // сохранить заказ: shopcart_ids, к оплате, фио, тел, самовывоз/доставка(адрес)
        }
    }
}