<?php

// ---- UPSTREAM ACCESS - DEFINE CONSTANTS --- //
global $database;
$result = $database->showTables();
if (!empty($result)) {
    $getConfigurationArr = Configuration::getAllConfiguration();
    if (!empty($getConfigurationArr) && is_array($getConfigurationArr)) {
        foreach($getConfigurationArr as $value) {
            define($value->keyname, $value->value);
        }
    }
}

function cleanInput($input){
// bad things to remove
    $search=array(
        '@<script[^>] * ? > . * ?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );
    $output=preg_replace($search, '', $input);
    return $output;
}

function redirect_to($redirection_page){
    $url='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
    header('Location: '.$redirection_page);
    exit;
}

function greekToGreeklishAndReverse($string,$type="greek") {
    $greeklish=array("A","B","G","D","E","Z","H","8","I","K","L","M","N","3","O","P","R","S","T","Y","F","X","C","W","a","b","g","d","e","z","h","8","i","k","l","m","n","3","o","p","r","s","s","t","y","f","x","c","w","a","e","h","i","o","y","w","A","E","H","I","O","Y","W");
    $greek=array("Α","Β","Γ","Δ","Ε","Ζ","Η","Θ","Ι","Κ","Λ","Μ","Ν","Ξ","Ο","Π","Ρ","Σ","Τ","Υ","Φ","Χ","Ψ","Ω","α","β","γ","δ","ε","ζ","η","θ","ι","κ","λ","μ","ν","ξ","ο","π","ρ","σ","ς","τ","υ","φ","χ","ψ","ω","ά","έ","ή","ί","ό","ύ","ώ","Ά","Έ","Ή","Ί","Ό","Ύ","Ώ");
   if($type=="greek"){
    $string =str_replace($greeklish,$greek, $string);
   }else{
    $string =str_replace($greek,$greeklish, $string);
   }

    return $string;
}

//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}


// get URL
function URLCONNECT($pathtocall, $type='get', $data='', $credentials=false, $headers=false, $useproxy=false) {
	$curlheaders = array();

	$urltocall = str_replace(' ','%20',$pathtocall);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	if($useproxy && !empty($_ENV['FW_PROXY'])){
		curl_setopt($curl, CURLOPT_PROXY, $_ENV['FW_PROXY']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	}

	if ($headers){
		foreach ($headers as $headerKey=>$headerVal) {
			$curlheaders []=$headerKey.': '.$headerVal;
		}
	}

	if($type=='get'){
		curl_setopt($curl, CURLOPT_HTTPGET, true);
    }

    if($type=='post'){
		$data=http_build_query($data);
		$curlheaders []='Content-Type: application/x-www-form-urlencoded;charset=UTF-8';
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

	if($type=='json'){
		$data = json_encode($data);
		$curlheaders []= 'Content-Type: application/json';
		$curlheaders []= 'Content-Length: '.strlen($data);
		curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

	if (!empty($credentials)){
		curl_setopt($curl, CURLOPT_USERPWD, $credentials['user'].':'.$credentials['pass']);
	}

	$debugplaincurlObj = Configuration::getConfigurationByKey('DEBUGPLAINCURL');
    if (!empty($debugplaincurlObj) && is_object($debugplaincurlObj)) {
        if ($debugplaincurlObj->value == 'true') {
			$timezoneObj = Configuration::getConfigurationByKey('TIMEZONE');
			if (!empty($timezoneObj) && is_object($timezoneObj)) {
				$timezone = $timezoneObj->value;
			} else {
				$timezone = 'Europe/Athens';
			}
			date_default_timezone_set($timezone);
			$date=date("Y-m-d, H:i:s").", ".$timezone;		
            $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/logs/files/curl_raw.lg', 'a');
			fwrite($fp, PHP_EOL.PHP_EOL.PHP_EOL.$date.PHP_EOL.PHP_EOL);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_STDERR, $fp);
        }
	}

	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($curl, CURLOPT_URL, $urltocall);

	if(count($curlheaders)>0) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheaders);
	}

	$urlresponse = curl_exec($curl);
	if (curl_errno($curl)) {//error on the curl function
		$_SESSION['error_message'] =  curl_error($curl);
		$urlresponse = '{"status":700,"result" :"'.curl_error($curl).'"}';
		$logtext = 'called: '.$urltocall.' error: '.curl_error($curl);
    } else {
		$logtext = 'called: '.$urltocall.' got: '.$urlresponse;
    }

    curl_close($curl);
	debuggerlog ('curl_results', $logtext);
	//set from json object
	//$urlresponse = mb_convert_encoding($urlresponse, "UTF-8", "auto");
	//$urlresponse = htmlspecialchars_decode(utf8_decode(htmlentities($urlresponse, ENT_COMPAT, 'utf-8', false)));
	$responsejson = json_decode($urlresponse);
	return($responsejson);
}

// check if is valid JSON string
function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

// clean data retrieved from database for safe output
function cleanOutput($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

//get user's IP
function getClientIP (){
	if(!empty($_SERVER['HTTP_TRUE_CLIENT_IP'])){
		$client=$_SERVER['HTTP_TRUE_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$client=$_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$client=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$client=$_SERVER['REMOTE_ADDR'];
	}
	if(isset($client)){
		//check for multiple values
		$client=str_replace(' ', '', $client);
		$exploded=explode(',', $client);
		$client=$exploded[0];
	}
	return $client;
}

//
// add to debugger
//
class DB extends SQLite3{
    function __construct($file){
        try{
            $this->open($file);
        }catch(Exception $ex){

        }
    }
}
function debuggerlog($priority, $text){
    $debuggingObj = Configuration::getConfigurationByKey('DEBUGGING');
    if (!empty($debuggingObj) && is_object($debuggingObj)) {
        $debugging = $debuggingObj->value;
    }
	if ($debugging=='true'){
        $timezoneObj = Configuration::getConfigurationByKey('TIMEZONE');
        if (!empty($timezoneObj) && is_object($timezoneObj)) {
            $timezone = $timezoneObj->value;
        } else {
            $timezone = 'Europe/Athens';
        }
		date_default_timezone_set($timezone);
		$date=date("Y-m-d, H:i:s").", ".$timezone;
		$url="http: //".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$server=$_SERVER['SERVER_ADDR'];
		$client = getClientIP ();
		$headerstxt='';
		foreach(getallheaders() as $name=> $value){
			$headerstxt .= "$name: $value".PHP_EOL;
		}
		//tempelis remove qmarks
		$headerstxt=str_replace(';', '|', $headerstxt);
        $thDB=$_SERVER['DOCUMENT_ROOT'].'/logs/files/file'.date("Y-m").'.sqlite';
		$dbase=new DB($thDB);
        if(!$dbase||!is_writable($thDB)){ // error on the SQLLITE file, use old file logs
			$log=PHP_EOL."Request IP (host) - (client): ".$server.' - '.$client.PHP_EOL.
					"Request timestamp: ".$date.PHP_EOL.
					"Request Page: ".$url.PHP_EOL.PHP_EOL;
			if($text!=''){
				$log .= "Logged data follows: ".PHP_EOL.$text.PHP_EOL.PHP_EOL;
			}
			$log .= "User Headers follow: ".PHP_EOL.$headerstxt;
			$log .= PHP_EOL.PHP_EOL."-------------------------".PHP_EOL.PHP_EOL;
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/files/'.$priority.'.lg', $log, FILE_APPEND);
			$log_db="Request timestamp: ".$date.PHP_EOL.
					"error :".$dbase->lastErrorMsg().PHP_EOL.
					"database path:".$thDB.PHP_EOL."-------------------------".PHP_EOL.PHP_EOL;
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/files/sql_error.lg', $log_db, FILE_APPEND);
		} else {
			//tempelis remove quotes
			$text=str_replace("'", " ", $text);
			$insert_query="INSERT INTO ".$priority." (timestamp, page, ip_host, ip_client, log_data, headers) "
					."VALUES ('".$date."','".$url."','".$server."','".$client."','".mb_convert_encoding($text, "UTF-8")."','".$headerstxt."')";
			$table_check='create table if not exists '.$priority.' (id  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
								timestamp  CHAR(255) NOT NULL ,
								page  CHAR(255) NOT NULL ,
								ip_host  CHAR(255) NOT NULL ,
								ip_client  CHAR(255) NOT NULL ,
								log_data  TEXT NOT NULL ,
								headers TEXT null)';
				$dbase->exec($table_check);
				$dbase->exec($insert_query);
		}
	}
}

// start getDraws
function getDraws () {
	$pathtocall = DRAW_URL.'draws/';
	$credentials = array('user'=>DRAW_USER, 'pass'=>DRAW_PASS);
	$response = URLCONNECT($pathtocall, 'get', false, $credentials);
	//$response = json_decode('{"status":0,"description":"SUCCESS","data":{"draws":[{"link":"http://10.168.30.85:8080/draw-server-rest/integration/ds/1.0/draws/302","id":302,"name":"Final_Draw_EXT_2","type":"Tickets","startDateTime":"2017-02-15T00:00:00-02:00","endDateTime":"2017-11-12T00:00:00-02:00","executionDateTime":"2017-11-11T00:00:00-02:00","status":"IN_PROGRESS","winningToken":715587591,"drawDefinitionId":7,"drawDefinitionTag":"Tag"},{"link":"http://10.168.30.85:8080/draw-server-rest/integration/ds/1.0/draws/501","id":501,"name":"BTA_monitored","type":"Random","startDateTime":"2017-10-16T00:00:00-02:00","endDateTime":"2030-10-10T00:00:00-03:00","executionDateTime":"2030-10-09T00:00:00-03:00","status":"ACTIVE","winningToken":null,"drawDefinitionId":9,"drawDefinitionTag":"bta"}]}}');
	//$response = json_decode('{"status":0,"description":"SUCCESS","data":{"draws":[]}}');
	if ($response) {// got smth back
		if (isset($response->data->draws)){
			if (count ($response->data->draws)>0) {
				$i=0;
				foreach ($response->data->draws as $draw) {
					if (($draw->status!= 'IN_PROGRESS') || (strpos($draw->name, CAMPAIGNID) === false)) { //remove all other draws that are active and not for this campaign
						unset($response->data->draws[$i]);
					} else {
						//make replacements
						$search = array('_', CAMPAIGNID);
						$replace = array(' ', '');
						$response->data->draws[$i]->name = trim(str_replace($search, $replace, $response->data->draws[$i]->name));
					}
					$i++;
				}
			}
			return ($response->data);
		} else {
			$_SESSION['error_message'] = $response->result;
		}
	} else {
		$_SESSION['error_message'] = 'Unable to get any response/URL call';
		return false;
	}
}// end getDraws

//start login
function login ($username, $password) {
	$pathtocall = str_replace("{MSISDN}", $username, LOGINURL);//apply MSISDN on URL
	$pathtocall = str_replace("{PASSWORD}", $password, $pathtocall);//apply password on URL
	$pathtocall = str_replace("{CAMPAIGNID}", CAMPAIGNID, $pathtocall);//apply campaignId on URL
	$headers=array();
	$headers = array("X-U-Channel"=>"WEB");
	$response = URLCONNECT($pathtocall, 'get', false, false, $headers);
	//$response =  json_decode('{"result":"SUCCESS","description":"Operation finished successfully"}');
	//$response =  json_decode('{"result":"ACCESS_DENIED","description":"access denied for user"}');
	if ($response) {// got smth back
		if ($response->result=='SUCCESS') {
			//get userId
			$pathtocall = str_replace("{MSISDN}", $username, USERIDURL);//apply MSISDN on URL
			$response = URLCONNECT($pathtocall);
			//$response = json_decode('{"id":133,"group":"BLACKLIST","status":"ACTIVE","createdAt":1449247675.332000000,"clients":null}');
			//$response = json_decode('{"result":"INVALID_USER_ID","description":"invalid user id"}');
			if (isset($response->id)) {// got smth back
				$_SESSION['userAll'] = $response;
				$_SESSION['userId'] = $response->id;
				$_SESSION['user_MSISDN'] = $username;
				return true;
			} else {
				$_SESSION['error_message'] = $response->result;
				return false;
			}
		} else {
			$_SESSION['error_message'] = $response->result;
		}
	} else {
		$_SESSION['error_message'] = 'Unable to get any response/URL call';
		return false;
	}
}// end login

// start getTickets
function getTickets ($drawid) {
	//override
	// return null;
	// return '5424, 24244';
	$pathtocall = DRAW_URL.'draws/'.$drawid.'/userTickets?userId='.$_SESSION['userId'];
	$credentials = array('user'=>DRAW_USER, 'pass'=>DRAW_PASS);
	$response = URLCONNECT($pathtocall, 'get', false, $credentials);
	$drawtickets = '';
	if ($response) {// got smth back
		if (isset($response->data->seriesTickets) && count ($response->data->seriesTickets)>0) {
			$drawtickets = implode (', ', $response->data->seriesTickets[0]);
		}
		return ($drawtickets);
	} else {
		$_SESSION['error_message'] = 'Unable to get any response/URL call';
		return false;
	}
}// end getTickets

//reCaptcha verify
function verifyReCaptcha($grecaptcharesponse){
	// override
	// return true;
    if(defined('RECAPTCHA_KEY')&&!empty($grecaptcharesponse)){
		$client = getClientIP ();
        $postdata=array('secret'=>RECAPTCHA_KEY, 'response'=>$grecaptcharesponse, 'remoteip'=>$client);
		debuggerlog ('recaptcha_post', json_encode($postdata));
		$recaptcharesult=URLCONNECT('https://www.google.com/recaptcha/api/siteverify', 'post', $postdata, false, false, true);
        if(isset($recaptcharesult->success)&&$recaptcharesult->success==true){
            return true;
        }
    }
    return false;
}



//mobifone

function WSDLTBDCONNECT() {
	global $error_codes;
	//initialize soap
	require_once('php/nusoap.php');
	// login to web service
	$client = new nusoap_client(WSDLURL, true);
	$client->setCredentials(WSDLUSER,WSDLPASS);
	// FOR DEBUGGING PURPOSES ONLY
	addtodebugger ('connect to service', $client->request, $client->response);
	$err = $client->getError();
	if ($err){
		// set response to generic error
		$_SESSION['error_message'] =  $error_codes[0][0];
		return false;
	 } else {
		 return $client;
	 }
}

//start inforequest
function inforequest ($msisdn) {
	global $error_codes;

	$client = WSDLTBDCONNECT();
	if ($client) {
		//set params for login
		$method="info";
		$param = array("infoRequest"=> array("msisdn" => $msisdn, "operatorId" => '1', "locale" => 'vi_VN'));
		$result = $client->call($method, $param);
		// FOR DEBUGGING PURPOSES ONLY
		addtodebugger ('infoRequest', $client->request, $client->response);
		if ($client->fault) { // Web service return error, get error code and set it to verbal
			$_SESSION['error_message'] = $error_codes[$result["detail"]["sub-code"]][0];
			return false;
		} else {
			$err = $client->getError();
			if ($err) {// set response to generic error
				//$_SESSION['error_message'] =  $error_codes[0][0].$err;
				//return false;
				return true; // no data present after HTTP headers fix
			} else {// everything went ok, set user profile to session variables
				return true;
			}
		}
	} else {
		return false;
	}
}// end inforequest

function addtodebugger ($function, $request_str, $response_str) {
	if (!isset($_SESSION['debugger'])){
		$_SESSION['debugger']='';
	}
	$_SESSION['debugger'] .=
	 '<div>'
	 .'<h2>Upstream '.$function.' Request</h2>'
	 .'<pre>'.htmlspecialchars($request_str, ENT_QUOTES).'</pre>'
	 .'<h2>Upstream '.$function.' Response</h2>'
	 .'<pre>' . htmlspecialchars($response_str, ENT_QUOTES) . '</pre>'
	 .'</div><br />';
}
// end add to debugger
