<!-- content.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php wpf_entry_meta(); ?>
	</header>
	<div class="entry-content">
		<?php the_content(__('Continue reading...', 'wpf')); ?>
	</div>
	<footer>
		<p>
		<?php wpf_postfooter_meta(); ?>
		</p>
	</footer>
	<hr />
</article>
<!-- end content.php -->