<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDeactiveReason extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        NAME, STATUS_ID
    ];

    public static function getReasons()
    {
        return self::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }
}
