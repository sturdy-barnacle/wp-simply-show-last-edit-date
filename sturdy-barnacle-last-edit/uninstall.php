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
// Delete position setting
delete_option('sb_position_update_info');

// Delete old format options (for backward compatibility with pre-1.0.3)
delete_option('sb_global_disable_posts');
delete_option('sb_global_disable_pages');

// Delete new format options for all post types (names array is sufficient)
$post_types = get_post_types(array('public' => true));
foreach ($post_types as $post_type) {
    delete_option('sb_global_disable_' . $post_type);
}

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
