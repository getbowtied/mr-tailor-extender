<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://mrtailor.wp-theme.design/
 * Description:       		Extends the functionality of Mr. Tailor with Gutenberg elements.
 * Version:           		1.2.1
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		5.0
 * Tested up to: 			5.1
 *
 * @package  Mr. Tailor Extender
 * @author   GetBowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/mr-tailor-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'mr-tailor-extender'
);

add_action( 'init', 'gbt_mt_gutenberg_blocks' );
if(!function_exists('gbt_mt_gutenberg_blocks')) {
	function gbt_mt_gutenberg_blocks() {

		if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_mt_wp_version('>=', '5.0') ) {
			include_once 'includes/gbt-blocks/index.php';
		} else {
			add_action( 'admin_notices', 'mt_theme_warning' );
		}
	}
}

if( !function_exists('mt_theme_warning') ) {
	function mt_theme_warning() {

		?>

		<div class="error">
			<p>Mr. Tailor Extender plugin couldn't find the Block Editor (Gutenberg) on this site. 
				It requires WordPress 5+ or Gutenberg installed as a plugin.</p>
		</div>

		<?php
	}
}

if( !function_exists('is_mt_wp_version') ) {
	function is_mt_wp_version( $operator = '>', $version = '4.0' ) {

		global $wp_version;

		return version_compare( $wp_version, $version, $operator );
	}
}