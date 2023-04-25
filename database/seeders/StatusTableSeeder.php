<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            ['name' => 'Imported'],
        ]);
    }
}
