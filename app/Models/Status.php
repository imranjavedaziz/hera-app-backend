<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        NAME
    ];

    public static function getStatusById($id) {
        $status = self::where(ID, $id)->first();
        return isset($status) ? $status->name : NULL;
    }
}
