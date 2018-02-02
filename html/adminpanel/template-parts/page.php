<?php
$allLanguages=Configuration::getConfigurationByKey("ALL_LANG");
$lang=explode(",",$allLanguages->value);
$vers=Configuration::getConfigurationByKey("VERSIONING");
$cashes=Configuration::getConfigurationByKey("DOMAIN");
$ver="?ver=".$vers->value;
$cache=$cashes->value;

if(isset($_REQUEST["add"])){
    $header_title='Add New '.ucwords($_REQUEST["data"]);
    $info='In this tab are listed all the pages that the admin created. Inactive pages are shown with red bold letters.';
?>
<div class="small-info"><?php echo $info;?></div>
<div class="header"><?php echo $header_title;?></div>
<?php
    if(isset($_REQUEST['action'])&&$_REQUEST['action']=='save_new'){
        if((isset($_REQUEST['lang'])&&$_REQUEST['lang']!='none')&&
          (isset($_REQUEST['title'])&&$_REQUEST['title']!='')&&
          (isset($_REQUEST['slug'])&&$_REQUEST['slug']!='')&&
          (isset($_REQUEST['content'])&&$_REQUEST['content']!='')){
            $pagearray=array();
            $pagearray['slug']=Sanitize::processString($_REQUEST['slug']);
            $pagearray['title']=Sanitize::processString($_REQUEST['title']);
            $pagearray['content']=str_replace( array($cache,$ver),array("{cache}","{ver}"), $_REQUEST['content']);
            $pagearray['meta_description']=Sanitize::processString($_REQUEST['meta_description']);
            $pagearray['meta_keywords']=Sanitize::processString($_REQUEST['meta_keywords']);
            $pagearray['lang']=Sanitize::processString($_REQUEST['lang']);
            $pagearray['enabled']="0";
            if(isset($_REQUEST['enabled'])){
                $pagearray['enabled']="1";
            }
            $alreadyExists=Page::checkForDublicatePage($pagearray['slug'],$pagearray['lang']);
            if($alreadyExists==false){
                $insert=Page::insertPage($pagearray);
                unset($_REQUEST);
            }
        }else{
            unset($_REQUEST['action']);
        }
    }
//ADD NEW PAGE
?>
    <div class="enclosed-table">
<?php
if(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray)){
?>
        <span class='requred-fields'>*A page with the slug <u>'<?php echo $pagearray['slug'];?>'</u> for the language <u>'<?php echo $pagearray['lang'];?>'</u> already exists. Please change one or both fields to continue.</span>
<?php
}
if(!(isset($_REQUEST['action'])&&$_REQUEST['action']=='save_new')||(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray))){
?>
        <form method="post" action="?data=page&add"  id="targetform">
<?php
}
?>
            <input type="hidden"  name="action"  value="save_new">
            <input type="hidden"  name="content"  id="content" value="">
            <div class="form-line clearfix">
                <div class="form-check col-sm-9">
                    <label for="lang">Language<?php echo ((isset($_REQUEST['lang'])&&$_REQUEST['lang']=="none")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                    <select class="form-control form-control-sm general-dropdown" name="lang">
                        <option value="none">Select Page Language</option>
<?php
    if(is_array($lang)&&!empty($lang)){
        foreach($lang as $i=>$lng_short){
?>
                        <option value='<?php echo $lng_short;?>' <?php echo ((isset($_REQUEST['lang'])&&$_REQUEST['lang']==$lng_short)?"selected":"");?>><?php echo $lng_short;?></option>
<?php
        }
    }
?>
            </select>
                </div>
                 <div class="form-check col-sm-3">
                    <label for="enabled" class="form-check-label checkbox-level">
                      <input type="checkbox" class="form-check-input"  name="enabled" <?php echo (isset($_REQUEST['enabled'])?"checked":"");?>>
                      Enable page
                    </label>
                </div>
            </div>
            <div class="form-line clearfix">
                <div class="form-group col-sm-4">
                    <label for="title">Page Title<?php echo ((isset($_REQUEST['title'])&&$_REQUEST['title']=="")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                    <input type="text" class="form-control"  name="title" <?php echo (isset($_REQUEST['title'])?"value='".$_REQUEST['title']."'":"");?>>
                </div>
                <div class="form-group col-sm-4">
                    <label for="slug">Slug<?php echo ((isset($_REQUEST['slug'])&&$_REQUEST['slug']=="")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                    <input type="text" class="form-control"  name="slug" <?php echo (isset($_REQUEST['slug'])?"value='".$_REQUEST['slug']."'":"");?>>
                </div>
            </div>
            <div class="form-group col-sm-12 summernote-area">
                <label for="content">Content</label>
<!--                <textarea class="form-control"  id="edit-content" name="content" rows="10"> <?php //echo (isset($_REQUEST['content'])?$_REQUEST['content']:"");?></textarea>-->
           <div id="edit-content" ><?php echo (isset($_REQUEST['content'])?$_REQUEST['content']:"");?></div>
            </div>
            <div class="form-line clearfix">
                <div class="form-group col-sm-12">
                    <label for="meta_description">Page Description</label>
                    <textarea class="form-control"  name="meta_description" rows="3"><?php echo (isset($_REQUEST['meta_description'])?$_REQUEST['meta_description']:"");?></textarea>
                </div>
            </div>
            <div class="form-line clearfix">
                <div class="form-group col-sm-12">
                    <label for="meta_keywords">Page Keywords</label>
                    <small  class="form-text text-muted">seperate with commas</small>
                    <textarea class="form-control"  name="meta_keywords"  rows="3"><?php echo (isset($_REQUEST['meta_description'])?$_REQUEST['meta_description']:"");?></textarea>
                </div>
            </div>
<?php
if(!(isset($_REQUEST['action'])&&$_REQUEST['action']=='save_new')||(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray))){
?>
            <div class="form-group col-sm-12">
                <button type="button" id="submitForm"  class="button-faux"><i class="fa fa-pencil-square-o small-pad" aria-hidden="true"></i>Save Page</button>
            </div>
        </form>
<?php
}
?>
    </div>
<?php
}else if(isset($_REQUEST["edit"])&&!empty($_REQUEST["edit"])){
    $request_id=$_REQUEST["edit"];
    $page=Page::getSinglePageById($request_id);
    $info='In this tab the admin can edit the specific page.';
    if($page){
       $header_title='Edit '.$page->title.' '.ucwords($_REQUEST["data"]);
       if(isset($_REQUEST['action'])&&$_REQUEST['action']=='update_p'){
            if((isset($_REQUEST['lang'])&&$_REQUEST['lang']!='none')&&
              (isset($_REQUEST['title'])&&$_REQUEST['title']!='')&&
              (isset($_REQUEST['slug'])&&$_REQUEST['slug']!='')&&
              (isset($_REQUEST['content'])&&$_REQUEST['content']!='')){
                $pagearray=array();
                $pagearray['slug']=Sanitize::processString($_REQUEST['slug']);
                $pagearray['title']=Sanitize::processString($_REQUEST['title']);
                $pagearray['content']=str_replace( array($cache,$ver),array("{cache}","{ver}"), $_REQUEST['content']);
                $pagearray['meta_description']=Sanitize::processString($_REQUEST['meta_description']);
                $pagearray['meta_keywords']=Sanitize::processString($_REQUEST['meta_keywords']);
                $pagearray['lang']=Sanitize::processString($_REQUEST['lang']);
                $pagearray['enabled']="0";
                if(isset($_REQUEST['enabled'])){
                    $pagearray['enabled']="1";
                }
                $alreadyExists=Page::checkForDublicatePage($pagearray['slug'],$pagearray['lang']);
                if(($alreadyExists!=false&&$alreadyExists->id==$page->id)||$alreadyExists==false){
                    $update=Page::updatePage($pagearray, $request_id);
                    $page=Page::getSinglePageById($request_id);
                    unset($_REQUEST);
                }
            }else{
                unset($_REQUEST['action']);
            }
        }
    }else{
        $header_title='Error  '.ucwords($_REQUEST["data"]);
    }

?>
    <div class="small-info"><?php echo $info;?></div>
    <div class="header"><?php echo $header_title;?></div>
    <div class="enclosed-table">
<?php
    if($page==false){
?>
        <span class='requred-fields'>The page you are trying to edit does not exist. It was either deleted or never created...</span>
        <div class="buttons-line">
            <a href="?data=page" class="button-faux"><i class="fa fa-arrow-left small-pad" aria-hidden="true"></i>Go Back to Page List</a>
        </div>
<?php
    }else{
        if(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray)&&$alreadyExists->id!=$request_id){
?>
            <span class='requred-fields'>*A page with the slug <u>'<?php echo $pagearray['slug'];?>'</u> for the language <u>'<?php echo $pagearray['lang'];?>'</u> already exists. Please change one or both fields to continue.</span>
<?php
        }
        if(!(isset($_REQUEST['action'])&&$_REQUEST['action']=='save_new')||(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray))){
?>
            <form  method="post" action="?data=page&edit=<?php echo $page->id;?>" id="targetform">
<?php
        }
?>
                <input type="hidden"  name="action"  value="update_p">
                <input type="hidden"  name="content"  id="content" value="">
                <input type="hidden"  name="page_id"  value="<?php echo $request_id;?>">
                <div class="form-line clearfix">
                    <div class="form-check col-sm-9">
                        <label for="lang">Language<?php echo ((isset($_REQUEST['lang'])&&$_REQUEST['lang']=="none")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                        <select class="form-control form-control-sm general-dropdown" name="lang">
                            <option value="none">Select Page Language</option>
<?php
            foreach($lang as $i=>$lng_short){
?>
                            <option value='<?php echo $lng_short;?>' <?php echo ((isset($page->lang)&&$page->lang==$lng_short)?"selected":"");?>><?php echo $lng_short;?></option>
<?php
            }
?>
                </select>
                    </div>
                     <div class="form-check col-sm-3">
                        <label for="enabled" class="form-check-label checkbox-level">
                          <input type="checkbox" class="form-check-input"  name="enabled" <?php echo ((isset($page->enabled)&&$page->enabled==1)?"checked":"");?>>
                          Enable page
                        </label>
                    </div>
                </div>
                <div class="form-line clearfix">
                    <div class="form-group col-sm-4">
                        <label for="title">Page Title<?php echo ((isset($_REQUEST['title'])&&$_REQUEST['title']=="")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                        <input type="text" class="form-control"  name="title" <?php echo (isset($page->title)?"value='".$page->title."'":"");?>>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="slug">Slug<?php echo ((isset($_REQUEST['slug'])&&$_REQUEST['slug']=="")?"<span class='requred-fields'>*Requred Field</span>":"");?></label>
                        <input type="text" class="form-control"  name="slug" <?php echo (isset($page->slug)?"value='".$page->slug."'":"");?>>
                    </div>
                </div>
                <div class="form-group col-sm-12 summernote-area">
                    <label for="content">Content</label>
<!--                    <textarea class="form-control"  id="edit-content" name="content" rows="10"> <?php //echo (isset($page->content)?$page->content:"");?></textarea>-->
                    <div id="edit-content" ><?php echo (isset($page->content)?str_replace(array("{cache}","{ver}"), array($cache,$ver), $page->content):"");?></div>
                </div>
                <div class="form-line clearfix">
                    <div class="form-group col-sm-12">
                        <label for="meta_description">Page Description</label>
                        <textarea class="form-control"  name="meta_description" rows="3"><?php echo (isset($page->meta_description)?$page->meta_description:"");?></textarea>
                    </div>
                </div>
                <div class="form-line clearfix">
                    <div class="form-group col-sm-12">
                        <label for="meta_keywords">Page Keywords</label>
                        <small  class="form-text text-muted">seperate with commas</small>
                        <textarea class="form-control"  name="meta_keywords"  rows="3"><?php echo (isset($page->meta_description)?$page->meta_description:"");?></textarea>
                    </div>
                </div>
<?php
        if(!(isset($_REQUEST['action'])&&$_REQUEST['action']=='update_p')||(isset($alreadyExists)&&$alreadyExists!=false&&isset($pagearray))){
?>
                <div class="form-group col-sm-12">
                    <button type="button" id="submitForm" class="button-faux"><i class="fa fa-pencil-square-o small-pad" aria-hidden="true"></i>Save Page</button>
                </div>
            </form>
<?php
        }
    }
?>
    </div>
<?php
    }else{

        $thisPageUsingMenuItem=false;
        if(isset($_REQUEST["delete"])&&!empty($_REQUEST["delete"])){
            $to_delete_id=Sanitize::processInt($_REQUEST["delete"]);
            $data=Page::getSinglePageById($to_delete_id);
            $menuItems=Menu::getAllMenuItems();
            $countThisPagesSlugsUsage=Page::fetchPageSlagCount($data->slug);
            if(!empty($menuItems)){
                foreach($menuItems as $i=>$menuObjects){
                    if($menuObjects->slug==$data->slug&&$countThisPagesSlugsUsage[0]['cou']==1){
                        $thisPageUsingMenuItem=true;
                        $to_be_deleted_lang_group=$data->lang;
                    }
                }
            }
            if($thisPageUsingMenuItem==false){
                if($data){
                    $to_be_deleted=$data->title;
                    $to_be_deleted_lang_group=$data->lang;
                    $deleted=Page::deletePage($to_delete_id);
                    unset($_REQUEST["delete"]);
                }
            }
        }
        $header_title=ucwords($_REQUEST["data"])."s list";
        $info='In this tab are listed all the pages that the admin created. Inactive pages are shown with red bold letters.';
?>
        <div class="small-info"><?php echo $info;?></div>
        <div class="header"><?php echo $header_title;?></div>
        <div class="buttons-line">
            <a href="?data=page&add" class="button-faux"><i class="fa fa-plus small-pad" aria-hidden="true"></i>Add Page</a>
        </div>
<?php
//SHOW ALL THE PAGES
        $requestedtable=$_REQUEST["data"];
        $header_title='Edit '.$_REQUEST["data"];
        $info='In this tab are listed all the pages that the admin created. Inactive pages are shown with red bold letters.';
        $lang="";
        $row="";
        $allPagesByLang=Page::getAllPagesByLang();
        if(isset($allPagesByLang)&&!empty($allPagesByLang)){
            foreach($allPagesByLang as $sort=>$item){
                 if($lang!=$item->lang){

        $row="";
                    ?>
        <div class="enclosed-table">
<?php
                    if(isset($to_be_deleted)&&isset($to_be_deleted_lang_group)&&$to_be_deleted_lang_group==$item->lang){
?>
            <span class='requred-fields'>The page <u>'<?php echo $to_be_deleted;?>'</u> was deleted successfully.</span>
<?php
                    }else if($thisPageUsingMenuItem&&$to_be_deleted_lang_group==$item->lang){
                                        ?>
            <span class='requred-fields'>There is e menu Item that is connected <b>Only</b> with this page. You have to either delete the menu Item first or create an other page to be connected with this item.</span>
<?php
                    }else if(isset($to_delete_id)&&!isset($to_be_deleted)&&$lang==""&&!$thisPageUsingMenuItem){
                ?>
            <span class='requred-fields'>No such page exists. It was either already deleted or never created...</span>
<?php
                    }
                     ?>
            <div class="page-element-row first-table-row clearfix">
               <div class="num-value col-sm-1">#No</div>
               <div class="page-title-value col-sm-3">Title</div>
               <div class="page-slug-value col-sm-2">Slug</div>
               <div class="page-enabled-value col-sm-2">Enabled</div>
               <div class="page-lang-value col-sm-2">Language</div>
               <div class="page-action-value col-sm-1">Edit</div>
               <div class="page-action-value col-sm-1">Delete</div>
           </div>
       <?php
                }
            ?>
            <div class="page-element-row clearfix <?php echo ($item->enabled=="1"?'':'disabled-page').' '.$row;?>">
               <div class="num-value col-sm-1"><?php echo ($sort+1);?></div>
               <div class="page-title-value col-sm-3"><?php echo $item->title;?></div>
               <div class="page-slug-value col-sm-2"><?php echo $item->slug;?></div>
               <div class="page-enabled-value col-sm-2"><?php echo ($item->enabled=="1"?'yes':'no');?></div>
               <div class="page-lang-value col-sm-2"><?php echo $item->lang;?></div>
               <div class="page-lang-value col-sm-1"><a class="link-center" href="?data=page&edit=<?php echo $item->id;?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>
               <div class="page-lang-value col-sm-1"><a class="link-center" href="?data=page&delete=<?php echo $item->id;?>"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
           </div>
<?php
              if($lang!=$item->lang){
?>
       </div>
<?php
                  $lang=$item->lang;
              }
              if($row==''){
                  $row='line-colored';
              }else{
                  $row="";
              }
         }
    }
}
?>
