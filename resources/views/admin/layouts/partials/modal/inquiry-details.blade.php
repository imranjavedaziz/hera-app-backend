<!--  Start Inquiries modal  -->
<div class="modal fade" id="modalInquiriesDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-inquiries">
      <div class="modal-content">
        <div class="modal-body">
            <div class="close-btn">
                <img src="/assets/images/svg/cross-big.svg" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
            </div>
            <div class="inquiries-wrapper">
                <h2>Issue ID: <span id="enquiry_id"></span></h2>
                <div class="inquiries-profile">
                    <div class="profile-logo">
                        <img src="" alt="Profile-logo">
                    </div>
                    <div class="profile-detail">
                        <div class="profile-title"></div>
                        <div class="profile-email profile-mail"></div>
                        <div class="profile-email profile-phone"></div>
                    </div>
                </div>
                <div class="profile-content">
                    <div class="title">Sent on: <span id="inquiry_date"></span></div>
                    <div class="desc"></div>
                    <div class="replies"><img src="/assets/images/small-logo.png" alt="logo"> You replied on: <span></span></div>
                    <div class="thanks"></div>
                    <div class="note replied_note"><span class="text-danger">*</span><em>Reply sent to user's email address</em></div>
                </div>
                <div class="inquiries-search-sec">
                    <form>
                        <input type="text" class="form-control reply-input" placeholder="Write a message" value="" required>
                        <button type="submit" class="btn-primary btn-logout reply-btn"></button>
                    </form>
                </div>
                <div class="note reply_note"><span class="text-danger">*</span><em>Your reply will be sent to user's email address</em></div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!--  End Inquiries modal  -->