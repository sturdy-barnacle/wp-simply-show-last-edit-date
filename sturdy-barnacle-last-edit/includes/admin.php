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
 * Register settings, sections, and fields
 */
function sb_register_settings() {
    // Register settings
    register_setting('sb_options_group', 'sb_options', 'sb_sanitize_options');

    // Add settings sections
    add_settings_section(
        'sb_display_settings',
        __('Display Options', 'sturdy-barnacle-last-edit'),
        'sb_render_display_settings_section',
        'sturdy-barnacle-last-edit'
    );

    // Add display fields
    add_settings_field(
        'sb_position_update_info',
        __('Last Edit Info Position', 'sturdy-barnacle-last-edit'),
        'sb_render_position_field',
        'sturdy-barnacle-last-edit',
        'sb_display_settings'
    );

    add_settings_section(
        'sb_global_settings',
        __('Global Disable Options', 'sturdy-barnacle-last-edit'),
        'sb_render_global_settings_section',
        'sturdy-barnacle-last-edit'
    );

    // Add global disable fields
    add_settings_field(
        'sb_global_disable_posts',
        __('Disable for all Posts', 'sturdy-barnacle-last-edit'),
        'sb_render_disable_posts_field',
        'sturdy-barnacle-last-edit',
        'sb_global_settings'
    );

    add_settings_field(
        'sb_global_disable_pages',
        __('Disable for all Pages', 'sturdy-barnacle-last-edit'),
        'sb_render_disable_pages_field',
        'sturdy-barnacle-last-edit',
        'sb_global_settings'
    );
}
add_action('admin_init', 'sb_register_settings');

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

/**
 * Sanitize options
 *
 * @param array $input Input options.
 * @return array Sanitized options.
 */
function sb_sanitize_options($input) {
    $output = array();

    // Sanitize position option
    if (isset($input['position_update_info'])) {
        $output['position_update_info'] = in_array($input['position_update_info'], array('before', 'after'), true) ? 
            $input['position_update_info'] : 'before';
    }

    // Sanitize global disable options
    $output['global_disable_posts'] = isset($input['global_disable_posts']) ? 1 : 0;
    $output['global_disable_pages'] = isset($input['global_disable_pages']) ? 1 : 0;

    return $output;
}
