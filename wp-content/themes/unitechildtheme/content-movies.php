<?php the_date('','<div class="col-md-12"><p class="h3">','</p><hr /></div>'); ?>

<div class="col-md-4 film">
    <div class="row">
        <div class="col-md-4">
            <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'thumbnail' );
				} else {
					echo '<img src="' . get_stylesheet_directory_uri() . '/images/img-default.jpg" />';
				}
				?>
            </a>
        </div>
        <div class="col-md-8">
            <a class="h5" href="<?php the_permalink() ?>"><?php the_title() ?></a><br>
			<?php
			$id = get_the_ID();
			the_terms( $id, 'actors', "<span class='text-trancate'>" . __( 'Actors:', 'unitechild' ) . ' ', ', ', '</span><br>' );
			the_terms( $id, 'year', "<span class='text-trancate'>" . __( 'Year:', 'unitechild' ) . ' ', ', ', "</span><br>" );
			the_terms( $id, 'countries', "<span class='text-trancate'>" . __( 'Countries:', 'unitechild' ) . ' ', ', ', "</span><br>" );
			the_terms( $id, 'genres', "<span class='text-trancate'>" . __( 'Genres:', 'unitechild' ) . ' ', ', ', "</span>" );
			?>
        </div>
    </div>
</div>