
@extends('admin.layouts.admin_base')
@section('content')
  <!-- start main wrapper -->
                <div class="main-right-wrapper">
                    <div class="dashboard-container">
                        <div class="user-management-header">
                            <div class="btn-group user-btn-group ms-auto">
                                <span>
                                    <img src="{{ asset('assets/images/svg/user-icon.svg')}}" alt="user-logo" /></span>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h1 class="section-title">Chat</h1>
                    </div>
                    <div class="chat-wrapper">
                        <div class="chat-wrapper-left">
                            <div class="chat-search">
                                <form class="search-input-wrapper">
                                    <input class="form-control search-input" type="search" placeholder="Search" aria-label="Search" id="search">
                                    <img src="{{ asset('assets/images/svg/search.svg')}}" alt="Search" class="search-img">
                                    <span class="search-close d-none">x</span>
                                </form>
                            </div>
                            <div class="chat-left-containt">
                            </div>
                        </div>
                        <div class="chat-wrapper-right">
                            <div class="chat-header">
                                <div class="user-chat-profile">
                                    <div class="profile-logo">
                                        <img id="receiverImage" src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                    </div>
                                    <div class="profile-detail">
                                        <div class="user-name" id="receiverRole">Lloyd Baldwin</div>
                                        <div class="user-id" id="receiverName" data-recevierId="">Jane Gregory, <span>SM0283</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-container">
                                <!-- For empty chat section-->
                                <div class="empty-msg ">No Messages Yet</div>
                                <!-- For chat section -->
                                <div class="msg-wrapper">
                                </div>
                            </div>
                            <div class="chat-footer">
                                <div class="chat-textarea-sec">
                                    <!-- <input type="text" class="form-control" placeholder="Write a message"> -->
                                    <textarea class="form-control" placeholder="Write a message" id="message" name="message"></textarea>
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
        $('.chat-left-containt').html('');
        $('.msg-wrapper').html('');
        var database = firebase.database();
        var adminId = '<?php echo $adminId; ?>';
        var env = '<?php echo $env; ?>';
        var userCollection = database.ref(env+'/Users/'+adminId+'/Friends');
        chatList();
        var userList = [];
        function chatList() {
            var chatUser = [];
            var count = 0;
            userCollection.on("child_added", function(snapshot) {
                count++;
                var childData = snapshot.val();
                var time = childData.time;
                var date = getChatDate(time);
                obj = {};
                obj.userId = childData.recieverId;
                obj.userFullName = childData.recieverName;
                obj.userImage= childData.recieverImage;
                obj.userRole = childData.currentRole;
                obj.username = childData.recieverUserName;
                obj.date = time;
                userList.push(obj);
                $('.chat-left-containt').append('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+childData.recieverName+'" userImage="'+childData.recieverImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'" data-date="'+time+'">'
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
                if (count == 1) {
                    var roleData = getRoleData(childData.currentRole);
                    updateUserChatProfile(childData.recieverImage, roleData, childData.recieverName, childData.recieverUserName, childData.recieverId);
                }
            });
        }
        $('.search-close').click(function(){
            $('#search').val('');
            $('.search-close').addClass("d-none");
            $('.chat-left-containt').html('');
            chatList();
        })

        $(document).on('click', '.user-chat-sec', function(){
            console.log('chat section');
            $('.msg-wrapper').html('');
            $(".user-chat-sec").removeClass("active");
            $(this).addClass("active");
            var userId = $(this).attr("userId");
            var name = $(this).attr("userFullName");
            var image = $(this).attr("userImage");
            var roleId = $(this).attr("userRole");
            console.log('userRole'+roleId);
            var username = $(this).attr("username");
            var roleData = getRoleData(roleId);
            updateUserChatProfile(image, roleData, name, username, userId);
        });

        function updateUserChatProfile(image, roleData, name, username, userId) {
            console.log('last'+userId);
            $("#receiverImage").attr("src",image);
            $("#receiverRole").html(roleData);
            $("#receiverName").html(name+', <span>'+username+'</span>');
            $('#receiverName').attr('data-recevierId', userId);
            var msgObj = getMessageCollectionObject(userId);
            getMessageList(msgObj, userId);
        }

        function getMessageCollectionObject(userId) {
            var chatNode = userId+'-'+adminId;
            var messageCollection = database.ref(env+'/Messages/'+chatNode).orderByChild('time');
            return messageCollection;
        }

        function sendMessage(msg, userId) {
            /** Save message */
            var msgObj = getMessageCollectionObject(userId);
            var message = {
                from : adminId,
                text: msg,
                time: new Date().getTime()
            }
            msgObj.push().set(message);
            /** Update user message in chat list */
            database.ref(env+'/Users/'+adminId+'/Friends/'+userId).update({
                message: msg,
                read: 0,
                time: new Date().getTime()
            });
        }

        function getMessageList(msgObj, userId) {
            msgObj.off("child_added");
            $('.msg-wrapper').html('');
            msgObj.on("child_added", (snapshot) => {
                var msgData = snapshot.val();
                if (msgData) {
                    $('.empty-msg').addClass("d-none");
                    $('.msg-wrapper').removeClass("d-none");
                    $('.msg-wrapper').append(checkMessage(msgData));
                    $(".msg-wrapper").animate({ scrollTop: $('.msg-wrapper').prop("scrollHeight")}, 100);
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
                var name = $(this).val();
                if (name == '') {
                    $('.search-close').addClass("d-none");
                    chatList();
                } else {
                    $(".search-close").removeClass("d-none");
                userCollection.orderByChild('recieverName').startAt(name).endAt(name+"\uf8ff").on("value", function(snapshot) {
                    console.log(snapshot.val());
                    snapshot.forEach(function(childSnapshot) {
                        var childData = childSnapshot.val();
                        var profileImage = childData.recieverImage;
                        var recieverName = childData.recieverName;
                        var message = childData.message;
                        var time = childData.time;
                        var date = getChatDate(time);
                        $('.chat-left-containt').append('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+recieverName+'" userImage="'+profileImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'" data-date="'+time+'">'
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
                                +'</div>');
                            })
                        });
                    }
                });
                $('.reply-btn').click(function(){
                    var userId = $('#receiverName').attr('data-recevierId');
                    console.log('on reply profile user id'+userId);
                    console.log('send message');
                    var msg = $('#message').val();
                    console.log('This msg sending '+msg);
                    sendMessage(msg, userId);
                    $('#message').val("");
                })

                $('#message').keypress(function(event){
                    var key = event.which;
                    if(key == '13') {
                        console.log('enter press');
                        $('.reply-btn').click();
                        return false;
                    }
                })
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
                    day = month+' '+dateName;
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
                    day = moment(unixTimeStamp).format('MMM DD');
            }
            return day;
        };

        function getRoleData(roleId) {
            switch (true) {
                case (roleId == '2'): 
                    role = 'PARENTS TO BE';
                    break;
                case (roleId == '3'):
                    role = 'SURROGATE MOTHER';
                    break;
                case (roleId == '4'):
                    role = 'EGG DONER';
                    break;
                case (roleId == '5'):
                    role = 'SPERM DONER';
                    break;
                default:
                role = 'PARENTS TO BE';
                break;
            }
            return role;
        }
    </script>
@endsection