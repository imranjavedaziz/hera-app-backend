<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                NAME  => 'Admin',                
            ],
            [
                NAME  => 'Parents To Be',                
            ],
            [
                NAME  => 'Surrogate Mother',
            ],
            [
                NAME  => 'Egg Doner',
            ],
            [
                NAME  => 'Sperm Doner',
            ],
        ];
        Role::insert($roles);
    }
}
