jQuery(function($) {
	
	"use strict";
	
	$('.lookbook .swiper-container').each(function(){

		var mt_lookbook = $(this);

		function lookbook_init(lookbook) {
			if ( $(window).width() < 1024 ) {
				lookbook.find(".swiper-slide").width( lookbook.innerWidth() );				
			} else if ( ($(window).width() >= 1024) && ($(window).innerWidth() < 1366) ) {
				lookbook.find(".swiper-slide").width( lookbook.innerWidth()/2 );
			} else {
				if(lookbook.hasClass('column-3')) {
					lookbook.find(".swiper-slide").width( lookbook.innerWidth()/3 );
				} else {
					lookbook.find(".swiper-slide").width( lookbook.innerWidth()/2 );
				}
			}
			lookbook.find(".swiper-slide.first").width( lookbook.innerWidth() );

			var lookbook_offset = 0;
			if ( $(".transparent_header .top-headers-wrapper").length > 0) {
				lookbook_offset = 0;
			} else {
				lookbook_offset = $(".top-headers-wrapper").height();
			}

			$(".lookbook .swiper-container, .lookbook .swiper-slide").height( $(window).height() - lookbook_offset );
			$(".lookbook").css('visibility', 'visible');
		}

		lookbook_init(mt_lookbook);	

		$(window).resize( mt_lookbook, function(){
			lookbook_init(mt_lookbook);		
		});

        var lookbook_slider = new Swiper(mt_lookbook, {
        	direction: 'horizontal',
            slidesPerView: 'auto',
			grabCursor: true,
			autoHeight: true,
			pagination: { 
				el: '.pagination', 
				clickable: true,
			},
		  	navigation: {
			    nextEl: $(this).find('.lookbook-arrow-right'),
			    prevEl: $(this).find('.lookbook-arrow-left'),
		  	},
		  	on: {
		  		init: function() {
		  			
		  			$(".lookbook").css('visibility', 'visible');
		  		}
		  	}
        });

	})	
});