console.log('Profile Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	$('.subcontent').on('click',function(){
		$('.subcontent').removeClass('down');
		$(this).addClass('down');
	});

});