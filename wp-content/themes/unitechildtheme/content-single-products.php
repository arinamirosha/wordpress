<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header page-header">
        <div>
            <?php
            $tn = do_shortcode('[gwts_gwl_gallery_slider id="' . get_the_ID() . '"]');
            if ($tn) {
                echo $tn;
            } elseif (unite_get_option('single_post_image', 1) == 1) {
                the_post_thumbnail('unite-featured', array('class' => 'thumbnail'));
            }
            ?>
        </div>
        <h1 class="entry-title "><?php the_title(); ?></h1>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php the_content(); ?>

        Поделиться: <input type="text" value="<?php echo getTinyUrl(get_permalink(get_the_ID())) ?>" id="tinyUrl">
        <button onclick="myFunction()">Copy</button>

        <?php if (get_post_meta( get_the_ID(), 'price', true )) : ?>
            <div class="price">
                Цена: <?php echo get_post_meta( get_the_ID(), 'price', true ); ?> руб.
            </div>
            <?php
//            $productname = get_the_title();
//            $price = get_post_meta( get_the_ID(), 'price', true );
//            echo print_wp_cart_button_for_product($productname, $price);
            echo do_shortcode('[shop_cart_add_button]');
            ?>
        <?php endif; ?>

        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'unitechild' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->

</article><!-- #post-## -->
