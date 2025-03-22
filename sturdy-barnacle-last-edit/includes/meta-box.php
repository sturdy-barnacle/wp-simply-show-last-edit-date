<?php
/**
 * Add custom meta box to post and page editing screens
 */
function sb_add_custom_meta_box()
{
    add_meta_box(
        'sb_meta_box',
        __('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'),
        'sb_meta_box_callback',
        ['post', 'page'],
        'side',  // Position on the side
        'default'  // Priority
    );
}
add_action('add_meta_boxes', 'sb_add_custom_meta_box');

/**
 * Render the meta box content
 * 
 * @param WP_Post $post The post object
 */
function sb_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('sb_meta_box', 'sb_meta_box_nonce');
    
    // Get existing value
    $sb_disable_update_info = get_post_meta($post->ID, 'sb_disable_update_info', true);

    // Get the post type
    $post_type = get_post_type($post);
    
    // Get global settings to show info
    $global_setting = 'off';
    if ($post_type === 'post') {
        $global_setting = get_option('sb_global_disable_posts', 'off');
    } elseif ($post_type === 'page') {
        $global_setting = get_option('sb_global_disable_pages', 'off');
    }
    
    // Show global setting message if needed
    if ($global_setting === 'on') {
        echo '<p class="description">';
        if ($post_type === 'post') {
            esc_html_e('Note: Last edit info is globally disabled for all posts.', 'sturdy-barnacle-last-edit');
        } else {
            esc_html_e('Note: Last edit info is globally disabled for all pages.', 'sturdy-barnacle-last-edit'); 
        }
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
 * @param int $post_id The post ID
 */
function sb_save_meta_box_data($post_id)
{
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
