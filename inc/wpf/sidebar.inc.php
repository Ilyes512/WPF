<?php
/**************************************************************************
 *    >SIDEBAR
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
		register_sidebar(
			array(
				'name'          => __( 'Sidebar', 'wpf' ),
				'id'            => 'sidebar-main',
				'description'   => __( 'This is the sidebar next to the maincontent of a page', 'wpf' ),
				'before_widget' => '<aside id="%1$s" class=" widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h5 class="widget-title"><strong>',
				'after_title'   => '</strong></h5>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Footer 1', 'wpf' ),
				'id'            => 'sidebar-footer-1',
				'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h5 class="widget-title"><strong>',
				'after_title'   => '</strong></h5>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Footer 2', 'wpf' ),
				'id'            => 'sidebar-footer-2',
				'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h5 class="widget-title"><strong>',
				'after_title'   => '</strong></h5>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Footer 3', 'wpf' ),
				'id'            => 'sidebar-footer-3',
				'description'   => __( 'An optional widget area for your site footer', 'wpf' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h5 class="widget-title"><strong>',
				'after_title'   => '</strong></h5>',
			)
		);

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
		// 1 = active sidebar, 0 = not active sidebar
		$widget_activation = array(
			( is_active_sidebar( 'sidebar-footer-1' ) ? 1 : 0 ),
			( is_active_sidebar( 'sidebar-footer-2' ) ? 1 : 0 ),
			( is_active_sidebar( 'sidebar-footer-3' ) ? 1 : 0 ),
		);

		/*
		 * Now make a string of the active status. 3 digits with each digit representing
		 * the widget from left to right (sidebar-footer-1 to sidebar-footer-3).
		 */
		$widget_activation = implode( '', $widget_activation );

		// Assign the classes based on  wish sidebar-footer widget and
		switch ( $widget_activation ){
			case '000':
				$class = null;
				$GLOBALS['wpf_widget_active'] = false;
				break;
			case '111':
				$class = array( 'widget-small', 'widget-small', 'widget-small' );
				break;
			case '100':
				$class = array( 'widget-xlarge', false, false );
				break;
			case '010':
				$class = array( false, 'widget-xlarge', false );
				break;
			case '001':
				$class = array( false, false, 'widget-xlarge' );
				break;
			case '110':
				$class = array( 'widget-small', 'widget-large', false );
				break;
			case '101':
				$class = array( 'widget-large', false, 'widget-small' );
				break;
			case '011':
				$class = array( false, 'widget-medium', 'widget-medium' );
				break;
			default:
				$class = array();
		}

		// return to a global for later use
		$GLOBALS['wpf_widget_classes'] = $class;

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
					echo '<div class="' . esc_attr( $class ) . '">';
					dynamic_sidebar( 'sidebar-footer-' . $i );
					echo '</div>';
				}
				$i++;
			}
			echo '</section><!-- .footer-sidebar -->';
		}
	} // end wpf_print_footer_sidebar()
}