<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';
include_once 'functions/function-helper.php';

//==============================================================================
//	Frontend Output
//==============================================================================
function gbt_18_mt_render_frontend_posts_slider( $attributes ) {

	extract( shortcode_atts( array(
		'number'				=> '12',
		'categoriesSavedIDs'	=> '',
		'align'					=> 'center',
		'orderby'				=> 'date_desc',
		'arrows'				=> true,
		'bullets' 				=> true,
		'fontColor'				=> '#000',
		'backgroundColor'		=> '#fff'
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

    	<div class="gbt_18_mt_posts_slider <?php echo $align; ?>">
    
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
			              
			        </div>
				</div>
		    </div>
		</div>

	<?php endif; ?> 
	
	<?php

	wp_reset_query();

	return ob_get_clean();
}