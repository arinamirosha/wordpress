<?php get_header(); ?>

<section id="primary" class="content-area col-md-12 <?php echo esc_attr( unite_get_option( 'site_layout' ) ); ?>">
    <main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

            <span class="search-form-right"><?php get_search_form(); ?></span>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content-movies', get_post_format() ); ?>

			<?php endwhile; ?>

			<?php wp_pagenavi(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

    </main>
</section>

<?php get_footer(); ?>
