<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryForm;
use App\Models\User;
use App\Jobs\SendInquiryReplyJob;
use App\Helpers\CustomHelper;
use DateTime;
use Carbon\Carbon;

class InquiryController extends AdminController
{
    /**
     * This function is used for view.
     */
    public function index()
    {
        $admin = User::where('role_id',ADMIN)->first();
    	$inquiries = EnquiryForm::with(USER)->select(ID, NAME, EMAIL, ENQUIRING_AS, MESSAGE, CREATED_AT)
    	->selectRaw('(select name from roles where id='.ENQUIRING_AS.AS_CONNECT.ROLE.' ')
    	->orderBy(ID, DESC)->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.inquiry.inquiry')->with(['title' => 'Inquiry', INQUIRIES => $inquiries,'timezone'=> $admin->timezone]);
    }

    /**
     * Display Inquiry.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return EnquiryForm::with(USER)->select(ID, NAME, EMAIL, COUNTRY_CODE, PHONE_NO, ENQUIRING_AS, MESSAGE, ADMIN_REPLY, REPLIED_AT, CREATED_AT)
        ->selectRaw('(select name from roles where id='.ENQUIRING_AS.AS_CONNECT.ROLE.' ')
        ->where(ID, $id)->first();

    }

    /**
     * Reply Inquiry.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, $id)
    {
        try{
            $msg = __('messages.admin.reply_sent');
            EnquiryForm::inquiryReply($id, $request->all());
            $enquiry = EnquiryForm::where(ID, $id)->first();
            dispatch(new SendInquiryReplyJob($enquiry));
            return response()->json([
                STATUS => true,
                MESSAGE => $msg,
                DATA => $enquiry,
            ]);
        } catch (\Exception $e) {
            $message = trans(LANG_SOMETHING_WRONG);
            return $this->sendError($message, $e->getMessage());
        }

    }

    /**
     * Reply Inquiry.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try{
            $enquiry = EnquiryForm::select(ID, NAME, EMAIL, ENQUIRING_AS, MESSAGE, CREATED_AT)
            ->selectRaw('(select name from roles where id='.ENQUIRING_AS.AS_CONNECT.ROLE.' ')
            ->whereMonth(CREATED_AT, $request->month)
            ->whereYear(CREATED_AT, $request->year)->get();
            return response()->json([
                STATUS => true,
                DATA => $enquiry,
            ]);
        } catch (\Exception $e) {
            $message = trans(LANG_SOMETHING_WRONG);
            return $this->sendError($message, $e->getMessage());
        }

    }
}
