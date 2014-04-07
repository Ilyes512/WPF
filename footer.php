<?php
/**
 * The footer for the WPF theme.
 *
 * Contains footer content and the closing of the #page div element.
 */

// Get the global $wpf_settings;
global $wpf_settings;

wpf_dev( 'footer.php' ); ?>
	<p id="back-top">
		<a href="#top"><i class="icon-chevron-up"></i><span class="hide-for-small-only"><?php _e( 'Back to top', 'wpf'); ?></span></a>
	</p>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<hr>
		<?php wpf_print_footer_sidebar(); ?>
		<section class="site-footer-meta">
			<div class="footer-menu">
				<?php $wpf_footer_menu = wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'fallback_cb'    => 'wpf_nav_menu_fallback',
					'items_wrap'     => '<ul>%3$s</ul>',
					'depth'          => '-1',
					'echo'           => false,
				) ); ?>
				<?php echo ( empty( $wpf_footer_menu ) ) ? '&nbsp;' : $wpf_footer_menu; ?>
			</div><!-- .footer-menu -->
			<div class="site-info">
				<?php printf( __( $GLOBALS['wpf_settings']['footer_site_info'], 'wpf' ), date( 'Y' ) ); ?>
			</div><!-- .site-info -->
		</section><!-- .site-footer-meta -->
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
<?php wpf_dev( 'end footer.php' ); ?>