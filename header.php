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

<?php global $is_IE; ?>
<?php if ( $is_IE ): ?>
<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php endif; ?>

<?php wp_head(); ?>
</head>

<body <?php body_class( $body_class ); ?>>

<div id="page" class="hfeed site" role="document">

	<header id="masthead" class="site-header" role="banner">
		<div id="navbar" class="contain-to-grid">
			<nav id="side-navigation" class="top-bar" role="navigation">
				<ul class="title-area">
					<li class="name"><h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1></li>
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
				<section class="top-bar-section">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '<ul class="left">%3$s</ul>',
					'walker'         => new wpf_walker(),
					'fallback_cb'    => 'wpf_nav_menu_fallback',
				) ); ?>
					<ul class="right hide-for-medium-down">
						<li class="divider"></li>
						<li class="has-form"><?php get_search_form(); ?></li>
					</ul>
				</section>
			</nav><!-- #site-navigation -->
		</div><!-- #nav-bar -->

		<div class="site-meta">
			<hgroup>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h4 class="site-subtitle"><?php if ( function_exists( 'wpf_site_subtitle' ) ) wpf_site_subtitle(); ?></h4>
			</hgroup>
			<hr>
		</div><!-- .site-meta -->

	</header><!-- #masthead -->
<?php
/**
 * @todo Add some better styling i.e. div container class with margin: 0 auto;.
 *
 *
 */
?>
<!--[if lt IE 7]>
	<p class="chromeframe"><?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.' , 'wpf' ); ?></p>
<![endif]-->
<?php wpf_dev( 'end header.php' );
?>