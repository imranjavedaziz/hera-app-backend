<!--  start User details modal  -->
<div class="modal fade" id="modalUserDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-userdetails">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="{{ asset('assets/images/svg/cross-big.svg')}}" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="users-wrapper">
                    <div class="user-profile-wrapper">
                        <div class="user-profile-left">
                            <div class="date"></div>
                            <div class="name"></div>
                            <div class="name-id"></div>
                            <div class="location"></div>
                            <div class="phoneno"></div>
                            <div class="email"></div>
                            <div class="user-profile-details">
                            <div class="profile-desc" id="age"></div>
                            <div class="profile-desc" id="height"></div>
                            <div class="profile-desc" id="weight"></div>
                            <div class="profile-desc" id="occupation"></div>
                            <div class="profile-desc" id="race"></div>
                            <div class="profile-desc" id="hair-colour"></div>
                            <div class="profile-desc" id="eye-colour"></div>
                        </div>
                        </div>
                        <div class="user-profile-right">
                            <img src="/assets/images/people3.jpeg" alt="Profile-logo">
                        </div>
                    </div>
                    <div class="user-profile-desc" id="bio"></div>
                    <div class="img-wrapper">
                    </div>
                    <div class="vedio-title">Donor has uploaded a short clip</div>
                    <div class="vedio-sec">
                        <video width="245" height="138" controls>
                            <source src="" type="video/mp4">
                            <source src="" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div id="modal-deactivate" class="deactivate-user modal-deactivate" data-id="" data-name="" data-status=""></div>
                    <div class="deactivate-para para-margin">This option will temporarily deactivate the account.</div>
                    <div id="modal-delete" class="deactivate-user modal-delete" data-id="">Delete this User</div>
                    <div class="deactivate-para">This option will permanently delete the account.</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  End User details modal  -->