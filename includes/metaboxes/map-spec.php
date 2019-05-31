<?php

global $map_metabox;

$map_metabox = new WPAlchemy_MetaBox(array
(
	'id' => '_map_metabox',
	'title' => 'Map',
	'template' => plugin_dir_path( __FILE__ ) . 'map-meta.php',
	'include_template' => plugin_dir_path( dirname(__FILE__) ) . 'templates/page-contact.php',
	'priority' => 'high',
	'context' => 'side'
));

/* eof */