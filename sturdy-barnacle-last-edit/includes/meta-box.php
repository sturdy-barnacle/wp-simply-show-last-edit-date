<?php
// Add custom field to post and page editing screens
function sb_add_custom_meta_box()
{
    add_meta_box('sb_meta_box', __('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'), 'sb_meta_box_callback', ['post', 'page']);
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
