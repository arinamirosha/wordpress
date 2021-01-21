<?php

namespace MyWpmvc\Controllers;

use MyWpmvc\Models\Product;
use MyWpmvc\Models\ShopCart;
use WPMVC\MVC\Controllers\ModelController as Controller;
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
        $product_id = get_the_ID();
        $product = Product::find( $product_id );
        $user_id = get_current_user_id();
        global $wpdb;

        $cart_item_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts as p LEFT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id "
                                        . "WHERE post_status='publish' AND post_type='shopcart' AND post_author=$user_id AND "
                                        . "( (pm.meta_key='product_id' AND pm.meta_value=$product_id) OR "
                                        . "(pm.meta_key='order_status' AND pm.meta_value=" . ShopCart::IN_CART . ") )"
                                        . "GROUP BY ID HAVING count(ID)=2" );

        if ( $cart_item_id ) {
            $cart_item = ShopCart::find( $cart_item_id );
            $cart_item->quantity++;
            $cart_item->save();
        } else {
            $cart_item = new ShopCart();
            $cart_item->title = $product->title;
            $cart_item->product_id = $product_id;
            $cart_item->quantity = 1;
            $cart_item->p_status = 'publish';
            $cart_item->order_status = ShopCart::IN_CART;
            $cart_item->save();
        }
    }

    public function show()
    {
        $user_id = get_current_user_id();
//        global $wpdb;
//
//        $cart_item_ids = $wpdb->get_results("SELECT ID FROM $wpdb->posts a LEFT JOIN $wpdb->postmeta b ON a.ID = b.post_id "
//                                            . "WHERE post_type='shopcart' AND post_status='publish' AND post_author=$user_id "
//                                            . "AND meta_key='order_status' AND meta_value=" . ShopCart::IN_CART);
//
//        $total = 0;
//        foreach ($cart_item_ids as $cart_item_id) {
//            $sc = ShopCart::find($cart_item_id->ID);
//            $product = Product::find($sc->product_id);
//            $total_price = $sc->quantity * $product->price;
//            $total += $total_price;
//            echo $product->title . ' '. $sc->quantity . ' ' . $product->price . ' ' . $total_price . '<br />';
//        }
//        echo 'total = '. $total;
        // Адаптировать (или полный запрос) и вынести в шаблон

        $builder = wp_query_builder();
        $shopcarts = $builder->select( '*' )
                           ->from( 'posts as a' )
                           ->join( 'postmeta as b', [
                               [
                                   'key_a' => 'a.ID',
                                   'key_b' => 'b.post_id',
                               ],
                           ] , true)
                           ->where( [
                               'post_status' => 'publish',
                               'post_type'   => 'shopcart',
                               'post_author' => $user_id,
                               'meta_key' => 'order_status',
                               'meta_value' => ShopCart::IN_CART,
                           ] )
                           ->get(ARRAY_A, function( $row ) {
                               return new ShopCart( $row );
                           } );

        $total = 0;
        foreach ($shopcarts as $shopcart) {
            $product = Product::find($shopcart->product_id);
            $shopcart->product = $product;
            $total += $product->price * $shopcart->quantity;
        }

        require_once 'wp-content/plugins/my-wpmvc/assets/views/shopcart/show.php';
    }

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
                        $vars['meta_query'] = array( "relation" => "AND", array( "key" => "order_status", "value" => ShopCart::IN_CART, "compare" => "=" ) );
                        break;
                    case 2:
                        $vars['meta_query'] = array( "relation" => "AND", array( "key" => "order_status", "value" => ShopCart::ORDERED, "compare" => "=" ) );
                        break;
                    case 3:
                        $vars['meta_query'] = array( "relation" => "AND", array( "key" => "order_status", "value" => ShopCart::FINISHED, "compare" => "=" ) );
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
        $start_in_post_types=array('shopcart');

        if( !empty($pagenow) && $pagenow=='edit.php' && in_array($post_type , $start_in_post_types) ){
            ?>
            <label for="filter-by-field" class="screen-reader-text">Мой фильтр</label>
            <select name="meta_filter" id="filter-by-field">
                <option<?php if(!isset($_GET['meta_filter']) || $_GET['meta_filter']==0){echo " selected";}?> value="0">Не применять фильтр</option>
                <option<?php if(isset($_GET['meta_filter']) && $_GET['meta_filter']==1){echo " selected";}?> value="1">В корзине</option>
                <option<?php if(isset($_GET['meta_filter']) && $_GET['meta_filter']==2){echo " selected";}?> value="2">Заказ оформлен</option>
                <option<?php if(isset($_GET['meta_filter']) && $_GET['meta_filter']==3){echo " selected";}?> value="3">Заказ завершен</option>
            </select>
            <?php
        }
        return;
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
        return;
    }
}