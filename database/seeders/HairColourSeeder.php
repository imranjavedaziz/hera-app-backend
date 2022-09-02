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
                NAME  => 'Black'
            ],
            [
                NAME  => 'Brown'
            ],
            [
                NAME  => 'Gray'
            ],
            [
                NAME  => 'Yellow'
            ],
            [
                NAME  => 'White'
            ],
            [
                NAME  => 'Others'
            ]
        ];   
        HairColour::insert($hair_colours);
    }
}
