<?php
/*
Plugin Name: Page Embed
Version: 0.0.1
Description: Embed the content of pages in other pages.
Author: happyprime, jeremyfelt
Author URI: https://jeremyfelt.com
Plugin URI: https://github.com/happyprime/page-embed/
Text Domain: hp-page-embed
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// This plugin requires PHP 5.3 or greater.
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '',
	"echo '<div class=\"error\"><p>" . __( 'Page Embed requires PHP 5.3. Please upgrade PHP or deactivate the plugin.', 'hp-page-embed' ) . "</p></div>';" ) );
	return;
} else {
	include_once __DIR__ . '/includes/page-embed.php';
}
