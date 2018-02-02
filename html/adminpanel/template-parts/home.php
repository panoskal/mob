<div class="small-info"></div>
<div class="header">Redirects</div>
<div class="general-info-line col-sm-12">
<?php
//check for any .lg files
foreach (glob($_SERVER['DOCUMENT_ROOT'].'/logs/files/*.lg') as $filename) {
	echo '<a class="button-faux" href="/logs/files/'. basename($filename). '" target="_blank">'. str_replace('_',' ', basename($filename, ".lg")) . ' log<i class="fa fa-plus-square" aria-hidden="true"></i></a>';
}
//are there any path log files?
    $config=Configuration:: getConfigurationByKey("CHECK_LOGS_URL");
    if(isset($config)&&!empty($config)){       
?>
  <a class="button-faux" href="<?php echo $config->value;?>" target="_blank">SQLite logs<i class="fa fa-plus-square" aria-hidden="true"></i></a>
  <?php
    } else {
?>
  <span class='requred-fields'>The logs url have not been set yet. go to Configuration Tab and enter the value under the 'CHECK_LOGS_URL' key.</span><br>
  <br>
  <?php
    }
    ?>
  <a class="button-faux" href="?data=urlsCheck">URLs check<i class="fa fa-plus-square" aria-hidden="true"></i></a> </div>
<div class="small-info">General Info about the Site</div>
<div class="header">Home</div>
<div class="general-info-line col-sm-12">
  <?php
$configEntries = Configuration::getAllConfiguration();
 if (!empty($configEntries) && is_array($configEntries)) {
    foreach($configEntries as $configVal) {
        if($configVal->keyname=="ALL_LANG"){
            $templang=explode(",",$configVal->value);
?>
  <div class="info-content row">
    <div class="info-title col-sm-2">Available Languages: </div>
    <div class="info-data col-sm-3">
      <?php

            if (is_array($templang)&&!empty($templang)) {
                foreach($templang as $i=>$data){
                    echo "<span class='lang-default'>".trim($data)."</span>";
                }
            }
?>
    </div>
  </div>
  <?php
        }
        if($configVal->keyname=="DEFAULT_LANG"){
?>
  <div class="info-content row">
    <div class="info-title col-sm-2">Main Language: </div>
    <div class="info-data col-sm-3"> <span class='lang-default'><?php echo $configVal->value;?></span> </div>
  </div>
  <?php
        }

    }
 }
 ?>
</div>
<div class="general-info-line col-sm-12">
  <?php
$winnerOne = Winner::getLastWinnerByDate();
 if (!empty($winnerOne) && is_array($winnerOne)) {
    foreach($winnerOne as $winnerData){
?>
  <div class="info-content row">
    <div class="info-title col-sm-2">Last Winner</div>
    <div class="info-data col-sm-9">
      <div class="info-subtitle col-sm-2">Name</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->name;?></span> </div>
      <div class="info-subtitle col-sm-2">Prize</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->prize;?></span> </div>
      <div class="info-subtitle col-sm-2">Date</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->winnerdate;?></span> </div>
      <div class="info-subtitle col-sm-2">Title</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->title;?></span> </div>
      <div class="info-subtitle col-sm-2">Email</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->email;?></span> </div>
      <div class="info-subtitle col-sm-2">Phone</div>
      <div class="info-data winners-data col-sm-10"> <span class='lang-default'><?php echo $winnerData->phone;?></span> </div>
    </div>
  </div>
  <?php

    }
 }
 ?>
</div>
<div class="general-info-line col-sm-12">
  <div class="info-content row">
    <div class="info-title col-sm-2">Available Pages: </div>
    <div class="info-data col-sm-10">
      <div class="row">
        <?php
$data=Page::getAllPagesByLang();
$allAvailSlugs=Page::fetchAllAvailablePageSlags();
$curentLang="";
$pages_by_lang=array();

 if (!empty($data) && is_array($data)) {
      foreach($data as $page) {
          $pages_by_lang[$page->lang][$page->slug]=$page->title;
      }
 }
$finallArayData=array();

//
if (!empty($allAvailSlugs) && is_array($allAvailSlugs)) {
    foreach($allAvailSlugs as $slugs){
         if (is_array($pages_by_lang)&&!empty($pages_by_lang)) {
            foreach($pages_by_lang as $elem => $langKey) {
                $found=false;
                if (is_array($langKey)&&!empty($langKey)) {
                    foreach($langKey as $slug=> $title) {
                        if($slug==$slugs["slug"]){
                            $finallArayData[$elem][]=$slug."<br> <span class='supersmall'>(".$title.")</span>";
                            $found=true;
                        }
                    }
                    if(!$found){
                         $finallArayData[$elem][]="---<br><span class='supersmall'>(---)</span>";
                    }
                }
            }
        }
    }
}
$countlang=count($pages_by_lang);
if($countlang<=6 && $countlang !== 0){
    $col_num=intval(12/$countlang);
}else{
    $col_num=2;
}
$tempLang="";

if (is_array($finallArayData)&&!empty($finallArayData)) {
    foreach($finallArayData as $lang => $slugData) {
        $row="";
?>
        <div class="info-data col-sm-<?php echo $col_num;?>">
          <?php

        if (is_array($slugData)&&!empty($slugData)) {
            foreach($slugData as $i => $slugsall) {
                if($lang!=$tempLang){
         ?>
          <div class="bottom-strike "> <span class=' slag-default lang-default'><?php echo $lang;?></span> </div>
          <?php
                    $tempLang=$lang;
                            }
         ?>
          <span class='slag-default <?php echo $row;?>'><?php echo $slugsall?></span>
          <?php
                if($row==''){
                    $row='line-colored';
                }else{
                    $row="";
                }
            }
?>
        </div>
        <?php
        }
    }
}
?>
      </div>
    </div>
  </div>
</div>
<div class=" clearfix"></div>
<div class="small-info "></div>
<div class="header">Change Password</div>
<div class="general-info-line col-sm-12">
  <?php
        if((isset($_REQUEST['action'])&&$_REQUEST['action']=='changePass')){
            if(isset($_REQUEST['password'])&&isset($_REQUEST['passwordrep'])&&
               !empty($_REQUEST['password'])&&!empty($_REQUEST['passwordrep'])){
                if($_REQUEST['password']==$_REQUEST['passwordrep']){
                    if(strlen($_REQUEST['password'])>=6){
                        $pass= Sanitize::processPass($_REQUEST['password']);
                        User::updateUsersPassword($pass, $_SESSION["id"]);
                        if($database->ReturnError()){
                             $mesage_update_password="An Error occured and the password was not updated. Try aigain later.";
                        }else{
                             $mesage_update_password="The password was updated successfully.";
                        }
                    }else{
                        $mesage_update_password="The password must have more than 6 characters.";
                    }
                }else{
                   $mesage_update_password="Please enter the same value on both fields.";
                }
            }else{
                $mesage_update_password="One or both of the 'Password' or 'Password Repeat' fields are empty. ";
            }
        }
    if(isset($mesage_update_password)&&!empty($mesage_update_password)){
        echo "<span class='requred-fields'>".$mesage_update_password."</span>";
    }
?>
  <form method="post" action="" id="targetform">
    <input name="action" value="changePass" type="hidden">
    <div class="form-line clearfix">
      <div class="form-group col-sm-12">
        <label for="user">User: </label>
        <span class="user_edit"><?php echo $_SESSION["id"]?></span> </div>
      <div class="form-group col-sm-6">
        <label for="password">Password</label>
        <input class="form-control" name="password" type="password">
      </div>
      <div class="form-group col-sm-6">
        <label for="passwordrep">Password Repeat</label>
        <input class="form-control" name="passwordrep" type="password">
      </div>
    </div>
    <div class="form-group col-sm-12">
      <button type="button" id="submitForm" class="button-faux"><i class="fa fa-pencil-square-o small-pad" aria-hidden="true"></i>Update Password</button>
    </div>
  </form>
</div>
