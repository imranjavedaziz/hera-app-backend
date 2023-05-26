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
                    We are glad to inform you, that we have created your account as ’{{ $user['role']['name'] }}’ on HERA. You can now login & connect with Intended Parents on the platform. The Intended Parents join our platform as they are looking for either a Surrogate Mother or an Egg Donor or a Sperm Donor. Your profile will be visible to the Intended Parents who have set their Preference Criteria that matches your profile description. If they like your profile, they might send you a match request.
                </td>
            </tr>
            <tr>
                <td style="font-size: 14px; line-height: 22px; padding: 0 40px 23px 40px; font-style: italic; color: #000;">
                    <span style="color: #ff4544;">*</span>We value your privacy and so we will not disclose your name to the intended parents. An ID will be displayed on your profile ({{ $user['username']}})
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
					Your login credential is your mobile no <span style="font-weight: bold;">{{ $user['phone_no'] }}</span> and password is  <span style="font-weight: bold;">{{ $password  }}</span>
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; padding: 0 40px 27px 40px; font-weight: bold; line-height: 25px;">
                    Regards,
                    <br />
                    HERA
                </td>
            </tr>
            <tr>
                <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 17px;">
                    <img src="{{ asset('assets/images/logo-gray.png') }}" alt="Logo" width="40" height="auto" style="margin-bottom: 10px;"><br />
                    You have received this email as a registered user of HERA Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:help@hera.com" style="font-weight: bold; color: #87857e;">help@hera.com</a>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
@stop