<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairColour;
use DB;

class HairColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        HairColour::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $hair_colours = [
            [
                NAME  => 'Brown',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Black',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Blonde',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Red',
                STATUS_ID   => ACTIVE
            ]
        ];   
        HairColour::insert($hair_colours);
    }
}
