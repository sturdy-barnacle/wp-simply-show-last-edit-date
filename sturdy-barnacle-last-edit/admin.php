<?php
function sturdy_barnacle_last_edit_settings_page() {
    add_options_page(
        'Sturdy Barnacle Last Edit Settings',
        'Sturdy Barnacle Last Edit',
        'manage_options',
        'sturdy-barnacle-last-edit',
        'sturdy_barnacle_last_edit_settings'
    );
}

function sturdy_barnacle_last_edit_settings() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }

    if ( isset( $_POST['sturdy_barnacle_last_edit_text'] ) ) {
        $last_edit_text = sanitize_text_field( $_POST['sturdy_barnacle_last_edit_text'] );
        update_option( 'sturdy_barnacle_last_edit_text', $last_edit_text );
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    if ( isset( $_POST['sturdy_barnacle_last_edit_global_enable_plugin'] ) ) {
        $
