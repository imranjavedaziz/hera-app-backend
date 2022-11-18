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

class AuthController extends AdminController
{
	/**
     * function used for login
     * 
     */
    public function getLogin()
	{ 
		if (Auth::check()) {
			return redirect($this->ADMIN_URL.'/user-management');
		}
		return view('admin.auth.login')->with(['title' => 'Log In']);
	}
	/**
     * function used for login
     * 
     */
	public function postLogin(Request $request) {
		$param=array(EMAIL =>$request->email,
    		PASSWORD =>$request->password
    	);
		$validate = Validator::make($param, [
            EMAIL => 'required|email',
            PASSWORD => 'required',
        ]);    
		if($validate->fails())
		{
			return  back()->withInput()->withErrors($validate);
		}
		$email = $request->input(EMAIL);
		$password = $request->input(PASSWORD);		
		$credentials = array(EMAIL =>$email, PASSWORD =>$password, ROLE_ID => [ADMIN]);
		if(Auth::attempt($credentials)){
			return redirect($this->ADMIN_URL.'/user-management');
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
}