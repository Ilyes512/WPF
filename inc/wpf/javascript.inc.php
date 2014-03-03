<?php
/**************************************************************************
 *    >JAVASCRIPT
 **************************************************************************/


add_action( 'wp_enqueue_scripts', 'wpf_scripts_and_styles' );
/**
 * Enqueues scripts and styles for front end.
 *
 *
 */
if ( ! function_exists( 'wpf_scripts_and_styles' ) ) {
	function wpf_scripts_and_styles() {
		$protocol = is_ssl() ? 'https' : 'http';
		/*
		 * Adds JavaScript to pages with the comment form to support sites with
		 * threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		/*
		 * Deregister built-in jQuery and register it again with Google's jQuery.
		 *
		 * @todo Find a better/safer way of doing this.
		 */
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', $protocol . '://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js', false, null, true );

		// register modernizr jsscript in the header
		wp_register_script( 'wpf-modernizr', get_template_directory_uri() . '/js/vendor/modernizr.min.js', array(), '2.7.1', false );

		// register Foundation jsscript in the footer
		wp_register_script( 'wpf-js', get_stylesheet_directory_uri() . '/js/foundation.min.js', array( 'jquery', 'wpf-modernizr' ), WPF_VERSION, true );

		// Add style.css
		wp_enqueue_style( 'wpf-stylesheet', get_stylesheet_uri(), array(), WPF_VERSION );

		// Add Google Fonts
		wp_deregister_style( 'open-sans' );
		$query_args = array( 'family' => 'Open+Sans:400italic,700italic,400,700' );
		wp_enqueue_style( 'open-sans', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );

		// Load a adjusted admin-bar.min.css file
		wp_deregister_style( 'admin-bar' );
		wp_register_style( 'admin-bar', get_template_directory_uri() . '/css/admin-bar.min.css', array( 'open-sans', 'dashicons' ) );

		// Add wpf-js script and all it's dependencies
		wp_enqueue_script( 'wpf-js' );
	} // end wpf_scripts_and_styles()
}

add_action( 'wp_head', 'wpf_placeholder' );
/**
 * Add placeholder.js for Internet Explorer 8 and later
 */
if ( ! function_exists( 'wpf_placeholder' ) ) {
	function wpf_placeholder() {
		echo '<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.js"></script><![endif]-->' . "\n";
	}
}

add_action( 'wp_footer', 'wpf_foundation_jquery', 999 );
/**
 * Initiate the necessary jQuery-scripts used by Foundation
 *
 * Initiate the necessary jQuery-scripts used by Foundation after the scripts
 * are enqueued in wpf_scripts_and_styles(). The wp_enqueue_scripts hook is
 * loaded with priority 20 within the wp_footer function.
 */
if ( ! function_exists( 'wpf_foundation_jquery' ) ) {
	function wpf_foundation_jquery() {
		echo "<script>\njQuery(document).foundation();\n</script>";
	} // end wpf_foundation_jquery()
}