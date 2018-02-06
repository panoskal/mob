$(document).ready(function () {

    $(".scrollable").niceScroll({cursorcolor:"#ff0000", cursorwidth : "10px", autohidemode: false, background: "#f0ec41", cursorborderradius : "0px"});

    function createAutoClosingAlert(selector, delay) {
       var alert = $(selector).alert();
       window.setTimeout(function() {
            alert.fadeTo(delay, 500).slideUp(500, function(){
                alert.alert('close');
            });
        }, delay);
    }

    $("#formlogin").submit(function(e) {
   		e.preventDefault();
    	var formelement = $(this);
        var formvariables = formelement.serialize();
        $.ajax({
            type: "POST",
            url:  "includes/formhandlers.php",
            data: formvariables,
            dataType: 'json',
            cache: false,
            async: false,
            success: function(response)
            {
            $("#vercodeimage").attr("src", "php/captchaimage.php?width=175&amp;height=40&amp;characters=6&amp;rand="+Math.random());
            $("#message").html(response.message);
            createAutoClosingAlert(".alert-dismissible", 1000);
                if(response.status == 'success')  {
                	if (ajax_action=='login') {
                        $('#loginbox').slideUp('slow', function (){
                            $('#userbox').slideDown('slow');
                            $("#greeting").fadeOut(function() {
                              $(this).text(response.greeting);
                            }).fadeIn();
                        });

                    }
                }
             }

        });
    });

});

$(function(){

});
