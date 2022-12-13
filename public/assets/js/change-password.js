$(document).ready(function () {

    $("#alert-msg-box").delay(3000).fadeOut(800);

    $(".eye-img").click(function() {
        var input = $('#floatingPassword');
        if(input.val() == '') {
            return false;
        }
        if (input.attr("type") == "password") {
            $(this).attr('src', "/assets/images/svg/eye-close.svg");
            input.attr("type", "text");
        } else {
            $(this).attr('src', "/assets/images/svg/eye-open.svg");
            input.attr("type", "password");
        }
    });

    $(document).on('submit','#changePassword', function (e) {
        const current_pass = $("#floatingPassword").val();
        const new_pass = $("#floatingNewPassword").val();
        const confirm_pass = $("#floatingConfirmPassword").val();
        if(current_pass.length == 0 && new_pass.length == 0 && confirm_pass.length == 0){
            e.preventDefault();
            $("#alert-msg-box").show();
            $("#alert-error-msg").text("Please provide all the mandatory details.");
            $("#alert-msg-box").delay(3000).fadeOut(800);
            return false;
        }
    });

    $(document).on('focus','input', function (e) {
        var id = $(this).attr('id')
        $("."+id).hide();
        $('#'+id).removeClass('error')
    });

    $('input').keypress(function( e ) { 
        if(e.which === 32) {
            return false;
        }
    })
});