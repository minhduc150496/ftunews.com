// JavaScript Document


// slick config
$(document).ready(function() {
	//Related
	$('.single-related-slick').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		prevArrow: $('.related-prev'),
		nextArrow: $('.related-next'),
		responsive: [
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1,
				}
			},
		]
	});
	//Trending
	$('.trending-slick').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		prevArrow: $('.trending-prev'),
		nextArrow: $('.trending-next'),
	});

	// This is how to append a post
	var t = $('.one-post').clone();
	t.find('.category-thumbnail-vertical-content').html("This is a clone.");

	t.appendTo($('#posts-container'));




});