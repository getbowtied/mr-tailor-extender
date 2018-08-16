<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_mt_latest_posts_slider_editor_assets' );

if ( ! function_exists( 'getbowtied_mt_latest_posts_slider_editor_assets' ) ) {
	function getbowtied_mt_latest_posts_slider_editor_assets() {
		
		wp_enqueue_script(
			'getbowtied-latest-posts-slider',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'getbowtied-latest-posts-slider-editor-css',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

add_action( 'enqueue_block_assets', 'getbowtied_mt_latest_posts_slider_assets' );

if ( ! function_exists( 'getbowtied_mt_latest_posts_slider_assets' ) ) {
	function getbowtied_mt_latest_posts_slider_assets() {
		
		wp_enqueue_style(
			'getbowtied-latest-posts-slider-css',
			plugins_url( 'css/style.css', __FILE__ ),
			array()
		);
	}
}

register_block_type( 'getbowtied/mt-latest-posts-slider', array(
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

	'render_callback' => 'getbowtied_mt_render_frontend_latest_posts_slider',
) );

function getbowtied_mt_render_frontend_latest_posts_slider( $attributes ) {

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories',
		'align'		=> 'center'
	), $attributes ) );

	$sliderrandomid = rand();

	ob_start();
	?> 

	<div class="wp-block-gbt-posts-slider <?php echo $align; ?>">
    
	    <script>
		jQuery(document).ready(function($) {
			$("#from-the-blog-<?php echo $sliderrandomid ?>").owlCarousel({
				items:3,
				itemsDesktop : [1200,3],
				itemsDesktopSmall : [1000,2],
				itemsTablet: false,
				itemsMobile : [600,1],
				lazyLoad : true,
			});
		});
		</script>
	    
		<div class="row">
		    <div class="from-the-blog-wrapper">
			
		        <div id="from-the-blog-<?php echo $sliderrandomid ?>" class="owl-carousel">
							
					<?php
		    
		            $args = array(
		                'post_status' => 'publish',
		                'post_type' => 'post',
		                'category' => $category,
		                'posts_per_page' => $number
		            );
		            
		            $recentPosts = get_posts( $args );
		            
		            if ( !empty($recentPosts) ) :
	
						foreach($recentPosts as $post) : ?>
		            
		                    <?php $post_format = get_post_format($post->ID); ?>
		                    
		                    <div class="from_the_blog_item <?php echo $post_format ? $post_format: 'standard'; ?> <?php if ( !has_post_thumbnail($post->ID)) : ?>no_thumb<?php endif; ?>">
		                        
								<a class="from_the_blog_img_link" href="<?php echo get_permalink($post->ID) ?>">
									<span class="from_the_blog_overlay"></span>
									
									<?php if ( has_post_thumbnail($post->ID)) :
										$image_id = get_post_thumbnail_id($post->ID);
										$image_url = wp_get_attachment_image_src($image_id,'large', true);
									?>
										<span class="from_the_blog_img" style="background-image: url(<?php echo $image_url[0]; ?> );"></span>
										<span class="with_thumb_icon"></span>
									<?php else : ?>
										<span class="from_the_blog_noimg"></span>
										<span class="no_thumb_icon"></span>
									<?php endif;  ?>
									
								</a>
		                        
		                        <div class="from_the_blog_content">
	                            	<h3><a class="from_the_blog_title" href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h3>
	                            	<div class="post_header_date"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo date('F d, Y', strtotime($post->post_date)); ?></a></div>                       
		                        </div>
		                        
		                    </div>
		        
		                <?php endforeach; ?>
		                
		            <?php endif; ?> 
		              
		        </div>
			</div>
	    </div>

	</div>
	
	<?php

	wp_reset_query();

	return ob_get_clean();

}

add_action('wp_ajax_getbowtied_mt_render_backend_latest_posts_slider', 'getbowtied_mt_render_backend_latest_posts_slider');
function getbowtied_mt_render_backend_latest_posts_slider() {

	$attributes = $_POST['attributes'];
	$output = '';
	$counter = 0;
	$sliderrandomid = rand();

	extract( shortcode_atts( array(
		'number'	=> '12',
		'category'	=> 'All Categories'
	), $attributes ) ); ?>

	<?php

	$output = 'el( "div", { key: "wp-block-gbt-posts-slider", className: "wp-block-gbt-posts-slider"},';

		$output .= 'el( "div", { key: "from-the-blog-wrapper", className: "from-the-blog-wrapper"},';

			$output .= 'el( "div", { key: "from-the-blog-'.$sliderrandomid.'", className: "owl-carousel from-the-blog-'.$sliderrandomid.'"},';

				$args = array(
		            'post_status' 		=> 'publish',
		            'post_type' 		=> 'post',
		            'category' 			=> $category,
		            'posts_per_page' 	=> '3'
		        );
		        
		        $recentPosts = get_posts( $args );

		        if ( !empty($recentPosts) ) :
		                    
		            foreach($recentPosts as $post) :
		        
		                $output .= 'el( "div", { key: "from_the_blog_item_' . $counter . '", className: "from_the_blog_item" },';

		                	$output .= 'el( "a", { key: "from_the_blog_img_link_' . $counter . '", className: "from_the_blog_img_link" },';

		                		$output .= 'el( "span", { key: "from_the_blog_overlay_' . $counter . '", className: "from_the_blog_overlay" }, ),';

		                		if ( has_post_thumbnail($post->ID)) :
									$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'large', true);

									$output .= 'el( "span", { key: "from_the_blog_img_' . $counter . '", className: "from_the_blog_img", style: { backgroundImage: "url('.$image_url[0].')" } } ),';
								
								else : 

									$output .= 'el( "span", { key: "from_the_blog_noimg_' . $counter . '", className: "from_the_blog_noimg" } ),';

								endif;

							$output .= '),';


							$output .= 'el( "div", { key: "from_the_blog_content_' . $counter . '", className: "from_the_blog_content" },';

								$output .= 'el( "h3", { key: "from_the_blog_content_h3_' . $counter . '" },';
									$output .= 'el( "a", { key: "from_the_blog_title_' . $counter . '", className: "from_the_blog_title" }, "'.$post->post_title.'" )';
								$output .= '),';
								$output .= 'el( "div", { key: "post_header_date_' . $counter . '", className: "post_header_date" }, "'.date('F d, Y', strtotime($post->post_date)).'" )';

							$output .= '),';

		            	$output .= '),'; 

						$counter++;

					endforeach; 

		        endif;

		        $output .= 'el( "div", { key: "owl-controls", className: "owl-controls clickable" },';
		        	$output .= 'el( "div", { key: "owl-pagination", className: "owl-pagination" },';
		        		$output .= 'el( "div", { key: "owl-page-1", className: "owl-page active" },';
		        			$output .= 'el( "span", { key: "owl-page-1-span" })';
		        		$output .= '),';	
						$output .= 'el( "div", { key: "owl-page-2", className: "owl-page" },';
		        			$output .= 'el( "span", { key: "owl-page-2-span" })';
		        		$output .= '),';
		        	$output .= '),';
		        $output .= '),';

		    $output .= ')';

		$output .= ')';

	$output .= ')';

	echo json_encode($output);
	exit;
}
