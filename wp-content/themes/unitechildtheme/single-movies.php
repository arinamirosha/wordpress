<?php get_header(); ?>

    <div class="row">

        <div class="col-md-3">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large' );
			} else {
				echo '<img src="' . get_stylesheet_directory_uri() . '/images/img-default.jpg" />';
			}
			?>
        </div>

        <div class="col-md-7">

            <div class='h1'><?php the_title() ?></div>
            <div class='h6'><?php the_content() ?></div>
            <div class="h3"><?php _e( 'Price:', 'unitechild' ) ?><?php the_field( "price" ) ?></div>
            <div class="h3"><?php _e( 'Release date:', 'unitechild' ) ?><?php the_field( "release_date" ) ?></div>

            <div class="taxes-box">
				<?php
                $id         = get_the_ID();
                $taxonomies = array(
                    'actors'    => 'Actors:',
                    'year'      => 'Year:',
                    'countries' => 'Countries:',
                    'genres'    => 'Genres:'
                );
				foreach ( $taxonomies as $key => $val ) {
					the_terms( $id, $key, '<div class="taxes-str"><span class="h3">' . __( $val,
							'unitechild' ) . ' </span><span class="h5">', ', ', "</span></div>" );
				}
                ?>
            </div>

        </div>

    </div>

<?php get_footer(); ?>