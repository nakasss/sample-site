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
$video_post_id = filter_input(INPUT_POST, 'video_size_post_id');
save_video_size($video_post_id);


function save_video_size ( $post_id ) {
    if ( !isset($post_id) || is_null($post_id) ) {
        echo 'Null ID';
        return $post_id;
    }
    
    $video_size_nonce_action = 'video-size-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_size_nonce = filter_input(INPUT_POST, 'video_size_nonce');
    if (!wp_verify_nonce($video_size_nonce, $video_size_nonce_action)) {
        echo 'Nonce error return';
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        echo 'Revision Error';
        return $post_id;
    }
    
    $video_size_input_name = 'video_size';
    $updated_video_size = filter_input(INPUT_POST, $video_size_input_name);
    if ( isset($updated_video_size) && !is_null($updated_video_size) ) {
        $video_size_meta_key = 'video-size';
        update_post_meta($post_id, $video_size_meta_key, $updated_video_size);
        echo $updated_video_size;
    }
}


