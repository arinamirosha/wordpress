<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package unite
 */

get_header(); ?>

<section id="primary"
         class="content-area col-sm-12 col-md-8 <?php echo esc_attr( unite_get_option( 'site_layout' ) ); ?>">
    <main id="main" class="site-main" role="main">

		<?php if ( ! empty( $_GET['s'] ) && ! empty( $_GET['tax'] ) && $_GET['tax'] == 'actors' ) : ?>

			<?php echo do_shortcode( '[actors s=' . $_GET['s'] . ']' ); ?>

		<?php elseif ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title">
					<?php if ( ! empty( $_GET['s'] ) ) : ?>
						<?php printf( __( 'Search Results for: %s', 'unitechild' ),
							'<span>' . get_search_query() . '</span>' ); ?>
					<?php else : ?>
						<?php _e( 'Empty request', 'unitechild' ); ?>
					<?php endif; ?>
                </h1>
            </header>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php unite_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
