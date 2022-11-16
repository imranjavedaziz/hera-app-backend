<div class="left-nav-wrapper left-nav-inner-wrapper scrollbar">
    <a href="{{ route('userList') }}" class="logo-container">
        <div class="logo-box">
            <img src="{{ asset('assets/images/logo.png')}}" alt="sidebar-logo">
        </div>
    </a>
    <div class="left-nav">
        <ul>
            <li class="@if (isset($title) && $title=='All Users') active @endif">
                <a href="{{ route('userList') }}" title="All Users">
                    <span class="nav-text">All Users</span>
                 </a>
            </li>
            <li class="@if (isset($title) && $title=='Inquiry') active @endif">
                <a href="{{ route('inquiryList') }}" title="Inquiries">
                    <span class="nav-text">Inquiries</span>
                </a>
            </li>               
            <li class="@if (isset($title) && $title=='Chat') active @endif">
                <a href="{{ route('chatList') }}" title="Chat">
                    <span class="nav-text">Chat</span>
                </a>
            </li>
            <li>
                <a href="#" title="Chat">
                    <span class="nav-text">Subscription</span>
                </a>
            </li>
        </ul>
    </div>
</div>