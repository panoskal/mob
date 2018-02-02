<?php
ob_start();
//include_once 'admin_formhandlers.php';
include_once '../config.php';
?>
    <!doctype html>
    <html class="no-js" lang="">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>WEB ADMIN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" href="../css/bootstrap.css" rel="stylesheet">
        <link type="text/css" href="../css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link type="text/css" href="../css/jquery.mCustomScrollbar.css" rel="stylesheet">
        <link type="text/css" href="../css/latofonts.css" rel="stylesheet">
        <link type="text/css" href="../css/latostyle.css" rel="stylesheet">
        <link type="text/css" href="../css/font-awesome.min.css" rel="stylesheet">
        <link type="text/css" href="../css/summernote-bs4.css" rel="stylesheet">
        <link type="text/css" href="../css/summernote.css" rel="stylesheet">
        <link type="text/css" href="../css/jquery-ui.min.css" rel="stylesheet">
        <link type="text/css" href="../css/main_admin.css" rel="stylesheet">
        <!-- include codemirror (codemirror.css, codemirror.js, xml.js, formatting.js) for summernote-->
        <!--[if lt IE 9]>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
                <script>window.html5 || document.write(\'<script src="js/vendor/html5shiv.js"><\/script>\')</script>
        <![endif]-->
    </head>

    <body>
    <?php
        if(isset($_REQUEST["logout"])){
            $session->logout();
        }
        if ($session->is_logged_in() || (isset($_GET['install']) && $_GET['install']=='getinstpa!2%%') ) {
        ?>
        <div class='container-fluid admin-panel-page'>
            <div class="row first-row">
               <div class="top-bar-info col-sm-12">
                    <div class="logout-user">
                        <a href=".."><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Homepage</a>&nbsp;
                        <a href='?logout' class='logout'><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                    </div>
                </div>
                <div class="main-menu-sidebar col-sm-3">
                   <div class="welcome-user"><a href="?" ><i class="fa fa-user-circle-o" aria-hidden="true"></i><span class="name-user">Welcome Mr. Admin</span></a></div>
                    <ul class="menu-items">
                        <li class='clearfix'><a href="?data=page" class="d-field-page"><i class="fa fa-file-text special-icon" aria-hidden="true"></i><span class="item-name">Pages</span><i class="fa fa-chevron-right arrow-right" aria-hidden="true"></i></a></li>
                        <li class='clearfix'><a href="?data=menu" class="d-field-page"><i class="fa fa-bookmark special-icon" aria-hidden="true"></i><span class="item-name">Menu</span><i class="fa fa-chevron-right arrow-right" aria-hidden="true"></i></a></li>
                        <li class='clearfix'><a href="?data=winners" class="d-field-page"><i class="fa fa-trophy special-icon" aria-hidden="true"></i><span class="item-name">Winners</span><i class="fa fa-chevron-right arrow-right" aria-hidden="true"></i></a></li>
                        <li class='clearfix'><a href="?data=configuration" class="d-field-page"><i class="fa fa-cog special-icon" aria-hidden="true"></i><span class="item-name">Configuration</span><i class="fa fa-chevron-right arrow-right" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
                <div class="main-page-content col-sm-offset-3 col-sm-9">
                <?php

                    if(isset($_REQUEST["data"])&&
                       ($_REQUEST["data"]=='page'||
                       $_REQUEST["data"]=='menu'||
                       $_REQUEST["data"]=='winners'||
                       $_REQUEST["data"]=='configuration'||
                       $_REQUEST["data"]=='urlsCheck')){
                            include_once 'template-parts/'.$_REQUEST["data"].'.php';
                    } else {
                        $result = $database->showTables();
                        if (!empty($result) && $session->is_logged_in()) {
                            include_once 'template-parts/home.php';
                        } else {
                            include_once 'template-parts/install.php';
                        }

                    }

                    ?>
                </div>
            </div>
        </div>
        <?php
        } else {
            echo "<div class='container-fluid admin-panel-page login-page'>";
            include_once 'template-parts/login.php';
            echo "</div>";
        }
        ?>
        <script type="text/javascript" src="../js/vendor/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="../js/vendor/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="../js/vendor/jquery.mCustomScrollbar.js"></script>
        <script type="text/javascript" src="../js/vendor/bootstrap.min.js"></script>
        <script type="text/javascript" src="../js/vendor/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="../js/vendor/summernote-bs4.min.js"></script>
        <script type="text/javascript" src="../js/vendor/summernote.min.js"></script>
        <script type="text/javascript" src="../js/vendor/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/main_admin.js"></script>
        <script type="text/javascript" src="../js/mainCopy.js"></script>

    </body>

    </html>
    <?php ob_end_flush(); ?>
