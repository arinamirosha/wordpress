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
    </div>
    <?php
}

// Add setting options
add_action('admin_init', 'my_wpmvc_settings');
function my_wpmvc_settings(){
    register_setting( 'option_group', 'address', 'sanitize_callback' );
    register_setting( 'option_group', 'schedule', 'sanitize_callback' );

    add_settings_section( 'section_id', 'Основные настройки', '', 'my_wpmvc_page' );

    add_settings_field('my_wpmvc_field1', '<label for="address">Адрес</label>', 'fill_address_field', 'my_wpmvc_page', 'section_id' );
    add_settings_field('my_wpmvc_field2', '<label for="schedule">График работы</label>', 'fill_schedule_field', 'my_wpmvc_page', 'section_id' );
}
function fill_address_field(){
    printf( '<input type="text" name="address" value="%s" />', esc_attr( get_option('address', '') ) );
}
function fill_schedule_field(){
    printf( '<input type="text" name="schedule" value="%s" />', esc_attr( get_option('schedule', '') ) );
}