<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EducationSeeder::class,
            EthnicitySeeder::class,
            EyeColourSeeder::class,
            GenderSeeder::class,
            HairColourSeeder::class,
            HeightSeeder::class,
            RaceSeeder::class,
            RelationshipStatusSeeder::class,
            RoleSeeder::class,
            SexualOrientationSeeder::class,
            StatusSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            WeightSeeder::class,
        ]);
    }
}
