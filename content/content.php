<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 *
 */
?>
<?php wpf_dev( 'content.php' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
		<?php endif; ?>
		
		<div class="entry-meta">
			<?php if ( is_home() ) : ?>
				<?php wpf_breadcrumb(); ?>
			<?php elseif ( is_archive() || is_single() ) : ?>
				<?php wpf_breadcrumb( 'show-home' ); ?>
			<?php endif; ?>
		</div><!-- .entry-meta -->
	
		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpf' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		<?php endif; // is_single() ?>
		
		<div class="entry-meta">
			<?php wpf_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'wpf' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<?php wpf_link_pages(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading...', 'wpf' ) ); ?>
			<?php wpf_link_pages(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( comments_open() && ! is_single() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a response', 'wpf' ) . '</span>', __( 'one response', 'wpf' ), __( '% responses', 'wpf' ) ); ?>
			</div><!-- .comments-link -->
		<?php elseif( ! comments_open() ) : ?>
			<div class="comments-link">
				<?php _e( 'Comments are locked for this post', 'wpf' ); ?>
			</div><!-- .comments-link -->
		<?php endif; // comments_open() ?>
		

		<?php if ( has_tag() ) : ?>
			<div class="post-tags">
				<?php the_tags( '<span class="post-tag">', '</span> <span class="post-tag">', '</span>' ); ?>
			</div><!--post-tags -->
		<?php endif; ?>
		<hr>
	</footer><!-- .entry-meta -->
</article>
<?php wpf_dev( 'end content.php' ); ?>