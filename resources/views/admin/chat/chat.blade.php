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
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Jane Gregory</div>
                                            <div class="user-msg">OK.</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Today</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You.</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec active">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Evan Ball</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Oct 28</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Cecelia Lowe</div>
                                            <div class="user-msg">Can we connect in sometime Can we connect in sometime</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src="{{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
                                <div class="user-chat-sec">
                                    <div class="user-chat-left">
                                        <div class="user-logo">
                                            <img src=" {{ asset('assets/images/people3.jpeg')}}" alt="user-logo">
                                        </div>
                                        <div class="user-detail">
                                            <div class="user-name">Lloyd Baldwin</div>
                                            <div class="user-msg">Thank You so much</div>
                                        </div>
                                    </div>
                                    <div class="user-chat-right">
                                        <div class="chat-date">Yesterday</div>
                                    </div>
                                </div>
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
                                        <div class="user-id" id="receiverName">Jane Gregory, <span>SM0283</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-container">
                                <!-- For empty chat section-->
                                <div class="empty-msg ">No Messages Yet</div>
                                <!-- For chat section -->
                                <div class="msg-wrapper">
                                   <div class="msg-wrapper-left">
                                       <div class="massage">Hey! I hope it worked</div>
                                       <div class="time">10:16 am</div>
                                   </div>
                                   <div class="msg-wrapper-right">
                                        <div class="massage">Be that person that is always willing to help others. Most of us could have used someone to talk to...to help us get through problems!</div>
                                        <div class="time">10:16 am</div>
                                    </div>
                                    <div class="msg-wrapper-right">
                                        <div class="massage">Be that person that is always willing to help others. Most of us could have used someone to talk to...to help us get through problems!</div>
                                        <div class="time">10:16 am</div>
                                    </div>
                                    <div class="msg-wrapper-left">
                                        <div class="massage">Hey! I hope it worked</div>
                                        <div class="time">10:16 am</div>
                                    </div>
                                     <div class="msg-wrapper-right">
                                        <div class="massage">Be that person that is always willing to help others. Most of us could have used someone to talk to...to help us get through problems!</div>
                                        <div class="time">10:16 am</div>
                                    </div>
                                    <div class="msg-wrapper-left">
                                        <div class="massage">Hey! I hope it worked</div>
                                        <div class="time">10:16 am</div>
                                    </div>
                                    <div class="msg-wrapper-right">
                                        <div class="massage">Be that person that is always willing to help others.</div>
                                        <div class="time">10:16 am</div>
                                    </div>
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
        var chatNode = adminId+'-'+localStorage.getItem('userId');
        var messageCollection = database.ref(env+'/Messages/'+chatNode);
        chatList(userCollection);
        function chatList() {
            userCollection.on("value", function(snapshot) {
                console.log(snapshot.val());
            snapshot.forEach(function(childSnapshot) {
                var childData = childSnapshot.val();
                localStorage.setItem('userId', childData.recieverId);
                var time = childData.time;
                var date = getChatDate(time);
                $('.chat-left-containt').append('<div class="user-chat-sec" userId="'+childData.recieverId+'" userFullName="'+childData.recieverName+'" userImage="'+childData.recieverImage+'" userRole="'+childData.currentRole+'" username="'+childData.recieverUserName+'">'
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
                })
                $('.user-chat-sec').click(function(){
                    console.log('chat section');
                    $('.msg-wrapper').html('');
                    $(".active").removeClass("active");
                    $(this).addClass("active");
                    var userId = $(this).attr("userId");
                    var name = $(this).attr("userFullName");
                    var image = $(this).attr("userImage");
                    var roleId = $(this).attr("userRole");
                    console.log('userRole'+roleId);
                    var username = $(this).attr("username");
                    var roleData = getRoleData(roleId);
                    $("#receiverImage").attr("src",image);
                    $("#receiverRole").html(roleData);
                    $("#receiverName").html(name+', <span>'+username+'</span>');
                    localStorage.setItem('userId', userId);
                    getMessageList();
                })

                $('.search-close').click(function(){
                    $('#search').val('');
                    $('.search-close').addClass("d-none");
                    $('.chat-left-containt').html('');
                    chatList(userCollection);
                })
            });
        }

        function getMessageCollectionObject() {
            var chatNode = adminId+'-'+localStorage.getItem('userId');
            console.log('ff'+chatNode);
            var messageCollection = database.ref(env+'/Messages/'+chatNode);
            return messageCollection;
        }

        function sendMessage() {
            var msgObj = getMessageCollectionObject();
            var msg = $('#message').val();
            var message = {
                from : adminId,
                text: msg,
                time: new Date().getTime()
            }
            msgObj.push().set(message);
            updateUserdata(msg);
        }

        function getMessageList() {
            $('.msg-wrapper').html('');
            var msgObj = getMessageCollectionObject();
            msgObj.on("child_added", (snapshot) => {
                if (snapshot.val()) {
                    $('.empty-msg').addClass("d-none");
                    $('.msg-wrapper').removeClass("d-none");
                    $('.msg-wrapper').append(checkMessage(snapshot.val()));
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
            if (msg.from == adminId) {
                return `<div class="msg-wrapper-left">
                            <div class="massage">${msg.text}</div>
                            <div class="time">${date}</div>
                        </div>`
            } else {
                return ` <div class="msg-wrapper-right">
                            <div class="massage">${msg.text}</div>
                            <div class="time">${date}</div>
                        </div>`
            }
        }

            $("#search").keyup(function() {
                $('.chat-left-containt').html('');
                var name = $(this).val();
                if (name == '') {
                    $('.search-close').addClass("d-none");
                    chatList(userCollection);
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
                        $('.chat-left-containt').append('<div class="user-chat-sec">'
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
                    sendMessage();
                    $('#message').val("");
                    $('#message').attr('placeholder','Write a message');
                })

                $('#message').keypress(function(event){
                    if(event.keyCode == '13') {
                        sendMessage();
                        $('#message').val("");
                        $('#message').attr('placeholder','Write a message');
                    }
                })

                messageCollection.on("value", (snapshot) => {
                    if (snapshot.val()) {
                        $('.empty-msg').addClass("d-none");
                        $('.msg-wrapper').removeClass("d-none");
                        $('.msg-wrapper').append(checkMessage(snapshot.val()));
                        $(".msg-wrapper").animate({ scrollTop: $('.msg-wrapper').prop("scrollHeight")}, 100);
                    } else {
                        $('.empty-msg').removeClass("d-none");
                        $('.msg-wrapper').addClass("d-none");
                    }
                })

                function updateUserdata(msg){
                    var userId = localStorage.getItem('userId');
                    database.ref(env+'/Users/'+adminId+'/Friends/'+userId).update({
                        message: msg,
                        time: new Date().getTime()
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
                    day = moment(unixTimeStamp).format('Y-m-d');
            }
            return day;
        };

        function getRoleData(roleId) {
            console.log('role'+roleId);
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