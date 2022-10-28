<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Weight;
use DB;

class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Weight::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $weights = array();
        for ($i=80; $i <=300 ; $i++) { 
            $data[NAME] = $i;
            $data[STATUS_ID] = ACTIVE;
            array_push($weights,$data);
        }
        Weight::insert($weights);
    }
}
