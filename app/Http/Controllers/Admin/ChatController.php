<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index()
    {
        return view('admin.chat.chat')->with(['title' => 'Chat', 'env' => env('APP_ENV'), 'adminId' => auth()->id()]);
    }
}
