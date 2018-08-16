<?php

// Portfolio

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_mt_lookbook_editor_assets' );

if ( ! function_exists( 'getbowtied_mt_lookbook_editor_assets' ) ) {
	function getbowtied_mt_lookbook_editor_assets() {
		
		wp_enqueue_script(
			'getbowtied-lookbook',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'getbowtied-lookbook-editor-css',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

add_action( 'enqueue_block_assets', 'getbowtied_mt_lookbook_assets' );

if ( ! function_exists( 'getbowtied_mt_lookbook_assets' ) ) {
	function getbowtied_mt_lookbook_assets() {
		
		wp_enqueue_style(
			'getbowtied-lookbook-css',
			plugins_url( 'css/style.css', __FILE__ ),
			array()
		);
	}
}

register_block_type( 'getbowtied/mt-lookbook', array(
	'attributes'      => array(
		'title'						=> array(
			'type'						=> 'string',
			'default'					=> 'Lookbook Title',
		),
		'subtitle'						=> array(
			'type'						=> 'string',
			'default'					=> 'Lookbook Subitle',
		),
		'titleColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'subtitleColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'productColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'bgColor'						=> array(
			'type'						=> 'string',
			'default'					=> '#000',
		),
		'imgURL'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'products'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'columns'						=> array(
			'type'						=> 'integer',
			'default'					=> '3',
		),
		'height'						=> array(
			'type'						=> 'integer',
			'default'					=> '750',
		),
		'orderBy'						=> array(
			'type'						=> 'string',
			'default'					=> 'date',
		),
		'order'							=> array(
			'type'						=> 'string',
			'default'					=> 'asc',
		),
		'align'							=> array(
			'type'						=> 'string',
			'default'					=> 'center',
		),
	),

	'render_callback' => 'getbowtied_mt_render_frontend_lookbook',
) );

function getbowtied_mt_render_frontend_lookbook( $attributes ) {
	
	$sliderrandomid = rand();
	
	extract(shortcode_atts(array(
		"title" 		=> 'Lookbook Title',
		"subtitle" 		=> 'Lookbook Subtitle',
		"products" 		=> '',
		"imgURL"		=> '',
		"columns" 		=> '3',
		"titleColor"	=> '#fff',
		"subtitleColor"	=> '#fff',
		"productColor"	=> '#fff',
		"bgColor"		=> '#000',
		"height"		=> '750',
        "orderBy"	 	=> 'date',
        "order" 		=> 'desc',
		"align"			=> 'center'
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
		                    'post_type'				=> 'product',
		                    'post_status' 			=> 'publish',
		                    'ignore_sticky_posts'	=> 1,
		                    'orderby' 				=> $orderBy,
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

		                if ( $products != '' ) {
		                    $ids = explode( ',', $products );
		                    $ids = array_map( 'trim', $ids );
		                    $args['post__in'] = $ids;
		                }

		                $products = new WP_Query( $args );

		                ?>

							<?php
							$first_slide_style = 'background-color:'.$bgColor.';';      
							
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
		
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

							<?php

							$product_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID ()), 'full' );				

							$style = '';      
							
							if (isset($product_thumbnail[0])) {            
								$style = 'background-image:url(' . $product_thumbnail[0] . ')';
							}

							?>

								<div class="swiper-slide column-<?php echo $columns; ?>">								
									<div class="lookbook_product_wrapper">
										<a href="<?php the_permalink(); ?>" class="lookbook_product_wrapper_inside" style="<?php echo $style; ?>"></a>
										<div class="lookbook_product_infos">										
											<h4 class="product_price" style="color:<?php echo $productColor; ?>"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></h4>
											<h3 class="product_title" style="color:<?php echo $productColor; ?>"><?php the_title(); ?></h3>
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