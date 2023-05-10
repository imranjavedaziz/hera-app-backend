<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Education;
use DB;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Education::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $education = [
            [
                NAME  => 'High School',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Some College',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Trade School',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Associate’s Degree',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Bachelor’s Degree',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Master’s Degree',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Ph.D.',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Other',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Education::insert($education);
    }
}