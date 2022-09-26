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
            StatusSeeder::class,
            RoleSeeder::class,
            EducationSeeder::class,
            EthnicitySeeder::class,
            EyeColourSeeder::class,
            GenderSeeder::class,
            HairColourSeeder::class,
            HeightSeeder::class,
            RaceSeeder::class,
            RelationshipStatusSeeder::class,
            SexualOrientationSeeder::class,
            StateSeeder::class,
            // CitySeeder::class,
            WeightSeeder::class,
        ]);
    }
}
