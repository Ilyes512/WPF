<?php /* Template Name: Left sidebar */
/**
 * The template for displaying pages with the sidebar on the left side.
 * 
 * 
 */
 
get_header(); ?>
<?php wpf_dev( 'tpl-leftsidebar.php' ); ?>
	<section id='primary' class="content-area leftsb">

		<!-- Row for main content area -->
		<div id="content" class="site-content" role="main">

			<?php /* Start loop */ ?>
			<?php while ( have_posts() ): the_post(); ?>
				<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wpf_link_pages(); ?>
					</div><!-- .entry-content -->

					<?php if ( has_tag() ) : ?>	
						<footer class="entry-meta">
							<div class="post-tags">
								<?php the_tags( '<span class="post-tag">', '</span> <span class="post-tag">', '</span>' ); ?>
							</div><!--post-tags -->
						</footer><!-- .entry-meta -->
					<?php endif; ?>
				</article><!-- #post -->
	
				<?php comments_template(); ?>
			<?php endwhile; ?>
	
		</div><!-- #content -->	
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>

	</section><!-- #primary -->
<?php wpf_dev( 'end tpl-leftsidebar.php' ); ?>
<?php get_footer(); ?>