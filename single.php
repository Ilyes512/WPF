<?php
/**
 * The Template for displaying all single posts.
 *
 *
 */

get_header(); ?>
<?php wpf_dev( 'single.php' ); ?>
	<div class="site-body">
<?php echo wpf_primarymenu_display( 'bottom' ); ?>
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
	</div><!-- .site-body -->
<?php wpf_dev( 'end single.php' ); ?>
<?php get_footer(); ?>