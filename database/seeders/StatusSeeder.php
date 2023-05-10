<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Status::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
