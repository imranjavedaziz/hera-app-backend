<?php

namespace App\Jobs;

use App\Imports\UsersImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $import = new UsersImport();
        Excel::import($import, new File($this->filePath));

        $totalRecords = $import->getTotalRecords();
        $insertedRecords = $import->getInsertedRecords();
        $skippedRecordsCount = $import->getSkippedRecordsCount();
        $skippedRecords = $import->getSkippedRecords();
        $existingRecordsCount = $import->getExistingRecordsCount();
        $existingRecords = $import->getExistingRecords();
        $data = [
            'totalRecords' => $totalRecords,
            'insertedRecords' => $insertedRecords,
            'skippedRecordsCount' => $skippedRecordsCount,
            'skippedRecords' => $skippedRecords,
            'existingRecordsCount' => $existingRecordsCount,
            'existingRecords' => $existingRecords,
        ];
        $adminEmail = env('ADMIN_EMAIL', 'admin-mbc@yopmail.com');
        Mail::send('emails.import-summary', $data, function ($message) use ($adminEmail) {
            $message->to($adminEmail)->subject('User Import Summary');
        });
    }
}
