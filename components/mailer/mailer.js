console.log('Mailer Included');

/* FORM BUTTON/LINK ONCLICK VALIDATION
	-
*/$('.addValidate a[data-validate-action="mail"]').on('click', function() {

	var formID = $(this).closest('form.addValidate').attr('id');

	///// INIT FOR EACH VALIDATION
	//**	
	t=0;
	$('#'+formID+' .toValidate.error').removeClass('error');
	var send = true;
console.log('b'+send);
	$('#'+formID+' .toValidate .field').each(function() {
			if(field_validation($(this).attr('id'))===false){
				send=false;
			}
		});
	///// VALIDATION PROCESS
	//**	
	/*var fullValidation = function() {
		$('#'+formID+' .field').each(function() {
			if(field_validation($(this).attr('id'))===false){
				send=false;
			}
		});
console.log('fchking'+send);
	}
	$.when( fullValidation() ).done(function() {
		if(send===true) {
			$('#hubert').addClass('showed');
			grecaptcha.execute();
		}
	});*/

});