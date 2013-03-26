<?php get_header(); ?>
<!-- single.php -->
	<section class="row" role="document">

		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
		<?php /* Start loop */ ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<header>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php wpf_entry_meta(); ?>
					<?php wpf_breadcrumb(true); ?>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer>
					<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpf'), 'after' => '</p></nav>' )); ?>
					<?php wpf_tags('<div class="text-center">', '</div>'); ?>
				</footer>
				<?php comments_template(); ?>
			</article>
			<?php endwhile; ?>
	
		</div> <!-- end .large-8.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section>
		
<?php get_footer(); ?>
<!-- end single.php -->