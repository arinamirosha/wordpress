<?php
// Including js file
function my_scripts_add(){
    if( is_single() && get_post_type() == 'products' ){
        wp_enqueue_script("myScript", get_bloginfo( 'stylesheet_directory' ) . '/js/myScript.js', array('jquery'),"0.1",false);
    }
}
add_action( 'wp_enqueue_scripts', 'my_scripts_add' );


// MOVIES
include_once( 'inc/last-movies-shortcode.php' );
include_once( 'inc/actors-shortcode.php' );
include_once( 'inc/cat-posts-shortcode.php' );

// Includes
include_once( 'inc/add-shortcode-buttons.php' ); // Adding shortcode buttons
include_once( 'inc/get-search-form-filter.php' ); // Search for posts or for actors
include_once( 'inc/create-my-widget.php' ); // Create widget


// Translation
function my_child_theme_setup() {
    load_child_theme_textdomain( 'unitechild', get_stylesheet_directory() . '/languages' );
    // JS
    wp_register_script( 'some_handle', '/js/shortcode_buttons.js' );
    $translation_array = array(
        'ins_shortcode' => __( 'Insert shortcode', 'unitechild' ),
    );
    wp_localize_script( 'some_handle', 'translation_obj', $translation_array );
    wp_enqueue_script( 'some_handle' );
}
add_action( 'after_setup_theme', 'my_child_theme_setup' );


// WP_PageNavi css
function theme_pagination_current_class( $class_name ) {
	return 'h4';
}
add_filter( 'wp_pagenavi_class_current', 'theme_pagination_current_class' );


// Default content for new post (in editor)
function my_editor_content($content) {
    $content = "This is default content from functions";
    return $content;
}
add_filter('default_content', 'my_editor_content');


// TinyUrl
function getTinyUrl($url) {
    $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
    return $tinyurl;
}


// Maintenance mode
//function wp_maintenance_mode() {
//    if (!current_user_can('edit_themes') || !is_user_logged_in()) {
//        wp_die('<h1>На обслуживании</h1><br />Сайт находится на плановом обслуживании. Пожалуйста, зайдите позже.');
//    }
//}
//add_action('get_header', 'wp_maintenance_mode');


// Trigger menu for logged in and not logged in users
function my_wp_nav_menu_args( $args = '' ) {

    if( is_user_logged_in() ) {
        $args['menu'] = 'Primary menu';
    } else {
        $args['menu'] = 'Для незарегистрированных';
    }
    return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

