jQuery(function($) {
	
	"use strict";

	$('.trigger-share-list').on('click',function(){
		
		var share_list_height = $('.box-share-list-inner').outerHeight();
		
		$('.box-share-list').css('height',share_list_height);
		$('.box-share-container').addClass('open');
		
		$("body").on('click',function(e) {
			if ( $('.box-share-container').hasClass('open') ) {
			
				if ( $(e.target).attr('class') == 'box-share-list-inner' ) {
					return;
				} else {
					$('.box-share-container').removeClass('open');
					$('.box-share-list').css('height',0);
					$('body').unbind('click');
				}
			
			}
		});
		
		return false;
	})
});