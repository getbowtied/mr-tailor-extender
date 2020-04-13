<?php

/*
 * Sliders output.
 */
function mt_products_slider( $type = '', $products = null, $title = '' ) {
?>

    <?php if ( $products->have_posts() ) : ?>

    	<div class="<?php echo $type; ?>-wrapper wc-products-slider mt_ext_products_slider woocommerce">

            <div class="mt_items_sliders_header">
                <div class="mt_items_sliders_title">
                    <?php echo $title ?>
                </div>
            </div>

            <div class="swiper-container">

                <ul class="products swiper-wrapper">

                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                    <?php endwhile; ?>

        		</ul>

            </div>

            <div class="swiper-pagination"></div>

        </div>

    <?php endif; ?>

<?php
}

?>
