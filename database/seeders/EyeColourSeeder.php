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
                NAME  => 'Black',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Brown',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Blue',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Other',
                STATUS_ID   => ACTIVE
            ]
        ];   
        EyeColour::insert($eye_colours);
    }
}
