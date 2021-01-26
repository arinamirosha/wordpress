<?php

namespace MyWpmvc\Controllers;

use AC\Request;
use MyWpmvc\Models\Promocode;
use WPMVC\MVC\Controllers\ModelController as Controller;
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
        $promids = $_POST['promids'];
        foreach ($promids as $promid) {
            Promocode::find($promid)->delete();
        }
    }
}