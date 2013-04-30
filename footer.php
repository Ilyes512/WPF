<?php
/**
 * The footer for the WPF theme.
 * 
 * Contains footer content and the closing of the #page div element.
 *
 * @todo Remove the javascript beneath wp_footer();
 */
wpf_dev( 'footer.php' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php wpf_print_footer_sidebar(); ?>
		<section>
			<div class="site-info">
				<p><?php printf( __( '&copy; %s Crafted on WPF<br><a href="https://github.com/MekZii/WPF" rel="nofollow" title="WPD - Wordpress Foundation">Visit the repository on github!</a>', 'wpf' ), date( 'Y' ) ); ?></p>
			</div> <!-- .site-info -->
			<div class="footer-menu">
				<?php wp_nav_menu(array(
					'theme_location' => 'utility',
					'container'      => false,
					'menu_class'     => 'inline-list right',
					'fallback_cb'    => false,
				) ); ?>
			</div> <!-- .footer-menu -->
		</section>
	</footer> <!-- #colophon -->

</div> <!-- #page -->

<?php wp_footer(); ?>

<script>
	jQuery(document).foundation();
</script>
</body>
</html>
<?php wpf_dev( 'end footer.php' );?>