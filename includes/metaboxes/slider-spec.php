<?php

global $slider_metabox;

$slider_metabox = new WPAlchemy_MetaBox(array
(
	'id' => '_slider_metabox',
	'title' => 'Homepage Slider',
	'template' => plugin_dir_path( __FILE__ ) . 'slider-meta.php',
	'include_template' => array(
		plugin_dir_path( dirname(__FILE__) ) . 'templates/page-with-slider.php',
		plugin_dir_path( dirname(__FILE__) ) . 'templates/page-with-slider-full-width.php',
	),
	'priority' => 'high',
	'context' => 'normal'
));

/* eof */