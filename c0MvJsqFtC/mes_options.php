<?

/*#############################*/
/* GLOBAL WEBSITE INIT OPTIONS */
/*#############################*/

/**
 * SESSION INIT
 */
    session_set_cookie_params(0);
    session_start();

/**
 * PAGE'S HTTP COMPLETE HEADERS
 * - 1 : hide complete headers
 * - 0 : show complete headers
 */
    $spip_header_silencieux=1;

/**
 * TOP OVERLAY CMS LINKS TO RECALCULATE THE PAGE OR TO GO TO PRIVATE AREA IF USER IS LOGGED
 * - true : hide links
 * - false : show links
 */
    $flag_preserver=true;

/**
 * FORCE SSL TO THE ADMIN
 * - Commented line with // : no SSL to the admin
 * - Uncommented line : force SSL
 */
    $_SERVER['SERVER_PORT']='443';

/**
 * ADD HTML CODE TO SHOW A SPLASH SCREEN IF THE PHONE
 * IS IN LANDSCAPE MODE TO ASK USER TO TURN THE DEVICE
 * - true : add splash screen
 * - false : no splash screen
 */
    $_GET['mandatory']=true;

/**
 * ADD GOOGLE ANALYTICS CODE
 * - true : add Google analytics script
 * - false : no splash screen
 */
     $_GET['analytics']=false;
     $_GET['idAnalytics']='';

/**
 * META ROBOTS
 * - false : noindex,nofollow
 */
    $_GET['robots']=true;

/**
 * REFRESH CACHE CSS & JS FILES
 * - if not forced, refresh css & js files on 1st, 15th and 28th 
 * - forced refresh, uncommented "Forced refresh" line 
 */
    if(date('j')==1 || date('j')==15 || date('j')==28){
        $_GET['refresh']=true;
    }else{
        $_GET['refresh']=false;
    }
    // -> Forced refresh
    $_GET['refresh']=true;

/**
 * LANGUAGE MANAGEMENT
 * - forcedlang : false : the "Forced language" will be delete
 * - forcedlang : 2 char. language code (fr, en, de...) to overwrite website main language
 * - geolang : true : activate language by ip or default
 * - geolang : false : deactivate language by ip
 */
    // -> Forced language
    // ------------------
    $_SESSION['forcedlang']=false;
    // -> Geo Language
    // ---------------
    $_SESSION['geolang']=false;

/**
 * CUSTOM OPTIONS & VARS
 */
 	if(substr_count($_SERVER['REQUEST_URI'],'BehindTheStage')==0){
		if(substr_count($_SERVER['HTTP_HOST'],'.cn')>0) {
			$_GET['site_root']='https://www.priviledger.cn';
            $_GET['idWT']='OK2-RWHnja4cbPmXtWnDH3LlbTodF-ysxl3fu6PU0uU';
            $_GET['idAnalytics']='UA-142566209-1';
            $_SESSION['lg']="zh";
		}elseif(substr_count($_SERVER['HTTP_HOST'],'.fr')>0) {
			$_GET['site_root']='https://www.priviledger.fr';
            $_GET['idWT']='BuVw13b5fSLbiN6zOGxdkVI2t2aC0CuEQq0Yn4pG204';
            $_GET['idAnalytics']='UA-142566209-2';
            $_SESSION['lg']="fr";
		}else{
			header('Status: 301 Moved Permanently', false, 301);
			header('Location: https://www.priviledger.fr');
			exit();
		}
	}
	$_GET['priville']='PARIS';

?>