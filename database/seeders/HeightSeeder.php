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
        Height::insert($heights);
    }
}
