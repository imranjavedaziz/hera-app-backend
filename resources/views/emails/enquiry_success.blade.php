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
	            style="background-color: white; width: 100%; max-width: 840px; margin-right:auto; margin-left: auto; font-family: 'Open Sans', sans-serif;border:1px solid #e4e8eb;padding: 0px; color: #353a3a;" aria-describedby="Recieved your query. Thank you">
	            <tr>
	                <th scope="col">
	                </th>
	            </tr>
	            <tr>
	                <td style="padding:73px 0 33px 40px;">
	                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="155" height="auto">
	                </td>
	            </tr>
	            <tr>
	                <td style="padding:0 48px 33px 40px; font-weight: bold; font-size: 26px;">
	                    We have recieved your query. We are looking into the issue and will have an answer for you shortly, Thank you.
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px;">
	                    <table aria-describedby="Recieved your query">
	                        <tr>
	                            <th scope="col">
	                            </th>
	                        </tr>
	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; width:210px; vertical-align:top">
	                                Inquiry Sent By:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px;padding-bottom: 39px; vertical-align:top">
	                                {{$enquiry['name']}}
	                            </td>
	                        </tr>
	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                Issue ID:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                HR00{{$enquiry['id']}}
	                            </td>
	                        </tr>

	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                Email Address:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                {{$enquiry['email']}}
	                            </td>
	                        </tr>

	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                Phone Number:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                {{$enquiry['country_code']}} {{preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '$1-$2 ($3)',$enquiry['phone_no']). "\n"}}
	                            </td>
	                        </tr>

	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                Sent On:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                {{CustomHelper::dateTimeZoneConversion($enquiry->created_at,$enquiry->user_timezone)}}
	                            </td>
	                        </tr>

	                        <tr>
	                            <td style="font-size: 18px; padding-bottom: 39px; vertical-align:top">
	                                Message:
	                            </td>
	                            <td style="font-weight: bold; font-size: 18px; padding-bottom: 26px; padding-right: 58px; vertical-align:top">
	                                {{$enquiry['message']}}
	                            </td>
	                        </tr>
	                    </table>
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 52px; font-weight: bold; font-size: 16px;">
	                    Sent via HERA
	                </td>
	            </tr>
	            <tr>
	                <td style="background-color: #f7f5f0; color: #87857e; font-size: 12px; padding: 19px 40px 25px 40px;line-height: 17px;">
	                    <img src="{{ asset('assets/images/logo-gray.png') }}" alt="Logo" width="40" height="auto" style="margin-bottom: 10px;"><br />
	                    You have received this email as a registered user of HERA Application. If you have any questions, please fill the Support Form using our mobile application or email us at <a href="mailto:help@hera.com" style="font-weight: bold; color: #87857e;">help@hera.com</a>
	                </td>
	            </tr>
	        </table>
	    </td>
	</tr>
	</table>
	<!-- end template body -->
@stop