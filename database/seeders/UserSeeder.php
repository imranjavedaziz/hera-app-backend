<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Johan',
            'last_name' => 'haden',
            'country_code' => '+1',
            'phone_no' => '1234567890',
            'email' => 'johan@gmail.com',
            'password' => bcrypt('Johan@123'),
        ]);
    }
}
