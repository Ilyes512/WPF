<?php
/**************************************************************************
 *    >CONTACT DEV SECTION
 **************************************************************************/


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


/**************************************************************************
 *    >CONTACT CONFIG SECTION
 **************************************************************************/


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