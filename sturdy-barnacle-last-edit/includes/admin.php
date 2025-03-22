<?php
// Adding global disable options
function sb_add_options_page()
{
    add_options_page('SB Show Last Edit Date', 'SB Show Last Edit Date', 'manage_options', 'sturdy-barnacle-last-edit', 'sb_options_page');
}
add_action('admin_menu', 'sb_add_options_page');

// Add link to Settings from the Plugins page listing
function sb_plugin_action_links($links, $plugin_file)
{
    if (plugin_basename(dirname(__DIR__) . '/sturdy-barnacle-last-edit.php') === $plugin_file) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=sturdy-barnacle-last-edit'),
            __('Settings', 'sturdy-barnacle-last-edit')
        );

        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'sb_plugin_action_links', 10, 2);
