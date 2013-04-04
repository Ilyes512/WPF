<?php get_header(); ?>
<!-- single.php -->
	<section class="row" role="document">

		<!-- Row for main content area -->
		<div class="large-8 columns" role="main">
		
		<?php /* Start loop */ ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<header>
					<h1><?php _e('Quote', 'wpf'); ?></h1>
					<?php wpf_entry_meta(); ?>
				</header>
				<div class="entry-content">
					<?php
						$quote_meta = get_post_custom();
						if ($quote_meta['source_is_url'][0]) {
							$cite = '<cite><a href="' . $quote_meta['quote_source'][0] . '">' . $quote_meta['person'][0] . '</a></cite>';
						} else {
							$cite = '<cite>' . $quote_meta['person'][0];
							if (isset($quote_meta['quote_source'])) $cite .= ' (' . $quote_meta['quote_source'][0] . ')';
							$cite .= '</cite>';
						}
					?>
					<blockquote><?php the_content(); echo $cite ?></blockquote>
				</div>
				<?php comments_template(); ?>
			</article>
			<?php endwhile; ?>
	
		</div> <!-- end .large-8.columns -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section>
		
<?php get_footer(); ?>
<!-- end single.php -->