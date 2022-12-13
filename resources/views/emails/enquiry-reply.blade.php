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
	            style="background-color: white; width: 100%; max-width: 840px; margin-right:auto; margin-left: auto; font-family: 'Open Sans', sans-serif;border:1px solid #e4e8eb;padding: 0px; color: #353a3a;" aria-describedby="Recieved your query">
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
	                <td style="padding-left: 40px; font-weight: bold; font-size: 26px;">
	                    Your query has a response from HERA Admin.
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 59px; font-weight: bold; font-size: 26px;">
	                    Issue ID: HR00{{$enquiry['id']}}
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 15px; font-weight: bold; font-size: 18px;">
	                    Your Query:
	                </td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px; line-height: 22px; padding: 0 51px 45px 40px;">
	                    {{$enquiry['message']}}
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 15px; font-weight: bold; font-size: 18px;">
	                    Response from HERA Admin:
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 32px; font-size: 18px;">
	                    {{$enquiry['admin_reply']}}
	                </td>
	            </tr>
	            <tr>
	                <td style="padding-left: 40px; padding-bottom: 42px; font-weight: bold; font-size: 16px;">
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