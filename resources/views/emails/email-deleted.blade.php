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
                style="background-color: white; width: 100%; max-width: 840px; margin-right:auto; margin-left: auto; font-family: 'Open Sans', sans-serif;border:1px solid #e4e8eb;padding: 0px; color: #353a3a;" aria-describedby="Account Verify">
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
                        Hello {{$user['first_name']}}!
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; line-height: 25px; padding: 0 40px 23px 40px;">
                        Your HERA account has been permanently deleted & cannot be restored. We hope you enjoyed you time on HERA and are looking forward to welcoming you back.
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; padding: 0 40px 27px 40px; font-weight: bold; line-height: 25px;">
                        Regards,<br />
                        HERA
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 1.42;">
                        <img src="{{ asset('assets/images/logo-gray.png') }}" alt="Logo" width="43" height="auto" style="margin-bottom: 5px;"><br />
                        You have received this email as a registered user of HERA Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:help@hera.com" style="font-weight: bold; color: #87857e;">help@hera.com</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <!-- end template body -->
@stop