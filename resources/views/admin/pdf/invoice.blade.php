<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MBC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0,">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="x-apple-disable-message-reformatting">
    <meta name="keywords" content="" />
    <!--<link rel="icon" href="{{ asset('assets/images/favicon.ico')}}" sizes="32x32" /> -->
    <!-- Font -->
    <!--<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin="anonymous"/> -->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />-->
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet" crossorigin="anonymous"
    />

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css "
      rel="stylesheet"
      crossorigin="anonymous"
    />
    <style type="text/css">
    html,
body {
  /**height: 100%; **/
}

body {
  width: 70%;
  /**height: 100%;**/
  font-family: 'Open Sans', sans-serif;
  font-weight: 400;
  font-size: 5px;
  line-height: 1.2;
  font-style: normal;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -webkit-tap-highlight-color: transparent !important;
  font-feature-settings: "liga"; }
.payment-container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  flex-wrap: wrap;
  width: 100%;
  height: 100vh;
  padding: 50px 0 51px 0;
  position: relative;
  color: #353a3a; }
  .payment-container .payment-wrapper {
    flex: inherit;
    width: 100%;
    max-width: 816px;
    padding: 0 52px;
    background-color: #fff; }
    .payment-container .payment-wrapper .app-logo {
      width: 99px;
      height: auto;
      margin-bottom: 30px; }
      .payment-container .payment-wrapper .app-logo img {
        width: 100%;
        height: auto;
        object-fit: cover; }
    .payment-container .payment-wrapper h2 {
      font-family: 'Open Sans', sans-serif;
      font-weight: 700;
      font-size: 26px;
      line-height: normal;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      margin-bottom: 31px; }
    .payment-container .payment-wrapper .billing-wrapper {
      border: 1px solid #ededed; }
      .payment-container .payment-wrapper .billing-wrapper .cell {
        display: flex;
        justify-content: space-between;
        padding: 18px 30px; }
        .payment-container .payment-wrapper .billing-wrapper .cell .bill-title {
          font-family: 'Open Sans', sans-serif;
          font-weight: 400;
          font-size: 15px;
          line-height: normal;
          font-style: normal;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale; }
        .payment-container .payment-wrapper .billing-wrapper .cell .bill-amount {
          font-family: 'Open Sans', sans-serif;
          font-weight: 700;
          font-size: 15px;
          line-height: normal;
          font-style: normal;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          word-break: break-all;
          text-align: right; }
        .payment-container .payment-wrapper .billing-wrapper .cell .bill-weight {
          font-weight: bold; }
      .payment-container .payment-wrapper .billing-wrapper .bill-bg {
        background-color: #f7f5f0; }
      .payment-container .payment-wrapper .billing-wrapper .bill-border {
        border-bottom: solid 1px #ededed; }
      .payment-container .payment-wrapper .billing-wrapper .cell-bottom {
        background-color: #353a3a;
        color: #fff;
        font-family: 'Open Sans', sans-serif;
        font-weight: 400;
        font-size: 13px;
        line-height: normal;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        line-height: 17px;
        display: flex;
        align-items: center;
        padding: 25px 30px 42px 30px; }
        .payment-container .payment-wrapper .billing-wrapper .cell-bottom a {
          font-family: 'Open Sans', sans-serif;
          font-weight: 700;
          font-size: 13px;
          line-height: normal;
          font-style: normal;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          color: #fff; }
        .payment-container .payment-wrapper .billing-wrapper .cell-bottom .logo-bill {
          margin-right: 31px; }
          .payment-container .payment-wrapper .billing-wrapper .cell-bottom .logo-bill img {
            width: 61px; }
            .sub-profile-wrapper {
  display: flex;
  align-items: center;
  margin-bottom: 43px; }
  .sub-profile-wrapper .profile-logo {
    width: 49px;
    height: 49px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 15px;
    min-width: 49px; }
    .sub-profile-wrapper .profile-logo img {
      width: 100%;
      height: 100%;
      object-fit: cover; }
  .sub-profile-wrapper .profile-detail {
    color: #353a3a; }
    .sub-profile-wrapper .profile-detail .profile-title {
      font-family: 'Open Sans', sans-serif;
      font-weight: 700;
      font-size: 18px;
      line-height: normal;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      margin-bottom: 5px; }
      .sub-profile-wrapper .profile-detail .profile-title span {
        font-family: 'Open Sans', sans-serif;
        font-weight: 400;
        font-size: 14px;
        line-height: normal;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale; }
    .sub-profile-wrapper .profile-detail .profile-email {
      font-family: 'Open Sans', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: normal;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      margin-bottom: 6px; }
    .sub-profile-wrapper .profile-detail .purchase {
      font-family: 'Open Sans', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: normal;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      margin-bottom: 5px; }
      .sub-profile-wrapper .profile-detail .purchase span {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 14px;
        line-height: normal;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale; }
    .sub-profile-wrapper .profile-detail .next-due {
      color: #ff4544;
      font-family: 'Open Sans', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: normal;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale; }
      .sub-profile-wrapper .profile-detail .next-due span {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        font-size: 14px;
        line-height: normal;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale; }
        .mb-30 {
  margin-bottom: 30px; }
  @media print {
}
    </style>
  </head>

  <body>
  <div class="container-fluid" style="scale:0.8">
      <div class="row">
        <div class="col-12 p-0">
            <div class="payment-container">
                <!-- sign in start here -->
                <div class="payment-wrapper">
                    <div class="app-logo">
                        <img src="{{public_path('images/logo.png')}}" alt="app logo" />
                    </div>
                    <h2>Payment Invoice</h2>
                    <div class="sub-profile-wrapper mb-30">
                        <div class="profile-logo">
                            <img src="{{$user->profile_pic}}" alt="Profile-logo">
                        </div>
                        <div class="profile-detail">
                            <div class="profile-title">{{CustomHelper::fullName($user)}}</div>
                            <div class="profile-email">{{$user->email}}</div>
                        </div>
                    </div>
                    <div class="billing-wrapper">
                        <div class="cell bill-bg">
                            <div class="bill-title bill-weight">Billing Details</div>
                        </div>
                        <div class="cell bill-border">
                        <?php
                            $purchasedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_start)->format('M d, Y');
                            $billedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_end)->format('M d, Y');
                            ?>
                            <div class="bill-title">Subscription Purchased</div>
                            <div class="bill-amount">{{$purchasedDate}}</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Transaction ID</div>
                            <div class="bill-amount">{{$subscriptionDetail->original_transaction_id}}</div>
                        </div>
                        <div class="cell bill-border">
                            <div class="bill-title">Billed on</div>
                            <div class="bill-amount">{{$billedDate}}</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title">Invoice Period</div>
                            <div class="bill-amount">{{$purchasedDate}} - {{$billedDate}}</div>
                        </div>
                        <div class="cell bill-bg">
                            <div class="bill-title">Amount Paid</div>
                            <div class="bill-amount">${{$subscriptionDetail->price}}</div>
                        </div>
                        <div class="cell">
                            <div class="bill-title bill-weight mb-25">The payment is made via Apple Subscription. Subscription will renew automatically on {{$billedDate}} at the current rate of US${{$subscriptionDetail->price}} a {{$subscriptionDetail->subscriptionPlan->interval}}.</div>
                        </div>
                        <div class="cell-bottom">
                            <div class="logo-bill">
                                <img src="{{public_path('images/logo.png')}}" alt="Profile-logo" />
                            </div>
                            <div class="bill-desc">You have received this email as a registered user of HERA Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:help@hera.com">help@hera.com</a></div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- end signin container wrapper -->
        </div>
    </div>
  </div>
  <!-- end main wrapper -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!--<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
  ></script>-->
</body>
</html>