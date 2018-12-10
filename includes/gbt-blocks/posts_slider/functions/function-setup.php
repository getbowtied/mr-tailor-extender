<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_mt_posts_slider_editor_assets' );
if ( ! function_exists( 'gbt_18_mt_posts_slider_editor_assets' ) ) {
	function gbt_18_mt_posts_slider_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_mt_posts_slider_script',
			plugins_url( 'block.js', dirname(__FILE__) ),
			array( 'wp-api-request', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
		);

		wp_enqueue_style(
			'gbt_18_mt_posts_slider_editor_styles',
			plugins_url( 'assets/css/editor.css', dirname(__FILE__) ),
			array( 'wp-edit-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_mt_posts_slider_assets' );
if ( ! function_exists( 'gbt_18_mt_posts_slider_assets' ) ) {
	function gbt_18_mt_posts_slider_assets() {
		
		wp_enqueue_style(
			'gbt_18_mt_posts_slider_styles',
			plugins_url( 'assets/css/style.css', dirname(__FILE__) ),
			array()
		);
	}
}

//==============================================================================
//	Register Block Type
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
	register_block_type( 'getbowtied/mt-posts-slider', array(
		'attributes'      					=> array(
			'number'						=> array(
				'type'						=> 'number',
				'default'					=> '12',
			),
			'categoriesSavedIDs'			=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'align'							=> array(
				'type'						=> 'string',
				'default'					=> 'center',
			),
			'orderby'						=> array(
				'type'						=> 'string',
				'default'					=> 'date_desc',
			),
			'arrows'						=> array(
				'type'						=> 'boolean',
				'default'					=> true
			),
			'bullets'						=> array(
				'type'						=> 'boolean',
				'default'					=> true
			),
			'fontColor'						=> array(
				'type'						=> 'string',
				'default'					=> '#000'
			),
			'backgroundColor'				=> array(
				'type'						=> 'string',
				'default'					=> '#fff'
			),
		),

		'render_callback' => 'gbt_18_mt_render_frontend_posts_slider',
	) );
}