<!-- index.php -->
<?php get_header(); ?>

	<section class="row" role="document">
	
		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
		<?php if (have_posts()): ?>
		
			<?php /* Start the Loop */ ?>
			<?php while (have_posts()): the_post(); ?>
				<?php get_template_part('content', get_post_format()); ?>
			<?php endwhile; ?>
			
			<?php else : ?>
				<?php get_template_part('content', 'none'); ?>
			
		<?php endif; // end have_posts() check ?>
		
		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if (function_exists('wpf_pagination')): wpf_pagination(); elseif (is_paged()): ?>
			<nav id="post-nav">
				<div class="post-previous"><?php next_posts_link(__('&larr; Older posts', 'wpf')); ?></div>
				<div class="post-next"><?php previous_posts_link(__('Newer posts &rarr;', 'wpf')); ?></div>
			</nav>
		<?php endif; ?>
	
		</div>
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section> <!-- end .large-8.columns -->	

<?php get_footer(); ?>
<!-- end index.php -->