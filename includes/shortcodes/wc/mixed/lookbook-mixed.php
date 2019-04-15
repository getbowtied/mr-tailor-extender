<?php

function shortcode_lookbook_mixed($atts, $content = null) {

	wp_enqueue_style('mrtailor-lookbook-shortcode-styles');

	global $woocommerce;
	$sliderrandomid = rand();
    extract(shortcode_atts(array(
		'title' => '',
		'subtitle' => '',
		'bg_image' => '',
		'columns' => 3,
        'orderby' => 'date',
        'order' => 'desc',
		'ids' => ''
	), $atts));
	ob_start();
    ?>

    <div class="woocommerce">
	    
	    <div class="lookbook">
	        
	        <div class="swiper-container">

	            <span class="lookbook-arrow-left" href="#"></span>
	            <span class="lookbook-arrow-right" href="#"></span>
	            
	            <div class="swiper-wrapper">
	                
	                <?php

	                $args = array(
	                    'post_type'				=> 'product',
	                    'post_status' 			=> 'publish',
	                    'ignore_sticky_posts'	=> 1,
	                    'orderby' 				=> $orderby,
	                    'order' 				=> $order,
	                    'posts_per_page' 		=> -1,
						'tax_query' 			=> array(
	                    	array(
	                           'taxonomy' => 'product_visibility',
	                           'field'    => 'name',
	                           'terms'    => 'exclude-from-catalog',
	                           'operator' => 'NOT IN',

	                    	)
	                	)
	                );

	                if ( isset( $atts['ids'] ) ) {
	                    $ids = explode( ',', $atts['ids'] );
	                    $ids = array_map( 'trim', $ids );
	                    $args['post__in'] = $ids;
	                }

	                $products = new WP_Query( $args );

	                ?>

						<?php
						$first_slide_style = '';      
						
						if (is_numeric($bg_image)) {
							$bg_image = wp_get_attachment_url($bg_image);          
							$first_slide_style = 'background-image:url(' . $bg_image . ')';
						}
						?>

						<div class="swiper-slide first" style="<?php echo $first_slide_style; ?>">
							<div class="lookbook-first-slide-wrapper">
								<h2 class="lookbook-title"><?php echo $title; ?></h2>
								<h3 class="lookbook-subtitle"><?php echo $subtitle; ?></h3>
							</div>
						</div>
	
						<?php while ( $products->have_posts() ) : $products->the_post(); ?>

						<?php

						$product_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID ()), 'full' );				

						$style = '';      
						
						if (isset($product_thumbnail[0])) {            
							$style = 'background-image:url(' . $product_thumbnail[0] . ')';
						}

						?>

							<div class="swiper-slide">								
								<div class="lookbook_product_wrapper">
									<a href="<?php the_permalink(); ?>" class="lookbook_product_wrapper_inside" style="<?php echo $style; ?>"></a>
									<div class="lookbook_product_infos">										
										<h4 class="lookbook_product_price"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></h4>
										<h3 class="lookbook_product_title"><?php the_title(); ?></h3>
									</div>
									<a href="<?php the_permalink(); ?>" class="lookbook_product_overlay"></a>
								</div>
							</div>
								
						<?php endwhile; // end of the loop. ?>

	            </div>
	            
				 <!-- Add Pagination -->
				<div class="pagination"></div>
				
	        </div>

	    </div>

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

			$(".lookbook .swiper-container, .lookbook .swiper-slide").height( $(window).height() - lookbook_offset );
			$(".lookbook").css('visibility', 'visible');
		}

		lookbook_init();	

		var lookbook_slider = [];

		$('.lookbook .swiper-container').each(function(idx){

	        lookbook_slider[idx] = new Swiper($(this), {
	            slidesPerView: 'auto',
	            direction: 'horizontal',
				grabCursor: true,
				autoHeight: true,
				pagination: { el: '.pagination', clickable: true, },
				navigation: {
					nextEl: $(this).find('.lookbook-arrow-right'),
					prevEl: $(this).find('.lookbook-arrow-left'),
				}
	        });
		})
				
        $(window).resize(function(){
			lookbook_init();		
		});
		
	});
	</script>

	<?php

	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;

}

add_shortcode("lookbook_mixed", "shortcode_lookbook_mixed");