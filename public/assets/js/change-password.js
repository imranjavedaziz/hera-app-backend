$(document).ready(function () {
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
});