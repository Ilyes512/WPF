<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 *
 */

wpf_dev( 'content.php' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">

		<?php wpf_post_thumbnail(); ?>

		<?php if ( is_home() ) : ?>
			<?php wpf_breadcrumb(); ?>
		<?php elseif ( is_archive() || is_single() ) : ?>
			<?php wpf_breadcrumb( 'show-home' ); ?>
		<?php endif; ?>

		<?php if ( is_single() ) : ?>
			<?php global $page; ?>
			<h1 class="entry-title"><?php echo ( $page > 1 ) ? get_the_title() . sprintf( __( ' - page %d', 'wpf' ), $page ) : get_the_title(); ?></h1>
		<?php else : ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( sprintf( __( 'Permalink to %s', 'wpf' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		<?php endif; // is_single() ?>

		<div class="entry-meta">
			<?php wpf_entry_meta(); ?>
			<?php edit_post_link( '<i class="icon-pencil-square-o icon-fw">&nbsp;</i>' . __( 'Edit', 'wpf' ), '<span class="edit-link">', '</span>' ); ?>
			<?php if ( has_tag() ) : ?>
				<div class="post-tags">
					<?php the_tags( '<span class="post-tag"><i class="icon-tag icon-fw">&nbsp;</i>', '</span> <span class="post-tag"><i class="icon-tag icon-fw">&nbsp;</i>', '</span>' ); ?>
				</div><!--post-tags -->
			<?php endif; ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<?php wpf_link_pages(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading', 'wpf' ) ); ?>
			<?php wpf_link_pages(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( is_single() && get_the_author_meta( 'description' ) && $GLOBALS['wpf_settings']['show_author_info'] ) : ?>
			<?php get_template_part( 'author-bio' ); ?>
		<?php endif; ?>
		<hr>
	</footer><!-- .entry-meta -->
</article>
<?php wpf_dev( 'end content.php' ); ?>