<?php
/**
 * The template for displaying Archive pages.
 * 
 * 
 */

get_header(); ?>
<?php wpf_dev( 'archive.php' ); ?>
	<section id='primary' class="content-area">
	
		<!-- Row for main content area -->
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if ( get_post_type() == 'post' ) : ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
					<?php else: ?>
						<?php get_template_part( 'content', get_post_type() ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		
			<?php /* Display navigation to next/previous pages when applicable */ ?>
			<?php if ( function_exists( 'wpf_pagination' ) ) : ?>
				<?php wpf_pagination(); ?>
			<?php elseif ( is_paged() ) : ?>
				<?php /* @todo Make post-nav semantic  */ ?>
				<nav id="post-nav">
					<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'wpf' ) ); ?></div>
					<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'wpf' ) ); ?></div>
				</nav><!-- #post-nav -->
			<?php endif; ?>
	
		</div><!-- #content -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section><!-- #primary -->
<?php wpf_dev( 'end archive.php' ); ?>
<?php get_footer(); ?>