<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EyeColour;
use DB;

class EyeColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        EyeColour::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $eye_colours = [
            [
                NAME  => 'Brown',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Blue',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Hazel',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Amber',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Green',
                STATUS_ID   => ACTIVE
            ]
        ];   
        EyeColour::insert($eye_colours);
    }
}
