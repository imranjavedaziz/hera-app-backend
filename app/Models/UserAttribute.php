<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        HEIGHT_ID,
        RACE_ID,
        MOTHER_ETHNICITY_ID,
        FATHER_ETHNICITY_ID,
        WEIGHT_ID,
        HAIR_COLOUR_ID,
        EYE_COLOUR_ID,
    ];
}
