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
                    // $('#first-name-error').empty()
                    // $('#last-name-error').empty()
                    // $('#gender-error').empty()
                    // $('#email-error').empty()
                    // $('#phone-error').empty()
                    // $('#password-error').empty()
                    // $('#captcha-error').empty()
                    if (data.errors) {
                      console.log(data.errors)
                    //     // $('#phone-number').attr('disabled', true);
                    //     if (data.errors.first_name) {
                    //         $('#first-name-error').html(data.errors.first_name[0]);
                    //     }
                    //     if (data.errors.last_name) {
                    //         $('#last-name-error').html(data.errors.last_name[0]);
                    //     }
                    //     if (data.errors.gender) {
                    //         $('#gender-error').html(data.errors.gender[0]);
                    //     }
                    //     if (data.errors.email) {
                    //         $('#email-error').html(data.errors.email[0]);
                    //     }
                    //     if (data.errors.phone) {
                    //         $('#phone-error').html(data.errors.phone[0]);
                    //     }
                    //     if (data.errors.password) {
                    //         $('#password-error').html(data.errors.password[0]);
                    //     }

                    //     var captcha = "g-recaptcha-response";
                    //     if (data.errors[captcha]) {
                    //         $('#captcha-error').html(data.errors[captcha][0]);
                    //     }
                    }
                    // if (data.success) {
                    //     $('#registerForm')[0].reset();
                    //     $('.error-response').empty();
                    //     $('.success-response').html("@lang('labels.frontend.modal.registration_message')");
                    //     alert("@lang('labels.frontend.modal.registration_message')");
                    //     location.href = "{{ route('frontend.auth.select-login') }}";
                    // }
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