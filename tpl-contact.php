<?php /* Template Name: Contact Form */
/**
 *  The template for displaying a contact page.
 *
 *
 */

get_header(); ?>
<?php wpf_dev( 'tpl-contact.php' ); ?>
	<section id='primary' class="content-area">

		<!-- Row for main content area -->
		<div id="content" class="site-content" role="main">

			<?php /* Start loop */ ?>
			<?php while ( have_posts() ): the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php edit_post_link( '<i class="icon-pencil-square-o icon-fw">&nbsp;</i>' . __( 'Edit', 'wpf' ), '<div class="entry-meta"><span class="edit-link">', '</span></div>' ); ?>
					<?php $content = get_the_content(); ?>
					<?php if ( $content != '' ) : ?>
						<div class="entry-content">
							<?php the_content(); ?>
						</div><!-- .entry-content -->
					<?php endif; ?>

					<?php if ( $wpf_c['c_feedback']['msg'] ) : ?>
						<div data-alert class="msgbox-<?php echo $wpf_c['c_feedback']['class']; ?>">
							<?php echo $wpf_c['c_feedback']['msg']; ?>
							<a href="#" class="close">&times;</a>
						</div>
					<?php endif; ?>

					<?php if ( $wpf_c['show_form'] ) : ?>
						<form id="wpf_contact" method="post" action="<?php the_permalink(); ?>" novalidate>
							<fieldset>
								<legend><?php the_title(); ?></legend>

								<div<?php echo $wpf_c['c_name']['class']; ?>>
									<label for="c_name"><?php _e( 'Name (required)', 'wpf' ); ?></label>
									<input type="text" id="c_name" name="c_name" placeholder="<?php _e( 'Your name please', 'wpf' ); ?>" maxlength="256"<?php if ( isset( $wpf_c_values['c_name'] ) ) echo ' value="' . $wpf_c_values['c_name'] . '"'; ?> autofocus>
									<?php if ( $wpf_c['c_name']['msg'] ) : ?>
										<small><?php echo $wpf_c['c_name']['msg']; ?></small>
									<?php endif; ?>
								</div>
								<div<?php echo $wpf_c['c_email']['class']; ?>>
									<label for="c_email"><?php _e( 'Email (required)', 'wpf' ); ?></label>
									<input type="email" id="c_email" name="c_email" placeholder="<?php _e( 'Your email please', 'wpf' ); ?>" maxlength="256"<?php if ( isset( $wpf_c_values['c_email'] ) ) echo ' value="' . $wpf_c_values['c_email'] . '"'; ?>>
									<?php if ( $wpf_c['c_email']['msg'] ) : ?>
										<small><?php echo $wpf_c['c_email']['msg']; ?></small>
									<?php endif; ?>
								</div>
								<div<?php echo $wpf_c['c_subject']['class']; ?>>
									<label for="c_subject"><?php _e( 'Subject (required)', 'wpf' ); ?></label>
									<input type="text" id="c_subject" name="c_subject" placeholder="<?php _e( 'Your subject please', 'wpf' ); ?>" maxlength="256"<?php if ( isset( $wpf_c_values['c_subject'] ) ) echo ' value="' . $wpf_c_values['c_subject'] . '"'; ?>>
									<?php if ( $wpf_c['c_subject']['msg'] ) : ?>
										<small><?php echo $wpf_c['c_subject']['msg']; ?></small>
									<?php endif; ?>
								</div>
								<div<?php echo $wpf_c['c_content']['class']; ?>>
									<label for="c_content"><?php _e( 'Content (required)', 'wpf' ); ?></label>
									<textarea id="c_content" name="c_content" placeholder="<?php _e( 'Your content please', 'wpf' ); ?>" maxlength="32000"><?php if ( isset( $wpf_c_values['c_content'] ) ) echo $wpf_c_values['c_content']; ?></textarea>
									<?php if ( $wpf_c['c_content']['msg'] ) : ?>
										<small><?php echo $wpf_c['c_content']['msg']; ?></small>
									<?php endif; ?>
								</div>
								<input type="submit" id="wpf_contact_send" name="wpf_contact_send" value="<?php _e( 'Send mail', 'wpf' ); ?>">
							</fieldset>
						</form>
					<?php endif; /* end $wpf_c['show_form'] */  ?>
				</article><!-- #post -->
			<?php endwhile; ?>

		</div><!-- #content -->
		<?php $GLOBALS['class_searchform'] = 'show-for-medium-down'; ?>
		<?php get_sidebar(); ?>
	</section><!-- #primary -->
<?php wpf_dev( 'end tpl-contact.php' ); ?>
<?php get_footer(); ?>