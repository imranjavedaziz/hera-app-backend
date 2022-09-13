<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = [
            [
                NAME  => 'Male',
                STATUS_ID   => ACTIVE             
            ],
            [
                NAME  => 'Female',
                STATUS_ID   => ACTIVE              
            ],
            [
                NAME  => 'Other',
                STATUS_ID   => ACTIVE
            ],
        ];
        Gender::insert($genders);
    }
}
