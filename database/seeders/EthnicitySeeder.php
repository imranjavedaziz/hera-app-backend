<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ethnicity;

class EthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ethnicities = [
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
        Ethnicity::insert($ethnicities);
    }
}
