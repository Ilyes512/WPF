<!-- content.php -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2><a href="<?php the_permalink(); ?>">Quote</a></h2>
				<?php wpf_entry_meta(); ?>
			</header>
			<div class="entry-content">
				<?php
					$quote_meta = get_post_custom();
					if ($quote_meta['source_is_url'][0]) {
						$cite = '<cite><a href="' . $quote_meta['quote_source'][0] . '">' . $quote_meta['person'][0] . '</a></cite>';
					} else {
						$cite = '<cite>' . $quote_meta['person'][0];
						if (isset($quote_meta['quote_source'])) $cite .= ' (' . $quote_meta['quote_source'][0] . ')';
						$cite .= '</cite>';
					}
				?>
				<blockquote><?php the_content(); echo $cite; ?></blockquote>
			</div>
			<footer>
				<?php wpf_postfooter_meta(false); ?>
			</footer>
			<hr>
		</article>
<!-- end content.php -->