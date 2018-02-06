<?php
include_once '../config.php';
//$lang=DEFAULT_LANG;
include_once '../templates/lang/'.$_SESSION['lang'].'.php';
// load app



header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// load config
if(isset($_REQUEST['msisdn'])){
		$_REQUEST['msisdn'] = trim(cleanInput($_REQUEST['msisdn']));
		// if number has a leading + trim it
		if (substr(($_REQUEST['msisdn']), 0, 1)== '+'){
			$_REQUEST['msisdn'] = substr($_REQUEST['msisdn'], 1);
		}
		// if number has a leading zero trim it
		if (substr(($_REQUEST['msisdn']), 0, 1)== '0'){
			$_REQUEST['msisdn'] = substr($_REQUEST['msisdn'], 1);
		}
		// if number is without a prefix, apply it here
		if (substr(($_REQUEST['msisdn']), 0, strlen($prefix))!= $prefix){
			$_REQUEST['msisdn'] = $prefix.$_REQUEST['msisdn'];
		}
		if ($_REQUEST['ajax_action']=='login') {
			if (!is_numeric($_REQUEST['msisdn'])) {
				$jsondata = array("status"=>"fail", "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	  <strong>'.ALERT_WARNING.'</strong>'.LOGIN_NOT_VALID_CREDENTIALS.'</div>');
				echo json_encode($jsondata);
			} else if ($_REQUEST['vercode'] != $_SESSION['security_code']) {
				$jsondata = array("status"=>"fail", "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	  <strong>'.ALERT_WARNING.'</strong>'.LOGIN_NOT_VALID_CAPTCHA.'</div>');
				echo json_encode($jsondata);
			} else {
				if (inforequest($_REQUEST['msisdn'])){
					$jsondata = array("status"=>"success", "greeting" => LOGIN_WELCOME_BACK.$_SESSION['user_MSISDN'], "message"=>'<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	  <strong>'.LOGIN_THANKS.'</strong></div>');
					echo json_encode($jsondata);
				} else {
					$jsondata = array("status"=>"fail", "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	  <strong>'.ALERT_WARNING.'</strong>'.$_SESSION['error_message'].'</div>');
					echo json_encode($jsondata);
				}
			}
		}
}
