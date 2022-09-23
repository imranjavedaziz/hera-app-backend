<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Height extends Model
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

    public static function getHeight($id) {
        $height = self::where(ID, $id)->first();
        return isset($height) ? $height->name :'';
    }
}
