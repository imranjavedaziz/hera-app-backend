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
            ],
            [
                NAME  => 'Homosexual',                
            ],
            [
                NAME  => 'Bisexual',
            ],
            [
                NAME  => 'Other',
            ],
        ];
        SexualOrientation::insert($sexual_orientations);
    }
}
