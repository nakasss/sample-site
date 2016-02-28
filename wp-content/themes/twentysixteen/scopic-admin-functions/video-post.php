<?php

/* 
 * For customize "Video Posts"
 */


/*
 * Add "Video Post" as custom post
 */
add_action('init', 'add_video_post_type');
function add_video_post_type() {
    $params = array(
        'labels' => array(
            'name' => 'Videos',
            'singular_name' => 'Video',
            'all_items' => 'All Videos',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Video',
            'edit_item' => 'Edit Video',
            'new_item' => 'New Video',
            'view_item' => 'View Video',
            'search_items' => 'Search Videos',
            'not_found' => 'No videos found',
            'not_found_in_trash' => 'No videos found in Trash'
        ),
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-format-video',
        //'capability_type' =>
        //'capabilities' => 
        //'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields')
        'supports' => array('title', 'thumbnail')
    );
    register_post_type('videos', $params);
}


/*
 * Add Category to "Video Post"
 */
add_action('init', 'add_video_post_cat');
function add_video_post_cat() {
    $params = array(
        'labels' => array(
            'name' => 'Categories',
            'singular_name' => 'Category'
        ),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'hierarchical' => true
    );
    register_taxonomy('videos-cat', array('videos'), $params);
}


/*
 * Video uploader box
 */
//Add box
add_action('admin_menu', 'add_video_upload_box');
function add_video_upload_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-upload-box', 'Video', 'create_video_uploader', 'videos', 'normal', 'high');
    }
}
function create_video_uploader() {
  // 認証に nonce を使う
  echo '<input type="hidden" name="myplugin_noncename" id="myplugin_noncename" value="' . 
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
  // データ入力用の実際のフォーム
  echo '<label for="myplugin_new_field">' . __("Description for this field", 'myplugin_textdomain' ) . '</label> ';
  echo '<input type="text" name="myplugin_new_field" value="whatever" size="25" />';
}


/*
 * Video description editor box
 */
// Add box
add_action('admin_menu', 'add_video_description_editor_box');
function add_video_description_editor_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-description-editor-box', 'Description', 'create_custom_editor', 'videos', 'normal', 'core');
    }
}
function create_custom_editor() {
    global $post;
    //$post = get_post(get_the_ID(), OBJECT, 'edit');
    $content = $post->post_content;
    $editor_id = 'video-description-editor';
    $params = array(
        'media_buttons' => false,
        'drag_drop_upload' => true
    );
    wp_editor($content, $editor_id, $params);
}
// Save box content
add_action('edit_post', 'edit_post_custom_test');
function edit_post_custom_test($post_id) {
    error_log('EDIT POST : '.$post_id.PHP_EOL, 0);
}
add_action('save_post', 'save_video_description');
function save_video_description($post_id) {
    $prev_post = get_post($post_id, OBJECT, 'edit');
    if ($prev_post->post_type !== 'videos') {
        return;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    
    $editor_id = 'video-description-editor';
    $post_content = filter_input(INPUT_POST, $editor_id); 
    if ($post_content !== $prev_post->post_content) {
        $prev_post->post_content = $post_content;
        remove_action( 'save_post', 'save_video_description' );
        $is_posted = wp_update_post($prev_post); //update
        if ($is_posted === 0) {
            error_log('Post Updated Failed'.PHP_EOL, 0);
        }
        add_action('save_post', 'save_video_description');
    }
}


/*
 * Add FB content edit box
 */
add_action('admin_menu', 'add_fb_share_content_box');
function add_fb_share_content_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-fb-box', 'Facebook', 'myplugin_inner_custom_box', 'videos', 'side', 'low');
    }
}


/*
 * Add FB content edit box
 */
add_action('admin_menu', 'add_email_share_content_box');
function add_email_share_content_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-email-box', 'Email', 'myplugin_inner_custom_box', 'videos', 'side', 'low');
    }
}