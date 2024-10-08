<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use Config;
use Session;
use Auth;
use App\Models\User;
use App\Url;
use App\Helpers\Helper;
use Validator;
use App\Jobs\SendActiveDeactiveUserJob;
use App\Jobs\UpdateStatusOnFirebaseJob;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Jobs\ImportUsersJob;

class UserController extends AdminController
{

    /**
     * This function is used for view.
     */
    public function index()
    {
        $admin = User::where('role_id',ADMIN)->first();
        $users = User::select('users.id','users.username','users.first_name','users.middle_name','users.last_name','users.email','users.role_id','users.country_code','users.phone_no','users.profile_pic','users.status_id','users.deactivated_by', 'users.deleted_by', 'users.deleted_at','users.created_at','users.timezone')
        ->where('users.role_id','!=',ONE)->where('users.email', '!=', '')->orderBy('users.id','desc')->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.user.user')->with(['title' => 'All Users','userData'=>$users ,'timezone'=> $admin->timezone]);  
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


    /**
     * Update User Status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        try{
            $msg = __('messages.admin.account_deactive');
            if ($request->status_id == ACTIVE) {
                $msg = __('messages.admin.account_active');
            }
            $user = User::where(ID, $id)->first();
            User::changeStatus($id,$request->all());
            dispatch(new SendActiveDeactiveUserJob($id, $request->status_id));
            dispatch(new UpdateStatusOnFirebaseJob($user, (int)$request->status_id, STATUS_ID));
            return $this->sendResponse($msg);
        } catch (\Exception $e) {
        	$message = trans(LANG_SOMETHING_WRONG);
            return $this->sendError($message, $e->getMessage());
        }
    }


    /**
     * Update User deleted.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        try{
            $msg = __('messages.admin.account_delete');
            $user = User::where(ID, $id)->first();
            User::deleteUser($id);
            dispatch(new SendActiveDeactiveUserJob($id, DELETED));
            dispatch(new UpdateStatusOnFirebaseJob($user, DELETED, STATUS_ID));
            return $this->sendResponse($msg);
        } catch (\Exception $e) {
        	$message = trans(LANG_SOMETHING_WRONG);
            return $this->sendError($message, $e->getMessage());
        }
    }

    /**
     * Update Admin timezone.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAdminTimezone(Request $request)
    {
        User::where('role_id',ADMIN)->update(['timezone' => $request->timezone]);
        return $this->sendResponse('success');
    }

    /**
     * Import Users
     *
     * @return \Illuminate\Http\Response
     */
    public function importUsers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|max:51200'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('flash_error', trans('messages.bulk_import.file_max'));
            }
            $valid = ['csv','xlsx'];
            if (!$request->hasFile('file') || !in_array($request->file('file')->getClientOriginalExtension(), $valid)) {
                $response = redirect()->back()->with('flash_error', trans('messages.bulk_import.file_type'));
            } else {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(sys_get_temp_dir(), $filename);
                ImportUsersJob::dispatch(sys_get_temp_dir() . '/' . $filename);
                $response = redirect()->back()->with('flash_success', trans('messages.bulk_import.success'));
            }
            return $response;
            } catch (\Exception $e) {
                 return redirect()->back()->withErrors($e->getMessage())->withInput();
            }
    }
}