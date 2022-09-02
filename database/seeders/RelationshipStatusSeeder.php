<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RelationshipStatus;

class RelationshipStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relationship_statuses = [
            [
                NAME  => 'Unmarried',                
            ],
            [
                NAME  => 'Married',                
            ],
            [
                NAME  => 'Divorced',
            ],
            [
                NAME  => 'Widow',
            ],
        ];
        RelationshipStatus::insert($relationship_statuses);
    }
}
