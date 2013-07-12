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
 *    >WP-LOGIN
 *    >WP-ADMIN
 *    >TPL-CONTACT
 */


/**************************************************************************
 *    >SETTINGS
 **************************************************************************/


/**
 * The current WPF version (used to add version number to WPF css/js files).
 *
 * Required: see the function wpf_scripts_and_styles()
 */
define('WPF_VERSION', '0.2.7');

/**
 * The value WPF_DEV_MODE is defined by wether WP_DEBUG is set to true or false
 * (or not at all == false).
 *
 * Optional: see the function wpf_dev().
 */
define('WPF_DEV_MODE', ( defined( 'WP_DEBUG' ) ) ? WP_DEBUG : false);

/**
 * You can change the "Home" url that is used for the wpf_breadcrumb(). Default "home url" is set to home_url( '/' ).
 *
 * Optional: you can use "define( 'WPF_BREADCRUMB_HOME_URL' , '#url');" in your child theme.
 */
//if( ! defined( 'WPF_BREADCRUMB_HOME_URL' ) ) define( 'WPF_BREADCRUMB_HOME_URL' , '#url');


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
	function wpf_setup() {
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
		add_theme_support( 'admin-bar', array(
			'callback' => '__return_false'
		) );

		// This theme uses wp_nav_menu() on two locations.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'wpf' ),
			'footer'  => __( 'Footer Navigation', 'wpf' ),
		) );

		/*
		 * This theme uses a custom image size for featured images, displayed on
		 * "standard" posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 637, 300, true );

		// Clean up gallery output in wp
		add_filter( 'use_default_gallery_style', '__return_false' );

		// This theme cleans up some of wordpress's mess.
		add_action( 'init', 'wpf_head_cleanup' );

		// Clean up comment styles in the head
		add_action( 'widgets_init', 'wpf_remove_recent_comments_style');

		// Fix caption output to be (more) semantic.
		add_filter( 'img_caption_shortcode', 'wpf_caption_shortcode', 10, 3 );

		// Remove the wp logo from the wordpress admin bar
		add_action( 'admin_bar_menu', function ( $wp_admin_bar ) { $wp_admin_bar->remove_node( 'wp-logo' ); }, 999 );
	} // end wpf_setup()
}


/**************************************************************************
 *    >CLEANING
 **************************************************************************/


require_once( 'inc/wpf/cleaning.inc.php' );


/**************************************************************************
 *    >JAVASCRIPT
 **************************************************************************/


require_once( 'inc/wpf/javascript.inc.php' );


/**************************************************************************
 *		>FOUNDATION NAVIGATION + BREADCRUMB
 **************************************************************************/


require_once( 'inc/wpf/navigation.inc.php' );


/**************************************************************************
 *		>SIDEBAR
 **************************************************************************/


require_once( 'inc/wpf/sidebar.inc.php' );


/**************************************************************************
 *		>MISC
 **************************************************************************/


require_once( 'inc/wpf/misc.inc.php' );


/**************************************************************************
 *		>WP-LOGIN
 **************************************************************************/


require_once( 'inc/wpf/wp-login.inc.php' );


/**************************************************************************
 *		>WP-ADMIN
 **************************************************************************/


require_once( 'inc/wpf/wp-admin.inc.php' );


/**************************************************************************
 *    >TPL-CONTACT
 **************************************************************************/

add_action( 'template_redirect', 'wpf_contact' );
/**
 * Include the logic/settings for tpl-contact.php.
 *
 * Includes the logic/settings for tpl-contact.php. It will load the child's
 * mail.inc.php first if that is available.
 */
if ( ! function_exists( 'wpf_contact' ) ) {
	function wpf_contact() {
		if ( is_page_template( 'tpl-contact.php' ) ) {
			if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/inc/wpf/mail.inc.php' ) ) {
				require_once( get_stylesheet_directory() . '/inc/wpf/mail.inc.php' );
			} else {
				require_once( get_template_directory() . '/inc/wpf/mail.inc.php');
			}
		}
	} // end wpf_contact()
}
