<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $filePath = public_path().'/docs/us_cities.csv';
        $file = fopen($filePath, "r");
        $i=ZERO;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if(isset($column[1]) && !empty($column[1])) {
                $state = State::where(CODE,'=',$column[1])->first();
                if(!empty($state)) {
                    $city_data = [
                        STATE_ID    => $state->id,
                        NAME        => $column[3],
                        STATUS_ID   => ACTIVE
                    ];
                    City::insert($city_data);
                    $i++;
                }
            }
        }
        fclose($file);
        echo $i." city record inserted successfully.";
    }
}
