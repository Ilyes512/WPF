<?php get_header(); ?>
<!-- search.php -->
	<section class="row" role="document">
	
		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
			<h2><?php _e('Search Results for', 'wpf'); ?> "<?php echo get_search_query(); ?>"</h2>
		
		<?php if (have_posts()) : ?>
		
			<?php /* Start the Loop */ ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content', get_post_format()); ?>
			<?php endwhile; ?>
			
			<?php else : ?>
				<?php get_template_part('content', 'none'); ?>
			
		<?php endif; // end have_posts() check ?>
		
		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if (function_exists('wpf_pagination')) { wpf_pagination(); } else if (is_paged()) { ?>
			<nav id="post-nav">
				<div class="post-previous"><?php next_posts_link(__('&larr; Older posts', 'wpf')); ?></div>
				<div class="post-next"><?php previous_posts_link(__('Newer posts &rarr;', 'wpf')); ?></div>
			</nav>
		<?php } ?>
	
		</div> <!-- end .large-8.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
		
	</section>
	
<?php get_footer(); ?>
<!-- end search.php -->