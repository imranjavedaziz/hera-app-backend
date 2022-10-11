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
        $heights = array();
        for ($i=58; $i <=84 ; $i++) { 
            $data[NAME] = $i;
            $data[STATUS_ID] = ACTIVE;
            array_push($heights,$data);
        }  
        Height::insert($heights);
    }
}
