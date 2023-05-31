<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import Users Job Report</title>
</head>
<body>
    <h2>Import Users Job Report</h2>
    <p>Total records: {{ $totalRecords }}</p>
    <p>Skipped records count: {{ $skippedRecordsCount }}</p>
    <p>Inserted records: {{ $insertedRecords }}</p>

    @if(is_array($skippedRecords) && count($skippedRecords) > 0)
            <p>Please find the Skipped Records reason mentioned below.</p>
            <table aria-describedby="report List">
                <thead>
                    <tr>
                        <th scope="col">Row No.</th>
                        <th scope="col">Email</th>
                        <th scope="col">Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($skippedRecords as $record)
                        <tr>
                            <td>{{ $record['row'] }}</td>
                            <td>{{ $record['email'] }}</td>
                            <td>
                                @if(is_array($record['errors']) && count($record['errors']) > 0)
                                    <ul>
                                        @foreach($record['errors'] as $error)
                                            <li>{{ implode(', ', $error) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


    @if(is_array($existingRecords) && count($existingRecords) > 0)
        <p>Already existing records:</p>
        <table aria-describedby="existing report">
            <thead>
                <tr>
                    <th scope="col">Row No.</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($existingRecords as $record)
                    <tr>
                        <td>{{ $record['row'] }}</td>
                        <td>{{ $record['email'] }}</td>
                        <td>{{ is_array($record['errors']) ? implode(",", $record['errors']) : $record['errors'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
