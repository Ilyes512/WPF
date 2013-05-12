<?php
/**
 * The template for displaying a "No posts found" message.
 * 
 * 
 */
?>
<?php wpf_dev( 'content-none.php' ); ?>
<div class="panel">
	<hr>

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wpf' ), admin_url( 'post-new.php' ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

	<h2><?php _e('Nothing Found', 'wpf'); ?></h2>
	
	<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'wpf' ); ?></p>
	<?php $GLOBALS['class_searchform'] = 'hide-for-small'; ?>
	<?php get_search_form(); ?>

	<?php else : ?>

	<h2><?php _e('Nothing Found', 'wpf'); ?></h2>

	<p><?php _e('Apologies, but no results were found. Perhaps searching will help find a related post.', 'wpf'); ?></p>
	<p><?php printf(__('Return to the <a href="%s">home page</a>', 'wpf'), home_url()); ?></p>

	<?php endif; ?>

	<hr>
</div><!-- .panel -->
<?php wpf_dev( 'end content-none.php' ); ?>