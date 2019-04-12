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
// require 'core/updater/plugin-update-checker.php';
// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
// 	'https://raw.githubusercontent.com/getbowtied/mr-tailor-extender/master/core/updater/assets/plugin.json',
// 	__FILE__,
// 	'mr-tailor-extender'
// );

if ( ! class_exists( 'MrTailorExtender' ) ) :

	/**
	 * MrTailorExtender class.
	*/
	class MrTailorExtender {

		/**
		 * The single instance of the class.
		 *
		 * @var MrTailorExtender
		*/
		protected static $_instance = null;

		/**
		 * MrTailorExtender constructor.
		 *
		*/
		public function __construct() {

			$theme = wp_get_theme();
			$parent_theme = $theme->parent();

			// Helpers
			include_once( 'includes/helpers/helpers.php' );

			// Vendor
			include_once( 'includes/vendor/enqueue.php' );

			if( ( $theme->template == 'mrtailor' && ( $theme->version >= '2.8.10' || ( !empty($parent_theme) && $parent_theme->version >= '2.8.10' ) ) ) || $theme->template != 'mrtailor' ) {

			// 	// Customizer
			// 	include_once( 'includes/customizer/class/class-control-toggle.php' );

				// Shortcodes
				include_once( 'includes/shortcodes/index.php' );

			// 	// Social Media
			// 	include_once( 'includes/social-media/class-social-media.php' );

			// 	//Widgets
			// 	include_once( 'includes/widgets/social-media.php' );

			// 	// Addons
				if ( $theme->template == 'mrtailor' && is_plugin_active( 'woocommerce/woocommerce.php') ) { 
					include_once( 'includes/addons/class-wc-category-header-image.php' );
				}
			}

			// Gutenberg Blocks
			add_action( 'init', array( $this, 'gbt_mt_gutenberg_blocks' ) );

			// if( $theme->template == 'shopkeeper' && ( $theme->version >= '2.8.1' || ( !empty($parent_theme) && $parent_theme->version >= '2.8.1' ) ) ) {

			// 	// Social Sharing Buttons
			// 	if ( is_plugin_active( 'woocommerce/woocommerce.php') ) { 
			// 		include_once( 'includes/social-sharing/class-social-sharing.php' );
			// 	}
			// }
		}

		/**
		 * Loads Gutenberg blocks
		 *
		 * @return void
		*/
		public function gbt_mt_gutenberg_blocks() {

			if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_mt_wp_version('>=', '5.0') ) {
				include_once 'includes/gbt-blocks/index.php';
			} else {
				add_action( 'admin_notices', 'mt_theme_warning' );
			}
		}

		/**
		 * Ensures only one instance of MrTailorExtender is loaded or can be loaded.
		 *
		 * @return MrTailorExtender
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
endif;

$mrtailor_extender = new MrTailorExtender;