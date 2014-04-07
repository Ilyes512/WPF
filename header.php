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

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png">
<?php wp_head(); ?>
</head>

<body <?php body_class( $body_class ); ?>>

<div id="page" class="hfeed site<?php echo $GLOBALS['wpf_settings']['page_boxed'] ? ' boxed' : ''; echo $GLOBALS['wpf_settings']['sidebar_left'] ? ' sidebar_left' : ''; ?>" role="document">

	<header id="masthead" class="site-header" role="banner">
<?php echo wpf_primarymenu_display( 'header' ); ?>

		<div class="fixed-header">
			<?php if ( $GLOBALS['wpf_settings']['header_show_title'] || $GLOBALS['wpf_settings']['header_show_description'] ) : ?>
				<div class="site-meta">
					<section>
						<h1 class="site-title">
							<?php if ( $GLOBALS['wpf_settings']['header_show_title'] ) :  ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							<?php endif; ?>
							<?php if ( $GLOBALS['wpf_settings']['header_show_description'] ) : ?>
								<p class="site-description"><?php if ( function_exists( 'wpf_site_description' ) ) wpf_site_description(); ?></p>
							<?php endif; ?>
						</h1>
					</section>
				</div><!-- .site-meta -->
			<?php endif; ?>
		</div><!-- .fixed-header -->
	</header><!-- #masthead -->
<?php wpf_dev( 'end header.php' ); ?>