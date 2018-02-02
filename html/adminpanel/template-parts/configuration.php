<?php
$requestedtable=$_REQUEST["data"];
$html=$_REQUEST["data"];
$header_title='Edit '.$_REQUEST["data"];
$info='All the availiable configuration for the site.';
?>

<div id="configErrMsgs"></div>

<div id="insertConfigModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div id="configErrMsg"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="add-config-modal">
                <form id="insertConfigForm" action="admin_formhandlers.php" method="post">
                    <div class="form-group">
                        <label for="configKeyname">Key</label>
                        <input name="keyname" type="text" class="form-control" id="configKeyname" placeholder="Key">
                    </div>
                    <div class="form-group">
                        <label for="configValue">Value</label>
                        <input name="value" type="text" class="form-control" id="configValue" placeholder="Value">
                    </div>
                    <div class="form-group">
                        <label for="configNotes">Notes</label>
                        <textarea name="notes" id="configNotes" class="form-control" cols="30" rows="4"></textarea>
                    </div>
                    <input type="hidden" name="configmodalid" id="configmodalid">
                    <button type="submit" name="insertConfig" id="insertConfigBtn" class="button-faux" data-configid>Submit</button>
                    <input type="hidden" name="action" value="insertConfig">
                </form>
            </div>
        </div>

    </div>
</div>
<div class="small-info">
    <?php echo $info;?>
</div>
<div class="header">
    <?php echo $header_title;?>
</div>
<div class="">
    <div class="add-config">
        <label for="add-config-btn"></label>
        <button class="add-config-btn button-faux config-edit" data-toggle="modal" data-target="#insertConfigModal" data-configelem="new">Insert Configuration Value <i class="fa fa-plus-square" aria-hidden="true"></i></button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="config-label col-sm-4 first-table-row page-element-row">Key <span class="small-info">hover on title to see usage</span></th>
                <th class="config-label col-sm-6 first-table-row page-element-row">Value</th>
                <th class="config-label col-sm-1 first-table-row page-element-row">Edit</th>
                <th class="config-label col-sm-1 first-table-row page-element-row">Delete</th>
            </tr>
        </thead>
        <tbody class="config-entry-body"><?php
        $configEntries = Configuration::getAllConfiguration();
        if (!empty($configEntries) && is_array($configEntries)) {
            foreach($configEntries as $configVal) {
        ?>

            <tr id="config-entry-row-id-<?php echo $configVal->id; ?>" class="config-entry-row">
                <td class="config-keyname col-sm-4"><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo !empty($configVal->notes)? $configVal->notes : ''; ?>"><?php
                        echo !empty($configVal->keyname)? $configVal->keyname : '';
                    ?></a></td>
                <td class="config-value col-sm-6"><?php
                    echo !empty($configVal->value)? $configVal->value : '';
                ?></td>
                <td class="config-edit col-sm-1" data-configelem="<?php echo $configVal->id ?>"  data-toggle="modal" data-target="#insertConfigModal"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></td>
                <td class="config-delete col-sm-1"><i class="fa fa-trash deleteConfig" aria-hidden="true" data-deleteid="<?php echo $configVal->id; ?>"></i></td>
            </tr>

            <?php
            }
        } else {
            echo '<h5><span class="error-msg">There are no configuration entries found.</span></h5>';
        }
        ?></tbody>
    </table>
    <?php // echo $_SESSION['lang']; ?>
</div>
