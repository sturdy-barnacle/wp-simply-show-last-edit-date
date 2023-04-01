<?php
/*
Plugin Name: Sturdy Barnacle Last Edit
Description: A simple plugin to display the last date and time a post was updated with an option to disable the display.
Version: 1.0
Author: Your Name
Author URI: https://yourwebsite.com
*/

// Add custom field to post editing screen
function sb_add_custom_meta_box() {
    add_meta_box('sb_meta_box', 'Sturdy Barnacle Last Edit', 'sb_meta_box_callback', 'post');
}
add_action('add_meta_boxes', 'sb_add_custom_meta_box');

function sb_meta_box_callback($post) {
    wp_nonce_field('sb_meta_box', 'sb_meta_box_nonce');
    $sb_disable_update_info = get_post_meta($post->ID, 'sb_disable_update_info', true);
    echo '<label for="sb_disable_update_info">Disable Last Edit Info:</label>';
    echo '<input type="checkbox" id="sb_disable_update_info" name="sb_disable_update_info" ' . checked($sb_disable_update_info, 'on', false) . '/>';
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
}
add_action('save_post', 'sb_save_meta_box_data');

function sb_display_last_updated_info($content) {
    if (!is_single()) {
        return $content;
    }
    $sb_disable_update_info = get_post_meta(get_the_ID(), 'sb_disable_update_info', true);
    if ($sb_disable_update_info == 'on') {
        return $content;
    }
    $last_updated = 'Last updated on: ' . get_the_modified_date('F j, Y') . ' at ' . get_the_modified_time('g:i a');
    return $content . '<p class="sb-last-updated">' . $last_updated . '</p>';
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

?>