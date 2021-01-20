<?php

function actors_func( $atts ) {

	$params   = shortcode_atts( array( 'per_page' => 100, 's' => '', ), $atts );
	$per_page = $params['per_page'];
	$s        = $params['s'];
	$result   = '';
	$paged    = ! empty( $_GET['p_num'] ) ? $_GET['p_num'] : 1;

	$atts     = $s ? [ 'name__like' => $s ] : [];
	$n_series = count( get_terms( 'actors', $atts ) );

	$atts   = array_merge( $atts, array( 'offset' => $per_page * ( $paged - 1 ), 'number' => $per_page ) );
	$actors = get_terms( 'actors', $atts );

	if ( count( $actors ) == 0 ) {
		return get_template_part( 'content', 'none' );
	} elseif ( $s ) {
		$result .= '<header class="page-header"><h1 class="page-title">' . __( 'Search Results for:', 'unitechild' ) .
		           ' <span>' . get_search_query() . '</span></h1></header>';
	}

	foreach ( $actors as $actor ) {
		$result .= "<div><a class='h4' href='" . get_term_link( $actor, 'actors' ) . "'>$actor->name </a>";
		$movies = get_posts( array(
			'post_type' => 'movies',
			'tax_query' => array(
				array(
					'taxonomy' => 'actors',
					'field'    => 'term_id',
					'terms'    => $actor->term_id,
				)
			)
		) );
		foreach ( $movies as $key => $movie ) {
			$result .= "<a href='" . get_post_permalink( $movie->ID ) . "'>$movie->post_title</a>";
			end( $movies );
			if ( $key !== key( $movies ) ) {
				$result .= ', ';
			}
		}
		$result .= '</div>';
	}

	global $wp;
	$result .= '<br>' . paginate_links( array(
			'base'    => home_url( $wp->request ) . '%_%',
			'format'  => '?p_num=%#%',
			'total'   => ceil( $n_series / $per_page ),
			'current' => $paged,
		) );

	return $result;
}

add_shortcode( 'actors', 'actors_func' );