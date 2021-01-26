<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;
/**
 * ShopCart model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class ShopCart extends Model
{
    CONST IN_CART = 1;
    CONST ORDERED = 2;
    CONST FINISHED = 3;

    use FindTrait;
    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'shopcart';
    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = [
        'title'        => 'post_title',
        'p_status'     => 'post_status',
        'author'       => 'post_author',
        'product_id'   => 'meta_product_id',
        'quantity'     => 'meta_quantity',
        'order_status' => 'meta_order_status',
        'total_price'  => 'func_total_price',
    ];

    protected function total_price()
    {
        return $this->product->price * $this->quantity;
    }

    protected function product()
    {
        return $this->has_one( Product::class, 'product_id' );
    }

    /**
     * Property registry_controller.
     * @since 1.0.0
     *
     * @var string
     */
    protected $registry_controller = 'ShopCartController';
}