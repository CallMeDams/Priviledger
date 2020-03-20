console.log('Home Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	/*****************/
	/* CARD CAROUSEL */
	/*****************/
	function rmvClass(a,b,c){
		$('.n1').removeClass('card'+a);
		$('.n2').removeClass('card'+b);
		$('.n3').removeClass('card'+c);
		$('.n1').addClass('card1');
		$('.n2').addClass('card2');
		$('.n3').addClass('card3');
		$('.n1').removeClass('n1');
		$('.n2').removeClass('n2');
		$('.n3').removeClass('n3');
	}
    if($('body').hasClass('desktop')===true){
		$('#card_carousel a.next').on('click',function(){
			$('.card1').animate({bottom:'-350px'},200).addClass('n3');
			$('.card2').animate({right:'1px',bottom:0},300).addClass('n1');
			$('.card3').animate({right:'19px',bottom:'-10px'},400).addClass('n2');
			setTimeout(function(){
				$('.card1').css({zIndex:10,right:'37px'}).animate({bottom:'-20px'},200);
				$('.card2').css({zIndex:30});
				$('.card3').css({zIndex:20});
			},500);
			setTimeout(function(){
				rmvClass(2,3,1);
			},800);
		});
		$('#card_carousel a.prev').on('click',function(){
			$('.card3').animate({bottom:'-350px'},200).addClass('n1');
			$('.card2').animate({right:'37px',bottom:'-20px'},300).addClass('n3');
			$('.card1').animate({right:'19px',bottom:'-10px'},400).addClass('n2');
			setTimeout(function(){
				$('.card3').css({zIndex:30,right:'1px'}).animate({bottom:0},200);
				$('.card2').css({zIndex:10});
				$('.card1').css({zIndex:20});
			},500);
			setTimeout(function(){
				rmvClass(3,1,2);
			},800);
		});
	}else{
		var mySwiperCards = new Swiper($('#cards'), {
            speed	: 1000,
            effect	: 'slide',
            slidesPerView	: 1,
            loop    : true,
            spaceBetween:5,
            pagination: {
                el: '#cardPagination',
                type: 'bullets',
                clickable: false
            },
            autoplay: {
                disableOnInteraction :false,
                delay : 3000
            }
        });
	}

});