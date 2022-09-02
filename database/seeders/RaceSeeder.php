<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Race;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $races = [
            [
                NAME  => 'ABC'
            ],
            [
                NAME  => 'DEF'
            ],
            [
                NAME  => 'GHI'
            ],
            [
                NAME  => 'JKL'
            ],
            [
                NAME  => 'MNO'
            ]
        ];   
        Race::insert($races);
    }
}
