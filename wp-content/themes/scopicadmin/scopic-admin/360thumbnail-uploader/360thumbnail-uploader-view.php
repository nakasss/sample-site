<?php


// Get post info
$post_id = get_the_ID();

// Nonce 
$thumb360_url_nonce_action = 'thumb360-url-nonce-action-'.$post_id;
$thumb360_url_nonce = wp_create_nonce($thumb360_url_nonce_action);

// Get 360 thumbnail url info
$thumb360_url = get_post_meta($post_id, 'thumb360-url', true);
$is_thumb360_uploaded = (isset($thumb360_url) && !is_null($thumb360_url) && !empty($thumb360_url)) ? true : false;

//// Get thumbnail info
//$thumbnail_id = get_post_thumbnail_id( $post_id );
//$default_thumb_url = get_bloginfo('template_directory').'/scopic-admin/img/blank_thumbnail_img.png';
//$thumbnail_url = !empty($thumbnail_id) ? wp_get_attachment_image_src( $thumbnail_id, 'full')[0] : $default_thumb_url;

// Get tutorial post
$tutorial_post_id = 19; //http://104.155.2.128/wp-admin/post.php?post=19&action=edit
$tutorial_post = get_post($tutorial_post_id, OBJECT);

?>


<div id="video-uploader-wrapper">
    <?php if ($is_video_uploaded) : ?>
    <div id="video-uploaded">
        <div id="video-frame">
            <!--<video id="uploaded-video" src="" poster="<?php echo $thumb360_url; ?>" controls></video>-->
        </div>
    </div><!-- #video-uploaded -->
    <?php endif; ?>
    <div id="no-video-uploaded">
        <div class="title-wrapper">
<!--            <h1>No Video</h1>-->
        </div>
        <div class="form-wrapper">
            <input type="hidden" name="thumb360-url-nonce" id="thumb360-url-nonce" value="<?php echo $thumb360_url_nonce; ?>" />
            <input type="text" name="thumb360-url" id="thumb360-url" value="<?php echo get_post_meta($post_id, 'thumb360-url', true); ?>" placeholder="Please input URL of 360 Thumbnail Photo on library." />
        </div>
        <div class="gcs-link-wrapper">
            <p class="gcs-link"><a href="https://console.cloud.google.com/storage/browser/scopic-mobile-app-storage/images/360-image/?project=indigo-shuttle-105615" target="_blank">→ Go Video Library</a></p>
        </div>
    </div><!-- #video-uploaded -->

</div><!-- #video-uploader-wrapper -->