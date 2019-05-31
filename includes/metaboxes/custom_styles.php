<?php

if ( ! function_exists ('mr_tailor_metaboxes_custom_styles') ) {
	function mr_tailor_metaboxes_custom_styles() {	
		global $slider_metabox;
		$slider_metabox->the_meta();

		?>

		<style>

		<?php

		$slide_counter = 0;
		while($slider_metabox->have_fields('items'))
		{
			$slide_counter++;
		?>
			
			.main-slider .slide_<?php echo esc_html($slide_counter); ?> {
				background-image:url(<?php echo ($slider_metabox->get_the_value('imgurl')); ?>);
			}
			
			.main-slider .slide_<?php echo esc_html($slide_counter); ?> .main-slider-elements.animated {				
				-webkit-animation-name: <?php echo ($slider_metabox->get_the_value('slide_animation')); ?>;
				-moz-animation-name: <?php echo ($slider_metabox->get_the_value('slide_animation')); ?>;
				-o-animation-name: <?php echo ($slider_metabox->get_the_value('slide_animation')); ?>;
				animation-name: <?php echo ($slider_metabox->get_the_value('slide_animation')); ?>;
			}
			
			<?php if ($slider_metabox->get_the_value('slider_mood') == 'light') : ?>

				.main-slider .slide_<?php echo esc_html($slide_counter); ?> h1 {
					color:#000;
				}
				
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> h1:after {
					background:#000;
				}
				
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> h2 {
					color:#000;
				}
				
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> a.slider_button {
					color:#fff;
					background:#000;
				}
				
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> a.slider_button:hover {
					color:#000 !important;
					background:#fff !important;
				}
				
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> .arrow-left,
				.main-slider .slide_<?php echo esc_html($slide_counter); ?> .arrow-right
				{
					color:#000;
				}
				
			<?php endif; ?>
			
		<?php    
		}	
		?>

		</style>

		<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) { if(!empty($line)) $new_lines[] = trim($line); }
		echo implode($new_lines);
	}
}

add_filter( 'wp_head', 'mr_tailor_metaboxes_custom_styles', 100 );