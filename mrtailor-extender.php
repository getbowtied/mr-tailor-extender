<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://mrtailor.wp-theme.design/
 * Description:       		Extends the functionality of Mr. Tailor with theme specific features.
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
		 * Page Templates
		 *
		 * @var array
		*/
		protected $templates = array();

		/**
		 * MrTailorExtender constructor.
		 *
		*/
		public function __construct() {

			$theme = wp_get_theme();
			$parent_theme = $theme->parent();

			$this->templates = array(
				plugin_dir_path(__FILE__) . 'includes/templates/page-with-slider.php' => 'Page With Slider',
				plugin_dir_path(__FILE__) . 'includes/templates/page-contact.php' => 'Contact Page',
				plugin_dir_path(__FILE__) . 'includes/templates/page-with-slider-full-width.php' => 'Page With Slider Full Width',
			);

			// Helpers
			include_once( 'includes/helpers/helpers.php' );

			// Vendor
			include_once( 'includes/vendor/enqueue.php' );

			if( ( $theme->template == 'mrtailor' && ( $theme->version >= '2.9' || ( !empty($parent_theme) && $parent_theme->version >= '2.9' ) ) ) || $theme->template != 'mrtailor' ) {

				// Customizer
				include_once( 'includes/customizer/class/class-control-toggle.php' );

				// Shortcodes
				include_once( 'includes/shortcodes/index.php' );

				// Social Media
				include_once( 'includes/social-media/class-social-media.php' );

				// Addons
				if ( $theme->template == 'mrtailor' && is_plugin_active( 'woocommerce/woocommerce.php') ) { 
					include_once( 'includes/addons/class-wc-category-header-image.php' );
				}
			}

			// Gutenberg Blocks
			add_action( 'init', array( $this, 'gbt_mt_gutenberg_blocks' ) );

			if( $theme->template == 'mrtailor' && ( $theme->version >= '2.9' || ( !empty($parent_theme) && $parent_theme->version >= '2.9' ) ) ) {

				// Social Sharing Buttons
				if ( is_plugin_active( 'woocommerce/woocommerce.php') ) { 
					include_once( 'includes/social-sharing/class-social-sharing.php' );
				}

				// Custom Code
				include_once( 'includes/custom-code/class-custom-code.php' );

				// Metaboxes
				include_once( 'includes/vendor/wpalchemy/MediaAccess-mod.php' );
				include_once( 'includes/vendor/wpalchemy/MetaBox-mod.php' );

				include_once 'includes/metaboxes/slider-spec.php';
				include_once 'includes/metaboxes/map-spec.php';

				add_filter( 'theme_page_templates', array( $this, 'gbt_mt_page_templates' ), 99 );
				add_filter( 'wp_insert_post_data', array( $this, 'gbt_mt_register_project_templates' ) );
				add_filter( 'template_include', array( $this, 'gbt_mt_view_project_template') );

				include_once 'includes/metaboxes/custom_styles.php';
			}
		}

		/**
		 * Checks if the template is assigned to the page
		 */
		public function gbt_mt_view_project_template( $template ) {
		
			global $post;

			if ( ! $post ) {
				return $template;
			}

			if ( ! isset( $this->templates[get_post_meta( 
				$post->ID, '_wp_page_template', true 
			)] ) ) {
				return $template;
			} 

			$file = get_post_meta( 
				$post->ID, '_wp_page_template', true
			);

			if ( file_exists( $file ) ) {
				return $file;
			} else {
				echo $file;
			}

			return $template;
		}

		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 */
		public function gbt_mt_register_project_templates( $atts ) {

			$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

			$templates = wp_get_theme()->get_page_templates();
			if ( empty( $templates ) ) {
				$templates = array();
			} 

			wp_cache_delete( $cache_key , 'themes');

			$templates = array_merge( $templates, $this->templates );

			wp_cache_add( $cache_key, $templates, 'themes', 1800 );

			return $atts;
		} 

		/**
		 * Page Templates
		 *
		 * @return void
		*/
		public function gbt_mt_page_templates( $posts_templates ) {

			$posts_templates = array_merge( $posts_templates, $this->templates );

			return $posts_templates;
			
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
