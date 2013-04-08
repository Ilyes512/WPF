<?php get_header(); ?>
<!-- single.php -->
	<section class="row" role="document">

		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
		<?php /* Start loop */ ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<header>
					<h1><?php _e('Quote', 'wpf'); ?></h1>
					<?php wpf_entry_meta(); ?>
				</header>
				<div class="entry-content">
					<?php
						if (function_exists('wpf_quote_print')) {
							// this is a function that is used by the plugin "WPF Quote"
							wpf_quote_print();
						} else {
							echo '<p>Please check if "WPF Quote"-plugin is installed and activated!</p>';
						}
					?>
				</div>
				<?php comments_template(); ?>
			</article>
			<?php endwhile; ?>
	
		</div> <!-- end .large-8.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section>
		
<?php get_footer(); ?>
<!-- end single.php -->