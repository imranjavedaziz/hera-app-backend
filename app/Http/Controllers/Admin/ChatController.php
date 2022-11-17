<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index($userId = null)
    {
        $user = User::where('id',$userId)->first();
        return view('admin.chat.chat')->with(['title' => 'Chat', 'env' => env('APP_ENV'), 'adminId' => auth()->id(), 'user' => $user]);
    }
}
