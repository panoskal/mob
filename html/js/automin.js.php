<?php
// make sure to update the path to where you cloned the projects to!
require_once '../minify/src/Minify.php';
require_once '../minify/src/CSS.php';
require_once '../minify/src/JS.php';
require_once '../minify/src/Exception.php';
require_once '../minify/src/Converter.php';

use MatthiasMullie\Minify;
$minifierJS = new Minify\JS('');

// set the array for loading the relevant CSS files
$jsfiles = array(
    'vendor/jquery-3.2.1.min.js',
    'vendor/jquery.mousewheel.min.js',
    'vendor/jquery.mCustomScrollbar.js',
    'vendor/bootstrap.min.js',
    'vendor/bootstrap-datepicker.min.js',
    'jquery.slicknav.min.js',
    'main.js');
foreach ($jsfiles as $jsfile) {
	$minifierJS->add($jsfile);
}
///* required header info and character set */
//header("Content-type: application/javascript ;charset: UTF-8");
///* cache control to process */
//header("Cache-Control: must-revalidate");
///* duration of cached content (1 hour) */
//$offset = 60 * 60 ;
///* expiration header format */
//$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s",time() + $offset) . " GMT";
///* send cache expiration header to broswer */
//header($ExpStr);
echo $minifierJS->minify();
?>
