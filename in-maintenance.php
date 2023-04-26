<?php
/*
Plugin Name: Maintenance Mode
Description: Light plugin that puts the website in maintenance mode and displays a custom message to visitors.
Version: 1.2
Author: Reese St Amant
Author URI: https://bettameta.com/
*/

// Add a maintenance mode page
function maintenance_mode() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sorry this site is under maintenance. Please check back later!' );
    }
}
add_action( 'wp', 'maintenance_mode' );

// Redirect non-logged in users to the maintenance mode page
function redirect_to_maintenance_mode() {
    if ( ! current_user_can( 'manage_options' ) && ! is_admin() && ! is_user_logged_in() ) {
        wp_redirect( home_url( '/maintenance-mode/' ) );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_to_maintenance_mode' );

// Add a rewrite rule for the maintenance mode page
function maintenance_mode_rewrite() {
    add_rewrite_rule( 'maintenance-mode/?$', 'index.php?maintenance-mode=1', 'top' );
}
add_action( 'init', 'maintenance_mode_rewrite' );

// Add a query variable for the maintenance mode page
function maintenance_mode_query_vars( $query_vars ) {
    $query_vars[] = 'maintenance-mode';
    return $query_vars;
}
add_filter( 'query_vars', 'maintenance_mode_query_vars' );

// Load the maintenance mode page template
function maintenance_mode_template( $template ) {
    if ( get_query_var( 'maintenance-mode' ) ) {
        return plugin_dir_path( __FILE__ ) . 'maintenance-mode-template.php';
    }
    return $template;
}
add_filter( 'template_include', 'maintenance_mode_template' );
?>
