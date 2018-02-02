<?php
// make sure to update the path to where you cloned the projects to!
require_once '../minify/src/Minify.php';
require_once '../minify/src/CSS.php';
require_once '../minify/src/JS.php';
require_once '../minify/src/Exception.php';
require_once '../minify/src/Converter.php';
include_once '../config.php';
use MatthiasMullie\Minify;

$minifierCSS = new Minify\CSS('');

// set the array for loading the relevant CSS files
$cssfiles = array(
    'bootstrap.min.css',
    'bootstrap-datepicker.min.css',
    'jquery.mCustomScrollbar.css',
    'latofonts.css',
    'latostyle.css',
    'kanitfonts.css',
    'font-awesome.min.css',
    'slicknav.min.css',
    'main.css');
foreach ($cssfiles as $cssfile) {
	$minifierCSS->add($cssfile);
}
/* required header info and character set */
header("Content-type: text/css;charset: UTF-8");
/* cache control to process */
header("Cache-Control: must-revalidate");
/* duration of cached content (1 hour) */
$offset = 60 * 60 ;
/* expiration header format */
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s",time() + $offset) . " GMT";
/* send cache expiration header to broswer */
header($ExpStr);
$verz="?ver=".VERSIONING;
$cached=DOMAIN;
if(CACHE!=""){
    $cached=CACHE;
}
$min= str_replace(array("{cache}","{ver}"), array($cached,$verz), $minifierCSS->minify());
echo $min;
?>
