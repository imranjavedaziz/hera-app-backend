<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountDeactiveReason;

class AccountDeactiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            [
                NAME  => "I have a privacy concern.",
                STATUS_ID   => ACTIVE             
            ],
            [
                NAME  => "I have created another account and don't need this one.",
                STATUS_ID   => ACTIVE              
            ],
            [
                NAME  => "Cannot find right matches.",
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => "This is temporary, I'll be back.",
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => "I find it distracting and takes away too much of my time.",
                STATUS_ID   => ACTIVE
            ],
            [
                NAME  => "I don't understand how it works.",
                STATUS_ID   => ACTIVE
            ],
        ];

        AccountDeactiveReason::insert($reasons);
    }
}
