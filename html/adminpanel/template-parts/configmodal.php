<div id="insertConfigModal<?php echo !empty($configVal->id) ? $configVal->id : ''; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="add-config-modal">
                <form id="insertConfigForm<?php echo !empty($configVal->id) ? $configVal->id : ''; ?>" action="#" method="post">
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
                    <button type="submit" name="insertConfig" class="button-faux insertConfigBtn" data-configid="<?php echo !empty($configVal->id) ? $configVal->id : 'new'; ?>">Submit</button>
                    <input type="hidden" name="action" value="insertConfig">
                </form>
            </div>
        </div>

    </div>
</div>
