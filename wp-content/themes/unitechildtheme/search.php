<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package unite
 */

get_header(); ?>
<?php $is_movies = in_array(get_post_type(), ['movies']); ?>

<section id="primary"
         class="content-area col-sm-12 <?php echo $is_movies ? 'col-md-12' : 'col-md-8'; ?> <?php echo esc_attr( unite_get_option( 'site_layout' ) ); ?>">
    <main id="main" class="site-main" role="main">

		<?php if ( ! empty( $_GET['s'] ) && ! empty( $_GET['tax'] ) && $_GET['tax'] == 'actors' ) : ?>

			<?php echo do_shortcode( '[actors s=' . $_GET['s'] . ']' ); ?>

		<?php elseif ( have_posts() ) : ?>

            <div class="row">
                <div class="<?php echo $is_movies ? 'col-md-8' : 'col-md-12'; ?>">
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
                </div>
                <?php if ($is_movies) : ?>
                    <div class="col-md-4">
                        <span class="search-form-right"><?php get_search_form() ?></span>
                    </div>
                <?php endif; ?>
            </div>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

            <?php $end = in_array(get_post_type(), ['movies', 'products']) ?  '-' . get_post_type() : '' ?>
            <?php get_template_part( 'content' . $end, 'search' ); ?>

			<?php endwhile; ?>

			<?php unite_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php $is_movies ?: get_sidebar(); ?>
<?php get_footer(); ?>
