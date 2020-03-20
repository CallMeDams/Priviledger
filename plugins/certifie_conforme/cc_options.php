<?php

/********************/
/* SECURITY */
/********************/
if (!defined("_ECRIRE_INC_VERSION")) return;

/*******************************************/
/* VARIABLES AND OPTIONS ONLY FOR FRONTEND */
/*******************************************/
if(substr_count($_SERVER['REQUEST_URI'],_DIR_RESTREINT_ABS)==0) {

    // $_SESSION['pre_env'] is an array and at the end of the file, this array will be converted in GETs variables
    // This variables will be or can be used in the templates with #ENV{var_name}

    /**
     * SESSION INIT
     */
        session_set_cookie_params(0);
        session_start();

    /**
     * PHP INI UPDATES
     */
        ini_set('arg_separator.output','&amp;');
        ini_set("url_rewriter.tags","a=href,area=href,frame=src,iframe=src,input=src");

    /**
     * DEVICE & OS DETECTION (Desktop, Tablet or Phone | iOs or Android)
     * - Put the result in session array 'cc_env'
     */
        require_once('imports/php/device_detect.php');
        // DEVICE
        $detect=new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');
// Device second security variable PRE_ENV variable creation
$_SESSION['pre_env']['realdevice']=$deviceType;
        if($deviceType=='tablet') { $deviceType='phone'; }
// Device PRE_ENV variable creation
$_SESSION['pre_env']['device']=$deviceType;
            // -> If url got a "device" (GET) variable, "device" is forced
            // -----------------------------------------------------------
            	if(isset($_GET['device'])) {
// Forced Device PRE_ENV variable creation
$_SESSION['pre_env']['device']=$_GET['device'];
            	}
        // OS
        $detectOS=new Mobile_Detect;
        $deviceOS = ($detectOS->isiOS() ? 'ios' : 'android');
// Device PRE_ENV variable creation
$_SESSION['pre_env']['os']=$deviceOS;


    /**
     * LANGUAGE MANAGEMENT
     * - Default language is the language choosed in the backoffice CMS configuration
     */
		if(!isset($_SESSION['lg']) || empty($_SESSION['lg'])){
			$_SESSION['lg']=$GLOBALS['meta']['langue_site'];
		}
			// -> Check if actual language is used or forced CMS language
			// ----------------------------------------------------------
				if(in_array($_SESSION['lg'],explode(',',$GLOBALS['meta']['langues_utilisees']))===false) {
					$_SESSION['lg']=$GLOBALS['meta']['langue_site'];
				}
			// -> If url got a "lang" (GET) variable, "lang" is forced over all automatic choices
			// ----------------------------------------------------------------------------------
				if(isset($_GET['lang']) && !empty($_GET['lang'])) {
					$_SESSION['getlg']=true;
					$_SESSION['lg']=$_GET['lang'];
				}
			// -> If there was no choice by GET variable
			// -----------------------------------------
				if(!isset($_SESSION['getlg']) || $_SESSION['getlg']===false){

					// -> Geolocated language by IP if option is activated with mes_options.php
					// ------------------------------------------------------------------------
						if(isset($_SESSION['geolang']) && $_SESSION['geolang']===true && !$_SESSION['geolangtrue']){
							// This will contain the ip of the request
							$ip=$_SERVER['REMOTE_ADDR'];
							// We will retrieve quickly with the file_get_contents
							$dataArray=file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip);
							// Convert to JSON format
							$dataArray=json_decode($dataArray,true);
							// Create countryCode with 2 letters
							$countryCode=strtolower($dataArray['geoplugin_countryCode']);
							// CMS hack for China because China country code is ZH and not CN
							$countryCode=str_replace('cn','zh',$countryCode);
							// Check if IP language is one of the used language in the website
							if(in_array($countryCode,explode(',',$GLOBALS['meta']['langues_utilisees']))===true) {
								$_SESSION['lg']=$countryCode;
							}else{
								// If not, we use the main website language
								$_SESSION['lg']=$GLOBALS['meta']['langue_site'];
							}
							// Define language
							$_SESSION['geolangtrue']===true;
						}
					// -> Delete geolocated language if not activated
					// ----------------------------------------------
						if(isset($_SESSION['geolang']) && $_SESSION['geolang']===false){
							unset($_SESSION['geolang']);
							unset($_SESSION['geolangtrue']);
						}
					// -> If "Forced language" variable is set in mes_options.php : this variable overwrite everything
					// -----------------------------------------------------------------------------------------------
						if(isset($_SESSION['forcedlang']) && $_SESSION['forcedlang']!=false){
							$_SESSION['lg']=$_SESSION['forcedlang'];
						}else{
							// If not forced, the session is delete
								unset($_SESSION['forcedlang']);
							// CMS language init
								if($_SESSION['geolangtrue']===false){
									$_SESSION['lg']=$GLOBALS['meta']['langue_site'];
								}
						}

				}
// Lang PRE_ENV variable creation
$_SESSION['pre_env']['langsite']=$GLOBALS['meta']['langue_site'];
$_SESSION['pre_env']['lang']=$_SESSION['lg'];


    /**
     * DEVELOPPEMENT & MAINTENANCE SPECIAL ACCESS
     * - Default language is the language choosed in the backoffice CMS configuration
     */
        if(!$_SESSION['apo'] || (isset($_GET['apo']) && $_GET['apo']==false)) {
            $_SESSION['apo']=false;
        }
        if(isset($_GET['apo']) && $_GET['apo']==true) {
            $_SESSION['apo']=true;
        }
// APO PRE_ENV variable creation
$_SESSION['pre_env']['apo']=$_SESSION['apo'];


    /**
     * CHECK Certifié Conforme IPs TO HIDE SOME CMS HTML PIECES OR SCRIPTS (Google stats for exemple)
     */
        $ips[]=gethostbyname('area61.gotdns.ch');
        $ips[]=gethostbyname('gate71.gotdns.ch');
        if(in_array($_SERVER["REMOTE_ADDR"],$ips)===true || '#SESSION{statut}'==='0minirezo') {
// Google Stats blocked for Webmaster PRE_ENV variable creation
$_SESSION['pre_env']['analytics']=false;
        }

// 
    /**
     * $_SESSION['pre_env'] CONVERTED TO #ENV VARS (GET)
     */
        if(isset($_SESSION['pre_env']) && !empty($_SESSION['pre_env']) && is_array($_SESSION['pre_env'])) {
            foreach($_SESSION['pre_env'] as $cle=>$valeur) {
                $_GET[$cle]=$valeur;
            }
            unset($_SESSION['pre_env']);
        }
}

?>