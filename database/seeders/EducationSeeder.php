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
        $educations = [
            [
                NAME  => 'Highschool'
            ],
            [
                NAME  => 'Intermediate'
            ],
            [
                NAME  => 'Graduation'
            ],
            [
                NAME  => 'Post Graduation'
            ],
            [
                NAME  => 'Other'
            ]
        ];   
        Education::insert($educations);
    }
}
