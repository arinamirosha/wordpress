<?php

namespace MyWpmvc\Controllers;

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
            $GLOBALS['form_errors'] = $form_errors; // не ок - вернуть ошибки
        } else {
            echo 'fine'; // ок - сохранить
        }
    }
}