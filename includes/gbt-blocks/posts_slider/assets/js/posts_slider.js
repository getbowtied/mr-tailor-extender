jQuery(function($) {

	"use strict";

	$('.gbt_18_mt_posts_slider').each(function(i) {

		var slides = 2;
		var slides_large = 2;
		var slides_xlarge = 2;

		if($(this).find('.swiper-container').hasClass('columns-3')) {
			slides = 3;
			slides_large = 3;
			slides_xlarge = 3;
		} else if($(this).find('.swiper-container').hasClass('columns-4')) {
			slides = 3;
			slides_large = 4;
			slides_xlarge = 4;
		} else if($(this).find('.swiper-container').hasClass('columns-5')) {
			slides = 3;
			slides_large = 4;
			slides_xlarge = 5;
		}

		var mySwiper = new Swiper ($(this).find('.swiper-container'), {
		    direction: 'horizontal',
		    autoplay: {
			    delay: 5000
		  	},
			loop: true,
			slidesPerView: slides,
			spaceBetween: 30,
			breakpoints: {
				0: {
			      slidesPerView: 1,
			      spaceBetween: 0,
			    },
			    768: {
			      slidesPerView: 2,
			      spaceBetween: 20,
			    },
			    1024: {
			      slidesPerView: slides_large,
			    },
				1280: {
			      slidesPerView: slides_xlarge,
			    },
			},
		    pagination: {
		    	el: $(this).find('.swiper-pagination')[i],
		    	dynamicBullets: false,
		    	clickable: true
		    },
		    navigation: {
			    nextEl: $(this).find('.swiper-button-next')[i],
			    prevEl: $(this).find('.swiper-button-prev')[i],
		  	},
		});
	});
});
