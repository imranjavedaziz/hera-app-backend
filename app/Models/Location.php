<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
