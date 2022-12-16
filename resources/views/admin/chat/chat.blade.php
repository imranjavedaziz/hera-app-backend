
@extends('admin.layouts.admin_base')
@section('content')
  <!-- start main wrapper -->
                <div class="main-right-wrapper">
                    <div class="dashboard-container">
                        <div class="user-management-header">
                            @include('admin.layouts.partials.modal.login-user-dropdown')
                        </div>
                        <h1 class="section-title">Chat</h1>
                    </div>
                    <div class="chat-wrapper">
                        <div class="chat-wrapper-left">
                            <div class="chat-search">
                                <form class="search-input-wrapper">
                                    <input class="form-control search-input" type="search" placeholder="Search" aria-label="Search" id="search">
                                    <img src="{{ asset('assets/images/svg/search.svg')}}" alt="Search" class="search-img">
                                    <span class="search-close d-none"><img src="{{ asset('assets/images/icon-close-circled.svg')}}" alt="Search-close"></span>
                                </form>
                            </div>
                            <div class="chat-left-containt">
                            </div>
                        </div>
                        <div class="chat-wrapper-right">
                            <div class="chat-header">
                                <div class="user-chat-profile">
                                    <div class="profile-logo">
                                        <img id="receiverImage" class="d-none" src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                    </div>
                                    <div class="profile-detail">
                                        <div class="user-name" id="receiverRole"></div>
                                        <div class="user-id" id="receiverName" data-recevierId=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-container">
                                <!-- For empty chat section-->
                                <div class="empty-msg d-none">No Messages Yet</div>
                                <!-- For chat section -->
                                <div class="msg-wrapper">
                                </div>
                            </div>
                            <div class="chat-footer">
                                <div class="chat-textarea-sec">
                                    <textarea class="form-control" placeholder="Write a message" id="message" name="message" disabled = "disabled"></textarea>
                                    <button type="button" class="btn-primary btn-send reply-btn">SEND</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection

@section('script')
<script src="https://www.gstatic.com/firebasejs/7.15.5/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.5/firebase-database.js"></script>
<script src="{{ asset('assets/js/firebase.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var user_data = '<?php echo $user; ?>';
        $('.chat-left-containt').html('');
        $('.msg-wrapper').html('');
        var database = firebase.database();
        var adminId = '<?php echo $adminId; ?>';
        var env = '<?php echo $env; ?>';
        var userCollection = database.ref(env+'/Users/'+adminId+'/Friends');
        chatList();
        function chatList() {
            var chatUser = [];
            userCollection.orderByChild('adminChatTime').on("child_added", function(snapshot) {
                var childData = snapshot.val();
                var time = childData.time;
                var adminChatTime = childData.adminChatTime;
                var date = (childData.message) ? getChatDate(time) : '';
                var statusId = childData.status_id;
                $('.chat-left-containt').append('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+childData.recieverName+'" userImage="'+childData.recieverImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'" data-date="'+adminChatTime+'" statusId="'+statusId+'">'
                                    +'<div class="user-chat-left">'
                                        +'<div class="user-logo">'
                                            +'<img src='+childData.recieverImage+' alt="user-logo">'
                                        +'</div>'
                                        +'<div class="user-detail">'
                                            +'<div class="user-name">'+childData.recieverName+'</div>'
                                            +'<div class="user-msg">'+childData.message+'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="user-chat-right">'
                                        +'<div class="chat-date">'+date+'</div>'
                                    +'</div>'
                                +'</div>');
                $('.chat-left-containt .user-chat-sec').sort(SortByChatTime).appendTo('.chat-left-containt');
                function SortByChatTime(a, b){
                    return ($(b).data('date')) < ($(a).data('date')) ? -1 : 1;
                }
                if(user_data){
                    var user = JSON.parse(user_data);
                    var middle_name = (user.middle_name != null) ? user.middle_name : '';
                    var userId = user.id;
                    var name = user.first_name + ' ' + middle_name + ' ' + user.last_name;
                    var image = user.profile_pic;
                    var roleId = user.role_id;
                    var username = user.username;
                    var statusId = user.status_id;
                    var roleData = getRoleData(roleId);
                    $(".user-chat-sec[userid='" + userId + "']").addClass("active");
                    updateUserChatProfile(image, roleData, name, username, userId, statusId);
                }else {
                    setTimeout(function() {
                    $('.chat-left-containt').children().first().click();
                }, 1000);
                }
            });
        }
        userCollection.on("child_changed", function(snapshot) {
                var childData = snapshot.val();
                var time = childData.time;
                var adminChatTime = childData.adminChatTime;
                var date = getChatDate(time);
                var statusId = childData.status_id;
                $(".user-chat-sec[userid='" + childData.recieverId + "']").remove();
                $('.chat-left-containt').prepend('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+childData.recieverName+'" userImage="'+childData.recieverImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'" data-date="'+adminChatTime+'" statusId="'+statusId+'">'
                                    +'<div class="user-chat-left">'
                                        +'<div class="user-logo">'
                                            +'<img src='+childData.recieverImage+' alt="user-logo">'
                                        +'</div>'
                                        +'<div class="user-detail">'
                                            +'<div class="user-name">'+childData.recieverName+'</div>'
                                            +'<div class="user-msg">'+childData.message+'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="user-chat-right">'
                                        +'<div class="chat-date">'+date+'</div>'
                                    +'</div>'
                                +'</div>');
            $(".user-chat-sec[userid='" + childData.recieverId + "']").addClass("active");
            });
        $('.search-close').click(function(){
            $('#search').val('');
            $('.search-close').addClass("d-none");
            $('.chat-left-containt').html('');
            chatList();
        })

        $(document).on('click', '.user-chat-sec', function(){
            $('.msg-wrapper').html('');
            $(".user-chat-sec").removeClass("active");
            $(this).addClass("active");
            var userId = $(this).attr("userId");
            var name = $(this).attr("userFullName");
            var image = $(this).attr("userImage");
            var roleId = $(this).attr("userRole");
            var username = $(this).attr("username");
            var statusId = $(this).attr("statusid");
            var roleData = getRoleData(roleId);
            updateUserChatProfile(image, roleData, name, username, userId, statusId);
        });

        function updateUserChatProfile(image, roleData, name, username, userId, statusId) {
            $("#receiverImage").removeClass("d-none");
            $("#receiverImage").attr("src",image);
            var userStatus = (statusId != 1) ? 'style="background-color: #ff5353;"' : 'style="display: none"';
            $("#receiverRole").html(roleData+' <span class="profile-status" '+userStatus+'>INACTIVE</span>');
            $("#receiverName").html(name+', <span>'+username+'</span>');
            $('#receiverName').attr('data-recevierId', userId);
            $('#receiverName').attr('data-statusId', statusId);
            var msgObj = getMessageCollectionObject(userId);
            getMessageList(msgObj, userId);
        }

        function getMessageCollectionObject(userId) {
            var chatNode = userId+'-'+adminId;
            var messageCollection = database.ref(env+'/Messages/'+chatNode).orderByChild('time');
            return messageCollection;
        }

        function sendMessage(msg,userId) {
            /** Save message */
            var timestampRow = Date.now();
            var chatNode = userId+'-'+adminId;
            var msgObj = database.ref(env+'/Messages/'+chatNode+ '/' + timestampRow)
            var message = {
                from : adminId,
                text: msg,
                time: new Date().getTime()
            }
            msgObj.set(message);

            /** Update user message in admin chat list */
            database.ref(env+'/Users/'+adminId+'/Friends/'+userId).update({
                message: msg,
                read: 0,
                chat_start: 1,
                time: new Date().getTime(),
                adminChatTime: new Date().getTime()
            });

            /** Update user message in user chat list */
            database.ref(env+'/Users/'+userId+'/Friends/'+adminId).update({
                message: msg,
                read: 0,
                chat_start: 1,
                time: new Date().getTime()
            });
            $(".user-chat-sec[userid='" + userId + "']").find(".user-msg").html(msg);
        }

        function getMessageList(msgObj, userId) {
            $('.empty-msg').removeClass("d-none");
            msgObj.off("child_added");
            $('.msg-wrapper').html('');
            $('#receiverName').attr('data-timeKey', '')
            var statusId = $('#receiverName').attr('data-statusId');
            $("#message").removeAttr('disabled');
            if (statusId !=1 ) {
                $('#message').attr('disabled','disabled');
            }
            msgObj.on("child_added", (snapshot) => {
                var msgData = snapshot.val();
                if ($('#receiverName').attr('data-timeKey') == '') {
                    $('#receiverName').attr('data-timeKey', snapshot.key);
                }
                if (msgData) {
                    $('.empty-msg').addClass("d-none");
                    $('.msg-wrapper').removeClass("d-none");
                    $('.msg-wrapper').append(checkMessage(msgData));
                    $('.msg-wrapper').scrollTop($('.msg-wrapper')[0].scrollHeight);
                } else {
                    $('.empty-msg').removeClass("d-none");
                    $('.msg-wrapper').addClass("d-none");
                }
            }) 
        }

        function checkMessage(msg) {
            var time = msg.time;
            var date = DisplayTime(time);
            var wrapperClass = 'msg-wrapper-left';
            if (typeof msg.text == 'undefined'){
                return;
            }
            if (msg.from == adminId) {
                var wrapperClass = 'msg-wrapper-right';
            }
            return ` <div class="${wrapperClass}">
                            <div class="massage">${msg.text}</div>
                            <div class="time">${date}</div>
                        </div>`
        }

            $("#search").keyup(function() {
                $('.chat-left-containt').html('');
                var name = $(this).val().toLowerCase();
                if (name == '') {
                    $('.search-close').addClass("d-none");
                    chatList();
                } else {
                    $(".search-close").removeClass("d-none");
                userCollection.orderByChild('receiverSearchName').startAt(name).endAt(name+"\uf8ff").on("value", function(snapshot) {
                    snapshot.forEach(function(childSnapshot) {
                        var childData = childSnapshot.val();
                        var profileImage = childData.recieverImage;
                        var recieverName = childData.recieverName;
                        var message = childData.message;
                        var time = childData.time;
                        var statusId = childData.status_id;
                        var date = getChatDate(time);
                        $('.chat-left-containt').append('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+recieverName+'" userImage="'+profileImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'" data-date="'+time+'" statusId="'+statusId+'">'
                                    +'<div class="user-chat-left">'
                                        +'<div class="user-logo">'
                                            +'<img src='+profileImage+' alt="user-logo">'
                                        +'</div>'
                                        +'<div class="user-detail">'
                                            +'<div class="user-name">'+recieverName+'</div>'
                                            +'<div class="user-msg">'+message+'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="user-chat-right">'
                                        +'<div class="chat-date">'+date+'</div>'
                                    +'</div>'
                                +'</div>'
                        );
                    })
                });
                }
                userCollection.off('value');
                });
                $('.reply-btn').click(function(){
                    var userId = $('#receiverName').attr('data-recevierId');
                    var msg = $('#message').val();
                    if(msg === "") {
                        return false;
                    }
                    sendMessage(msg, userId);
                    $('#message').val("");
                    sendPushNotification(userId, msg);
                })

                $('#message').keypress(function(event){
                    var key = event.which;
                    if(key == '13') {
                        userCollection.off("value");
                        console.log('enter press');
                        $('.reply-btn').click();
                        return false;
                    }
                })

                function sendPushNotification(userId, message) {
                    $.ajax({
                        url: '/admin/chat/send-push-notification',
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "receiver_id": userId,
                            "message": message,
                            "title" : 'HERA Support sent you a message'
                        },
                        dataType: 'json',
                        success: function (msg) {

                        },
                         error: function(jqXHR, textStatus, errorThrown) {
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                    });
                }
        });

        function getChatDate(unixTimeStamp) {
            var date = new Date(unixTimeStamp);
            var today = new Date();
            var formattedDate = dateFormate(date);
            var todayDate = dateFormate(today);
            var yesterdayDate = new Date(new Date().getTime());
            yesterdayDate.setDate(new Date().getDate()-1);
            var yesterday = dateFormate(yesterdayDate);
            var month = today.toLocaleString('default', { month: 'short' });
            var dateName = today.getDate();
            switch (true) {
                case (formattedDate == todayDate): 
                    day = 'Today';
                    break;
                case (formattedDate == yesterday):
                    day = 'Yesterday';
                    break;
                default:
                    day = moment(unixTimeStamp).format('MMM DD');
            }
            return day;
        }

        function dateFormate(date) {
            var year = date.getFullYear();
            var month = date.getMonth();
            var day = date.getDate();
            newDate = year + '-' + month + '-' + day;
            return newDate;
        }
        function DisplayTime(unixTimeStamp) {
            var date = new Date(unixTimeStamp);
            var today = new Date();
            var formattedDate = dateFormate(date);
            var todayDate = dateFormate(today);
            var yesterdayDate = new Date(new Date().getTime());
            yesterdayDate.setDate(new Date().getDate()-1);
            var yesterday = dateFormate(yesterdayDate);
            switch (true) {
                case (formattedDate == todayDate): 
                    day = moment(unixTimeStamp).format('hh:mm A');
                    break;
                case (formattedDate == yesterday):
                    day = 'Yesterday';
                    break;
                default:
                    day = moment(unixTimeStamp).format('MMM DD hh:mm A');
            }
            return day;
        };

        function getRoleData(roleId) {
            switch (true) {
                case (roleId == '2'): 
                    role = 'Intended Parent';
                    break;
                case (roleId == '3'):
                    role = 'Surrogate Mother';
                    break;
                case (roleId == '4'):
                    role = 'Egg Donor';
                    break;
                case (roleId == '5'):
                    role = 'Sperm Donor';
                    break;
                default:
                role = 'Intended Parent';
                break;
            }
            return role;
        }
    </script>
@endsection