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
use App\Http\Controllers\Api\ParentsToBeDashboardController;
use App\Http\Controllers\Api\ProfileMatchController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\InAppWebhookController;
use App\Http\Controllers\Api\FcmController;
use App\Http\Controllers\Api\NotificationController;

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
    Route::post('in-app-webhook-ios',[InAppWebhookController::class, 'iosSubscriptionEvent']);
    Route::post('in-app-webhook-android',[InAppWebhookController::class, 'androidSubscriptionEvent']);

    /***Public register route before authentication***/
    Route::post('register', [UserController::class, 'register']);
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('states', [StateController::class, 'getStates']);

    Route::group([MIDDLEWARE => ['jwt.verify']], function() {
        Route::post('register-device', [FcmController::class, 'registerDevice']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile-setter-data', [UserController::class, 'getProfileSetterData']);
        Route::post('profile-register', [UserController::class, 'profileRegister']);
        Route::post('profile-match-request', [ProfileMatchController::class, 'profileMatchRequest']);
        Route::post('profile-match-request-response', [ProfileMatchController::class, 'profileMatchRequestResponse']);
        Route::get('get-profile-matches', [ProfileMatchController::class, 'getProfileMatches']);
        Route::get('subscription-status',[SubscriptionController::class, 'getSubscriptionStatus']);
        Route::get('new-notification/{notifyType}',[NotificationController::class, 'getNewNotification']);
        Route::post('set-gallery', [UserController::class, 'setGallery']);
        Route::delete('delete-gallery', [UserController::class, 'deleteGallery']);
        Route::get('get-gallery', [UserController::class, 'getGalleryData']);

        //Profile Routes
        Route::post('/update-profile-pic', [UserController::class, 'updateProfilePic']);
        Route::get('/get-user-profile', [UserController::class, 'getUserProfile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);

        /***Only Donar route***/
        Route::middleware([EnsureDonarTokenIsValid::class])->group(function(){
            Route::get('attributes-setter-data', [UserController::class, 'getAttributesSetterData']);
            Route::post('set-attributes', [UserController::class, 'setAttributes']);
            Route::get('get-attributes', [UserController::class, 'getAttributes']);
            Route::get('ptb-profile-card', [DonarDashboardController::class, 'getPtbProfileCard']);
            Route::get('ptb-profile-details',[UserProfileController::class, 'getPtbProfileDetails']);
        });
        /***Only Parents route***/
        Route::middleware([EnsureParentsToBeTokenIsValid::class])->group(function(){
            Route::get('preferences-setter-data', [UserController::class, 'getPreferencesSetterData']);
            Route::post('set-preferences', [UserController::class, 'setPreferences']);
            Route::get('parents-matched-doner', [ParentsToBeDashboardController::class, 'matchedDonars']);
            Route::get('doner-profile-details',[UserProfileController::class, 'getDonerProfileDetails']);
            Route::get('preferences-age-range-data',[UserController::class, 'getPreferencesAgeRangeData']);
            Route::get('subscription-plan',[SubscriptionController::class, 'getPlan']);
            Route::post('create-subscription',[SubscriptionController::class, 'createSubscription']);
        });
    });
});