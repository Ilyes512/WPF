<?php
/**
 * The template for displaying the comments.
 *
 *
 */

// Check if the post is password protected
if ( post_password_required() ) return;

wpf_dev( 'contact.php' ); ?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf(
					_nx(
						'One comment on &ldquo;%2$s&rdquo;',
						'%1$s comments on &ldquo;%2$s&rdquo;',
						get_comments_number(),
						'comments title',
						'wpf'
					),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h2>

		<ul class="comment-list">
			<?php
				wp_list_comments(
					array(
						'format'      => 'html5',
						'avatar_size' => 50,
					)
				);
			?>
		</ul><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
				echo '<hr>';
				wpf_paginate_comments_link();
				echo '<hr>';
			}
		?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.' , 'wpf' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		$args = array(
		   'comment_notes_after' => '<p class="form-allowed-tags hide-for-small">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:<br> %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		   'title_reply'         => __( 'Leave a Reply', 'wpf' ),
		);
		comment_form( $args );
	?>

</div><!-- #comments -->

<?php wpf_dev( 'end contact.php' ); ?>

