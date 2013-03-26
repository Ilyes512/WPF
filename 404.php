<?php get_header(); ?>
<!-- 404.php -->
	<section class="row" role="document">
	
		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
			<div class="panel radius">
				<hr>
				<h1 class="entry-title"><?php _e('404 error - Page not found!', 'wpf'); ?></h1>

				<div class="error">
					<p class="bottom"><?php _e('Something went wrong! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'wpf'); ?></p>
				</div>
				<p><?php _e('Please try the following:', 'wpf'); ?></p>
				<ul> 
					<li><?php _e('Check your spelling', 'wpf'); ?></li>
					<li><?php printf(__('Return to the <a href="%s">home page</a>', 'wpf'), home_url()); ?></li>
					<li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'wpf'); ?></li>
				</ul>
				<hr>
			</div>
	
		</div> <!-- end .large-8.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
		<hr>
	</section>
<?php get_footer(); ?>
<!-- end 404.php -->