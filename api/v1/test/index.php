<?php

/* 
 * V1 Test API root
 */


/*
 * Load wp asset : /var/www/vhosts/mobileapp.scopic.nl/wp-load.php
 */
$api_include_path = dirname( dirname( dirname(__FILE__) ) ).'/include';
require_once $api_include_path.'/wp-assets.php';


/*
 * First Entry Point
 */
//ENTRY
run_test_api();

function run_test_api () {
    echo_json( get_all_test_video_info() );
}


/*
 * Param Check Function
 */
//Common
function isset_param($param_value) {
    if ( $param_value === false || is_null($param_value) ) {
        return false;
    } else {
        return true;
    }
}

function is_empty_param($param_value) {
    return empty($param_value) ? true : false;
}

//ID check
function is_numeric_id($id_value) {
    return is_numeric($id_value) ? true : false;
}

//Type check
function is_valid_type($type_value) {
    $type_list = array('id', 'category');
    return in_array($type_value, $type_list) ? true : false;
}


/*
 * Get All Video Info
 */
function get_all_test_video_info() {
    $params = array(
        'numberposts'   => -1,
        'post_type'     => 'videos',
        'orderby'       => 'date',
        'post_status'   => 'publish,private'
    );
    
    $video_posts = get_posts($params);
    $video_posts_arr = [];
    foreach ($video_posts as $video) {
        $video_posts_arr[] = get_video_arr($video);
    }
    
    return $video_posts_arr;
}


/*
 * Get All Video IDs
 */
function get_all_test_video_id() {
    $params = array(
        'numberposts'   => -1,
        'post_type'     => 'videos',
        'fields'        => 'ids',
        'orderby'       => 'date',
        'post_status'   => 'publish,private'
    );
    
    return get_posts($params);
}


/*
 * Get Video Info By ID
 */
function get_video_info_by_id ($video_id) {
    $video = get_post($video_id, OBJECT);
    return get_video_arr($video);
}


/*
 * Get Video Category List
 */
function get_all_categories () {
    $video_cat_taxonomy_name = 'videos-cat';
    $params = array(
	'type'                     => 'post',
	'orderby'                  => 'id',
	'order'                    => 'ASC',
	'number'                   => 10,
	'taxonomy'                 => $video_cat_taxonomy_name
    ); 
    $categories = get_categories( $params );
    
    $category_arr = [];
    foreach ($categories as $cat) {
        $category_arr[] = get_video_cat_arr($cat);
    }
    
    return $category_arr;
}


/*
 * Common Functions
 */
function get_video_arr($video) {
    $video_subtitle_key = 'video-subtitle'; //TODO : Sould be const
    $video_url_key = 'video-url'; //TODO : Sould be const
    $video_url_iphone5_key = 'video-url-iphone5'; //TODO : Sould be const
    $video_duration_key = 'video-duration'; //TODO : Sould be const
    $video_size_key = 'video-size'; //TODO : Sould be const
    $fb_share_content_key = 'fb-share-content'; //TODO : Sould be const
    $email_share_content_key = 'email-share-content'; //TODO : Sould be const

    $video_id = $video->ID;
    $video_thumb_id = get_post_thumbnail_id($video_id);

    $video_arr = [];
    $video_arr['id'] = $video_id; // Set id
    $video_arr['title'] = $video->post_title;
    $video_arr['subtitle'] = get_post_meta($video_id, $video_subtitle_key, true);
    $video_arr['description'] = $video->post_content;
    $video_arr['video_url'] = get_post_meta($video_id, $video_url_key, true);
    $video_arr['video_url_iphone5'] = get_post_meta($video_id, $video_url_iphone5_key, true);
    $video_arr['video_duration'] = get_post_meta($video_id, $video_duration_key, true);
    $video_arr['video_size'] = get_post_meta($video_id, $video_size_key, true);
    $video_arr['thumbnail_url'] = !empty($video_thumb_id) ? wp_get_attachment_image_src($video_thumb_id, 'full')[0] : '';
    $video_arr['categories'] = get_all_video_cat_by_id($video_id);
    $video_arr['fb_content'] = get_post_meta($video_id, $fb_share_content_key, true);
    $video_arr['email_content'] = get_post_meta($video_id, $email_share_content_key, true);
    $video_arr['created'] = $video->post_date;
    $video_arr['updated'] = $video->post_modified;

    return $video_arr;
}

function get_all_video_cat_by_id ($video_id) {
    $video_cat_taxonomy_name = 'videos-cat';
    $cat_list = wp_get_post_terms( $video_id, $video_cat_taxonomy_name);
    
    $video_cat_arr = [];
    foreach ($cat_list as $cat) {
        $video_cat_arr[] = get_video_cat_arr($cat);
    }
    
    return $video_cat_arr;
}

function get_video_cat_arr ($cat) {
    $video_cat_arr = [];
    
    $video_cat_arr['cat_id'] = $cat->term_id;
    $video_cat_arr['cat_name'] = $cat->name;
    $video_cat_arr['cat_slug'] = $cat->slug;
    
    return $video_cat_arr;
}


/*
 * Echo function
 */
function echo_json ($video_arr) {
    
    // Set header TODO : customize for error
    http_response_code(200);
    header("Content-Type: application/json; charset=utf-8");
    
    echo json_encode($video_arr);
}