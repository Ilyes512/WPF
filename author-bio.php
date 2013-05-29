<?php
/**
 * The template for displaying Author bios.
 *
 *
 */
?>

<div class="author-info">
	<?php if ( get_option( 'show_avatars' ) ): ?>
		<div class="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
		</div><!-- .author-avatar -->
	<?php endif; ?>
	<div class="author-description">
		<h2><?php printf( __( 'About %s', 'wpf' ), get_the_author() ); ?></h2>
		<p>
			<?php the_author_meta( 'description' ); ?>
			<?php if ( ! is_author() ) : ?>
				<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'wpf' ), get_the_author() ); ?>
				</a>
			<?php endif; ?>
		</p>
	</div><!-- .author-description -->
</div><!-- .author-info -->