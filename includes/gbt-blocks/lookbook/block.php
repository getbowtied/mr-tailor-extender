<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';

//==============================================================================
//  Frontend Output
//==============================================================================
if ( ! function_exists( 'gbt_18_mt_render_frontend_lookbook' ) ) {
	function gbt_18_mt_render_frontend_lookbook( $attributes ) {
		
		$sliderrandomid = rand();
		
		extract(shortcode_atts(array(
			"title" 			=> 'Lookbook Title',
			"subtitle" 			=> 'Lookbook Subtitle',
			"productIDs" 		=> '',
			"imgURL"			=> '',
			"columns" 			=> '3',
			"titleColor"		=> '#fff',
			"subtitleColor"		=> '#fff',
			"productColor"		=> '#fff',
			"backgroundColor"	=> '#000',
			"height"			=> '750',
			"align"				=> 'center'
		), $attributes));

		ob_start();
		?>

		<div class="gbt_18_mt_lookbook align<?php echo $align; ?>">
			    		        
	        <div class="swiper-container column-<?php echo $columns; ?>" style="height: <?php echo $height; ?>px">
	            
	            <div class="swiper-wrapper">
	                
	                <?php

	                $args = array(
	                    'include'	=> explode(',',$productIDs),
	                    'limit'		=> -1,			
	                );

	                $products = wc_get_products( $args );

	                $sorted = [];
				    foreach ( explode(',',$productIDs) as $id) {
				        foreach ($products as $unsorted) {
				            if ($unsorted->get_id() == $id) {
				                $sorted[] = $unsorted;
				                break;
				            }
				        }
				    }
				    if (sizeof($sorted) == sizeof($products)) {
				        $products= $sorted;
				    }

	                ?>

						<?php
						$first_slide_style = 'background-color:'.$backgroundColor.';';      
						
						if ( $imgURL != '' ) {
							$bg_image = wp_get_attachment_url($imgURL);          
							$first_slide_style .= 'background-image:url(' . $imgURL . ');';
						}
						?>

						<div class="swiper-slide first" style="<?php echo $first_slide_style; ?>">
							<div class="gbt_18_mt_lookbook_first_slide">
								<h2 class="gbt_18_mt_lookbook_first_slide_title" style="color:<?php echo $titleColor; ?>"><?php echo $title; ?></h2>
								<h3 class="gbt_18_mt_lookbook_first_slide_subtitle" style="color:<?php echo $subtitleColor; ?>"><?php echo $subtitle; ?></h3>
							</div>
						</div>
	
						<?php foreach( $products as $product ) : ?>

						<?php

						$product_thumbnail = wp_get_attachment_image_src( $product->get_image_id(), 'large' );				

						$style = '';      
						
						if (isset($product_thumbnail[0])) {            
							$style = 'background-image:url(' . $product_thumbnail[0] . ')';
						}

						?>

							<div class="swiper-slide" style="height: <?php echo $height; ?>px">								
								<div class="gbt_18_mt_lookbook_product">
									<a href="<?php echo get_permalink($product->get_id()); ?>" class="gbt_18_mt_lookbook_product_wrapper" style="<?php echo $style; ?>"></a>
									<div class="gbt_18_mt_lookbook_product_infos">										
										<h4 class="gbt_18_mt_lookbook_product_price" style="color:<?php echo $productColor; ?>"><?php echo $product->get_price_html(); ?></h4>
										<h3 class="gbt_18_mt_lookbook_product_title" style="color:<?php echo $productColor; ?>"><?php echo $product->get_name(); ?></h3>
									</div>
									<a href="<?php echo get_permalink($product->get_id()); ?>" class="gbt_18_mt_lookbook_product_overlay"></a>
								</div>
							</div>
								
						<?php endforeach; ?>

	            </div>
				
	        </div>
	        
	        <span class="swiper-button-prev"></span>
	        <span class="swiper-button-next"></span>

		</div>
	    
		<?php

		wp_reset_query();

		return ob_get_clean();
	}
}