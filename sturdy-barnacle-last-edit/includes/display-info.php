<?php
function sb_display_last_updated_info($content)
{
    if (!is_singular(['post', 'page'])) {
        return $content;
    }

    $sb_disable_update_info = get_post_meta(get_the_ID(), 'sb_disable_update_info', true);
    if ($sb_disable_update_info == 'on') {
        return $content;
    }

    $timezone_string = get_option('timezone_string');
    if ($timezone_string) {
        $timezone = new DateTimeZone($timezone_string);
        $last_updated_date = new DateTime(get_the_modified_date('Y-m-d H:i:s'), $timezone);
        $last_updated = esc_html__('Last updated on:', 'sturdy-barnacle-last-edit') . ' ' . esc_html($last_updated_date->format('F j, Y')) . ' ' . esc_html__('at', 'sturdy-barnacle-last-edit') . ' ' . esc_html($last_updated_date->format('g:i a'));
    } else {
        $last_updated = esc_html__('Last updated on:', 'sturdy-barnacle-last-edit') . ' ' . esc_html(get_the_modified_date('F j, Y')) . ' ' . esc_html__('at', 'sturdy-barnacle-last-edit') . ' ' . esc_html(get_the_modified_time('g:i a'));
    }

    $sb_last_updated_html = '<p class="sb-last-updated">' . $last_updated . '</p>';

    $sb_position_update_info = get_option('sb_position_update_info', 'before');

    if ($sb_position_update_info == 'before') {
        return $sb_last_updated_html . $content;
    } else {
        return $content . $sb_last_updated_html;
    }
}

add_filter('the_content', 'sb_display_last_updated_info');
