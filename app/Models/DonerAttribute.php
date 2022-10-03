<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonerAttribute extends Model
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
        EDUCATION_ID,
    ];

    public function height()
    {
        return $this->hasOne(Height::class, ID, HEIGHT_ID);
    }

    public function race()
    {
        return $this->hasOne(Race::class, ID, RACE_ID);
    }

    public function motherEthnicity()
    {
        return $this->hasOne(Ethnicity::class, ID, MOTHER_ETHNICITY_ID);
    }

    public function fatherEthnicity()
    {
        return $this->hasOne(Ethnicity::class, ID, ROLE_ID);
    }

    public function weight()
    {
        return $this->hasOne(Weight::class, ID, WEIGHT_ID);
    }

    public function hairColour()
    {
        return $this->hasOne(HairColour::class, ID, HAIR_COLOUR_ID);
    }

    public function eyeColour()
    {
        return $this->hasOne(EyeColour::class, ID, EYE_COLOUR_ID);
    }

    public function education()
    {
        return $this->hasOne(Education::class, ID, EDUCATION_ID);
    }
}
