<?php 
/**
 * The template for displaying the Search form
 * 
 * @todo Replace non-semantic classes
 */
?>

<?php wpf_dev( 'searchform.php' ); ?>
<form<?php if ( isset( $GLOBALS['class_searchform'] ) ) echo ' class="' . $GLOBALS['class_searchform'] . '"';?> role="search" method="get" action="<?php echo home_url( '/' ); ?>">
	<div class="searchform">
		<div class="searchinput">
			<input type="text" value="" name="s" placeholder="<?php _e( 'Search', 'wpf' ); ?>">
		</div>
		<div class="searchsubmit">
			<input type="submit" value="<?php _e( 'Search', 'wpf' ); ?>">
		</div>
	</div><!-- .searchform -->
</form>
<?php wpf_dev( 'end searchform.php' ); ?>