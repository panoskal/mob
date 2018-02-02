<?php
include_once '../config.php';
//$lang=DEFAULT_LANG;
include_once '../templates/lang/'.$_SESSION['lang'].'.php';
$jsondata = array("status"=>"fail",  "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.CLOSE.'</span></button>
			  <strong>'.ALERT_WARNING.'</strong>'.WRONG_DATA_POSTED.'</div>');

if (isset($_POST['task']) && $_POST['task'] == 'checkcupons') {
    if (isset($_POST['ddd']) && isset($_POST['telefone']) && isset($_POST['senha'])&& isset($_POST['g-recaptcha-response']) && preg_match('/^[1-9][0-9]{9,15}$/', $_POST['ddd'].$_POST['telefone'])){
//    	check recaptcha
    	if (verifyReCaptcha($_POST['g-recaptcha-response'])){
    		$username = PREFIX.$_POST['ddd'].$_POST['telefone'];
    		$password = cleanInput($_POST['senha']);
    		if (login ($username, $password)) {
    			$usercoupons = getDraws ();
    			$html = "";
    			if (!$usercoupons) {// oops, error stop all
    				$jsondata = array("status"=>"fail",  "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
    			  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.CLOSE.'</span></button>
    			  <strong>'.ALERT_WARNING.'</strong>'.$_SESSION['error_message'].'</div>');
    			} else {// no error, continue
    				if (!isset($usercoupons->draws) || count($usercoupons->draws)==0) {// no draws
    					$jsondata = array("status"=>"success", "message"=>'<div class="alert alert-warning alert-dismissible" role="alert">
    			  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.CLOSE.'</span></button>
    			  <strong>'.ALERT_WARNING.'</strong>'.CUPONS_NO_DRAWS_FOUND.'</div>');
    				} else {
    					foreach ($usercoupons->draws as $draw) {
    						$html .= '<div class="row"><div class="col-xs-12 col-sm-5"><dl class="dl-horizontal">';
    						$drawtickets = getTickets ($draw->id);
    						$html .= '<dt>'.CUPONS_NAME.':</dt><dd><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> '.$draw->name.'</dd>';
    						$html .= '<dt>'.CUPONS_DATE_START.':</dt><dd><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> '.date('m-d-Y', strtotime($draw->startDateTime)) .'</dd>';
    						$html .= '<dt>'.CUPONS_DATE_END.':</dt><dd><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> '.date('m-d-Y', strtotime($draw->endDateTime)) .'</dd>';
    						$html .= '</dl></div>';
    						if ($drawtickets=='') {
    							$html .= '<div class="col-xs-12 col-sm-5">'.CUPONS_NO_TICKETS_FOUND.'</div>';
    						} else {
    							$html .= '<div class="col-xs-12 col-sm-5"><strong>'.CUPONS_CUPONS.':</strong> '.$drawtickets.'</div>';
    						}
    						$html .= '</div>';
    					}
    					$jsondata = array("status"=>"success",  "message"=>'<div class="tickets-result"> '.$html.'</div>');
    				}
    			}

    		} else {
    			$jsondata = array("status"=>"fail",  "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
    			  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.CLOSE.'</span></button>
    			  <strong>'.ALERT_WARNING.'</strong>'.$_SESSION['error_message'].'</div>');
    		}
    	} else {
    		$jsondata = array("status"=>"fail",  "message"=>'<div class="alert alert-danger alert-dismissible" role="alert">
    			  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.CLOSE.'</span></button>
    			  <strong>'.ALERT_WARNING.'</strong>'.WRONG_RECAPTCHA.'</div>');
    	}

    }
    echo json_encode($jsondata, true);
}
