<?php

namespace MyWpmvc\Controllers;

use WPMVC\MVC\Controllers\ModelController as Controller;
/**
 * ShoppingCartController
 * WordPress MVC automated model controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class ShoppingCartController extends Controller
{
    /**
     * Property model.
     * @since 1.0.0
     *
     * @var string
     */
    protected $model = 'MyWpmvc\\Models\\ShoppingCart';
}