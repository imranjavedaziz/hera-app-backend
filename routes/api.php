<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\EnsureParentsToBeTokenIsValid;
use App\Http\Middleware\EnsureDonarTokenIsValid;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\DonarDashboardController;
use App\Http\Controllers\Api\ProfileMatchController;
use App\Http\Controllers\Api\UserProfileController;

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

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('/', function () {
        return Response::json(
            [
                'data' => null,
                'message' => trans('messages.invalid_url')
            ],
            200
        );
    });    
    Route::fallback(function () {
        return Response::json(
            [
                'data' => null,
                'message' => trans('messages.invalid_method')
            ],
            405
        );
    });

    /*******Test Route*****/
    
    
    /***Public route before authentication***/
    Route::post('login', [AuthController::class, 'login']);
    Route::post('sent-otp', [AuthController::class, 'sentOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

    /***Public register route before authentication***/
    Route::post('register', [UserController::class, 'register']);
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('states', [StateController::class, 'getStates']);

    Route::group([MIDDLEWARE => ['jwt.verify']], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile-setter-data', [UserController::class, 'getProfileSetterData']);
        Route::post('profile-register', [UserController::class, 'profileRegister']);
        Route::get('preferences-setter-data', [UserController::class, 'getPreferencesSetterData']);
        Route::post('set-preferences', [UserController::class, 'setPreferences']);
        Route::post('profile-match-request', [ProfileMatchController::class, 'profileMatchRequest']);
        Route::get('get-profile-matches', [ProfileMatchController::class, 'getProfileMatches']);

        Route::get('doner-profile-details',[UserProfileController::class, 'getDonerProfileDetails']);
    });

    /***Only Donar route***/
    Route::middleware([EnsureDonarTokenIsValid::class])->group(function(){
        Route::get('attributes-setter-data', [UserController::class, 'getAttributesSetterData']);
        Route::post('set-attributes', [UserController::class, 'setAttributes']);
        Route::get('donar-profile-card', [DonarDashboardController::class, 'getDonarProfileCard']);
        Route::get('ptb-profile-details',[UserProfileController::class, 'getPtbProfileDetails']);
        Route::post('set-gallery', [UserController::class, 'setGallery']);
        Route::get('get-gallery', [UserController::class, 'getGalleryData']);
    });

});