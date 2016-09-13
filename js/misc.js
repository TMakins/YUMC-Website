/**
 * File misc.js.
 *
 * Misc Javascript.
 */

jQuery(window).on('load',(function( $ ) {
    var $ = jQuery;
    $(".blog #main .masonry-container").masonry({
        // options
        itemSelector: '.masonry',
        columnWidth: 380,
        gutter: 80
    });
}));

jQuery(document).ready(function( $ ) {
    $(".social_widget .view_all_more_button").click(function(event) {
       event.preventDefault();

        $(this).siblings(".twitter_button").animate({left:'0'}, 50);
        $(this).siblings(".facebook_button").animate({right:'0'}, 50);
    });
});