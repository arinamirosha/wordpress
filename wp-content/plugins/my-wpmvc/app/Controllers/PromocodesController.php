<?php

namespace MyWpmvc\Controllers;

use MyWpmvc\Models\Promocode;
use WPMVC\MVC\Controllers\ModelController as Controller;
use WPMVC\Request;

/**
 * PromocodesController
 * WordPress MVC automated model controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class PromocodesController extends Controller
{
    /**
     * Property model.
     * @since 1.0.0
     *
     * @var string
     */
    protected $model = 'MyWpmvc\\Models\\Promocode';

    public function delete()
    {
        $promids = Request::input('promids');
        foreach ($promids as $promid) {
            Promocode::find($promid)->delete();
        }
    }

    public function save()
    {
        $request_data = Request::all();
        $form_errors = sanitize_promocode_request($request_data);

        if ( ! empty($form_errors) ) {
            $GLOBALS['form_errors'] = $form_errors;
        } else {
            $promocode = new Promocode();
            $promocode->title = str_replace(' ', '', $request_data['title']);
            $promocode->discount = $request_data['discount'];
            $promocode->type_discount = $request_data['type_discount'];
            $promocode->number_of_uses = 0;
            $promocode->p_status = 'publish';
            $promocode->save();
        }
    }
}