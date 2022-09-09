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
                NAME  => '58',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '59',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '60',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '61',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '62',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '63',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '64',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '65',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '66',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '67',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '68',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '69',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '70',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '71',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '72',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '73',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '74',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '75',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '76',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '77',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '78',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '79',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '80',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '81',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '82',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '83',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => '84',
                STATUS_ID   => ACTIVE
            ],
        ];   
        Height::insert($heights);
    }
}
