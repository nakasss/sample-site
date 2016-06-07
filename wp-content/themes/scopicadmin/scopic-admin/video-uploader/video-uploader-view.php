<?php


// Get post info
$post_id = get_the_ID();

// Nonce 
$video_url_nonce_action = 'video-url-nonce-action-'.$post_id;
$video_url_nonce = wp_create_nonce($video_url_nonce_action);
$video_url_iphone5_nonce_action = 'video-url-iphone5-nonce-action-'.$post_id;
$video_url_iphone5_nonce = wp_create_nonce($video_url_iphone5_nonce_action);
$video_url_gearvr_nonce_action = 'video-url-gearvr-nonce-action-'.$post_id;
$video_url_gearvr_nonce = wp_create_nonce($video_url_gearvr_nonce_action);
$video_duration_nonce_action = 'video-duration-nonce-action-'.$post_id;
$video_duration_nonce = wp_create_nonce($video_duration_nonce_action);
$video_size_nonce_action = 'video-size-nonce-action-'.$post_id;
$video_size_nonce = wp_create_nonce($video_size_nonce_action);

// Get video url info
$video_url = get_post_meta($post_id, 'video-url', true);
$is_video_uploaded = (isset($video_url) && !is_null($video_url) && !empty($video_url)) ? true : false;

// Get thumbnail info
$thumbnail_id = get_post_thumbnail_id( $post_id );
$default_thumb_url = get_bloginfo('template_directory').'/scopic-admin/img/blank_thumbnail_img.png';
$thumbnail_url = !empty($thumbnail_id) ? wp_get_attachment_image_src( $thumbnail_id, 'full')[0] : $default_thumb_url;

// Get tutorial post
$tutorial_post_id = 19; //http://104.155.2.128/wp-admin/post.php?post=19&action=edit
$tutorial_post = get_post($tutorial_post_id, OBJECT);


?>


<script type="text/javascript">
/*
 * Set Video Duration
 */
$(window).load(function () {
    var video = $("video#uploaded-video")[0];
    if (!video) return;
    
    var video_duration = video.duration;
    
    if (isNaN(video_duration)) {
        video.addEventListener('loadedmetadata', function(){
            video_duration = video.duration;
            upldate_video_duration(video_duration);
        });
    } else {
        upldate_video_duration(video_duration);
    }
});

function upldate_video_duration (video_duration) {
    var post_id = Number("<?php echo $post_id; ?>");
    var current_duration = Number("<?php echo get_post_meta($post_id, 'video-duration', true); ?>");
    
    if (current_duration !== video_duration) {
        //Update
        async_wp_post_video_duration(post_id, video_duration);
    } else {
        var $video_duration_input = $('input#video-duration');
        $video_duration_input.val(video_duration);
    }
};

function async_wp_post_video_duration (post_id, duration) {
    var async_post_php_path = "./wp-content/themes/<?php echo get_template(); ?>/scopic-admin/video-uploader/video-duration-async-uploader.php";
    
    var scheme_name = window.location.protocol;
    var host_name = window.location.host;
    var request_url = scheme_name + "//" + host_name + async_post_php_path;
    //var request_url = "testset" + async_post_php_path;
    
    var video_nonce = "<?php echo $video_duration_nonce; ?>";
    var post_param = {
        video_duration_post_id:post_id,
        video_duration_nonce:video_nonce,
        video_duration:duration
    };
    
    $.ajax({
        type:"POST",
        url:request_url,
        data:post_param,
        success:function (data, textStatus) {
            console.log("Update Completed. Post ID : " + post_id + ", Duration : " + data);
            var $video_duration_input = $('input#video-duration');
            $video_duration_input.val(duration);
        },
        error:function (xhr, textStatus, errorThrown) {
            alert("Failed to upload. Please click update button again.\n\nError Status:\n" + errorThrown);
        }
    }); 
};


/*
 * Set Video Size
 */
function upldate_video_size (video_size) {
    var post_id = Number("<?php echo $post_id; ?>");
    var current_size = Number("<?php echo get_post_meta($post_id, 'video-size', true); ?>");
    
    if (current_size !== video_size) {
        //Update
        async_wp_post_video_size(post_id, video_size);
    } else {
        var $video_size_input = $('input#video-size');
        $video_size_input.val(video_size);
    }
};

function async_wp_post_video_size (post_id, size) {
    var async_post_php_path = "./wp-content/themes/<?php echo get_template(); ?>/scopic-admin/video-uploader/video-size-async-uploader.php";
    
    var scheme_name = window.location.protocol;
    var host_name = window.location.host;
    var request_url = scheme_name + "//" + host_name + async_post_php_path;
    //var request_url = "testset" + async_post_php_path;
    
    var video_nonce = "<?php echo $video_size_nonce; ?>";
    var post_param = {
        video_size_post_id:post_id,
        video_size_nonce:video_nonce,
        video_size:size
    };
    
    $.ajax({
        type:"POST",
        url:request_url,
        data:post_param,
        success:function (data, textStatus) {
            console.log("Update Completed. Post ID : " + post_id + ", size : " + data);
            var $video_size_input = $('input#video-size');
            $video_size_input.val(size);
        },
        error:function (xhr, textStatus, errorThrown) {
            alert("Failed to upload. Please click update button again.\n\nError Status:\n" + errorThrown);
        }
    }); 
};
</script>



<div id="video-uploader-wrapper">
    <?php if ($is_video_uploaded) : ?>
    <div id="video-uploaded">
        <div id="video-frame">
            <video id="uploaded-video" src="<?php echo $video_url; ?>" poster="<?php echo $thumbnail_url; ?>" controls></video>
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
            <input type="hidden" name="video-url-iphone5-nonce" id="video-url-iphone5-nonce" value="<?php echo $video_url_iphone5_nonce; ?>" />
            <input type="text" name="video-url-iphone5" id="video-url-iphone5" value="<?php echo get_post_meta($post_id, 'video-url-iphone5', true); ?>" placeholder="Please input URL of video on library for old devices. Resolution sould be lower than 1080p." />
            <input type="hidden" name="video-url-gearvr-nonce" id="video-url-gearvr-nonce" value="<?php echo $video_url_gearvr_nonce; ?>" />
            <input type="text" name="video-url-gearvr" id="video-url-gearvr" value="<?php echo get_post_meta($post_id, 'video-url-gearvr', true); ?>" placeholder="Please input URL of video on library to deliver GearVR App." />
            <input type="hidden" name="video-size-nonce" id="video-size-nonce" value="<?php echo $video_size_nonce; ?>" />
            <input type="text" name="video-size" id="video-size" value="<?php echo get_post_meta($post_id, 'video-size', true); ?>" placeholder="Please input URL of video size. (byte)" />
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