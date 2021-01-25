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
        $form_errors  = [];

        $arr = ['first_name', 'last_name', 'patronymic', 'phone_number']; // не > 100 символов, required
        foreach ($arr as $v) {
            if ($error = sanitize_string($request_data[$v])) $form_errors[$v] = $error;
        }

        if ( ! $request_data['pickup'] ) {
            if ($error = sanitize_string($request_data['address'])) $form_errors['address'] = $error;
        }

        if ( ! empty($form_errors) ) {
            $GLOBALS['form_errors'] = $form_errors; // не ок - вернуть ошибки
        } else {
            echo 'fine'; // ок - сохранить
        }
    }
}