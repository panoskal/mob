<?php
ob_start();
include_once 'config.php';

$ver="?ver=".VERSIONING;
$cache=DOMAIN;
if(CACHE!=""){
    $cache=CACHE;
}
global $database;
$pathInfo="";
if(isset($_GET["path"])){
    $pathInfo=$_GET["path"];
}
$temp=explode("/",$pathInfo);
$mPageSlug="";
$lang=DEFAULT_LANG;

if(is_array($temp)&&!empty($temp)){
    if(isset($temp[0])&&!empty($temp[0])){
        $mPageSlug=$temp[0];

    } else {
        $mPageSlug='home';
    }
}
if(isset($_GET["lang"])&&!empty($_GET["lang"])){//lang is set
    $pos = strpos(ALL_LANG, $_GET["lang"]);
    if ($pos != false) {
      $lang=$_GET["lang"];
    }
}
$_SESSION['lang'] = $lang;
include_once 'templates/lang/'.$lang.'.php';
$menu=Menu::getmenuUltimateForm();
//echo '<pre>';
//print_r($menu);
//echo '</pre>';
//die();
$meta_description="";
$meta_keywords="";



if(ONEPAGE=="true"){
    $pages=Page::getAllPagesOrdered($lang);
    $meta_description = defined('GENERAL_DESCRIPTION') ? GENERAL_DESCRIPTION : "";
    $meta_keywords = defined('GENERAL_KEYWORDS') ? GENERAL_KEYWORDS : "";
}else{
//    if($mPageSlug!=='winners'){
        $pages = Page::checkForDublicatePage($mPageSlug,$lang) ;
        $meta_description = $pages->meta_description;
        $meta_keywords = $pages->meta_keywords;
//    }
}
$winners=Winner::getAllWinners();



?>
    <!doctype html>
    <html class="no-js" lang="">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <?php

        if(ONEPAGE=="true"){
            if (defined('CONFIGURATION_TITLE')) {
                echo '<title>' . CONFIGURATION_TITLE . '</title>';
            } else {
                echo '<title></title>';
            }
        } else {
            $req_path = $_REQUEST['path'];
            if ($req_path == '') {
                $title_tag = 'home';
            } else {
                $page_by_slug = Page::getSinglePageTltleBySlug($req_path);
                if (!empty($page_by_slug) && is_object($page_by_slug)) {
                    $title_tag = $page_by_slug->title;
                }
            }
            if (isset($title_tag)) {
                if (defined('CONFIGURATION_TITLE')) {
                    echo '<title>' . CONFIGURATION_TITLE . ' - ' . $title_tag . '</title>';
                } else {
                    echo '<title>' . $title_tag . '</title>';
                }
            } else {
                echo '<title></title>';
            }
        }
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?PHP echo $cache;?>/css/automin.css.php<?PHP echo $ver;?>">
        <meta name="description" content="<?php echo $meta_description?>" />
        <meta name="keywords" content="<?php echo $meta_keywords?>">
        <!--[if lt IE 9]>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
                <script>window.html5 || document.write(\'<script src="js/vendor/html5shiv.js"><\/script>\')</script>
        <![endif]-->
    </head>

    <body class="<?php if (isset($req_path)) {echo ($req_path == 'Home')?'main':preg_replace('/[0-9_]+/', '', $req_path);} ?>">
        <?php if ($session->is_logged_in()) { ?>
        <div class="gotoadmin"><a href="adminpanel"><i class="fa fa-cog" aria-hidden="true"></i></a></div>
        <?php } ?>
    <?php
        include_once 'templates/menu.php';
        include_once 'templates/skeleton.php';

        ?>


    <script  type="text/javascript" src="<?php echo $cache;?>/js/automin.js.php<?PHP echo $ver;?>"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=<?PHP echo $_SESSION['lang'];?>'></script>
    <?php
    if (defined('GANALYTICS') && GANALYTICS!='') {
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GANALYTICS; ?>"></script>
    <script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '<?php echo GANALYTICS; ?>');
    </script>
    <?php  } ?>


    </body>
    </html>
    <?php ob_end_flush(); ?>
