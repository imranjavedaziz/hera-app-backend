<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ethnicity;
use DB;

class EthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ethnicity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $ethnicities = [
            [
                NAME  => 'Central/Eastern European',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Northern European',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Southern European',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Western European',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Middle Eastern',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'North African',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'African',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'American Indian or Alaska Native',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Central American',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'South American',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'East Asian',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Southeast Asian',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Indian',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Pacific Islander',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Ethnicity::insert($ethnicities);
    }
}
