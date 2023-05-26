<?php
/*
Plugin Name: SB Show Last Edit Date
Plugin URI: https://sturdybarnacle.com
Description: Display the last date and time a post or page was updated.
Version: 1.0.0
Author: Kristina Quinones
Author URI: https://sturdybarnacle.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
*/

// Enqueue CSS
function sb_add_custom_css()
{
    wp_enqueue_style('sb-custom-css', plugin_dir_url(__FILE__) . 'css/sturdy-barnacle-last-edit.css');
}
add_action('wp_enqueue_scripts', 'sb_add_custom_css');

// Include additional files
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-box.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-info.php';
require_once plugin_dir_path(__FILE__) . 'includes/options-page.php';