<?php

/**************************************************************************
		Table of contents
 **************************************************************************
 *		>SETTINGS
 *		>LANG + CLEANUP + ENQUEUE
 *		>FOUNDATION NAVIGATION
 *		>THEME SUPPORT + SIDEBAR
 *		>MISC
 */


/**************************************************************************
 *		>SETTINGS
 **************************************************************************/


/**
 * The current WPF version (used to add version number to WPF css/js files).
 *
 * Required: see the function wpf_scripts_and_styles()
 *
 * @todo Make it optional
 */
define('WPF_VERSION', '0.2.2');

/**
 * The value WPF_DEV_MODE is defined by wether WP_DEBUG is set to true or false 
 * (or not at all == false).
 *
 * Optional: see the function wpf_dev().
 */
define('WPF_DEV_MODE', ( defined( 'WP_DEBUG' ) ) ? WP_DEBUG : false);

/**
 * You can change the "Home" url that is used for the wpf_breadcrumb().
 * 
 * Optional: you can use "define( 'WPF_BREADCRUMB_HOME_URL' , '#url');" in your child theme.
 */
//if( ! defined( 'WPF_BREADCRUMB_HOME_URL' ) ) define( 'WPF_BREADCRUMB_HOME_URL' , '#url');


/**************************************************************************
 *		>LANG + CLEANUP + ENQUEUE
 **************************************************************************/
 
 
add_action( 'after_setup_theme', 'wpf_cleanup' );
/**
 * Cleaning up WP source and adding language support
 * 
 * @todo clean up the "cleanup functions"
 */
if ( ! function_exists( 'wpf_cleanup' ) ) {
	// Add language support, start cleaning up <head> and add the necessary css and javascript files
	function wpf_cleanup() {
		// add language support
		load_theme_textdomain( 'wpf', get_template_directory() . '/lang' );
	
		// launching operation cleanup
		add_action( 'init', 'wpf_head_cleanup' );
		// remove pesky injected css for recent comments widget
		//add_filter('wp_head', 'wpf_remove_wp_widget_recent_comments_style', 1);
		// clean up comment styles in the head
		add_action( 'wp_head', 'wpf_remove_recent_comments_style', 1 );
		// clean up gallery output in wp
		//add_filter('gallery_style', 'wpf_gallery_style');
		
		// enqueue base scripts and styles
		add_action( 'wp_enqueue_scripts', 'wpf_scripts_and_styles', 999 );
		
		// additional post related cleaning
		/* For now it's commented until I come across any problems relating to this
			add_filter('img_caption_shortcode', 'wpf_cleaner_caption', 10, 3);
			add_filter('get_image_tag_class', 'wpf_image_tag_class', 0, 4);
			add_filter('get_image_tag', 'wpf_image_editor', 0, 4);
			add_filter('the_content', 'wpf_img_cleanup', 30);
		*/
	} // end wpf_cleanup()
}

/**
 * Cleaning up the head
 * 
 * @todo clean up the function
 */
if ( ! function_exists( 'wpf_head_cleanup' ) ) {
	function wpf_head_cleanup() {
		// category feeds
		// remove_action('wp_head', 'feed_links_extra', 3);
		// post and comment feeds
		// remove_action('wp_head', 'feed_links', 2);
		// EditURI link
		remove_action( 'wp_head', 'rsd_link' );
		// windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// index link
		remove_action( 'wp_head', 'index_rel_link' );
		// previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		// start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		// links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		// WP version
		remove_action( 'wp_head', 'wp_generator' );
		// remove WP version from css
		add_filter( 'style_loader_src', 'wpf_remove_wp_ver_css_js', 999 );
		// remove Wp version from scripts
		add_filter( 'script_loader_src', 'wpf_remove_wp_ver_css_js', 999 );
	} // end head cleanup()
}

/**
 * Remove WP version from scripts
 * 
 * @todo change this so it only deletes wp version ver={current_wp_version} in strpos()
 */
if ( ! function_exists( 'wpf_remove_wp_ver_css_js' ) ) {
	function wpf_remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	} // end wpf_remove_wp_ver_css_js()
}

/**
 * Remove injected CSS for recent comments widget
 * 
 * @todo see wpf_remove_recent_comments_style
 */
/*
if ( ! function_exists( 'wpf_remove_wp_widget_recent_comments_style' ) ) {
	function wpf_remove_wp_widget_recent_comments_style() {
		if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
			remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
		}
	} // end wpf_remove_wp_widget_recent_comments_style()
}
*/

/**
 * Remove injected CSS from recent comments widget
 * 
 * @todo see wpf_remove_wp_widget_recent_comments_style
 */
if ( ! function_exists( 'wpf_remove_recent_comments_style' ) ) {
	// remove injected CSS from recent comments widget
	function wpf_remove_recent_comments_style() {
		global $wp_widget_factory;
		if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
	} //  end wpf_remove_recent_comments_style()
}

/**
 * Remove injected CSS from gallery
 *
 *
 */
/*
if ( ! function_exists( 'wpf_gallery_style' ) ) {
	function wpf_gallery_style( $css ) {
		return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	} // end wpf_gallery_style()
}
*/

/**
 * Enqueue CSS and (JS)scripts
 *
 *
 */
if ( ! function_exists( 'wpf_scripts_and_styles' ) ) {
	function wpf_scripts_and_styles() {
		if ( ! is_admin() ) {
			// load style.css
			wp_register_style( 'wpf-stylesheet', get_stylesheet_uri(), array(), WPF_VERSION, 'all' );
			
			// deregister WordPress built in jQuery
			wp_deregister_script( 'jquery' );
			
			// register Google jQuery
			wp_register_script( 'jquery', "http" . ( $_SERVER['SERVER_PORT'] == 443 ? "s" : "" ) . "://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js", false, null, true );
			
			// modernizr (without html5shiv. html5shiv will still be loaded seperatly, but only if needed - see header.php)
			wp_register_script( 'wpf-modernizr', get_template_directory_uri() . '/js/vendor/custom.modernizr-2.6.2.min.js', array(), '2.6.2', false );
			
			// adding Foundation scripts file in the footer
			wp_register_script( 'wpf-js', get_stylesheet_directory_uri() . '/js/foundation.min.js', array( 'jquery' ), WPF_VERSION, true );
	
			// enqueue styles and scripts
			wp_enqueue_style( 'wpf-stylesheet' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wpf-modernizr' );
			wp_enqueue_script( 'wpf-js' );
			// comment reply script for threaded comments
			if ( get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' );
		}
	} // end wpf_scripts_and_styles()
}

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


/*
// Post related cleaning

if ( ! function_exists( 'wpf_cleaner_caption' ) ) {
	// Customized the output of caption, you can remove the filter to restore back to the
	// WP default output. Courtesy of DevPress.
	// http://devpress.com/blog/captions-in-wordpress/
	function wpf_cleaner_caption( $output, $attr, $content ) {
	
		// We're not worried about captions in feeds, so just return the output here.
		if ( is_feed() ) return $output;
	
		// Set up the default arguments.
		$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
		);
	
		// Merge the defaults with user input.
		$attr = shortcode_atts( $defaults, $attr );
	
		// If the width is less than 1 or there is no caption, return the content
		// wrapped between the [caption]< tags.
		if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
			return $content;
	
		// Set up the attributes for the caption <div>.
		$attributes = ' class="figure ' . esc_attr( $attr['align'] ) . '"';
	
		// Open the caption <div>.
		$output = '<figure' . $attributes .'>';
	
		// Allow shortcodes for the content the caption was created for.
		$output .= do_shortcode( $content );
	
		// Append the caption text.
		$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';
	
		// Close the caption </div>.
		$output .= '</figure>';
	
		// Return the formatted, clean caption.
		return $output;
		
	} // end wpf_cleaner_caption()
}

if ( ! function_exists( 'wpf_image_tag_class' ) ) {
	// Clean the output of attributes of images in editor.
	// http://www.sitepoint.com/wordpress-change-img-tag-html/
	function wpf_image_tag_class( $class, $id, $align, $size ) {
		$align = 'align' . esc_attr( $align );
		return $align;
	} // end wpf_image_tag_class()
	
	// Remove width and height in editor, for a better responsive world.
	function wpf_image_editor( $html, $id, $alt, $title ) {
		return preg_replace( array(
				'/\s+width="\d+"/i',
				'/\s+height="\d+"/i',
				'/alt=""/i',
			), array(
				'',
				'',
				'',
				'alt="' . $title . '"',
			),
			$html );
	} //end wpf_image_editor
}

function ( ! function_exists( 'wpf_img_cleanup' ) ) {
	// Wrap images with <figure>-tag and remove <p>-tag.
	// http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
	function wpf_img_cleanup( $content ) {
		$content = preg_replace( '/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $content );
		return $pee;
	} // end wpf_img_cleanup()
}
*/

add_action( 'admin_bar_menu', 'wpf_remove_wp_logo', 999 );
/**
 * Remove the wp logo in the wordpress admin bar
 *
 *
 */
if ( ! function_exists( 'wpf_remove_wp_logo' ) )
{
	function wpf_remove_wp_logo( $wp_admin_bar ) {
	    $wp_admin_bar->remove_node( 'wp-logo' );
	}
}


/**************************************************************************
 *		>FOUNDATION NAVIGATION
 **************************************************************************/


/**
 * Use Foundation's pagination style on paginate_links() output.
 *
 * @todo Code refactoring (incl. css classes)
 */
if ( ! function_exists( 'wpf_pagination' ) ) {
	function wpf_pagination() {
		global $wp_query;
	 
		$big = 999999999; // This needs to be an unlikely integer
	 
		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links( array(
			'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $wp_query->max_num_pages,
			'mid_size'  => 5,
			'prev_next' => True,
			'prev_text' => __( '&laquo;', 'wpf' ),
			'next_text' => __( '&raquo;', 'wpf' ),
			'type'      => 'list',
		) );
	 
		// Display the pagination if more than one page is found
		if ( $paginate_links ) {
			echo '<div class="pagination-centered">';
			echo $paginate_links;
			echo '</div><!-- end .pagination -->';
		}
	} // end wpf_pagination()
}

add_filter( 'wp_list_pages', 'wpf_active_list_pages_class' );
/**
 * Use the active class of ZURB Foundation on wp_list_pages output.
 *
 * @todo Code refactoring
 */
if ( ! function_exists( 'wpf_active_list_pages_class' ) ) {
	function wpf_active_list_pages_class( $input ) {
		$pattern = '/current_page_item/';
		$replace = 'current_page_item active';
		$output = preg_replace( $pattern, $replace, $input );
		return $output;
	} // end wpf_active_list_pages_class()
}

add_filter( 'nav_menu_css_class', 'wpf_active_nav_class', 10, 2 );
/**
 * Add Foundation 'active' class for the wp_nav_menu output.
 *
 * @todo Code refactoring
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
 * @todo Code refactoring
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
 * This is the fallback function for the (main WPF) wp_nav_menu().
 *
 * @param    array    $args    It receives the array $args from wp_nav_menu().
 * @return   string            It retuns the html output that is used as the fallback menu.
 */
if ( ! function_exists( 'wpf_nav_menu_fallback' ) ) {
	function wpf_nav_menu_fallback( $args ) {
		// If the user has no rights to change the menu's: abort
		if ( ! current_user_can( 'edit_theme_options' ) ) return;

		$output  = '<li class="divider"></li>';
		$output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Add a menu', 'wpf' ) . '</a></li>';
		$output .= '<li class="divider"></li>';
		
		// Add the menu wrapper
		$output = sprintf( $args['items_wrap'], null, null, $output );
		
		if ( $args['echo'] ) echo $output;

		return $output;
	} // end wpf_nav_menu_fallback()
}


/**************************************************************************
 *		>THEME SUPPORT + SIDEBAR
 **************************************************************************/

add_action( 'after_setup_theme', 'wpf_theme_support' );
/**
 * Adding theme support
 *
 * @todo handle tumbnail sizes
 */
if ( ! function_exists( 'wpf_theme_support' ) ) {
	function wpf_theme_support() {
		// Add post thumbnail supports.
		add_theme_support( 'post-thumbnails' );
		// set_post_thumbnail_size(150, 150, false);

		// rss
		add_theme_support( 'automatic-feed-links' );
		
		// Add menu supports.
		add_theme_support( 'menus' );
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'wpf' ),
			'utility' => __( 'Utility Navigation', 'wpf' ),
		) );
	} // end wpf_theme_support()
}

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
			'before_title'  => '<h6 class="widget-title"><strong>',
			'after_title'   => '</strong></h6>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer 1', 'wpf' ),
			'id' => 'sidebar-footer-1',
			'description' => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h6 class="widget-title"><strong>',
			'after_title' => '</strong></h6>',
		) );
		
		register_sidebar( array(
			'name'          => __( 'Footer 2', 'wpf' ),
			'id'            => 'sidebar-footer-2',
			'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h6 class="widget-title"><strong>',
			'after_title'   => '</strong></h6>',
		) );
		
		register_sidebar( array(
			'name'          => __( 'Footer 3', 'wpf' ),
			'id'            => 'sidebar-footer-3',
			'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h6 class="widget-title"><strong>',
			'after_title'   => '</strong></h6>',
		) );
		
		// wpf_footer_widget() will return a $GLOBAL['wpf_widget_classes']
		// containing the 3 footer grid classes
		wpf_footer_widget();
	} // end wpf_sidebar_support();
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
			( is_active_sidebar('sidebar-footer-1') ? 1 : 0 ),
			( is_active_sidebar('sidebar-footer-2') ? 1 : 0 ),
			( is_active_sidebar('sidebar-footer-3') ? 1 : 0 ),
		);
		
		$total_active = implode( '', $footer_sidebar );

		switch ( $total_active ){
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
				$class = array( 'widget-small', 'widget-medium', false );//
				break;
			case '101':
				$class = array( 'widget-medium', false, 'widget-small' );//
				break;
			case '011':
				$class = array( false, 'widget-medium', 'widget-small' );//
				break;
			default:
				$class = array( 'widget-small', 'widget-small', 'widget-small' );//
		}
	
		// return
		$GLOBALS['wpf_widget_classes'] = $class;
	}
}

/**
 * This will print the footer sidebars (and it's widget)
 * 
 * 
 */
 if ( ! function_exists( 'wpf_print_footer_sidebar' ) ) {
	 function wpf_print_footer_sidebar() {
	 	$sidebar_active =    is_active_sidebar( 'sidebar-footer-1' )
	 					  or is_active_sidebar( 'sidebar-footer-2' )
	 					  or is_active_sidebar( 'sidebar-footer-3' );
	 	
		if ( $sidebar_active and ! is_404() ) {
			echo '<section>';
			$i = 1;
			foreach ( $GLOBALS['wpf_widget_classes'] as $class ) {
				if ( $class ) {
					echo '<div class="' . $class . '">';
					dynamic_sidebar( 'sidebar-footer-' . $i );
					echo '</div>';
				}
				$i++;
			}
			echo '</section>';
		}
	 }
 }

/**************************************************************************
 *		>MISC
 **************************************************************************/


/**
 * Print developer comments
 *
 * This function will only print the comments when WPF_DEV_MODE is equal to true.
 * The comment will get contained within <!-- and -->.
 *
 * @param    string    $report    The comment that will be printed.
 * @param    bool      $echo      Print $report.
 * @return   string               It will always return $report.
 *
 * @todo Self-Explanatory Flag Values for Function Arguments
 */
if ( ! function_exists( 'wpf_dev' ) ) {
	function wpf_dev( $report = '', $echo = true ) {
		if ( defined( 'WPF_DEV_MODE' ) && WPF_DEV_MODE ) {
			$report = '<!-- ' . $report . ' -->' . "\n";
			if ( $echo ) echo $report;
		}
		
		return $report;
	}
}

/**
 * Prints the entry meta for posts.
 * 
 *
 */
if ( ! function_exists( 'wpf_entry_meta' ) ) {
	// prints the entry meta for posts.
	function wpf_entry_meta() {
		printf( __( '<p>Posted by <a href="%s" rel="author">%s</a> on <a href="%s"><time datetime="%s">%s</time></a></p>', 'wpf' ),
			get_author_posts_url( get_the_author_meta( 'ID') ),
			get_the_author(),
			get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
			get_the_time( 'c' ),
			// Translators: this is the date format for the post meta text
			get_the_time( __( 'F j, Y', 'wpf' ) )
		);
	}
}

/**
 * Post the tags with foundation labels
 *
 * @param    string    $before    Will be put before the tags html if has_tag() == true.
 * @param    string    $after     Will be put after the tags html if has_tag() == true.
 */
if ( ! function_exists( 'wpf_tags' ) ) {
	function wpf_tags( $before = null, $after = null ) {
		if ( has_tag() ) {
			echo $before;
			// Translators: Only translate "Tags:"
			the_tags( __( '<span class="secondary label">', 'wpf' ), '</span> <span class="secondary label">', '</span>' );
			echo $after;
		}
	}
}

/**
 * Print post meta information
 *
 * Prints the footer post meta for posts. Also uses wpf_tags(). Prints the category's
 * and show's the comment count (if comments is enabled).
 *
 * @param    bool    $tags    Wheter to print post tags or not. Default: true
 *
 * @todo Self-Explanatory Flag Values for Function Arguments
 */
if ( ! function_exists( 'wpf_postfooter_meta' ) ) {
	function wpf_postfooter_meta( $tags = true ) {		
		// check if the post has comments enabled
		if ( comments_open() ) {
			// get the number of comments
			$num_comments = get_comments_number();
			
			if ($num_comments == 0) {
				$comment = __( 'Leave a response', 'wpf' );
			} else {
				$comment = sprintf( _n( 'one response', '%s responses', $num_comments, 'wpf' ), $num_comments );	
			}
			// add the permalink + #respond anchor so it directs the user to the form
			$comment = '<a href="' . get_permalink() . '#respond">' . $comment . '</a>';

		} else {
			$comment = __( 'Comments are locked for this post', 'wpf' );
		}
		
		echo '<div class="text-center">' . $comment . '</div>';
		
		if ( $tags ) wpf_tags( '<br><div class="text-center">', '</div><br>' );
	}
}

/**
 * Prints the category breadcrumb for a post using foundation's breadcrumb
 * 
 * @link: http://foundation.zurb.com/docs/components/breadcrumbs.html
 *
 * @param    bool    $add_home    Wheter to add the "Home"-link within the breadcrumb. Default: false
 * @param    bool    $print       Wether to print (true) or return (false). Default: true
 * @return   string               When $print == false then it will return the breadcrumb html.
 *
 * @todo Refactor
 */
if ( ! function_exists( 'wpf_breadcrumb' ) ) {
	function wpf_breadcrumb( $add_home = false, $print = true ) {
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
			$html_result = '<ul class="breadcrumbs">';
			
			// Add the Home url if $add_home == true
			if ( $add_home ) {
				// @todo add esc_url() before defining WPF_BREADCRUMB_HOME_URL to $home_url
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
		// See wpf_breadcrumb($print = true) at the top
		if ( $print )
			echo $html_result;
		else
			return $html_result;	
	}
}

/**
 * This will alternate the .site-subtitle of the page
 *
 *
 */
if ( ! function_exists( 'wpf_site_subtitle' ) ) {
	function wpf_site_subtitle() {
		if ( is_archive() && have_posts() ) {
			if ( is_day() )
				printf( __( 'Daily Archives: %s', 'wpf' ), get_the_date() );
			elseif ( is_month() )
				printf( __( 'Monthly Archives: %s', 'wpf' ), get_the_date( _x( 'F Y' , 'monthly archives date format', 'wpf' ) ) );
			elseif ( is_year() )
				printf( __( 'Yearly Archives: %s', 'wpf' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wpf' ) ) );
			elseif ( is_category() )
				single_cat_title();
			else
				_e( 'Archives', 'wpf' );
		} elseif ( is_search() ) {
			echo __( 'Search Results for', 'wpf' ) . ' ' . substr( get_search_query(), 0, 50 );
			if ( strlen( get_search_query() ) > 50 ) echo '...';
		
		} else {
			echo bloginfo( 'description' );
		}
	}
}
?>