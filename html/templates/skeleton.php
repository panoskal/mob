<?php
if(ONEPAGE=="true"){
    if($pages==false){
        include_once 'templates/notFound.php';
    }else{
         for($i=0;$i<count($pages);$i++){
            $content=$pages[$i]['content'];
            $pagesslug=$pages[$i]['slug'];
            include_once 'templates/pages/'.$pages[$i]['slug'].'.php';
         }
    }
}else{
    if($pages==false){//den uparxei tetoia selida emfanhse thn error page
        include_once 'templates/notFound.php';
    }else{//uparxei h selida emfanhse th
        $content=$pages->content;
        $pagesslug=$pages->slug;
        include_once 'templates/pages/'.$pages->slug.'.php';
    }
}
