<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        FROM_USER_ID,TO_USER_ID,AMOUNT,DOC_URL,STATUS
    ];

    public function donar(){
        return $this->belongsTo(User::class,FROM_USER_ID,ID);
    }

    public function ptb(){
        return $this->belongsTo(User::class,TO_USER_ID,ID);
    }
}
