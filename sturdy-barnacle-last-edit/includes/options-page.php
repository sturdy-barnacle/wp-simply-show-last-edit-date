<?php
function sb_options_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_POST['sb_global_disable_submit'])) {
        check_admin_referer('sb_global_disable');
        $sb_global_disable_posts = isset($_POST['sb_global_disable_posts']) ? 'on' : 'off';
        update_option('sb_global_disable_posts', $sb_global_disable_posts);

        $sb_global_disable_pages = isset($_POST['sb_global_disable_pages']) ? 'on' : 'off';
        update_option('sb_global_disable_pages', $sb_global_disable_pages);
    }

    $sb_global_disable_posts = get_option('sb_global_disable_posts', 'off');
    $sb_global_disable_pages = get_option('sb_global_disable_pages', 'off');

    echo '<div class="wrap">';
    echo '<h1>Sturdy Barnacle Last Edit</h1>';
    echo '<p>Use the options below to globally disable the last edit info for all posts and/or pages.</p>';
    echo '<form method="post" action="">';
    wp_nonce_field('sb_global_disable');
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable for all Posts</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_posts" ' . checked($sb_global_disable_posts, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">Disable for all Pages</th>';
    echo '<td><input type="checkbox" name="sb_global_disable_pages" ' . checked($sb_global_disable_pages, 'on', false) . ' /></td>';
    echo '</tr>';
    echo '</table>';
    echo '<input type="submit" name="sb_global_disable_submit" class="button button-primary" value="Save Changes" />';
    echo '</form>';
    // Add the new text
    echo '<p>';
    printf(
        __('Thank you for using <strong>SB Show Last Edit Date</strong>! If you like this plugin, please consider <a href="%s" target="_blank">leaving a review</a> or <a href="%s" target="_blank">contributing to its development</a>.', 'sturdy-barnacle-last-edit'),
        'https://sturdybarnacle.com',
        'https://github.com/sturdy-barnacle/'
    );
    echo '</p>';
    echo '</div>';
}
