<?php

use MyWpmvc\Controllers\OrderController;
use MyWpmvc\Controllers\PromocodesController;
use MyWpmvc\Models\Promocode;
use MyWpmvc\Models\ShopCart;

/**
 * Get publish shopcart item ('in cart') for current user by id
 * @param $shopcart_id
 *
 * @return false|object|\WPMVC\MVC\Traits\FindTrait
 */
function get_shopcart_item( $shopcart_id ) {
    $shopcart_item = ShopCart::find($shopcart_id);
    if ($shopcart_item &&
        $shopcart_item->author == get_current_user_id() &&
        $shopcart_item->order_status == ShopCart::IN_CART &&
        $shopcart_item->p_status == 'publish') {
        return $shopcart_item;
    }
    return false;
}

/**
 * Get publish shopcart items ('in cart') for current user
 * @return array
 * @throws Exception
 */
function get_shopcart_items() {
    $builder = wp_query_builder();
    $shopcarts = $builder->select( '*' )
                         ->from( 'posts as a' )
                         ->join(
                             'postmeta as b',
                             [ [ 'key_a' => 'a.ID', 'key_b' => 'b.post_id' ] ],
                             true
                         )
                         ->where( [
                             'post_status' => 'publish',
                             'post_type'   => 'shopcart',
                             'post_author' => get_current_user_id(),
                             'meta_key'    => 'order_status',
                             'meta_value'  => ShopCart::IN_CART,
                         ] )
                         ->get( ARRAY_A, function ( $row ) {
                             return new ShopCart( $row );
                         } );
    return $shopcarts;
}


/**
 * Check string's length and requirement, return error message or false
 * @param $str
 * @param int $max_len
 * @param bool $is_required
 *
 * @return false|string
 */
function sanitize_string( $str, $max_len = 255, $is_required = true ) {
    if ( strlen($str) > $max_len) {
        return 'Не > ' . $max_len . ' символов';
    } elseif ( $is_required && empty( $str ) ) {
        return 'Заполните';
    }
    return false;
}

/**
 * Check number's type and min value, return error message or false
 * @param $str
 * @param int $max_len
 * @param bool $is_required
 *
 * @return false|string
 */
function sanitize_number( $number, $type = 'int', $min = 0, $is_required = true ) {
    switch ($type) {
        case 'int':
            $number = (int) $number;
            break;
        case 'float':
            $number = (float) $number;
            break;
    }

    if ( $number < $min ) {
        return 'Минимум ' . $min;
    } elseif ( $is_required && empty( $number ) ) {
        return 'Заполните';
    }

    return false;
}

/**
 * Sanitize order request data
 * @param $request_data
 *
 * @return array
 */
function sanitize_order_request( $request_data ) {
    $form_errors  = [];

    $arr = ['first_name', 'last_name', 'patronymic', 'phone_number'];
    foreach ($arr as $v) {
        if ($error = sanitize_string($request_data[$v], 100)) {
            $form_errors[$v] = $error;
        }
    }
    if ( ! $request_data['pickup'] ) {
        if ($error = sanitize_string($request_data['address'], 150)) {
            $form_errors['address'] = $error;
        }
    }

    return $form_errors;
}

/**
 * Sanitize promo code request data
 * @param $request_data
 *
 * @return array
 */
function sanitize_promocode_request( $request_data ) {
    $form_errors  = [];

    if ( $error = sanitize_string($request_data['title'], 50) ) {
        $form_errors['title'] = $error;
    } else {
        $promocode_title = str_replace(' ', '', $request_data['title']);
        $builder = wp_query_builder();
        $promocode_id = $builder->select( 'ID' )
                                ->from( 'posts' )
                                ->where( [
                                    'post_title' => $promocode_title,
                                    'post_type' => 'promocode',
                                ] )
                                ->value();
        if ($promocode_id) {
            $form_errors['title'] = 'Уже существует';
        }
    }

    if ( $error = sanitize_number($request_data['discount'], 'float') ) {
        $form_errors['discount'] = $error;
    }
    $t_discount = $request_data['type_discount'];
    if ( ! isset($t_discount) || ( $t_discount != Promocode::PERCENT && $t_discount != Promocode::MONEY ) ) {
        $form_errors['type_discount'] = 'Ошибка';
    }

    return $form_errors;
}



//SETTINGS FOR ADMIN

// Add my-wpmvc plugin menu settings (options)

add_action('admin_menu', 'add_my_wpmvc_page');
function add_my_wpmvc_page(){
    add_menu_page( 'Настройки my-wpmvc', 'my-wpmvc', 'manage_options', 'my_wpmvc', 'my_wpmvc_options_page_output' );
}
function my_wpmvc_options_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>
        <form action="options.php" method="POST">
            <?php
            settings_fields( 'option_group' );
            do_settings_sections( 'my_wpmvc_page' );
            submit_button();
            ?>
        </form>
        <p>
            Шорткод <strong>[shop_cart_add_button]</strong> - кнопка добавления в корзину (на странице товара)<br />
            Шорткод <strong>[shop_cart]</strong> - корзина (на отдельной странице)
        </p>
        <p>
            Для типа постов "<strong>products</strong>" с мета-полем "<strong>price</strong>"
        </p>

    </div>
    <?php
}
add_action('admin_init', 'my_wpmvc_settings');
function my_wpmvc_settings(){
    register_setting( 'option_group', 'address', 'string' );
    register_setting( 'option_group', 'schedule', 'string' );
    register_setting( 'option_group', 'delivery_price', 'intval' );
    register_setting( 'option_group', 'free_delivery_from', 'intval' );

    add_settings_section( 'section_id', 'Основные настройки', '', 'my_wpmvc_page' );

    add_settings_field('my_wpmvc_field1', '<label for="address">Адрес</label>', 'fill_address_field', 'my_wpmvc_page', 'section_id' );
    add_settings_field('my_wpmvc_field2', '<label for="schedule">График работы</label>', 'fill_schedule_field', 'my_wpmvc_page', 'section_id' );
    add_settings_field('my_wpmvc_field3', '<label for="delivery_price">Стоимость доставки</label>', 'fill_delivery_price_field', 'my_wpmvc_page', 'section_id' );
    add_settings_field('my_wpmvc_field4', '<label for="free_delivery_from">Бесплатно от</label>', 'fill_free_delivery_from_field', 'my_wpmvc_page', 'section_id' );
}
function fill_address_field(){
    printf( '<input type="text" name="address" value="%s" />', esc_attr( get_option('address', '') ) );
}
function fill_schedule_field(){
    printf( '<input type="text" name="schedule" value="%s" />', esc_attr( get_option('schedule', '') ) );
}
function fill_delivery_price_field(){
    printf( '<input type="number" min="0" step="10" name="delivery_price" value="%s" /> руб.', esc_attr( get_option('delivery_price', 0) ) );
}
function fill_free_delivery_from_field(){
    printf( '<input type="number" min="0" step="10" name="free_delivery_from" value="%s" /> руб.', esc_attr( get_option('free_delivery_from', 0) ) );
}

// Add my-wpmvc plugin submenu settings (promo codes)

add_filter( 'set_screen_option_' . 'promocodes_per_page', function( $status, $option, $value ){
    return (int) $value;
}, 10, 3 );
add_action('admin_menu', 'my_wpmvc_submenu');
function my_wpmvc_submenu() {
    $hook = add_submenu_page( 'my_wpmvc', 'Промокоды', 'Промокоды',
        'manage_options', 'my_wpmvc_promocodes','my_wpmvc_promocodes_page_output');
    add_action( "load-$hook", 'example_table_page_load' );
}
function example_table_page_load(){

    if (!empty($_POST) && isset($_POST['add_new'])) {
        PromocodesController::save();
    }

    require_once __DIR__ . '/app/class-Promocodes_List_Table.php';
    $GLOBALS['Promocodes_List_Table'] = new Promocodes_List_Table();
}
function my_wpmvc_promocodes_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <?php require_once 'assets/views/promocodes/create-form.php'; ?>

        <form action="" method="POST">
            <input type="hidden" name="page" value="my_wpmvc_promocodes" />
            <?php $GLOBALS['Promocodes_List_Table']->search_box('search', 'search_id'); ?>
        </form>

        <form action="" method="POST">
            <?php $GLOBALS['Promocodes_List_Table']->display(); ?>
        </form>

    </div>
    <?php
}


// AJAX

add_action( 'wp_ajax_check_promocode', 'check_promocode_function' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!} для авторизованных
function check_promocode_function(){
    PromocodesController::check_promocode();
}


// Add admin page for showing order

add_filter( 'post_row_actions', 'filter_function_name_2859', 10, 2 );
function filter_function_name_2859( $actions, $post ){
    global $post_type;
    if ( $post_type == 'shopcart_order' ) {
        $actions = [];
        $actions['view'] = '<a href="' . get_admin_url() . '/admin.php?page=order_show&post=' . $post->ID . '" aria-label="Открыть заказ">Открыть</a>';
    }
    return $actions;
}

add_action('admin_menu', 'add_my_wpmvc_page_post_show');
function add_my_wpmvc_page_post_show(){
    add_menu_page( 'Открыть пост', 'order-show', 'edit_pages', 'order_show', 'order_show_output' );
}
function order_show_output(){
    if ( isset($_POST) && !empty($_POST) ) {
        OrderController::update();
    }
    OrderController::show();
}
add_action('admin_menu', 'remove_admin_menu');
function remove_admin_menu() {
    remove_menu_page('order_show');
}