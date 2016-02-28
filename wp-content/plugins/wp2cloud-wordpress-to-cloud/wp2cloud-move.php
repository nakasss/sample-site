<?php

//----------------------------------------------------------------------------
// Move processing mini-framework.
//----------------------------------------------------------------------------

//--------
// Render processing phase.
function wp2cloud_move_render_phase($phase_id, $phase_message) {
    $be_patient_msg = __('Please be patient and don\'t navigate away from this page until the operation is completed.', 'wp2cloud');

    print <<<EOT
        <div id="phase-$phase_id" style="display:none">
            <p>$phase_message $be_patient_msg</p>
            <p><span id="processed-count-$phase_id">0</span> (<span id="progressbar-pct-$phase_id">0%</span>) <spand id="progress-more-$phase_id">...</span></p>
            <div id="result-message-$phase_id"></div>
        </div>

EOT;
}

define('WP2CLOUD_PROCESSING_BATCH_SIZE', 10);

//--------
// Render processing JavaScript.
function wp2cloud_move_render_js($object_id, $batch_size) {
    $ajax_nonce = wp_create_nonce('wp2cloud');
    $bad_data = __('Bad data received from server.', 'wp2cloud');

    //--------
    // This JavaScript function drives processing.  It shows the next phase
    // UI and then pings the server with the next batch and updates
    // the progress.  When the phase is completed, it moves to the next phase
    // (if any).  Note that the total count may not neccessarily be precise,
    // but it doesn't affect the functionality, as the total count is only
    // used to estimate the progress percentage.
?>
<script type="text/javascript" >
function wp2cloud_process_phase(phase_id, total) {
    $ = jQuery;

    $("#info").hide();

    if (!$("#phase-" + phase_id).length) {
        $("#done").show();
        return;
    }

    $("#phase-" + phase_id).show();

    if (total == 0)
        total = 100;

    function next_batch(id, processed) {
        var data = {
                action: 'wp2cloud_move',
                security: '<?php echo $ajax_nonce; ?>',
                object_id: '<?php echo $object_id; ?>',
                phase_id: phase_id,
                start_id: id,
        };

        $.post(ajaxurl, data, function(r) {
            if (r !== Object(r) || (typeof r.success === 'undefined' && typeof r.error === 'undefined' && typeof r.start_id === 'undefined')) {
                r = new Object;
                r.error = '<?php echo $bad_data; ?>';
            }

            if (r.error) {
                $('#result-message-' + phase_id).text(r.error).addClass('error');
                return;
            }

            processed += <?php echo $batch_size; ?>;

            if (processed > total)
                processed = total;

            $('#processed-count-' + phase_id).text(processed);

            if (r.success) {
                $('#result-message-' + phase_id).text(r.success).addClass('updated');
                $('#progressbar-pct-' + phase_id).text('100%');
                $('#progress-more-' + phase_id).text('');
                wp2cloud_process_phase(phase_id + 1, r.next_count);
                return;
            }

            if (processed < total)
                $('#progressbar-pct-' + phase_id).text(String(Math.floor(processed * 100 / total)) + '%');
            else
                $('#progressbar-pct-' + phase_id).text('99%');

            next_batch(r.start_id, processed);
        });
    }

    next_batch('', 0);
}
</script>

<?php
}

//--------
// Die with error message (for AJAX handlers).
function die_json_error_msg($message) {
    echo json_encode(array('error' => $message));
    die;
}

//--------
// Register AJAX handler for move step.
add_action('wp_ajax_wp2cloud_move', 'wp2cloud_move_process_step');
$wp2cloud_move_perms = array();

function wp2cloud_move_process_step() {
    global $wp2cloud_move_perms;
    @error_reporting(0); // Don't break the JSON result
    header('Content-type: application/json');

    //--------
    // Security & protocol checks.
    check_ajax_referer('wp2cloud', 'security');

    $permission = $wp2cloud_move_perms[$_POST['object_id']];

    if (!$permission)
        $permission = 'install_plugins';

    if (!current_user_can($permission))
        die_json_error_msg(__('Access denied.', 'wp2cloud'));

    $invalid_data_msg = __('Invalid data.', 'wp2cloud');

    if (!isset($_POST['object_id']) || !isset($_POST['phase_id']) || !isset($_POST['start_id']))
        die_json_error_msg($invalid_data_msg);

    $function = 'wp2cloud_move_' . $_POST['object_id'] . '_phase_' . $_POST['phase_id'];

    if (!function_exists($function)) {
        die_json_error_msg($invalid_data_msg);
    }

    //--------
    // Each move may consist of several phases, each phase is split
    // into batches, the execution is driven by the js on the page.
    //
    // Each phase handler takes start id, returns new start id
    // or success message and next phase's count.
    $ret_id = 0;
    $success = $function($_POST['start_id'], $ret_id /* by ref*/);

    if ($success)
        echo json_encode(array('success' => $success, 'next_count' => $ret_id));
    else
        echo json_encode(array('start_id' => $ret_id));

    die;
}

//----------------------------------------------------------------------------
// Move media to cloud.
// The move is done in 3 phases:
//  1. Copy media files to cloud.
//  2. Update references in posts (and options).
//  3. Remove media files from the server.
//----------------------------------------------------------------------------

define ('WP2CLOUD_MOVE_TABLE_NAME', $wpdb->prefix . "cloud_moved_media");

//--------
// Callback for move operation that records the move.
function wp2cloud_record_move($from, $to) {
    global $wp2cloud_plugin;

    $from = _wp_relative_upload_path($from);
    $wbid = $wp2cloud_plugin->parse_weblob_id($to);

    //--------
    // Insert the map record in the the table.  Create table if missing.
    $sql = $wp2cloud_plugin->media_db->prepare("REPLACE INTO " . WP2CLOUD_MOVE_TABLE_NAME .
        " (from_path, to_id) VALUES (%s, %d);", $from, $wbid['id']);

    $wp2cloud_plugin->media_db->last_error = '';
    $wp2cloud_plugin->media_db->query($sql);

    if ($wp2cloud_plugin->media_db->last_error)
        die_json_error_msg($wp2cloud_plugin->media_db->last_error);
}

//--------
// Phase 0 media move processing (called from wp2cloud_move_process_step).
// Copy all media to the cloud.
function wp2cloud_move_media_phase_0($start_id, &$ret_id) {
    global $wpdb;
    global $wp2cloud_plugin;

    $wp2cloud_plugin->move_function = 'wp2cloud_record_move';

    if (!$start_id) {
        //--------
        // Ensure we have the move table to avoid auto-creating it in each phase.
        $create_sql = "CREATE TABLE IF NOT EXISTS " . WP2CLOUD_MOVE_TABLE_NAME . " (
            from_path VARCHAR(1024) COLLATE UTF8_BIN KEY,
            to_id BIGINT NOT NULL,
            KEY(to_id)
            ) ENGINE=CLOUSE;";

        $wp2cloud_plugin->media_db->query($create_sql);
    }

    //--------
    // Get ids for the batch.
    $start_id = intval($start_id);
    $limit = WP2CLOUD_PROCESSING_BATCH_SIZE;

    $wpdb->last_error = '';
    $ids = $wpdb->get_results("SELECT id FROM $wpdb->posts WHERE post_type = 'attachment' AND id >= $start_id LIMIT $limit", ARRAY_N);

    if ($wpdb->last_error)
        die_json_error_msg($wpdb->last_error);

    foreach ($ids as $id) {
        //--------
        // Each id represents the attachment that needs to be uploaded.
        $orig_file = get_attached_file($id[0]);

        if (strpos($orig_file, '$wblob') !== false)
            continue;  // already in the cloud

        @set_time_limit(300);  // 5 minutes per file attachment

        //--------
        // Get and update attachment metadata.  The wp2cloud filter is going
        // to do the actual upload.  The move is going to be recorded via
        // the wp2cloud_record_move function.  Also, setting the function_move
        // callback works as flag saying that original files shouldn't be
        // removed (they will be be removed after updating the links).
        wp_update_attachment_metadata($id[0], wp_get_attachment_metadata($id[0], true));
        $new_file = get_attached_file($id[0]);

        //--------
        // Upload backup files, if any.  If the image was edited the old
        // versions are referred from the backup sizes metadata.
        $backup_sizes = get_post_meta($id[0], '_wp_attachment_backup_sizes', true);

        if (empty($backup_sizes))
            continue;

        try {
            $wp2cloud_plugin->query('START TRANSACTION');

            foreach ($backup_sizes as $bs) {
                $altPath = str_replace(wp_basename($orig_file), $bs['file'], $orig_file);
                $altId = str_replace(wp_basename($new_file), $bs['file'], $new_file);

                $wp2cloud_plugin->upload_image_to_cloud($altPath, $altId);
                wp2cloud_record_move($altPath, $altId);
            }

            $wp2cloud_plugin->query("COMMIT");
        } catch(Exception $e) {
            $wp2cloud_plugin->query("ROLLBACK");
            die_json_error_msg($e->getMessage());
        }
    }

    if (count($ids) < WP2CLOUD_PROCESSING_BATCH_SIZE) {
        //--------
        // Get count for phase 1 (translating links).
        $count = $wpdb->get_results("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type <> 'attachment'", ARRAY_N);
        $ret_id = isset($count[0]) ? $count[0][0] : 100;
        return __('Media are uploaded to cloud.', 'wp2cloud');
    } else {
        $ret_id = $ids[count($ids) - 1][0] + 1;
        return '';  // more to process
    }
}

//--------
// Translate weblob_id-based URL into direct cloud storage URL.
function wp2cloud_move_xlate_name($name) {
    global $wp2cloud_plugin;

    $sql = $wp2cloud_plugin->media_db->prepare("SELECT to_id FROM " . WP2CLOUD_MOVE_TABLE_NAME .
        " WHERE from_path = %s;", $name);

    $wp2cloud_plugin->media_db->last_error = '';
    $id = $wp2cloud_plugin->media_db->get_results($sql, ARRAY_N);

    if ($wp2cloud_plugin->media_db->last_error)
        die_json_error_msg($wp2cloud_plugin->media_db->last_error);

    if (empty($id))
        return null;

    return $wp2cloud_plugin->wp2cloud_dir_url . 'wp2cloud.php?weblob_id=' . $wp2cloud_plugin->fmt_weblob_id($id[0][0], basename($name));
}

//--------
// Translate weblob_id-based URL into direct cloud storage URL.
function wp2cloud_move_xlate_media_url($matches) {
    //--------
    // The $matches array contains the following:
    // $matches[0] - the orginal text, e.g. "http://a.com/wp-content/uploads/2014/07/foo.jpg"
    // $matches[1] - single or double quote, or paren
    // $matches[2] - the media path suffix, e.g. 2014/07/foo.jpg
    // $matches[3] - single or double quote, or paren

    $url = wp2cloud_move_xlate_name($matches[2]);
    return $url ? $matches[1] . $url . $matches[3] : $matches[0];
}

//--------
// Recursively translate nested aggregate objects.
function wp2cloud_move_xlate_object(&$obj) {
    $translated = false;

    if (is_string($obj)) {
        //--------
        // Check if it's a URL of a media file and try to translate it.
        $uploads = wp_upload_dir();

        if ($uploads['error'] !== false)
            die_json_error_msg($uploads['error']);

        $uploads_path = trailingslashit(parse_url($uploads['baseurl'], PHP_URL_PATH));
        $pos = strpos($obj, $uploads_path);

        if ($pos === false)
            return false;

        $from_path = substr($obj, $pos + strlen($uploads_path));
        $url = wp2cloud_move_xlate_name($from_path);

        if ($url) {
            $obj = $url;
            return true;
        }
    } else if (is_array($obj)) {
        //--------
        // Recursively translate elements.
        foreach ($obj as $key => $value) {
            if (wp2cloud_move_xlate_object($value)) {
                $obj[$key] = $value;
                $translated = true;
            }
        }
    } else if (is_object($obj)) {
        //--------
        // Recursively translate properties.
        foreach ($obj as $key => $value) {
            if (wp2cloud_move_xlate_object($value)) {
                $obj->$key = $value;
                $translated = true;
            }
        }
    }

    return $translated;
}

//--------
// Phase 1 media move processing (called from wp2cloud_move_process_step).
// Update references in posts (and options).
function wp2cloud_move_media_phase_1($start_id, &$ret_id) {
    global $wpdb;
    global $wp2cloud_plugin;

    //--------
    // Get the path part of the uploads URL and encode it for the regexp.
    // We consider any URL that has the path that matches the uploads URL's
    // path to be pointing to this server.  If the file exists on this server,
    // then we translate it.  Note that there could be a case when the URL
    // is actually pointing to a different server (and the file with the
    // same name exists on this server too), but if we did a strict match
    // we could miss a lot of files if the domain was renamed.
    $uploads = wp_upload_dir();

    if ($uploads['error'] !== false)
        die_json_error_msg($uploads['error']);

    $uploads_path = trailingslashit(parse_url($uploads['baseurl'], PHP_URL_PATH));
    $uploads_path = preg_quote($uploads_path, '#');

    if (!$start_id) {
        //--------
        // Translate theme mods (e.g. header image URL).  There should
        // be a very small number of those, so we don't bother with
        // batching and do them all on the first batch of posts.
        $wpdb->last_error = '';
        $mod_names = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'theme_mods_%'", ARRAY_N);

        if ($wpdb->last_error)
            die_json_error_msg($wpdb->last_error);

        foreach ($mod_names as $mod_name) {
            $theme_mods = get_option($mod_name[0]);

            if (wp2cloud_move_xlate_object($theme_mods))
                update_option($mod_name[0], $theme_mods);
        }
    }

    //--------
    // Get posts for the batch.
    $start_id = intval($start_id);
    $limit = WP2CLOUD_PROCESSING_BATCH_SIZE;

    $wpdb->last_error = '';
    $posts = $wpdb->get_results("SELECT id, post_content FROM $wpdb->posts WHERE post_type <> 'attachment' AND id >= $start_id LIMIT $limit", ARRAY_N);

    if ($wpdb->last_error)
        die_json_error_msg($wpdb->last_error);

    foreach ($posts as $post) {
        //--------
        // Translate URLs in the post content.
        // The URL can be in
        //  - single quotes: '[path]/2014/07/foo.jpg'
        //  - double quotes: "[path]/2014/07/foo.jpg"
        //  - parentheses:   ([path]/2014/07/foo.jpg)
        $content = preg_replace_callback("#([\"'\\(])[^\"'\\)>]*$uploads_path([^\"'\\)>]+)([\"'\\)>])#", 'wp2cloud_move_xlate_media_url', $post[1]);

        if ($content != $post[1]) {
            wp_update_post(array(
                'ID' => $post[0],
                'post_content' => $content
            ));
        }
    }

    if (count($posts) < WP2CLOUD_PROCESSING_BATCH_SIZE) {
        //--------
        // Get count for phase 2 (media file removal).
        $count = $wp2cloud_plugin->media_db->get_results("SELECT COUNT(*) FROM " . WP2CLOUD_MOVE_TABLE_NAME, ARRAY_N);
        $ret_id = isset($count[0]) ? $count[0][0] : 100;
        return __('Links are updated.', 'wp2cloud');
    } else {
        $ret_id = $posts[count($posts) - 1][0] + 1;
        return '';  // more to process
    }
}

//--------
// Phase 2 media move processing (called from wp2cloud_move_process_step).
// Remove media files from the server.
function wp2cloud_move_media_phase_2($start_id, &$ret_id) {
    global $wp2cloud_plugin;

    $uploads = wp_upload_dir();

    if ($uploads['error'] !== false)
        die_json_error_msg($uploads['error']);

    //--------
    // Get ids for the batch.
    $limit = WP2CLOUD_PROCESSING_BATCH_SIZE;

    $sql = $wp2cloud_plugin->media_db->prepare("SELECT from_path FROM " . WP2CLOUD_MOVE_TABLE_NAME .
        " WHERE from_path > %s LIMIT $limit", $start_id);

    $wp2cloud_plugin->media_db->last_error = '';
    $ids = $wp2cloud_plugin->media_db->get_results($sql, ARRAY_N);

    if ($wp2cloud_plugin->media_db->last_error)
        die_json_error_msg($wp2cloud_plugin->media_db->last_error);

    //--------
    // Each path is relative to the uploads dir, we just delete the file.
    foreach ($ids as $id)
        @ unlink(path_join($uploads['basedir'], $id[0]));

    if (count($ids) < WP2CLOUD_PROCESSING_BATCH_SIZE)
        return __('Media are removed from server.', 'wp2cloud');

    $ret_id = $ids[count($ids) - 1][0];
    return '';  // more to process
}

//--------
// Render "done" screen.
function wp2cloud_render_done_screen() {
    $close_button_msg = __('Close', 'wp2cloud');
    $close_button_link = admin_url('options-general.php?page=wp2cloud-settings');

    print <<<EOT
        <div id="done" style="display:none">
            <p><a href="$close_button_link" class="button">$close_button_msg</a></p>
        </div>
EOT;
}

//--------
// Render move media to cloud page.
function wp2cloud_render_media_move() {
    global $wpdb;

    //--------
    // Get total count for phase 0.
    $count = $wpdb->get_results("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment'", ARRAY_N);
    $total = isset($count[0]) ? $count[0][0] : 100;

    //--------
    // Render common UI and info screen.
    wp2cloud_move_render_js('media', WP2CLOUD_PROCESSING_BATCH_SIZE);

    echo '    <div class="wrap">';
    echo '        <h2>' . __('Move media to cloud', 'wp2cloud') . '</h2>';

    $info_msg = __('Move existing media to cloud and update links in posts and pages. The operation may take a while if you have a lot of media files.', 'wp2cloud');
    $button_msg = __('Start', 'wp2cloud');

    print <<<EOT
        <div id="info">
            <p>$info_msg</p>
            <button onclick="wp2cloud_process_phase(0, $total)">$button_msg</button>
        </div>
EOT;

    //--------
    // Render processing phases.
    wp2cloud_move_render_phase(0, __('Uploading media to cloud.', 'wp2cloud'));
    wp2cloud_move_render_phase(1, __('Updating links.', 'wp2cloud'));
    wp2cloud_move_render_phase(2, __('Removing media from server.', 'wp2cloud'));

    //--------
    // Render "done" screen.
    wp2cloud_render_done_screen();

    echo '    </div>';  // wrap
}

//--------
// The current implementation allows site admins to move their media files
// to cloud.
$wp2cloud_move_perms['media'] = 'manage_options';

//----------------------------------------------------------------------------
// Move tables to cloud.
// The move is done in 1 phase:
//  1. Alter tables to use ClouSE.
//----------------------------------------------------------------------------

//--------
// Phase 0 tables move processing (called from wp2cloud_move_process_step).
// Move all tables to the cloud.
function wp2cloud_move_tables_phase_0($start_id, &$ret_id) {
    global $wpdb;

    //--------
    // Get next table.
    $sql = $wpdb->prepare("SELECT table_name FROM information_schema.tables
        WHERE table_schema='$wpdb->dbname' AND table_name LIKE '$wpdb->base_prefix%%' AND engine<>'ClouSE'
        AND table_name > %s LIMIT 1", $start_id);

    $wpdb->last_error = '';
    $tables = $wpdb->get_results($sql, ARRAY_N);

    if ($wpdb->last_error)
        die_json_error_msg($wpdb->last_error);

    if (!count($tables))
        return __('Tables are moved to cloud.', 'wp2cloud');

    $wpdb->last_error = '';
    $wpdb->query("ALTER TABLE " . $tables[0][0] . " ENGINE='ClouSE'");

    if ($wpdb->last_error)
        die_json_error_msg($wpdb->last_error);

    $ret_id = $tables[0][0];
    return '';  // more to process
}

//--------
// Render move tables to cloud page.
function wp2cloud_render_tables_move() {
    global $wpdb;

    //--------
    // Get total count for phase 0.
    $count = $wpdb->get_results("SELECT COUNT(*) FROM information_schema.tables
                    WHERE table_schema='$wpdb->dbname' AND table_name LIKE '$wpdb->base_prefix%' AND engine<>'ClouSE'", ARRAY_N);
    $total = isset($count[0]) ? $count[0][0] : 100;

    //--------
    // Render common UI and info screen.
    wp2cloud_move_render_js('tables', 1 /* batch size */);

    echo '    <div class="wrap">';
    echo '        <h2>' . __('Move tables to cloud', 'wp2cloud') . '</h2>';

    $info_msg = __('Move tables to cloud. The operation may take a while if you have a lot of data in the database.', 'wp2cloud');
    $button_msg = __('Start', 'wp2cloud');

    print <<<EOT
        <div id="info">
            <p>$info_msg</p>
            <button onclick="wp2cloud_process_phase(0, $total)">$button_msg</button>
        </div>
EOT;

    //--------
    // Render processing phases.
    wp2cloud_move_render_phase(0, __('Moving tables to cloud.', 'wp2cloud'));

    //--------
    // Render "done" screen.
    wp2cloud_render_done_screen();

    echo '    </div>';  // wrap
}
