console.log('Expertises Included');

/* *********************************** */
/* ***** CUSTOM SHARED FUNCTIONS ***** */
/* *********************************** */
$(document).ready(function() {

	/*********/
	/* ORDER */
	/*********/
	if($('body').hasClass('phone')===true){
		
		// MOVES
		$("#side_2").after( $("#expertise_2") );
		$("#side_5").after( $("#expertise_3") );
		$("#side_1").after( $("#expertise_4") );
		$("#side_3").after( $("#expertise_5") );

	}

});