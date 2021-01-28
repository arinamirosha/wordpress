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

    const PICKUP = 'Самовывоз';

    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'order';
    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = [
        'title'             => 'post_title',
        'p_status'          => 'post_status',
        'shopcart_ids'      => 'meta_shopcart_ids',
        'to_pay'            => 'meta_to_pay',
        'first_name'        => 'meta_first_name',
        'last_name'         => 'meta_last_name',
        'patronymic'        => 'meta_patronymic',
        'phone'             => 'meta_phone',
        'address_or_pickup' => 'meta_address_or_pickup',
        'buyer_full_name'   => 'func_get_buyer_full_name',
    ];
    /**
     * Returns buyer's full name
     * @return string
     */
    public function get_buyer_full_name()
    {
        return $this->meta['first_name'] . ' ' . $this->meta['last_name'] . ' ' . $this->meta['patronymic'];
    }
    /**
     * Returns "has_many" relationship.
     * @return object|Relationship
     */
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
        'title' => 'Meta fields',
        'context' => 'normal',
        'priority' => 'default',
    );
}