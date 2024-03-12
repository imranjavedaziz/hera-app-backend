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
                Your Payment was Received!
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 5px 40px;">
                    Hello {{$data['to_user_first_name']}},
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 27px 40px;">
                {{$data['role']}} #{{$data['username']}} has received the payment of amount ${{number_format($data['amount'], 2)}} sent by you. Please find the transaction details mentioned below for your reference.
                </td>
            </tr>
            <tr>
                <td style="padding: 0 40px 30px 40px;">
                    <table style="width: 100%;border-collapse: collapse;" aria-describedby="Account Verify">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 11px; font-weight: normal; font-size: 14px;">
                            Paid Amount:
                            </td>
                            <td style="padding-bottom: 11px; font-weight: bold; font-size: 14px;text-align: right;">
                            ${{number_format($data['amount'], 2)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 24px; font-weight: normal; font-size: 14px;">
                            Transaction Fee:
                            </td>
                            <td style="padding-bottom: 24px; font-weight: bold; font-size: 14px;text-align: right;">
                            ${{number_format($data['fee'], 2)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal; font-size: 14px;border-top: 2px solid #e4e2d8;padding-top: 20px;">
                            Total Amount:
                            </td>
                            <td style="font-weight: bold; font-size: 14px;text-align: right;border-top: 2px solid #e4e2d8;padding-top: 20px;">
                            ${{number_format($data['net_amount'], 2)}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px; font-style: italic;color: #000;">
                    Your transaction ID is {{$data[PAYMENT_INTENT_ID]}}.
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 0px 40px;font-weight: normal;">
                Transaction Date
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 18px 40px;font-weight: bold;">
                {{$data['transaction_date']}}
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 0px 40px;font-weight: normal;">
                Transaction Time
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 18px 40px;font-weight: bold;">
                {{$data['transaction_time']}}
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
                    You have received this email as a registered user of HERA Family Planning Application. If you have any questions, please fill the Support Form using our mobile application or email us at <a href="mailto:support@makingbabyconnection.com" style="font-weight: bold; color: #87857e;">support@makingbabyconnection.com</a>
                    <div><a href="https://makingbabyconnection.com/terms-of-service/" style="font-weight: bold; color: #87857e;">Terms Conditions</a> | <a href="https://makingbabyconnection.com/privacy-policy/" style="font-weight: bold; color: #87857e;">Privacy Policy</a></div>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>

@stop