<?php
$header_title='Installation page';
$info='';

?>
<div class="veil hide"></div>
<div id="installDbModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                <h4 class="installDbTitle">Please fill the following information</h4>
            </div>
        </div>
    </div>
</div>

<div id="installDBMsgs" class="small-info"></div>
<div class="header">Installation settings</div>

<div class="row">
    <div class="col-sm-offset-1 col-sm-11">
        <div>
        <?php
            if (!empty($errorMsgs) && is_array($errorMsgs)) {
                foreach ($errorMsgs as $errorMsg) {
            ?>
            <span class="error-msg"><?php echo $errorMsg; ?></span><br>
        <?php
                }
            }
            ?>
            </div>
        <form action="" method="post" id="basicConfigForm" class="form-horizontal">
            <h3>User settings</h3>
            <div class="form-group">
                <label for="username" class="control-label col-sm-3">Username</label>
                <div class="col-sm-5">
                    <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="control-label col-sm-3">Password</label>
                <div class="col-sm-5">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <label for="confirmPass" class="control-label col-sm-3">Re-type password</label>
                <div class="col-sm-5">
                    <input type="password" name="confirmPass" class="form-control" id="confirmPass" placeholder="Re-type password">
                </div>
            </div>
            <h3>Basic application settings</h3>
            <div class="form-group">
                <label for="timezone" class="control-label col-sm-3">Timezone</label>
                <div class="col-sm-5">
                    <select name="timezone" id="timezone" class="form-control">
                        <option value="">...</option>
                        <?php
                            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                            if (!empty($tzlist)) {
                                foreach($tzlist as $value) {
                            ?>
                            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php
                                }
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="prefix" class="control-label col-sm-3">PREFIX</label>
                <div class="col-sm-5">
                    <input type="text" name="prefix" id="prefix" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="configuration_title" class="control-label col-sm-3">Site title</label>
                <div class="col-sm-5">
                    <input type="text" name="configuration_title" id="configuration_title" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="default_lang" class="control-label col-sm-3">Default language</label>
                <div class="col-sm-5">
                    <input type="text" name="default_lang" id="default_lang" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="all_lang" class="control-label col-sm-3">All languages</label>
                <div class="col-sm-5">
                    <input type="text" name="all_lang" id="all_lang" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="loginurl" class="control-label col-sm-3">Login URL</label>
                <div class="col-sm-5">
                    <input type="text" name="loginurl" id="loginurl" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="campaignid" class="control-label col-sm-3">Campaign ID</label>
                <div class="col-sm-5">
                    <input type="text" name="campaignid" id="campaignid" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="useridurl" class="control-label col-sm-3">User ID URL</label>
                <div class="col-sm-5">
                    <input type="text" name="useridurl" id="useridurl" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="draw_url" class="control-label col-sm-3">Draw URL</label>
                <div class="col-sm-5">
                    <input type="text" name="draw_url" id="draw_url" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="draw_user" class="control-label col-sm-3">Draw user</label>
                <div class="col-sm-5">
                    <input type="text" name="draw_user" id="draw_user" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="draw_pass" class="control-label col-sm-3">Draw pass</label>
                <div class="col-sm-5">
                    <input type="text" name="draw_pass" id="draw_pass" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="recaptcha_key" class="control-label col-sm-3">Recaptcha key</label>
                <div class="col-sm-5">
                    <input type="text" name="recaptcha_key" id="recaptcha_key" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="ganalytics" class="control-label col-sm-3">Google analytics</label>
                <div class="col-sm-5">
                    <input type="text" name="ganalytics" id="ganalytics" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="cache" class="control-label col-sm-3">Cache</label>
                <div class="col-sm-5">
                    <input type="text" name="cache" id="cache" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="domain" class="control-label col-sm-3">Domain</label>
                <div class="col-sm-5">
                    <input type="text" name="domain" id="domain" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="versioning" class="control-label col-sm-3">Versioning</label>
                <div class="col-sm-5">
                    <input type="text" name="versioning" id="versioning" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="debugging" class="control-label col-sm-3">Debugging mode</label>
                <div class="col-sm-5">
                    <select name="debugging" id="debugging" class="form-control">
                        <option value="">...</option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="debugplaincurl" class="control-label col-sm-3">Debug plain curl</label>
                <div class="col-sm-5">
                    <select name="debugplaincurl" id="debugplaincurl" class="form-control">
                        <option value="">...</option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="onepage" class="control-label col-sm-3">One page</label>
                <div class="col-sm-5">
                    <select name="onepage" id="onepage" class="form-control">
                        <option value="">...</option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="general_description" class="control-label col-sm-3">General meta description</label>
                <div class="col-sm-5">
                    <input type="text" name="general_description" id="general_description" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="general_keywords" class="control-label col-sm-3">General meta keywords</label>
                <div class="col-sm-5">
                    <input type="text" name="general_keywords" id="general_keywords" class="form-control">
                </div>
            </div>
            <!-- DEBUGPLAINCURL -->
            <button type="submit" class="btn btn-default button-faux" id="installDB">Install Database</button>
            <input type="hidden" name="action" value="installDB">
        </form>
    </div>
</div>
