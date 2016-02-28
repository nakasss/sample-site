=== WP2Cloud ===
Contributors: ArtemLivshits,Produced by OblakSoft
Donate link: http://www.oblaksoft.com/documentation/yapixx/
Tags: amazon, s3, cloud, aws, cloud storage, scale, fast, protect, photo, picture, image, media, upload, attachment, backup, buddypress, wp2cloud, google, GCS
Requires at least: 3.3.2
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Now WordPress site can store all its content (pages and media) in cloud. This makes site powered by enormous scale and reliability of cloud storage.
== Description ==

**Take your WordPress site to cloud with the help of Cloud Storage Engine ([ClouSE](http://www.oblaksoft.com/documentation "Cloud Storage Engine"))!**

WordPress-To-Cloud solution makes it possible for WordPress blogs to store all their data like pages, images, files in Amazon S3 or Google Cloud Storage.

Now WordPress can seamlessly get all the benefits of cloud storage - high availability, high reliability, quick and easy disaster recovery, and serving content in a highly scalable fashion.

WP2Cloud plugin is designed to complete the migration of a WordPress site to cloud storage.

= Benefits =
Cloud storage is awesome. It is highly- durable, reliable, available, and scalable; it has infinite capacity, it is the most mature and thus stable service - all of it on a Pay-For-What-You-Use pricing model.

Using cloud storage to store the website data has the following benefits:

- *No backup and recovery of the site data is needed*. More info is [here](http://www.oblaksoft.com/wordpress-on-s3-no-more-backups/ "WordPress on S3: No more backups").
- Storage is extremely reliable and durable by cloud storage design
- Pictures are served by cloud storage directly, which makes the website highly scalable
- Storage cost scales with usage, no upfront reservation is needed
- Storage consumption scales up and down with the amount of data stored.

= Deployment options =

WordPress-To-Cloud supports the following primary deployment choices:

1. Take the whole website data to cloud storage.
2. Only upload media files to cloud storage.
3. Only migrate website content to cloud storage while store media files in uploads folder on Web server running WordPress.

*As the best deployment practice we recommend moving to the cloud the whole website, both its content (that is stored in WordPress database) and media files.* 
Consider using our ready-to-run WordPress on Cloud Storage - [Oblaksoft Yapixx, the 1st WordPress website on Amazon S3](http://www.oblaksoft.com/documentation/yapixx/ "Yet Another Picture Sharing Site") as a starting point and a reference of such configuration. Refer to the complete [WordPress on S3](http://www.slideshare.net/artemlivshits/wordpress-on-s3-stepbystep), [WordPress on Google Cloud Storage](http://www.slideshare.net/artemlivshits/wordpress-to-google-cloud), and [WordPress on S3: do it yourself](http://www.slideshare.net/artemlivshits/wordpress-on-s3-do-it-youself) step-by-step guides for extra pointers in setting up WordPress on cloud storage.

= Hosting options =
The website server hosting decision can be made independently from the decision where to store the website data.
A Web server running the website can be hosted pretty much anywhere. Depending on your needs, the Web server can run by your favorite hoster, by a cloud provider, from your premise, as an instance in Amazon AWS or Google Cloud.

You can keep your current hosting arrangements, and only move the website data to cloud storage using WP2Cloud and ClouSE. 
With your data being safe in the cloud storage, if needed, you'll be able to easily change a hoster or a cloud, that is without any data migration whatsoever.

WordPress-To-Cloud solution works with nearly any hosting/cloud provider, but perhaps not with every hosting plan or stack supported by your provider. Feel free to contact your provider and us so we could attempt to make it work in your specific circumstances.
 
= How it works =
WP2Cloud stores media files such as pictures, music, videos, documents in the cloud. Media files that are uploaded to the WordPress are actually uploaded to the cloud storage, not to the local file system of the Web server hosting this website.

The media files are served from the cloud storage, the website pages now refer to the media files via direct cloud storage URLs. This makes your site directly powered by the enormous scale and reliability of cloud storage.

To make serving media files from Amazon S3 even faster, the plugin can be configured to use a Content Distribution Network (CDN), such as Amazon CloudFront. When the plugin is configured to use a CDN, the website pages refer to the media files via the CDN URLs. The CDN serves the media files from locations around the world to provide the best performance.

WP2Cloud plugin natively makes the website faster, more available and scalable:

1. Media files are stored reliably in the cloud and don't need to be backed up.
2. Website now can leverage the cloud power to scale out content delivery to the browser directly from the cloud storage. The file content is now served in a highly scalable fashion by the cloud storage utility provider which takes load off of the Web server. This is different from the default WordPress behavior where media files are local files, and are served by the Web server and consume network bandwidth, disk I/O and CPU power of the Web server
3. WordPress web tier is now stateless, so web tiers can now be auto-scaled and auto-balanced as needed to meet the website's scalability goals.
4. Files are distributed across the globe for fast access either natively by cloud storage itself and/or via an explicit integration with CDN.

WP2Cloud plugin relies on Cloud Storage Engine (ClouSE) for MySQL to enable integration with cloud storage.
In WordPress-to-Cloud solution, the site uses cloud storage as its *primary* storage. The storage configuration becomes transparent to WordPress. WordPress stores the website's data in MySQL, while ClouSE is plugged into MySQL and places all data into cloud storage, rather than using local disk. ClouSE plays the role of a database gateway for cloud storage, combining the best of local and cloud storage by delivering performance and security of local storage, plus all the benefits of the cloud storage.

= Supported cloud storage =
Supported cloud storage providers are:

- Amazon S3 and compatible. You can choose any of the Amazon Regions to store your data. Pick a Region to optimize for latency, minimize cost or address regulatory requirements.
- Google Cloud Storage.

Other storage providers may be added in the near future.

= Translations =
- Español por Andrew de [WebHostingHub](http://www.webhostinghub.com/)
- Русский предоставил OblakSoft

= More reading =
- [S3 Storage for WordPress Blogs](http://readwrite.com/2012/05/16/s3-storage-for-wordpress-blogs "readwrite.com: S3 Storage for WordPress Blogs")
- [Launching WordPress into the Google cloud](http://googledevelopers.blogspot.com/2012/11/launching-wordpress-into-google-cloud.html "WordPress on Google Cloud")
- [WP2Cloud featured at Softpedia.com](http://webscripts.softpedia.com/script/Modules/WordPress-Plugins/WP2Cloud-76263.html "WP2Cloud")

= Future plans =
Our goal is to design the best WordPress-to-Cloud experience for YOU. 
Cloud storage is very powerful, we'd love to take full advantage of its capabilities!

**We really appreciate your feedback.**
What are your scenarios? What would you like to see added, improved? What problems can we help you with?

*Feel free to [contact us](http://www.oblaksoft.com/about-us/email-us/ "email OblakSoft").* Thank you in advance!

= Support =

This plugin is actively supported by [OblakSoft](http://www.oblaksoft.com/ "http://www.oblaksoft.com/") and we will do our best to help you. In return we simply ask:

1. Rate this plugin or write a review. We cordially ask you to please [contact us](http://www.oblaksoft.com/about-us/email-us/) before giving a poor rating.
2. Follow us at our [OblakSoft Facebook page](http://www.facebook.com/pages/OblakSoft-LLC/324769304220011/ "http://www.facebook.com/pages/OblakSoft-LLC/324769304220011/").
3. Support us by referring, blogging about WP2Cloud plugin and/or ClouSE storage engine.
4. Help Out. If you see a question on the forum you can help with or have a great idea and want to code it up and submit a patch, that would be just plain awesome and we will shower your with praise. Also, we are happy to post translations if you provide them.
5. Donate - if this plugin makes your life easier enough to support our time it makes all the difference in the world. We are a small self-funded startup. You can donate to OblakSoft development team via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JMUHXKMNCQ75N "WP2Cloud PayPal URL").

**Thank you!**

== Installation ==

**Install WordPress on Cloud Storage**

If you plan to store all WordPress site data on cloud storage, consider using our preconfigured ready-to-run [WordPress on Cloud Storage image](http://www.oblaksoft.com/download/ "Download Yapixx: WordPress on Cloud Storage appliance"). 
*Pick a cloud storage and launch your own WordPress site to it in a matter of minutes.* WordPress on Cloud Storage image simply consists of the complete up-to-date stack: LAMP, ClouSE, WordPress, WP2Cloud. And this image provides an intuitive user interface to help configure connection to cloud storage. 

For additional information about WordPress on Cloud Storage please check out these detailed step-by-step instructions: [WordPress on S3](http://www.slideshare.net/artemlivshits/wordpress-on-s3-stepbystep), [WordPress on Google Cloud Storage](http://www.slideshare.net/artemlivshits/wordpress-to-google-cloud), and [WordPress on S3: do it yourself](http://www.slideshare.net/artemlivshits/wordpress-on-s3-do-it-youself).

See also [this announcement](http://www.oblaksoft.com/wordpress-on-s3-newsletter-may-2012/ "WordPress on Cloud Storage").

**Installation steps to only keep uploads on Cloud Storage**

In case you are interested only in uploading media files to cloud, please follow WP2Cloud installation instructions listed below.

*Please note that WP2Cloud plugin relies on Cloud Storage Engine (ClouSE) for MySQL to enable integration with cloud storage.*

To install and configure ClouSE please refer to these steps:

**Amazon S3**

1. [Sign up](http://docs.amazonwebservices.com/AmazonS3/latest/gsg/SigningUpforS3.html "http://docs.amazonwebservices.com/AmazonS3/latest/gsg/SigningUpforS3.html") for an AWS account.
2. [Create](http://docs.amazonwebservices.com/AmazonS3/latest/gsg/CreatingABucket.html "http://docs.amazonwebservices.com/AmazonS3/latest/gsg/CreatingABucket.html") an S3 bucket if you don't have one.
3. [Install and configure](http://www.oblaksoft.com/documentation/getting-started-with-clouse/ "http://www.oblaksoft.com/documentation/getting-started-with-clouse/") ClouSE.

**Google Cloud Storage**

1. [Sign up](http://developers.google.com/storage/docs/signup "http://developers.google.com/storage/docs/signup") for Google Cloud Storage.
2. [Enable](https://developers.google.com/storage/docs/reference/v1/apiversion1#enabling "https://developers.google.com/storage/docs/reference/v1/apiversion1#enabling") Interoperable Access.
3. [Create](https://developers.google.com/storage/docs/gsmanager#creatingfolders "https://developers.google.com/storage/docs/gsmanager#creatingfolders") a bucket if you don't have one.
4. [Install and configure](http://www.oblaksoft.com/documentation/getting-started-with-clouse/ "http://www.oblaksoft.com/documentation/getting-started-with-clouse/") ClouSE.

Once ClouSE is installed and configured, you can now activate WP2Cloud plugin: 

1. You can download and install WP2Cloud using the built-in WordPress plugin installer.  If you download WP2Cloud manually, unpack and upload it to the /wp-content/plugins/ directory.  WP2Cloud requires the Cloud Storage Engine for MySQL (ClouSE) to be installed and configured on the MySQL server.  **If ClouSE is not installed, WP2Cloud will fail to activate.**
2. In your WordPress administration, go to the Plugins page.
3. Activate the WP2Cloud plugin.

Now all new media files are going to be uploaded to the cloud.  

*Your website can gain higher availability and scalability!*

Optional: to configure the plugin to use a Content Distribution Network (CDN), go to the *WordPress to Cloud Settings* page and specify the Cloud Distribution URL. The CDN must be configured to use the cloud storage bucket as a content origin. For additional information about using WordPress on S3 with CloudFront please check out these detailed [step-by-step]( http://www.slideshare.net/artemlivshits/wordpress-on-s3-now-with-cdn) instructions.

**Move existing media files to Cloud Storage**

If your website has existing media files, you can move the media files to the cloud storage. Go to the *WordPress to Cloud Settings* page and click the *Move media to the cloud* link.


**Documentation and support**

The comprehensive documentation can be found [here](http://www.oblaksoft.com/documentation/yapixx/ "plugins at oblaksoft.com").

Please visit this [forum](http://wordpress.org/support/plugin/wp2cloud-wordpress-to-cloud/ "http://wordpress.org/support/plugin/wp2cloud-wordpress-to-cloud/") for questions or comments. Older support discussions can be found [here](http://wordpress.org/tags/wp2cloud/).

If you aren't able to find a resolution, please email us at bugs@oblaksoft.com. 
The authors are available for professional consulting and troubleshooting to make sure the solution meets your configuration and customization needs.

**Requirements**

* MySQL Server 5.5.19 or above
* Apache 2.2.15 or above
* PHP 5.3.2 or above
* ClouSE 1.0b.1.1 or above

**Advanced deployment configuration with Media Server**

If you don't have permissions to install the Cloud Storage Engine (ClouSE) on the MySQL server that stores your WordPress database, you can configure WP2Cloud to use a separate Media Server. The Media Server makes it possible to use cloud storage to store and serve media files, while keeping your existing database solution to store website content.

To use a Media Server, first [install and configure](http://www.oblaksoft.com/documentation/getting-started-with-clouse/ "http://www.oblaksoft.com/documentation/getting-started-with-clouse/") a MySQL server with ClouSE. After the Media Server is up and running, [create a database](http://codex.wordpress.org/Installing_WordPress#Step_2:_Create_the_Database_and_a_User) for WP2Cloud on your Media Server, as well as a MySQL user who has all privileges for accessing and modifying it.

[Edit wp-config.php](http://codex.wordpress.org/Editing_wp-config.php) and fill in the Media Server database configuration into the MEDIA_DB_* settings (similar to the DB_* settings):

`// ** MySQL media server settings ** //
/** The name of the database for WordPress media files */
define('MEDIA_DB_NAME', 'putyourdbnamehere');

/** MySQL database username */
define('MEDIA_DB_USER', 'usernamehere');

/** MySQL database password */
define('MEDIA_DB_PASSWORD', 'yourpasswordhere');

/** MySQL hostname */
define('MEDIA_DB_HOST', 'localhost');`

== Frequently Asked Questions ==

= What makes WP2Cloud different from other plugins that upload files to Amazon S3 or Google Cloud Storage? =
WP2Cloud plugin for WordPress:

1. Is the last piece in the puzzle of moving the whole WordPress site to cloud storage. It allows to take full advantage of cloud storage by consolidating both website content and media on the cloud storage.
2. Makes WordPress web tier stateless: media is stored on cloud storage, not on the web tier filesystem. This makes it possible to auto-scale and auto-balance WordPress web tiers.
3. Uploads files faster because it relies on [OblakSoft WebStor](http://www.oblaksoft.com/high-performance-amazon-s3-api-webstor/ "High Performance Library for Cloud Storage") - high performance library for Amazon S3 and Google Cloud Storage.
4. Handles media file uploads in a transactional manner. Either the whole file and all its derivative files (e.g. thumbnails for pictures) get uploaded or nothing does.

= Is there a limit on number or size of files being uploaded? =
Not at all. Since cloud storage space is nearly limitless, you can use as much of it as you need. WP2Cloud can upload very large files as it uses multi-part upload and thus it is not restricted by the available memory on the Web server.

= How long do I have to wait for the files to be uploaded to cloud storage? =
Media files are uploaded to cloud storage as fast as physically possible with the help of [WebStor](http://www.oblaksoft.com/high-performance-amazon-s3-api-webstor/ "High Performance Library") - high performance library for Amazon S3 and Google Cloud Storage. 

= Can I update media files using this plugin? =
Absolutely, your media experience is not affected by this plugin. You can change image, add title, alternative text, media tags - whatever you might need.

= I deleted a media file from WordPress, but I still can see it in the cloud storage, why? =
The actual deletion is intentionally delayed to account for caching latencies. For example, even though you deleted the image and removed the link from the page, the old page content might be still cached. The delay helps the old page to render correctly and show the old images.

If you still see a media file in the cloud after a couple hours have passed since deletion, please email us at bugs@oblaksoft.com.

= How can I prevent hotlinking to files stored in Amazon S3? =
You can use [Amazon S3 bucket policies](http://docs.amazonwebservices.com/AmazonS3/latest/dev/UsingBucketPolicies.html "Amazon S3 bucket policies"). 
For details please refer to [WordPress on S3: how to prevent hotlinking](http://www.oblaksoft.com/wordpress-on-s3-how-to-prevent-hotlinking/ "Hotlinking on cloud storage URLs").

= Can I use multisite setup with this plugin? =
Yes, both domain-based and path-based multisite setup is supported.

= Can I use W3 Total Cache with this plugin? =
Yes, WP2Cloud is compatible with W3 Total Cache. In fact, the latest WordPress-on-S3 AMI has W3 Total Cache plugin installed and configured.

= Can I use BuddyPress with this plugin? =
Yes, just make sure you use ClouSE 1.0b.1.4 (or later) and WP2Cloud 1.2.0 (or later).

= I'm using the WordPress-on-S3 AMI, how can I edit files on the EC2 instance? =
You can edit files in the File Manager module of the Webmin system administration console. Log into Webmin, select Others -> File Manager module on the left. The WordPress files are located in /var/www/wordpress. For information on how to connect to Webmin see [this post](http://www.oblaksoft.com/wordpress-on-s3-going-professional/#_system_administration).

Alternatively, you can use the Upload and Download module to transfer files between the instance and your computer. Log into Webmin, select Others -> Upload and Download.

For quick access to wp-config.php and .htaccess, log into Webmin, select Others -> Custom Commands.

*Note: Webmin and phpMyAdmin are available in WordPress-on-S3 AMIs released on 01/31/2013 or later*

= I've launched a website from WordPress-on-S3 AMI. How can I create a friendly domain name for the website? How can I secure website access with SSL? =
Please refer to [WordPress on S3: DNS and HTTPS configuration](http://www.oblaksoft.com/wordpress-on-s3-dns-https-configuration/) blog.

= My webserver runs on Windows, can I use WP2Cloud? =
Yes, ClouSE for Windows is currently available to a group of limited preview customers. If you would like to sign up for the limited preview program please [contact us](http://www.oblaksoft.com/about-us/email-us/ "email us").

= I'm using ClouSE and WP2Cloud to store my website's data in cloud storage (such as Amazon S3), can I export the data? =
Yes. Standard MySQL tools work well with ClouSE. For example, you can use mysqldump to export the whole WordPress database into a file. In fact, WP2Cloud makes data export easier and more robust, because the database dump now includes the media, so the dump fully represents all website's data.

= I don't have permissions to install ClouSE on my WordPress database server, can I still use WP2Cloud? =
Yes, you can configure WP2Cloud to use a separate Media Server. The configuraiton instructions are provided [here](http://wordpress.org/extend/plugins/wp2cloud-wordpress-to-cloud/installation/). See also [this post](http://wordpress.org/support/topic/to-use-a-separate-media-server#post-4134849) for information about how to install and configure MySQL as a Media Server on a shared hosting server.

= Where do I go to file a bug or ask a question? =
The comprehensive documentation can be found [here](http://www.oblaksoft.com/documentation/yapixx/ "plugins at oblaksoft.com").

Please visit this [forum](http://wordpress.org/support/plugin/wp2cloud-wordpress-to-cloud "http://wordpress.org/support/plugin/wp2cloud-wordpress-to-cloud/") for questions or comments. Older support discussions can be found [here](http://wordpress.org/tags/wp2cloud/).

If you aren't able to find a resolution, please email us at bugs@oblaksoft.com. 
The authors are available for professional consulting to meet your configuration, troubleshooting and customization needs.

= Known issues =

* Some Linux distributions have custom-built MySQL binaries that are different from the official MySQL builds available from www.mysql.com. ClouSE may fail to install with custom-built MySQL binaries, printing an error like "ERROR 1126 (HY000): Can't open shared library '/usr/lib64/mysql/plugin/ha_clouse-5.5.20.so' (errno: 13 undefined symbol: my_pthread_fastmutex_init)". In this case you need to install the official MySQL binaries from www.mysql.com. See [this](http://wordpress.org/support/topic/error-during-mysql-plugin-install?replies=15#post-2825921) and [this](http://wordpress.org/support/topic/plugin-wp2cloud-plugin-installation-error-mysql?replies=6#post-3255676) forum posts for more details.

* The wp-smush.it plugin 1.6.5.4 requires a [patch](http://wordpress.org/support/topic/compress-images-in-google-cloud-storage#post-4823865) to work with WP2Cloud. Make sure that the **Enforce image URL is same as Home** option (Settings --> Media) is unchecked.

* The wp-post-thumbnail plugin doesn't work with WP2Cloud. wp-post-thumbnail uses direct filesystem calls making it impossible for WP2Cloud to provide media file storage.

* The bp-album plugin 0.1.8.14 needs a [patch](https://github.com/BP-Media/bp-album/issues/5) in order for picture deletions to work properly. The dev version of db-album at [github](https://github.com/BP-Media/bp-album/) already has the [fix](https://github.com/BP-Media/bp-album/commit/5b4396707260233557f1daa302b6b567ab7afa50).

== Screenshots ==

1. Add media: when media files are uploaded to the WordPress they are actually uploaded to the cloud storage.  The derivative media files (e.g. thumbnails for pictures) are also uploaded to the cloud storage. Note that Link URL points to the WP2Cloud media file redirector: this URL redirects to the cloud storage URL. Note that this changes the default WordPress behavior for media files and their related derivative files to be stored on the local file system of the Web server hosting the website.
2. Insert a picture into a post: when a picture is inserted into a post, WP2CLOUD shortcodes are stored in the post for the webpage instead of the actual picture and thumbnail URLs.  Such WP2CLOUD shortcode contains the ID that uniquely identifies the media file on the website.
3. View post source: before the page is delivered by the Web server to the Web client (browser), the WP2CLOUD shortcodes are translated into the actual direct URLs that point to the cloud storage on Amazon S3.  The web browser downloads all the media files (including thumbnails) from the cloud storage, bypassing the web server. Note that media files are served by Amazon S3 directly relying on enormous power of the cloud to make serving the content highly scalable.
4. Setup CDN (optional): to make content delivery even faster, you can setup a Content Distribution Network (CDN) for the media files.  When the CDN URL is specified, the media files URLs point to the CDN that takes care of distributing files in the most optimal fashion.
5. View post source with CDN: when a CDN is used, the media files are served from the CDN.
6. High-level architectural diagram of a WordPress-powered website that uses cloud storage to store and serve its content.  The website's content management is done through WordPress, which uses a MySQL server to store the website's data. The website using the WP2Cloud plugin will upload pictures (and other media files) directly to cloud storage maintaining the files location in MySQL.  ClouSE plugs into MySQL and works directly with cloud storage using the cloud storage API to store all website data, site's content and media files.  This makes the web server natively stateless, improving its robustness and scalability. Further on, the performance of the website increases as while the web pages (lightweight HTML) are still served by WordPress, media files are now served by cloud storage directly.

== Changelog ==

= 1.4.0 =
* Add support for cloud migration, now existing site data can move to cloud
* Hide licensing UI from non-superadmins (multisite only)
* Fix a problem with media server configuration when PHP uses mysqli connection

= 1.3.16 =
* Implement ClouSE license ordering functionality

= 1.3.15 =
* Fix "Constant ABSPATH is aready defined" PHP notice
* Handle references to media in other sites in the same network (multisite only)

= 1.3.14 =
* Fix incorrect media URLs in posts created via XMLRPC (e.g. from a mobile app)
* Add Spanish translation by Andrew Kurtis, [WebHostingHub](http://www.webhostinghub.com/)

= 1.3.13 =
* Handle blog switching in multi-site setup (see [this post](http://wordpress.org/support/topic/error-on-switch_blog))

= 1.3.12 =
* Add link to Amazon CloudFront setup guide into the CDN settings description
* Embed links to step-by-step guides into the "ClouSE is missing" activation error
* Add Russian translation
* Implement plugin localization support

= 1.3.11 =
* Add support for wp2cloud-config.php, which can be used to add custom ABSPATH
* Handle weblob_id-based (redirect) URLs in WooCommerce downloads

= 1.3.10 =
* Make wp2cloud_get_file_url handle files that haven't been uploaded to cloud yet

= 1.3.9 =
* Add wp2cloud_download_file hook for other plugins to integrate with WP2Cloud

= 1.3.8 =
* Add wp2cloud_* hooks for other plugins to integrate with WP2Cloud
* Handle weblob_id-based (redirect) URLs in sitemaps generated by WordPress SEO plugin
* Handle weblob_id-based (redirect) URLs in parentheses

= 1.3.7 =
* Handle single-quoted weblob_id-based (redirect) URLs
* Implement a more robust way of translating weblob_id-based (redirect) URLs to direct cloud URLs
* Use 301 code (permanent redirect) for weblob_id-based redirects

= 1.3.6 =
* Add a warning to inform the admin when WordPress tables are not on cloud storage

= 1.3.5 =
* Fix a problem with thumbnail updates; now thumbnails can be updated w/o updating the main image
* Perf: avoid extra database queries when rendering cloud URLs

= 1.3.4 =
* Add support for http://wordpress.org/extend/plugins/bp-album/

= 1.3.3 =
* Fix a problem with images that have names containing non-Latin characters
* Add support for PHP versions that don't have exif_read_data function

= 1.3.2 =
* Properly generate weblob_id-based (redirect) URLs with default WP_CONTENT_URL

= 1.3.1 =
* Add support for WordPress 3.5
* Fix code that caused PHP warnings in the error log

= 1.3.0 =
* Add support for Media Server
* Fix code that caused PHP warnings in the error log

= 1.2.1 =
* Fix a problem with uploading images that contain Exif tags

= 1.2.0 =
* Add support for BuddyPress; now BuddyPress avatars are stored on cloud storage
* Fix a problem with CDN distribution URLs that contain ports; now the port is properly preserved

= 1.1.5 =
* Eliminate some debug-only warnings
* Properly handle image resize arguments in the weblob_id-based (redirect) URLs
* Fix compatibility issues with wpallimport plugin

= 1.1.4 =
* Fix problems with translating weblob_id-based (redirect) URLs to direct cloud URLs
* Fix problems with editing images when using path-based multisite setup

= 1.1.3 =
* Eliminate usage of 'goto' as it's not supported by some PHP versions

= 1.1.2 =
* Add cache control headers for resources that don't change
* Fix code that caused PHP warnings in the error log
* Fix problems with images in the header that are wider than the required width
* Fix problems with image editing after recovery from cloud: help WordPress to auto-create directories for images
* Fix problems with editing images that were uploaded with a different theme (that had custom image sizes)

= 1.1.1 =
* Fix problems with multisite setup caused by unfortunately named settings.php; thanks to [thorstone137](http://profiles.wordpress.org/thorstone137) for investigating the issue and providing a fix
* Fix problems with network-activating the plugin; now it can be network-activated in the multisite setup and won't require activation for each site
* Fix problems with moving the plugin to a different directory; now it can properly self-reference without hardcoding the path to the plugin

= 1.1.0 =
* Add support for Content Distribution Network (CDN)

= 1.0.3 =
* NOTE: the data format has changed, if you have production data created with version 1.0.2, please contact us
* Fix problems with image editing
* Fix problems with images in the header and background
* Fix problems with showing images in the Visual Editor

= 1.0.2 =
* Fix media URLs for the generic uploader; previously it only worked correctly for media in posts

= 1.0.1 =
* Fix error handling for MySQL errors
* Fail to activate if ClouSE is not configured, and provide guidance to the user

= 1.0.0 =
* WP2Cloud released

== Upgrade Notice ==

n/a
