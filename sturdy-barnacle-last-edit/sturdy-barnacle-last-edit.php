<?php
/**
 * Plugin Name: Sturdy Barnacle Last Edit
 * Plugin URI: https://example.com/
 * Description: Allows users to customize the text before the modified date and enable/disable the plugin on a per-post and per-page basis.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://example.com/
 */

function sturdy_barnacle_last_edit( $content ) {
    if ( !is_singular() ) return $content;

    $post_enable_plugin = get_post_meta( get_the_ID(), 'sturdy_barnacle_last_edit_enable_plugin', true );
    $page_enable_plugin = get_post_meta( get_the_ID(), 'sturdy_barnacle_last_edit_enable_plugin', true );
    $global_enable_plugin = get_option( 'sturdy_barnacle_last_edit_global_enable_plugin', 'yes' );

    if ( $global_enable_plugin === 'no' || ( is_page() && $page_enable_plugin === 'no' ) || ( is_single() && $post_enable_plugin === 'no' ) ) {
        $modified_time = '';
    } else {
        $last_edit_text = get_option( 'sturdy_barnacle_last_edit_text', 'Last updated on' );
        $modified_time = get_the_modified_time( 'F j, Y' );
    }

    $modified_content = $content . '<p>' . $last_edit_text . ' ' . $modified_time . '</p>';

    return $modified_content;
}

add_filter( 'the_content', 'sturdy_barnacle_last_edit' );

require_once( plugin_dir_path( __FILE__ ) . 'admin.php' );
