<!-- header.php -->
<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">

	<title><?php wp_title('&raquo;', true, 'right'); bloginfo('name'); ?></title>

	<!-- Mobile viewport optimized: j.mp/bplateviewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png">

<?php wp_head(); ?>
</head>

<body <?php body_class('antialiased'); ?>>

<div id="container" class="container" role="document">

	<header role="banner">
		<div class="fixed contain-to-grid">
			<nav class="top-bar">
				<ul class="title-area">
					<li class="name">
						<h1><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
				<section class="top-bar-section">
					<?php
						wp_nav_menu(array(
							'theme_location' => 'primary',
							'container' => false,
							'depth' => 0,
							'items_wrap' => '<ul class="left">%3$s</ul>',
							'fallback_cb' => false,
							'walker' => new wpf_walker(array(
								'in_top_bar' => true,
								'item_type' => 'li')
								)
							));
					?>
					<ul class="right hide-for-medium-down">
						<li class="divider"></li>
						<li class="has-form"><?php get_search_form(); ?></li>
					</ul>
				</section>
			</nav>
		</div> <!-- end .fixed.contain-to-grid -->
		<div class="row">
			<div class="large-12 columns">
				<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				<h4 class="subheader"><?php bloginfo('description'); ?></h4>
				<hr/>
			</div>
		</div>
	</header>
<!-- end header.php -->