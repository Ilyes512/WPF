<!-- content.php -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php wpf_entry_meta(); ?>
				<?php if (is_archive()) wpf_breadcrumb(true); else wpf_breadcrumb(); ?>
			</header>
			<div class="entry-content">
				<?php the_content(__('Continue reading...', 'wpf')); ?>
			</div>
			<footer>
				<?php wpf_postfooter_meta(); ?>
			</footer>
			<hr>
		</article>
<!-- end content.php -->