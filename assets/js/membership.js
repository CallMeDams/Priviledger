console.log('Membership Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	/***************************/
	/* APP SCREENSHOT CAROUSEL */
	/***************************/
	$('.poa').each(function(){
		var newh = $(this).width()*1.7761904762%
		$(this).height(newh);
	});
	setInterval(function() { 
	  $('#poa1 > img:first')
		.fadeOut(1000)
		.next()
		.fadeIn(1000)
		.end()
		.appendTo('#poa1');
	},2000);
	setInterval(function() { 
	  $('#poa2 > img:first')
		.fadeOut(1000)
		.next()
		.fadeIn(1000)
		.end()
		.appendTo('#poa2');
	},2000);

});