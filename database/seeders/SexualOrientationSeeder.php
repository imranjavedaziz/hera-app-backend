<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SexualOrientation;
use DB;

class SexualOrientationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SexualOrientation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
