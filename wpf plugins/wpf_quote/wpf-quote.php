<?php
/*
Plugin Name:	WPF Quote
Plugin URI:		https://github.com/MekZii/WPF/
Description:	This plugin is used with the Wordpress theme called WPF (uses Zurb Foundation framework). This plugin will show random quotes (quote post type).
Version:			0.2
Author:				MekZii
License:			MIT License
License URI:	http://www.opensource.org/licenses/mit-license.php
*/

/****************************************************
		Table of contents
*****************************************************
 *		>WP_QUOTE PLUGIN INIT
 *		>REGISTER POST TYPE 'QUOTE'
 *		>QUOTE PRINT
 *		>QUOTE WIDGET
*/

/****************************************************
 *		>WP_QUOTE PLUGIN INIT
*****************************************************/
if (!function_exists('wpf_quote_init')) {
	function wpf_quote_init() {
		//Load localization/textdomain
		load_plugin_textdomain('wpf_quote', false, dirname(plugin_basename(__FILE__)) . '/lang');
	}
}
add_action('plugins_loaded', 'wpf_quote_init');

/****************************************************
 *		>REGISTER POST TYPE 'QUOTE'
*****************************************************/

if (!function_exists('wpf_quote_custom_post_type')) {
	// register post type
	function wpf_quote_custom_post_type() {
		
		$quote_labels = array(
													'name' => _x('My Quotes', 'post type general name', 'wpf_quote'),
													'singular_name' => _x('Quote', 'post type singular name', 'wpf_quote'),
													'menu_name' => __('Quotes', 'wpf_quote'),
													'all_items' => __('All Quotes', 'wpf_quote'),
													'add_new' => _x('Add New', 'quote', 'wpf_quote'),
													'add_new_item' => __('Add New Quote', 'wpf_quote'),
													'edit_item' => __('Edit Quote', 'wpf_quote'),
													'new_item' => __('New Quote', 'wpf_quote'),
													'view_item' => __('View Quote', 'wpf_quote'),
													'search_items' => __('Search Quotes', 'wpf_quote'),
													'not_found' =>  __('No quotes found', 'wpf_quote'),
													'not_found_in_trash' => __('No quotes found in Trash', 'wpf_quote'),
													);

		$quote_args = array(
												'labels' => $quote_labels,
												'description' => __('This is a custom post type for posting quotes', 'wpf_quote'),
												'public' => true,
												//'menu_icon' => get_stylesheet_directory_uri() . '/picture.png'.
												'supports' => array('author', 'editor', 'comments'),
												'has_archive' => true
												);
		register_post_type('quote', $quote_args);
	}
}
add_action('init', 'wpf_quote_custom_post_type');

if (!function_exists('wpf_quote_setup_metaboxes')) {
	function wpf_quote_setup_metaboxes() {
		add_action('add_meta_boxes', 'wpf_quote_add_metabox');
		
		add_action('save_post', 'wpf_quote_metabox_save', 10, 2);
	}
}
add_action('load-post.php', 'wpf_quote_setup_metaboxes');
add_action('load-post-new.php', 'wpf_quote_setup_metaboxes');

if (!function_exists('wpf_quote_add_metabox')) {
	// register the meta box to a post type
	function wpf_quote_add_metabox($post_type) {
		if ($post_type == 'quote') add_meta_box('person_id', esc_html__('Quote optional data', 'wpf_quote'), 'wpf_quote_metabox', 'quote', 'normal');
	}
}

if (!function_exists('wpf_quote_metabox_save')) {
	function wpf_quote_metabox_save($post_id, $post) {
		// bail if we're doing an auto save
		if (defined('DOING_AUTOSAVE') and DOING_AUTOSAVE) return;
		
		// if our nonce isn't there, ore we can't verify it, bail
		if (!isset($_POST['meta_box_nonce']) or !wp_verify_nonce($_POST['meta_box_nonce'], 'nonce_person')) return;
	
		// if our current user can't edit this post, bail
		if (!current_user_can('edit_posts')) return;
		
		//echo '<pre>'; print_r($post); echo '</pre>';
		
		if ($post->post_type == 'quote') {
			
			if (!wp_is_post_revision($post_id) and empty($_POST['post_name'])) {
			
				// unhook this function so it doesn't loop infinitely
				remove_action('save_post', 'wpf_quote_metabox_save');
			
				// update the post, which calls save_post again
				$slug = $post->post_type . '-' . $post_id;
				wp_update_post(array('ID' => $post_id, 'post_name' => $slug, 'post_title' => $slug));
				
				// re-hook this function
				add_action('save_post', 'wpf_quote_metabox_save');
				
			} elseif ($post->post_titel != $post->post_name) {
			
				// unhook this function so it doesn't loop infinitely
				remove_action('save_post', 'wpf_quote_metabox_save');
				
				wp_update_post(array('ID' => $post_id, 'post_title' => $post->post_name));
				
				// re-hook this function
				add_action('save_post', 'wpf_quote_metabox_save');
			}
			
			// save the data if set
			if (isset($_POST['person'])) update_post_meta($post_id, 'person', esc_attr($_POST['person']));
			if (isset($_POST['source_is_url'])) update_post_meta($post_id, 'source_is_url', esc_attr($_POST['source_is_url']));
			if (isset($_POST['quote_source'])) {
				if (isset($_POST['source_is_url']) and $_POST['source_is_url'] == 1)
					update_post_meta($post_id, 'quote_source', esc_url($_POST['quote_source']));
				else
					update_post_meta($post_id, 'quote_source', esc_attr($_POST['quote_source']));
			}
		}
	}
}

if (!function_exists('wpf_quote_metabox')) {
	// show the meta boxes on the quote post page
	function wpf_quote_metabox($post) {
	
		$values = get_post_custom($post->ID);
		
		$person_value = (!empty($values['person'][0])) ? esc_attr($values['person'][0]) : '';
		$quote_source_value = (!empty($values['quote_source'][0])) ? esc_attr($values['quote_source'][0]) : '';
		$source_is_url_value = (!empty($values['source_is_url'][0])) ? esc_attr($values['source_is_url'][0]) : 0;
		
		// Use nonce for verification
		wp_nonce_field('nonce_person', 'meta_box_nonce');
		
		echo '<label for="person">' . __('Person: ', 'wpf_quote') . '</label>';
		echo '<input type="text" name="person" id="person" value="'.$person_value.'">';
		
		echo '<br><br><label for="quote_source">' . __('Source: ', 'wpf_quote') . '</label>';
		echo '<input size="50" type="text" name="quote_source" id="quote_source" value="'.$quote_source_value.'">';
		
		echo '<br><br><label for="">' . __(' Check if "Source" is a url: ', 'wpf_quote') .'</label>';
		echo ' <input type="checkbox" name="source_is_url" id="source_is_url" value="1"' . checked($source_is_url_value, '1', false) . '>';
	}
}

if (!function_exists('wpf_quote_custom_post_messages')) {
	// Adjust the messages for custom post type's
	function wpf_quote_custom_post_messages($messages) {
		global $post, $post_ID;
		$messages['quote'] = array(
			0 => '', 
			1 => sprintf(__('Quote updated. <a href="%s">View Quote</a>', 'wpf_quote'), esc_url(get_permalink($post_ID))),
			2 => __('Custom field updated.', 'wpf_quote'),
			3 => __('Custom field deleted.', 'wpf_quote'),
			4 => __('Quote updated.', 'wpf_quote'),
			5 => isset($_GET['revision']) ? sprintf(__('Quote restored to revision from %s', 'wpf_quote'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
			6 => sprintf(__('Quote published. <a href="%s">View Quote</a>', 'wpf_quote'), esc_url(get_permalink($post_ID))),
			7 => __('Quote saved.', 'wpf_quote'),
			8 => sprintf(__('Quote submitted. <a target="_blank" href="%s">Preview Quote</a>', 'wpf_quote'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
			9 => sprintf(__('Quote scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview quote</a>', 'wpf_quote'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
			10 => sprintf(__('Quote draft updated. <a target="_blank" href="%s">Preview Quote</a>', 'wpf_quote'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
		);
		return $messages;
	}
}
add_filter('post_updated_messages', 'wpf_quote_custom_post_messages');

if (!function_exists('wpf_quote_set_quote_columns')) {
	// manage the admin columns
	function wpf_quote_set_quote_columns($columns) {
		return array(
								 'cb' => '<input type="checkbox">',
								 'edit' => __('Edit', 'wpf_quote'),
								 'slug' => __('Slug', 'wpf_quote'),
								 'person' => __('Person', 'wpf_quote'),
								 'content' => __('Quote', 'wpf_quote'),
								 'date' => __('Date', 'wpf_quote')
	    );
	}
}
add_filter('manage_quote_posts_columns' , 'wpf_quote_set_quote_columns');

if (!function_exists('wpf_quote_set_quote_custom_column')) {
	// Set the output for the custom columns
	function wpf_quote_set_quote_custom_column($column, $post_id) {
		switch ($column) {
			case 'person':
				echo get_post_meta($post_id, 'person', true);
				break;
			case 'slug';
				global $post;
				echo $post->post_name;
				break;	
			case 'content';
				echo the_excerpt();
				break;
			case 'edit';
				echo '<strong><a href="' . get_edit_post_link() . '">' . __('Edit', 'wpf_quote') . '</a></strong>';
				break;
		}
	}
}
add_action('manage_quote_posts_custom_column', 'wpf_quote_set_quote_custom_column', 10, 2);

if (!function_exists('wpf_quote_set_quote_sortable_columns')) {
	// make the added columns sortable
	function wpf_quote_set_quote_sortable_columns($columns) {
		$columns['person'] = 'person';
		$columns['slug'] = 'slug';
		$columns['content'] = 'content';
	
		return $columns;
	}
}
add_filter('manage_edit-quote_sortable_columns', 'wpf_quote_set_quote_sortable_columns');

/*
function wpf_set_quote_column_orderby($query) {
	if (!is_admin()) return;

	$orderby = $query->get('orderby');
	
	if ('person' == $orderby) {
		$query->set('meta_ket', 'person_sort');
		$query->set('orderby', 'meta_value_num');
	}
}
add_filter('pre_get_posts', 'wpf_set_quote_column_orderby');
*/

/****************************************************
 *		>QUOTE PRINT
*****************************************************/

if (!function_exists('wpf_quote_print')) {
	// print the quote
	function wpf_quote_print() {
		// get the meta value's
		$quote_meta = get_post_custom();
		
		$source_is_url = (empty($quote_meta['source_is_url'][0])) ? false : $quote_meta['source_is_url'][0];
		$quote_source = (empty($quote_meta['quote_source'][0])) ? false : $quote_meta['quote_source'][0];
		$person = (empty($quote_meta['person'][0])) ? __('Unknown', 'wpf_quote') : $quote_meta['person'][0];;

		// first check if 'source_is_url' and 'quote_source' are not empty, when true prints the source as url. Else print source within parentheses.
		if ($source_is_url and $quote_source) {
			$cite = '<cite><a href="' . $quote_source . '">' . $person . '</a></cite>';
		} else {
			$cite = '<cite>' . $person;
			if ($quote_source) $cite .= ' (' . $quote_source . ')';
			$cite .= '</cite>';
		}
		// print the html
		echo '<blockquote>' . get_the_content() . $cite . '</blockquote>';
	}
}

/****************************************************
 *		>QUOTE WIDGET
*****************************************************/
class wpf_quote extends WP_Widget {

	public function __construct() {
		$widget_options = array(
														'classname' => 'wpf_quote',
														'description' => __('Display a random quote', 'wpf_quote'
													 ));
		parent::__construct(false, __('Random quotes', 'wpf_quote'), $widget_options);
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['read_more'] = sanitize_text_field($new_instance['read_more']);
		$instance['quote_pool'] = (is_int((int) $new_instance['quote_pool']) and (int) $new_instance['quote_pool'] > 0) ? $new_instance['quote_pool'] : 10;
		return $instance;
	}

 	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => __('WPF Quotes', 'wpf_quote'), 'read_more' => 0, 'quote_pool' => 10));
		$title = esc_attr($instance['title']);
		$read_more = esc_attr($instance['read_more']);
		$quote_pool = $instance['quote_pool'];
		
  	echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title', 'wpf_quote') . ':</label>';
  	echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '"></p>';
  	
  	echo '<p><label for="' . $this->get_field_id('read_more') . '">' . __('Show "Read more Quotes"-link', 'wpf_quote') . ':</label>';
  	echo '<input class="widefat" id="' . $this->get_field_id('read_more') . '" name="' . $this->get_field_name('read_more') . '" type="checkbox" value="1"' . checked($read_more, '1', false) . '>';
  	
  	echo '<p><label for"' . $this->get_field_id('quote_pool') . '">' . __('Select quote out of ... quotes', 'wpf_quote') . ':</label>';
  	echo '<input class="widefat" id="' . $this->get_field_id('quote_pool') . '" name="' . $this->get_field_name('quote_pool') . '" type="text" value="' . $quote_pool . '" maxlength="4"></p>';
	}

	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$read_more = $instance['read_more'];
		$quote_pool = $instance['quote_pool'];

		echo $before_widget;

		if (!empty($title)) echo $before_title . $title . $after_title;;

		// Query
		global $wpdb;
		$wpf_quote_query = $wpdb->prepare("
			SELECT * FROM (
					SELECT $wpdb->posts.*
					FROM $wpdb->posts
					WHERE $wpdb->posts.post_type = 'quote'
					AND ($wpdb->posts.post_status = 'publish' or $wpdb->posts.post_status = 'private')
					ORDER BY $wpdb->posts.post_date
					DESC LIMIT 0, %u
				) AS wpf_temp
				ORDER BY RAND()
				LIMIT 1", $quote_pool);
		$quotes = $wpdb->get_results($wpf_quote_query, OBJECT);

		if ($quotes) {
			global $post;
			foreach ($quotes as $post) {
				setup_postdata($post);
				wpf_quote_print();
			}
			// Restore $post global to the current post in the main query.
			wp_reset_postdata();

			if (!empty($read_more)) {
				printf('<p><a href="%s">%s</a></p>', esc_url(home_url()) . '/quote/', __('Read more quotes', 'wpf_quote'));
			}
			
		} else {
			echo '<p>' . __('There are no quotes yet', 'wpf_quote') . '</p>';
		}
		echo $after_widget;
	}

}
add_action('widgets_init', create_function('', 'register_widget("wpf_quote");'));
?>