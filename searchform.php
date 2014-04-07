<?php
/**
 * The template for displaying the Search form
 *
 *
 */
?>

<?php wpf_dev( 'searchform.php' ); ?>

<?php
// TEMP: Always show the searchform when the searchform in the Top Bar is off (see WPF Options page in admin)
if ( ! $GLOBALS['wpf_settings']['menu_primary_searchform'] ) {
	unset( $GLOBALS['class_searchform'] );
}
?>

<form<?php if ( isset( $GLOBALS['class_searchform'] ) ) echo ' class="' . $GLOBALS['class_searchform'] . '"';?> role="search" method="get" action="<?php echo home_url( '/' ); ?>">
	<div class="searchform">
		<div class="searchinput">
			<input type="text" value="" name="s" placeholder="<?php _e( 'Search', 'wpf' ); ?>">
		</div>
		<div class="searchsubmit">
			<input class="icon" type="submit" value="&#xf002;">
		</div>
	</div><!-- .searchform -->
</form>
<?php wpf_dev( 'end searchform.php' ); ?>