(function($) {

	"use strict";

	$('.mt_ext_products_slider ul.products li.product').addClass('swiper-slide');

	$('.mt_ext_products_slider').each(function() {

		var autoplay = $(this).attr('data-autoplay');
		if ($.isNumeric(autoplay)) {
			autoplay = autoplay * 1000;
		} else {
			autoplay = 10000;
		}

		var mySwiper = new Swiper ($(this).find('.swiper-container'), {
			slidesPerView: 4,
			loop: false,
			spaceBetween: 30,
			breakpoints: {
				0: {
					slidesPerView: 2,
				},
				640: {
					slidesPerView: 3,
				},
				1024: {
					slidesPerView: 4,
				}
			},
			autoplay: {
			    delay: autoplay
		  	},
			pagination: {
			    el: $(this).find('.swiper-pagination'),
			    type: 'bullets',
			    clickable: true
			},
		});

	});

})(jQuery);
