<?php

/* 
 * Call in jQuery in ./video-uploader-view.php
 */


/*
 * Load wp asset
 */
require_once '../include/wp-assets.php';


/*
 * Entry Point
 */
$video_post_id = filter_input(INPUT_POST, 'video_duration_post_id');
save_video_duration($video_post_id);


function save_video_duration ( $post_id ) {
    if ( !isset($post_id) || is_null($post_id) ) {
        echo 'Null ID';
        return $post_id;
    }
    
    $video_duration_nonce_action = 'video-duration-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_duration_nonce = filter_input(INPUT_POST, 'video_duration_nonce');
    if (!wp_verify_nonce($video_duration_nonce, $video_duration_nonce_action)) {
        echo 'Nonce error return';
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        echo 'Revision Error';
        return $post_id;
    }
    
    $video_duration_input_name = 'video_duration';
    $updated_video_duration = filter_input(INPUT_POST, $video_duration_input_name);
    if ( isset($updated_video_duration) && !is_null($updated_video_duration) ) {
        $video_duration_meta_key = 'video-duration';
        update_post_meta($post_id, $video_duration_meta_key, $updated_video_duration);
        echo $updated_video_duration;
    }
}
