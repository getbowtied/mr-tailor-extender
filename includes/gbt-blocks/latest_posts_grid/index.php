<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_latest_posts_editor_assets' );

if ( ! function_exists( 'getbowtied_latest_posts_editor_assets' ) ) {
	function getbowtied_latest_posts_editor_assets() {
		
		wp_enqueue_script(
			'getbowtied-latest-posts',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'getbowtied-latest-posts-grid-editor-css',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

add_action( 'enqueue_block_assets', 'getbowtied_latest_posts_assets' );

if ( ! function_exists( 'getbowtied_latest_posts_assets' ) ) {
	function getbowtied_latest_posts_assets() {
		
		wp_enqueue_style(
			'getbowtied-latest-posts-grid-css',
			plugins_url( 'css/style.css', __FILE__ ),
			array()
		);
	}
}

register_block_type( 'getbowtied/latest-posts-grid', array(
	'attributes'      					=> array(
		'number'						=> array(
			'type'						=> 'number',
			'default'					=> '12',
		),
		'category'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'align'							=> array(
			'type'						=> 'string',
			'default'					=> 'center',
		),
		'columns'						=> array(
			'type'						=> 'number',
			'default'					=> '3'
		),
	),

	'render_callback' => 'getbowtied_render_frontend_latest_posts_grid',
) );

function getbowtied_render_frontend_latest_posts_grid( $attributes ) {

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories',
		'align'		=> 'center',
		'columns'	=> '3'
	), $attributes ) );

	ob_start();
	?> 

	<div class="wp-block-gbt-posts-grid">
    
	    <div class="latest_posts_grid_wrapper columns-<?php echo $columns; ?> <?php echo $align; ?>">
							
			<?php

	        $args = array(
	            'post_status' 		=> 'publish',
	            'post_type' 		=> 'post',
	            'category' 			=> $category,
	            'posts_per_page' 	=> $number
	        );
	        
	        $recentPosts = get_posts( $args );
	        
	        if ( !empty($recentPosts) ) : ?>
	                    
	            <?php foreach($recentPosts as $post) : ?>
	        
	                <?php $post_format = get_post_format($post->ID); ?>

	                <div class="latest_posts_grid_item <?php echo $post_format ? $post_format: 'standard'; ?> <?php if ( !has_post_thumbnail($post->ID)) : ?>no_thumb<?php endif; ?>">
	                    
						<a class="latest_posts_grid_link" href="<?php echo get_post_permalink($post->ID); ?>">
							<span class="latest_posts_grid_img_container">
								<span class="latest_posts_grid_overlay"></span>
								
								<?php if ( has_post_thumbnail($post->ID)) :
									$image_id = get_post_thumbnail_id($post->ID);
									$image_url = wp_get_attachment_image_src($image_id,'large', true);
								?>
									<span class="latest_posts_grid_img" style="background-image: url(<?php echo esc_url($image_url[0]); ?> );"></span>
								<?php else : ?>
									<span class="latest_posts_grid_noimg"></span>
								<?php endif;  ?>

							</span><!--.from_the_blog_img_container-->
							<span class="latest_posts_grid_title" href="<?php echo get_post_permalink($post->ID); ?>"><?php echo $post->post_title; ?></span>
						</a>
	                    
	                </div>
	    
	            <?php endforeach; // end of the loop. ?>
	            
	        <?php

	        endif;
	        
	        ?> 

		</div>

	</div>
	
	<?php

	wp_reset_query();

	return ob_get_clean();

}

add_action('wp_ajax_getbowtied_render_backend_latest_posts_grid', 'getbowtied_render_backend_latest_posts_grid');
function getbowtied_render_backend_latest_posts_grid() {

	$attributes = $_POST['attributes'];
	$output = '';
	$counter = 0;

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories',
		'columns'	=> '3'
	), $attributes ) );

	$output = 'el( "div", { key: "wp-block-gbt-posts-grid", className: "wp-block-gbt-posts-grid"},';

		$output .= 'el( "div", { key: "latest_posts_grid_wrapper", className: "latest_posts_grid_wrapper columns-' . $columns . '"},';

			$args = array(
	            'post_status' 		=> 'publish',
	            'post_type' 		=> 'post',
	            'category' 			=> $category,
	            'posts_per_page' 	=> $number
	        );
	        
	        $recentPosts = get_posts( $args );

	        if ( !empty($recentPosts) ) :
	                    
	            foreach($recentPosts as $post) :
	        
	                $output .= 'el( "div", { key: "latest_posts_grid_item_' . $counter . '", className: "latest_posts_grid_item" },';

	                	$output .= 'el( "a", { key: "latest_posts_grid_link_' . $counter . '", className: "latest_posts_grid_link" },';

	                		$output .= 'el( "span", { key: "latest_posts_grid_img_container_' . $counter . '", className: "latest_posts_grid_img_container"},';
	                		
	                			$output .= 'el( "span", { key: "latest_posts_grid_overlay_' . $counter . '", className: "latest_posts_grid_overlay" }, ),';

	                			if ( has_post_thumbnail($post->ID)) :
	                				$image_id = get_post_thumbnail_id($post->ID);
									$image_url = wp_get_attachment_image_src($image_id,'large', true);

									$output .= 'el( "span", { key: "latest_posts_grid_img_' . $counter . '", className: "latest_posts_grid_img", style: { backgroundImage: "url(' . esc_url($image_url[0]) . ')" } } )';

								else :

									$output .= 'el( "span", { key: "latest_posts_grid_noimg_' . $counter . '", className: "latest_posts_grid_noimg"} )';

								endif;

	                		$output .= '),';

							$output .= 'el( "span", { key: "latest_posts_grid_title_' . $counter . '", className: "latest_posts_grid_title"}, "' . $post->post_title . '" )';

	                	$output .= '),';

	            	$output .= '),';

					$counter++;

				endforeach; 

	        endif;

		$output .= ')';

	$output .= ')';

	echo json_encode($output);
	exit;
}
