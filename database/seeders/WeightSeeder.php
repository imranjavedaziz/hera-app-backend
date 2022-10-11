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
        $weights = array();
        for ($i=80; $i <=300 ; $i++) { 
            $data[NAME] = $i;
            $data[STATUS_ID] = ACTIVE;
            array_push($weights,$data);
        }
        Weight::insert($weights);
    }
}
