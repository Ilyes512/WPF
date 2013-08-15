<?php
/**************************************************************************
 *    >MAIL
 **************************************************************************/


/**
 * This is the logic for template tpl-contact.php for the WordPress WPF theme
 *
 *
 */

global $wpf_c, $wpf_c_values, $wpf_settings;

/*
// Adjust these settings if you are using tpl-contact.php
// At the moment I only tested SMTP. Send me a mail if you want to use another method.
$wpf_c_settings = array(
	// enable SMTP authentication
	'SMTPAuth'   => true,
	// options are "ssl" or "tls"
	'SMTPSecure' => 'YOUR_SMTP_SECURITY',
	// your username (can equal your email adress)
	'Username'   => 'YOUR_USERNAME',
	// the password for your emailaccount
	'Password'   => 'YOUR_PASSWORD',
	// default is "localhost"
	'Host'       => 'YOUR_EMAIL_HOST',
	// default is port "25"
	'Port'       => 'YOUR_EMAIL_HOST_PORT',
	// the CharSet for your email
	'CharSet'    => 'utf-8',
	// the email adres you are using to send the mails
	'From'       => 'YOUR_EMAIL',
	// the name you want to attach to your email
	'FromName'   => 'YOUR_NAME',
	// set to false if your not sending html mails.
	'html'       => true,
	// set this to either false/true to show/hide phpmailer debug information
	'debug'      => WPF_DEV_MODE, // see functions.php

	// set this to true if you are done with the above settings
	'setup'      => false,
);
*/

//------------------------------------------------------^^^-CONFIG-^^^------------------------------------------------------------------------------------------

$wpf_c = array(
	// these keynames equal the $_POST input:
	'c_name'      => array( 'msg' => null, 'class' => null ),
	'c_email'     => array( 'msg' => null, 'class' => null ),
	'c_subject'   => array( 'msg' => null, 'class' => null ),
	'c_content'   => array( 'msg' => null, 'class' => null ),
	'c_feedback'  => array( 'msg' => null, 'class' => null ),

	// when a error is found this will be set to 1
	'errors'      => 0,
	// wheter to show the contact form or not
	'show_form'   => 0,
	// the error class that is used when there is a error
	'error_class' => ' class="error"',
);

// Check if the settings have been set.
if( $wpf_settings['contact_setup'] ) {
	$wpf_c['show_form']    = 1;
	$wpf_c['wpf_c_set']    = 1;
} else {
	// If the current user is an administrator, then warn him about the settings.
	// If the user is not then show a msg that the contact form is not ready.
	if ( current_user_can( 'edit_theme_options' ) ) {
		$wpf_c['c_feedback']['msg']   = sprintf(
			__( 'Please go to the %s to configure this page!', 'wpf' ),
			'<a href="' . admin_url( 'themes.php?page=wpf-options&tab=contact' ) . '" >' . __( 'WPF Contact Options', 'wpf' ) . '</a>'
		);
		//__( 'Please go to the WPF Options page to configure this: ', 'wpf' );
		$wpf_c['c_feedback']['class'] = 'alert';
	} else {
		$wpf_c['c_feedback']['msg']   = __( 'Please try again later! The contact form hasn\'t been setup yet.', 'wpf' );
		$wpf_c['c_feedback']['class'] = 'secondary';
	}
}

// check if the settings are done and if the form has been filled in
if ( $wpf_settings['contact_setup'] && isset( $_POST['wpf_contact_send'] ) ) {
	// Remove the automatically added slashes
	$wpf_c_values = array_map('stripslashes_deep', $_POST);

	// Sanitize the inputs
	// If the sanitized-value is not the same as the $_POST-value then return `null`
	$wpf_c_values['c_name']        = sanitize_text_field( $wpf_c_values['c_name'] ) == $_POST['c_name'] ? sanitize_text_field( $wpf_c_values['c_name'] ) : null;
	$wpf_c_values['c_email']       = sanitize_email( $wpf_c_values['c_email'] ) == $_POST['c_email'] ? sanitize_email( $wpf_c_values['c_email'] ) : null;
	$wpf_c_values['c_subject']     = sanitize_text_field( $wpf_c_values['c_subject'] ) ? sanitize_text_field( $wpf_c_values['c_subject'] ) : null;
	$wpf_c_values['c_content']     = trim( esc_textarea( $wpf_c_values['c_content'] ) );

	if ( empty( $wpf_c_values['c_name'] ) || strlen( $wpf_c_values['c_name'] > 256 ) ) {
		$wpf_c['c_name']['msg']       = __( 'Please fill in a name!', 'wpf' );
		$wpf_c['c_name']['class']     = $wpf_c['error_class'];
		$wpf_c['errors']              = 1;
	}
	if ( ! is_email( $_POST['c_email'] ) || strlen( $wpf_c_values['c_email'] > 256 ) ) {
		$wpf_c['c_email']['msg']      = __( 'This email seems to be incorrect!', 'wpf' );
		$wpf_c['c_email']['class']    = $wpf_c['error_class'];
		$wpf_c['errors']              = 1;
	}
	if ( empty( $wpf_c_values['c_subject'] ) || strlen( $wpf_c_values['c_subject'] ) > 256 ) {
		$wpf_c['c_subject']['msg']    = __( 'Please fill in a subject!', 'wpf' );
		$wpf_c['c_subject']['class']  = $wpf_c['error_class'];
		$wpf_c['errors']              = 1;
	}
	if ( empty( $wpf_c_values['c_content'] ) || strlen( $wpf_c_values['c_content'] ) > 32000 ) {
		$wpf_c['c_content']['msg']    = __( 'Please let us know what you want to say or ask!', 'wpf' );
		$wpf_c['c_content']['class']  = $wpf_c['error_class'];
		$wpf_c['errors']              = 1;
	}

	if ( $wpf_c['errors'] ) {
		$wpf_c['c_feedback']['msg']   = __ ( 'There seem to be 1 or multiple errors. Please check the individual messages below.', 'wpf' );
		$wpf_c['c_feedback']['class'] = 'alert';
	} else {
		// Include the phpmailer classes
		require_once ABSPATH . WPINC . '/class-phpmailer.php';
		require_once ABSPATH . WPINC . '/class-smtp.php';

		$phpmailer = new PHPMailer();
		$phpmailer->IsSMTP();

		// The value WPF_DEV_MODE is defined by wether WP_DEBUG is set to true or false. See functions.php
		$phpmailer->SMTPDebug     = $wpf_settings['contact_debug'];

		// Mail Config
		$phpmailer->SMTPAuth      = $wpf_settings['contact_smtpauth'];
		$phpmailer->SMTPSecure    = $wpf_settings['contact_smtpsecure'];
		$phpmailer->Username      = $wpf_settings['contact_username'];
		$phpmailer->Password      = $wpf_settings['contact_password'];
		$phpmailer->Host          = $wpf_settings['contact_host'];
		$phpmailer->Port          = $wpf_settings['contact_port'];

		// Mail headers and content
		$phpmailer->AddAddress(     $wpf_settings['contact_from'],  $wpf_settings['contact_fromname'] );
		$phpmailer->SetFrom(        $wpf_c_values['c_email'], $wpf_c_values['c_name'] );
		$phpmailer->AddReplyTo(     $wpf_c_values['c_email'], $wpf_c_values['c_name'] );

		$phpmailer->CharSet       = $wpf_settings['contact_charset'];
		$phpmailer->Subject       = $wpf_c_values['c_subject'];

		// Retrieve the template and replace the template variables
		if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/inc/wpf/email_template/contact.tpl' ) ) {
			$message = file_get_contents( get_stylesheet_directory() . '/inc/wpf/email_template/contact.tpl' );
		} else {
			$message = file_get_contents( get_template_directory() . '/inc/wpf/email_template/contact.tpl' );
		}
		$message = str_replace( '%name%',    $wpf_c_values['c_name'],    $message );
		$message = str_replace( '%email%',   $wpf_c_values['c_email'],   $message );
		$message = str_replace( '%subject%', $wpf_c_values['c_subject'], $message );
		$message = str_replace( '%content%', $wpf_c_values['c_content'], $message );

		if ( $wpf_settings['contact_html'] ) {
			$phpmailer->AltBody    = __( 'To view the message, please use an HTML compatible email viewer!', 'wpf');
			$phpmailer->MsgHTML($message);
		} else {
			$phpmailer->Body = $wpf_c_values['c_content'];
		}

		// Start sending the mail
		if ( $phpmailer->Send() ) {
			$wpf_c['c_feedback']['msg']   = __( 'Mail sended! We will contact you as soon as possible!', 'wpf' );
			$wpf_c['c_feedback']['class'] = 'succes';
			$wpf_c['show_form']           = 0;
		} else {
			$wpf_c['c_feedback']['msg']   = __( 'Something went wrong sending the mail! Try again or come back later.', 'wpf' );
			$wpf_c['c_feedback']['class'] = 'alert';
			$wpf_c['show_form']           = 0;
		}
	}
}
?>