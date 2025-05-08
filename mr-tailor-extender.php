<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://mrtailor.wp-theme.design/
 * Description:       		Extends the functionality of Mr. Tailor with theme specific features.
 * Version:           		3.3
 * Author:            		Get Bowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		6.0
 * Tested up to: 			6.8
 * Text Domain:             mrtailor-extender
 *
 * @package  Mr. Tailor Extender
 * @author   Get Bowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/mr-tailor-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'mr-tailor-extender'
);

if ( ! class_exists( 'MrTailorExtender' ) ) :

	class MrTailorExtender {

		private static $instance = null;
		private static $initialized = false;
		private $theme_slug;

		private function __construct() {
			// Empty constructor - initialization happens in init_instance
		}

		private function init_instance() {
			if (self::$initialized) {
				return;
			}

			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			
			$version = ( isset(get_plugin_data( __FILE__ )['Version']) && !empty(get_plugin_data( __FILE__ )['Version']) ) ? get_plugin_data( __FILE__ )['Version'] : '1.0';
			define ( 'MT_EXT_VERSION', $version );

			$this->theme_slug = 'mrtailor';

			// Helpers
			include_once( dirname( __FILE__ ) . '/includes/helpers/helpers.php' );

			// Vendor
			include_once( dirname( __FILE__ ) . '/includes/vendor/enqueue.php' );

            // Customizer
			include_once( dirname( __FILE__ ) . '/includes/customizer/repeater/class-mt-ext-repeater-control.php' );

			// Shortcodes
			include_once( dirname( __FILE__ ) . '/includes/shortcodes/index.php' );

			// Social Media
			include_once( dirname( __FILE__ ) . '/includes/social-media/class-social-media.php' );

			// Gutenberg Blocks
            include_once( dirname( __FILE__ ) . '/includes/gbt-blocks/index.php' );

            // Mr. Tailor Dependent Components
			if( function_exists('mrtailor_theme_version') ) {

                // Addons
    			if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
    				include_once( dirname( __FILE__ ) . '/includes/addons/class-wc-category-header-image.php' );
    			}

				// Social Sharing Buttons
				if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
					include_once( dirname( __FILE__ ) . '/includes/social-sharing/class-social-sharing.php' );
				}

				// Custom Code
				include_once( dirname( __FILE__ ) . '/includes/custom-code/class-custom-code.php' );

				// VC Templates
				if ( defined(  'WPB_VC_VERSION' ) ) {

					// Modify and remove existing shortcodes from VC
					include_once( dirname( __FILE__ ) . '/includes/wpbakery/custom_vc.php' );

					// VC Templates
					$vc_templates_dir = dirname(__FILE__) . '/includes/wpbakery/vc_templates/';
					vc_set_shortcodes_templates_dir($vc_templates_dir);
				}

                // Custom Menu
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom-menu.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/edit_custom_walker.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom_walker.php' );
			}

			if ( is_admin() ) {
				global $gbt_dashboard_params;
				$gbt_dashboard_params = array(
					'gbt_theme_slug' => $this->theme_slug,
				);
				include_once( dirname( __FILE__ ) . '/dashboard/index.php' );
			}

			self::$initialized = true;
		}

		public static function get_instance() {
			return self::init();
		}

		public static function init() {
			if (self::$instance === null) {
				self::$instance = new self();
				self::$instance->init_instance();
			}
			return self::$instance;
		}

		private function __clone() {}
		
		public function __wakeup() {
			throw new Exception("Cannot unserialize singleton");
		}
	}
endif;

add_action( 'after_setup_theme', function() {
	MrTailorExtender::init();
} );
