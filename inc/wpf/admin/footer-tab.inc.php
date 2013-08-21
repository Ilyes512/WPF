<?php
/**************************************************************************
 *    >FOOTER SECTION
 **************************************************************************/


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