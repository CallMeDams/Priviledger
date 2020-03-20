<?php

/* ####################### */
/* CUSTOM PLUGIN FUNCTIONS */
/* ####################### */

/************/
/* SECURITY */
/************/
if (!defined("_ECRIRE_INC_VERSION")) return;

/*******/
/* SECTION NUMBER (BAD WAY - FIND BETTER ONE) */
/*******/
function sectionCtrl($side,$ida,$idr) {
	if(($side=='article' || $side=='article_edit') && isset($ida) && $ida!='') {
		$result=sql_fetch(sql_select('id_rubrique',"spip_articles","id_article=".$ida));
		$this_section=$result['id_rubrique'];
	}elseif(($side=='rubrique' || $side=='rubrique_edit') && isset($idr) && $idr!='') {
		$this_section=$idr;
	}else{
		$this_section='no';
	}
	return $this_section;
}

/************************/
/* FILE MANAGER SORTING */
/************************/
function fmTreatment($type,$url) {
	$preview_1='<img src="components/filemanager/images/$1.png" height="50" alt="" />';
	switch($type) {
		case 'png':
		case 'gif':
		case 'jpg':
		case 'jpeg':
			return array('image',0,'addon_full_background','data-url="'.$url.'"'); break;
		case 'mp3':
		case 'wav':
			return array('audio',str_replace('$1','audio',$preview_1),''); break;
		case 'mov':
		case 'avi':
		case 'mp4':
		case 'mpg':
		case 'mpeg':
			return array('audio',str_replace('$1','video',$preview_1),''); break;
		case 'doc':
		case 'docx':
			return array('office',str_replace('$1','doc',$preview_1),''); break;
		case 'xls':
		case 'xlsx':
			return array('office',str_replace('$1','xls',$preview_1),''); break;
		case 'ppt':
		case 'pptx':
			return array('office',str_replace('$1','ppt',$preview_1),''); break;
		case 'pdf':
			return array('acrobat',str_replace('$1','pdf',$preview_1),''); break;
		case 'ai':
			return array('document',str_replace('$1','ai',$preview_1),''); break;
		case 'psd':
			return array('document',str_replace('$1','psd',$preview_1),''); break;
		case 'indd':
			return array('document',str_replace('$1','indd',$preview_1),''); break;
		case 'eps':
			return array('document',str_replace('$1','eps',$preview_1),''); break;
		case 'txt':
			return array('document',str_replace('$1','txt',$preview_1),''); break;
		default :
			return array('file',str_replace('$1','file',$preview_1),''); break;
	}
}

/*******/
/* RETURN session_id(); */
/*******/
function sid($flux) {
    return session_id();
    //return '1284b97d2203f43b5e8170154415b093';
}

/******************/
/* RETURN USER IP */
/******************/
function get_ip() {
	$ipaddress='';
    if($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/***********/
/* CLEANER */
/***********/
function cleaner($text,$what) {
    if($what==='css') {
        $text=str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$text)))));
    }elseif($what==='accents') {
        $text=htmlentities($text, ENT_NOQUOTES, 'utf-8');
        $text=preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $text);
        $text=preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $text);
        $text=preg_replace('#&[^;]+;#', '', $text);
    }else{
        if(substr_count($text, '</p>')==1){
            $text=preg_replace('|<p>(.*)<\/p>|isU','$1',$text);
            $text=preg_replace('|<span class="block"(.*)>(.*)</span>|isU','$2',$text);
        }
    }
    return $text;
}

/*********************************/
/* CUSTOM LESS FUNCTIONS FOR CSS */
/*********************************/
function lessing($todo,$text,$varray) {
    if($todo=='createarray') {
        preg_match_all('|<\@(.*)\|(.*)>|isU',$text,$lessvars,PREG_PATTERN_ORDER);
        return $lessvars;
    }else{
        $i=0;
        foreach($varray[1] as $var){
            $text=str_replace('@'.$var,$varray[2][$i],$text);
            $i++;
        }    
        return $text;
    }
}
function lessfile($path,$content) {
    if(!file_exists($path)){
        fopen($path, 'w');
    }
    if(is_writable($path)) {
        if($fp=fopen($path, 'w')){
            fwrite($fp,$content);
            fclose($fp);
        }
    }
}

/***************************/
/* LANGUAGE HTML GENERATOR */
/***************************/
function htmlang($lang) {
	switch($lang) {
		case 'en':
			return 'en<span>glish</span>'; break;
		default :
			return 'fr<span>an&ccedil;ais</span>'; break;
	}
}

/**************/
/* CRYPT HTML */
/**************/
function crypt_html($off,$text) {
	$cuts=rand(5,10); $sections=str_split($text,$cuts); $id = 'e'.rand(1,999999999);
	foreach($sections as $key=>$value) {
		$vars[]="var txt".$key."='".$value."'";
		$strs[]='txt'.$key;
	}
	$script=implode('; ',$vars);
	$script.='; document.getElementById("'.$id.'").innerHTML='.implode('+',$strs).';';
	$script='<script>'.$script.'</script>';
	return '<span id="'.$id.'">[javascript protected html]</span>
			'.$script;
}

function encdec($flux,$action,$string,$secret_key,$secret_iv) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    // Hash
    $key = hash('sha256', $secret_key);
    // IV - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if( $action == 'enc' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'dec' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/**************************/
/* MAKE GOOGLE MAP SCRIPT */
/**************************/
function make_gmap() {
	return '';
}

?>