<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;
/**
 * Order model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class Order extends Model
{
    use FindTrait;

    const PICKUP   = 1; // Самовывоз
    const DELIVERY = 2; // Доставка

    const PROCESS  = 3; // Формируется
    const DELIVER  = 4; // Доставляется
    const READY    = 5; // Готов к выдаче
    const FINISHED = 6; // Завершен

    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'shopcart_order';

    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = [
        'title'              => 'post_title',
        'p_status'           => 'post_status',
        'shopcart_ids'       => 'meta_shopcart_ids',
        'to_pay'             => 'meta_to_pay',
        'first_name'         => 'meta_first_name',
        'last_name'          => 'meta_last_name',
        'patronymic'         => 'meta_patronymic',
        'phone'              => 'meta_phone',
        'promocode_discount' => 'meta_promocode_discount',
        'delivery_or_pickup' => 'meta_delivery_or_pickup',
        'address'            => 'meta_address',
        'delivery_price'     => 'meta_delivery_price',
        'order_status'       => 'meta_order_status',
        'buyer_full_name'    => 'func_get_buyer_full_name',
    ];

    public function get_buyer_full_name()
    {
        return $this->meta['first_name'] . ' ' . $this->meta['last_name'] . ' ' . $this->meta['patronymic'];
    }

    protected function shopcarts()
    {
        return $this->has_many( ShopCart::class, 'shopcart_ids' );
    }
    /**
     * Property registry_controller.
     * @since 1.0.0
     *
     * @var string
     */
    protected $registry_controller = 'OrderController';
    /**
     * Property registry_metabox.
     * @since 1.0.0
     *
     * @var array
     */
    protected $registry_metabox = array(
        'title'    => 'Meta fields',
        'context'  => 'normal',
        'priority' => 'default',
    );
}