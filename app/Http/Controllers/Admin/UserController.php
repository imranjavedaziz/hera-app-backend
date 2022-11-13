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

class UserController extends AdminController
{

    /**
     * This function is used for view.
     */
    public function index()
    {
        $users = User::select('users.id','users.username','users.first_name', 'users.last_name','users.email','users.role_id','users.country_code','users.phone_no','users.profile_pic','users.status_id','users.created_at')
            ->where('users.role_id','!=',ONE)->where('users.email', '!=', '')->orderBy('users.id','desc')->paginate(10);
        return view('admin.user.user')->with(['title' => 'All Users','userData'=>$users]);   
    }
}