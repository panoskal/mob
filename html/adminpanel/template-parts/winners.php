<?php
$requestedtable=$_REQUEST["data"];
$html= $_REQUEST["data"];
$header_title='Edit '.$_REQUEST["data"];
$info='The list with the winners. the admin can add, remove and edit the list with the winners.';
?>

<div id="winnerErrMsgs"></div>

<div id="insertWinnerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div id="winnerErrMsg"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="add-winners-modal">
                <form id="insertWinnerForm" action="admin_formhandlers.php" method="post">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="winnerName">Name</label>
                            <input name="name" type="text" class="form-control" id="winnerName" placeholder="Name">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="winnerPrize">Prize</label>
                            <input name="prize" type="text" class="form-control" id="winnerPrize" placeholder="Prize">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="winnerTitle">Title</label>
                            <input name="title" type="text" class="form-control" id="winnerTitle" placeholder="Title">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="winnerEmail">E-mail</label>
                            <input name="email" type="email" class="form-control" id="winnerEmail" placeholder="E-mail">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="winnerPhone">Phone</label>
                            <input name="phone" type="text" class="form-control" id="winnerPhone" placeholder="Phone">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="winnerDate">Date</label>
                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd" data-date-update-view-date="false">
                                <input name="winnerdate" type="text" class="form-control datepicker" id="winnerDate" placeholder="Date">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="">&nbsp;</label>
                            <input type="hidden" name="winnermodalid" id="winnermodalid">
                            <button type="submit" name="insertWinner" id="insertWinnerBtn" class="button-faux" data-winnerid>Submit</button>
                            <input type="hidden" name="action" value="insertWinner">
                        </div>
                    </div>
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
    <div class="add-winner">
        <label for="add-config-btn"></label>
        <button class="add-winner-btn button-faux winner-edit" data-toggle="modal" data-target="#insertWinnerModal" data-winnerelem="new">Insert Winner <i class="fa fa-plus-square" aria-hidden="true"></i></button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="winner-label first-table-row page-element-row">Name</th>
                <th class="winner-label first-table-row page-element-row">Prize</th>
                <th class="winner-label first-table-row page-element-row">Title</th>
                <th class="winner-label first-table-row page-element-row">E-mail</th>
                <th class="winner-label first-table-row page-element-row">Date</th>
                <th class="winner-label first-table-row page-element-row">Edit</th>
                <th class="winner-label first-table-row page-element-row">Delete</th>
            </tr>
        </thead>
        <tbody class="winner-entry-body"><?php
        $wwinnerEntries = Winner::getAllWinners();
        if (!empty($wwinnerEntries) && is_array($wwinnerEntries)) {
            foreach($wwinnerEntries as $winnerVal) {
        ?>

            <tr id="winner-entry-row-id-<?php echo $winnerVal->id; ?>" class="winner-entry-row">
                <td class="winner-name"><?php echo !empty($winnerVal->name)? $winnerVal->name : '' ?></td>
                <td class="winner-prize"><?php echo !empty($winnerVal->prize)? $winnerVal->prize : '' ?></td>
                <td class="winner-title"><?php echo !empty($winnerVal->title)? $winnerVal->title : '' ?></td>
                <td class="winner-email"><?php echo !empty($winnerVal->email)? $winnerVal->email : '' ?></td>
                <td class="winner-date"><?php echo $winnerVal->winnerdate !== "0000-00-00"? $winnerVal->winnerdate : '' ?></td>
                <td class="winner-edit" data-winnerelem="<?php echo !empty($winnerVal->id)?$winnerVal->id : ''; ?>"  data-toggle="modal" data-target="#insertWinnerModal"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></td>
                <td class="winner-delete"><i class="fa fa-trash deleteWinner" aria-hidden="true" data-deleteid="<?php echo !empty($winnerVal->id)?$winnerVal->id : ''; ?>"></i></td>
            </tr>

            <?php
            }
        }
        ?></tbody>
    </table>
</div>
