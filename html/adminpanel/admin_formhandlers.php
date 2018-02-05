<?php
include_once '../config.php';

if ( isset($_POST['action']) && $_POST['action'] == 'fillConfigForm' ) {
    if (isset($_POST['configId']) && is_numeric($_POST['configId'])) {
        $fillConfigData = Configuration::getConfigurationById($_POST['configId']);
        if (!empty($fillConfigData) && is_array($fillConfigData)) {
            $fillConfigDataJSON = json_encode($fillConfigData);
            echo $fillConfigDataJSON;
        } else {
            $fillConfigDataErrMsgs = array();
            $fillConfigDataErrMsgs['nodata']  = 'Data not found';
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($fillConfigDataErrMsgs, JSON_UNESCAPED_UNICODE));
        }
    } else if (isset($_POST['configId']) && !is_numeric($_POST['configId'])) {
        $fillConfigData = array();
        $fillConfigDataJSON = json_encode($fillConfigData);
        echo $fillConfigDataJSON;
    }

}


if (isset($_POST['action']) && $_POST['action']==='insertConfig') {

    $success = true;
    $insertConfigArr = array();
    $errMessages = array();

    if (empty($_POST['keyname'])) {
        $errMessages['keynameErr'] = "Please enter key name";
        $success = false;
    } else {
        $insertConfigArr['keyname'] = Sanitize::processString($_POST['keyname']);
        $keynameExists = Configuration::getConfigurationByKey($insertConfigArr['keyname']);
        if (!is_numeric($_POST['configmodalid']) && !empty($keynameExists)) {
            $errMessages['keynameErr'] = "This key name already exists. Please insert new one...";
            $success=false;
        }
    }

    if (empty($_POST['value'])) {
        $insertConfigArr['value'] = "";
    } else {
        if ($_POST['keyname'] === 'ALL_LANG') {
            $allLangArr = explode("," ,$_POST['value']);
            $newAllLangArr = array();
            $pageLanguages = Page::getAllPageLang();
            $storedValues = array();
            $pageLanguageArr = array();
            if (is_array($pageLanguages)) {
                $pageLanguageArr = array_column($pageLanguages, 'lang');
            }
            if (is_array($pageLanguageArr)) {
                foreach ($pageLanguageArr as $pageLanguage) {
                    if (!in_array(trim($pageLanguage), $allLangArr)) {
                        array_push($storedValues, trim($pageLanguage));
                    }
                }
            }
            if (!empty($storedValues)) {
                foreach ($storedValues as $storedValue) {
                    array_push($allLangArr, trim($storedValue));
                }
            }
            $allLangArr = array_unique($allLangArr);
            foreach ($allLangArr as $trimVal) {
                array_push($newAllLangArr, trim($trimVal));
            }
            $newAllLangString = implode(",", $newAllLangArr);
            $insertConfigArr['value'] = Sanitize::processString($newAllLangString);
        } else {
            $insertConfigArr['value'] = Sanitize::processString($_POST['value']);
        }

    }

    if (empty($_POST['notes'])) {
        $insertConfigArr['notes'] = "";
    } else {
        $insertConfigArr['notes'] = Sanitize::processString($_POST['notes']);
    }

    if ($success) {

        $dbSuccess = true;

        $successMessages = array();

        $configObj = new Configuration();

        if (!empty($_POST['configmodalid']) && is_numeric($_POST['configmodalid']) ) {
            $configmodalid = Sanitize::processInt($_POST['configmodalid']);
            $existingElem = Configuration::getConfigurationById($configmodalid);
            if (empty($existingElem)) {
                $errMessages['configIdErr'] = "Element not found";
                $dbSuccess = false;
            } else {
                $updateConfigVal = $configObj->updateConfig($insertConfigArr, $configmodalid);
                if (empty($updateConfigVal)) {
                    $errMessages['insertConfigErr'] = "Could not update entries due to a system error";
                    $dbSuccess = false;
                } else {
                    $affectedId = $configmodalid;
                    $successMessages['insertSuccess'] = "You succesfully updated a configuration value";
                }
            }
        } else if (!empty($_POST['configmodalid']) && is_string($_POST['configmodalid'])) {
            $configmodalid = Sanitize::processString($_POST['configmodalid']);
            if ($configmodalid !== "new") {
                $errMessages['configIdErr'] = "Element not found";
                $dbSuccess = false;
            }  else {
                $newConfigVal = $configObj->insertSingleConfig($insertConfigArr);
                if (empty($newConfigVal)) {
                    $errMessages['insertConfigErr'] = "Could not insert entries into database due to a system error";
                    $dbSuccess = false;
                } else {
                    $affectedId = $newConfigVal;
                    $successMessages['insertSuccess'] = "You succesfully inserted a configuration value";
                }
            }
        } else {
            $errMessages['configIdErr'] = "Element not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
        }

        if ($dbSuccess) {
//            echo json_encode($successMessages);
            $updatedConfigEntry = array();
            $newConfigEntryArr = Configuration::getConfigurationById($affectedId);
            if (!empty($newConfigEntryArr) && is_array($newConfigEntryArr)) {
                $updatedConfigEntry['updatedConfigEntry']  = '<tr id="config-entry-row-id-';
                $updatedConfigEntry['updatedConfigEntry'] .= $affectedId;
                $updatedConfigEntry['updatedConfigEntry'] .='" class="config-entry-row"><td class="config-keyname col-sm-4"><a href="#" data-toggle="tooltip" data-placement="top" title="';
                $updatedConfigEntry['updatedConfigEntry'] .= $newConfigEntryArr['notes'];
                $updatedConfigEntry['updatedConfigEntry'] .= '">';
                $updatedConfigEntry['updatedConfigEntry'] .= $newConfigEntryArr['keyname'];
                $updatedConfigEntry['updatedConfigEntry'] .= '</a></td><td class="config-value col-sm-6">';
                $updatedConfigEntry['updatedConfigEntry'] .= $newConfigEntryArr['value'];
                $updatedConfigEntry['updatedConfigEntry'] .= '</td><td class="config-edit col-sm-1" data-configelem="';
                $updatedConfigEntry['updatedConfigEntry'] .= $affectedId;
                $updatedConfigEntry['updatedConfigEntry'] .= '" data-toggle="modal" data-target="#insertConfigModal"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></td><td class="config-delete col-sm-1"><i class="fa fa-trash deleteConfig" aria-hidden="true" data-deleteid="';
                $updatedConfigEntry['updatedConfigEntry'] .= $affectedId;
                $updatedConfigEntry['updatedConfigEntry'] .='"></i></td></tr>';
                $updatedConfigEntry['returnedId'] = $configmodalid;
                $updatedConfigEntry['successMessages'] = $successMessages;
                echo json_encode($updatedConfigEntry);
            }
        } else {
            if (isset($errMessages['insertConfigErr'])) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
            } else if (isset($errMessages['configIdErr'])) {
                header('HTTP/1.1 400 Bad request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
            }
        }
        $_POST = "";
        $insertConfigArr = "";
    } else {
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
    }

}


if (isset($_POST['action']) && $_POST['action']==='deleteConfig') {

    $deleteConfigMsgs = array();

    if ($_POST['deleteElemId'] != '') {
        $deleteElemId = Sanitize::processInt($_POST['deleteElemId']);
        $deleteResult = Configuration::deleteConfig($deleteElemId);
        if (!empty($deleteResult)) {
            $deleteConfigMsgs['success'] = "You have succesfully deleted a configuration entry";
            echo json_encode($deleteConfigMsgs, JSON_UNESCAPED_UNICODE);
        } else {
            $deleteConfigMsgs['fail'] = "Element to be deleted, not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($deleteConfigMsgs, JSON_UNESCAPED_UNICODE));
        }
    } else {
        $deleteConfigMsgs['fail'] = "Could not find the configuration entry that you requested";
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($deleteConfigMsgs, JSON_UNESCAPED_UNICODE));
    }

}

if ( isset($_POST['action']) && $_POST['action'] == 'fillWinnerForm' ) {
//    echo json_encode($_POST);
    if (isset($_POST['winnerId']) && is_numeric($_POST['winnerId'])) {
        $winnerObj = new Winner();
        $fillWinnerData = $winnerObj->getWinnerById($_POST['winnerId']);
        if (!empty($fillWinnerData) && is_object($fillWinnerData)) {
            $fillWinnerDataJSON = json_encode($fillWinnerData);
            echo $fillWinnerDataJSON;
        } else {
            $fillWinnerDataErrMsgs = array();
            $fillWinnerDataErrMsgs['nodata']  = 'Winner not found';
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($fillWinnerDataErrMsgs, JSON_UNESCAPED_UNICODE));
        }
    } else if (isset($_POST['winnerId']) && !is_numeric($_POST['winnerId'])) {
        $fillWinnerData = array();
        $fillWinnerDataJSON = json_encode($fillWinnerData);
        echo $fillWinnerDataJSON;
    }

}

if (isset($_POST['action']) && $_POST['action']==='insertWinner') {

    $success = true;
    $insertWinnerArr = array();
    $winnerErrMessages = array();

    if (!empty($_POST) && is_array($_POST)) {
        foreach ($_POST as $key=>$value) {
            if ($key === 'name') {
                if (empty($value)) {
                    $winnerErrMessages[$key.'Err'] = "Please enter winner's full name";
                    $success = false;
                } else {
                    $insertWinnerArr["$key"] = Sanitize::processString($_POST["$key"]);
                }
            } else if ($key === 'email') {
                if (empty($value)) {
                    $insertWinnerArr["$key"] = "";
                } else {
                    $validEmail = Sanitize::processEmail($_POST["$key"]);
                    if (empty($validEmail)) {
                        $winnerErrMessages[$key.'Err'] = "Please enter valid email address";
                        $success = false;
                    } else {
                        $insertWinnerArr["$key"] = $validEmail;
                    }
                }
            } else {
                if (empty($value)) {
                    $insertWinnerArr["$key"] = "";
                } else {
                    $insertWinnerArr["$key"] = Sanitize::processString($_POST["$key"]);
                }
            }
        }
    }

    if ($success) {
        $dbSuccess = true;

        if (!empty($_POST['winnermodalid']) && is_numeric($_POST['winnermodalid']) ) {
            $winnermodalid = Sanitize::processInt($_POST['winnermodalid']);
            $existingWinnerObj = new Winner();
            $existingWinner = $existingWinnerObj->getWinnerById($winnermodalid);
            if (empty($existingWinner)) {
                $winnerErrMessages['winnerIdErr'] = "Winnner not found";
                $dbSuccess = false;
            } else {
                $updateWinerVal = $existingWinnerObj->updateWinner($insertWinnerArr, $winnermodalid);
                if (empty($updateWinerVal)) {
                    $winnerErrMessages['insertWinnerErr'] = "Could not update entries due to a system error";
                    $dbSuccess = false;
                } else {
                    $affectedId = $winnermodalid;
                }
            }
        } else if (!empty($_POST['winnermodalid']) && is_string($_POST['winnermodalid'])) {
            $winnermodalid = Sanitize::processString($_POST['winnermodalid']);
            if ($winnermodalid !== "new") {
                $winnerErrMessages['winnerIdErr'] = "Winner not found";
                $dbSuccess = false;
            }  else {
                $insertNewWinnerObj = new Winner();
                $newWinnerVal = $insertNewWinnerObj->insertWinner($insertWinnerArr);
                if (empty($newWinnerVal)) {
                    $winnerErrMessages['insertWinnerErr'] = "Could not insert entries into database due to a system error";
                    $dbSuccess = false;
                } else {
                    $affectedId = $newWinnerVal;
                }
            }
        } else {
            $winnerErrMessages['winnerIdErr'] = "Element not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($winnerErrMessages, JSON_UNESCAPED_UNICODE));
        }

        if ($dbSuccess) {
            $updatedWinnerEntry = array();
            $newWinnerEntryObj = new Winner();
            $newWinnerEntry = $newWinnerEntryObj->getWinnerById($affectedId);
            if (!empty($newWinnerEntry) && is_object($newWinnerEntry)) {
                $updatedWinnerEntry['updatedWinnerEntry']  = '<tr id="winner-entry-row-id-';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $affectedId;
                $updatedWinnerEntry['updatedWinnerEntry'] .='" class="winner-entry-row"><td class="winner-name">';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $newWinnerEntry->name;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '</td><td class="winner-prize">';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $newWinnerEntry->prize;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '</td><td class="winner-title">';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $newWinnerEntry->title;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '</td><td class="winner-email">';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $newWinnerEntry->email;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '</td><td class="winner-date">';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $newWinnerEntry->winnerdate;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '</td><td class="winner-edit" data-winnerelem="';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $affectedId;
                $updatedWinnerEntry['updatedWinnerEntry'] .= '" data-toggle="modal" data-target="#insertWinnerModal"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></td><td class="winner-delete"><i class="fa fa-trash deleteWinner" aria-hidden="true" data-deleteid="';
                $updatedWinnerEntry['updatedWinnerEntry'] .= $affectedId;
                $updatedWinnerEntry['updatedWinnerEntry'] .='"></i></td></tr>';
                $updatedWinnerEntry['returnedId'] = $winnermodalid;
                echo json_encode($updatedWinnerEntry);
            }
        } else {
            if (isset($winnerErrMessages['insertWinnerErr'])) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode($winnerErrMessages, JSON_UNESCAPED_UNICODE));
            } else if (isset($winnerErrMessages['winnerIdErr'])) {
                header('HTTP/1.1 400 Bad request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode($winnerErrMessages, JSON_UNESCAPED_UNICODE));
            }
        }

    $_POST = "";
    $insertWinnerArr = "";
    } else {
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($winnerErrMessages, JSON_UNESCAPED_UNICODE));
    }

}

if (isset($_POST['action']) && $_POST['action']==='deleteWinner') {

    $deleteWinnerMsgs = array();

    if ($_POST['deleteElemId'] != '') {
        $deleteElemId = Sanitize::processInt($_POST['deleteElemId']);
        $deleteResult = Winner::deleteWinner($deleteElemId);
        if (!empty($deleteResult)) {
            $deleteWinnerMsgs['success'] = "You have succesfully deleted a winner entry";
            echo json_encode($deleteWinnerMsgs, JSON_UNESCAPED_UNICODE);
        } else {
            $deleteWinnerMsgs['fail'] = "Could not find winner you are about to delete";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            debuggerlog("Delete_winner", $deleteWinnerMsgs['fail']);
            die(json_encode($deleteWinnerMsgs, JSON_UNESCAPED_UNICODE));
        }
    } else {
        $deleteWinnerMsgs['fail'] = "Could not find the winner entry that you requested";
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($deleteWinnerMsgs, JSON_UNESCAPED_UNICODE));
    }

}



if (isset($_POST['action']) && $_POST['action']==='deleteMenuItem') {
    $deleteMenuItemMsg = array();
    if ($_POST['deleteElemSlug'] != '') {
        $deleteElemSlug = Sanitize::processString($_POST['deleteElemSlug']);

        $database->beginTransaction();
        $deleteResult = Menu::deleteMenuItems($deleteElemSlug);
        $data=Menu::getAllMenuItems();
        $resorter=1;
        if(!empty($data)){
            foreach($data as $i=>$rowz){
                Menu::updateOrderMenu($rowz->slug, $resorter) ;
                $resorter++;
            }
        }
        if($database->ReturnError()){
            $database->cancelTransaction();
            $deleteResult=false;
        }else{
            $database->endTransaction();
        }

        $_POST = "";
        if (!empty($deleteResult)) {
            $deleteMenuItemMsg['success'] = "You have succesfully deleted a configuration entry";
            echo json_encode($deleteMenuItemMsg, JSON_UNESCAPED_UNICODE);
        } else {
            $deleteMenuItemMsg['fail'] = "Element to be deleted, not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($deleteMenuItemMsg, JSON_UNESCAPED_UNICODE));
        }
    } else {
        $_POST = "";
        $deleteMenuItemMsg['fail'] = "Could not find the configuration entry that you requested";
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($deleteMenuItemMsg, JSON_UNESCAPED_UNICODE));
    }

}



if (isset($_POST['action']) && $_POST['action']==='linkset') {
    $linkMenuItemMsg = array();
    if ($_POST['doAction'] != ''&&$_POST['elementSlug'] != '') {
        $elemSlug = Sanitize::processString($_POST['elementSlug']);
        $action=$_POST['doAction'];
        $database->beginTransaction();

        if($action=="link"){
            Menu::is_not_link_Menu($elemSlug, 0) ;
        }else if($action=="unlink"){
            Menu::is_not_link_Menu($elemSlug, 1) ;
        }else {
            $database->cancelTransaction();
            $linkMenuItemMsg['fail'] = "Element to be ".$action.", not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($linkMenuItemMsg, JSON_UNESCAPED_UNICODE));
        }
        $result=true;
        if($database->ReturnError()){
            $database->cancelTransaction();
            $result=false;
        }else{
            $database->endTransaction();
        }

        $_POST = "";
        if (!empty($result)) {
            $linkMenuItemMsg['success'] = "You have succesfully ".$action." the menu item.";
            echo json_encode($linkMenuItemMsg, JSON_UNESCAPED_UNICODE);
        } else {
            $linkMenuItemMsg['fail'] = "Element to be ".$action.", not found";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($linkMenuItemMsg, JSON_UNESCAPED_UNICODE));
        }
    } else {
        $_POST = "";
        $linkMenuItemMsg['fail'] = "Could not find the configuration entry that you requested";
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($linkMenuItemMsg, JSON_UNESCAPED_UNICODE));
    }

}



if (isset($_POST['action']) && $_POST['action']==='menuItemNEW') {

    $success = true;
    $insertMenuArr = array();
    $errMessages = array();
    $insertMenuArr['is_not_link']=0;
    if (empty($_POST['order'])||$_POST['order']=="none") {
        $errMessages['orderErr'] = "Please select the display order for the menu item";
        $success = false;
    } else {
        $insertMenuArr['order'] = Sanitize::processInt($_POST['order']);
//        $orderExists = Menu::getSingleMenuByOrder($insertMenuArr['order']);
//        if (!empty($orderExists)) {
//            $errMessages['orderErr'] = "A menu item with this order, already exist. Please insert an other one...";
//            $success=false;
//        }
    }
    if (!isset($_POST['slug'])||$_POST['slug']=="none") {
        $errMessages['slugErr'] = "Please select the menu slug for the menu item";
        $success = false;
    } else {
        if ($_POST['slug']=="0") {//element is an parent slug
            if(isset($_POST['place_holder_name'])&&!empty($_POST['place_holder_name'])){
                $insertMenuArr['slug'] = Sanitize::processString($_POST['place_holder_name']);
            }else{
                $errMessages['slugErr'] = "You have selected to add a 'placeholder Item' Please select a name";
                $success=false;
            }
            if (isset($_POST['is_not_link'])) {
                $insertMenuArr['is_not_link']=1;
            }
        }else{
            $insertMenuArr['slug'] = Sanitize::processString($_POST['slug']);
            $slugExists = Menu::getSingleMenuBySlug($insertMenuArr['slug']);
            if (!empty($slugExists)) {
                $errMessages['slugErr'] = "A menu item with this slug, already exist. Please insert an other one...";
                $success=false;
            }
        }
    }
    if(isset($_POST['has_parent'])&&!empty($_POST['has_parent'])&&$_POST['has_parent']!="none"){
        $insertMenuArr['has_parent']=$_POST['has_parent'];
    }else{
         $insertMenuArr['has_parent']=0;
    }

    if ($success) {
        $dbSuccess = true;
        $successMessages = array();

        $newMenuItemInsert = Menu::insertMenuItems($insertMenuArr);
        if (!isset($newMenuItemInsert)) {
            $errMessages['insertMenuErr'] = "Could not insert entries into database due to a system error";
            $dbSuccess = false;
        } else {
            $affectedId = $newMenuItemInsert;
            $successMessages['insertSuccess'] = "You succesfully inserted a new Menu Item";
        }
        $_POST = "";
    } else {
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
    }

}

if (isset($_POST['action']) && $_POST['action']==='resortMenuItems') {
    $errMessages = array();
    if(isset($_POST["updateArr"])&&!empty($_POST["updateArr"])){
        $updatearr=$_POST["updateArr"];
        $database->beginTransaction();
        $data=Menu::getAllMenuItems();
        $resorter=1;
        $resortersub=1;
        $debug="";
        if(!empty($data)&&!empty($updatearr)){
            foreach($updatearr as $slug){
                foreach($data as $i=>$rowz){
                    if($rowz->slug==$slug){
                        Menu::updateOrderMenu(Sanitize::processString($rowz->slug), $resorter) ;
                        $resorter++;
                    }
                }
            }
            if(isset($_POST["updateArrSub"])&&!empty($_POST["updateArrSub"])){
                $updateArrSub=$_POST["updateArrSub"];
                foreach($updateArrSub as $parent=>$el){
                   foreach($el as $elem=>$kid){
                        Menu::updateOrderSubMenu(Sanitize::processString($kid), $resortersub,Sanitize::processString($parent)) ;
                        $resortersub++;
                    }
                    $resortersub=1;
                }
            }
        }

        $_POST = "";

        if($database->ReturnError()){
            $database->cancelTransaction();
            $errMessages['fail'] = "The items have not been resorted";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
        }else{
            $database->endTransaction();
            $errMessages['success'] = "The items have been resorted successfuly.";
            echo json_encode($errMessages, JSON_UNESCAPED_UNICODE);
        }


    } else {
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($errMessages, JSON_UNESCAPED_UNICODE));
    }
}


if(isset($_POST['action'])&&$_POST['action']=='installDB') {


    $success = true;
    $installDBMsgs = array();
    $insertUserArr = array();
    $installDbArr = array();
    $debugArr = ['true', 'false'];

    if ($_POST['username'] == '') {
        $installDBMsgs['usernameErr'] = "Please enter username";
        $success = false;
    } else {
        $insertUserArr['username'] = Sanitize::processString($_POST['username']);
    }

    if ($_POST['password'] == '') {
        $installDBMsgs['passwordErr'] = "Please enter password";
        $success = false;
    } else {
        if ($_POST['password'] != $_POST['confirmPass']) {
            $installDBMsgs['passwordConfErr'] = "Password and confirmation password don't match";
            $success = false;
        } else {
            $insertUserArr['password'] = Sanitize::processPass($_POST['password']);
        }
    }

    if ($_POST['timezone'] == '') {
        $installDBMsgs['timezoneErr'] = "Please select your timezone";
        $success = false;
    } else {
        $installDbArr['timezone']['value'] = Sanitize::processString($_POST['timezone']);
        $installDbArr['timezone']['note'] = '';
    }

    if ($_POST['prefix'] == '') {
        $installDBMsgs['prefixErr'] = "Please enter prefix";
        $success = false;
    } else {
        $validPrefixUrl = Sanitize::processInt($_POST['prefix']);
        if (!$validPrefixUrl) {
            $installDBMsgs['prefixErr'] = "The Prefix you entered is not a number";
            $success = false;
        } else {
            $installDbArr['prefix']['value'] = $validPrefixUrl;
            $installDbArr['prefix']['note'] = 'Country code';
        }
    }

    if ($_POST['default_lang'] == '') {
        $installDBMsgs['default_langErr'] = "Please select your timezone";
        $success = false;
    } else {
        $installDbArr['default_lang']['value'] = Sanitize::processString($_POST['default_lang']);
        $installDbArr['default_lang']['note'] = '';
    }

    if ($_POST['all_lang'] == '') {
        $installDBMsgs['all_langErr'] = "Please select your languages";
        $success = false;
    } else {
        $allLangArr = explode("," ,$_POST['all_lang']);
        $newAllLangArr = array();
        foreach ($allLangArr as $trimVal) {
            array_push($newAllLangArr, trim($trimVal));
        }
        $newAllLangString = implode(",", $newAllLangArr);
        $installDbArr['all_lang']['value'] = Sanitize::processString($newAllLangString);
        $installDbArr['all_lang']['note'] = '';
    }

    if ($_POST['loginurl'] == '') {
        $installDbArr['loginurl']['value'] = '';
        $installDbArr['loginurl']['note'] = 'URL for validating user';
    } else {
        $validLoginUrl = Sanitize::processUrl($_POST['loginurl']);
        if (empty($validLoginUrl)) {
            $installDBMsgs['loginurlErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $installDbArr['loginurl']['value'] = $validLoginUrl;
            $installDbArr['loginurl']['note'] = 'URL for validating user';
        }
    }

    if ($_POST['campaignid'] == '') {
        $installDbArr['campaignid']['value'] = '';
        $installDbArr['campaignid']['note'] = '';
    } else {
        $installDbArr['campaignid']['value'] = Sanitize::processString($_POST['campaignid']);
        $installDbArr['campaignid']['note'] = '';
    }

    if ($_POST['useridurl'] == '') {
        $installDbArr['useridurl']['value'] = '';
        $installDbArr['useridurl']['note'] = 'No trailing slashes';
    } else {
        $validUserIdUrl = Sanitize::processUrl($_POST['useridurl']);
        if (empty($validUserIdUrl)) {
            $installDBMsgs['useridurlErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $installDbArr['useridurl']['value'] = $validUserIdUrl;
            $installDbArr['useridurl']['note'] = 'No trailing slashes';
        }
    }

    if ($_POST['draw_url'] == '') {
        $installDbArr['draw_url']['value'] = '';
        $installDbArr['draw_url']['note'] = 'URL to check winners (no trailing slashes)';
    } else {
        $validDrawUrl = Sanitize::processUrl($_POST['draw_url']);
        if (empty($validDrawUrl)) {
            $installDBMsgs['draw_urlErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $installDbArr['draw_url']['value'] = $validDrawUrl;
            $installDbArr['draw_url']['note'] = 'URL to check winners (no trailing slashes)';
        }
    }

    if ($_POST['draw_user'] == '') {
        $installDbArr['draw_user']['value'] = '';
        $installDbArr['draw_user']['note'] = '';
    } else {
        $installDbArr['draw_user']['value'] = Sanitize::processString($_POST['draw_user']);
        $installDbArr['draw_user']['note'] = '';
    }

    if ($_POST['draw_pass'] == '') {
        $installDbArr['draw_pass']['value'] = '';
        $installDbArr['draw_pass']['note'] = '';
    } else {
        $installDbArr['draw_pass']['value'] = Sanitize::processString($_POST['draw_pass']);
        $installDbArr['draw_pass']['note'] = '';
    }

    if ($_POST['recaptcha_key'] == '') {
        $installDbArr['recaptcha_key']['value'] = '';
        $installDbArr['recaptcha_key']['note'] = '';
    } else {
        $installDbArr['recaptcha_key']['value'] = Sanitize::processString($_POST['recaptcha_key']);
        $installDbArr['recaptcha_key']['note'] = '';
    }

    if ($_POST['ganalytics'] == '') {
        $installDbArr['ganalytics']['value'] = '';
        $installDbArr['ganalytics']['note'] = 'Google key for analytics';
    } else {
        $installDbArr['ganalytics']['value'] = Sanitize::processString($_POST['ganalytics']);
        $installDbArr['ganalytics']['note'] = 'Google key for analytics';
    }

    if ($_POST['debugging'] == '') {
        $installDBMsgs['debuggingErr'] = "Please set debbugging mode on or off (true/false)";
        $success = false;
    } else {
        if (!in_array($_POST['debugging'], $debugArr)) {
            $installDBMsgs['debuggingErr'] = "Please set debbugging mode on or off (true/false)";
        }
        $installDbArr['debugging']['value'] = Sanitize::processString($_POST['debugging']);
        $installDbArr['debugging']['note'] = 'Set debugging mode on/off by setting this value true or false';
    }

    if ($_POST['debugplaincurl'] == '') {
        $installDBMsgs['debugplaincurlErr'] = "Please set debug plain curl on or off (true/false)";
        $success = false;
    } else {
        if (!in_array($_POST['debugplaincurl'], $debugArr)) {
            $installDBMsgs['debugplaincurlErr'] = "Please set debbugging mode on or off (true/false)";
        }
        $installDbArr['debugplaincurl']['value'] = Sanitize::processString($_POST['debugplaincurl']);
        $installDbArr['debugplaincurl']['note'] = 'Set debugging mode on/off by setting this value true or false';
    }


    if ($_POST['cache'] == '') {
        $installDbArr['cache']['value'] = '';
        $installDbArr['cache']['note'] = 'URL: the of the cache server (no trailing slashes)';
    } else {
        $validCacheUrl = Sanitize::processUrl($_POST['cache']);
        if (empty($validCacheUrl)) {
            $installDBMsgs['cacheErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $installDbArr['cache']['value'] = $validCacheUrl;
            $installDbArr['cache']['note'] = 'URL: the of the cache server (no trailing slashes)';
        }
    }

    if ($_POST['domain'] == '') {
        $installDbArr['domain']['value'] = '';
        $installDbArr['domain']['note'] = 'URL: the of the site (no trailing slashes)';
    } else {
        $validDomainUrl = Sanitize::processUrl($_POST['domain']);
        if (empty($validDomainUrl)) {
            $installDBMsgs['domainErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $installDbArr['domain']['value'] = $validDomainUrl;
            $installDbArr['domain']['note'] = 'URL: the of the site (no trailing slashes)';
        }
    }

    if ($_POST['versioning'] == '') {
        $installDbArr['versioning']['value'] = '';
        $installDbArr['versioning']['note'] = 'NUMBER: version number Always greater number than the previews one';
    } else {
        $installDbArr['versioning']['value'] = Sanitize::processInt($_POST['versioning']);
        $installDbArr['versioning']['note'] = 'NUMBER: version number Always greater number than the previews one';
    }

    if ($_POST['configuration_title'] == '') {
        $installDbArr['configuration_title']['value'] = '';
        $installDbArr['configuration_title']['note'] = 'Enter the general site title';
    } else {
        $installDbArr['configuration_title']['value'] = Sanitize::processString($_POST['configuration_title']);
        $installDbArr['configuration_title']['note'] = 'Enter the general site title';
    }

    if ($_POST['general_description'] == '') {
        $installDbArr['general_description']['value'] = '';
        $installDbArr['general_description']['note'] = 'Enter general site meta description';
    } else {
        $installDbArr['general_description']['value'] = Sanitize::processString($_POST['general_description']);
        $installDbArr['general_description']['note'] = 'Enter general site meta description';
    }

    if ($_POST['general_keywords'] == '') {
        $installDbArr['general_keywords']['value'] = '';
        $installDbArr['general_keywords']['note'] = 'Enter general site meta keywords';
    } else {
        $installDbArr['general_keywords']['value'] = Sanitize::processString($_POST['general_keywords']);
        $installDbArr['general_keywords']['note'] = 'Enter general site meta keywords';
    }

    if ($_POST['onepage'] == '') {
        $installDbArr['onepage']['value'] = 'true';
        $installDbArr['onepage']['note'] = 'TRUE|FALSE: toggle between one page(with scroll down) and multiple pages';
    } else {
        $installDbArr['onepage']['value'] = Sanitize::processString($_POST['onepage']);
        $installDbArr['onepage']['note'] = 'TRUE|FALSE: toggle between one page(with scroll down) and multiple pages';
    }

    $installDbArr['check_logs_url']['value'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/logs';
    $installDbArr['check_logs_url']['note'] = 'Url for checking error logs';


    if ($success) {

//        Install::setTimezone();
        Install::setMode();
        Install::createConfig();
        Install::createMenu();
        Install::createPages();
        Install::createUsers();
        Install::createWinners();

        $tablesNum = $database->showTables();

        if (count($tablesNum) === 5) {
            try {
                $database->beginTransaction();
                User::insertUser($insertUserArr);
                Configuration::insertMultiConfig($installDbArr);
                $database->endTransaction();
                $installDBMsgs['dbSuccessMsg'] = "Database tables are successfully initialized";
                echo json_encode($installDBMsgs, JSON_UNESCAPED_UNICODE);
            } catch(PDOException $e) {
                debuggerlog('Query_fail', $e->getMessage());
//                $e->getMessage();
                $installDBMsgs['dbFailMsg'] = $e->getMessage();
                $database->cancelTransaction();
                header('HTTP/1.1 400 Bad request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode($installDBMsgs, JSON_UNESCAPED_UNICODE));
            }
        } else {
            $installDBMsgs['dbFailMsg'] = "There was a problem while creating the database tables";
            header('HTTP/1.1 400 Bad request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode($installDBMsgs, JSON_UNESCAPED_UNICODE));
        }

    } else {
        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($installDBMsgs, JSON_UNESCAPED_UNICODE));
    }

}

if((isset($_POST['action']) && $_POST['action']=='apitester')) {

    $success = true;
    $apitesterMsgs = array();
    $apitesterArr = array();
    $validTypes = array('json', 'post', 'get');

    if (empty($_POST['srvurl'])) {
        $apitesterMsgs['srvurlErr'] = "Please fill in the url field";
        $success = false;
    } else {
        $validUrl = Sanitize::processUrl($_POST['srvurl']);
        if (empty($validUrl)) {
            $apitesterMsgs['srvurlErr'] = "Please enter valid URL";
            $success = false;
        } else {
            $apitesterArr['srvurl'] = $validUrl;
        }
    }

    if (empty($_POST['srvtype'])) {
        $apitesterMsgs['srvtypeErr'] = "Please select type";
        $success = false;
    } else {
        if (!in_array($_POST['srvtype'], $validTypes)) {
            $apitesterMsgs['srvtypeErr'] = "This is not a valid type";
            $success = false;
        } else {
            $apitesterArr['srvtype'] = Sanitize::processString($_POST['srvtype']);
        }
    }

    if (isset($_POST['srvathChk']) && $_POST['srvathChk'] == 'true') {
        if (empty($_POST['srvuser'])) {
            $apitesterMsgs['srvuserErr'] = "Please fill in authentication username";
            $success = false;
        } else {
            $apitesterArr['srvuser'] = Sanitize::processString($_POST['srvuser']);
        }

        if (empty($_POST['srvpass'])) {
            $apitesterMsgs['srvpassErr'] = "Please fill in authentication password";
            $success = false;
        } else {
            $apitesterArr['srvpass'] = Sanitize::processString($_POST['srvpass']);
        }

        if (!empty($_POST['srvuser']) && !empty($_POST['srvpass'])) {
            $credentials = array(
                'user'  =>  $apitesterArr['srvuser'],
                'pass'  =>  $apitesterArr['srvpass']
            );
        }

    } else {
        $credentials = false;
    }

    if (!empty($_POST['srvheaders'])) {
        if (isJSON($_POST['srvheaders'])) {
            $apitesterArr['srvheaders'] = json_decode($_POST['srvheaders'], true);
        } else {
            $apitesterMsgs['srvheadersErr'] = "Please fill headers field with valid JSON object";
            $success = false;
        }
    } else {
        $apitesterArr['srvheaders'] = false;
    }

    if (!empty($_POST['srvbody'])) {
        if (isJSON($_POST['srvbody'])) {
            $apitesterArr['srvbody'] = json_decode($_POST['srvbody'], true);
        } else {
            $apitesterMsgs['srvbodyErr'] = "Please fill body field with valid JSON object";
            $success = false;
        }
    } else if (empty($_POST['srvbody']) && ($_POST['srvtype'] == 'json' || $_POST['srvtype'] == 'post')) {
        $apitesterMsgs['srvbodyErr'] = "For json and post type, data must be provided for body";
        $success = false;
    } else {
        $apitesterArr['srvbody'] = '';
    }

    if ($success) {

        $response = URLCONNECT($apitesterArr['srvurl'], $apitesterArr['srvtype'], $apitesterArr['srvbody'], $credentials, $apitesterArr['srvheaders'], $useproxy=false);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);

    } else {

        header('HTTP/1.1 400 Bad request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($apitesterMsgs, JSON_UNESCAPED_UNICODE));

    }

}
