<?php
define('WPF_VERSION', '0.1.0');

/****************************************************
		Table of contents
*****************************************************
 *		>CLEANUP
 *		>FOUNDATION NAVIGATION
 *		>THEME SUPPORT + SIDEBAR
 *		>MISC
*/

/****************************************************
 *		>CLEANUP
*****************************************************/

// Start cleaning up <head> and add the necessary css and javascript files
add_action('after_setup_theme','wpf_cleanup');
function wpf_cleanup() {

	// launching operation cleanup
	add_action('init', 'wpf_head_cleanup');
	// remove pesky injected css for recent comments widget
	//add_filter('wp_head', 'wpf_remove_wp_widget_recent_comments_style', 1);
	// clean up comment styles in the head
	add_action('wp_head', 'wpf_remove_recent_comments_style', 1);
	// clean up gallery output in wp
	//add_filter('gallery_style', 'wpf_gallery_style');
	
	// enqueue base scripts and styles
	add_action('wp_enqueue_scripts', 'wpf_scripts_and_styles', 999);
	
	// additional post related cleaning
	/* For now it's off until I see the any problems relating to this
		add_filter('img_caption_shortcode', 'wpf_cleaner_caption', 10, 3);
		add_filter('get_image_tag_class', 'wpf_image_tag_class', 0, 4);
		add_filter('get_image_tag', 'wpf_image_editor', 0, 4);
		add_filter('the_content', 'wpf_img_cleanup', 30);
	*/
} // end wpf_cleanup

function wpf_head_cleanup() {
	// remove generator meta
	// remove_action('wp_head', 'wp_generator');
	// category feeds
	// remove_action('wp_head', 'feed_links_extra', 3);
	// post and comment feeds
	// remove_action('wp_head', 'feed_links', 2);
	// EditURI link
	remove_action('wp_head', 'rsd_link');
	// windows live writer
	remove_action('wp_head', 'wlwmanifest_link');
	// index link
	remove_action('wp_head', 'index_rel_link');
	// previous link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	// start link
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	// links for adjacent posts
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	// WP version
	remove_action('wp_head', 'wp_generator');
	// remove WP version from css
	add_filter('style_loader_src', 'wpf_remove_wp_ver_css_js', 9999);
	// remove Wp version from scripts
	add_filter('script_loader_src', 'wpf_remove_wp_ver_css_js', 9999);

} // end head cleanup

// remove WP version from scripts
function wpf_remove_wp_ver_css_js($src) {
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
/*
// remove injected CSS for recent comments widget
function wpf_remove_wp_widget_recent_comments_style() {
	if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
		remove_filter('wp_head', 'wp_widget_recent_comments_style');
	}
}
*/
// remove injected CSS from recent comments widget
function wpf_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}
/*
// remove injected CSS from gallery
function wpf_gallery_style($css) {
	return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}
*/

// Enqueue CSS and Scripts
function wpf_scripts_and_styles() {
	if (!is_admin()) {
		global $is_IE;
	
		// load style.css
		wp_register_style('wpf-stylesheet', get_stylesheet_uri(), array(), WPF_VERSION, 'all');
		
		// deregister WordPress built in jQuery
		wp_deregister_script('jquery');
		
		// register Google jQuery
		wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js", false, null, true);
		
		// modernizr (without html5shiv, will still be loaded but only if needed)
		wp_register_script('wpf-modernizr', get_stylesheet_directory_uri() . '/js/vendor/custom.modernizr.js', array(), '2.6.2', false);
		
		// adding Foundation scripts file in the footer
		wp_register_script('wpf-js', get_stylesheet_directory_uri() . '/js/foundation.min.js', array('jquery'), WPF_VERSION, true);

		// enqueue styles and scripts
		wp_enqueue_style('wpf-stylesheet');
		if ($is_IE) wp_enqueue_script('html5shiv', "http://html5shiv.googlecode.com/svn/trunk/html5.js" , array(), false, true);
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpf-modernizr');
		wp_enqueue_script('wpf-js');
		// comment reply script for threaded comments
		if (get_option('thread_comments')) wp_enqueue_script('comment-reply');
	}
}

/*
// Post related cleaning

// Customized the output of caption, you can remove the filter to restore back to the
// WP default output. Courtesy of DevPress.
// http://devpress.com/blog/captions-in-wordpress/
function wpf_cleaner_caption($output, $attr, $content) {

	// We're not worried about captions in feeds, so just return the output here.
	if (is_feed()) return $output;

	// Set up the default arguments.
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	// Merge the defaults with user input.
	$attr = shortcode_atts($defaults, $attr);

	// If the width is less than 1 or there is no caption, return the content
	// wrapped between the [caption]< tags.
	if (1 > $attr['width'] || empty($attr['caption']))
		return $content;

	// Set up the attributes for the caption <div>.
	$attributes = ' class="figure ' . esc_attr($attr['align']) . '"';

	// Open the caption <div>.
	$output = '<figure' . $attributes .'>';

	// Allow shortcodes for the content the caption was created for.
	$output .= do_shortcode($content);

	// Append the caption text.
	$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';

	// Close the caption </div>.
	$output .= '</figure>';

	// Return the formatted, clean caption.
	return $output;
	
} // end wpf_cleaner_caption

// Clean the output of attributes of images in editor.
// http://www.sitepoint.com/wordpress-change-img-tag-html/
function wpf_image_tag_class($class, $id, $align, $size) {
	$align = 'align' . esc_attr($align);
	return $align;
} // end wpf_image_tag_class

// Remove width and height in editor, for a better responsive world.
function wpf_image_editor($html, $id, $alt, $title) {
	return preg_replace(array(
			'/\s+width="\d+"/i',
			'/\s+height="\d+"/i',
			'/alt=""/i'
		),
		array(
			'',
			'',
			'',
			'alt="' . $title . '"'
		),
		$html);
} //end wpf_image_editor

// Wrap images with <figure>-tag and remove <p>-tag.
// http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
function wpf_img_cleanup($content) {
	$content = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $content);
	return $pee;
} // end wpf_img_cleanup
*/

/****************************************************
 *		>FOUNDATION NAVIGATION
*****************************************************/

// Pagination
function wpf_pagination() {
	global $wp_query;
 
	$big = 999999999; // This needs to be an unlikely integer
 
	// For more options and info view the docs for paginate_links()
	// http://codex.wordpress.org/Function_Reference/paginate_links
	$paginate_links = paginate_links(array(
		'base' => str_replace($big, '%#%', get_pagenum_link($big)),
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages,
		'mid_size' => 5,
		'prev_next' => True,
		'prev_text' => __('&laquo;', 'wpf'),
		'next_text' => __('&raquo;', 'wpf'),
		'type' => 'list'
	));
 
	// Display the pagination if more than one page is found
	if ($paginate_links) {
		echo '<div class="pagination-centered">';
		echo $paginate_links;
		echo '</div><!--// end .pagination -->';
	}
}

// Add Foundation 'active' class for the current menu item
function wpf_active_nav_class($classes, $item) {
	if ($item->current == 1 || $item->current_item_ancestor == true) {
		$classes[] = 'active';
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'wpf_active_nav_class', 10, 2);

/**
 * Use the active class of ZURB Foundation on wp_list_pages output.
 * From required+ Foundation http://themes.required.ch
 */
function wpf_active_list_pages_class($input) {
	$pattern = '/current_page_item/';
	$replace = 'current_page_item active';
	$output = preg_replace($pattern, $replace, $input);
	return $output;
}
add_filter('wp_list_pages', 'wpf_active_list_pages_class', 10, 2);

/**
 * class required_walker
 * Custom output to enable the the ZURB Navigation style.
 * Courtesy of Kriesi.at. http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
 * From required+ Foundation http://themes.required.ch
 */
class wpf_walker extends Walker_Nav_Menu {

	/**
	 * Specify the item type to allow different walkers
	 * @var array
	 */
	var $nav_bar = '';

	function __construct($nav_args = '') {

		$defaults = array(
			'item_type' => 'li',
			'in_top_bar' => false,
		);
		$this->nav_bar = apply_filters('req_nav_args', wp_parse_args($nav_args, $defaults));
	}

	function display_element($element, &$children_elements, $max_depth, $depth=0, $args, &$output) {
		$id_field = $this->db_fields['id'];
		if (is_object($args[0])) {
			$args[0]->has_children = ! empty($children_elements[$element->$id_field]);
		}
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Check for flyout
		$flyout_toggle = '';
		if ($args->has_children && $this->nav_bar['item_type'] == 'li') {

			if ($depth == 0 && $this->nav_bar['in_top_bar'] == false) {

				$classes[] = 'has-flyout';
				$flyout_toggle = '<a href="#" class="flyout-toggle"><span></span></a>';

			} elseif ($this->nav_bar['in_top_bar'] == true) {

				$classes[] = 'has-dropdown';
				$flyout_toggle = '';
			}
		}

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		if ( $depth > 0 ) {
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		} else {
			$output .= $indent . ($this->nav_bar['in_top_bar'] == true ? '<li class="divider"></li>' : '') . '<' . $this->nav_bar['item_type'] . ' id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		}

		$attributes  = ! empty($item->attr_title) ? ' title="'	. esc_attr($item->attr_title) .'"' : '';
		$attributes .= ! empty($item->target)			? ' target="'	. esc_attr($item->target		) .'"' : '';
		$attributes .= ! empty($item->xfn)				? ' rel="'		. esc_attr($item->xfn				) .'"' : '';
		$attributes .= ! empty($item->url)				? ' href="'		. esc_attr($item->url				) .'"' : '';

		$item_output  = $args->before;
		$item_output .= '<a '. $attributes .'>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $flyout_toggle; // Add possible flyout toggle
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	function end_el(&$output, $item, $depth = 0, $args = array()) {
		if ($depth > 0) {
			$output .= "</li>\n";
		} else {
			$output .= "</" . $this->nav_bar['item_type'] . ">\n";
		}
	}

	function start_lvl(&$output, $depth = 0, $args = array()) {
		if ($depth == 0 && $this->nav_bar['item_type'] == 'li') {
			$indent = str_repeat("\t", 1);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"flyout\">\n";
	} else {
			$indent = str_repeat("\t", $depth);
			$output .= $this->nav_bar['in_top_bar'] == true ? "\n$indent<ul class=\"dropdown\">\n" : "\n$indent<ul class=\"level-$depth\">\n";
		}
	}
}

/****************************************************
 *		>THEME SUPPORT + SIDEBAR
*****************************************************/
if (!function_exists('wpf_theme_support')) {
	function wpf_theme_support() {
		// language support
		load_theme_textdomain('wpf', get_template_directory() . '/lang');
		
		// Add post thumbnail supports.
		add_theme_support('post-thumbnails');
		// set_post_thumbnail_size(150, 150, false);
		
		// rss
		add_theme_support('automatic-feed-links');
		
		// Add post formarts supports.
		//add_theme_support('post-formats', array('gallery', 'link', 'image', 'quote', 'video'));
		
		// Add menu supports.
		add_theme_support('menus');
		register_nav_menus(array(
			'primary' => __('Primary Navigation', 'wpf'),
			'utility' => __('Utility Navigation', 'wpf')
		));
	} // end wpf_theme_support()
}
add_action('after_setup_theme', 'wpf_theme_support');

if (!function_exists('wpf_sidebar_support')) {
	function wpf_sidebar_support() {
		// create widget areas: sidebar, footer
		register_sidebar(array(
			'name'=> __('Sidebar', 'wpf'),
			'id' => 'sidebar-main',
			'description' => __('This is the sidebar next to the maincontent of a page', 'wpf'),
			'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="large-12 columns">',
			'after_widget' => '</div></article>',
			'before_title' => '<h6 class="widget-title"><strong>',
			'after_title' => '</strong></h6>'
		));
		
		register_sidebar(array(
			'name' => __('Footer 1', 'wpf'),
			'id' => 'sidebar-footer-1',
			'description' => __('An optional widget area for your site footer', 'wpf'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="widget-title"><strong>',
			'after_title' => '</strong></h6>'
		));
		
		register_sidebar(array(
			'name' => __('Footer 2', 'wpf'),
			'id' => 'sidebar-footer-2',
			'description' => __('An optional widget area for your site footer', 'wpf'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="widget-title"><strong>',
			'after_title' => '</strong></h6>'
		));
		
		register_sidebar(array(
			'name' => __('Footer 3', 'wpf'),
			'id' => 'sidebar-footer-3',
			'description' => __('An optional widget area for your site footer', 'wpf'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="widget-title"><strong>',
			'after_title' => '</strong></h6>'
		));
		// wpf_footer_widget() will return a $GLOBAL['$wpf_widget_classes'] containing the 3 footer grid classes
		wpf_footer_widget();
	} // end wpf_sidebar_support();
}
add_action('widgets_init', 'wpf_sidebar_support');

if (!function_exists('wpf_footer_widget')) {
	// this function takes care off the footer widget classes
	function wpf_footer_widget() {
		// 1 = active sidebar, 0 = not active
		
		$footer_sidebar = array(
												(is_active_sidebar('sidebar-footer-1') ? 1 : 0),
												(is_active_sidebar('sidebar-footer-2') ? 1 : 0),
												(is_active_sidebar('sidebar-footer-3') ? 1 : 0)
											);
		$total_active = implode('', $footer_sidebar);
		
		switch ($total_active){
			case '100':
				$class = array('large-12', false, false);
				break;
			case '010':
				$class = array(false, 'large-12', false);
				break;
			case '001':
				$class = array(false, false, 'large-12');
				break;
			case '110':
				$class = array('large-4', 'large-8', false);
				break;
			case '101':
				$class = array('large-8', false, 'large-4');
				break;
			case '011':
				$class = array(false, 'large-8', 'large-4');
				break;
			default:
				$class = array('large-4', 'large-4', 'large-4');	
		}
	
		// return
		$GLOBALS['wpf_widget_classes'] = $class;
	}
}

if (!function_exists('wpf_entry_meta')) {
	// return entry meta information for posts, used by multiple loops.
	function wpf_entry_meta() {
		echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s at %s.', 'wpf'), get_the_time('l, F jS, Y'), get_the_time()) .'</time>';
		echo '<p class="byline author">'. __('Written by', 'wpf') .' <a href="'. get_author_posts_url(get_the_author_meta('ID')) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
	}
}

/****************************************************
 *		>MISC
*****************************************************/


?>