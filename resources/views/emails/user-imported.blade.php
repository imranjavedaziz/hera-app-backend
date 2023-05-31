@extends('emails.layouts.base-template')
@section('content')
<table
style="background: #f2f2f2; border: 0; margin: auto; width: 100%; font-family: 'Open Sans', sans-serif; height: 100%;" aria-describedby="Account Verify">
<!-- // new table -->
<tr>
    <th scope="col">
    </th>
</tr>
<tr>
    <td>
        <table
            style="background-color: white; width: 100%; max-width: 840px; margin:0 auto; font-family: 'Open Sans', sans-serif;border:0;padding: 0px; color: #353a3a; border-collapse: collapse;" aria-describedby="Account Verify">
            <tr>
                <th scope="col">
                </th>
            </tr>
            <tr>
                <td style="text-align: center; padding-top: 45px; padding-bottom: 41px;">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="155" height="auto">
                </td>
            </tr>
            <tr>
                <td style="text-align: center; padding-bottom: 22px; font-weight: bold; font-size: 26px;">
                    Hello {{ $user['first_name'] }}!
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 8px 40px;">
                    Congratulations on being registered as an ’{{ $user['role']['name'] }}’ on HERA Family Planning! We are excited to have you join our community of parents, surrogate mothers, egg donors & sperm donors.
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 8px 40px;">
                    To get started, please download the "HERA Family Planning!" mobile app from the App Store or Google Play. Once you have downloaded the app, open it and login in using the below mentioned credentials.
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
					Mobile Number: <span style="font-weight: bold;">{{ $user['phone_no'] }}</span><br> Password:  <span style="font-weight: bold;">{{ $password  }}</span>
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
                Once you have logged in, you will be able to:<br>
                @if($user->role_id == 2)
                <span style="font-weight: bold;">- Browse profiles of available surrogate mothers, egg donors & sperm donors.</span><br>
                <span style="font-weight: bold;">- Connect & chat with other users. </span><br>
                <span style="font-weight: bold;">- Send Payment to users. </span>
                @else
                <span style="font-weight: bold;">- Browse profiles of available parents.</span><br>
                <span style="font-weight: bold;">- Connect & chat with other users. </span><br>
                <span style="font-weight: bold;">- Request Payment from parents. </span>
                @endif
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; padding: 0 40px 27px 40px; font-weight: bold; line-height: 25px;">
                    Regards,
                    <br />
                    HERA Team
                </td>
            </tr>
            <tr>
                <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 17px;">
                    <img src="{{ asset('assets/images/logo-gray.png') }}" alt="Logo" width="40" height="auto" style="margin-bottom: 10px;"><br />
                    You have received this email as a registered user of HERA Family Planning Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:support@makingbabyconnection.com" style="font-weight: bold; color: #87857e;">support@makingbabyconnection.com</a>
                    <div><a href="https://makingbabyconnection.com/terms-of-service/" style="font-weight: bold; color: #87857e;">Terms Conditions</a> | <a href="https://makingbabyconnection.com/privacy-policy/" style="font-weight: bold; color: #87857e;">Privacy Policy</a></div>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
@stop