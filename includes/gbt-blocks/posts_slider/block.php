<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';
include_once 'functions/function-helper.php';

//==============================================================================
//	Frontend Output
//==============================================================================
if ( ! function_exists( 'gbt_18_mt_render_frontend_posts_slider' ) ) {
	function gbt_18_mt_render_frontend_posts_slider( $attributes ) {

		extract( shortcode_atts( array(
			'number'				=> '12',
			'categoriesSavedIDs'	=> '',
			'align'					=> 'center',
			'orderby'				=> 'date_desc',
			'columns' 				=> '3',
		), $attributes ) );

		$args = array(
	        'post_status' 		=> 'publish',
	        'post_type' 		=> 'post',
	        'posts_per_page' 	=> $number
	    );

	    switch ( $orderby ) {
	    	case 'date_asc' :
				$args['orderby'] = 'date';
				$args['order']	 = 'asc';
				break;
			case 'date_desc' :
				$args['orderby'] = 'date';
				$args['order']	 = 'desc';
				break;
			case 'title_asc' :
				$args['orderby'] = 'title';
				$args['order']	 = 'asc';
				break;
			case 'title_desc':
				$args['orderby'] = 'title';
				$args['order']	 = 'desc';
				break;
			default: break;
		}

	    if( substr($categoriesSavedIDs, - 1) == ',' ) {
			$categoriesSavedIDs = substr( $categoriesSavedIDs, 0, -1);
		}

		if( substr($categoriesSavedIDs, 0, 1) == ',' ) {
			$categoriesSavedIDs = substr( $categoriesSavedIDs, 1);
		}

	    if( $categoriesSavedIDs != '' ) $args['category'] = $categoriesSavedIDs;
	    
	    $recentPosts = get_posts( $args );

		ob_start();
		        
	    if ( !empty($recentPosts) ) : ?>

	    	<div class="gbt_18_mt_posts_slider align<?php echo $align; ?>">
		    
			    <div class="swiper-container columns-<?php echo $columns; ?>">
				
			        <div class="swiper-wrapper">
								
						<?php
		
						foreach($recentPosts as $post) : ?>
			            		                    
		                    <div class="swiper-slide <?php if ( !has_post_thumbnail($post->ID)) : ?>no_thumb<?php endif; ?>">
		                        
								<a class="gbt_18_mt_posts_slider_link" href="<?php echo get_permalink($post->ID) ?>">
									<span class="gbt_18_mt_posts_slider_overlay"></span>
									
									<?php if ( has_post_thumbnail($post->ID)) :
										$image_id = get_post_thumbnail_id($post->ID);
										$image_url = wp_get_attachment_image_src($image_id,'large', true);
									?>
										<span class="gbt_18_mt_posts_slider_img" style="background-image: url(<?php echo $image_url[0]; ?> );"></span>
									<?php else : ?>
										<span class="gbt_18_mt_posts_slider_noimg"></span>
									<?php endif;  ?>
									
								</a>
		                        
		                        <div class="gbt_18_mt_posts_slider_content">
	                            	<h4 class="gbt_18_mt_posts_slider_title"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h4>
	                            	<div class="gbt_18_mt_posts_slider_date"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo date('F d, Y', strtotime($post->post_date)); ?></a></div>                       
		                        </div>
		                        
		                    </div>
		        
		                <?php endforeach; ?>
			              
			        </div>

			        <div class="swiper-pagination"></div>
					
				</div>

				<span class="swiper-button-prev"></span>
	        	<span class="swiper-button-next"></span>

			</div>

		<?php endif; ?> 
		
		<?php

		wp_reset_query();

		return ob_get_clean();
	}
}