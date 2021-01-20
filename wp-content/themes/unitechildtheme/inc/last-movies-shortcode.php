<?php

function last_movies_func( $atts ) {
	$params = shortcode_atts( array( 'count' => 5 ), $atts );

	$movies = get_posts( array(
		'numberposts' => $params['count'],
		'post_type'   => 'movies',
	) );

	$result = '<div class="row">';

	foreach ( $movies as $movie ) {
		setup_postdata( $movie );

		$result .= "<div class='col-md-6 film'><div class='row'><div class='col-md-4'><a href='" . get_permalink( $movie ) . "' title='$movie->post_title'>";

		if ( has_post_thumbnail( $movie->ID ) ) {
			$result .= get_the_post_thumbnail( $movie->ID, 'thumbnail' );
		} else {
			$result .= '<img src="' . get_stylesheet_directory_uri() . '/images/img-default.jpg" />';
		}

		$result .= "</a></div><div class='col-md-8'><a class='h3' href='" . get_permalink( $movie ) . "'>$movie->post_title</a><br>" .
		           get_string_terms( $movie->ID, 'actors', __( 'Actors:', 'unitechild' ) ) .
		           get_string_terms( $movie->ID, 'year', __( 'Year:', 'unitechild' ) ) .
		           get_string_terms( $movie->ID, 'countries', __( 'Countries:', 'unitechild' ) ) .
		           get_string_terms( $movie->ID, 'genres', __( 'Genres:', 'unitechild' ) ) .
		           '</div></div></div>';
	}
	wp_reset_postdata();

	$result .= '</div>';

	return $result;
}

add_shortcode( 'lastmovies', 'last_movies_func' );

function get_string_terms( $post_id, $tax, $before ) {
	$str       = "<span class='text-trancate'>$before ";
	$cur_terms = get_the_terms( $post_id, $tax );
	if ( is_array( $cur_terms ) ) {
		foreach ( $cur_terms as $key => $cur_term ) {
			$str .= "<a href='" . get_term_link( $cur_term->term_id, $cur_term->taxonomy ) . "'>$cur_term->name</a>";
			end( $cur_terms );
			if ( $key !== key( $cur_terms ) ) {
				$str .= ', ';
			}
		}
	}
	$str .= '</span><br>';

	return $str;
}