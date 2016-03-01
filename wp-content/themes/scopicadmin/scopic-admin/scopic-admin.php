<?php

/*
 * Get Started
 * Load this file in function.php in theme
 * ex : require_once locate_template('/scopic-admin-functions/scopic-admin.php');
 */


/*
 * Load styles
 */
add_action( 'admin_enqueue_scripts', 'load_video_post_style' );
function load_video_post_style() {
    wp_enqueue_style('scpic-admin-style', get_template_directory_uri().'/scopic-admin/css/scopic-admin-style.css');
    
    wp_enqueue_style('video-uploader-style', get_template_directory_uri().'/scopic-admin/video-uploader/video-uploader-style.css');
}


/*
 * Load scripts
 */
add_action( 'admin_enqueue_scripts', 'load_video_post_script' );
function load_video_post_script() {
    //load jquery
    //wp_deregister_script('jquery');
    wp_enqueue_script('original-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
    
    wp_enqueue_script('video-uploader-script', get_template_directory_uri().'/scopic-admin/video-uploader/video-uploader-functions.js');
}


/*
 * Load libs
 */
require_once locate_template('scopic-admin/video-post.php');
require_once locate_template('scopic-admin/modify-func.php');
require_once locate_template('scopic-admin/hide-func.php');