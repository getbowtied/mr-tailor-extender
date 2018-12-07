<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';

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
    
    <?php 

	if (class_exists('WooCommerce')) {
	?>

	<div class="wp-block-gbt-lookbook <?php echo $align; ?>">

	    <div class="woocommerce">
		    
		    <div class="lookbook">
		        
		        <div class="swiper-container">

		            <span class="lookbook-arrow-left" href="#"></span>
		            <span class="lookbook-arrow-right" href="#"></span>
		            
		            <div class="swiper-wrapper">
		                
		                <?php

		                $args = array(
		                    'include'	=> explode(',',$productIDs),
		                    'limit'		=> -1,			
		                );

		                $products = wc_get_products( $args );

		                ?>

							<?php
							$first_slide_style = 'background-color:'.$backgroundColor.';';      
							
							if ( $imgURL != '' ) {
								$bg_image = wp_get_attachment_url($imgURL);          
								$first_slide_style .= 'background-image:url(' . $imgURL . ');';
							}
							?>

							<div class="swiper-slide first" style="<?php echo $first_slide_style; ?>">
								<div class="lookbook-first-slide-wrapper">
									<h2 class="lookbook-title" style="color:<?php echo $titleColor; ?>"><?php echo $title; ?></h2>
									<h3 class="lookbook-subtitle" style="color:<?php echo $subtitleColor; ?>"><?php echo $subtitle; ?></h3>
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

								<div class="swiper-slide column-<?php echo $columns; ?>">								
									<div class="lookbook_product_wrapper">
										<a href="<?php echo get_permalink($product->get_id()); ?>" class="lookbook_product_wrapper_inside" style="<?php echo $style; ?>"></a>
										<div class="lookbook_product_infos">										
											<h4 class="product_price" style="color:<?php echo $productColor; ?>"><?php echo $product->get_price_html(); ?></h4>
											<h3 class="product_title" style="color:<?php echo $productColor; ?>"><?php echo $product->get_name(); ?></h3>
										</div>
										<a href="<?php echo get_permalink($product->get_id()); ?>" class="lookbook_product_overlay"></a>
									</div>
								</div>
									
							<?php endforeach; // end of the loop. ?>

		            </div>
		            
					 <!-- Add Pagination -->
					<div class="pagination"></div>
					
		        </div>

		    </div>

	    </div>
    
    <?php } ?>

	</div>
    
	<script>
	jQuery(document).ready(function($) {
		
		function lookbook_init() {
			if ( $(window).width() < 1024 ) {
				$(".lookbook .swiper-slide").width( Math.ceil($(window).innerWidth()) );				
			} else if ( ($(window).width() >= 1024) && ($(window).innerWidth() < 1366) ) {
				$(".lookbook .swiper-slide").width( Math.ceil($(window).innerWidth()/2) );
			} else {
				$(".lookbook .swiper-slide").width( Math.ceil($(window).innerWidth()/<?php echo $columns; ?>) );
			}
			$(".lookbook .swiper-slide.first").width( Math.ceil($(window).innerWidth()) );

			var lookbook_offset = 0;
			if ( $(".transparent_header .top-headers-wrapper").length > 0) {
				lookbook_offset = 0;
			} else {
				lookbook_offset = $(".top-headers-wrapper").height();
			}

			$(".lookbook .swiper-container, .lookbook .swiper-slide").height( <?php echo $height; ?> );
			$(".lookbook").css('visibility', 'visible');
		}

		lookbook_init();	

		var lookbook_slider = [];

		$('.lookbook .swiper-container').each(function(idx){

	        lookbook_slider[idx] = new Swiper($(this), {
	            slidesPerView: 'auto',
	            mode: 'horizontal',
	            centeredSlides: false,
				grabCursor: true,
				calculateHeight: true,
				pagination: '.pagination',
				paginationClickable: true,
				resizeReInit: true,
				nextButton: $(this).find('.lookbook-arrow-right'),
	    		prevButton: $(this).find('.lookbook-arrow-left')
	        });

		})

        $(window).resize(function(){
			lookbook_init();		
			lookbook_slider.forEach(function(slider){
			    slider.slideTo(0, 0);
			});
		});
		
	});
	</script>

	<?php

	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}