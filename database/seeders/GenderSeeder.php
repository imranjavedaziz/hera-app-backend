<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;
use DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Gender::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
