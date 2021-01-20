<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="row">
        <div class="col-md-4">
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'unite-featured', array( 'class' => 'thumbnail' )); ?></a>
        </div>
        <div class="col-md-7">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <?php if (get_post_meta( get_the_ID(), 'price', true )) : ?>
                <div class="price">
                    Цена: <?php echo get_post_meta( get_the_ID(), 'price', true ); ?> руб.
                </div>
            <?php endif; ?>
            <div class="entry-content">

                <?php if(unite_get_option('blog_settings') == 1 || !unite_get_option('blog_settings')) : ?>
                    <?php the_content( __( 'Continue reading <i class="fa fa-chevron-right"></i>', 'unite' ) ); ?>
                <?php elseif (unite_get_option('blog_settings') == 2) :?>
                    <?php the_excerpt(); ?>
                <?php endif; ?>

                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'unite' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div><!-- .entry-content -->
        </div>
    </div>

	<hr />
</article><!-- #post-## -->
