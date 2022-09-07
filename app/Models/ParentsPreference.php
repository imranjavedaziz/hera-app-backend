<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        ROLE_ID_LOOKING_FOR,
        AGE,
        HEIGHT,
        RACE,
        ETHNICITY,
        HAIR_COLOUR,
        EYE_COLOUR,
        EDUCATION,
    ];
}
