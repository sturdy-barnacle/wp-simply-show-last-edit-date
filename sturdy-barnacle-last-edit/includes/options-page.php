<?php
/**
 * Options page functionality
 *
 * Handles the plugin settings page in WordPress admin.
 *
 * @package SB_Show_Last_Edit_Date
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the options page
 */
function sb_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get all public post types as objects (we need labels for display)
    $post_types = get_post_types(array('public' => true), 'objects');
    
    // Migrate old settings to new format (one-time migration)
    // Old format: sb_global_disable_posts, sb_global_disable_pages (plural)
    // New format: sb_global_disable_post, sb_global_disable_page (singular, from post type name)
    if (get_option('sb_global_disable_posts') !== false && get_option('sb_global_disable_post') === false) {
        update_option('sb_global_disable_post', get_option('sb_global_disable_posts'));
        delete_option('sb_global_disable_posts');
    }
    if (get_option('sb_global_disable_pages') !== false && get_option('sb_global_disable_page') === false) {
        update_option('sb_global_disable_page', get_option('sb_global_disable_pages'));
        delete_option('sb_global_disable_pages');
    }
    
    // Handle form submission
    if (isset($_POST['sb_settings_submit'])) {
        check_admin_referer('sb_settings_nonce');
        
        // Sanitize and save global disable options for all post types
        foreach ($post_types as $post_type) {
            $option_name = 'sb_global_disable_' . $post_type->name;
            $value = isset($_POST[$option_name]) ? 'on' : 'off';
            update_option($option_name, sanitize_text_field($value));
        }
        
        // Sanitize and save position option
        $sb_position_update_info = isset($_POST['sb_position_update_info']) ? 
            sanitize_text_field(wp_unslash($_POST['sb_position_update_info'])) : 'before';
        // Validate that it's one of the allowed values
        $sb_position_update_info = in_array($sb_position_update_info, array('before', 'after'), true) ? 
            $sb_position_update_info : 'before';
        update_option('sb_position_update_info', $sb_position_update_info);
        
        // Show settings saved message
        add_settings_error(
            'sb_settings',
            'sb_settings_updated',
            __('Settings saved.', 'sturdy-barnacle-last-edit'),
            'updated'
        );
    }

    // Get current option values
    $sb_position_update_info = get_option('sb_position_update_info', 'before');

    // Display settings
    settings_errors('sb_settings');
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('SB Show Last Edit Date', 'sturdy-barnacle-last-edit'); ?></h1>
        
        <p><?php echo esc_html__('Configure settings for the last edit date display.', 'sturdy-barnacle-last-edit'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('sb_settings_nonce'); ?>
            
            <h2><?php echo esc_html__('Display Options', 'sturdy-barnacle-last-edit'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Last Edit Info Position', 'sturdy-barnacle-last-edit'); ?></th>
                    <td>
                        <select name="sb_position_update_info">
                            <option value="before" <?php selected($sb_position_update_info, 'before'); ?>>
                                <?php echo esc_html__('Before Content', 'sturdy-barnacle-last-edit'); ?>
                            </option>
                            <option value="after" <?php selected($sb_position_update_info, 'after'); ?>>
                                <?php echo esc_html__('After Content', 'sturdy-barnacle-last-edit'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('Global Disable Options', 'sturdy-barnacle-last-edit'); ?></h2>
            <table class="form-table">
                <?php foreach ($post_types as $post_type) : 
                    $option_name = 'sb_global_disable_' . $post_type->name;
                    $option_value = get_option($option_name, 'off');
                    $label = sprintf(
                        /* translators: %s: post type label (plural) */
                        __('Disable for all %s', 'sturdy-barnacle-last-edit'),
                        $post_type->labels->name
                    );
                ?>
                <tr valign="top">
                    <th scope="row"><?php echo esc_html($label); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_attr($option_name); ?>" <?php checked($option_value, 'on'); ?> />
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <p class="submit">
                <input type="submit" name="sb_settings_submit" class="button button-primary" 
                    value="<?php echo esc_attr__('Save Changes', 'sturdy-barnacle-last-edit'); ?>" />
            </p>
        </form>
        
        <p>
            <?php
            printf(
                /* translators: 1: Link to review page, 2: Link to GitHub repository */
                esc_html__('Thank you for using %1$s! If you like this plugin, please consider %2$s or %3$s.', 'sturdy-barnacle-last-edit'),
                '<strong>' . esc_html__('SB Show Last Edit Date', 'sturdy-barnacle-last-edit') . '</strong>',
                sprintf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    'https://ko-fi.com/kristinaq',
                    esc_html__('sending me a tip', 'sturdy-barnacle-last-edit')
                ),
                sprintf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    'https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date',
                    esc_html__('contributing to its development', 'sturdy-barnacle-last-edit')
                )
            );
            ?>
        </p>
    </div>
    <?php
}
