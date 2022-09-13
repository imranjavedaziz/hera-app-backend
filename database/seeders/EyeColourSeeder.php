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
