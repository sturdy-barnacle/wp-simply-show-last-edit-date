<?php
/**
 * Display last updated information
 *
 * Handles the display of last edit date and time on posts and pages.
 *
 * @package SB_Show_Last_Edit_Date
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display last updated information on posts and pages
 * 
 * @param string $content The post content.
 * @return string The post content with last updated info.
 */
function sb_display_last_updated_info($content) {
    // Only show on singular post or page
    if (!is_singular(['post', 'page'])) {
        return $content;
    }
    
    // Check global disable settings first
    $post_type = get_post_type();
    if ($post_type === 'post' && get_option('sb_global_disable_posts', 'off') === 'on') {
        return $content;
    }
    if ($post_type === 'page' && get_option('sb_global_disable_pages', 'off') === 'on') {
        return $content;
    }
    
    // Check individual post/page setting
    $sb_disable_update_info = get_post_meta(get_the_ID(), 'sb_disable_update_info', true);
    if ($sb_disable_update_info == 'on') {
        return $content;
    }

    // Get the last updated date and time
    try {
        $timezone_string = get_option('timezone_string');
        if ($timezone_string) {
            $timezone = new DateTimeZone($timezone_string);
            $last_updated_date = new DateTime(get_the_modified_date('Y-m-d H:i:s'), $timezone);
            $last_updated = sprintf(
                '%1$s %2$s %3$s %4$s',
                esc_html__('Last updated on:', 'sturdy-barnacle-last-edit'),
                esc_html($last_updated_date->format('F j, Y')),
                esc_html__('at', 'sturdy-barnacle-last-edit'),
                esc_html($last_updated_date->format('g:i a'))
            );
        } else {
            $last_updated = sprintf(
                '%1$s %2$s %3$s %4$s',
                esc_html__('Last updated on:', 'sturdy-barnacle-last-edit'),
                esc_html(get_the_modified_date('F j, Y')),
                esc_html__('at', 'sturdy-barnacle-last-edit'),
                esc_html(get_the_modified_time('g:i a'))
            );
        }
    } catch (Exception $e) {
        // Fallback in case of date handling error
        $last_updated = esc_html__('Last updated recently', 'sturdy-barnacle-last-edit');
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('SB Show Last Edit Date error: ' . $e->getMessage());
        }
    }

    // Create the HTML
    $sb_last_updated_html = sprintf(
        '<p class="sb-last-updated">%s</p>',
        $last_updated
    );

    // Get position setting
    $sb_position_update_info = get_option('sb_position_update_info', 'before');
    
    // Apply position setting
    if ($sb_position_update_info === 'before') {
        return $sb_last_updated_html . $content;
    } else {
        return $content . $sb_last_updated_html;
    }
}

add_filter('the_content', 'sb_display_last_updated_info');
