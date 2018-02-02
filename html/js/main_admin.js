jQuery(function ($) {

    $(document).ready(function () {
        $('#installDbModal').modal('show');
    });

    $('#edit-content').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontname', 'fontsize', 'color', 'strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph', ' style']],
            ['height', ['height', 'table', 'hr']],
            ['misc', ['codeview', 'fullscreen', 'undo', 'redo']],
            ['mybutton', ['hello']],
            ['highlight', ['highlight']]
        ],
        buttons: {
            hello: function (context) {
                var ui = $.summernote.ui;

                // create button
                var button = ui.button({
                    contents: '<i class="fa fa-child"/> Hello',
                    tooltip: 'hello',
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.insertText', 'hello');
                    }
                });

                return button.render(); // return button as jquery object
            },
            highlight: function (context) {
                var ui = $.summernote.ui;
                // create button
                var button = ui.button({
                    contents: 'Highlight',
                    tooltip: 'Testing summernote',
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.pasteHTML', '<span class="color:red;">'+window.getSelection().toString()+'</span>');
                    }
                });
                return button.render(); // return button as jquery object
            }
        }
    });

    'fontname', 'fontsize', 'color', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'
    $('.summernote-area .note-codable').unbind('keyup');
    $('.summernote-area .note-codable').keyup(function () {
        $(this).closest('.note-editing-area').find('.note-editable').html(this.value);
        $(this).closest('.summernote-area').find('#edit-content').html(this.value);
    });
    $(document).ready(function () {
        $('#submitForm').click(function () {
            $('#content').val($('.note-editable').html());
            //            alert($("#content").val());
            $("#targetform").submit();
        });

    });

    $('[data-toggle="tooltip"]').tooltip();

//    $("#sortable").sortable({
//        stop: function (event, ui) {
//            $(".menu-resort").css("display", "inline-block");
//        }
//    });
//
     $('#sortable').sortable({
        connectWith: '#sortable',
        beforeStop: function(ev, ui) {
            if ($(ui.item).hasClass('hasItems') && $(ui.placeholder).parent()[0] != this) {
                $(this).sortable('cancel');
            }
        },stop: function (event, ui) {
            $(".menu-resort").css("display", "inline-block");
        }
    });
    $('ul.sortable').sortable({
        connectWith: 'ul.sortable',
        stop: function (event, ui) {
            $(".menu-resort").css("display", "inline-block");
        }
    });


    $("#sortable").disableSelection();

    $(document).on('click', '.config-edit', function () {
        $('#configErrMsg').empty();
        var configId = $(this).data('configelem');
        $('#insertConfigBtn').attr('data-configid', configId);
        var values = {
            'action': 'fillConfigForm',
            'configId': configId
        };
        $.ajax({
            type: "POST",
            data: values,
            url: "admin_formhandlers.php",
            success: function (msg) {
                var configObj = JSON.parse(msg);
                $('#configKeyname').val(configObj.keyname);
                $('#configValue').val(configObj.value);
                $('#configNotes').val(configObj.notes);
                $('#configmodalid').val(configId);
            },
            error: function (request, status, error) {
                // $('#configErrMsg').val();
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var errString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        errString += obj[key] + '<br>';
                    }
                }
                $('#configErrMsg').append('<span class="error-msg">' + errString + '</span>');
            }
        });
    });

    $(document).on('click', '#insertConfigBtn', function (e) {
        $('#configErrMsg').empty();
        e.preventDefault();
        var configElemId = $(this).data('configid');
        var configFormData = $('#insertConfigForm').serialize();
        $.ajax({
            type: "POST",
            data: configFormData,
            url: "admin_formhandlers.php",
            success: function (msg) {
                var configObj = JSON.parse(msg);
                if (configObj.returnedId === 'new') {
                    $('.config-entry-body').append(configObj.updatedConfigEntry);
                } else {
                    configElemId = parseInt(configElemId);
                    var newRow = $("#config-entry-row-id-" + configObj.returnedId + "");
                    newRow.replaceWith(configObj.updatedConfigEntry);
                }
                $('[data-toggle="tooltip"]').tooltip();
                $('#insertConfigModal').modal('hide');
            },
            error: function (request, status, error) {
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var errString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        errString += obj[key] + '<br>';
                    }
                }
                $('#configErrMsg').append('<span class="error-msg">' + errString + '</span>');
            },
            complete: function () {

            }
        });
    });

    $(document).on('click', '.deleteConfig', function (e) {
        if (!confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε στο συγκεκριμένο στοιχείο?')) {
            e.preventDefault();
        } else {
            e.preventDefault();
            var deleteElemId = $(this).data('deleteid');
            var values = {
                'action': 'deleteConfig',
                'deleteElemId': deleteElemId
            };
            $.ajax({
                type: "POST",
                data: values,
                url: "admin_formhandlers.php",
                success: function (msg) {
                    $('#config-entry-row-id-' + deleteElemId).remove();
                },
                error: function (request, status, error) {
                    $('#configErrMsgs').empty();
                    var jsonObj = request.responseText;
                    var obj = JSON.parse(jsonObj);
                    var errString = "";
                    for (var key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            errString += obj[key] + '<br>';
                        }
                    }
                    $('#configErrMsgs').append('<span class="error-msg">' + errString + '</span>');
                    $("html, body").animate({
                        scrollTop: "0"
                    }, 500);
                }
            });
        }
    });

    $(document).on('click', '.winner-edit', function () {
        $('#winnerErrMsg').empty();
        var winnerId = $(this).data('winnerelem');
        $('#insertWinnerBtn').attr('data-winnerelem', winnerId);
        var values = {
            'action': 'fillWinnerForm',
            'winnerId': winnerId
        };
        $.ajax({
            type: "POST",
            data: values,
            url: "admin_formhandlers.php",
            success: function (msg) {
                var winnerObj = JSON.parse(msg);
                $('#winnerName').val(winnerObj.name);
                $('#winnerPrize').val(winnerObj.prize);
                $('#winnerTitle').val(winnerObj.title);
                $('#winnerEmail').val(winnerObj.email);
                $('#winnerPhone').val(winnerObj.phone);
                $('#winnerDate').val(winnerObj.winnerdate);
                $('#winnermodalid').val(winnerId);
            },
            error: function (request, status, error) {
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var errString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        errString += obj[key] + '<br>';
                    }
                }
                $('#winnerErrMsg').append('<span class="error-msg">' + errString + '</span>');
            }
        });
    });

    $(document).on('click', '#insertWinnerBtn', function (e) {
        $('#winnerErrMsg').empty();
        e.preventDefault();
        var winnerElemId = $(this).data('winnerid');
        var winnerFormData = $('#insertWinnerForm').serialize();
        $.ajax({
            type: "POST",
            data: winnerFormData,
            url: "admin_formhandlers.php",
            success: function (msg) {
                var winnerObj = JSON.parse(msg);
                if (winnerObj.returnedId === 'new') {
                    $('.winner-entry-body').append(winnerObj.updatedWinnerEntry);
                } else {
                    var newRow = $("#winner-entry-row-id-" + winnerObj.returnedId + "");
                    newRow.replaceWith(winnerObj.updatedWinnerEntry);
                }
                $('#insertWinnerModal').modal('hide');
            },
            error: function (request, status, error) {
                $('#winnerErrMsg').val();
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var errString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        errString += obj[key] + '<br>';
                    }
                }
                $('#winnerErrMsg').append('<span class="error-msg">' + errString + '</span>');
            },
            complete: function () {

            }
        });
    });

    $(document).on('click', '.deleteWinner', function (e) {
        if (!confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε στο συγκεκριμένο στοιχείο?')) {
            e.preventDefault();
        } else {
            e.preventDefault();
            var deleteElemId = $(this).data('deleteid');
            var values = {
                'action': 'deleteWinner',
                'deleteElemId': deleteElemId
            };
            $.ajax({
                type: "POST",
                data: values,
                url: "admin_formhandlers.php",
                success: function (msg) {
                    $('#winnerErrMsgs').empty();
                    $('#winner-entry-row-id-' + deleteElemId).remove();
                },
                error: function (request, status, error) {
                    $('#winnerErrMsgs').empty();
                    var jsonObj = request.responseText;
                    var obj = JSON.parse(jsonObj);
                    var errString = "";
                    for (var key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            errString += obj[key] + '<br>';
                        }
                    }
                    $('#winnerErrMsgs').append('<span class="error-msg">' + errString + '</span>');
                    $("html, body").animate({
                        scrollTop: "0"
                    }, 500);
                }
            });
        }
    });

    $(document).on('click', '.menu-resort', function (e) {
        var updateArr = [],updateArrSub ={},
            pos = 0,possub=0,t ,parentel;
        $('#menuErrMsgsout').empty();
        for (var i = 1; i < ($("#sortable li.main").length + 1); i++) {
            parentel=$("#sortable li.main:nth-child(" + i + ")").find('.page-element-row .slugvalue').text();

            updateArr[pos] = parentel;
            if ( $("#sortable li.main:nth-child(" + i + ") .sortable").length ) {
                updateArrSub[parentel]=[];
                for (t = 1; t < ($("#sortable li.main:nth-child(" + i + ") .sortable li").length + 1); t++) {
                    updateArrSub[parentel][possub] = $("#sortable li.main:nth-child(" + i + ")  .sortable li:nth-child(" + t + ")").find('.page-element-row .subslugvalue').text();
                    possub++;
                }
                possub=0;
            }
            pos++;
        }

        var values = {
            'action': 'resortMenuItems',
            'updateArr': updateArr,
            'updateArrSub':updateArrSub,
        };
        console.log(updateArrSub);
        $.ajax({
            type: "POST",
            data: values,
            url: "admin_formhandlers.php",
            success: function (msg) {
                location.reload();
            },
            error: function (request, status, error) {
                var jsonObj = request.responseText;
                alert(jsonObj);
//                var obj = JSON.parse(jsonObj);
//                var errString = "";
//                for (var key in obj) {
//                    if (obj.hasOwnProperty(key)) {
//                        errString += obj[key] + '<br>';
//                    }
//                }
//                $('#menuErrMsgsout').append('<span class="requred-fields"><b>ERROR: </b>' + errString + '</span>');
//                $("html, body").animate({
//                    scrollTop: "0"
//                }, 500);
            }
        });
    });

    $(document).on('click', '.deleteMenuItem', function (e) {
        if (!confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε στο συγκεκριμένο στοιχείο?')) {
            e.preventDefault();
        } else {
            e.preventDefault();
            var deleteElemSlug = $(this).data('deleteslug');
            var values = {
                'action': 'deleteMenuItem',
                'deleteElemSlug': deleteElemSlug
            };
            $.ajax({
                type: "POST",
                data: values,
                url: "admin_formhandlers.php",
                success: function (msg) {
                    //                    var obj = JSON.parse(msg);
                    location.reload();
                },
                error: function (request, status, error) {
                    $('#menuErrMsgsout').empty();
                    var jsonObj = request.responseText;
                    var obj = JSON.parse(jsonObj);
                    var errString = "";
                    for (var key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            errString += obj[key] + '<br>';
                        }
                    }
                    $('#menuErrMsgsout').append('<span class="requred-fields"><b>ERROR: </b>' + errString + '</span>');
                    $("html, body").animate({
                        scrollTop: "0"
                    }, 500);
                }
            });
        }
    });

    $(document).on('click', '#insertmenuBtn', function (e) {
        $('#menuErrMsgs').empty();
        e.preventDefault();
        var menuFormData = $('#insertMenuForm').serialize();
        $.ajax({
            type: "POST",
            data: menuFormData,
            url: "admin_formhandlers.php",
            success: function (msg) {
                location.reload();
            },
            error: function (request, status, error) {
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var errString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        errString += obj[key] + '<br>';
                    }
                }
                $('#menuErrMsgs').append('<span class="errormsg">' + errString + '</span>');
            },
            complete: function () {}
        });
    });

    $(document).on('click', '#installDB', function (e) {
        e.preventDefault();
        var installDBFormData = $('#basicConfigForm').serialize();
        $.ajax({
            type: "POST",
            data: installDBFormData,
            url: "admin_formhandlers.php",
            beforeSend: function () {
                $('#installDBMsgs').empty();
                var cog_loader = '<div class="cog-loader"><i class="fa fa-cog fa-spin" style="font-size:150px;color:#aaa;"></i></div>';
                $('.veil').removeClass('hide');
                $('.veil').append(cog_loader);
            },
            success: function (msg) {
                $('.veil').addClass('hide');
                $('#installDBMsgs').empty();
                var jsonObj = msg;
                var obj = JSON.parse(jsonObj);
                var msgString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        msgString += obj[key] + '<br>';
                    }
                }
                $('#installDBMsgs').append('<span class="success-msg">' + msgString + '</span>');
                document.getElementById("basicConfigForm").reset();
            },
            error: function (request, status, error) {
                $('.veil').addClass('hide');
                $('#installDBMsgs').empty();
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var msgString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        msgString += obj[key] + '<br>';
                    }
                }
                $('#installDBMsgs').append('<span class="error-msg">' + msgString + '</span>');
            }
        });
    });


    $(document).on('change', '#srvath-chk', function (e) {
        if ($('#srvath-chk:checkbox:checked').length > 0) {
            $('.user-auth-input').fadeIn(500);
        } else {
            $('.user-auth-input').fadeOut(500);
        }
    });

    $(document).on('click', '#apitesterBtn', function (e) {
        e.preventDefault();
        var apitesterFormData = $('#apitester').serialize();
        $.ajax({
            type: "POST",
            data: apitesterFormData,
            url: "admin_formhandlers.php",
            beforeSend: function () {
                $('#apitesterMsgs').empty();
                var cog_loader = '<div class="cog-loader"><i class="fa fa-cog fa-spin" style="font-size:150px;color:#aaa;"></i></div>';
                $('.veil').removeClass('hide');
                $('.veil').append(cog_loader);
            },
            success: function (msg) {
                $('.veil').addClass('hide');
                $('#apitesterMsgs').empty();

                try {
                    jQuery.parseJSON(msg)
                    //must be valid JSON
                    $('#apitesterMsgs').append('<pre>' + JSON.stringify(msg, undefined, 2).replace(/\\/g, "") + '</pre>');
                } catch (e) {
                    //must not be valid JSON
                    $('#apitesterMsgs').append('<pre style="white-space:pre-wrap;word-break:break-word;">' + msg + '</pre>');
                }
                document.getElementById("basicConfigForm").reset();
            },
            error: function (request, status, error) {
                $('.veil').addClass('hide');
                $('#apitesterMsgs').empty();
                var jsonObj = request.responseText;
                var obj = JSON.parse(jsonObj);
                var msgString = "";
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        msgString += obj[key] + '<br>';
                    }
                }
                $('#apitesterMsgs').append('<span class="error-msg">' + msgString + '</span>');
            }
        });
    });
}($));
