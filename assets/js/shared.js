console.log('Shared Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	/******************/
	/* HEADER SCOLLED */
	/******************/
	function checkScrollWindow() {
		if($(window).scrollTop()>100){
			$('header').addClass('scrolled');
		}else{
			$('header').removeClass('scrolled');
		}
	}
	$(window).on('scroll',function(){
		checkScrollWindow();
	});
	checkScrollWindow();
	
	/********************/
	/* SELECT COUNTRIES */
	/********************/
	$('.custom_select select').each(function(){
		var $this = $(this), numberOfOptions = $(this).children('option').length;

		$this.addClass('select-hidden'); 
		$this.wrap('<div class="select"></div>');
		$this.after('<div class="select-styled"></div>');

		var $styledSelect = $this.next('div.select-styled');
		$styledSelect.text($this.children('option:selected').eq(0).text());

		var $list = $('<ul />', {
			'class': 'select-options'
		}).insertAfter($styledSelect);

		for (var i = 0; i < numberOfOptions; i++) {
			$('<li />', {
				text: $this.children('option').eq(i).text(),
				rel: $this.children('option').eq(i).val()
			}).appendTo($list);
		}

		var $listItems = $list.children('li');

		$styledSelect.click(function(e) {
			e.stopPropagation();
			$('div.select-styled.active').not(this).each(function(){
				$(this).removeClass('active').next('ul.select-options').hide();
			});
			$(this).toggleClass('active').next('ul.select-options').toggle();
		});

		$listItems.click(function(e) {
			e.stopPropagation();
			$styledSelect.text($(this).text()).removeClass('active');
			/*$this.val($(this).attr('rel'));
			$list.hide();*/
			document.location.href=$(this).attr('rel');
		});

		$(document).click(function() {
			$styledSelect.removeClass('active');
			$list.hide();
		});

	});

	/***************/
	/* MOBILE MENU */
	/***************/
	$('.hamburger').on('click',function(){
		$(this).toggleClass('is-active');
		$('#topbar').toggleClass('is-active');
		$('nav#main').toggleClass('is-active');
	});

	/***************/
	/* CIRCLE LINK */
	/***************/
	if($('body').hasClass('desktop')===true){
		var maxScroll=$('body').height()-$('footer').height()-$(window).height();
		$(window).on('scroll',function(){
			var scrlTop=$(window).scrollTop();
			if(scrlTop>maxScroll){
				$('a.downloadapp').css({
					position:'absolute',
					bottom:'460px'
				});
			}else{
				$('a.downloadapp').css({
					position:'fixed',
					bottom:'20px'
				});
			}
		});	
	}else{
		var wr=($(window).width()-$('a.downloadapp').outerWidth())/2;
		$('a.downloadapp').css({
			right:wr
		});
	}

});