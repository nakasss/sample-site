<?php


// Get post info
$post_id = get_the_ID();

// Nonce 
$video_url_nonce_action = 'video-url-nonce-action-'.$post_id;
$video_url_nonce = wp_create_nonce($video_url_nonce_action);

// Get video url info
$video_url = get_post_meta($post_id, 'video-url', true);
error_log('Updated URL : '.$video_url, 0);
$is_video_uploaded = (isset($video_url) && !is_null($video_url) && !empty($video_url)) ? true : false;

// Get thumbnail info
$thumbnail_id = get_post_thumbnail_id( $post_id );
$default_thumb_url = get_bloginfo('template_directory').'/scopic-admin-functions/img/blank_thumbnail_img.png';
$thumbnail_url = !empty($thumbnail_id) ? wp_get_attachment_image_src( $thumbnail_id, 'full')[0] : $default_thumb_url;

// Get tutorial post
$tutorial_post_id = 19; //http://104.155.2.128/wp-admin/post.php?post=19&action=edit
$tutorial_post = get_post($tutorial_post_id, OBJECT);


?>


<div id="video-uploader-wrapper">
    <?php if ($is_video_uploaded) : ?>
    <div id="video-uploaded">
        <div id="video-frame">
            <video src="<?php echo $video_url; ?>" poster="<?php echo $thumbnail_url; ?>" controls></video>
        </div>
    </div><!-- #video-uploaded -->
    <?php endif; ?>
    <div id="no-video-uploaded">
        <div class="title-wrapper">
<!--            <h1>No Video</h1>-->
        </div>
        <div class="form-wrapper">
            <input type="hidden" name="video-url-nonce" id="video-url-nonce" value="<?php echo $video_url_nonce; ?>" />
            <input type="text" name="video-url" id="video-url" value="<?php echo get_post_meta($post_id, 'video-url', true); ?>" placeholder="Please input URL of video on library." />
        </div>
        <div class="gcs-link-wrapper">
            <p class="gcs-link"><a href="https://console.cloud.google.com/storage/browser/scopic-mobile-app-storage/videos/?project=indigo-shuttle-105615" target="_blank">→ Go Video Library</a></p>
        </div>
    </div><!-- #video-uploaded -->
    
    <div id="tutorial-open-wrapper">
        <h2 class="tutorial-link"><?php echo $tutorial_post->post_title; ?></h2>
<!--        <p class="tutorial-link"><a href="http://104.155.2.128/how-to-upload-video-in-google-cloud-strage/" target="_blank">How to upload video</a></p>-->
        <button type="button" class="handlediv button-link" aria-expanded="false">
            <span class="screen-reader-text">Toggle panel: Video</span>
            <span class="toggle-indicator" aria-hidden="true"></span>
        </button>
    </div>
    
    <div id="tutorial-post-wrapper">
        <div class="tutorial-post-content">
            <?php echo apply_filters('the_content', $tutorial_post->post_content); ?>
        </div>
        <div class="tutorial-close-btn-wrapper">
            <h2 class="tutorial-link">Close Tutorial</h2>
<!--        <p class="tutorial-link"><a href="http://104.155.2.128/how-to-upload-video-in-google-cloud-strage/" target="_blank">How to upload video</a></p>-->
        <button type="button" class="handlediv button-link" aria-expanded="false">
            <span class="screen-reader-text">Toggle panel: Video</span>
            <span class="toggle-indicator" aria-hidden="true"></span>
        </button>
        </div>
    </div>
</div><!-- #video-uploader-wrapper -->