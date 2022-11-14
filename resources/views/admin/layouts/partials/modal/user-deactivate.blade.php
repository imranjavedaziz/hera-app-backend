<!--  start deactivated modal  -->
<div class="modal fade" id="modalDeactivated" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-logout">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="{{ asset('assets/images/svg/cross.svg')}}" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="logout-wrapper">
                    <h3 id="deactive-name"></h3>
                    <h5>Are you sure you want to <span id="status-text"></span> this user?</h5>
                    <div class="logout-footer">
                        <button type="button" class="btn-hidden" data-bs-dismiss="modal">Not now</button>
                        <button type="button" class="btn-primary btn-logout btn-red" id="deactivate-btn-text"  data-id="" data-name="" data-status=""></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  End deactivated modal  -->