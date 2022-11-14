$(document).ready(function () {
    $(document).on('click', '#open-detail-modal', function(e){
        console.log('hello');
        e.preventDefault();
        var id = $(this).attr("data-id");
        console.log(id);
        $.ajax({
            url: 'user/'+ id,
            type: 'get',
            dataType: 'json',
            success: function (msg) {
                console.log(msg);
                var middle_name = (msg.middle_name != null) ? msg.middle_name : '';
                console.log(middle_name);
                var status = (msg.status_id == 1) ? 2 : 1
                var status_text = (status == 2) ? 'Deactivate' : 'Activate';
                console.log(status_text);
                if(msg.deleted_at == null){
                    $('#modal-deactivate').attr('data-id' , msg.id)
                    $('#modal-deactivate').attr('data-name' , msg.first_name)
                    $('#modal-deactivate').attr('data-status' , status)
                    $('#modal-deactivate').html(status_text + ' this user.')
                    $('#modal-delete').attr('data-id' , msg.id)
                    $('#modal-delete').attr('data-name' , msg.first_name)
                }else{
                    $('#modal-deactivate').addClass('d-none')
                    $('#modal-delete').addClass('d-none')
                }
                $('.date').html('Joined on: ' +new Date(msg.created_at).toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }));
                $('.name').html(msg.first_name + ' ' + middle_name + ' ' + msg.last_name);
                $('.name-id').html(msg.role + ', ' + msg.username);
                (msg.location != null) ? $('.location').html(msg.location.name + ', ' + msg.location.zipcode) : $('.location').hide();
                $('.phoneno').html('Phone Number: ' + msg.phone_no.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 ($3)"));
                $('.email').html('Email: ' + msg.email);
                $('.user-profile-right img').attr('src', msg.profile_pic)
                $('#age').html('Age: <span>'+ msg.age + ' yrs </span>')
                if (msg.doner_attribute != null) {
                    var inches = msg.doner_attribute.height;
                    var feet = Math.floor(inches / 12);
                    inches %= 12;
                    $('#height').html('Height: <span>' + feet + ' ft ' + inches + ' in </span>')
                    $('#weight').html('Weight: <span>' + msg.doner_attribute.weight + ' pounds </span>')
                    $('#race').html('Race: <span>' + msg.doner_attribute.race + '</span>')
                    $('#eye-colour').html('Hair Color: <span>' + msg.doner_attribute.eye_colour + '</span>')
                    $('#hair-colour').html('Eye Color: <span>' + msg.doner_attribute.hair_colour + '</span>')
                }else{
                    $('#height').hide();
                    $('#weight').hide();
                    $('#race').hide();
                    $('#eye-colour').hide();
                    $('#hair-colour').hide();
                }
                if (msg.user_profile != null) {
                    $('#occupation').html('Occupation: <span>' + msg.user_profile.occupation + '</span>');
                    $('#bio').html( msg.user_profile.bio);
                }else{
                    $('#occupation').hide();
                    $('#bio').hide();
                }
                if(msg.doner_photo_gallery){
                    var img = '';
                    msg.doner_photo_gallery.forEach(function(doner_photo_gallery) {
                        var path = doner_photo_gallery.file_url;
                        img = img.concat('<img src="'+path+'" alt="Image">');
                    });
                    $('.img-wrapper').html(img)
                }else{
                    $('.img-wrapper').hide();
                }
                if(msg.doner_video_gallery != null){
                    var video = document.getElementsByTagName('video')[0];
                    var sources = video.getElementsByTagName('source');
                    sources[0].src = msg.doner_video_gallery.file_url;
                    sources[1].src = msg.doner_video_gallery.file_url;
                    video.load();
                }else{
                    $('.vedio-title').hide();
                    $('.vedio-sec').hide();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
        $('#modalUserDetails').modal('show');
    });

    $(document).on('click', '.modal-deactivate', function(e){
        console.log('hello');
        e.preventDefault();
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        var status = $(this).attr("data-status");
        var status_text = (status == 2) ? 'deactivate' : 'activate';
        $('#deactivate-btn-text').attr('data-id' , id)
        $('#deactivate-btn-text').attr('data-status' , status)
        $('#deactive-name').html(status_text.charAt(0).toUpperCase() + status_text.slice(1) + ' ' + name);
        $('#deactivate-btn-text').html(status_text.toUpperCase());
        $('#status-text').html(status_text);
        $('#modalUserDetails').modal('hide');
        $('#modalDeactivated').modal('show');
    });

    $(document).on('click', '#deactivate-btn-text', function(event){
        var id = $(this).attr("data-id");
        var status = $(this).attr("data-status");
        console.log(id);
        console.log(status);
        let deactivated_by = 0;
        if((status == 2)){
            deactivated_by = 1;
        }
        $.ajax({
            url: 'user/change-status/'+id,
            type: 'put',
            data: {
                "_token": "{{ csrf_token() }}",
                "status_id": status,
                "deactivated_by": deactivated_by,
            },
            beforeSend: function () {
                $('.loader').show();
            },
            complete:function () {
                $('.loader').hide();
            },
            statusCode: {
                200: function (data) {
                    $('#modalDeactivated').modal('hide');
                    $('#deactivate-msg').html(data.message);
                    $("#deactivate-msg-box").show();
                    setTimeout(function() {
                        $("#deactivate-msg-box").hide()
                    }, 5000);
                    if(status == 2){
                        $("#inactive-user"+id).html('Inactive<br><span>(By Admin)</span>');
                        $("#inactive-user"+id).removeClass("d-none");
                        $("#inactive-user"+id).addClass("d-block");
                        $("#active-user"+id).removeClass("d-block");
                        $("#active-user"+id).addClass("d-none");
                        $(".modal-deactivate"+id).html("Activate User");
                    }else{
                        $("#active-user"+id).removeClass("d-none");
                        $("#active-user"+id).addClass("d-block");
                        $("#inactive-user"+id).removeClass("d-block");
                        $("#inactive-user"+id).addClass("d-none");
                        $(".modal-deactivate"+id).html("Deactivate User");
                    }
                    var status_replace = (status == 2) ? "1" : "2"
                    $('.modal-deactivate'+id).attr('data-status' , status_replace)
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    $(document).on('click', '.modal-delete', function(e){
        console.log('hello');
        e.preventDefault();
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        $('#delete-btn-text').attr('data-id' , id)
        $('#delete-name').html('Delete ' + name);
        $('#modalUserDetails').modal('hide');
        $('#modalDeleted').modal('show');
    });

    $(document).on('click', '#delete-btn-text', function(event){
        var id = $(this).attr("data-id");
        $.ajax({
            url: 'user/delete/'+id,
            type: 'delete',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            beforeSend: function () {
                $('.loader').show();
            },
            complete:function () {
                $('.loader').hide();
            },
            statusCode: {
                200: function (data) {
                    $('#modalDeleted').modal('hide');
                    $('#deactivate-msg').html(data.message);
                    $("#deactivate-msg-box").show();
                    setTimeout(function() {
                        $("#deactivate-msg-box").hide()
                    }, 5000);
                    $(".modal-deactivate"+id).hide();
                    $(".modal-delete"+id).hide();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });
});
