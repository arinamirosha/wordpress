<?php

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