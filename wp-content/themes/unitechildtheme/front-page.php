<?php

    if ( get_option( 'show_on_front' ) == 'posts' ) {
        get_template_part( 'index' );
    } elseif ( 'page' == get_option( 'show_on_front' ) ) {

 get_header(); ?>

	<div id="primary" class="content-area col-sm-12 col-md-12">
		<main id="main" class="site-main" role="main">

            <div class="welcome-page-main">
                <h1 class="archive-title">Welcome, lover of fine furniture.</h1>
                <p>Passionate about style and grace? Looking for furnishings that
                    fit your life as well as they fit your room? You've come to the right place.
                    <b>Distinct Furnishings</b> offers the best in fine furniture, for simply
                    any budget.</p>
            </div>

            <div class="row">
                <?php // display categories in columns
                $cats = explode("<br />",wp_list_categories('title_li=&echo=0&depth=1&style=none&taxonomy=categories'));
                $cat_n = count($cats) - 1;
                for ( $i = 0; $i < $cat_n; $i++ ) :
                    if ( $i < $cat_n / 2 ) :
                        $cat_left = $cat_left . '<li>' . $cats[$i] . '</li>';
                    elseif ( $i >= $cat_n / 2 ):
                        $cat_right = $cat_right . '<li>' . $cats[$i] . '</li>';
                    endif;
                endfor; ?>
                <ul class="left"><?php echo $cat_left;?></ul>
                <ul class="right"><?php echo $cat_right;?></ul>
            </div>

            <div class="floating-column">
                <h2>Luxurious Sofas</h2>
                <?php echo do_shortcode('[catposts terms="sofas"]'); ?>
            </div>

            <div class="floating-column">
                <h2>Award-Winning Chairs</h2>
                <?php echo do_shortcode('[catposts terms="chairs"]'); ?>
            </div>

            <div class="floating-column">
                <h2>Super-Puper Tables</h2>
                <?php echo do_shortcode('[catposts terms="tables"]'); ?>
            </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	get_footer();
}
?>