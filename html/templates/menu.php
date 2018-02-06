 <?php
   $scrollancore="";
   $href="";
   if(ONEPAGE=="true"){
       $scrollancore='rel="relativeanchor"';
       $href="#";
   }
?>
<div class="header">
    <div class="menu">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topmenu"> <span class="sr-only">Nhấn vào cho thực đơn</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    <a class="navbar-brand" href="http://www.mobifone.vn" target="_blank"><img src="http://locvang.mobifone.vn/images/mobifone-logo.png" alt="mobifone logo"></a> </div>
                <div class="collapse navbar-collapse" id="topmenu">
                    <ul class="nav navbar-nav">
                    <?php
                        if (!empty($menu) && is_array($menu)) {
                            $children_array=array();
                            foreach($menu as $sort=>$menuval) {
                                if(!empty($menuval->has_parent)){
                                    $children_array[$menuval->has_parent][]=$menuval;
                                }else{
                                    if ($menuval->slug == $mPageSlug) {
                                        $class="active";
                                    }else{
                                        $class="";
                                    }
                                    if($menuval->is_not_link==1){
?>
                                        <li class="<?php echo $class?>" class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $menuval->slug;?><span class="caret"></span></a>
<?php
                                    }else{
                                         echo '<li class="'.$class.'"><a href="'.$href.$menuval->slug.'"  '.$scrollancore.' >'.$menuval->title.'</a>';
                                    }
                                    if(isset($children_array[$menuval->slug])){
//                                        var_dump($children_array[$menuval->title]);
?>
                                            <ul class="dropdown-menu">
<?php
                                        foreach($children_array[$menuval->slug] as $r=>$submenu){
?>
                                               <li><a href="<?php echo $href.$submenu->slug; ?>"><?php echo $submenu->title; ?></a></li>

<?php
                                        }
?>
                                            </ul>
<?php
                                    }
?>
                                        </li>
<?php
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    </div>
</div>
