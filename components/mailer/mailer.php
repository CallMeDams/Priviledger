<?

/* ############## */
/* MAILING SCRIPT */
/* ############## */

/*******/
/* INIT */
/*******/

	/* SESSION */
	/*---------*/
	session_set_cookie_params(0);
	session_start();

	/* PHPMAILER INCLUDE */
	/* ------------------ */
		// ... Import the PHPMailer class into the global namespace
		use PHPMailer\PHPMailer\PHPMailer;
	
	/* GOOGLE RECAPTCHA KEYS INCLUDE */
	/* ------------------ */
		// ... Import the Google recaptcha keys and mailing vars
		$conf = require('../../config.off.fg.php');


/******************/
/* FORM TREATMENT */
/******************/
if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']!='') {

	
	/* LANGUAGES */
	/*-----------*/
	$langAdmin = require('lang/'.$_POST[langAdmin].'_private.php');
	$langUser = require('lang/'.$_POST[langUser].'_public.php');

	
	/* HTML TO TEXT FUNCTION */
	/*-----------------------*/
	function htmltotext($html) {
		$html = strip_tags($html);
		$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
		return $html;
	}

	
	/* GOOGLE RECAPTCHA MANDATORY FILE */
	/*---------------------------------*/
	require('recaptcha.php');

	
	/* GOOGLE RECAPTCHA POST CHECKING */
	/*--------------------------------*/
	$recaptcha = $_POST['g-recaptcha-response'];
	$object = new Recaptcha();
	$response = $object->verifyResponse($recaptcha);
	if(isset($response['success']) and $response['success'] != true) {
		
		// ... Bad answer from Google
		echo $langAdmin[captcha_error].$response['error-codes'];
	
	}else{

		
		/* CUSTOM PLUGIN FUNCTIONS INCLUSION */
		/*-----------------------------------*/	
		require('../../plugins/certifie_conforme/cc_fonctions.php');
		
		
		/* CUSTOMIZATION #1 */
		/*------------------*/
		// ... Incluse website colors and others custom vars
		if(file_exists('../../assets/css/less.css')) {
			$myVarArray=lessing('createarray',file_get_contents('../../assets/css/less.css'),'');
		}else{
			$myVarArray[]='';
		}
		
	
	/*****************/
	/* ADMIN MESSAGE */
	/*****************/
	// ... Message preparation and customization for website owner(s)
	
		///// ADMIN DEFAULT TEMPLATE
		//** Get HTML admin default template source
		$template_html = lessing('lessing',file_get_contents('templates/default.html'),$myVarArray);
		
		///// "TO" INIT
		//**	Define which website owner will receive the email
		if($_POST[sendTo] && $_POST[sendTo]!='') {
			$convertTo = explode(',',$_POST[sendTo]);
			$sendTo = array(str_replace('+','@',strrev($convertTo[1])),$convertTo[0]);
		}else{
			$sendTo = array($conf[toMailDefault],$conf[toNameDefault]);
		}
		
		///// ADMIN SUBJECT
		//**	If no custom subject, use default one
		if($_POST[adminSubject] && $_POST[adminSubject] != '') {
			$subjectAdmin = trim($_POST[adminSubject]);
		}else{
			$subjectAdmin = lessing('lessing',$langAdmin[subjectDefault],$myVarArray);
		}
		
		///// ADMIN MAIL TITLE
		//**	If no custom title, use default one
		if($_POST[adminTitreMail] && $_POST[adminTitreMail] != '') {
			$titreAdmin = $_POST[adminTitreMail];
		}else{
			$titreAdmin = $langAdmin[titreDefault];
		}
		
		///// USER NAME FOR ADMIN
		//**	Who is writing to you
		if( ($_POST[Firstname] && $_POST[Firstname] != '') || ($_POST[Lastname] && $_POST[Lastname] != '') ) {
			$name = trim($_POST[Firstname].' '.$_POST[Lastname]).' ('.trim($_POST[Email]).')';
			$nameTo = trim($_POST[Firstname].' '.$_POST[Lastname]);
		}elseif($_POST[Name] && $_POST[Name] != '') {
			$name = trim($_POST[Name]).' ('.trim($_POST[Email]).')';
			$nameTo = trim($_POST[Name]);
		}else{
			$name = trim($_POST[Email]);
		}
		
		///// USER COMPANY & JOB FOR ADMIN
		//**	Optionnal company and job informations from sender
		if($_POST[Company] && $_POST[Company] != '') {
			if($_POST[Job] && $_POST[Job] != '') {
				$job = '<em>'.trim($_POST[Job]).'</em> ';
			}
			$company = ', '.$job.$langAdmin[companyText].' <span style="text-decoration:underline">'.strtoupper($_POST[Company]).'</span>,';
		}
		
		///// USER PHONE FOR ADMIN
		//**	Optionnal phone information from sender
		if($_POST[Phone] && $_POST[Phone] != '') {
			$phone = $langAdmin[phoneText].'<strong>'.$_POST[Phone].'</strong>, ';
			$telto = 'tel:'.trim(str_replace(array(' ','(',')'),array('','',''),$_POST[Phone]));
		}else{
			$template_html = preg_replace('|<PHONE>(.*)<PHONE>|isU','',$template_html);
		}
		
		// INTRO ADMIN
		//**	Mail introduction composed from user POST
		if($_POST[adminIntro] && $_POST[adminIntro] != '') {
			$introAdmin = $_POST[adminIntro];
		}else{
			$introAdmin = '<strong>'.$name.'</strong> '.$company.$langAdmin[endingIntro].$phone;
		}
        
        ///// ADMIN ALTERNATIVE TEXTAREA MESSAGE
		//**	
        if($_POST[altBothMessage] && $_POST[altBothMessage] != '') {
			$adminMessage = $_POST[altBothMessage];
		}elseif($_POST[altAdminMessage] && $_POST[altAdminMessage] != '') {
            $adminMessage = $_POST[altAdminMessage];
        }elseif($_POST[Message] && $_POST[Message] != '') {
            $adminMessage = nl2br($_POST[Message]);
        }else{
            $adminMessage = $langAdmin[noMessage];
        }
		
		///// FULL TEMPLATE PREPARATION
		//**	
		$mail_tags = array('@TITRE','@INTRO','@MESSAGE','@MAILLINK','@MAILTEXTLINK','@CALLLINK','@CALLTEXTLINK','<PHONE>');
		$mail_tags_replacement = array($titreAdmin,$introAdmin,$adminMessage,'mailto:'.$_POST[Email],$langAdmin[maillinktext],$telto,$langAdmin[calllinktext],'');
		$template_html = str_replace($mail_tags,$mail_tags_replacement,$template_html);

	/* USER MESSAGE
		- Message preparation and customization for website user(s) if confirmation activated
	*/
		if($_POST[userConfirm] && $_POST[userConfirm]='oui') {
			
            ///// USER DEFAULT TEMPLATE
            //** Get HTML template source
			$template_html_user = lessing('lessing',file_get_contents('templates/for_user.html'),$myVarArray);
			
            ///// USER SUBJECT
			//**	
			if($_POST[userSubject] && $_POST[userSubject] != '') {
				$subjectUser = trim($_POST[userSubject]);
			}else{
				$subjectUser = lessing('lessing',$langUser[subjectDefault],$myVarArray);
			}
			
            ///// USER MAIL TITLE
			//**	
			if($_POST[userTitreMail] && $_POST[userTitreMail] != '') {
				$titreUser = $_POST[userTitreMail];
			}else{
				$titreUser = $langUser[titreDefault];
			}
            
            ///// INTRO USER
			//**	
			if($_POST[userIntro] && $_POST[userIntro] != '') {
				$introUser = $_POST[userIntro];
			}else{
				$introUser = $langUser[introDefault];
			}
            
            ///// USER ALTERNATIVE TEXTAREA MESSAGE
			//**	
            if($_POST[altBothMessage] && $_POST[altBothMessage] != '') {
				$userMessage = $_POST[altBothMessage];
			}elseif($_POST[altUserMessage] && $_POST[altUserMessage] != '') {
                $userMessage = $_POST[altUserMessage];
            }elseif($_POST[Message] && $_POST[Message] != '') {
                $userMessage = nl2br($_POST[Message]);
            }else{
                $userMessage = $langAdmin[noMessage];
            }
            
			///// FULL TEMPLATE PREPARATION
			//**	
			$mail_tags_user = array('@TITRE','@INTRO','@MESSAGE','@FINAL');
			$mail_tags_replacement_user = array($titreUser,str_replace('DATE',date("Y-m-d H:i:s"),$introUser),$userMessage,lessing('lessing',$langUser[finalDefault],$myVarArray));
			$template_html_user = str_replace($mail_tags_user,$mail_tags_replacement_user,$template_html_user);
		}


		/* *************** */
		/* MAILER FUNCTION */
		/* *************** */
		require $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';
		function sendTheEmail($from,$to,$subject,$message,$success,$error) {
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = 'ssl0.ovh.net';
			$mail->Port = 587;
			$mail->SMTPAuth = true;
			$mail->Username = 'postmaster@maison-sur-marne.com';
			$mail->Password = 'q$00@aTL_rk-k9OQ';
			$mail->setFrom($from[0], utf8_decode($from[1]));
			$mail->addAddress($to[0], utf8_decode($to[1]));
			$mail->addBCC('webmaster@certifie-conforme.com', 'MSM [X]');
			$mail->Subject = utf8_decode($subject);
			$mail->msgHTML(utf8_decode($message));
			//$mail->addAttachment('images/phpmailer_mini.png');
			if (!$mail->send()) {
				return 'N '.$error.'<br>Mailer Error: ' . $mail->ErrorInfo;
			} else {
				return 'Y '.$success;
			}
		}

		/* *************** */
		/* SENDING MESSAGE */
		/* *************** */

		// SENDING TO ADMIN

		if($_POST[userConfirm] && $_POST[userConfirm]=='oui') {
			$checkAdminSend = sendTheEmail(array($_POST[Email],$nameTo),$sendTo,$subjectAdmin,$template_html,$langAdmin[mail_sent],$langAdmin[mail_not_sent]);
			if(substr($checkAdminSend,0,1)=='Y'){
				echo sendTheEmail($sendTo,array($_POST[Email],$nameTo),$subjectUser,$template_html_user,$langAdmin[mail_sent],$langAdmin[mail_not_sent]);
			}else{
				echo $checkAdminSend;
			}
		}else{
			echo sendTheEmail(array($_POST[Email],$nameTo),$sendTo,$subjectAdmin,$template_html,$langAdmin[mail_sent],$langAdmin[mail_not_sent]);
		}

	}	

}else{ 

	header('HTTP/1.1 403 Forbidden');
	header("Location: ".$conf[url_site]."/403");
	exit;

}

?>