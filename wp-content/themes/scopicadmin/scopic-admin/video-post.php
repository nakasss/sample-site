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
    if( function_exists( 'register_post_type' )) {
        register_post_type('videos', $params);
    }
}


/*
 * Add "Video Post" Category
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
    if( function_exists( 'register_taxonomy' )) {
        register_taxonomy('videos-cat', array('videos'), $params);
    }
}


/*
 * Video Subtitle box
 */
//Add box
add_action('admin_menu', 'add_video_subtitle_box');
function add_video_subtitle_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-subtitle-box', 'Subtitle', 'create_video_subtitle', 'videos', 'normal', 'high');
    }
}
function create_video_subtitle() {
    $post_id = get_the_ID();
    
    // Nonce 
    $video_subtitle_nonce_action = 'video-subtitle-nonce-action-'.$post_id;
    $video_subtitle_nonce = wp_create_nonce($video_subtitle_nonce_action);

    // Get video url info
    $subtitle = get_post_meta($post_id, 'video-subtitle', true);
?>
<div id="video-subtitle-wrapper">
    <input type="hidden" name="video-subtitle-nonce" id="video-subtitle-nonce" value="<?php echo $video_subtitle_nonce; ?>" />
    <input type="text" name="video-subtitle" id="video-subtitle" value="<?php echo $subtitle; ?>" placeholder="Please input video subtitle if the video has.">
</div><!-- #video-subtitle-wrapper -->
<?php
}

// Save box content
add_action('save_post', 'save_video_subtitle');
function save_video_subtitle($post_id) {
    $video_subtitle_nonce_action = 'video-subtitle-nonce-action-'.$post_id;
    $video_subtitle_nonce = filter_input(INPUT_POST, 'video-subtitle-nonce');
    
    if (!wp_verify_nonce($video_subtitle_nonce, $video_subtitle_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $subtitle_input_name = 'video-subtitle';
    $updated_subtitle = filter_input(INPUT_POST, $subtitle_input_name);
    if ( isset($updated_subtitle) && !is_null($updated_subtitle) ) {
        $video_subtitle_meta_key = 'video-subtitle';
        update_post_meta($post_id, $video_subtitle_meta_key, $updated_subtitle);
    }
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
    require_once locate_template('scopic-admin/video-uploader/video-uploader-view.php');
}
//Save box content
add_action('save_post', 'save_uploaded_video');
function save_uploaded_video( $post_id ) {
    $video_url_nonce_action = 'video-url-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_url_nonce = filter_input(INPUT_POST, 'video-url-nonce');
    if (!wp_verify_nonce($video_url_nonce, $video_url_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $video_url_input_name = 'video-url';
    $updated_video_url = filter_input(INPUT_POST, $video_url_input_name);
    if ( isset($updated_video_url) && !is_null($updated_video_url) ) {
        $video_url_meta_key = 'video-url';
        update_post_meta($post_id, $video_url_meta_key, $updated_video_url);
        /*
        if (!empty($updated_video_url)) {
            save_video_duration($post_id);
        }
        if (!empty($updated_video_url)) {
            save_video_size($post_id);
        }
         */
    }
}
/*
add_action('save_post', 'save_video_duration');
function save_video_duration ( $post_id ) {
    $video_duration_nonce_action = 'video-duration-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_duration_nonce = filter_input(INPUT_POST, 'video-duration-nonce');
    if (!wp_verify_nonce($video_duration_nonce, $video_duration_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $video_duration_input_name = 'video-duration';
    $updated_video_duration = filter_input(INPUT_POST, $video_duration_input_name);
    if ( isset($updated_video_duration) && !is_null($updated_video_duration) ) {
        $video_duration_meta_key = 'video-duration';
        update_post_meta($post_id, $video_duration_meta_key, $updated_video_duration);
    }
}
 */
//Save video url for iphone5
add_action('save_post', 'save_video_url_iphone5');
function save_video_url_iphone5( $post_id ) {
    $video_url_iphone5_nonce_action = 'video-url-iphone5-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_url_iphone5_nonce = filter_input(INPUT_POST, 'video-url-iphone5-nonce');
    if (!wp_verify_nonce($video_url_iphone5_nonce, $video_url_iphone5_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $video_url_iphone5_input_name = 'video-url-iphone5';
    $updated_video_url_iphone5 = filter_input(INPUT_POST, $video_url_iphone5_input_name);
    if ( isset($updated_video_url_iphone5) && !is_null($updated_video_url_iphone5) ) {
        $video_url_iphone5_meta_key = 'video-url-iphone5';
        update_post_meta($post_id, $video_url_iphone5_meta_key, $updated_video_url_iphone5);
    }
}
//Save video size
add_action('save_post', 'save_video_size');
function save_video_size ( $post_id ) {
    $video_size_nonce_action = 'video-size-nonce-action-'.$post_id; // Nonce was set in ./video-uploader/video-uploader-view.php
    $video_size_nonce = filter_input(INPUT_POST, 'video-size-nonce');
    if (!wp_verify_nonce($video_size_nonce, $video_size_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $video_size_input_name = 'video-size';
    $updated_video_size = filter_input(INPUT_POST, $video_size_input_name);
    if ( isset($updated_video_size) && !is_null($updated_video_size) ) {
        $video_size_meta_key = 'video-size';
        update_post_meta($post_id, $video_size_meta_key, $updated_video_size);
    }
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
add_action('save_post', 'save_video_description');
function save_video_description($post_id) {
    $prev_post = get_post($post_id, OBJECT, 'edit');
    if ($prev_post->post_type !== 'videos') {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $editor_id = 'video-description-editor';
    $post_content = filter_input(INPUT_POST, $editor_id); 
    if ($post_content !== $prev_post->post_content) {
        $prev_post->post_content = $post_content;
        remove_action( 'save_post', 'save_video_description' );
        $is_posted = wp_update_post($prev_post); //update
        if ($is_posted === 0) {
            //TODO : Error handling
        }
        add_action('save_post', 'save_video_description');
    }
}


/*
 * FB content edit box
 */
// Add box
add_action('admin_menu', 'add_fb_share_content_box');
function add_fb_share_content_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-fb-box', 'Facebook Share URL', 'create_fb_content_box', 'videos', 'side', 'low');
    }
}
function create_fb_content_box() {
    $post_id = get_the_ID();
    
    // Nonce 
    $fb_content_nonce_action = 'fb-content-nonce-action-'.$post_id;
    $fb_content_nonce = wp_create_nonce($fb_content_nonce_action);

    // Get video url info
    $fb_content = get_post_meta($post_id, 'fb-share-content', true);
?>
<div id="fb-content-wrapper" class="video-post-share-content-wrapper">
    <input type="hidden" name="fb-share-content-nonce" id="fb-share-content-nonce" value="<?php echo $fb_content_nonce; ?>" />
    <textarea name="fb-share-content" id="fb-share-content" placeholder="Please input URL that will be shared in Facebook."><?php echo $fb_content; ?></textarea>
</div><!-- #fb-content-wrapper -->
<?php
}
// Save box content
add_action('save_post', 'save_fb_content');
function save_fb_content( $post_id ) {
    $fb_content_nonce_action = 'fb-content-nonce-action-'.$post_id;
    $fb_content_nonce = filter_input(INPUT_POST, 'fb-share-content-nonce');
    if (!wp_verify_nonce($fb_content_nonce, $fb_content_nonce_action)) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $fb_content_input_name = 'fb-share-content';
    $updated_fb_content = filter_input(INPUT_POST, $fb_content_input_name);
    if ( isset($updated_fb_content) && !is_null($updated_fb_content) ) {
        $fb_content_meta_key = 'fb-share-content';
        update_post_meta($post_id, $fb_content_meta_key, $updated_fb_content);
    }
}


/*
 * Email content edit box
 */
// Add box
add_action('admin_menu', 'add_email_share_content_box');
function add_email_share_content_box() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box('video-email-box', 'Email', 'create_email_content_box', 'videos', 'side', 'low');
    }
}
function create_email_content_box() {
    $post_id = get_the_ID();
    
    // Nonce 
    $email_content_nonce_action = 'email-content-nonce-action-'.$post_id;
    $email_content_nonce = wp_create_nonce($email_content_nonce_action);

    // Get video url info
    $email_content = get_post_meta($post_id, 'email-share-content', true);
?>
<div id="email-content-wrapper" class="video-post-share-content-wrapper">
    <input type="hidden" name="email-share-content-nonce" id="email-share-content-nonce" value="<?php echo $email_content_nonce; ?>" />
    <textarea name="email-share-content" id="email-share-content" placeholder="Please input sentence that will be shared through email."><?php echo $email_content; ?></textarea>
</div><!-- #email-content-wrapper -->
<?php
}
// Save box content
add_action('save_post', 'save_email_content');
function save_email_content( $post_id ) {
    $email_content_nonce_action = 'email-content-nonce-action-'.$post_id;
    $email_content_nonce = filter_input(INPUT_POST, 'email-share-content-nonce');
    if ( !wp_verify_nonce($email_content_nonce, $email_content_nonce_action) ) {
        return $post_id;
    }
    
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }
    
    $email_content_input_name = 'email-share-content';
    $updated_email_content = filter_input(INPUT_POST, $email_content_input_name);
    if ( isset($updated_email_content) && !is_null($updated_email_content) ) {
        $email_content_meta_key = 'email-share-content';
        update_post_meta($post_id, $email_content_meta_key, $updated_email_content);
    }
}