<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://github.com/getbowtied/mr-tailor-extender
 * Description:       		Extends the functionality of Mr. Tailor with Gutenberg elements.
 * Version:           		1.0
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		5.0
 * Tested up to: 			5.0
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

add_action( 'init', 'github_mt_plugin_updater' );
if(!function_exists('github_mt_plugin_updater')) {
	function github_mt_plugin_updater() {

		include_once 'updater.php';

		define( 'WP_GITHUB_FORCE_UPDATE', true );

		if ( is_admin() ) {

			$config = array(
				'slug' 				 => plugin_basename(__FILE__),
				'proper_folder_name' => 'mr-tailor-extender',
				'api_url' 			 => 'https://api.github.com/repos/getbowtied/mr-tailor-extender',
				'raw_url' 			 => 'https://raw.github.com/getbowtied/mr-tailor-extender/master',
				'github_url' 		 => 'https://github.com/getbowtied/mr-tailor-extender',
				'zip_url' 			 => 'https://github.com/getbowtied/mr-tailor-extender/zipball/master',
				'sslverify'			 => true,
				'requires'			 => '5.0',
				'tested'			 => '5.0',
				'readme'			 => 'README.txt',
				'access_token'		 => '',
			);

			new WP_GitHub_Updater( $config );
		}
	}
}

add_action( 'init', 'gbt_mt_gutenberg_blocks' );
if(!function_exists('gbt_mt_gutenberg_blocks')) {
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
}

if( !function_exists('mt_theme_warning') ) {
	function mt_theme_warning() {

		?>

		<div class="message error woocommerce-admin-notice woocommerce-st-inactive woocommerce-not-configured">
			<p>Mr. Tailor Extender plugin couldn't find the Block Editor (Gutenberg) on this site. It requires WordPress 5+ or Gutenberg installed as a plugin.</p>
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