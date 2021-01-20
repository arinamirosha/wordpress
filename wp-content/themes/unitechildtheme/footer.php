<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package unite
 */
?>
            </div><!-- row -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info container">
			<div class="row">
				<nav role="navigation" class="col-md-6">
					<?php unite_footer_links(); ?>
				</nav>

                <?php if (current_user_can('level_10')) {
                    echo get_num_queries() . ' queries in ';
                    echo timer_stop() . ' seconds';
                } ?>

				<div class="copyright col-md-6">
					<?php do_action( 'unite_credits' ); ?>
					<?php echo unite_get_option( 'custom_footer_text' ); ?>
					<?php do_action( 'unite_footer' ); ?>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>