<?php

/*
 * Load wp asset
 */


/*
 * Paths
 */
$scopic_admin_path = dirname( dirname(__FILE__) );
$theme_path = dirname( $scopic_admin_path );
$wp_content_path = dirname( dirname( $theme_path ) );
$wp_root_path = dirname( $wp_content_path );


/*
 * require wp-load.php
 */
require_once $wp_root_path.'/wp-load.php';

