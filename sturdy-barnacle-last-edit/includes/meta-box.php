<?php
/**
 * Meta box functionality
 *
 * Handles the custom meta box on post and page editing screens.
 *
 * @package SB_Show_Last_Edit_Date
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta box to all public post type editing screens
 */
function sb_add_custom_meta_box() {
    // Get all public post types
    $post_types = get_post_types(array('public' => true));
    
    add_meta_box(
        'sb_meta_box',
        __('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'),
        'sb_meta_box_callback',
        $post_types,
        'side',  // Position on the side
        'default'  // Priority
    );
}
add_action('add_meta_boxes', 'sb_add_custom_meta_box');

/**
 * Render the meta box content
 * 
 * @param WP_Post $post The post object.
 */
function sb_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('sb_meta_box', 'sb_meta_box_nonce');
    
    // Get existing value
    $sb_disable_update_info = get_post_meta($post->ID, 'sb_disable_update_info', true);

    // Get the post type
    $post_type = get_post_type($post);
    $post_type_object = get_post_type_object($post_type);
    
    // Get global settings to show info
    $option_name = 'sb_global_disable_' . $post_type;
    $global_setting = get_option($option_name, 'off');
    
    // Show global setting message if needed
    if ($global_setting === 'on') {
        echo '<p class="description">';
        printf(
            /* translators: %s: post type label (plural, lowercase) */
            esc_html__('Note: Last edit info is globally disabled for all %s.', 'sturdy-barnacle-last-edit'),
            esc_html(strtolower($post_type_object->labels->name))
        );
        echo '</p>';
    }

    // Checkbox for disabling last edit info
    echo '<label for="sb_disable_update_info">' . esc_html__('Disable Last Edit Info:', 'sturdy-barnacle-last-edit') . '</label>';
    echo '<input type="checkbox" id="sb_disable_update_info" name="sb_disable_update_info" ' . checked($sb_disable_update_info, 'on', false) . '/><br>';
    echo '<p class="description">' . esc_html__('Check this box to hide the last edit date information for this specific content.', 'sturdy-barnacle-last-edit') . '</p>';
}

/**
 * Save the meta box data
 * 
 * @param int $post_id The post ID.
 */
function sb_save_meta_box_data($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['sb_meta_box_nonce'])) {
        return;
    }
    
    // Verify the nonce
    if (!wp_verify_nonce($_POST['sb_meta_box_nonce'], 'sb_meta_box')) {
        return;
    }
    
    // If this is an autosave, we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sanitize and save the checkbox value
    $sb_disable_update_info = isset($_POST['sb_disable_update_info']) ? 'on' : 'off';
    update_post_meta($post_id, 'sb_disable_update_info', sanitize_text_field($sb_disable_update_info));
}
add_action('save_post', 'sb_save_meta_box_data');
