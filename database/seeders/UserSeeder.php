<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public $adminUser;

    public $adminRole;

    public function __construct()
    {
        $this->adminUser = [
            ROLE_ID => ADMIN,
            FIRST_NAME => ADMIN_NAME,
            MIDDLE_NAME => '',
            LAST_NAME => '',
            EMAIL => config('constants.ADMIN_EMAIL'),
            DOB => date("Y-m-d",strtotime("2000-01-01")),
            COUNTRY_CODE => '+1',
            PHONE_NO => ADMIN_PHONE,
            PASSWORD => Hash::make('Mbc@2022'),
            EMAIL_VERIFIED => ONE,
            STATUS_ID => ACTIVE,
        ];
        $this->adminRole = [
            NAME => ADMIN_NAME
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where(NAME,ADMIN_NAME)->first();
        if(empty($role)) {
            Role::insert($this->adminRole);
        }
        $admin = User::where(EMAIL,config('constants.ADMIN_EMAIL'))->first();
        if(empty($admin)) {
            User::insert($this->adminUser);
        }
    }
}
