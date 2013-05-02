<?php
/**
 *  The template for displaying all pages.
 * 
 * 
 */

get_header(); ?>
<?php wpf_dev( 'page.php' ); ?>
	<section id='primary' class="content-area">
	
		<!-- Row for main content area -->
		<div id="content" class="site-content" role="main">
		
			<?php /* Start loop */ ?>
			<?php while ( have_posts() ): the_post(); ?>
				<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php wpf_entry_meta(); ?>
					</header><!-- .entry-header -->
	
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array(
							'before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpf'),
							'after' => '</p></nav>',
						) ); ?>
					</div><!-- .entry-content -->
	
					<footer class="entry-meta">
						<?php wpf_tags( '<p>', '</p>' ); ?>
						<?php edit_post_link( __( 'Edit', 'wpf' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->
	
				<?php comments_template(); ?>
			<?php endwhile; ?>
	
		</div><!-- #content -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section><!-- #primary -->	
<?php wpf_dev( 'end page.php' ); ?>
<?php get_footer(); ?>