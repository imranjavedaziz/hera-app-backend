function password_check(id) {
  const pass = $("#"+id).val();
  const lable_text = $("."+id+"Label").text();
  const regex = /[a-zA-Z]/;
  console.log(pass.length);
  console.log(lable_text);
  if(pass.length == 0) {
    $("."+id).show();
    $("#"+id).addClass('error')
    $("."+id).text("Please enter "+ lable_text.toLowerCase());
  } else if(regex.exec(pass.charAt(0)) == null) {
  console.log(id);
    $("."+id).show();
    $("#"+id).addClass('error')
    $("."+id).text(lable_text + " should start with an alphabet");
  } else {
    $("."+id).hide();
    $('#modal-deactivate').removeClass('error')
  }
}