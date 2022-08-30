<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('/', function () {
        return Response::json(
            [
                'data' => null,
                'message' => config('constants.msgs.invalid_url')
            ],
            200
        );
    });    
    Route::fallback(function () {
        return Response::json(
            [
                'data' => null,
                'message' => config('constants.msgs.invalid_method')
            ],
            405
        );
    });

    /*******Test Route*****/

    
    /***Public route before authentication***/
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {

    Route::group([MIDDLEWARE => ['jwt.refresh']], function() {        
        Route::get('refresh_token', [AuthController::class, 'refreshToken']);
    });

    Route::group([MIDDLEWARE => ['jwt.verify']], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        
    });

});
