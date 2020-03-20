function defer(method) { 'use strict';
	if(window.jQuery) {
		method();
    }else{
		setTimeout(function() { defer(method); }, 100);
    }
}

defer(function() { 'use strict';

	console.log('Sort and Filter Init Included');
    
    /* ********************** */
	/* ***** ADDON INIT ***** */
	/* ********************** */
	$(document).ready(function() {
		
		$('.addSFC').each(function(){
            $(this).jplist({				
                itemsBox: '.list' 
                ,itemPath: '.list-item' 
                ,panelPath: '.jplist-panel'	
            });
        });
        
	});
	
});