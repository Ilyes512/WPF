<?php

/**************************************************************************
	Table of contents
 **************************************************************************
 *    >SETTINGS
 *    >WPF SETUP
 *    >CLEANING
 *    >JAVASCRIPT
 *    >FOUNDATION NAVIGATION + BREADCRUMB
 *    >SIDEBAR
 *    >MISC
 *    >LOGIN
 *    >ADMIN
 *    >TPL-CONTACT
 */

/**************************************************************************
 *    >SETTINGS
 **************************************************************************/

/**
 * Get the WPF settings and store them in a global variable for reusage.
 *
 *
 */

global $wpf_settings;
$wpf_settings = get_option( 'wpf_settings' );

/**
 * The current WPF version (used to add version number to WPF css/js files).
 *
 * Required: see the function wpf_scripts_and_styles()
 */
define( 'WPF_VERSION', '0.2.7' );

/**
 * The value WPF_DEV_MODE is defined by wether WP_DEBUG is set to true or false
 * (or not at all == false).
 *
 * Optional: see the function wpf_dev().
 */
define( 'WPF_DEV_MODE', ( defined( 'WP_DEBUG' ) ) ? WP_DEBUG : false );

/**
 * You can change the "Home" url that is used for the wpf_breadcrumb(). Default "home url" is set to home_url( '/' ).
 *
 * Optional: you can use "define( 'WPF_BREADCRUMB_HOME_URL' , '#url');" in your child theme.
 *
 * @todo: Clean this up
 */
//if( ! defined( 'WPF_BREADCRUMB_HOME_URL' ) ) define( 'WPF_BREADCRUMB_HOME_URL' , '#url' );

/**************************************************************************
 *    >WPF SETUP
 **************************************************************************/

/**
 * Sets up the content width value based on the theme's design.
 *
 *
 */
if ( ! isset( $content_width ) )
	$content_width = 637;

add_action( 'after_setup_theme', 'wpf_setup' );
/**
 * Setting up WPF theme.
 *
 * @todo add editor_styles and post_formats
 */
if ( ! function_exists( 'wpf_setup' ) ) {
 function wpf_setup()
	{
		global $wpf_settings;

		/*
		 * Makes wpf available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 */
		load_theme_textdomain( 'wpf', get_template_directory() . '/languages' );

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, and column width.
		 *
		 * @todo add editor_styles
		 */
		/*
		add_editor_style( 'css/editor-style.css' );
		*/
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * This theme does not support all available post formats YET!
		 * See http://codex.wordpress.org/Post_Formats
		 *
		 * Structured post formats:
		 * @link http://core.trac.wordpress.org/ticket/23347
		 */
		/*
		add_theme_support( 'structured-post-formats', array(
			'link', 'video'
		) );
		add_theme_support( 'post-formats', array(
			'aside', 'audio', 'chat', 'gallery', 'image', 'quote', 'status'
		) );
		*/

		/*
		 * Custom callback to make it easier for our fixed navbar to coexist with
		 * the WordPress toolbar. See `.wp-toolbar` in style.css.
		 *
		 * @see WP_Admin_Bar::initialize()
		 */
		add_theme_support(
			'admin-bar',
			array('callback' => '__return_false')
		);

		/*
		 * This theme uses a custom image size for featured images, displayed on
		 * "standard" posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 637, 300, true );

		/*
		 * Enable html5 theme support
		 *
		 * @todo Remove $html5_args when both 'search-form' and 'comment-list' are not commented
		 */
		$html5_args = array(
			//'search-form', // @todo Check what enabling html5 does to search-form
			'comment-form'
			//'comment-list', // @todo Check what enabling html5 does to search-form
		);
		add_theme_support( 'html5', $html5_args );

		// This theme uses wp_nav_menu() on two locations.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Navigation', 'wpf' ),
				'footer'  => __( 'Footer Navigation', 'wpf' ),
			)
		);

		// Clean up gallery output in wp
		add_filter( 'use_default_gallery_style', '__return_false' );

		// This theme cleans up some of wordpress's mess.
		add_action( 'init', 'wpf_head_cleanup' );

		// Clean up comment styles in the head
		add_action( 'widgets_init', 'wpf_remove_recent_comments_style' );

		// Fix caption output to be (more) semantic.
		add_filter( 'img_caption_shortcode', 'wpf_caption_shortcode', 10, 3 );

		// Remove the wp logo from the wordpress admin bar
		add_action( 'admin_bar_menu', function ( $wp_admin_bar ) { $wp_admin_bar->remove_node( 'wp-logo' ); }, 999 );

		// Include theme files
		wpf_include_theme_files();

		/*
		 * Check if the WPF theme options have been created. If not, then use default
		 * settings.
		 *
		 */
		if ( false === $wpf_settings ) {
			// Get the default value's
			$wpf_settings = wpf_get_default_options();
			// Update the wpf_settings in the database
			update_option( 'wpf_settings', $wpf_settings );
		} elseif ( $wpf_settings['wpf_version'] != WPF_VERSION ) {
			// Add possible new field's to the current $wpf_settings using the default value
			$wpf_settings = wp_parse_args( $wpf_settings, wpf_get_default_options() );
			// Overwrite the wpf_version
			$wpf_settings['wpf_version'] = WPF_VERSION;
			// Update the wpf_settings in the database
			update_option( 'wpf_settings', $wpf_settings );
		}

	} // end wpf_setup()
}

/**
 * Include all the different theme files
 *
 *
 */
if ( ! function_exists( 'wpf_include_theme_files' ) ) {
	function wpf_include_theme_files()
	{
		// CLEANING
		require( get_stylesheet_directory() . '/inc/wpf/cleaning.inc.php' );

		// JAVASCRIPT
		require( get_stylesheet_directory() . '/inc/wpf/javascript.inc.php' );

		// FOUNDATION NAVIGATION + BREADCRUMB
		require( get_stylesheet_directory() . '/inc/wpf/navigation.inc.php' );

		// SIDEBAR
		require( get_stylesheet_directory() . '/inc/wpf/sidebar.inc.php' );

		// MISC
		require( get_stylesheet_directory() . '/inc/wpf/misc.inc.php' );

		// LOGIN
		require( get_stylesheet_directory() . '/inc/wpf/login.inc.php' );

		// WP-ADMIN
		require( get_stylesheet_directory() . '/inc/wpf/admin.inc.php' );

		// MAIL
		add_action(
			'template_redirect',
			function() {
				if ( is_page_template( 'tpl-contact.php' ) )
					require( get_stylesheet_directory() . '/inc/wpf/mail.inc.php' );
			}
		);

	} // end wpf_include_theme_files()
}
