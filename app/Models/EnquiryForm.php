<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EnquiryForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        NAME, EMAIL, COUNTRY_CODE, PHONE_NO, ENQUIRING_AS, MESSAGE, USER_TIMEZONE, ADMIN_REPLY, REPLIED_AT
    ];

    public function user(){
        return $this->belongsTo(User::class,EMAIL,EMAIL);
    }

    /**
     * This function is used for reply to support
     * @param $id
     */
    public static function inquiryReply($id, $input){
        return EnquiryForm::where('id',$id)->update([REPLIED_AT => Carbon::now(), ADMIN_REPLY => $input[ADMIN_REPLY]]);
    }
}
