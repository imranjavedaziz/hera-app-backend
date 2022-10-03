<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        RECIPIENT_ID,
        SENDER_ID,
        TITLE,
        DESCRIPTION,
        NOTIFY_TYPE,
        READ_AT,
    ];

    public function getUnreadCount($userId, $notifyType) {
        $count = self::where(RECIPIENT_ID, $userId)
        ->where(NOTIFY_TYPE, $notifyType)
        ->whereNull(READ_AT)
        ->get()
        ->count();

        return $count > ZERO ? true : false;
    }
}
