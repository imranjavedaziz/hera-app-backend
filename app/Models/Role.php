<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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

    protected $hidden = ['deleted_at', 'updated_at'];

    public static function getRoleById($id)
    {
        $role = self::where(ID, $id)->first();
        return isset($role) ? $role->name : null;
    }
}
