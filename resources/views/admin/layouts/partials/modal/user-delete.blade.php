<!--  start deleted modal  -->
<div class="modal fade" id="modalDeleted" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-logout">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="{{ asset('assets/images/svg/cross.svg')}}" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="logout-wrapper">
                    <h3 id="delete-name"></h3>
                    <h5>All the data related to this account will be deleted permanently & cannot be restored. Do you still want to proceed?</h5>
                    <div class="logout-footer">
                        <button type="button" class="btn-hidden" data-bs-dismiss="modal">Not now</button>
                        <button type="button" class="btn-primary btn-logout" id="delete-btn-text"  data-id="" data-name="">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  End deleted modal  -->