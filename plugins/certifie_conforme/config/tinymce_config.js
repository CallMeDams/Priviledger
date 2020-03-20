function barre_forcer_hauteur () {
	$(".tiny_editor").each(function() {
		var hauteur_min = $(this).height();
		var hauteur_max = parseInt($(window).height()) - 200;
		var hauteur = hauteur_min;
		var signes = $(this).val().length;
		if (signes){
			/* en gros: 400 signes donnent 100 pixels de haut */
			var hauteur_signes = Math.round(signes / 4) + 50;
			if (hauteur_signes > hauteur_min && hauteur_signes < hauteur_max) 
				hauteur = hauteur_signes;
			else 
				if (hauteur_signes > hauteur_max) 
					hauteur = hauteur_max;
			$(this).height(hauteur);
		}
	});
}
function TinyMCE_init(){
	$('.editer_chapo label, .editer_texte label, .editer_ps label').each(function(){
		$(this).replaceWith('<h3 class="legend"><strong>'+$(this).html()+'</strong></h3>');
	});
	$('.editer_chapo #chapo, .editer_texte #text_area, .editer_ps #ps').addClass('tiny_editor');
	barre_forcer_hauteur();
	tinymce.init({
		selector:		".tiny_editor",
		language:		"fr_FR",
		resize: 		true,
		theme:			"modern",
		image_advtab: 	true,
		plugins: [
			"advlist autolink lists link image charmap hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking table contextmenu",
			"template paste textcolor colorpicker textpattern"
		],
		visual:			true,
		visualblocks_default_state:	true,
		toolbar1:		"bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | styleselect | forecolor backcolor",
		toolbar2:		"insertfile | searchreplace | link unlink image media | insertdatetime charmap | template | fullscreen",
		toolbar3:		"hr",
		menu:			{
			edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
			format : {title : 'Format', items : 'underline strikethrough superscript subscript | removeformat'},
			table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
			tools  : {title : 'Tools' , items : 'code'}
		},
		extended_valid_elements : 'script',
  		link_context_toolbar: true,
		allow_unsafe_link_target: true,
		link_class_list: [
			{title: 'None', value: ''}
		],
		textcolor_map: [
			"ff5859", "Made for you",
			"000000", "Black",
			"ffffff", "White"
		],
		style_formats:	[
			{title: "Headers", items: [
				{title: "Header 2", format: "h2"},
				{title: "Header 3", format: "h3"},
				{title: "Header 4", format: "h4"},
				{title: "Header 5", format: "h5"},
				{title: "Header 6", format: "h6"}
			]},
			{title: "Blocks", items: [
				{title: "Paragraph", format: "p"},
				{title: "Blockquote", format: "blockquote"},
				{title: "Div", format: "div"},
				{title: "Pre", format: "pre"}
			]},
			{title: "Inline", items: [
				{title: "Underline", icon: "underline", format: "underline"},
				{title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
				{title: "Superscript", icon: "superscript", format: "superscript"},
				{title: "Subscript", icon: "subscript", format: "subscript"},
				{title: "Code", icon: "code", format: "code"}
			]},
			{title: "Alignment", items: [
				{title: "Left", icon: "alignleft", format: "alignleft"},
				{title: "Center", icon: "aligncenter", format: "aligncenter"},
				{title: "Right", icon: "alignright", format: "alignright"},
				{title: "Justify", icon: "alignjustify", format: "alignjustify"}
			]}
		],
		content_css : '../components/starter/bridge.css',
		file_browser_callback: function(field_name,url,type,win) {
			var dw=window.innerWidth-40,
				dh=window.innerHeight-60;
			if(dw>1800 && (dw=1800), dh>1200 && (dh=1200), dw>600) {
				var diff = (dw - 20)%138;
				dw=dw-diff+10
			}
			if(type=='image') { var dt='EXPLOREUR DE MEDIAS'; }else{ var dt='EXPLOREUR DE FICHIERS'; }
			var cmsURL = '../filemanager/?type='+type;
			tinymce.activeEditor.windowManager.open({
					file            : cmsURL,
					title           : dt,
					width           : dw,
					height          : dh,
					resizable       : "yes",
					inline          : "yes",
					close_previous  : "yes"
				},{
					window  : win,
					input   : field_name
			});
		},/*setup : function(ed) {
			// Add a custom button
			ed.addButton('clearbutton', {
				title : 'Clear Break',
				image : '../imports/core/rte_clear.gif',
				onclick : function() {
					// Add you own code to execute something on click
					ed.focus();
					ed.selection.setContent('<div class="clear_in">&nbsp;</div>');
				}
			});
		},*/
	});
	//alert('rsys');
}