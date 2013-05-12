<?php
/**
 * The Template for displaying all single posts.
 * 
 * 
 */

get_header(); ?>
<?php wpf_dev( 'single.php' ); ?>
	<section id='primary' class="content-area">

		<!-- Row for main content area -->
		<div id="content" class="site-content" role="main">

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content/content', get_post_format() ); ?>
				<?php comments_template(); ?>
			<?php endwhile; ?>
	
		</div><!-- #content -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section><!-- #primary -->
<?php wpf_dev( 'end single.php' ); ?>		
<?php get_footer(); ?>