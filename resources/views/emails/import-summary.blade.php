@extends('emails.layouts.base-template')
@section('content')
<table
style="background: #f2f2f2; border: 0; margin: auto; width: 100%; font-family: 'Open Sans', sans-serif; height: 100%;" aria-describedby="Account Verify">
<!-- // new table -->
<tr style="display:none">
    <th scope="col">
    </th>
</tr>
<tr>
    <td>
        <table
            style="background-color: white; width: 100%; max-width: 840px; margin:0 auto; font-family: 'Open Sans', sans-serif;border:0;padding: 0px; color: #353a3a; border-collapse: collapse;" aria-describedby="Account Verify">
            <tr style="display:none">
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
                    Bulk Import Completed!
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 22px 40px;">
                    You had initiated Bulk import of users on the platform & the process is now completed. An email notification has been sent to the accounts that have been added through the Bulk import. Users can find their credentials in the email notification to log in to their HERA account. Once they have logged in for the first time, their status will be updated to Active on HERA Dashboard.
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 18px 40px;font-weight: bold;">
                    Import Status
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 8px 40px;">
                    Total Records: <strong>{{ $totalRecords }} Users</strong>
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 8px 40px;">
                    Success Records: <strong>{{ $insertedRecords }} Users</strong>
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; line-height: 22px; padding: 0 40px 30px 40px; color: #ff4544;">
                    Skipped Records: <strong>{{ $skippedRecordsCount }} Users</strong>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
@stop