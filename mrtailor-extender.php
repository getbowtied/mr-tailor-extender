<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://mrtailor.wp-theme.design/
 * Description:       		Extends the functionality of Mr. Tailor with theme specific features.
 * Version:           		1.3.6
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		5.0
 * Tested up to: 			5.3
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
			include_once( dirname( __FILE__ ) . '/includes/helpers/helpers.php' );

			// Vendor
			include_once( dirname( __FILE__ ) . '/includes/vendor/enqueue.php' );

			if( ( $theme->template == 'mrtailor' && ( $theme->version >= '2.9' || ( !empty($parent_theme) && $parent_theme->version >= '2.9' ) ) ) || $theme->template != 'mrtailor' ) {

				// Customizer
				include_once( dirname( __FILE__ ) . '/includes/customizer/class/class-control-toggle.php' );

				// Shortcodes
				include_once( dirname( __FILE__ ) . '/includes/shortcodes/index.php' );

				// Social Media
				include_once( dirname( __FILE__ ) . '/includes/social-media/class-social-media.php' );

				// Addons
				if ( $theme->template == 'mrtailor' && is_plugin_active( 'woocommerce/woocommerce.php') ) {
					include_once( dirname( __FILE__ ) . '/includes/addons/class-wc-category-header-image.php' );
				}
			}

			// Gutenberg Blocks
			add_action( 'init', array( $this, 'gbt_mt_gutenberg_blocks' ) );

			if( $theme->template == 'mrtailor' && ( $theme->version >= '2.9' || ( !empty($parent_theme) && $parent_theme->version >= '2.9' ) ) ) {

				// Social Sharing Buttons
				if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
					include_once( dirname( __FILE__ ) . '/includes/social-sharing/class-social-sharing.php' );
				}

				// Custom Code
				include_once( dirname( __FILE__ ) . '/includes/custom-code/class-custom-code.php' );

				// VC Templates
				add_action( 'plugins_loaded', function() {

					if ( defined(  'WPB_VC_VERSION' ) ) {

						// Modify and remove existing shortcodes from VC
						include_once( dirname( __FILE__ ) . '/includes/wpbakery/custom_vc.php' );

						// VC Templates
						$vc_templates_dir = dirname(__FILE__) . '/includes/wpbakery/vc_templates/';
						vc_set_shortcodes_templates_dir($vc_templates_dir);
					}
				});
			}

			if( $theme->template == 'mrtailor' && ( $theme->version >= '2.9' || ( !empty($parent_theme) && $parent_theme->version >= '2.9' ) ) ) {

				//Custom Menu
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom-menu.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/edit_custom_walker.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom_walker.php' );
			}

			add_action('mrtailor_header_start', array($this, 'mrtailor_header_code'));
			add_action('mrtailor_footer_action', array($this, 'mrtailor_footer_code'));
		}

		/**
		 * Output header custom code
		 *
		 * @return void
		 */
		public function mrtailor_header_code() {
			echo get_option( 'mt_custom_code_header_js', '' );
		}

		/**
		 * Output footer custom code
		 *
		 * @return void
		 */
		public function mrtailor_footer_code() {
			echo get_option( 'mt_custom_code_footer_js', '' );
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
