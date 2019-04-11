<?php

//Include shortcodes
include_once('wp/socials.php');
include_once('wp/from-the-blog.php');
include_once('wp/from-the-blog-listing.php');
include_once('wp/banner.php');

//Mixed shortcodes
include_once('wp/mixed/blog-posts-mixed.php');

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	
	//Include shortcodes
	include_once('wc/product-categories.php');
	include_once('wc/wc-mod-product.php');
	include_once('wc/add-to-cart.php');

	//Mixed shortcodes
	include_once('wc/mixed/recent-products-mixed.php');
	include_once('wc/mixed/featured-products-mixed.php');
	include_once('wc/mixed/sale-products-mixed.php');
	include_once('wc/mixed/best-selling-products-mixed.php');
	include_once('wc/mixed/top-rated-products-mixed.php');
	include_once('wc/mixed/product-category-mixed.php');
	include_once('wc/mixed/products-mixed.php');
	include_once('wc/mixed/products-by-attribute-mixed.php');
	include_once('wc/mixed/lookbook-mixed.php');

	//Sliders shortcodes
	include_once('wc/sliders/recent-products-slider.php');
	include_once('wc/sliders/featured-products-slider.php');
	include_once('wc/sliders/sale-products-slider.php');
	include_once('wc/sliders/best-selling-products-slider.php');
	include_once('wc/sliders/top-rated-products-slider.php');
	include_once('wc/sliders/product-category-slider.php');
	include_once('wc/sliders/products-slider.php');
	include_once('wc/sliders/products-by-attribute-slider.php');
}

add_action( 'wp_enqueue_scripts', 'getbowtied_mt_shortcodes_styles', 99 );
function getbowtied_mt_shortcodes_styles() {
	wp_register_style('mrtailor-banner-shortcode-styles', plugins_url( 'assets/css/banner.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-from-the-blog-list-shortcode-styles', plugins_url( 'assets/css/from-the-blog-list.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-from-the-blog-slider-shortcode-styles',	plugins_url( 'assets/css/from-the-blog-slider.css', __FILE__ ), NULL );
}

// add_action( 'wp_enqueue_scripts', 'getbowtied_sk_shortcodes_scripts', 99 );
// function getbowtied_sk_shortcodes_scripts() {
// 	wp_register_script('shopkeeper-posts-slider-shortcode-script', 	plugins_url( 'assets/js/posts-slider.js', __FILE__ ), array('jquery') );
// 	wp_register_script('shopkeeper-slider-shortcode-script', 		plugins_url( 'assets/js/slider.js', __FILE__ ), array('jquery') );
// }

// // Add Shortcodes to WP Bakery
if ( defined(  'WPB_VC_VERSION' ) ) {
	add_action( 'init', 'getbowtied_mt_wb_shortcodes' );
	function getbowtied_mt_wb_shortcodes() {
		include_once('wb/blog-posts.php');
		include_once('wb/social-media-profiles.php');
		include_once('wb/banner.php');
		include_once('wb/title.php');
		include_once('wb/output/title.php');

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			include_once('wb/wc-recent-products.php');
			include_once('wb/wc-featured-products.php');
			include_once('wb/wc-products-by-category.php');
			include_once('wb/wc-products-by-attribute.php');
			include_once('wb/wc-product-by-id-sku.php');
			include_once('wb/wc-products-by-ids-skus.php');
			include_once('wb/wc-sale-products.php');
			include_once('wb/wc-top-rated-products.php');
			include_once('wb/wc-best-selling-products.php');
			include_once('wb/wc-add-to-cart-button-custom.php');
			include_once('wb/wc-product-categories.php');
			include_once('wb/wc-product-categories-grid.php');
            include_once('wb/lookbook.php');
		}
	}
}