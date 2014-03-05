<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 *
 */

get_header(); ?>
<?php wpf_dev( '404.php' ); ?>
	<div class="site-body">
<?php echo wpf_primarymenu_display( 'bottom' ); ?>
		<section id='primary' class="content-area">

			<!-- Row for main content area -->
			<div id="content" class="site-content" role="main">

				<div class="panel">
					<hr>
					<h1><?php _e( '404 error - Page not found!', 'wpf' ); ?></h1>

					<p><?php _e( 'Something went wrong! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'wpf' ); ?></p>

					<p><?php _e( 'Please try the following:', 'wpf' ); ?></p>
					<ul>
						<li><?php _e( 'Check your spelling', 'wpf' ); ?></li>
						<li><?php printf( __( 'Return to the <a href="%s">home page</a>', 'wpf' ), home_url() ); ?></li>
						<li><?php _e( 'Click the <a href="javascript:history.back()">Back</a> button', 'wpf' ); ?></li>
					</ul>
					<hr>
				</div><!-- .panel -->

			</div><!-- #content -->
			<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
			<?php get_sidebar(); ?>
		</section><!-- #content -->
	</div><!-- .site-body -->
<?php wpf_dev( 'end 404.php' ); ?>
<?php get_footer(); ?>