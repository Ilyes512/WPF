<!-- content.php -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2><a href="<?php the_permalink(); ?>">Quote</a></h2>
				<?php wpf_entry_meta(); ?>
			</header>
			<div class="entry-content">
				<?php
					if (function_exists('wpf_quote_print')) {
						// this is a function that is used by the plugin "WPF Quote"
						wpf_quote_print();
					} else {
						echo '<p>Please check if "WPF Quote"-plugin is installed and activated!</p>';
					}
				?>
			</div>
			<footer>
				<?php wpf_postfooter_meta(false); ?>
			</footer>
			<hr>
		</article>
<!-- end content.php -->