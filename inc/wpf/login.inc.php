<?php
/**************************************************************************
 *    >WP-LOGIN
 **************************************************************************/


add_action( 'login_head', 'wpf_login_head' );
/**
 * Add an extra stylesheet to the login head.
 *
 * @link http://codex.wordpress.org/Customizing_the_Login_Form
 */
if ( ! function_exists( 'wpf_login_head' ) ) {
	function wpf_login_head() { ?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/img/logo-wpf.png");
				background-size: 289px 134px;
				width: 289px;
				height: 134px;
				margin: 0 auto;
			}
		</style>
	<?php } // end wpf_login_head()
}


add_filter( 'login_headerurl', 'wpf_login_headerurl' );
/**
 * Change the headerurl that is used for the form on wp-login.php
 *
 *
 */
if ( ! function_exists( 'wpf_login_headerurl' ) ) {
	function wpf_login_headerurl() {
		return esc_url ( home_url( '/' ) );
	} // end wpf_login_headerurl()
}

add_filter( 'login_headertitle', 'wpf_login_headertitle' );
/**
 * Change the headertitle that is used for the form on wp-login.php
 *
 *
 */
if ( ! function_exists( 'wpf_login_headertitle' ) ) {
	function wpf_login_headertitle() {
		return esc_attr ( __( 'Go back to the homepage' , 'wpf') );
	} // end wpf_login_headertitle()
}