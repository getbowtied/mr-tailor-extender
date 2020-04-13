(function($) {

	"use strict";

	var mySwiper = new Swiper ($('.from-the-blog-wrapper.swiper-container'), {
		slidesPerView: 3,
		loop: false,
		spaceBetween: 30,
		breakpoints: {
			0: {
				slidesPerView: 2,
			},
			640: {
				slidesPerView: 3,
			}
		},
		pagination: {
		    el: $('.from-the-blog-wrapper.swiper-container .swiper-pagination'),
		    type: 'bullets',
		    clickable: true
		},
	});

})(jQuery);
