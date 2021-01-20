<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header page-header">
        <?php
        if ( unite_get_option( 'single_post_image', 1 ) == 1 ) :
            the_post_thumbnail( 'unite-featured', array( 'class' => 'thumbnail' ));
        endif;
        ?>
        <h1 class="entry-title "><?php the_title(); ?></h1>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php the_content(); ?>

        <div class="price">
            Цена: <?php echo get_post_meta( get_the_ID(), 'price', true ); ?> руб.
        </div>

        <?php
        $productname = get_the_title();
        $price = get_post_meta( get_the_ID(), 'price', true );
        echo print_wp_cart_button_for_product($productname, $price);
        ?>

        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'unitechild' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->

</article><!-- #post-## -->
