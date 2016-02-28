<?php

/* 
 * To modify some design from admin screen.
 * 
 */



/*
 * Modify admin bar logo
 */
/*
add_action('wp_head', 'change_admin_bar_logo', 100);
add_action('admin_head', 'change_admin_bar_logo', 100);
function change_admin_bar_logo() {
    
    $logo_url = get_bloginfo('template_directory').'/scopic-admin-functions/img/scopic_logo_circle.png';
    echo '<style type="text/css">'
    . '#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon::before { '
    . 'background: url('.$logo_url.') no-repeat  !important;'
    . 'background-size: 20px !important;'
    . 'display: inline-block;'
    . 'content: "";'
    . 'width: 20px;'
    . 'height: 20px;'        
    . '}</style>';
}
*/
  

/*
 * Modify login page logo
 */
//logo img
add_action('login_head', 'custom_login_logo');
function custom_login_logo() {
    $logo_url = get_bloginfo('template_directory').'/scopic-admin-functions/img/scopic_logo_circle.png';
    echo '<style type="text/css">h1 a { '
    . 'background-image: url('.$logo_url.') !important;'
    . 'background-repeat: no-repeat !important;'
    . 'background-size: 84px !important;'
    . 'background-position: center top !important;'
    . '}</style>';
}
//logo link
add_filter('login_headerurl', 'custom_logo_link');
function custom_logo_link() {
    return get_bloginfo('url');
}
//logo title
add_filter('login_headertitle', 'custom_logo_title');
function custom_logo_title(){
    return get_bloginfo('name');
}


/*
 * Modify footer text
 */
if (!current_user_can('administrator')) {
    add_filter('admin_footer_text', 'custom_admin_footer');
}
function custom_admin_footer() {
    echo 'Please contact <a href="mailto:yuta@scopic.nl">yuta@scopic.nl</a> if you have any questions!';
}