<div class="veil hide"></div>
<div class="small-info">Check URLS are working properly</div>
<div class="header">URLS</div>

<div class="general-info-line col-sm-10">
    <form method="post" action="" id="apitester">
        <div class="form-group">
            <label for="url">URL</label>
            <input class="form-control" name="srvurl" type="text">
        </div>

        <div class="form-check">
            <div class="form-group">
                <label for="type">TYPE</label>
                <select class="form-control form-control-sm general-dropdown" name="srvtype">
                    <option value="">Select Type</option>
                    <option value="json">json</option>
                    <option value="post">post</option>
                    <option value="get">get</option>
                </select>
            </div>
        </div>

        <div class="checkbox">
            <label><input name="srvathChk" id="srvath-chk" type="checkbox" value="true">Use http authentication?</label>
        </div>

        <div class="form-group user-auth-input">
            <label for="srvuserauth">HTTP Authentication</label>
            <div class="row">
                <div class="form-group col-sm-6">
                    <input class="form-control" name="srvuser" type="text" placeholder="username">
                </div>

                <div class="form-group col-sm-6">
                    <input class="form-control" name="srvpass" type="password" placeholder="passsword">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="type">Headers</label><span class="small-info">&nbsp;&nbsp;Please use json object</span>
            <textarea name="srvheaders" class="form-control" rows="3" cols="10" placeholder="JSON"></textarea>
        </div>

        <div class="form-group">
            <label for="type">Body</label><span class="small-info">&nbsp;&nbsp;Please use json object</span>
            <textarea name="srvbody" class="form-control" rows="3" cols="10" placeholder="JSON"></textarea>
        </div>

        <div class=""></div>
        <div class="form-group">
            <label for="passwordrep">Responce</label>
            <div id="apitesterMsgs" class="responce_area"></div>
        </div>

        <div class="form-group">
            <button type="submit" id="apitesterBtn" class="button-faux"><i class="fa fa-pencil-square-o small-pad" aria-hidden="true"></i>Check URL</button>
            <input type="hidden" name="action" value="apitester">
        </div>
    </form>
</div>
