<?php
/**************************************************************************
 *    >WP-ADMIN
 **************************************************************************/


add_action( 'admin_head', 'wpf_admin_head' );
/**
 * Add favicon the the head in the WP_admin area
 *
 *
 */
if ( ! function_exists( 'wpf_admin_head' ) ) {
	function wpf_admin_head() {
		echo '<link rel="shortcut icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/favicon.png">';
	} // end wpf_admin_head()
}