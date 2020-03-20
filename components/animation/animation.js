console.log('Animation Included');

/* ********************** */
/* ***** ADDON INIT ***** */
/* ********************** */
$( document ).ready(function() {

	/* Target each element with class "animated" */
	/* ----------------------------------------- */
	$('.animation').each(function(index){
	
		/* Create animation vars */
		/* --------------------- */
		var target=$(this);
		var animOpt=$(this).data('anim-options');
		
		/* Test if target must be animated
		   when the element is entirely on screen or when 
		   the top of the element just enter on screen
		   -------------------------------------------- */
		if(target.hasClass('entered')===true){
			/* Init Waypoint+InView function */
			/* ----------------------------- */
			var inView = new Waypoint.Inview({
				element: target,
				entered: function(direction) {
					/* Add animation.css classes */
					/* ------------------------- */
					target.addClass('animated '+animOpt);
				}
			});
		}else{
			/* Init Waypoint+InView function */
			/* ----------------------------- */
			var inView = new Waypoint.Inview({
				element: target,
				enter: function(direction) {
					/* Add animation.css classes */
					/* ------------------------- */
					target.addClass('animated '+animOpt);
				}
			});
		}
	});
    
});