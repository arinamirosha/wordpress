<?php

function catposts_func( $atts ) {

    $terms  = $atts['terms'];
    $result = '';
    global $post;

    $args = array(
        'post_type' => 'products',
        'tax_query' => array(
            array(
                'taxonomy' => 'categories',
                'field'    => 'slug',
                'terms'    => $terms
            )
        ));
    $myposts = get_posts( $args );

    foreach( $myposts as $post ) {
        setup_postdata($post);
        $result .= '<a href="' . get_the_permalink() . '">'. get_the_title() . '</a><br />';
    }

    return $result;
}

add_shortcode( 'catposts', 'catposts_func' );