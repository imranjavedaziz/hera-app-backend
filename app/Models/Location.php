<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        ADDRESS,
        CITY_ID,
        STATE_ID,
        ZIPCODE,
        LATITUDE,
        LONGITUDE,
    ];

    public static function getLocationByUserId($userId)
    {
        $query = self::where(USER_ID, $userId);
        $query->select([
            ID,USER_ID,ADDRESS,STATE_ID,CITY_ID,ZIPCODE,LATITUDE,LONGITUDE,
            DB::raw("(select code from states where id=".STATE_ID.") as state_code"),
            DB::raw("(select name from states where id=".STATE_ID.") as state_name"),
        ]);

        return $query->first();
    }
}
