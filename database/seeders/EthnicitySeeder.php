<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ethnicity;
use DB;

class EthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ethnicity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $ethnicities = [];
        $ethnicity = [];
        $ethnicities_data = ['Central/Eastern European', 'Northern European', 'Southern European', 'Western European', 'Middle Eastern', 'North African', 'African', 'American Indian or Alaska Native', 'Central American', 'South American', 'East Asian', 'Southeast Asian', 'Indian', 'Pacific Islander'];
        foreach ($ethnicities_data as $ethnicity_data) {
            $ethnicity[NAME] = $ethnicity_data;
            $ethnicity[STATUS_ID] = ACTIVE;
            array_push($ethnicities, $ethnicity);
        }
        Ethnicity::insert($ethnicities);
    }
}
