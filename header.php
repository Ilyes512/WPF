<?php
/**
 * The header for the WPF theme.
 *
 *
 */

// Add classes to body that are needed to style the theme
$body_class = ( is_admin_bar_showing() ) ? 'wp-toolbar antialiased' : 'antialiased';

?><!DOCTYPE html>
<?php wpf_dev( 'header.php' ); ?>
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>

<meta name="viewport" content="width=device-width">
<link rel="shortcut icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png">
<?php wp_head(); ?>
</head>

<body <?php body_class( $body_class ); ?>>

<div id="page" class="hfeed site<?php echo $GLOBALS['wpf_settings']['boxed_page'] ? ' boxed' : ''; echo $GLOBALS['wpf_settings']['sidebar_left'] ? ' sidebar_left' : ''; ?>" role="document">

	<header id="masthead" class="site-header" role="banner">
		<?php if ( function_exists( 'wpf_primarymenu_display') && ! $GLOBALS['wpf_settings']['primarymenu_location'] ) echo wpf_primarymenu_display(); ?>
<!--[if lt IE 7]>
<p class="chromeframe"><?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.' , 'wpf' ); ?></p>
<![endif]-->
		<?php if ( ! $GLOBALS['wpf_settings']['hide_site_title'] ) : ?>
			<div class="site-meta">
				<section>
					<h1 class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						<?php if ( ! $GLOBALS['wpf_settings']['hide_site_description'] ) : ?>
							<p class="site-description"><?php if ( function_exists( 'wpf_site_description' ) ) wpf_site_description(); ?></p>
						<?php endif; ?>
					</h1>
				</section>
			</div><!-- .site-meta -->
		<?php endif; ?>
		<?php if ( function_exists( 'wpf_primarymenu_display' ) && $GLOBALS['wpf_settings']['primarymenu_location'] ) echo wpf_primarymenu_display(); ?>
		<hr>
	</header><!-- #masthead -->
<?php wpf_dev( 'end header.php' ); ?>