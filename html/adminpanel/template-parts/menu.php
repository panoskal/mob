<?php
   //SHOW ALL THE PAGES
    $header_title='List '.ucwords($_REQUEST["data"]). " Items";
    $info='In this tab the admin set the pages that will be shown as menu items.';
    $data=Menu::getAllMenuItems();
    $slugsAll=Page::fetchAllAvailablePageSlags();
?>
   <div id="insertmenuModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div id="menuErrMsgs"></div>
            <div class="add-config-modal">
                <form id="insertMenuForm" action="admin_formhandlers.php" method="post">
                    <div class="form-group">
                        <label for="order">Page Slug</label>
                         <select class="form-control form-control-sm general-dropdown " name="slug" id="slug-drop">
                            <option value="none">Select slug</option>
<?php
    if(isset($slugsAll)&&!empty($slugsAll)){
        foreach($slugsAll as $i=>$a_slug){
            $found=false;
            if(isset($data)&&!empty($data)){
                foreach($data as $i=>$dataobj){
                    if($dataobj->slug== $a_slug['slug']){
                        $found=true;
                    }
                }
            }
            if(!$found){
                ?>
                            <option value='<?php echo $a_slug['slug'];?>'><?php echo $a_slug['slug'];?></option>
<?php
            }
        }
     }
?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                         <select class="form-control form-control-sm general-dropdown " name="order" id="order">
                            <option value="none">Select order</option>
<?php

    if(isset($slugsAll)&&(isset($data))&&(count($slugsAll)>count($data))){
        for($i=1;$i<(count($slugsAll)+1);$i++){
            $found=false;
            if(!empty($data)){
                foreach($data as $j=>$dataobj){
                    if($dataobj->order==$i){
                        $found=true;
                        break;
                    }
                }
            }
            if(!$found){
?>
                            <option value='<?php echo $i;?>'><?php echo $i;?></option>
<?php
            }
        }
    }
?>
                        </select>
                    </div>
                    <button type="submit" name="insertmenuBtn" id="insertmenuBtn" class="button-faux">Submit</button>
                    <input type="hidden" name="action" value="menuItemNEW">
                </form>
            </div>
        </div>

    </div>
</div>

    <div class="small-info"><?php echo $info;?></div>
    <div class="header"><?php echo $header_title;?></div>
    <div class="add-menu-item clearfix" id="add-menu-item">
            <button class="add-config-btn button-faux menu-resort" >Save Resorted Menu Item <i class="fa fa-repeat" aria-hidden="true"></i></button>
<?php
        if(isset($slugsAll)&&(isset($data))&&(count($slugsAll)>count($data))){
?>
        <button class="add-config-btn button-faux menu-edit" data-toggle="modal" data-target="#insertmenuModal" data-menuelem="new">Insert New Menu Item <i class="fa fa-plus-square" aria-hidden="true"></i></button>

<?php
        }else{
            ?>
            <br>
        <span class='requred-fields'>All the page slugs are alredy in use. You can't use the same one more than once.</span>
<?php
        }

?>
     </div>
 <div id="menuErrMsgsout"></div>

<?php
    $row="";
    if(isset($data)&&!empty($data)){
?>
    <div class="enclosed-table col-sm-6">
        <div class="page-element-row first-table-row clearfix">
           <div class="num-value col-sm-4">Current 0rder</div>
           <div class="page-title-value col-sm-6">Menu slug</div>
           <div class="page-action-value col-sm-2">Delete</div>
        </div>
        <ul id="sortable">
<?php
        foreach($data as $sort=>$item){
?>
 <li class="ui-state-default" id="menu-item-slug-<?php echo $item->slug; ?>">
        <div class="page-element-row clearfix <?php echo $row;?> ">

            <div class="num-value col-sm-4"><?php echo $item->order;?></div>
            <div class="slugvalue num-value col-sm-6"><?php echo $item->slug;?></div>
            <div class="page-lang-value col-sm-2"><i class="fa fa-trash deleteMenuItem link-center" aria-hidden="true" data-deleteslug="<?php echo $item->slug; ?>"></i></div>

        </div>

            </li>
<?php
              if($row==''){
                  $row='line-colored';
              }else{
                  $row="";
              }
         }
?>
        </ul>
       </div>
<?php
    }






