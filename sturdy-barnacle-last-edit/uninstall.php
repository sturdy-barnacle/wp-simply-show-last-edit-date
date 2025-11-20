<?php
/**
 * Uninstall script
 *
 * Fired when the plugin is uninstalled.
 *
 * @package SB_Show_Last_Edit_Date
 */

// If uninstall not called from WordPress, exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options.
delete_option('sb_global_disable_posts');
delete_option('sb_global_disable_pages');
delete_option('sb_position_update_info');

// Delete post meta for all posts and pages.
global $wpdb;

$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
        'sb_disable_update_info'
    )
);

// Clear any cached data that has been generated.
wp_cache_flush();
