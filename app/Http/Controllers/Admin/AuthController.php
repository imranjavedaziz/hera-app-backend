<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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
use DB;
use Hash;
use App\Jobs\PasswordChangeJob;

class AuthController extends AdminController
{
	/**
     * function used for login
     * 
     */
    public function getLogin()
	{ 
		if (Auth::check()) {
			return redirect($this->ADMIN_URL.USER_MANAGEMENT_ROUTE);
		}
		return view('admin.auth.login')->with(['title' => 'Log In']);
	}
	/**
     * function used for login
     * 
     */
	public function postLogin(Request $request) {

		$validate = Validator::make($request->all(), [
            EMAIL => 'bail|required|email',
            PASSWORD => 'bail|required|min:8|max:20|'.PASSWORD_REGEX,
        ]);
        
		if($validate->fails())
		{
			return  back()->withInput()->withErrors($validate);
		}

		$email = $request->input(EMAIL);
		$password = $request->input(PASSWORD);		
		$credentials = array(EMAIL =>$email, PASSWORD =>$password, ROLE_ID => [ADMIN]);
		if(Auth::attempt($credentials)){
			return redirect($this->ADMIN_URL.USER_MANAGEMENT_ROUTE);
		}else{
			return back()->withInput()->withErrors(['error'=>__('messages.admin.invalid_credentail')]);
		}
	}

	/**
     * function used for logout user
     * 
    */
	public function logout(){
		Auth::logout();
		return redirect('/'.$this->ADMIN_URL);
	}

	/**
     * function used for get
     * view of change password
     */
    public function changePassword()
	{
		return view('admin.auth.change-password')->with(['title' => 'Change Password']);
	}

    /**
     * @param Request $request
     *
     * @return array
     * @throws \Throwable
     */
    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            # Validation
	        $validate = $this->changePasswordValidation($request);
			if($validate->fails())
			{
				return back()->withInput()->withErrors($validate);
			}

	        $user = auth()->user();
	        if(!Hash::check($request->current_password, $user->password)){
	        	$response = back()->withInput()->withErrors([ERROR =>__('messages.change_password.old_password_does_not_match')]);
	        }elseif(Hash::check($request->new_password, $user->password)) {
	        	$response = back()->withInput()->withErrors([ERROR =>__('messages.change_password.new_password_can_not_be_old_password')]);
	        }else{
	        	#Update the new Password
		        $user->password = bcrypt($request->new_password);
                $user->save();
                DB::commit();
            	dispatch(new PasswordChangeJob($user));
		        $response = redirect($this->ADMIN_URL.USER_MANAGEMENT_ROUTE)->withFlashSuccess(trans('messages.change_password.change_password_success'));
	        }
        } catch (\Exception $e) {
            DB::rollback();
        	$response = back()->withInput()->withErrors([ERROR => $e->getMessage()]);
        }

        return $response;
    }

    private function changePasswordValidation($request){
    	return Validator::make($request->all(), [
            CURRENT_PASSWORD => 'bail|required|min:8|max:20|'.PASSWORD_REGEX,
            NEW_PASSWORD => 'bail|required|min:8|max:20|'.PASSWORD_REGEX,
            CONFIRM_PASSWORD => 'bail|required|same:new_password',
        ], [
            CURRENT_PASSWORD_REQ => __('messages.request_validation.error_msgs.current_password_req'),
            NEW_PASSWORD_REQ => __('messages.request_validation.error_msgs.new_password_req'),
            CONFIRM_PASSWORD_REQ => __('messages.request_validation.error_msgs.confirm_password_req'),
            CURRENT_PASS_REGEX => __('messages.request_validation.error_msgs.pass_regex'),
            NEW_PASS_REGEX => __('messages.request_validation.error_msgs.pass_regex'),
        ]);
    }

}