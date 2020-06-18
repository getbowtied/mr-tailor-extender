jQuery(function($) {

	"use strict";

    $('.main-navigation > ul > li.menu-item-megamenu').each( function() {
        var childrenNumber = $(this).find('> .sub-menu > li').length;
        $(this).addClass('megamenu-'+childrenNumber+'-col');

        if( $(this).attr('data-item-image') ) {
            $(this).find('.sub-menu .menu-item-image-column').css('background-image', 'url('+$(this).attr('data-item-image')+')')
        }
    });

    $('.main-navigation > ul > li.menu-item-has-children .sub-menu li.menu-item a').on( 'mouseenter', function() {
        if( $(this).parent().attr('data-item-image') ) {
            $(this).parents('.menu-item-parent').find('.sub-menu .menu-item-image-column').css('background-image', 'url('+$(this).parent().attr('data-item-image')+')');
        } else {
            $(this).parents('.menu-item-parent').find('.sub-menu .menu-item-image-column').css('background-image', 'url('+$(this).parents('.menu-item-parent').attr('data-item-image')+')')
        }
    });
});
