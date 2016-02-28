<?php

function wp2cloud_validate_cdn_url($wp2cloud_cdn)
{
    if (!$wp2cloud_cdn)
        return true;

    $wp2cloud_cdn_url = (strpos($wp2cloud_cdn, "://") === false ? "wp2cloud://" : "").$wp2cloud_cdn;

    return filter_var($wp2cloud_cdn_url, FILTER_VALIDATE_URL);
}

//--------
// Set ClouSE licensing information to the engine.
function wp2cloud_set_clouse_license($license_text)
{
    global $wp2cloud_plugin;

    //--------
    // The license text is a SQL statement that sets some variables.
    // We parse the statement, extract the variables and generate new
    // SQL.  The license text looks like this:
    //
    //   SET SESSION clouse_license_url='s3://yapixx/db0';
    //   SET SESSION clouse_license_exp='2015-02-16';
    //   SET SESSION clouse_license_sku='BAS';
    //   SET SESSION clouse_license_key='********';

    $ile_fmt = __('Invalid license. Please enter license text from %1$s that you\'ve receieved in email. If you have any questions, contact %2$s', 'wp2cloud');
    $invalid_license_error = sprintf($ile_fmt, 'license.sql', 'support@oblaksoft.com');
    $valid_vars = array('clouse_license_url', 'clouse_license_exp', 'clouse_license_sku', 'clouse_license_key');

    if (preg_match_all('/(clouse_license_.+)=["\']([^"\']+)/', $license_text, $matches, PREG_SET_ORDER) != count($valid_vars))
        return $invalid_license_error;

    $clouse_vars = array();

    foreach ($matches as $match)
    {
        if (!in_array($match[1], $valid_vars) || isset($clouse_vars[$match[1]]))
            return $invalid_license_error;

        $clouse_vars[$match[1]] = $match[2];
    }

    if (count($clouse_vars) != count($valid_vars))
        return $invalid_license_error;

    //--------
    // Set the license.  We enforce variable order, just in case.

    for($i = 0; $i < count($valid_vars); $i++)
    {
        $fmt = "SET SESSION ". $valid_vars[$i] . "=%s;";
        $sql = $wp2cloud_plugin->media_db->prepare($fmt, $clouse_vars[$valid_vars[$i]]);
        $wp2cloud_plugin->media_db->last_error = '';
        $wp2cloud_plugin->media_db->query($sql);

        if ($wp2cloud_plugin->media_db->last_error)
            return $wp2cloud_plugin->media_db->last_error;
    }

    return '';
}

//--------
// Plugin settings page.
function wp2cloud_settings()
{
    global $wp2cloud_plugin;

    if(!empty($_POST['submit']))
    {
        //--------
        // Update settings.
        $wp2cloud_cdn = $_POST['wp2cloud_cdn'];
        $wp2cloud_cdn_html = htmlspecialchars($wp2cloud_cdn);

        $wp2cloud_warn_nocloud = empty($_POST['wp2cloud_warn_nocloud']) ? null : $_POST['wp2cloud_warn_nocloud'];

        if (!wp2cloud_validate_cdn_url($wp2cloud_cdn))
        {
            echo '<div id="error" class="error"><p>';
            printf(__('Invalid Distribution URL: %1$s', 'wp2cloud'), $wp2cloud_cdn_html);
            echo '</p></div>';
        }
        else
        {
            $options = array('cdn' => $wp2cloud_cdn, 'ignore_nocloud' => $wp2cloud_warn_nocloud == '1' ? null : '1');
            update_option('wp2cloud', $options);
            echo '<div id="message" class="updated"><p>' . __('Settings updated.', 'wp2cloud') . '</p></div>';
        }
    }
    else
    {
        //--------
        // Get settings.
        $options = get_option('wp2cloud');
        $wp2cloud_cdn = $options['cdn'];
        $wp2cloud_cdn_html = htmlspecialchars($wp2cloud_cdn);
        $wp2cloud_warn_nocloud = $options['ignore_nocloud'] == '1' ? null : '1';
    }

    if (!empty($_POST['paste_license']))
    {
        //--------
        // The user pasted the license text, try to install the license.
        $error = wp2cloud_set_clouse_license(stripslashes($_POST['license_text']));

        if ($error)
            echo '<div id="message" class="error"><p>' . htmlspecialchars($error) . '</p></div>';
        else
            echo '<div id="message" class="updated"><p>' . __('License installed.', 'wp2cloud') . '</p></div>';
    }

    if (!empty($_POST['install_license']))
    {
        //--------
        // Contact oblaksoft.com to load a previously ordered license.
        $lic = $wp2cloud_plugin->get_clouse_license();

        if ($lic && isset($lic['clouse_license_url']))
        {
            $bucket_url = htmlspecialchars($lic['clouse_license_url']);
            $site_key = htmlspecialchars(wp_hash($bucket_url));

            $url = 'https://www.oblaksoft.com/wp-admin/admin-ajax.php?action=check-license';
            $url = add_query_arg(array('bucket_url' => $bucket_url, 'site_key' => $site_key), $url);

            $license_text = file_get_contents($url);

            if ($license_text)
                $error = wp2cloud_set_clouse_license($license_text);
            else
                $error = __('License is not found, please order one first.', 'wp2cloud');
        }
        else
        {
            $error = __('Database access error, please retry later.', 'wp2cloud');
        }

        if ($error)
            echo '<div id="message" class="error"><p>' . htmlspecialchars($error) . '</p></div>';
        else
            echo '<div id="message" class="updated"><p>' . __('License installed.', 'wp2cloud') . '</p></div>';
    }

    $wp2cloud_warn_nocloud_checked = checked('1', $wp2cloud_warn_nocloud, false);

    //--------
    // Display settings page.
    $icon_html = screen_icon('options-general');
    $settings_header = __('WordPress to Cloud Settings', 'wp2cloud');

    $cdn_header = __('Content Distribution Network (CDN)','wp2cloud');
    $cdn_text = __('    You can setup a CDN (e.g. <a href="http://aws.amazon.com/cloudfront/">Amazon CloudFront</a>)
                for media files to make content delivery even faster.  To do so, please specify the distribution
                URL that would be used instead of the <em>host/bucket</em> part of the cloud storage location.
                For example, if your cloud storage location looks like <em>s3.amazonaws.com/oblaksoft-yapixx/db0</em>
                and the distribution URL looks like <em>d111111abcdef8.cloudfront.net</em>, the media files
                would have URLs like <em>http://d111111abcdef8.cloudfront.net/db0/path/to/foo.jpg</em>.  You
                need to <a href="http://www.slideshare.net/artemlivshits/wordpress-on-s3-now-with-cdn">configure</a>
                the CDN to point it to the cloud storage origin location.', 'wp2cloud');
    $cdn_dist_url = __('Distribution URL', 'wp2cloud');
    $cdn_descr = sprintf(__('E.g. %1$s', 'wp2cloud'), '<code>d11111abcdef8.cloudfront.net</code>');

    $dbp_header = __('Cloud database protection', 'wp2cloud');
    $dbp_text = __('    If you opt to store your whole WordPress database in the cloud storage, WordPress to Cloud can
                warn you if any of the WordPress tables are not stored in the cloud. If you decide to ignore
                this warning, you should consider protecting your data by other means (e.g. database backups).',
        'wp2cloud');
    $dbp_warn = __('Warn when WordPress tables are not stored in the cloud storage', 'wp2cloud');

    $save_changes = __('Save Changes', 'wp2cloud');

    list($expired_fmt, $license_fmt, $beta_fmt) = $wp2cloud_plugin->get_license_fmt_strings();
    $lic = $wp2cloud_plugin->get_clouse_license();

    if ($lic)
    {
        $cvm_fmt = __('%1$s version is %2$s.', 'wp2cloud');
        $clouse_version_message = sprintf($cvm_fmt, 'ClouSE',  $lic['clouse_version']);
    }

    if ($lic && isset($lic['clouse_license_url']))
    {
        $bucket_url = htmlspecialchars($lic['clouse_license_url']);
        $site_url = htmlspecialchars(site_url());
        $site_key = htmlspecialchars(wp_hash($bucket_url));

        $expiration = $lic['clouse_license_exp'];
        $sku_name = $wp2cloud_plugin->get_license_sku_name($lic['clouse_license_sku']);

        if (strtotime($expiration) >= time())
            $lic_message = sprintf($license_fmt, "<b>$sku_name</b>", "ClouSE", "<b>$expiration</b>");
        else
            $lic_message = sprintf($expired_fmt, "ClouSE", "<b>$expiration</b>");

        $paste_content_msg = sprintf(__('Paste content of %s here ...', 'wp2cloud'), 'license.sql');

        $order_lic_hint_msg = sprintf(__('Order license at %s', 'wp2cloud'), 'www.oblaksoft.com');
        $order_lic_msg = __('Order License', 'wp2cloud');

        $already_ordered_msg = __('Already ordered?', 'wp2cloud');

        $install_lic_hint_msg = __('Install previously ordered license', 'wp2cloud');
        $install_lic_msg = __('Install License', 'wp2cloud');

        $paste_lic_hint_msg = __('Paste previously ordered license', 'wp2cloud');
        $paste_lic_msg = __('Paste License', 'wp2cloud');

        $licensing_form = <<<EOT
        <p>$clouse_version_message  $lic_message</p>

        <textarea id="license_input" rows="5" cols="64" style="display:none" placeholder="$paste_content_msg"></textarea>
        <table>
        <tbody><tr valign="top">

        <td><form action="https://www.oblaksoft.com/licensing/" method="POST">
            <input name="bucket_url" type="hidden" value="$bucket_url">
            <input name="site_url" type="hidden" value="$site_url">
            <input name="site_key" type="hidden" value="$site_key">
            <input type="submit" name="submit" title="$order_lic_hint_msg" value="$order_lic_msg">
        </form></td>

        <td><form method="POST">
            $already_ordered_msg
            <input type="submit" name="install_license" title="$install_lic_hint_msg" value="$install_lic_msg">
        </form></td>

        <td><form method="POST">
            <input name="license_text" id="license_text" type="hidden" value="">
            <input type="submit" name="paste_license" title="$paste_lic_hint_msg" value="$paste_lic_msg" onClick="return paste_license_text()">
        </form></td>

        </tr>
        </tbody></table>
EOT;
    }
    else if ($lic)
    {
        $licensing_form = $clouse_version_message . "  " . sprintf($beta_fmt, "ClouSE", "<b>2015-01-01</b>");
    }
    else
    {
        $licensing_form = sprintf(__('Could not get %s information.', 'wp2cloud'), 'ClouSE');
    }

    $licensing_header = sprintf(__('%s licensing', 'wp2cloud'), 'ClouSE');

    $move_media_msg = __('Move media to cloud', 'wp2cloud');
    $move_media_link = admin_url('options-general.php?page=wp2cloud-move-media');

    print <<<EOT
    <script type="text/javascript">
        function paste_license_text() {
            $ = jQuery;

            if (!$("#license_input").is(":visible")) {
                $("#license_input").show();
                $("#license_input").focus();
                return false;
            }

            $("#license_text").val($("#license_input").val());
            return true;
        }
    </script>

    <div class="wrap">
        $icon_html <h2>$settings_header</h2>
        <form method="POST">
            <h3>$cdn_header</h3>

            <p>
            $cdn_text
            </p>

            <table class="form-table">
            <tbody><tr valign="top">
                <th scope="row"><label for="wp2cloud_cdn">$cdn_dist_url</label></th>
                <td><input name="wp2cloud_cdn" type="text" id="wp2cloud_cdn" value="$wp2cloud_cdn_html" class="regular-text code">
                <p class="description">$cdn_descr</code></p>
                </td>
            </tr>
            </tbody></table>
EOT;

    if (current_user_can('install_plugins'))
    {
        print <<<EOT
            <h3>$dbp_header</h3>
            <p>
            $dbp_text
            </p>

            <table class="form-table">
            <tbody><tr>
            <th scope="row" colspan="2" class="th-full">
            <label for="wp2cloud_warn_nocloud">
            <input name="wp2cloud_warn_nocloud" type="checkbox" id="wp2cloud_warn_nocloud" value="1" $wp2cloud_warn_nocloud_checked>
            $dbp_warn</label>
            </th> </tr>
            </tbody></table>
EOT;
    }

    print <<<EOT
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="$save_changes">
                <a href="$move_media_link" style="float:right">$move_media_msg</a>
            </p>
        </form>

EOT;

    if (current_user_can('install_plugins'))
    {
        print <<<EOT
        <hr>
        <h3>$licensing_header</h3>
        $licensing_form
EOT;
    }

    echo '    </div>';
}

?>
