<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HairColour;

class HairColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
