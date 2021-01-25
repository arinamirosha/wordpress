<?php

use MyWpmvc\Models\ShopCart;

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
function sanitize_string( $str ) {
    if ( strlen($str) > 100) {
        return 'Не > 100 символов';
    } elseif ( empty( $str ) ) {
        return 'Заполните';
    }
    return false;
}

// Add my-wpmvc plugin settings to menu

add_action('admin_menu', 'add_my_wpmvc_page');
function add_my_wpmvc_page(){
    add_options_page( 'Настройки my-wpmvc', 'my-wpmvc', 'manage_options', 'my_wpmvc', 'my_wpmvc_options_page_output' );
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

// Add setting options
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