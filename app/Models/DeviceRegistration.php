<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceRegistration extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID, DEVICE_ID, DEVICE_TOKEN, DEVICE_TYPE, STATUS_ID
    ];
}
