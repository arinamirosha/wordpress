<?php

function true_add_mce_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'true_add_tinymce_script' );
        add_filter( 'mce_buttons', 'true_register_mce_button' );
    }
}
add_action( 'admin_head', 'true_add_mce_button' );

function true_add_tinymce_script( $plugin_array ) {
    $plugin_array['true_mce_button']     = get_stylesheet_directory_uri() . '/js/shortcode_buttons.js';
    $plugin_array['actors_scode_button'] = get_stylesheet_directory_uri() . '/js/shortcode_buttons.js';

    return $plugin_array;
}

function true_register_mce_button( $buttons ) {
    array_push( $buttons, 'true_mce_button' );
    array_push( $buttons, 'actors_scode_button' );

    return $buttons;
}