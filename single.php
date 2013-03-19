<!-- single.php -->
<?php get_header(); ?>
	
	<section class="row" role="document">

		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
		<?php /* Start loop */ ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<header>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php wpf_entry_meta(); ?>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer>
					<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpf'), 'after' => '</p></nav>' )); ?>
					<?php wpf_tags('<p>', '</p>'); ?>
				</footer>
				<?php comments_template(); ?>
			</article>
			<?php endwhile; ?>
	
		</div><!-- end .large-12.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section> <!-- end .large-8.columns -->
		
<?php get_footer(); ?>
<!-- end single.php -->