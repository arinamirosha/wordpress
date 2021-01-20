<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;
/**
 * ShoppingCart model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class ShoppingCart extends Model
{
    use FindTrait;
    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'shopping_cart';
    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = array();
    /**
     * Property registry_controller.
     * @since 1.0.0
     *
     * @var string
     */
    protected $registry_controller = 'ShoppingCartController';
    /**
     * Property registry_metabox.
     * @since 1.0.0
     *
     * @var array
     */
    protected $registry_metabox = array(
        'title' => 'Meta fields',
        'context' => 'normal',
        'priority' => 'default',
    );
}