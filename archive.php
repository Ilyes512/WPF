<?php
/**
 * The template for displaying Archive pages.
 *
 *
 */

get_header(); ?>
<?php wpf_dev( 'archive.php' ); ?>
	<div class="site-body">
<?php echo wpf_primarymenu_display( 'bottom' ); ?>
		<section id="primary" class="content-area">

			<!-- Row for main content area -->
			<div id="content" class="site-content" role="main">

				<?php if ( function_exists( 'wpf_before_content' ) ) echo wpf_before_content(); ?>

				<?php if ( have_posts() ) : ?>
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content/content', get_post_format() ); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'content/content', 'none' ); ?>
				<?php endif; ?>

				<?php wpf_paginate_link(); ?>

			</div><!-- #content -->
			<?php $GLOBALS['class_searchform'] = 'hide-for-large-up'; ?>
			<?php get_sidebar(); ?>
		</section><!-- #primary -->
	</div><!-- .site-body -->
<?php wpf_dev( 'end archive.php' ); ?>
<?php get_footer(); ?>