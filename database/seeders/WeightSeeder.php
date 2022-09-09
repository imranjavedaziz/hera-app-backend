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
                NAME  => '100',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '101',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '102',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '103',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '104',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Weight::insert($weights);
    }
}
