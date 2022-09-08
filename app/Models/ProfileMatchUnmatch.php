<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileMatchUnmatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        FROM_USER_ID,
        TO_USER_ID,
        STATUS_ID,
    ];

    public function fromUser() {
        return $this->belongsTo(User::class,FROM_USER_ID);
    }

    public function toUser() {
        return $this->belongsTo(User::class, TO_USER_ID);
    }

}
