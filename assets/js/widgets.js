console.log('Widgets Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	/******************/
	/* SALES CAROUSEL */
	/******************/
	var mySwiperSales = new Swiper($('#sales_container'), {
		speed	: 1000,
		effect	: 'slide',
		slidesPerView	: 'auto',
		loop    : true,
		spaceBetween:50,
		pagination: {
			el: '.swiper-pagination',
			type: 'bullets',
			clickable: true
		},
		autoplay: {
			disableOnInteraction :false,
			delay : 3000
		},
		navigation: {
			nextEl: '.next',
			prevEl: '.prev',
		}
	});

	/********************/
	/* POPULAR CAROUSEL */
	/********************/
	var myFirstSwiper = new Swiper($('#FirstItemsCarousel'), {
		speed	: 1000,
		effect	: 'slide',
		slidesPerView: 3,
		//loop    : true,
		spaceBetween:30,
		autoplay: {
			disableOnInteraction :false,
			delay : 3000
		},
		scrollbar: {
			el: '#firstScroll',
			draggable: true
		},
		breakpoints: {
			// when window width is <= 800px
			800: {
			  slidesPerView:1,
			  spaceBetween:0
			}
		}
	});
	var myFirstSwiper = new Swiper($('#SecondItemsCarousel'), {
		speed	: 1000,
		effect	: 'slide',
		slidesPerView	: 3,
		//loop    : true,
		spaceBetween:30,
		autoplay: {
			disableOnInteraction :false,
			delay : 3000
		},
		scrollbar: {
			el: '#secondScroll',
			draggable: true
		},
		breakpoints: {
			// when window width is <= 800px
			800: {
			  slidesPerView:1,
			  spaceBetween:0
			}
		}
	});

});