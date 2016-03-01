<?php

/* 
 * To hide some functions from admin screen.
 * 
 */


/*
 * Hide side bar menus
 */
add_action('admin_menu', 'remove_sidebar_menus');
function remove_sidebar_menus() {
    remove_menu_page('edit-comments.php');
    
    //hide from editor users
    if (!current_user_can('administrator')) {
        remove_menu_page('index.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('tools.php');
    }
}


/*
 * Hide admin bar menus
 */
add_action('admin_bar_menu', 'remove_adminbar_menus', 201);
function remove_adminbar_menus($wp_admin_bar) {
    $wp_admin_bar->remove_menu( 'view' );
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('new-page');
    $wp_admin_bar->remove_menu('new-media');
    $wp_admin_bar->remove_menu('customize');
    
    if (!current_user_can('administrator')) {
        $wp_admin_bar->remove_menu('updates');
    }
}


/*
 * Skip notification of version updates
 */
if (!current_user_can('administrator')) {
    add_filter('pre_site_transient_update_core', '__return_zero');
    add_action('admin_menu', 'remove_wp_update_notification');
}
function remove_wp_update_notification() {
    remove_action( 'admin_notices', 'update_nag');
    remove_action('wp_version_check', 'wp_version_check');
    remove_action('admin_init', '_maybe_update_core');
}


/*
 * Hide admin page by redirection
 */
if (!current_user_can('administrator')) {
    add_action( 'admin_init', 'redirect_dashboard' );
}
function redirect_dashboard () {
    $admin_page_url = filter_input(INPUT_SERVER, 'SCRIPT_NAME' );
    if ( '/wp-admin/index.php' === $admin_page_url) {
        wp_redirect( admin_url( 'edit.php?post_type=videos' ) );
    }
}


/*
 * Hide footer version info 
 */
if (!current_user_can('administrator')) {
    add_action('admin_menu', 'remove_footer_version');
}
function remove_footer_version() {
    remove_filter('update_footer', 'core_update_footer');
}


/*
 * Hide permalink function from all post type
 */
if (!current_user_can('administrator')) {
    add_filter( 'get_sample_permalink_html', '__return_false' );
    add_filter( 'get_shortlink', '__return_false' );
}