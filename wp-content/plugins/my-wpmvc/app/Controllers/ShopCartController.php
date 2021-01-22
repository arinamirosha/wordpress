<?php

namespace MyWpmvc\Controllers;

use MyWpmvc\Models\Product;
use MyWpmvc\Models\ShopCart;
use WPMVC\MVC\Controllers\ModelController as Controller;
use WPMVC\Request;

/**
 * ShopCartController
 * WordPress MVC automated model controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class ShopCartController extends Controller
{
    /**
     * Property model.
     * @since 1.0.0
     *
     * @var string
     */
    protected $model = 'MyWpmvc\\Models\\ShopCart';
    
    public function add_to_cart()
    {
        $quantity = Request::input( 'quantity', 0, true, 'intval' );
        if ( ! $quantity || $quantity < 1 ) return;

        $product_id = get_the_ID();
        $product = Product::find( $product_id );
        $user_id = get_current_user_id();

        $builder = wp_query_builder();
        $cart_item_id = $builder->select( 'ID' )
                                ->from( 'posts as a' )
                                ->join('postmeta as b', [ [ 'key_a' => 'a.ID', 'key_b' => 'b.post_id' ] ], true )
                                ->where( [
                                    'post_status' => 'publish',
                                    'post_type' => 'shopcart',
                                    'post_author' => $user_id,
                                    'raw' => "( (b.meta_key='product_id' AND b.meta_value=$product_id) OR "
                                             . "(b.meta_key='order_status' AND b.meta_value=" . ShopCart::IN_CART . ") )"
                                ] )
                                ->group_by('ID' )
                                ->having( 'count(ID) = 2' )
                                ->value();

        if ( $cart_item_id ) {
            $cart_item = ShopCart::find( $cart_item_id );
            $cart_item->quantity += $quantity;
            $cart_item->save();
        } else {
            $cart_item = new ShopCart();
            $cart_item->title = $product->title;
            $cart_item->product_id = $product_id;
            $cart_item->quantity = $quantity;
            $cart_item->p_status = 'publish';
            $cart_item->order_status = ShopCart::IN_CART;
            $cart_item->save();
        }
    }
    
    public function show()
    {
        $user_id = get_current_user_id();
        $builder = wp_query_builder();

        $shopcarts = $builder->select( '*' )->from( 'posts as a' )->join( 
            'postmeta as b',
            [ [ 'key_a' => 'a.ID', 'key_b' => 'b.post_id' ] ],
            true
         )->where( [
            'post_status' => 'publish',
            'post_type' => 'shopcart',
            'post_author' => $user_id,
            'meta_key' => 'order_status',
            'meta_value' => ShopCart::IN_CART,
        ] )->get( ARRAY_A, function ( $row ) {
            return new ShopCart( $row );
        } );

        if ($shopcarts) {
            $total = 0;
            foreach ( $shopcarts as $shopcart ) {
                $product = Product::find( $shopcart->product_id );
                $shopcart->product = $product;
                $total += $product->price * $shopcart->quantity;
            }

            require_once 'wp-content/plugins/my-wpmvc/assets/views/shopcart/show.php';
        } else {
            require_once 'wp-content/plugins/my-wpmvc/assets/views/shopcart/empty.php';
        }

    }

    public function show_add_to_cart_button()
    {
        require_once 'wp-content/plugins/my-wpmvc/assets/views/shopcart/add-to-cart-button.php';
    }

    public function remove_cart_item()
    {
        $shopcart_id = Request::input( 'shopcart_id', 0, true, 'intval' );
        $shopcart_item = get_shopcart_item( $shopcart_id );
        if ($shopcart_item) {
            $shopcart_item->delete();
        }
    }

    public function change_quantity()
    {
        $quantity = Request::input( 'quantity', 0, true, 'intval' );
        if ( ! $quantity || $quantity < 1 ) return;

        $shopcart_id = Request::input( 'shopcart_id', 0, true, 'intval' );
        $shopcart_item = get_shopcart_item( $shopcart_id );

        if ($shopcart_item) {
            $shopcart_item->quantity = $quantity;
            $shopcart_item->save();
        }
    }
    
    // FILTERS AND ACTIONS
    
    /**
     * @since 1.0.0
     *
     * @hook request
     *
     * @return
     */
    public function custom_filter_for_shopcart( $vars )
    {
        global $pagenow;
        global $post_type;
        $start_in_post_types = array( 'shopcart' );
        if ( !empty( $pagenow )
            && $pagenow == 'edit.php'
            && in_array( $post_type, $start_in_post_types )
        ) {
            if ( !empty( $_GET['meta_filter'] ) ) {
                switch ( intval( $_GET['meta_filter'] ) ) {
                    case 1:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => ShopCart::IN_CART,
                            "compare" => "=",
                        ) );
                        break;
                    case 2:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => ShopCart::ORDERED,
                            "compare" => "=",
                        ) );
                        break;
                    case 3:
                        $vars['meta_query'] = array( "relation" => "AND", array(
                            "key" => "order_status",
                            "value" => ShopCart::FINISHED,
                            "compare" => "=",
                        ) );
                        break;
                }
            }
        }
        return $vars;
    }
    
    /**
     * @since 1.0.0
     *
     * @hook restrict_manage_posts
     *
     * @return
     */
    public function custom_action_for_shopcart_html()
    {
        global $pagenow;
        global $post_type;
        $start_in_post_types = array( 'shopcart' );
        if ( !empty( $pagenow )
            && $pagenow == 'edit.php'
            && in_array( $post_type, $start_in_post_types )
        ) {
            ?>
            <label for="filter-by-field" class="screen-reader-text">Мой фильтр</label>
            <select name="meta_filter" id="filter-by-field">
                <option<?php 
            if ( !isset( $_GET['meta_filter'] ) || $_GET['meta_filter'] == 0 ) {
                echo " selected";
            }
            ?> value="0">Не применять фильтр</option>
                <option<?php 
            if ( isset( $_GET['meta_filter'] ) && $_GET['meta_filter'] == 1 ) {
                echo " selected";
            }
            ?> value="1">В корзине</option>
                <option<?php 
            if ( isset( $_GET['meta_filter'] ) && $_GET['meta_filter'] == 2 ) {
                echo " selected";
            }
            ?> value="2">Заказ оформлен</option>
                <option<?php 
            if ( isset( $_GET['meta_filter'] ) && $_GET['meta_filter'] == 3 ) {
                echo " selected";
            }
            ?> value="3">Заказ завершен</option>
            </select>
            <?php 
        }
    }
    
    /**
     * @since 1.0.0
     *
     * @hook manage_shopcart_posts_columns
     *
     * @return
     */
    public function custom_filter_add_status_column( $columns )
    {
        $new_columns = [ 'text_status' => 'Статус заказа' ];
        return array_slice( $columns, 0, 1 ) + $new_columns + $columns;
    }
    
    /**
     * @since 1.0.0
     *
     * @hook manage_shopcart_posts_custom_column
     *
     * @return
     */
    public function custom_action_fill_status_column( $column_name )
    {
        if ( $column_name === 'text_status' ) {
            //            switch (get_post()->order_status) {
            switch ( get_post_meta( get_the_ID(), 'order_status', true ) ) {
                case ShopCart::IN_CART:
                    echo 'В корзине';
                    break;
                case ShopCart::ORDERED:
                    echo 'Заказ оформлен';
                    break;
                case ShopCart::FINISHED:
                    echo 'Заказ завершен';
                    break;
            }
        }
    }
    
    /**
     * @since 1.0.0
     *
     * @hook wp_enqueue_scripts
     *
     * @return
     */
    public function enqueue_scripts_styles()
    {
//        wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
//        wp_enqueue_style( 'font-awesome' );

        wp_register_script( 'load_jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js' );
        wp_enqueue_script( 'load_jquery'  );
    }


}