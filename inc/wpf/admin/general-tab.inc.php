<?php
/**************************************************************************
 *    >GENERAL LAYOUT SECTION
 **************************************************************************/


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


/**************************************************************************
 *    >GENERAL POST SECTION
 **************************************************************************/


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