<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $states;

    public function __construct()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        State::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->states = [
            ['AL','Alabama'],
            ['AK','Alaska'],
            ['AZ','Arizona'],
            ['AR','Arkansas'],
            ['CA','California'],
            ['CO','Colorado'],
            ['CT','Connecticut'],
            ['DE','Delaware'],
            ['FL','Florida'],
            ['GA','Georgia'],
            ['HI','Hawaii'],
            ['ID','Idaho'],
            ['IL','Illinois'],
            ['IN','Indiana'],
            ['IA','Iowa'],
            ['KS','Kansas'],
            ['KY','Kentucky'],
            ['LA','Louisiana'],
            ['ME','Maine'],
            ['MD','Maryland'],
            ['MA','Massachusetts'],
            ['MI','Michigan'],
            ['MN','Minnesota'],
            ['MS','Mississippi'],
            ['MO','Missouri'],
            ['MT','Montana'],
            ['NE','Nebraska'],
            ['NV','Nevada'],
            ['NH','New Hampshire'],
            ['NJ','New Jersey'],
            ['NM','New Mexico'],
            ['NY','New York'],
            ['NC','North Carolina'],
            ['ND','North Dakota'],
            ['OH','Ohio'],
            ['OK','Oklahoma'],
            ['OR','Oregon'],
            ['PA','Pennsylvania'],
            ['RI','Rhode Island'],
            ['SC','South Carolina'],
            ['SD','South Dakota'],
            ['TN','Tennessee'],
            ['TX','Texas'],
            ['UT','Utah'],
            ['VT','Vermont'],
            ['VA','Virginia'],
            ['WA','Washington'],
            ['WV','West Virginia'],
            ['WI','Wisconsin'],
            ['WY','Wyoming']
        ];
    }
    public function run()
    {
        $data = [];
        foreach($this->states as $key => $state) {
            $data[$key][CODE] =  $state[ZERO];
            $data[$key][NAME] =  $state[ONE];
            $data[$key][STATUS_ID] =  ACTIVE;
        }
        State::insert($data);
    }
}
