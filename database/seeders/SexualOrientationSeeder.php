<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SexualOrientation;

class SexualOrientationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sexual_orientations = [
            [
                NAME  => 'Heterosexual',
                STATUS_ID   => ACTIVE       
            ],
            [
                NAME  => 'Homosexual',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Bisexual',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Other',
                STATUS_ID   => ACTIVE
            ],
        ];
        SexualOrientation::insert($sexual_orientations);
    }
}
