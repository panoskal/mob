 <?php
   $scrollancore="";
   $href="";
   if(ONEPAGE=="true"){
       $scrollancore='rel="relativeanchor"';
       $href="#";
   }
?>
<!--
<div class="header">
  <div class="menu">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar-top">
      <div class="container">
         Brand and toggle get grouped for better mobile display
        <div class="navbar-header">
-->
<!--
<div class="header">
  <div class="menu">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar-top">
      <div class="container">
         Brand and toggle get grouped for better mobile display
        <div class="navbar-header">
                <div class="container">
                    <ul class="main-menu" id="menu">
                    <?php
//                        for($i=0;$i<count($menu);$i++){
//                            $class="";
//                            if(ONEPAGE=="false"){
//                                if($menu[$i]['slug']==$mPageSlug){
//                                    $class="active";
//                                }else{
//                                  $class="";
//                                }
//                            }
//                            echo '<li ><a href="'.$href.$menu[$i]['slug'].'"  '.$scrollancore.' >'.$menu[$i]['title'].'</a></li>';
//                        }
//                        $class="";
//                        if(ONEPAGE=="false"){
//                            $class="";
//                        }
                        ?>
                    </ul>
                </div>
            </div>
        </header>
    </div>
</section>
-->
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
                                        <li class="<? echo $class?>" class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $menuval->slug;?><span class="caret"></span></a>
<?php
                                    }else{
                                         echo '<li ><a href="'.$href.$menuval->slug.'"  '.$scrollancore.' >'.$menuval->title.'</a>';
                                    }
                                    var_dump($menuval->title);
                                    if(isset($children_array[$menuval->title])){
                                        var_dump($children_array[$menuval->title]);
?>
                                            <ul class="dropdown-menu">
<?php
                                        foreach($children_array[$menuval->title] as $r=>$submenu){
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
//                        for($i=0;$i<count($menu);$i++){
//                            $class="";
//                            if(ONEPAGE=="false"){
//                                if($menu[$i]['slug']==$mPageSlug){
//                                    $class="active";
//                                }else{
//                                  $class="";
//                                }
//                            }
//                            echo '<li ><a href="'.$href.$menu[$i]['slug'].'"  '.$scrollancore.' >'.$menu[$i]['title'].'</a></li>';
//                        }
//                        $class="";
//                        if(ONEPAGE=="false"){
//                            $class="";
//                        }
                        ?>
<!--
                        <li class="active"><a href="main" title="Trang chủ">Trang chủ</a></li>
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Giải thưởng <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="prizes">Lộc Vàng Xuân Mới</a></li>
                                <li><a href="prizes?phase=1">Rinh Lộc Phát Tài</a></li>
                                <li><a href="prizes?phase=2">Rinh Lộc Phát Tài 2</a></li>
                                <li><a href="prizes?phase=3">Rinh Lộc Phát Tài 3</a></li>
                                <li><a href="prizes?phase=4">Rinh Lộc Phát Tài 4</a></li>
                                <li><a href="prizes?phase=5">Rinh Lộc Phát Tài 5</a></li>
                                <li><a href="prizes?phase=6">Rinh Lộc Phát Tài 6</a></li>
                            </ul>
                        </li>
                        <li><a href="participation" title="Cách thức tham gia">Cách thức tham gia</a></li>
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Điều kiện &amp; Thể lệ <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="terms?phase=4">Rinh Lộc Phát Tài 4</a></li>
                                <li><a href="terms?phase=5">Rinh Lộc Phát Tài 5</a></li>
                                <li><a href="terms?phase=6">Rinh Lộc Phát Tài 6</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Danh sách khách hàng nhận giải <span class="caret"></span></a>
                            <ul class="dropdown-menu">

                                <li><a href="winners?phase=1">Rinh Lộc Phát Tài</a></li>
                                <li><a href="winners?phase=2">Rinh Lộc Phát Tài 2</a></li>
                                <li><a href="winners?phase=3">Rinh Lộc Phát Tài 3</a></li>
                                <li><a href="winners?phase=4">Rinh Lộc Phát Tài 4</a></li>
                                <li><a href="winners">Rinh Lộc Phát Tài 5</a></li>
                                <li><a href="winners?phase=5">Rinh Lộc Phát Tài 6</a></li>
                            </ul>
                        </li>
                        <li><a href="login" title="Tra cứu điểm số">Tra cứu điểm số</a></li>
-->


                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    </div>
</div>
