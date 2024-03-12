<div class="left-nav-wrapper left-nav-inner-wrapper scrollbar">
    <a href="{{ route('userList') }}" class="logo-container">
        <div class="logo-box">
            <img src="{{ asset('assets/images/logo.svg')}}" alt="sidebar-logo">
        </div>
    </a>
    <div class="left-nav">
        <ul>
            <li class="@if (isset($title) && $title=='All Users') active @endif">
                <a href="{{ route('userList') }}">
                    <span class="nav-text">All Users</span>
                </a>
            </li>
            <li class="@if (isset($title) && $title=='Support') active @endif">
                <a href="{{ route('inquiryList') }}">
                    <span class="nav-text">Support Forms</span>
                </a>
            </li>
            <li class="@if (isset($title) && $title=='Chat') active @endif">
                <a href="{{ route('chatList') }}">
                    <span class="nav-text">Chat</span>
                </a>
            </li>
            <li class="@if (isset($title) && $title=='Subscription') active @endif">
                <a href="{{ route('subscriptionList') }}">
                    <span class="nav-text">Subscription</span>
                </a>
            </li>
            <li class="@if (isset($title) && $title=='Payment Requests') active @endif">
                <a href="{{ route('pendingPaymentRequests') }}">
                    <span class="nav-text">Payment Requests</span>
                </a>
            </li>
        </ul>
    </div>
</div>