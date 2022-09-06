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
                NAME  => 'Black',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Brown',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Gray',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Yellow',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'White',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Others',
                STATUS_ID   => ACTIVE
            ]
        ];   
        HairColour::insert($hair_colours);
    }
}
