<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;
/**
 * Promocode model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class Promocode extends Model
{
    use FindTrait;

    const PERCENT = 1;
    const MONEY   = 2;

    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'promocode';
    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = [
        'title'          => 'post_title',
        'date'           => 'post_date',
        'p_status'       => 'post_status',
        'discount'       => 'meta_discount',
        'type_discount'  => 'meta_type_discount',
        'number_of_uses' => 'meta_number_of_uses',
    ];
    /**
     * Basic registry definition.
     * This sample shows the default settings.
     * @var array
     */
    protected $registry = [
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => false,
        'show_in_menu'       => false,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
    ];
    /**
     * Property registry_controller.
     * @since 1.0.0
     *
     * @var string
     */
    protected $registry_controller = 'PromocodesController';
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