<?php

$theme = wp_get_theme();
if ( $theme->template != 'mrtailor') {

	add_action( 'wp_enqueue_scripts', 'mt_extender_vendor_scripts', 99 );
	function mt_extender_vendor_scripts() {

		wp_register_style(
			'mr_tailor-owl',
			plugins_url( 'owl-carousel/css/owl.carousel.css', __FILE__ ),
			array(),
			filemtime(plugin_dir_path(__FILE__) . 'owl-carousel/css/owl.carousel.css')
		);

		wp_register_script(
			'mr_tailor-owl', 
			plugins_url( 'owl-carousel/js/owl.carousel.min.js', __FILE__ ),
			array('jquery'),  
			TRUE
		);

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_style(
			'mr_tailor_swiper_style',
			plugins_url( 'swiper/css/swiper'.$suffix.'.css', __FILE__ ),
			array(),
			filemtime(plugin_dir_path(__FILE__) . 'swiper/css/swiper'.$suffix.'.css')
		);
		wp_register_script(
			'mr_tailor_swiper_script',
			plugins_url( 'swiper/js/swiper'.$suffix.'.js', __FILE__ ),
			array()
		);
	}
}