<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EyeColour;

class EyeColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eye_colours = [
            [
                NAME  => 'Black'
            ],
            [
                NAME  => 'Brown'
            ],
            [
                NAME  => 'Blue'
            ],
            [
                NAME  => 'Other'
            ]
        ];   
        EyeColour::insert($eye_colours);
    }
}
