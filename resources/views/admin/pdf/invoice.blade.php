<!DOCTYPE html>
<html lang="en">

<head>
    <title>MBC | Payment Invoice</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="assets/images/favicon.ico" sizes="32x32">
    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
</head>
<body style="margin: 0; padding: 0; background: #FFFFFF;">
<!-- begin template body -->
<table
style="border: 0; margin: auto; width: 100%; font-family: 'Open Sans', sans-serif;" aria-describedby="Payment Invoice">
<!-- // new table -->
<tr>
    <th scope="col">
    </th>
</tr>
<tr>
    <td>
        <table
            style="background-color: white; width: 100%; max-width: 600px; margin-right:auto; margin-left: auto; font-family: 'Open Sans', sans-serif;padding: 0px; color: #353a3a;" aria-describedby="Payment Invoice">
            <tr>
                <th scope="col">
                </th>
            </tr>
            <tr>
                <td style="padding:10px 0 33px 40px;">
                    <img src="{{public_path('images/logo.svg')}}" alt="Logo" width="100" height="auto">
                </td>
            </tr>
            <tr>
                <td style="padding:0 48px 33px 40px; font-weight: bold; font-size: 26px;">
                    Payment Invoice
                </td>
            </tr>
            <tr>
                <td style="padding:0 48px 0 40px;">
                    <table style="margin-bottom: 30px;" aria-describedby="Payment Invoice">
                        <tr style="display: none;">
                            <th scope="col">
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 49px; height: 49px; border-radius: 50%; overflow: hidden; margin-right: 10px; min-width: 49px;">
                                    <img src="{{$user->profile_pic}}" alt="Profile-logo" width="52" height="auto" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 18px;font-weight: bold;">{{CustomHelper::fullName($user)}}</div>
                                <div style="font-size: 14px;">{{$user->email}}</div>
                            </td>
                        </tr>
                </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="border: 1px solid #ededed; margin: 0 40px; font-size: 15px; border-collapse: collapse;" aria-describedby="Payment Invoice">
                        <tr style="display: none;">
                            <th scope="col">
                            </th>
                        </tr>
                        <?php
                            $purchasedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_start)->format('M d, Y');
                            $billedDate = \Carbon\Carbon::parse($subscriptionDetail->current_period_end)->format('M d, Y');
                            ?>
                        <tr style="background-color: #f7f5f0; border: 1px solid #ededed;"><td colspan="2" style="padding: 18px 30px; font-weight: bold;">Billing Details</td></tr>
                        <tr style="border: 1px solid #ededed;"><td style="padding: 18px 30px;">Subscription Purchased</td><td style="text-align: right; padding: 18px 30px; font-weight: bold;">{{$subscriptionDetail->subscriptionPlan->name}}</td></tr>
                        <tr style="border: 1px solid #ededed;"><td style="padding: 18px 30px;">Transaction ID</td><td style="text-align: right; padding: 18px 30px; font-weight: bold;">{{$subscriptionDetail->original_transaction_id}}</td></tr>
                        <tr style="border: 1px solid #ededed;"><td style="padding: 18px 30px;">Billed on</td><td style="text-align: right; padding: 18px 30px; font-weight: bold;">{{$purchasedDate}}</td></tr>
                        <tr style="border: 1px solid #ededed;"><td style="padding: 18px 30px;">Invoice Period</td><td style="text-align: right; padding: 18px 30px; font-weight: bold;">{{$purchasedDate}} - {{$billedDate}}</td></tr>
                        <tr style="background-color: #f7f5f0;border: 1px solid #ededed;"><td style="padding: 18px 30px;">Amount Paid</td><td style="text-align: right; padding: 18px 30px; font-weight: bold;">${{$subscriptionDetail->price}}</td></tr>
                        <tr style="border: 1px solid #ededed; font-weight: bold;"><td colspan="2" style="padding: 18px 30px;">The payment is made via @if ($subscriptionDetail->device_type == 'ios') Apple @else Google @endif Subscription. Subscription will renew automatically on {{$billedDate}} at the current rate of US${{$subscriptionDetail->price}} a month.</td></tr>
                        <tr>
                            <td colspan="2">
                                <table style="border-spacing: 0;" aria-describedby="Payment Invoice">
                                    <tr style="display: none;">
                                        <th scope="col">
                                        </th>
                                    </tr>
                                    <tr style="background-color: #353a3a;color: #fff;">
                                        <td style="padding: 25px 0 42px 30px;">
                                            <img src="{{public_path('images/logo.png')}}" alt="Profile-logo" width="61" height="auto">
                                        </td>
                                        <td style="padding: 25px 30px 42px 30px;font-size: 13px; line-height: 1.31;font-family: 'Open Sans', sans-serif; font-weight: 300;">
                                            You have received this email as a registered user of HERA Application. If you have any questions, please fill the Support Form using our mobile application or email us at <a href="mailto:support@makingbabyconnection.com" style="font-weight: bold; color: #fff;" target = "_blank">support@makingbabyconnection.com</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<!-- end template body -->

</body>
</html>