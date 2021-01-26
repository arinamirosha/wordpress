<?php

namespace MyWpmvc;

use WPMVC\Bridge;
/**
 * Main class.
 * Bridge between WordPress and App.
 * Class contains declaration of hooks and filters.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class Main extends Bridge
{
    /**
     * Declaration of public WordPress hooks.
     */
    public function init()
    {
        $this->add_shortcode( 'shop_cart', 'view@shortcodes.shop-cart' );
        $this->add_shortcode( 'shop_cart_add_button', 'view@shortcodes.shop-cart-add-button' );

        $this->add_model( 'ShopCart' );
        $this->add_model( 'Order' );

        $this->add_action( 'wp_enqueue_scripts', 'ShopCartController@enqueue_scripts_styles' );
    }
    /**
     * Declaration of admin only WordPress hooks.
     * For WordPress admin dashboard.
     */
    public function on_admin()
    {
        $this->add_filter( 'request', 'ShopCartController@custom_filter_for_shopcart' );
        $this->add_action( 'restrict_manage_posts', 'ShopCartController@custom_action_for_shopcart_html' );

        $this->add_filter( 'manage_shopcart_posts_columns', 'ShopCartController@custom_filter_add_status_column' );
        $this->add_action( 'manage_shopcart_posts_custom_column', 'ShopCartController@custom_action_fill_status_column' );
    }
}