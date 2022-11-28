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

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {

        $(document).on('submit','#changePassword', function (e) {
            e.preventDefault();
            console.log('he')
            var $this = $(this);
            console.log($this)
            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serializeArray(),
                dataType: $this.data('type'),
                statusCode: {
                    417: function (data) {
                        console.log(data + 'validation')
                    },
                    200: function (data) {
                        console.log(data + 'success data')
                    },
                },
                success: function (data) {
                  console.log(data);
                    if (data.errors) {
                      console.log(data.errors)
                },
                error: function(XMLHttpRequest, errorThrown) {
                    console.log(XMLHttpRequest);
                    // console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    });



});