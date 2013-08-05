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


/**************************************************************************
 *    >Menu's
 **************************************************************************/


add_action( 'admin_menu', 'wpf_add_options_page' );
/*
 * Add a theme options menu page in the admin area
 *
 *
 */
if ( ! function_exists( 'wpf_add_options_page' ) ) {
	function wpf_add_options_page() {

		global $wpf_options_page;
		// Add a menu page under appearance
		$wpf_options_page = add_theme_page(
			__( 'WPF Theme Options' , 'wpf' ), // $page_title
			__( 'WPF Options', 'wpf' ),        // $menu_title
			'edit_theme_options',              // $capability
			'wpf-options',                     // $menu_slug
			'wpf_options_display'              // $callback
		);
		add_action( "load-$wpf_options_page", 'wpf_options_help' );
		add_action( "admin_footer-$wpf_options_page", 'wpf_options_js' );

		$GLOBALS['active_tab'] = ( isset( $_GET['tab'] ) && ( 'header' == $_GET['tab'] || 'footer' == $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

	} // end wpf_add_options_page()
}


/**************************************************************************
 *    >Sections, Settings, and Fields
 **************************************************************************/


add_action( 'admin_init', 'wpf_create_options' );
/*
 * Create the option sections
 *
 *
 */
if ( ! function_exists( 'wpf_create_options' ) ) {
	function wpf_create_options() {

		// GENERAL OPTIONS TAB

		// general_layout_section
		add_settings_section(
			'general_layout_section',      // $id
			__( 'Layout Options', 'wpf' ), // $title
			null,                          // $callback
			'wpf-options'                  // $page
		);

		add_settings_field(
			'boxed_page',                   // $id
			__( 'Boxed page', 'wpf' ),      // $title
			'wpf_checkbox_option_display',  // $callback
			'wpf-options',                  // $page
			'general_layout_section',       // $section
			array(                          // $args
				'id'    => 'boxed_page',
				'label' => __( 'Show the pages boxed', 'wpf' )
			)
		);

		add_settings_field(
			'sidebar_left',                // $id
			__( 'Sidebar left', 'wpf' ),   // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'general_layout_section',      // $section
			array(                         // $args
				'id'    => 'sidebar_left',
				'label' => __( 'Show the sidebar to the left', 'wpf' )
			)
		);

		register_setting(
			'general_layout_section', // $option_group
			'wpf_settings',           // $option_name
			'wpf_options_validation'  // $sanitize_callback
		);

		// general_post_section
		add_settings_section(
			'general_post_section',      // $id
			__( 'Post Options', 'wpf' ), // $title
			null,                        // $callback
			'wpf-options'                // $page
		);

		add_settings_field(
			'show_author_info',              // $id
			__( 'Show author info', 'wpf' ), // $title
			'wpf_checkbox_option_display',   // $callback
			'wpf-options',                   // $page
			'general_post_section',          // $section
			array(                           // $args
				'id'    => 'show_author_info',
				'label' => __( 'Show the author info on the post page', 'wpf' )
			)
		);

		register_setting(
			'general_post_section', // $option_group
			'wpf_settings'          // $option_name
		);

		// HEADER OPTIONS TAB

		// header_menu_section
		add_settings_section(
			'header_menu_section',       // $id
			__( 'Menu Options', 'wpf' ), // $title
			null,                        // $callback
			'wpf-options'                // $page
		);

		add_settings_field(
			'primarymenu_fixed',           // $id
			__( 'Menu Fixed', 'wpf' ),     // $title
			'wpf_select_option_display',   // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'        => 'primarymenu_fixed',
				'options'   => array(
					array(
						'value'   => false,
						'label'   => __( 'None', 'wpf' )
					),
					array(
						'value'   => 'fixed',
						'label'   => __( 'Fixed', 'wpf' )
					),
					array(
						'value'   => 'sticky-top-bar',
						'label'   => __( 'Sticky', 'wpf' )
					)
				)
			)
		);

		add_settings_field(
			'primarymenu_location',        // $id
			__( 'Menu Location', 'wpf' ),  // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'    => 'primarymenu_location',
				'label' => __( 'Show the menu beneath the title area', 'wpf' ) . '<em>' . __( ' (Doesn\'t work when menu is fixed)', 'wpf' ) . '</em>'
			)
		);

		add_settings_field(
			'primarymenu_center',          // $id
			__( 'Center Menu', 'wpf' ),    // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'    => 'primarymenu_center',
				'label' => __( 'Center the menu', 'wpf' )
			)
		);

		add_settings_field(
			'primarymenu_title',       // $id
			__( 'Menu Title', 'wpf' ), // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'header_menu_section',     // $section
			array(                     // $args
				'id'    => 'primarymenu_title'
			)
		);

		register_setting(
			'header_menu_section', // $option_group
			'wpf_settings'         // $option_name
		);

		// header_title_section
		add_settings_section(
			'header_title_section',       // $id
			__( 'Title Options', 'wpf' ), // $title
			null,                         // $callback
			'wpf-options'                 // $page
		);

		add_settings_field(
			'hide_site_title',              // $id
			__( 'Hide site title', 'wpf' ), // $title
			'wpf_checkbox_option_display',  // $callback
			'wpf-options',                  // $page
			'header_title_section',         // $section
			array(                          // $args
				'id'    => 'hide_site_title',
				'label' => __( 'Hide the site title', 'wpf' )
			)
		);

		add_settings_field(
			'hide_site_description',              // $id
			__( 'Hide site description', 'wpf' ), // $title
			'wpf_checkbox_option_display',        // $callback
			'wpf-options',                        // $page
			'header_title_section',               // $section
			array(                                // $args
				'id'    => 'hide_site_description',
				'label' => __( 'Hide the site description', 'wpf' )
			)
		);

		register_setting(
			'header_title_section', // $option_group
			'wpf_settings'          // $option_name
		);

		// FOOTER OPTIONS TAB
		add_settings_section(
			'footer_section',              // $id
			__( 'Footer Options', 'wpf' ), // $title
			null,                          // $callback
			'wpf-options'                  // $page
		);

		add_settings_field(
			'footer_site_info',            // $id
			__( 'Site info', 'wpf' ),      // $title
			'wpf_textarea_option_display', // $callback
			'wpf-options',                 // $page
			'footer_section',              // $section
			array(                         // $args
				'id' => 'footer_site_info'
			)
		);

		register_setting(
			'footer_section', // $option_group
			'wpf_settings'    // $option_name
		);

	} // end wpf_create_options()
}


/**************************************************************************
 *    >Helper functions
 **************************************************************************/


/**
 * This function contain's the default options array.
 *
 *
 */
 if ( ! function_exists( 'wpf_get_default_options' ) ) {
	 function wpf_get_default_options() {

	 $options                          = array(
	 	'boxed_page'                     => false,
	 	'sidebar_left'                   => false,
	 	'show_author_info'               => true,

	 	'primarymenu_fixed'              => 'fixed',
	 	'primarymenu_location'           => false,
	 	'primarymenu_center'    => true,
	 	'primarymenu_title'              => get_bloginfo( 'name', 'display' ),
	 	'hide_site_title'                => false,
	 	'hide_site_description'          => false,


	 	'footer_site_info'               => sprintf( __( '<p>&copy; %s Crafted on WPF<br><a href="https://github.com/MekZii/WPF" rel="nofollow" title="WPD - Wordpress Foundation">Visit the repository on github!</a></p>', 'wpf' ), date( 'Y' ) ),
	 	'wpf_version'                    => WPF_VERSION
	 );

	 return $options;

	 } // end wpf_get_default_options()
 }

/**
 * This function is the validation function for register_setting().
 *
 * This function is the validation function for register_setting() and will save
 * the options into the options array.
 */
 if ( ! function_exists( 'wpf_options_validation' ) ) {
	function wpf_options_validation( $inputs ) {
		// If a reset button is clicked then continue with the function that wil
		// handle the reset.
		if ( ! empty( $inputs['reset-general'] ) || ! empty( $inputs['reset-header'] ) || ! empty( $inputs['reset-footer'] ) ) {
			return wpf_reset_options( $inputs );
		}

		$new_options = $GLOBALS['wpf_settings'];

		if ( ! empty( $inputs['submit-general'] ) ) {

			$new_options['boxed_page']               = isset( $inputs['boxed_page'] ) && $inputs['boxed_page'] ? true : false;
			$new_options['sidebar_left']             = isset( $inputs['sidebar_left'] ) && $inputs['sidebar_left'] ? true : false;

			$new_options['show_author_info']         = isset( $inputs['show_author_info'] ) && $inputs['show_author_info'] ? true : false;

		} elseif ( ! empty( $inputs['submit-header'] ) ) {

			if ( isset( $inputs['primarymenu_fixed'] ) ) {
				if ( 'fixed' == $inputs['primarymenu_fixed'] ) {
					$new_options['primarymenu_fixed'] = 'fixed';
				} elseif ( 'sticky-top-bar' == $inputs['primarymenu_fixed'] ) {
					$new_options['primarymenu_fixed'] = 'sticky-top-bar';
				} else {
					$new_options['primarymenu_fixed'] = false;
				}
			}

			$new_options['primarymenu_location']     = isset( $inputs['primarymenu_location'] ) && $inputs['primarymenu_location'] ? true : false;
			$new_options['primarymenu_center']       = isset( $inputs['primarymenu_center'] ) && $inputs['primarymenu_center'] ? true : false;
			$new_options['primarymenu_title']        = $inputs['primarymenu_title'];

			$new_options['hide_site_title']          = isset( $inputs['hide_site_title'] ) && $inputs['hide_site_title'] ? true : false;
			$new_options['hide_site_description']    = isset( $inputs['hide_site_description'] ) && $inputs['hide_site_description'] ? true : false;

		} elseif ( ! empty( $inputs['submit-footer'] ) ) {

			$new_options['footer_site_info']         = wpf_admin_textarea_validation ( $inputs['footer_site_info'] );

		}

		return $new_options;
	} // end wpf_options_validation()
}

/**
 * This function will take care of resetting the options.
 *
 * This function will only be called inside wpf_options_validation() function.
 */
if ( ! function_exists( 'wpf_reset_options' ) ) {
	function wpf_reset_options( $inputs ) {
		$new_options      = $GLOBALS['wpf_settings'];
		$reset_options    = wpf_get_default_options();

		if ( ! empty( $inputs['reset-general'] ) ) {

			$new_options['boxed_page']                   = $reset_options['boxed_page'];
			$new_options['sidebar_left']                 = $reset_options['sidebar_left'];

			$new_options['show_author_info']             = $reset_options['show_author_info'];

		} elseif ( ! empty( $inputs['reset-header'] ) ) {

			$new_options['primarymenu_fixed']            = $reset_options['primarymenu_fixed'];
			$new_options['primarymenu_location']         = $reset_options['primarymenu_location'];
			$new_options['primarymenu_center']           = $reset_options['primarymenu_center'];
			$new_options['primarymenu_title']            = $reset_options['primarymenu_title'];

			$new_options['hide_site_title']              = $reset_options['hide_site_title'];
			$new_options['hide_site_description']        = $reset_options['hide_site_description'];

		} elseif ( ! empty( $inputs['reset-footer'] ) ) {

			$new_options['footer_site_info']             = $reset_options['footer_site_info'];

		}

		return $new_options;
	} // end wpf_reset_options()
}

/**
 * This is a helper function for validating textarea wish allows some html-tags.
 *
 *
 */
if ( ! function_exists( 'wpf_admin_textarea_validation' ) ) {
	function wpf_admin_textarea_validation( $string ) {

		$default_allowed_attr = array(
			'class'              => array(),
			'id'                 => array()
		);

		$allowed_html = array(
			'p'          => $default_allowed_attr,
			'br'         => $default_allowed_attr,
			'span'       => $default_allowed_attr,
			'em'         => $default_allowed_attr,
			'i'          => $default_allowed_attr,
			'hr'         => $default_allowed_attr,
			'h1'         => $default_allowed_attr,
			'h2'         => $default_allowed_attr,
			'h3'         => $default_allowed_attr,
			'h4'         => $default_allowed_attr,
			'h5'         => $default_allowed_attr,
			'h6'         => $default_allowed_attr,
			'div'        => $default_allowed_attr,
			'strong'     => $default_allowed_attr,
			'img'        => array(
				'class'     => array(),
				'id'        => array(),
				'alt'       => array(),
				'src'       => array(),
				'height'    => array(),
				'width'     => array()
			),
			'a'          => array(
				'class'     => array(),
				'id'        => array(),
				'href'      => array(),
				'rel'       => array(),
				'title'     => array()
			)
		);

		// String needs to be stripped of slasshes for wp_kses().
		$string = get_magic_quotes_gpc() ? stripslashes( $string ) : $string;

		// Strip everything except for the allowed html tag's and the combining attributes. Also strip unknown protocol's
		// See http://codex.wordpress.org/Function_Reference/wp_kses for more info
		$new_string = wp_kses( $string , $allowed_html );

		return $new_string;
	} // end wpf_admin_textarea_validation()
}

if ( ! function_exists( 'wpf_options_js' ) ) {
	function wpf_options_js() {
	$id = isset ( $_GET['tab'] ) ? '#reset-' . $_GET['tab'] : '#reset-general';

	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('<?php echo $id; ?>').click(function() {
				if (!confirm('<?php _e( 'Are you sure you want to reset this options page?', 'wpf' ); ?>')) {
					console.log('true');
					return false;
				}
			});
		});
	</script>
	<?php
	} // end wpf_options_js()
}


/**************************************************************************
 *    >Callback's
 **************************************************************************/


if ( ! function_exists( 'wpf_options_display' ) ) {
	function wpf_options_display() {

		global $active_tab;
	?>
		<div class="wrap">

			<div id="icon-themes" class="icon32"><br></div>
			<h2 class="nav-tab-wrapper">
				<a href="?page=wpf-options&tab=general" class="nav-tab <?php echo 'general' == $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'wpf' ); ?></a>
				<a href="?page=wpf-options&tab=header" class="nav-tab <?php echo 'header' == $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Header Options', 'wpf' ); ?></a>
				<a href="?page=wpf-options&tab=footer" class="nav-tab <?php echo 'footer' == $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Footer Options', 'wpf' ); ?></a>
			</h2>

			<?php settings_errors(); ?>

			<?php if ( 'general' == $active_tab ) : ?>
				<br />
				<img class="align-center" src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="" title="" />
				<p>
					<?php _e( 'This is the general settings page for the WPF Theme. Go trough the tabmenu\'s and adjust any of the specific elements of the theme.', 'wpf' ); ?>
					<?php _e( 'Current version: ', 'wpf' ); echo $GLOBALS['wpf_settings']['wpf_version']; ?>
				</p>

				<form method="post" action="options.php">
					<?php settings_fields( 'general_layout_section' ); ?>
					<?php settings_fields( 'general_post_section' ); ?>

					<h3><?php _e( 'Layout Options', 'wpf' ); ?></h3>
					<?php _e( 'Make some subtile changes to the layout', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'general_layout_section' ); ?>
					<?php echo '</table>'; ?>

					<h3><?php _e( 'Post Options', 'wpf' ); ?></h3>
					<?php _e( 'Make some subtile changes to the post pages', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'general_post_section' ); ?>
					<?php echo '</table>'; ?>

					<p class="submit">
						<?php submit_button( __( 'Save Changes', 'wpf' ), 'primary', 'wpf_settings[submit-general]', false ); ?>
						<?php submit_button( __( 'Reset Values', 'wpf' ), 'reset', 'wpf_settings[reset-general]', false, array( 'id' => 'reset-general' ) ); ?>
					</p>

				</form>
			<?php elseif ( 'header' == $active_tab ) : ?>
				<form method="post" action="options.php">
					<?php settings_fields( 'header_menu_section' ); ?>
					<?php settings_fields( 'header_title_section' ); ?>

					<h3><?php _e( 'Menu options', 'wpf' ); ?></h3>
					<?php _e( 'Make the menu fixed and/or contained-to-grid', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'header_menu_section' ); ?>
					<?php echo '</table>'; ?>

					<h3><?php _e( 'Title &amp; description', 'wpf' ); ?></h3>
					<?php _e( 'Hide the title and/or the description', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'header_title_section' ); ?>
					<?php echo '</table>'; ?>

					<p class="submit">
						<?php submit_button( __( 'Save Changes', 'wpf' ), 'primary', 'wpf_settings[submit-header]', false ); ?>
						<?php submit_button( __( 'Reset Values', 'wpf' ), 'delete', 'wpf_settings[reset-header]', false, array( 'id' => 'reset-header' ) ); ?>
					</p>
				</form>
			<?php elseif ( 'footer' == $active_tab ) : ?>
				<form method="post" action="options.php">
					<?php settings_fields( 'footer_section' ); ?>

					<h3><?php _e( 'Colophon', 'wpf' ); ?></h3>
					<?php _e( 'The &copy; copyright text in the footer', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'footer_section' ); ?>
					<?php echo '</table>'; ?>

					<p class="submit">
						<?php submit_button( __( 'Save Changes', 'wpf' ), 'primary', 'wpf_settings[submit-footer]', false ); ?>
						<?php submit_button( __( 'Reset Values', 'wpf' ), 'delete', 'wpf_settings[reset-footer]', false, array( 'id' => 'reset-footer' ) ); ?>
					</p>
				</form>
			<?php endif; ?>

		</div><!-- .wrap -->
	<?php
	} // end wpf_options_display()
}

if ( ! function_exists( 'wpf_checkbox_option_display' ) ) {
	function wpf_checkbox_option_display( $args ) {

		extract( $args );

		$html     = '<label for="wpf_settings[' . $id . ']">';
			$html   .= '<input type="checkbox" name="wpf_settings[' . $id . ']" id="wpf_settings[' . $id . ']" value="1" ' . checked( 1, $GLOBALS['wpf_settings'][$id], false ) . ' />';
			$html   .= '&nbsp;';
			$html   .= $label;
		$html    .= '</label>';

		echo $html;

	} // end wpf_checkbox_option_display()
}

if ( ! function_exists( 'wpf_textarea_option_display' ) ) {
	function wpf_textarea_option_display( $args ) {

		extract( $args );
		if ( ! isset( $cols ) ) $cols = 100;
		if ( ! isset( $rows ) ) $rows = 10;

		echo '<textarea name="wpf_settings[' . $id . ']" id="wpf_settings[' . $id . ']" cols="' . $cols . '" rows="' . $rows . '">' . esc_textarea( $GLOBALS['wpf_settings'][$id] ) .'</textarea>';
	} // end wpf_textarea_option_display()
}

if ( ! function_exists( 'wpf_select_option_display' ) ) {
	function wpf_select_option_display( $args ) {

		extract( $args );

		$html = '<select name="wpf_settings[' . $id . ']" id="wpf_settings[' . $id . ']">';
			foreach ( $options as $option ) {
				$html .= '<option value="' . $option['value'] . '"' . selected( $GLOBALS['wpf_settings'][$id], $option['value'], false ) . '>' . $option['label'] . '</option>';
			}
		$html .= '</select>';

		echo $html;

	} // end wpf_select_option_display()
}

if ( ! function_exists( 'wpf_text_option_display' ) ) {
	function wpf_text_option_display( $args ) {

		extract( $args );
		if ( ! isset( $size ) ) $size = 50;
		$placeholder = isset( $placeholder ) ? ' placeholder="' . $placeholder . '"' : '';

		echo '<input type="text" name="wpf_settings[' . $id . ']" id="wpf_settings[' . $id . ']" value="' . esc_attr( $GLOBALS['wpf_settings'][$id] ) . '" size="' . $size . '"' . $placeholder . ' />';

	} // end wpf_text_option_display()
}


/**************************************************************************
 *    >Options Help
 **************************************************************************/


if ( ! function_exists( 'wpf_options_help' ) ) {
	function wpf_options_help() {

		global $active_tab;

		$screen = get_current_screen();
		if ( $screen->id != $GLOBALS['wpf_options_page'] )
			return;

		if ( 'general' == $active_tab ) {

			$screen->add_help_tab( array(
				'id'         => 'wpf-help-general-layout',
				'title'      => __( 'Layout Options', 'wpf' ),
				'content'    => __( '<h5>Testing</h5><p>This is some general input<p>' , 'wpf' )
			) );

			$screen->add_help_tab( array(
				'id'         => 'wpf-help-general-post',
				'title'      => __( 'Post Options', 'wpf' ),
				'content'    => __( '<p>This is some general input</p>' , 'wpf' )
			) );

		} elseif ( 'header' == $active_tab ) {

		} elseif ( 'footer' == $active_tab ) {

		}


	} // end wpf_options_help()
}