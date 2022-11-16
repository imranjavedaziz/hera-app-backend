<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryForm;

class InquiryController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index()
    {
    	$inquiries = EnquiryForm::with(USER)->select(ID, NAME, EMAIL, ENQUIRING_AS, MESSAGE, CREATED_AT)
    	->selectRaw('(select name from roles where id='.ENQUIRING_AS.AS_CONNECT.ROLE.' ')
    	->orderBy(ID, DESC)->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.inquiry.inquiry')->with(['title' => 'Inquiry', INQUIRIES => $inquiries]);
    }


    /**
     * Display User.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::select(ID, USERNAME, ROLE_ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, PROFILE_PIC, DOB, SUBSCRIPTION_STATUS, CREATED_AT, PHONE_NO, EMAIL, STATUS_ID, DELETED_AT)
        ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age')
        ->selectRaw('(select name from roles where id='.ROLE_ID.AS_CONNECT.ROLE.' ')
        ->with([
            DONERATTRIBUTE => function($q) {
                return $q->select(ID, USER_ID, HEIGHT_ID, RACE_ID, MOTHER_ETHNICITY_ID, FATHER_ETHNICITY_ID, WEIGHT_ID, HAIR_COLOUR_ID, EYE_COLOUR_ID)
                ->selectRaw('(select name from heights where id='.HEIGHT_ID.AS_CONNECT.HEIGHT.' ')
                ->selectRaw('(select name from eye_colours where id='.EYE_COLOUR_ID.AS_CONNECT.EYE_COLOUR.' ')
                ->selectRaw('(select name from hair_colours where id='.HAIR_COLOUR_ID.AS_CONNECT.HAIR_COLOUR.' ')
                ->selectRaw('(select name from weights where id='.WEIGHT_ID.AS_CONNECT.WEIGHT.' ')
                ->selectRaw('(select name from races where id='.RACE_ID.AS_CONNECT.RACE.' ')
                ->selectRaw('(select name from ethnicities where id='.MOTHER_ETHNICITY_ID.AS_CONNECT.MOTHER_ETHNICITY.' ')
                ->selectRaw('(select name from ethnicities where id='.FATHER_ETHNICITY_ID.AS_CONNECT.FATHER_ETHNICITY.' ')
                ->selectRaw('(select name from education where id='.EDUCATION_ID.AS_CONNECT.EDUCATION.' ');
            }, 
            USERPROFILE => function($q) {
                return $q->select(ID, USER_ID, OCCUPATION, BIO);
            }, LOCATION, DONERPHOTOGALLERY, DONERVIDEOGALLERY
        ])->where(ID, $id)->first();

    }
}
