<?php
/**************************************************************************
 *    >CLEANING
 **************************************************************************/


/**
 * Cleaning up the head
 *
 *
 */
if ( ! function_exists( 'wpf_head_cleanup' ) ) {
	function wpf_head_cleanup() {
		// Remove the links to the extra feeds such as category feeds.
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		// Remove the (EditUri) link to the Really Simple Discovery service endpoint.
		remove_action( 'wp_head', 'rsd_link' );

		// Remove the link to the Windows Live Writer manifest file.
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Remove relational links (previous and next) for the posts adjacent to the current post.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

		// Remove the generator meta information
		remove_action( 'wp_head', 'wp_generator' );

		// Remove the rel shortlink-link
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	} // end head_cleanup()
}

/**
 * Remove injected CSS from recent comments widget
 *
 *
 */
if ( ! function_exists( 'wpf_remove_recent_comments_style' ) ) {
	function wpf_remove_recent_comments_style() {
		global $wp_widget_factory;
		if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
	} // end wpf_remove_recent_comments_style()
}

/**
 * Produce a more semantic output for img-caption's.
 *
 * @todo check if wp 3.6 produce a (more) semantic caption
 */
if ( ! function_exists( 'wpf_caption_shortcode' ) ) {
	function wpf_caption_shortcode( $output, $attr, $content ) {

		// We're not worried about captions in feeds, so just return the output here.
		if ( is_feed() ) return $output;

		// Set up the default arguments.
		$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
		);

		// Merge the defaults with user input and extract.
		$attr = extract( shortcode_atts( $defaults, $attr ) );

		// If the width is less than 1 or there is no caption, return the content
		// wrapped between the [caption]< tags.
		if ( 1 > $width || empty( $caption ) )
			return $content;

		// Open the caption <div> including the needed classes.
		$output = '<figure class="wp-caption ' . esc_attr( $align ) . '">';

		// Allow shortcodes for the content the caption was created for.
		$output .= do_shortcode( $content );

		// Append the caption text.
		$output .= '<figcaption>' . $caption . '</figcaption>';

		// Close the caption </div>.
		$output .= '</figure>';

		// Return the formatted, clean caption.
		return $output;

	} // end wpf_caption_shortcode()
}