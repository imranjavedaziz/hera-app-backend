<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
                NAME  => 'Egg Donor',
            ],
            [
                NAME  => 'Sperm Donor',
            ],
        ];
        Role::insert($roles);
    }
}
