jQuery(function($) {

	"use strict";

	$('.gbt_18_mt_posts_slider').each(function(i) {

		var slides = 2;
		var slides_large = 2;

		if($(this).find('.swiper-container').hasClass('columns-3')) {
			slides = 3;
			slides_large = 3;
		} else if($(this).find('.swiper-container').hasClass('columns-4')) {
			slides = 4;
			slides_large = 3;
		} else if($(this).find('.swiper-container').hasClass('columns-5')) {
			slides = 5;
			slides_large = 3;
		} else {
			slides = 2;
		}

		var mySwiper = new Swiper ($(this).find('.swiper-container'), {
		    direction: 'horizontal',
		    autoplay: {
			    delay: 5000
		  	},
			loop: true,
			slidesPerView: slides,
			spaceBetween: 25,
			breakpoints: {
				480: {
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
