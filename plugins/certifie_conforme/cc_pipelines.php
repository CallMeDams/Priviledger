<?php

/**
 * SECURITY
 */
if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Adding TinyMCE scripts in the head of the private pages
 *
 * @pipeline header_prive
 * @param  string $flux Head content
 * @return string Head content
 */
function cc_insert_head_prive($flux){
	$addons = find_in_path('config/cc_addons.js');
	$flux .= 
		   "<script type='text/javascript' src='$addons'></script>\n";
	if(_request('exec')=='article_edit') {
		//$js = find_in_path('components/tinymce/tinymce.gzip.js');
		$js = find_in_path('components/tinymce/tinymce.min.js');
		$flux .= 
		   "<script type='text/javascript' src='$js'></script>\n";
		/*$js_start = generer_url_public('config/tinymce_config');
		if (defined('_VAR_MODE') AND _VAR_MODE=="recalcul")
			$js_start = parametre_url($js_start, 'var_mode', 'recalcul');*/
		$js_start = find_in_path('config/tinymce_config.js');
		$flux .= 
		   "<script type='text/javascript' src='$js_start?refresh=".uniqid()."'></script>\n"
		 . "<script type='text/javascript'>
	$(document).ready(function(){ TinyMCE_init(); });
</script>\n";
	}
	/*$addons = find_in_path('components/starter/bridge.css');
	$flux .= 
		   "<link type='text/css' rel='stylesheet' href='$addons?refres=".uniqid()."' />\n";*/
	return $flux;
}

/**
 * Clean text before cms treatment and public view
 *
 * @pipeline pre_typo
 * @param  string $flux Public page content
 * @return string Public page content
 */
function cc_clean_typo($flux){
	
	/* - SINGLE <p> CLEANING IN FRONTEND PAGES ONLY - */
	if(substr_count($_SERVER['REQUEST_URI'],_DIR_RESTREINT_ABS)==0) {
		if(substr_count($flux, '</p>')==1){
			$flux=preg_replace('|<p(.*)>(.*)<\/p>|isU','<span class="block"$1>$2</span>',$flux);
			$flux=str_replace('class="block" class="','class="block ',$flux);
		}
		// RTE URL hack for private/public foldr
		$flux=str_replace(' />','>',$flux);
		$flux=str_replace('="../','="',$flux);
		$flux=str_replace('target="_blank"','target="_blank" rel="external"',$flux);
	}
	
	// Clean row empty padding/margin
	//$flux = str_replace('style=";"','',$flux);
	/*$flux = str_replace('
<p><!--<br class=\'autobr\' />
		--></p>
','<!--
-->',$flux);*/
	// URL Conversion for TinyMCE
		/*if(substr_count($flux, '@art')>0){
			//$flux=preg_replace('|@art([0-9]*)|isU',findUrl('article','$1'),$flux);
			//$flux=preg_replace('|href="@art([0-9]*)"|isU','$1',$flux);
			$flux=preg_replace('|@art([0-9]*)|isU','./?article$1',$flux);
		}
		if(substr_count($flux, '@rub')>0){
			//$flux=preg_replace('|href="@rub([0-9]*)"|isU',findUrl('rubrique','$1'),$flux);
		}*/
	
	// RETURN CLEAN HTML
	return $flux;
}

/**
 * Adding only useful css and js in the head and bottom of public pages
 * by replacing <!--INITCSS-->, <!--ADDDONSCSS--> and <!--ADDONSJS-->
 *
 * @pipeline affichage_final
 * @param  string $flux Public page content
 * @return string Public page content
 */
function cc_clean_page($flux){

/* ***** DETECT ADMIN CONNECTION ***** */
if(substr_count($flux,'<meta name="admin" content="yes">')>0 || substr_count($flux,'<meta name="refresh" content="yes">')>0){
	$refresh='?refresh='.uniqid();
}

/* ***** POST TYPO CLEANING ***** */
	$findthis=array(' />','../','..%2F');
	$replaceby=array('>','','');
	$flux=str_replace($findthis,$replaceby,$flux);
	$flux=preg_replace('|<img src=\'(.*)\' width=\'([0-9]*)\' height=\'([0-9]*)\' class=\'backimage\'>|','$1',$flux);

/* ***** COMPONENTS CSS AND JS ADDITIONS ***** */
	if(substr_count($flux,'ADDONSCSS')>0) {

	///// COMPONENTS KEYWORDS DETECTION & LIST IN ARRAY MAKING
	
		// INITCSS components array creation
		preg_match('|<!--INITCSS\((.*)\)-->|',$flux,$initmatches);
		if(!empty($initmatches[1])>0) {
			$initCptCSS=explode('.',$initmatches[1]);
		}else{
			$initCptCSS[]='';
		}

		// Empty arrays creation for components CSS/S inclusion
		$addjs[]='';
		
		// Mandatory JS addons
		$addjs[]='<script defer src="components/jquery/jquery.js'.$refresh.'"></script>';
		if(file_exists('components/jquery/jquery-ui.js')) {
			$addjs[]='<script defer src="components/jquery/jquery-ui.js'.$refresh.'"></script>';
		}
        if(substr_count($flux,'incTouch')>0) {
			$addjs[]='<script defer src="'.minifier('components/touch/touch.js').$refresh.'"></script>';
		}
		if(file_exists('components/starter/starter.js')) {
			$addjs[]='<script defer src="'.minifier('components/starter/starter.js').$refresh.'"></script>';
		}
		
		// GRID : Dynamic Filtering & Sorting snippet
		if(substr_count($flux,'animated')>0 || substr_count($flux,'incAnimation')>0) {
			$addCptCSS[]='components/animation/animation.css';
		}
		
		// INVIEW : Detect Inview snippet
		if(substr_count($flux,'incInView')>0 || substr_count($flux,'incAnimNum')>0 || substr_count($flux,'addAnimNum')>0 || substr_count($flux,'animated')>0) {
			$addjs[]='<script defer src="'.minifier('components/inview/inview.js').$refresh.'"></script>';
		}
		// NOTIFY : Detect Notify snippet
		if(substr_count($flux,'incNotify')>0 || substr_count($flux,'addValidate')>0) {
			if(in_array('initNotify',$initCptCSS)===true) {
				$addInitCptCSS[]='components/notifications/notify.css';
			}else{
				$addCptCSS[]='components/notifications/notify.css';
			}
			$addjs[]='<script defer src="'.minifier('components/notifications/notify.js').$refresh.'"></script>';
		}
		
		// GRID : Dynamic Filtering & Sorting snippet
		if(substr_count($flux,'addGrid')>0 || substr_count($flux,'incGrid')>0) {
			$addjs[]='<script defer src="'.minifier('components/grid/grid.js').$refresh.'"></script>';
		}
		// MODAL : Detect Modal snippet
		if(substr_count($flux,'addModal')>0 || substr_count($flux,'incModal')>0 || substr_count($flux,'data-fancybox')>0) {
			if(in_array('initSelect',$initCptCSS)===true) {
				$addInitCptCSS[]='components/modal/modal.css';
			}else{
				$addCptCSS[]='components/modal/modal.css';
			}
			$addjs[]='<script defer src="'.minifier('components/modal/modal.js').$refresh.'"></script>';
		}
		// NUMBERS ANIMATION : Detect Animated Numbers snippet
		if(substr_count($flux,'addAnimNum')>0 || substr_count($flux,'incAnimNum')>0) {
			$addjs[]='<script defer src="'.minifier('components/animnum/animnum.js').$refresh.'"></script>';
		}
		// PARALLAX : Detect Parallax snippet ******** > data-src="" data-speed="0.1" data-parallax
		if(substr_count($flux,'addParallax')>0) {
			$addjs[]='<script defer src="'.minifier('components/parallax/parallax.js').$refresh.'"></script>';
		}
		// FULLBACKGROUND : Detect Full Background
		if(substr_count($flux,'addFullBack')>0) {
			if(in_array('initSFC',$initCptCSS)===true) {
				$addInitCptCSS[]='components/fullbackground/fullbackground.css';
			}else{
				$addCptCSS[]='components/fullbackground/fullbackground.css';
			}
			$addjs[]='<script defer src="'.minifier('components/fullbackground/fullbackground.js').$refresh.'"></script>';
		}
		// SCROLL TO : Detect Scroll snippet
		if(substr_count($flux,'scrollTo')>0) {
			$addjs[]='<script defer src="'.minifier('components/scrollto/scrollto.js').$refresh.'"></script>';
		}
		// SELECT : Detect Select snippet
		if(substr_count($flux,'addSelect')>0 || substr_count($flux,'incSelect')>0) {
			if(in_array('initSelect',$initCptCSS)===true) {
				$addInitCptCSS[]='components/select/select.css';
			}else{
				$addCptCSS[]='components/select/select.css';
			}
			$addjs[]='<script defer src="'.minifier('components/select/lang/'.$_SESSION['lg'].'.js').$refresh.'"></script>';
			$addjs[]='<script defer src="'.minifier('components/select/select.js').$refresh.'"></script>';
		}
		// SORTANDFILTER : Detect Sort and Filtering snippet
		/* CORE */
		if(substr_count($flux,'addSFC')>0 || substr_count($flux,'incSFC')>0) {
			if(in_array('initSFC',$initCptCSS)===true) {
				$addInitCptCSS[]='components/sortandfilter/sortandfilter.css';
			}else{
				$addCptCSS[]='components/sortandfilter/sortandfilter.css';
			}
			$addjs[]='<script defer src="'.minifier('components/sortandfilter/sortandfilter.js').$refresh.'"></script>';
		}
		// SWIPER : Detect Swiper snippet
		if(substr_count($flux,'addSwiper')>0 || substr_count($flux,'incSwiper')>0) {
			if(in_array('initSwiper',$initCptCSS)===true) {
				$addInitCptCSS[]='components/swiper/swiper.css';
			}else{
				$addCptCSS[]='components/swiper/swiper.css';
			}
			$addjs[]='<script defer src="'.minifier('components/swiper/swiper.js').$refresh.'"></script>';
		}
		// TICKER : Detect Ticker snippet ******** > data-speed="", data-width="" with/or not data-margin
		if(substr_count($flux,'addTicker')>0 || substr_count($flux,'incTicker')>0) {
			if(in_array('initTicker',$initCptCSS)===true) {
				$addInitCptCSS[]='components/ticker/ticker.css';
			}else{
				$addCptCSS[]='components/ticker/ticker.css';
			}
			$addjs[]='<script defer src="'.minifier('components/ticker/ticker.js').$refresh.'"></script>';
		}
		// TIPS : Detect Tooltip snippet ******** > data-style="light,dark,red,blue,green,youtube,tipsy,bootstrap,tipped,jtools"
		if(substr_count($flux,'data-tip')>0 || substr_count($flux,'addValidate')>0) {
			$addCptCSS[]='components/tips/tips.css';
			$addjs[]='<script defer src="'.minifier('components/tips/tips.js').$refresh.'"></script>';
		}
		// VALIDATE FORM : Detect Validation snippet
		if(substr_count($flux,'addValidate')>0 || substr_count($flux,'incValidate')>0) {
			$addCptCSS[]='components/validate/validate.css';
			$addjs[]='<script defer src="'.minifier('components/validate/lang/'.$_SESSION['lg'].'.js').$refresh.'"></script>';
			$addjs[]='<script defer src="'.minifier('components/validate/validate.js').$refresh.'"></script>';
//$addjs[]='<script defer src="https://www.google.com/recaptcha/api.js"></script>';
		}
		// MAILER : Detect Simple Mail Form snippet
		if(substr_count($flux,'addMailer')>0) {
			$addjs[]='<script defer src="'.minifier('components/mailer/mailer.js').$refresh.'"></script>';
		}

	///// CSS HTML OUTPUT DETECTION AND PREPARATION

		// Add mandatory CSS common for all pages
		$arrayCSS=array('shared');

		// #ENV{page} CSS array creation : top inline and bottom external
		preg_match('|<!--ADDONSCSS\((.*)\)-->|',$flux,$matches);
		if(!empty($matches[1])>0 && $matches[1]!='') {
			$addons=explode('.',$matches[1]);
			$arrayCSS=array_merge($arrayCSS,$addons);
		}

		// Init variables for HTML output
		$addHTMLcss=$addHTMLcssInit='';
		
		// Add & Prepare custom LESS style VARS
		if(file_exists('assets/css/vars.css')) {
			$myVarArray=lessing('createarray',file_get_contents(minifier('assets/css/vars.css')),'');
		}else{
			$myVarArray[1][]='';
		}
		
		// Add STARTER CSS
		if(file_exists('components/starter/starter.css')) {
			$addHTMLcssInit.=cleaner(file_get_contents(minifier('components/starter/starter.css')),'css');
		}
		
		// Add FONTS CSS
		if(file_exists('assets/fonts/fonts.css')) {
			$addHTMLcssInit.=cleaner(file_get_contents(minifier('assets/fonts/fonts.css')),'css');
		}
		
	///// ADD PAGES INIT CSS
		
		// 1. INIT components CSS
		if(!empty($addInitCptCSS)>0) {
			foreach($addInitCptCSS as $value){
				$addHTMLcssInit.=file_get_contents(minifier($value));
			}
		}
		// 2. #ENV{page} IN <--INITCSS--> CSS
		if(!empty($matches[1])>0) {
			foreach($arrayCSS as $value){
				$CSSpath='';
				if(in_array($value,$initCptCSS)===true) {
					$CSSpath='assets/css/'.$value.'.css';
				}else{
					if(file_exists('assets/css/init/init_'.$value.'.css')) {
						$CSSpath='assets/css/init/init_'.$value.'.css';
					}
				}
				if($CSSpath!='') {
					$lessflow=lessing('lessing',file_get_contents(minifier($CSSpath)),$myVarArray);
				}
				$addHTMLcssInit.=cleaner($lessflow,'css');				
			}
		}
		
		// 3. Detect .fa- and Add : FONTAWSOME font
		if(file_exists('components/fontawesome/css/fontawesome.css') && substr_count($flux,'fa-')>0) {
			$globalfa=lessing('lessing',file_get_contents(minifier('components/fontawesome/css/fontawesome.css')),$myVarArray);
			$addHTMLcssInit.=cleaner($globalfa,'css');
			/*if(file_exists('components/fontawesome/css/brands.css') && substr_count($flux,'fab fa-')>0){
				$facontent=file_get_contents('components/fontawesome/css/brands.css');
				$fa=str_replace('../webfonts',$myVarArray[2][0].'/components/fontawesome/webfonts',$facontent);
				$addHTMLcssInit.=cleaner($fa,'css');
			}
			if(file_exists('components/fontawesome/css/light.css') && substr_count($flux,'fa-')>0){
				$facontent=file_get_contents('components/fontawesome/css/light.css');
				$fa=str_replace('../webfonts',$myVarArray[2][0].'/components/fontawesome/webfonts',$facontent);
				$addHTMLcssInit.=cleaner($fa,'css');
			}
			if(file_exists('components/fontawesome/css/regular.css') && substr_count($flux,'fa-')>0){
				$facontent=file_get_contents('components/fontawesome/css/regular.css');
				$fa=str_replace('../webfonts',$myVarArray[2][0].'/components/fontawesome/webfonts',$facontent);
				$addHTMLcssInit.=cleaner($fa,'css');
			}
			if(file_exists('components/fontawesome/css/solid.css') && substr_count($flux,'fa-')>0){
				$facontent=file_get_contents('components/fontawesome/css/solid.css');
				$fa=str_replace('../webfonts',$myVarArray[2][0].'/components/fontawesome/webfonts',$facontent);
				$addHTMLcssInit.=cleaner($fa,'css');
			}*/
		}
		
		// 4. Add COMPONENTS CSS
		if(!empty($addCptCSS)>0) {
			foreach($addCptCSS as $value) {
				$addHTMLcss.='
	<link rel="stylesheet" href="'.minifier($value).$refresh.'">';
			}
		}
		
		// 5. Add pages CSS
		if(!empty($matches[1])>0) {
			$lessflow='';
			$arrayCSS=array_diff($arrayCSS,$initCptCSS);
			foreach($arrayCSS as $value){
				if(file_exists('assets/css/'.$value.'.css')) {
					$lessflow.=lessing('lessing',file_get_contents(minifier('assets/css/'.$value.'.css')),$myVarArray);
				}
			}
			lessfile('flow/cache/vars_styles_end.css',$lessflow);
			$addHTMLcss.='
<link rel="stylesheet" href="'.minifier('flow/cache/vars_styles_end.css').$refresh.'">';
		}
		
		// 6. Add shared.js after JS modules and before custom pages JS
		if(file_exists('assets/js/shared.js')) {
			$addjs[]='<script defer src="'.minifier('assets/js/shared.js').$refresh.'"></script>';
		}
		
		//  7. JS custom files per page array creation
		preg_match('|<!--ADDONSJS\((.*)\)-->|',$flux,$addJSmatches);
		if(!empty($addJSmatches[1])>0) {
			 $addCptJS=explode('.',$addJSmatches[1]);
			 foreach($addCptJS as $value){
			 	if(file_exists('assets/js/'.$value.'.js')) {
					$addjs[]='<script defer src="'.minifier('assets/js/'.$value.'.js').$refresh.'"></script>';
				}
			 }
		}
	
	// Add CSS HTML output
		$flux = preg_replace('|<!--INITCSS\((.*)\)-->|', '<style>
			'.$addHTMLcssInit.'
	</style>', $flux);
		$flux = preg_replace('|<!--ADDONSCSS\((.*)\)-->|', '
			'.$addHTMLcss.'
	', $flux);
	
	// Add CSS JS output
        $addjs[]='<script defer src="'.minifier('components/starter/loaded.js').$refresh.'"></script>';
		$flux =  preg_replace('|<!--ADDONSJS\((.*)\)-->|', '
			'.implode('
	',$addjs).'
	', $flux);
		
	}

	return $flux;

} 

?>