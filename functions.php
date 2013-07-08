<?php

/**************************************************************************
		Table of contents
 **************************************************************************
 *		>SETTINGS
 *      >INCLUDING FILES
 *		>WPF SETUP
 *		>FOUNDATION NAVIGATION + BREADCRUMB
 *		>SIDEBAR
 *		>WP-LOGIN
 *		>ADMIN
 *		>MISC
 */


/**************************************************************************
 *		>SETTINGS
 **************************************************************************/


/**
 * The current WPF version (used to add version number to WPF css/js files).
 *
 * Required: see the function wpf_scripts_and_styles()
 */
define('WPF_VERSION', '0.2.6');

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
 *		>INCLUDING FILES
 **************************************************************************/

add_action( 'template_redirect', 'wpf_contact' );
/**
 * Contains the logic for tpl-contact.php
 *
 *
 */
if ( ! function_exists( 'wpf_contact' ) ) {
	function wpf_contact() {
		if ( is_page_template( 'tpl-contact.php' ) ) {
			if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/inc/mail.inc.php' ) ) {
				require_once( get_stylesheet_directory() . '/inc/mail.inc.php' );
			} else {
				require_once( get_template_directory() . '/inc/mail.inc.php');
			}
		}
	} // end wpf_contact()
}

/**************************************************************************
 *		>WPF SETUP
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
		add_action( 'wp_head', 'wpf_remove_recent_comments_style', 1 );

		// fix caption output to be (more) semantic.
		add_filter( 'img_caption_shortcode', 'wpf_caption_shortcode', 10, 3 );

		// Remove the wp logo from the wordpress admin bar
		add_action( 'admin_bar_menu', function ( $wp_admin_bar ) { $wp_admin_bar->remove_node( 'wp-logo' ); }, 999 );
	} // end wpf_setup()
}

/**
 * Cleaning up the head
 *
 *
 */
if ( ! function_exists( 'wpf_head_cleanup' ) ) {
	function wpf_head_cleanup() {
		// Remove the links to the extra feeds such as category feeds.
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		// Remove the (EditUri) link to the Really Simple Discovery service endpoint.
		remove_action( 'wp_head', 'rsd_link' );

		// Remove the link to the Windows Live Writer manifest file.
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Remove relational links (previous and next) for the posts adjacent to the current post.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

		// Remove the generator meta information
		remove_action( 'wp_head', 'wp_generator' );

		// Remove the rel shortlink-link
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	} // end head_cleanup()
}

/**
 * Remove injected CSS from recent comments widget
 *
 *
 */
if ( ! function_exists( 'wpf_remove_recent_comments_style' ) ) {
	function wpf_remove_recent_comments_style() {
		global $wp_widget_factory;
		if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
	} //  end wpf_remove_recent_comments_style()
}

add_action( 'wp_enqueue_scripts', 'wpf_scripts_and_styles' );
/**
 * Enqueues scripts and styles for front end.
 *
 * @return void
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
		wp_register_script( 'jquery', $protocol . "://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js", false, null, true );

		// register modernizr jsscript in the header
		wp_register_script( 'wpf-modernizr', get_template_directory_uri() . '/js/vendor/custom.modernizr.js', array(), '2.6.2', false );

		// register Foundation jsscript in the footer
		wp_register_script( 'wpf-js', get_stylesheet_directory_uri() . '/js/foundation.min.js', array( 'jquery', 'wpf-modernizr' ), WPF_VERSION, true );

		// Add style.css
		wp_enqueue_style( 'wpf-stylesheet', get_stylesheet_uri(), array(), WPF_VERSION );

		// Add Google Fonts
		$query_args = array( 'family' => 'Open+Sans:300' );
		wp_enqueue_style( 'open-sans', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );

		// Add wpf-js script and all it's dependencies
		wp_enqueue_script( 'wpf-js' );
	} // end wpf_scripts_and_styles()
}

add_action('wp_enqueue_scripts', 'qmodo_setup', 11);
/**
 * Adding Google Fonts
 *
 * @return void
 */
function qmodo_setup() {
	$protocol = is_ssl() ? 'https' : 'http';
	$query_args = array( 'family' => 'Open+Sans:300' );
	wp_enqueue_style( 'open-sans', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
}

add_action( 'wp_footer', 'wpf_foundation_jquery', 21 );
/**
 * Initiate the necessary jQuery-scripts used by Foundation
 *
 * Initiate the necessary jQuery-scripts used by Foundation after the scripts
 * are enqueued in wpf_scripts_and_styles(). The wp_enqueue_scripts hook is
 * loaded with priority 20 within the wp_footer function.
 */
if ( ! function_exists( 'wpf_foundation_jquery' ) ) {
	function wpf_foundation_jquery() { ?>
		<script>jQuery(document).foundation();</script>
	<?php } // end wpf_foundation_jquery()
}

/**
 * Produce a more semantic output for img-caption's.
 *
 * @todo check if wp 3.6 produce a (more) semantic caption
 */
if ( ! function_exists( 'wpf_caption_shortcode' ) ) {
	function wpf_caption_shortcode( $output, $attr, $content ) {

		// We're not worried about captions in feeds, so just return the output here.
		if ( is_feed() ) return $output;

		// Set up the default arguments.
		$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
		);

		// Merge the defaults with user input and extract.
		$attr = extract( shortcode_atts( $defaults, $attr ) );

		// If the width is less than 1 or there is no caption, return the content
		// wrapped between the [caption]< tags.
		if ( 1 > $width || empty( $caption ) )
			return $content;

		// Open the caption <div> including the needed classes.
		$output = '<figure class="wp-caption ' . esc_attr( $align ) . '">';

		// Allow shortcodes for the content the caption was created for.
		$output .= do_shortcode( $content );

		// Append the caption text.
		$output .= '<figcaption>' . $caption . '</figcaption>';

		// Close the caption </div>.
		$output .= '</figure>';

		// Return the formatted, clean caption.
		return $output;

	} // end wpf_caption_shortcode()
}


/**************************************************************************
 *		>FOUNDATION NAVIGATION + BREADCRUMB
 **************************************************************************/


/**
 * This is an untouched wp_link_pages() as found in wp v3.6-beta3.
 *
 * This function can be deleted as soon as Wordpress 3.6 has been launched. All
 * temp_wp_link_pages() calls will then need to be replaced by wp_link_pages()
 * before deleting this function.
 *
 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/post-template.php
 * @todo Delete temp_wp_link_pages() as soon as Wordpress 3.6 has been launched
 * and replace the calls with wp_link_pages().
 *
 * @param    string|array    $args Optional. Overwrite the defaults.
 * @return    string         Formatted output in HTML
 */
if ( ! function_exists( 'temp_wp_link_pages' ) ) {
	function temp_wp_link_pages( $args = '' ) {
		$defaults = array(
			'before'           => '<p>' . __( 'Pages:' ),
			'after'            => '</p>',
			'link_before'      => '',
			'link_after'       => '',
			'next_or_number'   => 'number',
			'separator'        => ' ',
			'nextpagelink'     => __( 'Next page' ),
			'previouspagelink' => __( 'Previous page' ),
			'pagelink'         => '%',
			'echo'             => 1,
		);

		$r = wp_parse_args( $args, $defaults );
		$r = apply_filters( 'wp_link_pages_args', $r );
		extract( $r, EXTR_SKIP );

		global $page, $numpages, $multipage, $more, $pagenow;

		$output = '';
		if ( $multipage ) {
			if ( 'number' == $next_or_number ) {
				$output .= $before;
				for ( $i = 1; $i <= $numpages; $i++ ) {
					$link = $link_before . str_replace( '%', $i, $pagelink ) . $link_after;
					if ( $i != $page || ! $more && 1 == $page ) {
						$link = _wp_link_page( $i ) . $link . '</a>';
					}
					$link = apply_filters( 'wp_link_pages_link', $link, $i );
					$output .= $separator . $link;
				}
				$output .= $after;
			} elseif ( $more ) {
				$output .= $before;
				$i = $page - 1;
				if ( $i ) {
					$link = _wp_link_page( $i ) . $link_before . $previouspagelink . $link_after . '</a>';
					$link = apply_filters( 'wp_link_pages_link', $link, $i );
					$output .= $separator . $link;
				}
				$i = $page + 1;
				if ( $i <= $numpages ) {
					$link = _wp_link_page( $i ) . $link_before . $nextpagelink . $link_after . '</a>';
					$link = apply_filters( 'wp_link_pages_link', $link, $i );
					$output .= $separator . $link;
				}
				$output .= $after;
			}
		}

		$output = apply_filters( 'wp_link_pages', $output, $args );

		if ( $echo ) {
			echo $output;
		}
		return $output;
	}
}

add_filter( 'wp_link_pages_args', 'wpf_link_pages_args' );
/**
 * Add a new way of displaying wp_link_pages().
 *
 * This filter is called in wp_link_pages() to add the ability to display both
 * the page numbers and next/previous links.
 *
 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/post-template.php
 *
 * @param    array    $args    These are the arguments passed to wp_link_pages().
 * @return   array             The arguments including the (possible) changes to have next/previous links.
 */
if ( ! function_exists( 'wpf_link_pages_args' ) ) {
	function wpf_link_pages_args( $args ) {
		if ( 'next_and_number' == $args['next_or_number'] ) {
			global $page, $numpages, $multipage, $more;
			$args['next_or_number'] = 'number';

			if ( $multipage && $more ) {
				$prev = '';
				$next = '';
				extract( $args );

				$i = $page - 1;
				if ( $i ) {
					$prev .= _wp_link_page( $i ) . $link_before . $previouspagelink . $link_after . '</a>';
					$prev  = apply_filters( 'wp_link_pages_link', $prev, $i );
					$prev  = $separator . $prev;
				}
				$i = $page + 1;
				if ( $i <= $numpages ) {
					$next .= _wp_link_page( $i ) . $link_before . $nextpagelink . $link_after . '</a>';
					$next  = apply_filters( 'wp_link_pages_link', $next, $i );
					$next  = $separator . $next;
				}

				$args['before'] = $args['before'] . $prev;
				$args['after'] = $next . $args['after'];
			}
		}
		return $args;
	} // end wpf_link_pages_args()
}

add_filter( 'wp_link_pages_link', 'wpf_link_pages_link', 10, 2);
/**
 * Adjust the links that wpf_link_pages() outputs.
 *
 * This filter is found in wp_link_pages() and will adjust the pagination the be
 * able to use Foundation's pagination. It will add <li>-tags (including closing
 * tag). The current page will receive <span>-tags (including closing tag and
 * "current" class) if it doesnt contain a link.
 *
 * @param    string    $link           The link that is being displayed or the content for the current page. ie. <a...>Content</a>
 * @param    int       $page_number    This is the pagenumber that get's the $link.
 * @return   string                    The link that is going to be displayed after adding some htmlmarkup.
 */
if ( ! function_exists( 'wpf_link_pages_link' ) ) {
	function wpf_link_pages_link( $link, $page_number ) {
		global $page;

		if ( $page == $page_number ) {
			// Make sure that there is no <a>-htmltag (For search result page)
			if ( ! preg_match( '/<a\s+/i', $link ) )
				return '<li><span class="current">' . $link . '</span></li>';
		}
		// default
		return '<li>' . $link . '</li>';

	} // end wpf_link_pages_link()
}

/**
 * Use Foundation's pagination style for paginate_links().
 *
 *
 */
if ( ! function_exists( 'wpf_paginate_link' ) ) {
	function wpf_paginate_link() {
		global $wp_query;

		$big = 999999999; // This needs to be an unlikely integer

		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links( array(
			'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $wp_query->max_num_pages,
			'end_size'  => 2,
			'mid_size'  => 5,
			'prev_next' => True,
			'prev_text' => __( '&#xf053; Previous', 'wpf' ),
			'next_text' => __( 'Next &#xf054;', 'wpf' ),
			'type'      => 'list'
		) );

		// Display the pagination if more than one page is found
		if ( $paginate_links )
			echo '<div class="pagination-container">' . $paginate_links . '</div>';
	} // end wpf_paginate_link()
}

/**
 * Use Foundation's pagination style for wp_link_pages().
 *
 *
 */
if ( ! function_exists( 'wpf_link_pages' ) ) {
	function wpf_link_pages() {
		switch ( true ) {
			case is_single() || is_page();
				temp_wp_link_pages( array(
					'before'           => '<div class="pagination-container"><ul class="page-numbers">',
					'after'            => '</ul></div>',
					'next_or_number'   => 'next_and_number',
					'nextpagelink'     => __( 'Next page &#xf054;', 'wpf' ),
					'previouspagelink' => __( '&#xf053; Previous page', 'wpf' ),
				) );
				break;
			case is_search();
				temp_wp_link_pages( array(
					'before'           => '<div class="pagination-container"><span class="pagination-title">' . __( 'Pages:', 'wpf' ) . '</span><ul class="page-numbers">',
					'after'            => '</ul></div>',
					'next_or_number'   => 'next_and_number',
					'nextpagelink'     => __( 'Next page &#xf054;', 'wpf' ),
					'previouspagelink' => __( '&#xf053; Previous page', 'wpf' ),
				) );
				break;
		}
	} // end wpf_link_pages()
}

add_filter( 'wp_list_pages', 'wpf_list_pages_active_class' );
/**
 * Use the .active class of ZURB Foundation on wp_list_pages output.
 *
 *
 */
if ( ! function_exists( 'wpf_list_pages_active_class' ) ) {
	function wpf_list_pages_active_class( $input ) {
		$pattern = '/current_page_item/';
		$replace = 'current_page_item active';
		$output = preg_replace( $pattern, $replace, $input );
		return $output;
	} // end wpf_list_pages_active_class()
}

add_filter( 'nav_menu_css_class', 'wpf_active_nav_class', 10, 2 );
/**
 * Add Foundation 'active' class for the wp_nav_menu output.
 *
 *
 */
if ( ! function_exists( 'wpf_active_nav_class' ) ) {
	function wpf_active_nav_class( $classes, $item ) {
		if ( $item->current == 1 || $item->current_item_ancestor == true ) {
			$classes[] = 'active';
		}
		return $classes;
	} // end wpf_active_nav_class()
}

/**
 * Create a custom HTML list of nav menu items.
 *
 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/nav-menu-template.php
 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
if ( ! class_exists( 'wpf_walker' ) ) {
	class wpf_walker extends Walker_Nav_menu {
		function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

			$id_field = $this->db_fields['id'];
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
			}
			return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul class=\"dropdown\">\n";
		}

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$class_names = $value = '';

			$all_classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = ( $args->has_children ) ? 'has-dropdown' : '';
			$classes[] = in_array( 'active', $all_classes ) ? 'active' : '';

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			if ( $depth > 0 ) {
				$output .= $indent . '<li' . $value . $class_names .'>';
			} else {
				$output .= "\n$indent" . '<li class="divider"></li>'."\n$indent".'<li' . $value . $class_names .'>';
			}

			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= ! empty( $item->target     ) ? ' target="' . esc_attr( $item->target     ) . '"' : '';
			$attributes .= ! empty( $item->xfn        ) ? ' rel="'    . esc_attr( $item->xfn        ) . '"' : '';
			$attributes .= ! empty( $item->url        ) ? ' href="'   . esc_attr( $item->url        ) . '"' : '';

			$item_output  = $args->before;
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	} // end class wpf_walker
}

/**
 * This is the fallback function for the wp_nav_menu()'s used by WPF.
 *
 * @param    array    $args    It receives the array $args from wp_nav_menu().
 * @return   string            It retuns the html output that is used as the fallback menu.
 */
if ( ! function_exists( 'wpf_nav_menu_fallback' ) ) {
	function wpf_nav_menu_fallback( $args ) {
		// If the user has no rights to change the menu's: abort
		if ( ! current_user_can( 'edit_theme_options' ) ) return;

		$output = '';

		switch ( $args['theme_location'] ) {
			case 'primary';
				$output  = '<li class="divider"></li>';
				$output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Add a menu', 'wpf' ) . '</a></li>';
				$output .= '<li class="divider"></li>';
				break;
			case 'footer';
				$output = '<li><a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Add a menu', 'wpf' ) . '</a></li>';
				break;
		}

		// Add the menu wrapper
		$output = sprintf( $args['items_wrap'], $args['menu_id'], $args['menu_class'], $output );

		if ( $args['echo'] ) echo $output;

		return $output;
	} // end wpf_nav_menu_fallback()
}

/**
 * Prints the category breadcrumb for a post using foundation's breadcrumb
 *
 * @link: http://foundation.zurb.com/docs/components/breadcrumbs.html
 *
 * @param    string  $add_home    To add the "Home"-link to the breadcrumb use "show-home". Default: 'hide-home'.
 * @param    string  $return      Set to 'print' for printing the breadcrumb or to 'return' for returning the breadcrumb instead. Default: 'print'.
 * @return   string               When $return equals to 'return' then it will return the breadcrumb html.
 *
 * @todo Refactor
 */
if ( ! function_exists( 'wpf_breadcrumb' ) ) {
	function wpf_breadcrumb( $add_home = 'hide-home', $return = 'print' ) {
		$raw_cat     = get_the_category();
		$raw_count   = count( $raw_cat );
		$cat_array   = array();
		$html_result = '';

		if ( $raw_count > 0 ) {
			foreach ( $raw_cat as $cat ) {
				// turning the object into an array
				$cat = get_object_vars( $cat );

				// Parent cat: if there are multiple parents, then the older parent will be overrided!
				// (so the last parent will be shown)
				if ( $cat['parent'] == 0 ) {
					// the parent is the first li-item in the breadcrumb
					$breadcrumb[0] = array(
						'cat_ID' => $cat['cat_ID'],
						'name'   => $cat['name'],
						'slug'   => $cat['slug'],
						'parent' => $cat['parent'],
					);
				} else {
					$cat_array[ $cat['cat_ID'] ] = array(
						'cat_ID' => $cat['cat_ID'],
						'name'   => $cat['name'],
						'slug'   => $cat['slug'],
						'parent' => $cat['parent'],
					);
				}
			}
			// the first child to look is based on the parent's cat_ID
			$next_child = $breadcrumb[0]['cat_ID'];

			// starting from the parent we will look for a $cat that has the parent['cat_ID'] in child['parent']
			for ( $i = 0; $i < $raw_count; $i++ ) {
				foreach ( $cat_array as $cat ) {
					if ( $next_child == $cat['parent'] ) {
						// add the next breadcrumb li-item
						$breadcrumb[] = $cat;
						// set the next child to search for
						$next_child = $cat['cat_ID'];
					}
				}
				// if $breadcrumb is equally sized as $raw_count then that means $breadcrumb is complete
				if ( count( $breadcrumb ) == $raw_count )
					break;
			}

			// create the final breadcrumb html
			$html_result = '<ul class="category-list">';

			// Add the Home url if $add_home == 'show-home'
			if ( $add_home == 'show-home' ) {
				if ( defined( 'WPF_BREADCRUMB_HOME_URL' ) )
					$home_url = WPF_BREADCRUMB_HOME_URL;
				else
					$home_url = home_url( '/' );
				$html_result .= '<li><a href="' . esc_url( $home_url ) . '">' . __( 'Home', 'wpf' ) . '</a></li>';
			}

			foreach ( $breadcrumb as $cat ) {
				// if the current page is the category's archive page then add .current (disable link)
				$class = ( is_category( $cat['cat_ID'] ) ) ? ' class="current"' : '';

				$html_result .= '<li' . $class . '><a href="' . esc_url( get_category_link( $cat['cat_ID'] ) ) . '">';
				$html_result .= $cat['name'];
				$html_result .= '</a></li>';
			}
			$html_result .= '</ul>';
		}

		// By default the breadcrumb is printed.
		if ( $return == 'print' )
			echo $html_result;
		elseif ( $return == 'return' )
			return $html_result;
	} // end wpf_breadcrumb()
}


/**************************************************************************
 *		>SIDEBAR
 **************************************************************************/


add_action( 'widgets_init', 'wpf_sidebar_support' );
/**
 * Add sidebar support
 *
 *
 */
if ( ! function_exists( 'wpf_sidebar_support' ) ) {
	function wpf_sidebar_support() {
		// create widget areas: sidebar-main, sidebar-footer-1, sidebar-footer-2 or sidebar-footer-3
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'wpf' ),
			'id'            => 'sidebar-main',
			'description'   => __( 'This is the sidebar next to the maincontent of a page', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class=" widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h5 class="widget-title"><strong>',
			'after_title'   => '</strong></h5>',
		) );

		register_sidebar( array(
			'name'          => __( 'Footer 1', 'wpf' ),
			'id'            => 'sidebar-footer-1',
			'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h5 class="widget-title"><strong>',
			'after_title'   => '</strong></h5>',
		) );

		register_sidebar( array(
			'name'          => __( 'Footer 2', 'wpf' ),
			'id'            => 'sidebar-footer-2',
			'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h5 class="widget-title"><strong>',
			'after_title'   => '</strong></h5>',
		) );

		register_sidebar( array(
			'name'          => __( 'Footer 3', 'wpf' ),
			'id'            => 'sidebar-footer-3',
			'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h5 class="widget-title"><strong>',
			'after_title'   => '</strong></h5>',
		) );

		/*
		 * wpf_footer_widget() will return a $GLOBAL['wpf_widget_classes'] containing
		 * the 3 footer grid classes.
		 */
		wpf_footer_widget();
	} // end wpf_sidebar_support()
}

/**
 * Takes care off the footer widget classes
 *
 * This function will run after initializing the sidebar's (see wpf_sidebar_support()
 * wish is hooked to the action 'widgets_init').
 */
if ( ! function_exists( 'wpf_footer_widget' ) ) {
	function wpf_footer_widget() {
		// 1 = active sidebar, 0 = not active
		$footer_sidebar = array(
			( is_active_sidebar( 'sidebar-footer-1' ) ? 1 : 0 ),
			( is_active_sidebar( 'sidebar-footer-2' ) ? 1 : 0 ),
			( is_active_sidebar( 'sidebar-footer-3' ) ? 1 : 0 ),
		);

		$total_active = implode( '', $footer_sidebar );

		switch ( $total_active ){
			case '000':
				$GLOBALS['wpf_widget_active'] = false;
				break;
			case '100':
				$class = array( 'widget-large', false, false );
				break;
			case '010':
				$class = array( false, 'widget-large', false );
				break;
			case '001':
				$class = array( false, false, 'widget-large' );
				break;
			case '110':
				$class = array( 'widget-small', 'widget-medium', false );
				break;
			case '101':
				$class = array( 'widget-medium', false, 'widget-small' );
				break;
			case '011':
				$class = array( false, 'widget-medium', 'widget-small' );
				break;
			default:
				$class = array( 'widget-small', 'widget-small', 'widget-small' );
		}
		// return to a global for later use
		$GLOBALS['wpf_widget_classes'] = ( isset ( $class ) ) ? $class : array();
		if ( ! isset( $GLOBALS['wpf_widget_active'] ) )
			$GLOBALS['wpf_widget_active'] = true; // Return that there are widget active
	} // end wpf_footer_widget()
}

/**
 * This will print the footer sidebars (and it's widget)
 *
 *
 */
if ( ! function_exists( 'wpf_print_footer_sidebar' ) ) {
	function wpf_print_footer_sidebar() {
		if ( $GLOBALS['wpf_widget_active'] and ! is_404() ) {
			echo '<section class="footer-sidebar">';
			$i = 1;
			foreach ( $GLOBALS['wpf_widget_classes'] as $class ) {
				if ( $class ) {
					echo '<div class="' . $class . '">';
					dynamic_sidebar( 'sidebar-footer-' . $i );
					echo '</div>';
				}
				$i++;
			}
			echo '</section><!-- .footer-sidebar -->';
		}
	} // end wpf_print_footer_sidebar()
}


/**************************************************************************
 *		>WP-LOGIN
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


/**************************************************************************
 *		>ADMIN
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


/**************************************************************************
 *		>MISC
 **************************************************************************/


/**
 * Print developer comments
 *
 * This function will only print the developers helping comments when
 * WPF_DEV_MODE is equal to true. The comment will get contained within
 * <!-- and -->.
 *
 * @param    string        $report    The comment that will be printed.
 * @param    bool          $echo      Print $report.
 * @return   string/bool              Returns the variable $report. Default: false
 */
if ( ! function_exists( 'wpf_dev' ) ) {
	function wpf_dev( $report = false, $echo = true ) {
		if ( defined( 'WPF_DEV_MODE' ) && WPF_DEV_MODE ) {
			$report = '<!-- ' . $report . ' -->' . "\n";
			if ( $echo ) echo $report;
		}
		return $report;
	} // end wpf_dev()
}

/**
 * Prints the entry meta for posts.
 *
 *
 */
if ( ! function_exists( 'wpf_entry_meta' ) ) {
	function wpf_entry_meta() {
		// Post sticky
		if ( is_sticky() && is_home() && ! is_paged() )
			echo '<span class="featured-post">' . __( 'Sticky', 'wpf' ) . '</span>';

		// Post date
		printf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
			esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ),
			esc_attr( sprintf( __( 'Permalink to the month archive for %s', 'wpf' ), get_the_date( 'F' ) ) ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		// Post author
		if ( 'post' == get_post_type() ) {
			printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'wpf' ), get_the_author() ) ),
				get_the_author()
			);
		}
	} // end wpf_entry_meta()
}

/**
 * This will alternate the .site-subtitle of the page
 *
 *
 */
if ( ! function_exists( 'wpf_site_subtitle' ) ) {
	function wpf_site_subtitle() {
		switch ( true ) {
			case is_day():
				printf( __( 'Daily Archives: %s', 'wpf' ), get_the_date() );
				break;
			case is_month():
				printf( __( 'Monthly Archives: %s', 'wpf' ), get_the_date( _x( 'F Y' , 'monthly archives date format', 'wpf' ) ) );
				break;
			case is_year():
				printf( __( 'Yearly Archives: %s', 'wpf' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wpf' ) ) );
				break;
			case is_category():
				single_cat_title();
				break;
			case is_search():
				// Get the search query
				$search_query = get_search_query();

				// If the search query is longer then 100 char's then get the first 100
				// char's and add '...' to the end.
				if ( strlen( $search_query ) > 100 ) {
					$search_query = substr( $search_query, 0, 100 ) . '...';
				}
				printf( __( 'The search results for "%s"', 'wpf' ), $search_query );
				break;
			case is_author():
				$author = get_userdata( get_query_var( 'author' ) );
				printf( __( 'Author\'s archive: %s', 'wpf' ), $author->display_name );
				break;
			case is_archive():
				_e( 'Archives', 'wpf' );
				break;
			default:
				echo bloginfo( 'description' );
		}
	} // end wpf_site_subtitle()
}

/**
 * This function takes care of post thumbnails
 *
 * It will post the thumbnail when available and will make the thumbnail
 * clickable (permalink to post). Also it will only show the thumbnail on the
 * first page of the post.
 */
if ( ! function_exists( 'wpf_post_thumbnail' ) ) {
	function wpf_post_thumbnail() {
		if ( has_post_thumbnail() && ! post_password_required() ) {

			$prefix = '<div class="entry-thumbnail">';
			$postfix = '</div>';

			if ( is_single() ) {
				global $page;
				if ( $page == 1 )
					echo $prefix . get_the_post_thumbnail() . $postfix;
			} else {
				echo $prefix;
				echo '<a href="' . get_permalink() . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'wpf' ), the_title_attribute( 'echo=0' ) ) ). '" rel="bookmark">' . get_the_post_thumbnail() . '</a>';
				echo $postfix;
			}
		}
	} // end wpf_post_thumbnail()
}

?>