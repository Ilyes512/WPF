<?php
/**
 * The sidebar containing the sidebar-main widget area.
 * 
 * 
 */
?>
<?php wpf_dev( 'sidebar.php' ); ?>
<div id="main-sidebar" class="sidebar-container" role="complementary">
	<?php dynamic_sidebar( 'sidebar-main' ); ?>
</div><!-- #sidebar -->
<?php wpf_dev( 'end sidebar.php' ); ?>