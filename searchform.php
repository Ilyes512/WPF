<!-- searchform.php -->
<form<?php if (isset($GLOBALS['class_searchform'])) echo ' class="'.$GLOBALS['class_searchform'].'"';?> role="search" method="get" action="<?php echo home_url('/'); ?>">
	<div class="row collapse">
		<div class="small-8 columns">
			<input type="text" value="" name="s" placeholder="<?php _e('Search', 'wpf'); ?>">
		</div>
		<div class="small-4 columns">
			<input type="submit" value="<?php _e('Search', 'wpf'); ?>" class="postfix button radius">
		</div>
	</div>
</form>
<!-- end searchform.php -->