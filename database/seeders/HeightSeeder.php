<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Height;

class HeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $heights = [
            [
                NAME  => 'ABC',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'DEF',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'GHI',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'JKL',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'MNO',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Height::insert($heights);
    }
}
