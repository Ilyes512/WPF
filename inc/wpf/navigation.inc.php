<?php
/**************************************************************************
 *    >FOUNDATION NAVIGATION + BREADCRUMB
 **************************************************************************/


add_filter( 'wp_link_pages_args', 'wpf_link_pages_args' );
/**
 * Add a new way of displaying wp_link_pages().
 *
 * This filter is called within wp_link_pages(). It will add the ability to display both
 * the page numbers and next/previous links.
 *
 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/post-template.php
 *
 * @param    array    $args    These are the arguments passed to wp_link_pages().
 * @return   array             Return the arguments including the possible changes of having next/previous links.
 */
if ( ! function_exists( 'wpf_link_pages_args' ) ) {
	function wpf_link_pages_args( $args ) {
		if ( 'both' == $args['next_or_number'] ) {
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

add_filter( 'wp_link_pages_link', 'wpf_link_pages_link', 10, 2 );
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

		// Make sure that there is no <a>-htmltag either (or else on a search result page it will cause a styling problem)
		if ( $page == $page_number && ! preg_match( '/<a\s+/i', $link ) )
			return '<li><span class="current">' . $link . '</span></li>';

		// Default
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
		$paginate_links = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'end_size'  => 2,
				'mid_size'  => 5,
				'prev_next' => True,
				'prev_text' => __( '&#xf053; Previous', 'wpf' ),
				'next_text' => __( 'Next &#xf054;', 'wpf' ),
				'type'      => 'list',
			)
		);

		// Display the pagination if more than one page is found
		if ( $paginate_links )
			echo '<div class="navigation">' . $paginate_links . '</div>';
	} // end wpf_paginate_link()
}

/**
 * Use Foundation's pagination style for paginate_comments_links().
 *
 *
 */
if ( ! function_exists( 'wpf_paginate_comments_link' ) ) {
	function wpf_paginate_comments_link() {

		// For more options and info view the docs for paginate_comments_links()
		// http://codex.wordpress.org/Template_Tags/paginate_comments_links
		$paginate_links = paginate_comments_links(
			array(
				'end_size'  => 2,
				'mid_size'  => 5,
				'prev_text' => __( '&#xf053; Previous', 'wpf' ),
				'next_text' => __( 'Next &#xf054;', 'wpf' ),
				'type'      => 'list',
				'echo'      => false,
			)
		);

		// Display the pagination if more than one page is found
		if ( $paginate_links )
			echo '<div class="navigation">' . $paginate_links . '</div>';
	} // end wpf_paginate_comments_link()
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
				wp_link_pages(
					array(
						'before'           => '<div class="pagination-container"><ul class="page-numbers">',
						'after'            => '</ul></div>',
						'next_or_number'   => 'both',
						'nextpagelink'     => __( 'Next page &#xf054;', 'wpf' ),
						'previouspagelink' => __( '&#xf053; Previous page', 'wpf' ),
					)
				);
				break;
			case is_search();
				wp_link_pages(
					array(
						'before'           => '<div class="pagination-container"><span class="pagination-title">' . __( 'Pages:', 'wpf' ) . '</span><ul class="page-numbers">',
						'after'            => '</ul></div>',
						'next_or_number'   => 'both',
						'nextpagelink'     => __( 'Next page &#xf054;', 'wpf' ),
						'previouspagelink' => __( '&#xf053; Previous page', 'wpf' ),
					)
				);
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
		if ( $item->current == 1 || $item->current_item_ancestor == true )
			$classes[] = 'active';
		return $classes;
	} // end wpf_active_nav_class()
}

/**
 * Create a custom HTML list of nav menu items.
 *
 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/nav-menu-template.php
 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
if ( ! class_exists( 'Wpf_Walker' ) ) {
	class Wpf_Walker extends Walker_Nav_menu {
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
	} // end class Wpf_Walker
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
				$output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . __( 'Add a menu', 'wpf' ) . '</a></li>';
				$output .= '<li class="divider"></li>';
				break;
			case 'footer';
				$output = '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . __( 'Add a menu', 'wpf' ) . '</a></li>';
				break;
		}

		// Add the menu wrapper
		$output = sprintf( $args['items_wrap'], $args['menu_id'], $args['menu_class'], $output );

		if ( $args['echo'] ) echo $output;

		return $output;
	} // end wpf_nav_menu_fallback()
}

/**
 * This function will display the primary topbar menu
 *
 *
 */
if ( ! function_exists( 'wpf_primarymenu_display' ) ) {
	function wpf_primarymenu_display() {

		// The #navbar classes
		$navbar_class         = $GLOBALS['wpf_settings']['menu_primary_center'] ? 'contain-to-grid ' : '';
		$navbar_class        .= $GLOBALS['wpf_settings']['menu_primary_fixed'] ? $GLOBALS['wpf_settings']['menu_primary_fixed'] : '';

		// The top-bar data-options
		$navbar_data_options  = $GLOBALS['wpf_settings']['menu_primary_custom_back_text'] ? 'back_text:' . $GLOBALS['wpf_settings']['menu_primary_back_text'] : 'custom_back_text:false;';

		?>
		<div id="navbar" class="<?php echo esc_attr( $navbar_class ); ?>">

			<nav id="site-navigation" class="top-bar" role="navigation" data-options="sticky_class:sticky-top-bar;<?php echo esc_attr( $navbar_data_options ); ?>" data-topbar>
				<ul class="title-area">
					<li class="name">
						<h1>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<?php echo esc_html( $GLOBALS['wpf_settings']['menu_primary_title'] ); ?>
							</a>
						</h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#"><?php _e( 'Menu', 'wpf' ); ?></a></li>
				</ul>
				<section class="top-bar-section">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'items_wrap'     => '<ul class="left">%3$s</ul>',
							'walker'         => new Wpf_Walker(),
							'fallback_cb'    => 'wpf_nav_menu_fallback',
						)
					); ?>
					<ul class="right hide-for-medium-down">
						<li class="divider"></li>
						<li class="has-form"><?php get_search_form(); ?></li>
					</ul>
				</section><!-- .top-bar-section -->
			</nav><!-- #site-navigation -->
		</div><!-- #nav-bar -->
	<?php
	} // end wpf_primarymenu_display()
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
				$class = is_category( $cat['cat_ID'] ) ? ' class="current"' : '';

				$html_result .= '<li' . $class . '><a href="' . esc_url( get_category_link( $cat['cat_ID'] ) ) . '">';
				$html_result .= esc_html( $cat['name'] );
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
