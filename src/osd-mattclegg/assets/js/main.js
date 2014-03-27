(function ( $ ) {
	"use strict";

	$(function () {

		// Place your public-facing JavaScript here

        $('.subpages-expand').click( function(e) {
            $(this).parent('.page_item_has_children').toggleClass('expanded');
        } );

        // ascending sort
        function asc_sort(a, b){
            return ($(b).text()) < ($(a).text()) ? 1 : -1;
        }

        // decending sort
        function dec_sort(a, b){
            return ($(b).text()) > ($(a).text()) ? 1 : -1;
        }

        $('.subpages-sort').click( function(e) {
            var $list = $(this).prev("ul");
            $(this).toggleClass('subpages-sortordered');

            if($(this).hasClass('subpages-sortordered')){
                $list.append($list.find('> li').get().sort(dec_sort));
            }else{
                $list.append($list.find('> li').get().sort(asc_sort));
            }
        } );

	});

}(jQuery));