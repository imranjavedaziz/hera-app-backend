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
                NAME  => 'Some College',
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
            ]
        ];   
        Education::insert($education);
    }
}