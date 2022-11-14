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
                        <button type="button" class="btn-primary btn-logout">Log out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  End modalLogout  -->

<!--  start deactivated modal  -->

<div class="modal fade" id="modalDeactivated" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-logout">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="{{ asset('assets/images/svg/cross.svg')}}" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="logout-wrapper">
                    <h3>Deactivate Jeff?</h3>
                    <h5>Are you sure you want to deactivate this user?</h5>
                    <div class="logout-footer">
                        <button type="button" class="btn-hidden" data-bs-dismiss="modal">Not now</button>
                        <button type="button" class="btn-primary btn-logout">DEACTIVATE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  End deactivated modal  -->

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
                            <div class="date">Joined on: Sep 3, 2021</div>
                            <div class="name">Henry Lowe</div>
                            <div class="name-id">Sperm Donor, #SD5882</div>
                            <div class="location">Kentucky, 60065</div>
                            <div class="phoneno">Phone Number: 938 983 (9920)</div>
                            <div class="email">Email: henrylowe948@yahoo.com</div>
                        </div>
                        <div class="user-profile-right">
                            <img src="./assets/images/people3.jpeg" alt="Profile-logo">
                        </div>
                    </div>
                    <div class="user-profile-details">
                        <div class="profile-desc">Age: <span>29 yrs</span></div>
                        <div class="profile-desc">Height: <span>5 ft 9 in</span></div>
                        <div class="profile-desc">Weight: <span>40 pounds</span></div>
                        <div class="profile-desc">Occupation: <span>Data Analyst</span></div>
                        <div class="profile-desc">Race: <span>Native American</span></div>
                        <div class="profile-desc">Ethnicity: <span>Native American</span></div>
                        <div class="profile-desc">Mother's Ethnicity: <span>Native American</span></div>
                        <div class="profile-desc">Father's Ethnicity: <span>Native American</span></div>
                        <div class="profile-desc">Hair Color: <span>Black</span></div>
                        <div class="profile-desc">Eye Color: <span>Amber</span></div>
                    </div>
                    <div class="user-profile-desc">I give priority to health and play a wide range of sports. I have a good exposure to different cultures of the world. I value the donation programmes & every parent who needs support.</div>
                    <div class="img-wrapper">
                        <img src="./assets/images/people1.jpeg" alt="Image">
                        <img src="./assets/images/people2.jpeg" alt="Image">
                        <img src="./assets/images/people3.jpeg" alt="Image">
                        <img src="./assets/images/people1.jpeg" alt="Image">
                        <img src="./assets/images/people2.jpeg" alt="Image">
                        <img src="./assets/images/people3.jpeg" alt="Image">
                        <img src="./assets/images/people1.jpeg" alt="Image">
                    </div>
                    <div class="vedio-title">Donor has uploaded a short clip</div>
                    <div class="vedio-sec">
                        <video width="245" height="138" controls poster="{{ asset('assets/images/people1.jpeg')}}">
                            <source src="movie.mp4" type="video/mp4">
                            <source src="movie.ogg" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="deactivate-user">Deactivate this User</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  End User details modal  -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/jquery-3.6.1.min.js') }} "></script>

    <!-- end main wrapper -->
    <script type="text/javascript">
    $(".btn-logout").click(function() {
        window.location.href = "/admin/logout";
    });
    </script>