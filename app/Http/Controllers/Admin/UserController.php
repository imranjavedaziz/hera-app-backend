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
        $users = User::select('users.id','users.username','users.first_name', 'users.last_name','users.email', 'users.country_code', 'users.status_id')
            ->where('users.role_id',TWO)->where('users.email', '!=', '')->paginate(7);
        return view('admin.user.user')->with(['title' => 'User Managenent','userData'=>$users]);   
    }
}