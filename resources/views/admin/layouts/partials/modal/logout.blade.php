<!--  start modalLogout  -->

<div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-logout">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="{{ asset('assets/images/svg/cross.svg')}}" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="logout-wrapper">
                    <h3>Log Out?</h3>
                    <h5>Are you sure you want to log out?</h5>
                    <div class="logout-footer">
                        <button type="button" class="btn-hidden" data-bs-dismiss="modal">Not now</button>
                        <button type="button" class="btn-primary btn-logout" id="btn-logout">Log out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  End modalLogout  -->