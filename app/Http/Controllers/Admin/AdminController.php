<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	protected $ADMIN_URL;

    public function __construct(){
    	$this->ADMIN_URL = config('constant.ADMIN_URL');
    }

    public function sendResponse($message)
    {
        $response = [
            STATUS => true,
            MESSAGE => $message
        ];

        return response()->json($response);
    }

    public function sendError($message, $errorMessages)
    {
        $response = [
            STATUS => false,
            MESSAGE => $message,
            'errors' => $errorMessages
        ];
    
        return response()->json($response);
    }
}