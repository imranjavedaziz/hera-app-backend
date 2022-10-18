<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Race;
use DB;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Race::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $races = [
            [
                NAME  => 'White',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Black or African American',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'American Indian or Alaska Native',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Asian',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Native Hawaiian or Other Pacific Islander',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Mixed Or Other Race',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Race::insert($races);
    }
}
