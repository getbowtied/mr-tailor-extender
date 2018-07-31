<?php

// Shopkeeper Custom Gutenberg Blocks
 
add_filter( 'block_categories', function( $categories, $post ) {
	if ( $post->post_type !== 'post' && $post->post_type !== 'page' && $post->post_type !== 'portfolio' ) {
		return $categories;
	}
	return array_merge(
		array(
			array(
				'slug' => 'mrtailor',
				'title' => __( 'Mr. Tailor', 'gbt-blocks' ),
			),
		),
		$categories
	);
}, 10, 2 );

require_once 'latest_posts_list/index.php';
require_once 'latest_posts_slider/index.php';
require_once 'banner/index.php';
require_once 'portfolio/index.php';
require_once 'social-media-profiles/index.php';