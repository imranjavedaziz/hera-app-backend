@extends('emails.layouts.base-template')
@section('content')
	<table
	style="background: #f2f2f2; border: 0; margin: auto; width: 100%; font-family: 'Open Sans', sans-serif; height: 100%;" aria-describedby="Account Verify">
	<!-- // new table -->
	<tr>
		<th>
		</th>
	</tr>
	<tr>
	    <td>
	        <table
	            style="background-color: white; border: 0; width: 100%; max-width: 840px; margin-right:auto; margin-left: auto; font-family: 'Open Sans', sans-serif;border:1px solid #e4e8eb;padding: 0px; color: #353a3a;" aria-describedby="Account Verify">
	            <tr>
	                <th>
	                </th>
	            </tr>
				<tr>
	                <td style="text-align: center; padding-top: 73px; padding-bottom: 50px;">
	                    <img src="{{ asset('images/mbc-logo-coloured.png') }}" alt="Logo" width="155" height="auto">
	                </td>
	            </tr>
	            <tr>
	                <td style="text-align: center; padding-bottom: 22px; font-weight: bold; font-size: 26px;">
	                    Welcome {{ $user['first_name'] }}!
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
	                    You have successfully started your <span style="font-weight: bold;">{{ $user['role']['name'] }}</span> journey on HERA. Please use the below mentioned code to verify your email address. Match & Chat with the potential Surrogate Mothers/Egg Donors/Sperm Donors.
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 36px; padding: 0 40px 23px 40px; color: #5abcec; font-weight: 800;">
	                    0  9  8  1  0  9
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 13px; padding: 0 40px 30px 40px; color: #ff4544;">
	                    <em>Note: This code will expire in 30 mins</em>
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; padding: 0 40px 27px 40px; font-weight: bold; line-height: 25px;">
	                    Best Wishes,<br />
	                    Team HERA
	                </td>
	            </tr>
	            <tr>
	                <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 17px;">
	                    <img src="{{ asset('images/mbc-logo-black-white.png') }}" alt="Logo" width="40" height="auto" style="margin-bottom: 10px;"><br />
	                    You have received this email as a registered user of HERA Application. If you have any questions, please fill the Inquiry Form using our mobile application or email us at <a href="mailto:help@hera.com" style="font-weight: bold; color: #87857e;">help@hera.com</a>
	                </td>
	            </tr>
	        </table>
	    </td>
	</tr>
	</table>
	<!-- end template body -->
@stop