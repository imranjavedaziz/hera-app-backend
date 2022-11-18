<?php

namespace App\Services;

use App\Models\EnquiryForm;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEnquirySuccessJob;
use App\Helpers\CustomHelper;

class UserEnquiryService
{

    public function getRoles()
    {
        return Role::select(ID, NAME)->where(ID, '>', 1)->get();
    }

    public function enquiry($input)
    {
        $input[EMAIL] = strtolower($input[EMAIL]);
        $input[USER_TIMEZONE] = CustomHelper::get_local_time();
        $enquiry = EnquiryForm::create($input);
        if($enquiry){
            dispatch(new SendEnquirySuccessJob($enquiry));
        }
        return $enquiry;
    }
}
