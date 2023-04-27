<?php
/*
Plugin Name: Simply Show Last Edit Date
Plugin URI: https://sturdybarnacle.com
Description: Simply display the last date and time a post or page was updated.
Version: 1.0.0
Author: Kristina Quinones
Author URI: https://sturdybarnacle.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Text Domain: sturdy-barnacle-last-edit
*/

// Add custom field to post and page editing screens
function sb_add_custom_meta_box()
{
    add_meta_box('sb_meta_box', __('Simply Show Last Edit Date', 'sturdy-barnacle-last-edit'), 'sb_meta_box_callback', ['post', 'page']);
}
add_action('add_meta_boxes', 'sb_add_custom_meta_box');

function sb_meta_box_callback($post)
{
    wp_nonce_field('sb_meta_box', 'sb_meta_box_nonce');
    $sb_disable_update_info = get_post_meta($post->ID, 'sb_disable_update_info', true);

    echo '<label for="sb_disable_update_info">' . esc_html__('Disable Last Edit Info:', 'sturdy-barnacle-last-edit') . '</label>';
    echo '<input type="checkbox" id="sb_disable_update_info" name="sb_disable_update_info" ' . checked($sb_disable_update_info, 'on', false) . '/><br>';
}

function sb_save_meta_box_data($post_id)
{
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

function sb_display_last_updated_info($content)
{
    if (!is_singular(['post', 'page'])) {
        return $content;
    }

    $sb_disable_update_info = get_post_meta(get_the_ID(), 'sb_disable_update_info', true);
    if ($sb_disable_update_info == 'on') {
        return $content;
    }

    $timezone_string = get_option('timezone_string');
    if ($timezone_string) {
        $timezone = new DateTimeZone($timezone_string);
        $last_updated_date = new DateTime(get_the_modified_date('Y-m-d H:i:s'), $timezone);
        $last_updated = esc_html__('Last updated on:', 'sturdy-barnacle-last-edit') . ' ' . esc_html($last_updated_date->format('F j, Y')) . ' ' . esc_html__('at', 'sturdy-barnacle-last-edit') . ' ' . esc_html($last_updated_date->format('g:i a'));
    } else {
        $last_updated = esc_html__('Last updated on:', 'sturdy-barnacle-last-edit') . ' ' . esc_html(get_the_modified_date('F j, Y')) . ' ' . esc_html__('at', 'sturdy-barnacle-last-edit') . ' ' . esc_html(get_the_modified_time('g:i a'));
    }

    $sb_last_updated_html = '<p class="sb-last-updated">' . $last_updated . '</p>';

    $sb_position_update_info = get_option('sb_position_update_info', 'before');

    if ($sb_position_update_info == 'before') {
        return $sb_last_updated_html . $content;
    } else {
        return $content . $sb_last_updated_html;
    }
}

add_filter('the_content', 'sb_display_last_updated_info');

// CSS / Styles
function sb_add_custom_css()
{
    wp_enqueue_style('sb-custom-css', plugin_dir_url(__FILE__) . 'sturdy-barnacle-last-edit.css');
}
add_action('wp_enqueue_scripts', 'sb_add_custom_css');

// Adding global disable options
function sb_add_options_page()
{
    add_options_page('Simply Show Last Edit Date', 'Simply Show Last Edit Date', 'manage_options', 'sturdy-barnacle-last-edit', 'sb_options_page');
}
add_action('admin_menu', 'sb_add_options_page');

// Settings Page
function sb_options_page()
{
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
    echo '<p>Use the options below to globally disable the last edit info for all posts and/or pages.</p>';
    echo '<form method="post" action="">';
    wp_nonce_field('sb_global_disable');
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable for all Posts</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_posts" ' . checked($sb_global_disable_posts, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable for all Pages</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_pages" ' . checked($sb_global_disable_pages, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '</table>';
    echo '<input type="submit" name="sb_global_disable_submit" class="button button-primary" value="Save Changes" />';
    echo '</form>';
    // Add the new text
    echo '<p>';
    printf(
        __('Thank you for using <strong>Simply Show Last Edit Date</strong>! If you like this plugin, please consider <a href="%s" target="_blank">leaving a review</a> or <a href="%s" target="_blank">contributing to its development</a>.', 'sturdy-barnacle-last-edit'),
        'https://sturdybarnacle.com',
        'https://github.com/sturdy-barnacle/'
    );
    echo '</p>';
    echo '</div>';
}

// Add link to Settings from the Plugins page listing
function sb_plugin_action_links($links)
{
    $settings_link = sprintf(
        '<a href="%s">%s</a>',
        admin_url('options-general.php?page=sturdy-barnacle-last-edit'),
        __('Settings', 'sturdy-barnacle-last-edit')
    );

    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sb_plugin_action_links');

?>