(function ($) {
	"use strict";
	$(document).ready(function () {
		/*  [ owl-carousel ]

		 - - - - - - - - - - - - - - - - - - - - */
		$('.product-images .owl-carousel').owlCarousel({
			loop:true,
			margin:0,
			responsiveClass:true,
			items: 1,
			autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			nav:true,
			dots:true,
			dotsData:true,
			navText: ["",""],
			smartSpeed:700,
		})
		$(".slide-blog .owl-carousel").owlCarousel({
			loop:true,
			margin: 15,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				768: {
					items: 3
				},
				992: {
					items: 3
				},
				1200: {
					items: 3
				}
			}
		});
		$('.work-ad .owl-carousel').owlCarousel({
			loop:true,
			margin: 15,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				768: {
					items: 3
				},
				992: {
					items: 4
				},
				1200: {
					items: 5
				}
			}
		});
		$('.slide-banner .owl-carousel').owlCarousel({
			items: 1,
			nav: true,
			navText: ["", ""],
			loop: true,
			autoplay: true,
			autoplayTimeout: 5000,
			autoplayHoverPause: true,
			dotsData: true,
			smartSpeed: 600,
		});

		$('.block-testimonial .owl-carousel').owlCarousel({
			items: 1,
			nav: true,
			navText: ["", ""],
			loop: true,
			autoplay: true,
			autoplayTimeout: 4000,
			autoplayHoverPause: true,
			smartSpeed: 800,
		});


		$('.carousel-khoahoc').owlCarousel({
			loop:true,
			margin: 15,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				768: {
					items: 3
				},
				992: {
					items: 4
				},
				1200: {
					items: 5
				}
			}
		})

		$('.carousel-khoahoc2').owlCarousel({
			loop:true,
			margin: 15,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				768: {
					items: 3
				},
				992: {
					items: 4
				},
				1200: {
					items: 4
				}
			}
		})

		$('.carousel-khoahoc3').owlCarousel({
			loop:true,
			margin: 15,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				768: {
					items: 3
				},
				992: {
					items: 3
				},
				1200: {
					items: 3
				}
			}
		})

		$('.carousel-postRelated').owlCarousel({
			loop:true,
			margin: 25,
			nav: true,
			navText: ["", ""],
			dots: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 4000,
			smartSpeed: 800,
			responsive: {
				0: {
					items: 1
				},
				480: {
					items: 2
				},
				992: {
					items: 2
				},
				1200: {
					items: 3
				}
			}
		})

		$(".block-categories .dropdown-toggle").on('click', function () {
			$(this).parent().toggleClass('open-submenu');
			$(this).parent().children(".submenu ").slideToggle();
			return false;
		});
	});


})(jQuery);