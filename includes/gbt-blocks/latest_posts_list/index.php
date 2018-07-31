<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_mt_latest_posts_list_editor_assets' );

if ( ! function_exists( 'getbowtied_mt_latest_posts_list_editor_assets' ) ) {
	function getbowtied_mt_latest_posts_list_editor_assets() {
		
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

add_action( 'enqueue_block_assets', 'getbowtied_mt_latest_posts_list_assets' );

if ( ! function_exists( 'getbowtied_mt_latest_posts_list_assets' ) ) {
	function getbowtied_mt_latest_posts_list_assets() {
		
		wp_enqueue_style(
			'getbowtied-latest-posts-grid-css',
			plugins_url( 'css/style.css', __FILE__ ),
			array()
		);
	}
}

register_block_type( 'getbowtied/mt-latest-posts-list', array(
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
	),

	'render_callback' => 'getbowtied_mt_render_frontend_latest_posts_list',
) );

function getbowtied_mt_render_frontend_latest_posts_list( $attributes ) {

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories',
		'align'		=> 'center'
	), $attributes ) );

	ob_start();
	?> 

	<div class="wp-block-gbt-posts-grid <?php echo $align; ?>">
    
	    <div class="row">
	        <div class="blog-list-wrapper">

	            <?php 

	            $args = array(
	                'post_status' => 'publish',
	                'post_type' => 'post',
	                'category_name' => $category,
	                'posts_per_page' => $number
	            );

	            $recentPosts = get_posts( $args );

	            if ( !empty($recentPosts) ) :
	
					foreach($recentPosts as $post) : ?>

	                    <?php $post_format = get_post_format($post->ID); ?>
	            
	                    <?php 
	                    $bg_style = "";
	                    if ( has_post_thumbnail($post->ID)) :
	                        $image_id = get_post_thumbnail_id( $post->ID );
	                        $image_url = wp_get_attachment_image_src( $image_id, 'large' );
	                        $bg_style = 'background-image: url(' . $image_url[0] . ')';
	                    endif;
	                    ?>
						
						<div class="blog-list-item">	
							<a class="blog_list_img_link" href="<?php get_permalink($post->ID); ?>">
							
								<span class="blog_list_overlay"></span>
							
								<span class="blog_list_img" style="<?php echo $bg_style; ?>"></span>
								
								<span class="blog-list-content-wrapper">
									<span class="blog-list-content-inner">
										
										<span class="blog-list-day"><?php echo date('d', strtotime($post->post_date)); ?></span>
										
										<span class="blog-list-content">
											<span class="blog-list-date"><?php echo date('F Y', strtotime($post->post_date)); ?></span>
											<h3 class="blog-list-title"><?php echo $post->post_title; ?></h3>
										</span><!--.blog-list-content-->
										
									</span><!--blog-list-content-inner-->
								</span><!--.blog-list-content-wrapper-->
								
							</a>
						</div><!--.blog-list-item-->
						
	                <?php endforeach; // end of the loop. ?>

	            <?php endif; ?>

	        </div>
	    </div>

	</div>
	
	<?php

	wp_reset_query();

	return ob_get_clean();

}

add_action('wp_ajax_getbowtied_mt_render_backend_latest_posts_grid', 'getbowtied_mt_render_backend_latest_posts_grid');
function getbowtied_mt_render_backend_latest_posts_grid() {

	$attributes = $_POST['attributes'];
	$output = '';
	$counter = 0;

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories'
	), $attributes ) );

	$output = 'el( "div", { key: "wp-block-gbt-posts-grid", className: "wp-block-gbt-posts-grid"},';

		$output .= 'el( "div", { key: "blog-list-wrapper", className: "blog-list-wrapper"},';

			$args = array(
	            'post_status' 		=> 'publish',
	            'post_type' 		=> 'post',
	            'category' 			=> $category,
	            'posts_per_page' 	=> $number
	        );
	        
	        $recentPosts = get_posts( $args );

	        if ( !empty($recentPosts) ) :
	                    
	            foreach($recentPosts as $post) :

	            	$bg_style = "";
                    if ( has_post_thumbnail($post->ID)) :
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'large', true);
                        $bg_style = 'backgroundImage: "url(' . $image_url[0] . ')"';
                    endif;
	        
	                $output .= 'el( "div", { key: "blog-list-item-' . $counter . '", className: "blog-list-item" },';

	                	$output .= 'el( "a", { key: "blog_list_img_link-' . $counter . '", className: "blog_list_img_link" },';

	                		$output .= 'el( "span", { key: "blog_list_overlay-' . $counter . '", className: "blog_list_overlay"} ),';

	                		$output .= 'el( "span", { key: "blog_list_img-' . $counter . '", className: "blog_list_img", style: {'.$bg_style.'} } ),';

	                		$output .= 'el( "span", { key: "blog-list-content-wrapper_' . $counter . '", className: "blog-list-content-wrapper"},';
	                		
	                			$output .= 'el( "span", { key: "blog-list-content-inner_' . $counter . '", className: "blog-list-content-inner" },';

	                				$output .= 'el( "span", { key: "blog-list-day_' . $counter . '", className: "blog-list-day" }, "'. date('d', strtotime($post->post_date)) .'" ),';

	                				$output .= 'el( "span", { key: "blog-list-content_' . $counter . '", className: "blog-list-content" },';

	                					$output .= 'el( "span", { key: "blog-list-date_' . $counter . '", className: "blog-list-date" }, "'. date('F Y', strtotime($post->post_date)) .'" ),';
	                					$output .= 'el( "h3", { key: "blog-list-title_' . $counter . '", className: "blog-list-title" }, "'. $post->post_title .'" ),';

                					$output .= '),';

								$output .= '),';

	                		$output .= '),';

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
