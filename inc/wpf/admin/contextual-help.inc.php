<?php
/**************************************************************************
 *    >CONTEXTUAL HELP TAB
 **************************************************************************/
/**
 * This will contain the contextual help tab's content.
 *
 *
 */
if ( ! function_exists( 'wpf_options_help_tab' ) ) {
	function wpf_options_help_tab() {

		$screen = get_current_screen();
		if ( $screen->id != $GLOBALS['wpf_options_page'] )
			return;

		switch ( $GLOBALS['active_tab'] ) {
			case 'general':
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
				break;

			case 'header':

				break;

			case 'footer':

				break;

			case 'contact':

				break;
		}

	} // end wpf_options_help_tab()
}