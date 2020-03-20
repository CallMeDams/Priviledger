console.log('Starter Included');

/*!
 * hoverIntent
 */
$.fn.hoverDelay=function(n){var e={delayIn:300,delayOut:300,handlerIn:function(){},handlerOut:function(){}};return n=$.extend(e,n),this.each(function(){var e,t,u=$(this);u.hover(function(){t&&clearTimeout(t),e=setTimeout(function(){n.handlerIn(u)},n.delayIn)},function(){e&&clearTimeout(e),t=setTimeout(function(){n.handlerOut(u)},n.delayOut)})})};

/* ############## */
/* CORE FUNCTIONS */
/* ############## */
$(document).ready(function(){

	/*************/
	/* FUNCTIONS */
	/*************/
	
		/* DEVELOPMENT ONLINE DATA */
		/*-------------------------*/
		function checkDev(){
			var winwidth = $(window).width();
			var winheight = $(window).height();
			$('#check strong').text(winwidth+' '+winheight);
		}
		$(window).scroll(function(){
			var winscrolltop = $(window).scrollTop();
			$('#check span').text(' '+winscrolltop);		
		});
	
	/***********/
	/* TOOLBOX */
	/***********/

		/* SAME MAX DIMENSION AS THE HIGHEST OR LONGEST ELEMENT */
		/*------------------------------------------------------*/
		if($('.samesize').length>0){
			$('.samesize').each(function(){
				var type = $(this).data('size');
				var finalsize = 0;
				$('.item',this).each(function(){
					if(type=='width'){
						var sizethis = $(this).width();						
					}else{						
						var sizethis = $(this).height();
					}
					if(sizethis>finalsize) { finalsize = sizethis; }
				});
				if(type=='width'){
					$('.item',this).each(function(){ $(this).width(finalsize); });					
				}else{						
					$('.item',this).each(function(){ $(this).height(finalsize); });
				}				
			});
		}

		/* LINKS MANAGEMENT */
		/*------------------*/
			
			/* ... Dead link prevent default action */
			$("a[href$='#']").attr('href','javascript:void(0);');
			
			/* ... Simulate classic link action on every element with .clickngo class */
			$('.clickngo').css('cursor','pointer').click(function(e){
				e.preventDefault();
				document.location.href=$(this).data('target');
			});
			
			/* ... Simulate classic link action in a new window on every element with .clicknopen class */
			$('.clicknopen').css('cursor','pointer').click(function(e){
				e.preventDefault();
				if($(this).data('blank')>0){
					window.open($(this).data('target'),'_blank');
				}else{
					document.location.href=$(this).data('target');
				}
			});

		/* JAVASCRIPT PAGE BACK HISTORY */
		/*------------------------------*/
		$('.hPrev').click(function() { history.back(); });
		$('.hNext').click(function() { history.forward(); });

		/* LOAD MOBILE VERSION UNDER 800PX WIDTH */
		/*---------------------------------------*/
        if($('link[rel=canonical]').attr('href').indexOf("?")>0){
            var newloc=$('link[rel=canonical]').attr('href')+'&device=';
        }else{
            var newloc=$('link[rel=canonical]').attr('href')+'?device=';
        }
		if($(window).width()>900 && $('body').hasClass('phone')===true){
			document.location.href=newloc+'desktop';
		}
		if($('body').hasClass('desktop')===true){
			$(window).on('resize',function(){
				if($(window).width()<850 && $('body').hasClass('desktop')===true){
					document.location.href=newloc+'phone';
				}
				if($(window).width()>850 && $('body').hasClass('phone')===true){
					document.location.href=newloc+'desktop';
				}
			});
		}else{
			window.addEventListener("orientationchange",function() {
				location.reload();
			},false);
		}

		/* FORM IHFN */
		/*-----------*/
		if($('.ihfn').length>0) {
			$('.ihfn').each(function(){
				var thisname = $(this).attr('name');
				$('input.ihfn[name='+thisname+']').val(1);
				console.log('input.ihfn[name='+thisname+']');
			});
		}

	/*******/
	/* STARTER JS INIT */
	/*******/
	checkDev();
	$(window).resize(function() {
		checkDev();
	});

});