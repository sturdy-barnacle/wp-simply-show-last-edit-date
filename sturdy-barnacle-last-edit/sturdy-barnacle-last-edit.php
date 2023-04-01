<?php
/*
Plugin Name: Sturdy Barnacle Last Edit
Description: A simple plugin to display the last date and time a post or page was updated with options to disable and position the display.
Version: 1.0
Author: Your Name
Author URI: https://yourwebsite.com
*/

// Add custom field to post and page editing screens
function sb_add_custom_meta_box() {
    add_meta_box('sb_meta_box', 'Sturdy Barnacle Last Edit', 'sb_meta_box_callback', ['post', 'page']);
}
add_action('add_meta_boxes', 'sb_add_custom_meta_box');

function sb_meta_box_callback($post) {
    wp_nonce_field('sb_meta_box', 'sb_meta_box_nonce');
    $sb_disable_update_info = get_post_meta($post->ID, 'sb_disable_update_info', true);
    $sb_position_update_info = get_post_meta($post->ID, 'sb_position_update_info', true);

    echo '<label for="sb_disable_update_info">Disable Last Edit Info:</label>';
    echo '<input type="checkbox" id="sb_disable_update_info" name="sb_disable_update_info" ' . checked($sb_disable_update_info, 'on', false) . '/><br>';

    echo '<label for="sb_position_update_info">Position of Last Edit Info:</label>';
    echo '<select id="sb_position_update_info" name="sb_position_update_info">';
    echo '<option value="before"' . selected($sb_position_update_info, 'before', false) . '>Before Content</option>';
    echo '<option value="after"' . selected($sb_position_update_info, 'after', false) . '>After Content</option>';
    echo '</select>';
}

function sb_save_meta_box_data($post_id) {
    if (!isset($_POST['sb_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['sb_meta_box_nonce'], 'sb_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $sb_disable_update_info = isset($_POST['sb_disable_update_info']) ? 'on' : 'off';
    update_post_meta($post_id, 'sb_disable_update_info', $sb_disable_update_info);

    $sb_position_update_info = isset($_POST['sb_position_update_info']) ? sanitize_text_field($_POST['sb_position_update_info']) : 'before';
    update_post_meta($post_id, 'sb_position_update_info', $sb_position_update_info);
}
add_action('save_post', 'sb_save_meta_box_data');

function sb_display_last_updated_info($content) {
    if (!is_singular(['post', 'page'])) {
        return $content;
    }

    $sb_disable_update_info = get_post_meta(get_the_ID(), 'sb_disable_update_info', true);
    $sb_position_update_info = get_post_meta(get_the_ID(), 'sb_position_update_info', true);
    if ($sb_disable_update_info == 'on') {
        return $content;
        }
        $last_updated = 'Last updated on: ' . get_the_modified_date('F j, Y') . ' at ' . get_the_modified_time('g:i a');
        $sb_last_updated_html = '<p class="sb-last-updated">' . $last_updated . '</p>';
        
        if ($sb_position_update_info == 'before') {
            return $sb_last_updated_html . $content;
        } else {
            return $content . $sb_last_updated_html;
        }
    }
    add_filter('the_content', 'sb_display_last_updated_info');
    
    function sb_add_custom_css() {
    echo '<style>
    .sb-last-updated {
    font-size: 0.8em;
    font-style: italic;
    color: #777;
    }
    </style>';
    }
    add_action('wp_head', 'sb_add_custom_css');
    
    // Adding global disable options
    function sb_add_options_page() {
    add_options_page('Sturdy Barnacle Last Edit', 'Sturdy Barnacle Last Edit', 'manage_options', 'sturdy-barnacle-last-edit', 'sb_options_page');
    }
    add_action('admin_menu', 'sb_add_options_page');
    
    function sb_options_page() {
    if (!current_user_can('manage_options')) {
    return;
    }
    if (isset($_POST['sb_global_disable_submit'])) {
        check_admin_referer('sb_global_disable');
        $sb_global_disable_posts = isset($_POST['sb_global_disable_posts']) ? 'on' : 'off';
        update_option('sb_global_disable_posts', $sb_global_disable_posts);
    
        $sb_global_disable_pages = isset($_POST['sb_global_disable_pages']) ? 'on' : 'off';
        update_option('sb_global_disable_pages', $sb_global_disable_pages);
    }
    
    $sb_global_disable_posts = get_option('sb_global_disable_posts', 'off');
    $sb_global_disable_pages = get_option('sb_global_disable_pages', 'off');
    
    echo '<div class="wrap">';
    echo '<h1>Sturdy Barnacle Last Edit</h1>';
    echo '<form method="post" action="">';
    wp_nonce_field('sb_global_disable');
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable Last Edit Info for Posts</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_posts" ' . checked($sb_global_disable_posts, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable Last Edit Info for Pages</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_pages" ' . checked($sb_global_disable_pages, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '</table>';
    echo '<input type="submit" name="sb_global_disable_submit" class="button button-primary" value="Save Changes" />';
    echo '</form>';
    echo '</div>';
    }    