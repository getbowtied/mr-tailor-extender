<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_mt_lookbook_editor_assets' );
if ( ! function_exists( 'gbt_18_mt_lookbook_editor_assets' ) ) {
	function gbt_18_mt_lookbook_editor_assets() {

		wp_enqueue_script(
			'gbt_18_mt_lookbook_script',
			plugins_url( 'block.js', dirname(__FILE__) ),
			array( 'wp-api-request', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
		);

		wp_enqueue_style(
			'gbt_18_mt_lookbook_editor_styles',
			plugins_url( 'assets/css/editor.css', dirname(__FILE__) ),
			array( 'wp-edit-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_mt_lookbook_assets' );
if ( ! function_exists( 'gbt_18_mt_lookbook_assets' ) ) {
	function gbt_18_mt_lookbook_assets() {
		global $theme;

		if( function_exists( 'mt_extender_vendor_scripts' ) ) {
			mt_extender_vendor_scripts();
		}

		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );

		wp_enqueue_style(
			'gbt_18_mt_lookbook_styles',
			plugins_url( 'assets/css/style.css', dirname(__FILE__) ),
			array()
		);

		wp_enqueue_script(
			'gbt_18_mt_lookbook_slider_script',
			plugins_url( 'assets/js/lookbook.js', dirname(__FILE__) ),
			array( 'jquery' )
		);
	}
}

//==============================================================================
//	Register Block Type
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
	register_block_type( 'getbowtied/mt-lookbook', array(
		'attributes'      => array(
			'title'						=> array(
				'type'						=> 'string',
				'default'					=> 'Lookbook Title',
			),
			'subtitle'						=> array(
				'type'						=> 'string',
				'default'					=> 'Lookbook Subitle',
			),
			'titleColor'					=> array(
				'type'						=> 'string',
				'default'					=> '#fff',
			),
			'subtitleColor'					=> array(
				'type'						=> 'string',
				'default'					=> '#fff',
			),
			'productColor'					=> array(
				'type'						=> 'string',
				'default'					=> '#fff',
			),
			'backgroundColor'				=> array(
				'type'						=> 'string',
				'default'					=> '#000',
			),
			'imgURL'						=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'productIDs'					=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'columns'						=> array(
				'type'						=> 'integer',
				'default'					=> '3',
			),
			'height'						=> array(
				'type'						=> 'integer',
				'default'					=> '750',
			),
			'align'							=> array(
				'type'						=> 'string',
				'default'					=> 'center',
			),
		),

		'render_callback' => 'gbt_18_mt_render_frontend_lookbook',
	) );
}
