console.log('Validate Included');

var imported = document.createElement("script");
imported.src = "components/validate/lang/"+$('html').attr('lang')+".js";
document.getElementsByTagName("head")[0].appendChild(imported);

/* ########## */
/* ADDON INIT */
/* ########## */

/*************/
/* VARS INIT */
/*************/
var j=0;
var t=0;

/****************************/
/* SHOWING ERROR FUNCTIONS */
/****************************/

	/* TEXT REPLACEMENT */
	/*------------------*/
	function replacer(message,findArray,replaceArray){
		var c;
		for(c=0;c<findArray.length;c++){
			var message = message.replace(findArray[c],replaceArray[c]);
		}
		return message;
	}

function show_errors(message,rplarray,t,to,uid) { 

/***/console.log(message);

	setTimeout(function(){
		var chars = ['$','£','§'];
		var errortxt = replacer(message,chars,rplarray);
/****/console.log(errortxt);
		$('#'+uid).attr('title',errortxt);
		var tip = document.querySelector('#'+uid);
		tippy(tip,{
			arrow:true,
			dynamicTitle:true,
			zIndex:25
		});
		tip._tippy.show();
		/*const btn = document.querySelector('#'+uid);
		btn._tippy.show();
		tippy(btn, { dynamicTitle: true });
		btn.title = 'New tooltip :)';
		new Noty({
			type: 'error',
			theme: 'bootstrap-v4',
			text: errortxt,
			timeout: to
//,closeWith: ['button']
		}).show();*/
	},/*t**/150);
}

/*********************************/
/* FIELD VALUE CHECKING FUNCTION */
/*********************************/
function field_validation(uid) {

	var trgt = $('#'+uid);
	var status = true;

	///// NAME
	//**	

/***/console.log('fv'+uid);

	if(typeof trgt.data('validate-name') != "undefined" && trgt.data('validate-name').length != '') {
		var name = trgt.data('validate-name');
	}else if(typeof trgt.attr('placeholder') != "undefined" && trgt.attr('placeholder').length != '') {
		var name = trgt.attr('placeholder');
	}else{
		var name = trgt.attr('name');
	}/*

	///// TIMEOUT
	//**	how long the notification will stay displayed
	if(trgt.data('timeout')>0){
		var to = trgt.data('timeout');
	}else{
		var to = 3000;
	}*/
	var to = 0;

	/* FIELD TESTING BY TYPE */
	/*-----------------------*/
	
	// ... required (not empty)
	if(trgt.prop('required') && trgt.val().length===0 && (trgt.attr('type')!='radio' || trgt.attr('type')!='checkbox')) {
		var message_array = [name,'',''];
		trgt.parent('.toValidate').addClass('error');
		show_errors(notblank,message_array,t,to,uid);
		t++;
		status=false;
	}
	if(trgt.prop('required') && (trgt.attr('type')=='radio' || trgt.attr('type')=='checkbox')) {
		if(trgt.siblings('input').length==0) {
			var message_array = [name,'',''];
			trgt.parent('.toValidate').addClass('error');
			show_errors(checked,message_array,t,to,uid);
			t++;
			status=false;
		}
	}

	// ... email (good syntax & less than 264 letters)
	if(trgt.attr('type')=='email') {
		if(trgt.val().length!=0 && trgt.val().length>264) {
			var message_array = [name,'264',''];
			trgt.parent('.toValidate').addClass('error');
			show_errors(toolong,message_array,t,to,uid);
			t++;
			status=false;
		}
	}
	//**	email (good syntax)
	if(trgt.attr('type')=='email' && trgt.val().length>0) {
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(trgt.val())==false){
			var message_array = [name,'',''];
			trgt.parent('.toValidate').addClass('error');
			show_errors(notvalid,message_array,t,to,uid);
			t++;
			status=false;
		}
	}

	return status;

}

/************************/
/* MANAGEMENT FUNCTIONS */
/************************/
	
	/* DELAY FUNCTION FOR KEYUP */
	/*--------------------------*/
	/* ... delay the keyup() handler until a user stops typing */
	var delay = (function(j){
		var timers = {};
		return function (callback, ms, label) {
			label = label || 'defaultTimer';
			clearTimeout(timers[label] || 0);
			timers[label] = setTimeout(callback, ms);
		};
	})();
	
	/* DELAYED FIELD VALIDATION ON KEYUP */
	/*-----------------------------------*/
	function checkAndDelay(uid) {

		delay(function(){

/**/console.log('ckd'+uid);

			console.log('rr'+$('#'+uid).data('original-title'));

			if($('#'+uid).parent('.toValidate').hasClass('error')==true && $('#'+uid).data('original-title')!==undefined) {
				console.log('rss'+$('#'+uid).data('tippy'));
				$('#'+uid).removeAttr('title');
				var dtip = document.querySelector('#'+uid);
				dtip._tippy.destroy();
			}

			/* INIT FOR EACH VALIDATION */
			t=0;
			$('#'+uid).removeAttr('title').parent('.toValidate.error').removeClass('error');
			/* VALIDATION PROCESS */
			field_validation(uid);
		},500,uid);		
		
	}

/****************************/
/* FIELD ONKEYUP VALIDATION */
/****************************/
$('.toValidate input, .toValidate textarea').on('keyup',function(e) {
	checkAndDelay($(this).attr('id'));
});