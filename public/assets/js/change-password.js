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
    });
});

function password_check(id) {
  const pass = $("#"+id).val();
  const lable_text = $("."+id+"Label").text();
  const regex = /[a-zA-Z]/;
  console.log(pass.length);
  console.log(lable_text);
  if(pass.length == 0) {
    $("."+id).show();
    $("."+id).text("Please enter "+ lable_text.toLowerCase());
  } else if(regex.exec(pass.charAt(0)) == null) {
    $("."+id).show();
    $("."+id).text(lable_text + " should start with an alphabet");
  } else {
    $("."+id).hide();
  }
}