<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://github.com/getbowtied/mr-tailor-extender
 * Description:       		Extends the functionality of Mr. Tailor with Gutenberg elements.
 * Version:           		1.0
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		4.9
 * Tested up to: 			4.9.7
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

// add_action( 'init', 'github_plugin_updater' );
// function github_plugin_updater() {

// 	include_once 'updater.php';

// 	define( 'WP_GITHUB_FORCE_UPDATE', true );

// 	if ( is_admin() ) {

// 		$config = array(
// 			'slug' 				 => plugin_basename(__FILE__),
// 			'proper_folder_name' => 'shopkeeper-extender',
// 			'api_url' 			 => 'https://api.github.com/repos/getbowtied/shopkeeper-extender',
// 			'raw_url' 			 => 'https://raw.github.com/getbowtied/shopkeeper-extender/master',
// 			'github_url' 		 => 'https://github.com/getbowtied/shopkeeper-extender',
// 			'zip_url' 			 => 'https://github.com/getbowtied/shopkeeper-extender/zipball/master',
// 			'sslverify'			 => true,
// 			'requires'			 => '4.9',
// 			'tested'			 => '4.9.7',
// 			'readme'			 => 'README.md',
// 			'access_token'		 => '',
// 		);

// 		new WP_GitHub_Updater( $config );

// 	}
// }

function gbt_mt_gutenberg_blocks() {

	$theme = wp_get_theme();
	if ( $theme->template != 'mrtailor') {
		return;
	}

	if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_mt_wp_version('>=', '5.0') ) {
		include_once 'includes/gbt-blocks/index.php';
	} else {
		add_action( 'admin_notices', 'mt_theme_warning' );
	}
}
add_action( 'init', 'gbt_mt_gutenberg_blocks' );

function mt_theme_warning() {

	echo '<div class="message error woocommerce-admin-notice woocommerce-st-inactive woocommerce-not-configured">';
	echo '<p>Mr. Tailor Extender is enabled but not effective. Please activate Gutenberg plugin in order to work.</p>';
	echo '</div>';
}

function is_mt_wp_version( $operator = '>', $version = '4.0' ) {

	global $wp_version;

	return version_compare( $wp_version, $version, $operator );
}
