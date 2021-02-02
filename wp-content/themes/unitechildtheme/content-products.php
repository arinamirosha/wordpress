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
            <?php // highlight search terms in title
            $title = get_the_title();
            if (is_search()) {
                $keys  = explode(" ", $s);
                $title = preg_replace('/('.implode('|',$keys).')/iu','<span class="search-terms">\0</span>',$title);
            }
            ?>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo $title; ?></a></h2>

            <?php if (get_post_meta( get_the_ID(), 'price', true )) : ?>
                <div class="price">
                    Цена: <?php echo round( get_post_meta( get_the_ID(), 'price', true ) ); ?> руб.
                </div>
            <?php endif; ?>

            <?php if ( is_search() ) : // Only display Excerpts for Search ?>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->
            <?php else : ?>
                <div class="entry-content">

                    <?php if(unite_get_option('blog_settings') == 1 || !unite_get_option('blog_settings')) : ?>
                        <?php the_content(); ?>
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
            <?php endif; ?>
        </div>
    </div>

	<hr />
</article><!-- #post-## -->
