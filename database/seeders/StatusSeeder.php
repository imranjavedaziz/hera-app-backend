<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                NAME  => ACTIVE_STATUS
            ],
            [
                NAME  => INACTIVE_STATUS
            ],
            [
                NAME  => PENDING_STATUS
            ],
            [
                NAME  => REJECTED_STATUS
            ],
            [
                NAME  => DELETED_STATUS
            ]
        ];   
        Status::insert($statuses);
    }
}
