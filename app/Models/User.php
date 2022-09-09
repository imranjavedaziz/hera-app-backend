<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        ROLE_ID,
        USERNAME,
        FIRST_NAME,
        MIDDLE_NAME,
        LAST_NAME,
        COUNTRY_CODE,
        PHONE_NO,
        EMAIL,
        DOB,
        PROFILE_PIC,
        EMAIL_VERIFIED,
        EMAIL_VERIFIED_AT,
        PASSWORD,
        STATUS,
        REGISTRATION_STEP,
        RECENT_ACTIVITY,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        PASSWORD,
        REMEMBER_TOKEN,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        EMAIL_VERIFIED_AT => DATETIME,
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function checkUser($field, $value)
    {
        return self::where($field,$value)->first();
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, USER_ID, ID);
    }

    public function parentsPreference()
    {
        return $this->hasOne(ParentsPreference::class, USER_ID, ID);
    }

    public function location()
    {
        return $this->hasOne(Location::class, USER_ID, ID);
    }

    public function role()
    {
        return $this->hasOne(Role::class, ID, ROLE_ID);
    }

    public function donerAttribute()
    {
        return $this->hasOne(DonerAttribute::class, USER_ID, ID);
    }
}
