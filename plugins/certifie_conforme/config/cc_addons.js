/** Function count the occurrences of substring in a string;
 * @param {String} string   Required. The string;
 * @param {String} subString    Required. The string to search for;
 * @param {Boolean} allowOverlapping    Optional. Default: false;
 * @author Vitim.us http://stackoverflow.com/questions/4009756/how-to-count-string-occurrence-in-string/7924240#7924240
 */
function occurrences(string, subString, allowOverlapping) {
    string += "";
    subString += "";
    if (subString.length <= 0) return (string.length + 1);
    var n = 0,
        pos = 0,
        step = allowOverlapping ? 1 : subString.length;
    while (true) {
        pos = string.indexOf(subString, pos);
        if (pos >= 0) {
            ++n;
            pos += step;
        } else break;
    }
    return n;
}

function hide_aide() {
	$('.aide').hide();
}

$(document).ready(function(){
	
    // REMOVE .aide ICONS
	hide_aide();
	
    // REMOVE UNUSEFUL ARTICLE FOR AUTHOR
	if($('#bando_identite .session strong.nom').text()!="Webmaster") {
		$('td.titre').each(function(index,element) {
			//alert(occurrences($('a',this).text(),"[X]"));
			if(occurrences($('a',this).text(),"[X]")>0) {
				$(this).parents('tr.row_odd').hide();
			}
		});
	}
	
    // NO AJAX LINK
	$('span.article-edit-24').each(function(index, element) {
		var url = $('a',this).attr('href');
		var data = $('a',this).html();
		$('a',this).hide();
		$(this).append('<a href="'+url+'">'+data+'</a>');
	});
    
});