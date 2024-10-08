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
	                <td style="text-align: center; padding-top: 73px; padding-bottom: 50px;">
	                    <img src="{{ asset('images/mbc-logo-coloured.png') }}" alt="Logo" width="155" height="auto">
	                </td>
	            </tr>
	            <tr>
	                <td style="text-align: center; padding-bottom: 22px; font-weight: bold; font-size: 26px;">
	                    Hello {{ $user['first_name'] }}!
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
	                    <span style="font-weight: bold;">Welcome to HERA</span>
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
	                    You have successfully registered as a <span style="font-weight: bold;">{{ $user['role']['name'] }}</span> on HERA Family Planning.
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 23px 40px;">
	                    <?php if($user['role_id'] != 2) { ?> Explore, match & chat with intended parents<?php }else{ ?> Match & chat with the potential surrogate mothers, egg donors or sperm donors<?php } ?>.
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; padding: 0 40px 27px 40px; font-weight: bold; line-height: 25px;">
	                    Regards,<br />
	                    HERA Team
	                </td>
	            </tr>
	            <tr>
	                <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 17px;">
	                    <img src="{{ asset('images/mbc-logo-black-white.png') }}" alt="Logo" width="40" height="auto" style="margin-bottom: 10px;"><br />
	                    You have received this email as a registered user of HERA Family Planning Application. If you have any questions, please fill the Support Form using our mobile application or email us at <a href="mailto:support@makingbabyconnection.com" style="font-weight: bold; color: #87857e;">support@makingbabyconnection.com</a>
						<div><a href="https://makingbabyconnection.com/terms-of-service/" style="font-weight: bold; color: #87857e;">Terms Conditions</a> | <a href="https://makingbabyconnection.com/privacy-policy/" style="font-weight: bold; color: #87857e;">Privacy Policy</a></div>
					</td>
	            </tr>
	        </table>
	    </td>
	</tr>
	</table>
	<!-- end template body -->
@stop