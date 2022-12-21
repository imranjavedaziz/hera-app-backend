<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Facades\{
    App\Services\FcmService,
};

class ChatController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index($userId = null)
    {
        $user = User::where('id',$userId)->first();
        $usersCount = User::whereIn(ROLE_ID,[SURROGATE_MOTHER,EGG_DONER,SPERM_DONER])->get()->count();
        return view('admin.chat.chat')->with(['title' => 'Chat', 'env' => env('APP_ENV'), 'adminId' => auth()->id(), 'user' => $user, 'userCount' => $usersCount]);
    }

    public function sendPushNotification(Request $request) {
        try {
            $response = FcmService::sendPushNotification($request->all(), auth()->id());
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
