<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package unite
 */

get_header(); ?>

<section id="primary" class="content-area col-sm-12 col-md-8 <?php echo esc_attr(unite_get_option( 'site_layout' )); ?>">
    <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <?php
                echo '<h1 class="page-title">' . post_type_archive_title('', false) . '</h1>';
                the_archive_description( '<div class="taxonomy-description">', '</div>' );

                if (current_user_can('level_10')) {
                    global $wpdb;
                    $x = $wpdb->get_var($wpdb->prepare(
                        "SELECT sum(meta_value) FROM $wpdb->posts wp_p LEFT JOIN $wpdb->postmeta wp_pm ON wp_p.id = wp_pm.post_id "
                        . "WHERE post_type = 'products' AND post_status = 'publish' AND meta_key='price'"
                    ));
                    echo '<p class="h3">Общая стоимость товаров: '. round($x) . ' руб.</p>';
                }

                ?>
            </header><!-- .page-header -->

            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>

                <?php
                /* Include the Post-Format-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                 */
                get_template_part( 'content-products', get_post_format() );
                ?>

            <?php endwhile; ?>

            <?php unite_paging_nav(); ?>

        <?php else : ?>

            <?php get_template_part( 'content', 'none' ); ?>

        <?php endif; ?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
