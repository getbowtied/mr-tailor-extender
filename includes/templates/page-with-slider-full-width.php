<?php
/*
Template Name: Page with Slider Full Width
*/

?>

<?php get_header(); ?>

<div class="page_with_slider">

	<?php
        global $slider_metabox;
        $slider_metabox->the_meta();
    ?>
    
    <?php
    
	$slider_style = $slider_metabox->get_the_value('slider_template');
	switch ($slider_style) {
		case "style_1":
			include_once('slider/style_1.php');
			break;
		case "style_3":
			include_once('slider/style_3.php');
			break;
		case "style_4":
			include_once('slider/style_4.php');
			break;
		case "style_6":
			include_once('slider/style_6.php');
			break;
		default:
			include_once('slider/style_1.php');
	}
	
	?>
    
    <?php if ($post->post_content != "") : ?>
    
    <div class="full-width-page">
    
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content" role="main">
                
                    <?php while ( have_posts() ) : the_post(); ?>
        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->
        
                    <?php endwhile; // end of the loop. ?>
    
            </div><!-- #content -->           
            
        </div><!-- #primary -->
    
    </div><!-- .full-width-page -->
    
    <?php endif; ?>
    
</div>
    
<?php get_footer(); ?>
