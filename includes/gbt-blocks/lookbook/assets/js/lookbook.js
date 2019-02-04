jQuery(function($) {
	
	"use strict";

	$('.gbt_18_mt_lookbook').each(function(){

		var mt_lookbook = $(this);

		function lookbook_init(lookbook) {
			if ( $(window).width() < 1024 ) {
				lookbook.find(".swiper-slide").width( lookbook.innerWidth() );				
			} else if ( ($(window).width() >= 1024) && ($(window).innerWidth() < 1366) ) {
				lookbook.find(".swiper-slide").width( lookbook.innerWidth()/2 );
			} else {
				if(lookbook.find('.swiper-container').hasClass('column-3')) {
					lookbook.find(".swiper-slide").width( lookbook.innerWidth()/3 );
				} else {
					lookbook.find(".swiper-slide").width( lookbook.innerWidth()/2 );
				}
			}
			lookbook.find(".swiper-slide.first").width( lookbook.innerWidth() );
		}

		lookbook_init(mt_lookbook);	

		$(window).resize( mt_lookbook, function(){
			lookbook_init(mt_lookbook);		
		});

        var lookbook_slider = new Swiper(mt_lookbook.find('.swiper-container'), {
        	direction: 'horizontal',
            slidesPerView: 'auto',
			grabCursor: true,
		  	navigation: {
			    nextEl: $(this).find('.swiper-button-next'),
			    prevEl: $(this).find('.swiper-button-prev'),
		  	},
		  	on: {
		  		init: function() {
		  			
		  			$(".gbt_18_mt_lookbook").css('visibility', 'visible');
		  		}
		  	}
        });
	})
});