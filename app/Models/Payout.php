<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,PAYOUT_TXN_ID,AMOUNT,BANK_ACCOUNT_TOKEN,TRANSFER_TXN_ID,PAYOUT_DATE,STATUS,ERROR_MESSAGE,ERROR_CODE
    ];

    public function donor(){
        return $this->belongsTo(User::class,USER_ID,ID);
    }
}
