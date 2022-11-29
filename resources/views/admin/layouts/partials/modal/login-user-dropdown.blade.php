<div class="btn-group user-btn-group ms-auto">
    <span>
        <img src="/assets/images/svg/user-icon.svg" alt="user-logo" /></span>
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a href="{{ route('change-password') }}" title="Change Password">
                <button class="dropdown-item" type="button">
                        Change Password
                </button>
            </a>
        </li>
        <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
        </li>
    </ul>
</div>