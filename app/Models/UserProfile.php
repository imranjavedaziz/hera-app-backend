<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID, GENDER_ID, SEXUAL_ORIENTATION_ID, RELATIONSHIP_STATUS_ID, OCCUPATION, BIO
    ];
}
