<?php
// This is a JavaScript.
header('Content-type: application/javascript');
header('Cache-Control: public, max-age=31536000');  // cache for 365 days

// Load WordPress bootstrap.  This requires wp2cloud-mce.php to be in the
// [ROOT]/wp-content/plugins/wp2cloud-wordpress-to-cloud/ directory.
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

function wp2cloud_plugin_dir_url_mse() {
    global $current_blog;

    $url = @parse_url(plugin_dir_url(__FILE__), PHP_URL_PATH);

    if (!empty($current_blog) && strpos($url, $current_blog->path) !== 0) {
        $url = rtrim($current_blog->path, '/') . '/' . ltrim($url, '/');
    }

    return $url;
}

// Emit the JS variable that has a reference back to the plugin dir.
echo 'var wp2cloud_dir_url = "' . wp2cloud_plugin_dir_url_mse() . '";';
?>

(function() {
	tinymce.create('tinymce.plugins.wp2cloud', {

		init : function(ed, url) {
			var t = this;
			t.url = url;

			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t._get_wblob_url(o.content);
			});

			ed.onExecCommand.add(function(ed, cmd, ui, val) {
				if(cmd == "mceInsertContent")
					ed.setContent(ed.getContent());
			});
		},

		_get_wblob_url : function(co) {
			return co.replace(/\"\[WP2CLOUD (weblob_id=[^\]]+)\]\"/g, '"' + wp2cloud_dir_url + 'wp2cloud.php?$1"');
		},

		getInfo : function() {
			return {
				longname : 'WP2Cloud shortcode handler',
				author : 'OblakSoft',
				authorurl : 'http://www.oblaksoft.com',
				infourl : '',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('wp2cloud', tinymce.plugins.wp2cloud);
})();
