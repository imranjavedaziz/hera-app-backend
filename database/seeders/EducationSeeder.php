<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Education;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $education = [
            [
                NAME  => 'Highschool',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Intermediate',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Graduation',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Post Graduation',
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