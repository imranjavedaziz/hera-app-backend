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
    	$inquiries = EnquiryForm::select(ID, NAME, EMAIL, ENQUIRING_AS, MESSAGE, CREATED_AT)
    	->selectRaw('(select name from roles where id='.ENQUIRING_AS.AS_CONNECT.ROLE.' ')
    	->orderBy(ID, DESC)->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.inquiry.inquiry')->with([TITLE => INQUIRY, INQUIRIES => $inquiries]);
    }
}
