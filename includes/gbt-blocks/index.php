<?php

global $theme;

//==============================================================================
//	Main Editor Styles
//==============================================================================
wp_enqueue_style(
	'getbowtied-mt-product-blocks-editor-styles',
	plugins_url( 'assets/css/editor.css', __FILE__ ),
	array( 'wp-edit-blocks' )
);

//==============================================================================
//	Main JS
//==============================================================================
add_action( 'admin_init', 'getbowtied_mt_product_blocks_scripts' );
if ( ! function_exists( 'getbowtied_mt_product_blocks_scripts' ) ) {
	function getbowtied_mt_product_blocks_scripts() {

		wp_enqueue_script(
			'getbowtied-mt-product-blocks-editor-scripts',
			plugins_url( 'assets/js/main.js', __FILE__ ),
			array( 'wp-blocks', 'jquery' )
		);

	}
}

//==============================================================================
//  Load Swiper
//==============================================================================
if ( $theme->template != 'mrtailor') {
    $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    wp_register_style(
        'getbowtied_swiper_styles',
        plugins_url( 'vendor/swiper/css/swiper'.$suffix.'.css', __FILE__ ),
        array(),
        filemtime(plugin_dir_path( __FILE__ ) . 'vendor/swiper/css/swiper'.$suffix.'.css')
    );
    wp_register_script(
        'getbowtied_swiper_scripts',
        plugins_url( 'vendor/swiper/js/swiper'.$suffix.'.js', __FILE__ ),
        array()
    );
}

//==============================================================================
//  Load Blocks
//==============================================================================

// Mr. Tailor Dependent Blocks
if ( $theme->template == 'mrtailor') {
    include_once 'social_media_profiles/block.php';
    include_once 'portfolio/block.php';
}

// WooCommerce Dependent Blocks
if( is_plugin_active( 'woocommerce/woocommerce.php') ) {
	include_once 'lookbook/block.php';
}

include_once 'posts_grid/block.php';
include_once 'posts_slider/block.php';
include_once 'banner/block.php';