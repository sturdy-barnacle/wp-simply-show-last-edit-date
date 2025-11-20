<?php
/**
 * Admin functionality
 *
 * Handles admin menu and plugin action links.
 *
 * @package SB_Show_Last_Edit_Date
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add options page to WordPress admin menu
 */
function sb_add_options_page() {
    add_options_page(
        __('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'),
        __('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'),
        'manage_options',
        'sturdy-barnacle-last-edit',
        'sb_options_page'
    );
}
add_action('admin_menu', 'sb_add_options_page');

/**
 * Add settings link to plugin action links
 *
 * @param array  $links Array of plugin action links.
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @return array Modified array of plugin action links.
 */
function sb_plugin_action_links($links, $plugin_file) {
    if (plugin_basename(dirname(__DIR__) . '/sturdy-barnacle-last-edit.php') === $plugin_file) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('options-general.php?page=sturdy-barnacle-last-edit')),
            esc_html__('Settings', 'sturdy-barnacle-last-edit')
        );

        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'sb_plugin_action_links', 10, 2);
