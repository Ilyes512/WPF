<!-- footer.php -->
	<footer>
	<?php if((is_active_sidebar('sidebar-footer-1') or is_active_sidebar('sidebar-footer-2') or is_active_sidebar('sidebar-footer-3')) and !is_404()): ?>
		<div class="row">
		<?php
			$i = 1;
			foreach($GLOBALS['wpf_widget_classes'] as $class):
				if($class):
		?>
			<div class="<?=$class?> columns">
				<?php dynamic_sidebar('sidebar-footer-'.$i); ?>
			</div>
		<?php
				endif;
				$i++;
			endforeach;
		?>
		</div> <!-- end .row -->
		<?php endif; ?>
		<div class="row">
			<div class="large-6 columns">
				<p>
					<?php printf(__('&copy; %s Crafted on WPF<br><a href="https://github.com/MekZii/WPF" rel="nofollow" title="WPD - Wordpress Foundation">Visit the repository on github!</a>', 'wpf'),
											 date('Y')
											); ?>
				</p>
			</div>
			<div class="large-6 columns">
				<?php wp_nav_menu(array('theme_location' => 'utility', 'container' => false, 'menu_class' => 'inline-list right', 'fallback_cb' => false)); ?>
			</div>
		</div>
		
	</footer>

</div> <!-- end #container.container -->

<?php wp_footer(); ?>

<script>
	jQuery(document).foundation();
</script>
</body>
</html>
<!-- end footer.php -->