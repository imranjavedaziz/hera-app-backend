<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RelationshipStatus;
use DB;

class RelationshipStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        RelationshipStatus::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $relationship_statuses = [
            [
                NAME  => 'Single',
                STATUS_ID   => ACTIVE               
            ],
            [
                NAME  => 'Married',
                STATUS_ID   => ACTIVE               
            ],
            [
                NAME  => 'Widowed',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Divorced',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Married but separated',
                STATUS_ID   => ACTIVE
            ]
        ];
        RelationshipStatus::insert($relationship_statuses);
    }
}
