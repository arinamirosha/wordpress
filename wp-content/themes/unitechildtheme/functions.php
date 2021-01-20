<?php
// FILMS
//include_once('inc/test-films-type-with-taxonomies.php');


// MOVIES
include_once( 'inc/last-movies-shortcode.php' );
include_once( 'inc/actors-shortcode.php' );
include_once( 'inc/cat-posts-shortcode.php' );


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


// Adding shortcode buttons
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


// Search for posts or for actors
function my_search_form( $form ) {

	global $wp;

	if ( strripos( get_permalink(), 'actors' ) || ( ! empty( $_GET['tax'] ) && $_GET['tax'] == 'actors' ) ) {
		$url   = esc_url( home_url( '/' ) );
		$input = '<input type="hidden" value="actors" name="tax">';
	} else {
		$url   = home_url( $wp->request );
		$input = '';
	}

	$form = "<form role='search' method='get' class='search-form form-inline' action='$url'>
                <label class='sr-only'>" . _x( 'Search for:', 'label' ) . "</label>
                <div class='input-group'>
                    <input type='search' value='" . get_search_query() . "' name='s' class='search-field form-control' placeholder='" . esc_attr_x( 'Search &hellip;',
			'placeholder' ) . "'>
                    $input
                    <span class='input-group-btn'><button type='submit' class='search-submit btn btn-primary'><span class='glyphicon glyphicon-search'></span></button></span>
                </div>
            </form>";

	return $form;
}
add_filter( 'get_search_form', 'my_search_form' );


// WP_PageNavi css
function theme_pagination_current_class( $class_name ) {
	return 'h4';
}
add_filter( 'wp_pagenavi_class_current', 'theme_pagination_current_class' );


