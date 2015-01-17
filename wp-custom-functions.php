<?php
/*
Plugin Name: WP Custom Functions
Description: Functionality changes to WordPress, plugins, and themes instead of using functions.php.
Version: 0.1
License: GPL
Author: Osama Shabrez
Author URI: http://osamashabrez.com
*/

// Unlink urls in comment text
remove_filter('comment_text', 'make_clickable', 9);
 
//Remove the url field from your comment form
function remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

// Block Referal URL exploit for Comments
function verify_comment_referer() {
    if (!wp_get_referer()) {
        wp_die( __('You cannot post comment at this time, may be you need to enable referrers in your browser.') );
    }
}
add_action('check_comment_flood', 'verify_comment_referer');

// Create Nonce
function add_nonce_field_to_comment_form() {
    wp_nonce_field('comment_form_nonce_field');
}
 
// Include Nonce To Comment Form
add_action('comment_form', 'add_nonce_field_to_comment_form');
 
// Check Nonce Field Validity
function check_nonce_field_on_comment_form() {
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'comment_form_nonce_field')) {
        die('Nonce Check Failed - Killing Request');
    }
}
 
// Add Nonce Check To Comment Form Post
add_action('pre_comment_on_post', 'check_nonce_field_on_comment_form');