<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Weight;

class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weights = [
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
        Weight::insert($weights);
    }
}
