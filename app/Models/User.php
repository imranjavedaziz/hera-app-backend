<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;
use Carbon\Carbon;

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
        SUBSCRIPTION_STATUS,
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

    public function getRoleIdAttribute()
    {
        return (int)$this->attributes[ROLE_ID];
    }

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
        return self::where($field, $value)->first();
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
        return $this->hasOne(Location::class, USER_ID, ID)
        ->select([
            ID,USER_ID,STATE_ID,ZIPCODE,
            DB::raw("(select name from ".STATES." where id=".STATE_ID.") as " .NAME. " "),
        ]);
    }

    public function role()
    {
        return $this->hasOne(Role::class, ID, ROLE_ID);
    }

    public function donerAttribute()
    {
        return $this->hasOne(DonerAttribute::class, USER_ID, ID);
    }

    public function donerPhotoGallery()
    {
        return $this->hasMany(DonerGallery::class, USER_ID, ID)->where(FILE_TYPE, IMAGE);
    }

    public function donerVideoGallery()
    {
        return $this->hasOne(DonerGallery::class, USER_ID, ID)->where(FILE_TYPE, VIDEO);
    }

    public function deviceRegistration()
    {
        return $this->hasMany(DeviceRegistration::class, USER_ID, ID)->where(STATUS_ID, ACTIVE);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class, RECIPIENT_ID, ID)->whereNull(READ_AT);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, USER_ID, ID)->where(STATUS_ID, ACTIVE)->latest();
    }

    /**
     * This function is used for change user status
     * @param $id
     */
    public static function changeStatus($id, $input)
    {
        return User::where('id', $id)->update([STATUS_ID => $input[STATUS_ID], DEACTIVATED_BY => $input[DEACTIVATED_BY]]);
    }

    /**
     * This function is used for delete user
     * @param $id
     */
    public static function deleteUser($id)
    {
        return User::where('id', $id)->update([DELETED_AT => Carbon::now(), STATUS_ID => DELETED, DELETED_BY => ONE]);
    }

    public function NotificationSetting()
    {
        return $this->hasOne(NotificationSetting::class, USER_ID, ID);
    }
}
