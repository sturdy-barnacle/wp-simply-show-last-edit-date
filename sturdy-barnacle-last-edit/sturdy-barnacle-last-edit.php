<?php
/**
 * SB Show Last Edit Date
 *
 * @package SB_Show_Last_Edit_Date
 * @author Kristina Quinones
 * @copyright 2025 Kristina Quinones
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: SB Show Last Edit Date
 * Plugin URI: https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date
 * Description: Display the last date and time a post or page was updated.
 * Version: 1.0.2
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Author: Kristina Quinones
 * Author URI: https://github.com/sturdy-barnacle
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: sturdy-barnacle-last-edit
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SB_LAST_EDIT_VERSION', '1.0.2');
define('SB_LAST_EDIT_MIN_WP_VERSION', '5.2');
define('SB_LAST_EDIT_MIN_PHP_VERSION', '7.4');

// Check PHP version
if (version_compare(PHP_VERSION, SB_LAST_EDIT_MIN_PHP_VERSION, '<')) {
    add_action('admin_notices', 'sb_last_edit_php_version_notice');
    return;
}

// Check WordPress version
if (version_compare(get_bloginfo('version'), SB_LAST_EDIT_MIN_WP_VERSION, '<')) {
    add_action('admin_notices', 'sb_last_edit_wp_version_notice');
    return;
}

/**
 * Admin notice for PHP version
 */
function sb_last_edit_php_version_notice() {
    echo '<div class="error"><p>';
    printf(
        /* translators: %s: minimum required PHP version */
        esc_html__('SB Show Last Edit Date requires PHP version %s or higher. Please upgrade PHP.', 'sturdy-barnacle-last-edit'),
        esc_html(SB_LAST_EDIT_MIN_PHP_VERSION)
    );
    echo '</p></div>';
}

/**
 * Admin notice for WordPress version
 */
function sb_last_edit_wp_version_notice() {
    echo '<div class="error"><p>';
    printf(
        /* translators: %s: minimum required WordPress version */
        esc_html__('SB Show Last Edit Date requires WordPress version %s or higher. Please upgrade WordPress.', 'sturdy-barnacle-last-edit'),
        esc_html(SB_LAST_EDIT_MIN_WP_VERSION)
    );
    echo '</p></div>';
}

/**
 * Load plugin text domain for translations
 */
function sb_last_edit_load_textdomain() {
    load_plugin_textdomain('sturdy-barnacle-last-edit', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'sb_last_edit_load_textdomain');

/**
 * Enqueue plugin CSS
 */
function sb_add_custom_css() {
    wp_enqueue_style(
        'sb-custom-css',
        plugin_dir_url(__FILE__) . 'css/sturdy-barnacle-last-edit.css',
        array(),
        SB_LAST_EDIT_VERSION
    );
}
add_action('wp_enqueue_scripts', 'sb_add_custom_css');

/**
 * Migrate old options to new format
 */
function sb_migrate_options() {
    // Check if migration is needed
    if (get_option('sb_options_migrated')) {
        return;
    }

    // Get old options
    $old_position = get_option('sb_position_update_info', 'before');
    $old_disable_posts = get_option('sb_global_disable_posts', 'off');
    $old_disable_pages = get_option('sb_global_disable_pages', 'off');

    // Prepare new options array
    $new_options = array(
        'position_update_info' => $old_position,
        'global_disable_posts' => ($old_disable_posts === 'on') ? 1 : 0,
        'global_disable_pages' => ($old_disable_pages === 'on') ? 1 : 0,
    );

    // Save new options
    update_option('sb_options', $new_options);

    // Mark as migrated
    update_option('sb_options_migrated', true);

    // Clean up old options
    delete_option('sb_position_update_info');
    delete_option('sb_global_disable_posts');
    delete_option('sb_global_disable_pages');
}
add_action('admin_init', 'sb_migrate_options');

// Include additional files
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-box.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-info.php';
require_once plugin_dir_path(__FILE__) . 'includes/options-page.php';
