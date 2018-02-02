 <?php
   $scrollancore="";
   $href="";
   if(ONEPAGE=="true"){
       $scrollancore='rel="relativeanchor"';
       $href="#";
   }
?>
<section class="menu-section">
    <div class="menu-float">
        <header class="header">
            <div class="top-menu-wrapper">
                <div class="container">
                </div>
            </div>
            <div class="main-menu-wrapper">
                <div class="container">
                    <ul class="main-menu" id="menu">
                    <?php
                        for($i=0;$i<count($menu);$i++){
                            $class="";
                            if(ONEPAGE=="false"){
                                if($menu[$i]['slug']==$mPageSlug){
                                    $class="active";
                                }else{
                                  $class="";
                                }
                            }
                            echo '<li ><a href="'.$href.$menu[$i]['slug'].'"  '.$scrollancore.' >'.strtoupper($menu[$i]['title']).'</a></li>';
                        }
                        $class="";
                        if(ONEPAGE=="false"){
                            $class="";
                        }
                        ?>
                    </ul>
                    <!--
        <div class="lang">
            <a href="?lang=pt" class="langs"><img src="img/pt.png" alt=""></a>
            <a href="?lang=en" class="langs"><img src="img/en.png" alt=""></a>
        </div>
-->
                </div>
            </div>
        </header>
    </div>
</section>
