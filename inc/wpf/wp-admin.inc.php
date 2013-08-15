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

		// Adds the contextual help
		add_action( "load-$wpf_options_page", 'wpf_options_help' );

		// Adds the javascript inline for the reset confirm dialog
		add_action( "admin_footer-$wpf_options_page", 'wpf_options_js' );

		// Find out what tab is active
		$GLOBALS['active_tab'] = ( isset( $_GET['tab'] ) && ( 'header' == $_GET['tab'] || 'footer' == $_GET['tab'] || 'contact' == $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

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
			'page_boxed',                   // $id
			__( 'Boxed page', 'wpf' ),      // $title
			'wpf_checkbox_option_display',  // $callback
			'wpf-options',                  // $page
			'general_layout_section',       // $section
			array(                          // $args
				'id'    => 'page_boxed',
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
			'menu_primary_fixed',          // $id
			__( 'Menu Fixed', 'wpf' ),     // $title
			'wpf_select_option_display',   // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'        => 'menu_primary_fixed',
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
			'menu_primary_location',       // $id
			__( 'Menu Location', 'wpf' ),  // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'    => 'menu_primary_location',
				'label' => __( 'Show the menu beneath the title area', 'wpf' ) . '<em>' . __( ' (Doesn\'t work when menu is fixed)', 'wpf' ) . '</em>'
			)
		);

		add_settings_field(
			'menu_primary_center',         // $id
			__( 'Center Menu', 'wpf' ),    // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'header_menu_section',         // $section
			array(                         // $args
				'id'    => 'menu_primary_center',
				'label' => __( 'Center the menu', 'wpf' )
			)
		);

		add_settings_field(
			'menu_primary_title',      // $id
			__( 'Menu Title', 'wpf' ), // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'header_menu_section',     // $section
			array(                     // $args
				'id'    => 'menu_primary_title'
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
			'header_show_title',             // $id
			__( 'Hide site title', 'wpf' ), // $title
			'wpf_checkbox_option_display',  // $callback
			'wpf-options',                  // $page
			'header_title_section',         // $section
			array(                          // $args
				'id'    => 'header_show_title',
				'label' => __( 'Hide the site title', 'wpf' )
			)
		);

		add_settings_field(
			'header_show_description',            // $id
			__( 'Hide site description', 'wpf' ), // $title
			'wpf_checkbox_option_display',        // $callback
			'wpf-options',                        // $page
			'header_title_section',               // $section
			array(                                // $args
				'id'    => 'header_show_description',
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

		// CONTACT OPTIONS TAB

		// contact_dev_section
		add_settings_section(
			'contact_dev_section',              // $id
			__( 'Contact Development', 'wpf' ), // $title
			null,                               // $callback
			'wpf-options'                       // $page
		);

		add_settings_field(
			'contact_setup',                        // $id
			__( 'Open/Close Contact Form', 'wpf' ), // $title
			'wpf_select_option_display',            // $callback
			'wpf-options',                          // $page
			'contact_dev_section',                  // $section
			array(                                  // $args
				'id'        => 'contact_setup',
				'options'   => array(
					array(
						'value'   => false,
						'label'   => __( 'Disabled', 'wpf' )
					),
					array(
						'value'   => true,
						'label'   => __( 'Enabled', 'wpf' )
					)
				)
			)
		);

		add_settings_field(
			'contact_debug',                     // $id
			__( 'PHPMailer Debug Mode', 'wpf' ), // $title
			'wpf_select_option_display',         // $callback
			'wpf-options',                       // $page
			'contact_dev_section',               // $section
			array(                               // $args
				'id'        => 'contact_debug',
				'options'   => array(
					array(
						'value'   => false,
						'label'   => __( 'Disabled', 'wpf' )
					),
					array(
						'value'   => true,
						'label'   => __( 'Enabled', 'wpf' )
					)
				)
			)
		);

		// contact_config_section
		add_settings_section(
			'contact_config_section',             // $id
			__( 'Contact Configuration', 'wpf' ), // $title
			null,                                 // $callback
			'wpf-options'                         // $page
		);

		add_settings_field(
			'contact_smtpauth',                 // $id
			__( 'SMTP authentication', 'wpf' ), // $title
			'wpf_select_option_display',        // $callback
			'wpf-options',                      // $page
			'contact_config_section',           // $section
			array(                              // $args
				'id'        => 'contact_smtpauth',
				'options'   => array(
					array(
						'value'   => false,
						'label'   => __( 'Disabled', 'wpf' )
					),
					array(
						'value'   => true,
						'label'   => __( 'Enabled', 'wpf' )
					)
				)
			)
		);

		add_settings_field(
			'contact_smtpsecure',         // $id
			__( 'SMTP security', 'wpf' ), // $title
			'wpf_select_option_display',  // $callback
			'wpf-options',                // $page
			'contact_config_section',     // $section
			array(                        // $args
				'id'        => 'contact_smtpsecure',
				'options'   => array(
					array(
						'value'   => 'ssl',
						'label'   => 'SSL'
					),
					array(
						'value'   => 'tls',
						'label'   => 'TLS'
					)
				)
			)
		);

		add_settings_field(
			'contact_username',        // $id
			__( 'Username', 'wpf' ),   // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_username'
			)
		);

		add_settings_field(
			'contact_password',        // $id
			__( 'Password', 'wpf' ),   // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_password'
			)
		);

		add_settings_field(
			'contact_host',            // $id
			__( 'Host', 'wpf' ),       // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_host'
			)
		);

		add_settings_field(
			'contact_port',            // $id
			__( 'Port', 'wpf' ),       // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_port'
			)
		);

		add_settings_field(
			'contact_charset',            // $id
			__( 'Character Set', 'wpf' ), // $title
			'wpf_text_option_display',    // $callback
			'wpf-options',                // $page
			'contact_config_section',     // $section
			array(                        // $args
				'id'    => 'contact_charset'
			)
		);

		add_settings_field(
			'contact_from',            // $id
			__( 'From', 'wpf' ),       // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_from'
			)
		);

		add_settings_field(
			'contact_fromname',        // $id
			__( 'From Name', 'wpf' ),  // $title
			'wpf_text_option_display', // $callback
			'wpf-options',             // $page
			'contact_config_section',  // $section
			array(                     // $args
				'id'    => 'contact_fromname'
			)
		);

		add_settings_field(
			'contact_html',                // $id
			__( 'HTML Mail', 'wpf' ),      // $title
			'wpf_checkbox_option_display', // $callback
			'wpf-options',                 // $page
			'contact_config_section',      // $section
			array(                         // $args
				'id'    => 'contact_html',
				'label' => __( 'Enable HTML Mail\'s', 'wpf' )
			)
		);

		register_setting(
			'contact_config_section', // $option_group
			'wpf_settings'            // $option_name
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

	 $options                  = array(
	 	// GENERAL TAB
	 	'page_boxed'                 => false,
	 	'sidebar_left'               => false,
	 	'show_author_info'           => true,

	 	// HEADER TAB
	 	'menu_primary_fixed'         => 'fixed',
	 	'menu_primary_location'      => false,
	 	'menu_primary_center'        => true,
	 	'menu_primary_title'         => get_bloginfo( 'name', 'display' ),
	 	'header_show_title'          => true,
	 	'header_show_description'    => true,

	 	// FOOTER TAB
	 	'footer_site_info'           => sprintf( __( '<p>&copy; %s Crafted on WPF<br><a href="https://github.com/MekZii/WPF" rel="nofollow" title="WPD - Wordpress Foundation">Visit the repository on github!</a></p>', 'wpf' ), date( 'Y' ) ),

	 	// CONTACT TAB
	 	'contact_setup'              => false,
	 	'contact_debug'              => true,
	 	'contact_smtpauth'           => true,
	 	'contact_smtpsecure'         => 'ssl',
	 	'contact_username'           => false,
	 	'contact_password'           => false,
	 	'contact_host'               => 'localhost',
	 	'contact_port'               => '25',
	 	'contact_charset'            => 'utf-8',
	 	'contact_from'               => false,
	 	'contact_fromname'           => false,
	 	'contact_html'               => true,

	 	// VERSION
	 	'wpf_version'                => WPF_VERSION,
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
		if ( ! empty( $inputs['reset-general'] ) || ! empty( $inputs['reset-header'] ) || ! empty( $inputs['reset-footer'] ) || ! empty( $inputs['reset-contact'] ) ) {
			return wpf_reset_options( $inputs );
		}

		$new_options = $GLOBALS['wpf_settings'];

		if ( ! empty( $inputs['submit-general'] ) ) {

			$new_options['page_boxed']               = isset( $inputs['page_boxed'] ) && $inputs['page_boxed'] ? true : false;
			$new_options['sidebar_left']             = isset( $inputs['sidebar_left'] ) && $inputs['sidebar_left'] ? true : false;

			$new_options['show_author_info']         = isset( $inputs['show_author_info'] ) && $inputs['show_author_info'] ? true : false;

		} elseif ( ! empty( $inputs['submit-header'] ) ) {

			if ( isset( $inputs['menu_primary_fixed'] ) ) {
				if ( 'fixed' == $inputs['menu_primary_fixed'] ) {
					$new_options['menu_primary_fixed'] = 'fixed';
				} elseif ( 'sticky-top-bar' == $inputs['menu_primary_fixed'] ) {
					$new_options['menu_primary_fixed'] = 'sticky-top-bar';
				} else {
					$new_options['menu_primary_fixed'] = false;
				}
			}

			$new_options['menu_primary_location']     = isset( $inputs['menu_primary_location'] ) && $inputs['menu_primary_location'] ? true : false;
			$new_options['menu_primary_center']       = isset( $inputs['menu_primary_center'] ) && $inputs['menu_primary_center'] ? true : false;
			$new_options['menu_primary_title']        = sanitize_text_field( $inputs['menu_primary_title'] );

			$new_options['header_show_title']          = isset( $inputs['header_show_title'] ) && $inputs['header_show_title'] ? true : false;
			$new_options['header_show_description']    = isset( $inputs['header_show_description'] ) && $inputs['header_show_description'] ? true : false;

		} elseif ( ! empty( $inputs['submit-footer'] ) ) {

			$new_options['footer_site_info']         = wpf_admin_textarea_validation ( $inputs['footer_site_info'] );

		} elseif ( ! empty( $inputs['submit-contact'] ) ) {

			$new_options['contact_debug']            = isset( $inputs['contact_debug'] ) && $inputs['contact_debug'] ? true : false;
	 		$new_options['contact_setup']            = isset( $inputs['contact_setup'] ) && $inputs['contact_setup'] ? true : false;

			$new_options['contact_smtpauth']         = isset( $inputs['contact_smtpauth'] ) && $inputs['contact_smtpauth'] ? true : false;
			$new_options['contact_smtpsecure']       = isset( $inputs['contact_smtpsecure'] ) && 'ssl' == $inputs['contact_smtpsecure'] ? 'ssl' : 'tls';
			$new_options['contact_username']         = sanitize_text_field( $inputs['contact_username'] );
			$new_options['contact_password']         = sanitize_text_field( $inputs['contact_password'] );
			$new_options['contact_host']             = sanitize_text_field( $inputs['contact_host'] );
			$new_options['contact_port']             = sanitize_text_field( $inputs['contact_port'] );
			$new_options['contact_charset']          = sanitize_text_field( $inputs['contact_charset'] );
			$new_options['contact_from']             = sanitize_text_field( $inputs['contact_from'] );
			$new_options['contact_fromname']         = sanitize_text_field( $inputs['contact_fromname'] );
			$new_options['contact_html']            = isset( $inputs['contact_html'] ) && $inputs['contact_html'] ? true : false;

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

			$new_options['page_boxed']               = $reset_options['page_boxed'];
			$new_options['sidebar_left']             = $reset_options['sidebar_left'];

			$new_options['show_author_info']         = $reset_options['show_author_info'];

		} elseif ( ! empty( $inputs['reset-header'] ) ) {

			$new_options['menu_primary_fixed']       = $reset_options['menu_primary_fixed'];
			$new_options['menu_primary_location']    = $reset_options['menu_primary_location'];
			$new_options['menu_primary_center']      = $reset_options['menu_primary_center'];
			$new_options['menu_primary_title']       = $reset_options['menu_primary_title'];

			$new_options['header_show_title']        = $reset_options['header_show_title'];
			$new_options['header_show_description']  = $reset_options['header_show_description'];

		} elseif ( ! empty( $inputs['reset-footer'] ) ) {

			$new_options['footer_site_info']         = $reset_options['footer_site_info'];

		} elseif ( ! empty( $inputs['reset-contact'] ) ) {

			$new_options['contact_setup']            = $reset_options['contact_setup'];
			$new_options['contact_debug']            = $reset_options['contact_debug'];

			$new_options['contact_smtpauth']         = $reset_options['contact_smtpauth'];
			$new_options['contact_smtpsecure']       = $reset_options['contact_smtpsecure'];
			$new_options['contact_username']         = $reset_options['contact_username'];
			$new_options['contact_password']         = $reset_options['contact_password'];
			$new_options['contact_host']             = $reset_options['contact_host'];
			$new_options['contact_port']             = $reset_options['contact_port'];
			$new_options['contact_charset']          = $reset_options['contact_charset'];
			$new_options['contact_from']             = $reset_options['contact_from'];
			$new_options['contact_fromname']         = $reset_options['contact_fromname'];
			$new_options['contact_html']             = $reset_options['contact_html'];

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
	$id = '#reset-' . $GLOBALS['active_tab'];
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('<?php echo $id; ?>').click(function() {
				if (!confirm('<?php _e( 'Are you sure you want to reset this options page?', 'wpf' ); ?>')) {
					return false;
				}
			});
		});
	</script>
	<?php
	} // end wpf_options_js()
}


/**************************************************************************
 *    >Display Callback's
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
				<a href="?page=wpf-options&tab=contact" class="nav-tab <?php echo 'contact' == $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Contact Options', 'wpf' ); ?></a>
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
					<?php _e( 'Hide the title and/or the description of the site', 'wpf' ); ?>
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
			<?php elseif ( 'contact' == $active_tab ) : ?>
				<form method="post" action="options.php">
					<?php settings_fields( 'contact_dev_section' ); ?>
					<?php settings_fields( 'contact_config_section' ); ?>

					<h3><?php _e( 'Contact Development', 'wpf' ); ?></h3>
					<?php _e( 'You can disable the Contact Form or/and put it in Debug Mode', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'contact_dev_section' ); ?>
					<?php echo '</table>'; ?>

					<h3><?php _e( 'Contact Configuration', 'wpf' ); ?></h3>
					<?php _e( 'Fill in these options to use the WPF Contact Template', 'wpf' ); ?>
					<?php echo '<table class="form-table">'; ?>
					<?php do_settings_fields( 'wpf-options', 'contact_config_section' ); ?>
					<?php echo '</table>'; ?>

					<p class="submit">
						<?php submit_button( __( 'Save Changes', 'wpf' ), 'primary', 'wpf_settings[submit-contact]', false ); ?>
						<?php submit_button( __( 'Reset Values', 'wpf' ), 'delete', 'wpf_settings[reset-contact]', false, array( 'id' => 'reset-contact' ) ); ?>
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