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
                NAME  => 'Hispanic and/or Latino/a/x',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Not Hispanic and/or Latino/a/x',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Prefer not to disclose',
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => 'Other',
                STATUS_ID   => ACTIVE
            ]
        ];   
        Ethnicity::insert($ethnicities);
    }
}
