$(document).ready(function () {
    $('#menu').slicknav({
        label: '',
        prependTo: '.slick-menu',
        closeOnClick: 'true' // Close menu when a link is clicked.
    });

    $("#form-cupons").submit(function (e) {
        e.preventDefault();
        var formelement = $(this);
        var formvariables = formelement.serialize();
        $.ajax({
            type: "POST",
            url: "includes/formhandlers.php",
            data: formvariables,
            dataType: 'json',
            cache: false,
            async: false,
            success: function (response) {
                grecaptcha.reset();
                $("#message").html(response.message);
                if (response.status == 'success') {
                    $("#formbox").fadeOut(200);
                }
            },
            error: function (response) {
                $("#message").html(response.message);
            }

        });
    });

});

$(function(){
	$('#ct-scroll').mCustomScrollbar();
	$('#faq-scroll').mCustomScrollbar();
	$('#ganhadores-scroll').mCustomScrollbar();
	$('a[rel="relativeanchor"]').click(function(){
		$('html, body').animate({
			scrollTop: $( $.attr(this, 'href') ).offset().top -50
		}, 500);
		return false;
	});
});
