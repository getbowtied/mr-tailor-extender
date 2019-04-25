<?php

// [from_the_blog]
function shortcode_from_the_blog($atts, $content = null) {

	wp_enqueue_style('mrtailor-from-the-blog-slider-shortcode-styles');
	wp_enqueue_style('mr_tailor-owl');
	wp_enqueue_script('mr_tailor-owl');

	$sliderrandomid = rand();
	extract(shortcode_atts(array(
		"posts" => '9',
		"category" => ''
	), $atts));
	ob_start();
	?> 
    
    <script>
	jQuery(document).ready(function($) {
		$("#from-the-blog-<?php echo $sliderrandomid ?>").owlCarousel({
			items:3,
			itemsDesktop : [1200,3],
			itemsDesktopSmall : [1000,2],
			itemsTablet: false,
			itemsMobile : [600,1],
			lazyLoad : true,
			/*autoHeight : true,*/
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
                'category_name' => $category,
                'posts_per_page' => $posts
            );
            
            $recentPosts = new WP_Query( $args );
            
            if ( $recentPosts->have_posts() ) : ?>
                        
                <?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>
            
                    <?php $post_format = get_post_format(get_the_ID()); ?>
                    
                    <div class="from_the_blog_item <?php echo $post_format ? $post_format: 'standard'; ?> <?php if ( !has_post_thumbnail()) : ?>no_thumb<?php endif; ?>">
                        
						<a class="from_the_blog_img_link" href="<?php the_permalink() ?>">
							<span class="from_the_blog_overlay"></span>
							
							<?php if ( has_post_thumbnail()) :
								$image_id = get_post_thumbnail_id();
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
                        
                        	<h3><a class="from_the_blog_title" href="<?php the_permalink() ?>"><?php echo get_the_title(); ?></a></h3>
                        	<div class="post_header_date">

                        		<?php 
                        			if ( has_post_format( array( 'chat', 'status' ) ) )
										$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'mr_tailor' );
									else
										$format_prefix = '%2$s';
                        		?>

                        		<a href="<?php echo esc_url( get_permalink() ); ?>" 
                        			title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'mr_tailor' ), the_title_attribute( 'echo=0' ) ) ); ?>" 
                        			rel="bookmark">
                        			<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        				<?php echo esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) ); ?>
                        			</time>
                        		</a>
                        		
                        	</div>                       
                                
                        </div>
                        
                    </div>
        
                <?php endwhile; // end of the loop. ?>
                
            <?php

            endif;
            
            ?> 
              
        </div>
	</div>
    </div>
	
	<?php
	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("from_the_blog", "shortcode_from_the_blog");