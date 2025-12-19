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
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'sturdy-barnacle-last-edit'));
    }
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
                                <?php echo esc_html__('Top of post (default)', 'sturdy-barnacle-last-edit'); ?>
                            </option>
                            <option value="after" <?php selected($sb_position_update_info, 'after'); ?>>
                                <?php echo esc_html__('Bottom of post', 'sturdy-barnacle-last-edit'); ?>
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
                /* translators: 1: Plugin name, 2: Link to tip page, 3: Link to GitHub repository */
                esc_html__('Thank you for using %1$s! If you like this plugin, please consider %2$s or %3$s.', 'sturdy-barnacle-last-edit'),
                '<strong>' . esc_html__('SB Show Last Edit Date', 'sturdy-barnacle-last-edit') . '</strong>',
                '<a href="' . esc_url('https://ko-fi.com/kristinaq') . '" target="_blank">' . esc_html__('sending me a tip', 'sturdy-barnacle-last-edit') . '</a>',
                '<a href="' . esc_url('https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date') . '" target="_blank">' . esc_html__('contributing to its development', 'sturdy-barnacle-last-edit') . '</a>'
            );
            ?>
        </p>
    </div>
    <?php
}

/**
 * Render display settings section
 */
function sb_render_display_settings_section() {
    echo '<p>' . esc_html__('Configure the position where the last edit date will be displayed.', 'sturdy-barnacle-last-edit') . '</p>';
}

/**
 * Render position field
 */
function sb_render_position_field() {
    $options = get_option('sb_options', array());
    $position = isset($options['position_update_info']) ? $options['position_update_info'] : 'before';
    ?>
    <select name="sb_options[position_update_info]">
        <option value="before" <?php selected($position, 'before'); ?>>
            <?php echo esc_html__('Before Content', 'sturdy-barnacle-last-edit'); ?>
        </option>
        <option value="after" <?php selected($position, 'after'); ?>>
            <?php echo esc_html__('After Content', 'sturdy-barnacle-last-edit'); ?>
        </option>
    </select>
    <?php
}

/**
 * Render global settings section
 */
function sb_render_global_settings_section() {
    echo '<p>' . esc_html__('Configure global settings to disable the last edit date display for specific post types.', 'sturdy-barnacle-last-edit') . '</p>';
}

/**
 * Render disable posts field
 */
function sb_render_disable_posts_field() {
    $options = get_option('sb_options', array());
    $disable_posts = isset($options['global_disable_posts']) ? $options['global_disable_posts'] : 0;
    ?>
    <input type="checkbox" name="sb_options[global_disable_posts]" value="1" <?php checked(1, $disable_posts); ?> />
    <?php
}

/**
 * Render disable pages field
 */
function sb_render_disable_pages_field() {
    $options = get_option('sb_options', array());
    $disable_pages = isset($options['global_disable_pages']) ? $options['global_disable_pages'] : 0;
    ?>
    <input type="checkbox" name="sb_options[global_disable_pages]" value="1" <?php checked(1, $disable_pages); ?> />
    <?php
}
