<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as Responses;
use App\Http\Middleware\CheckUserAccountStatus;
use App\Http\Middleware\EnsureParentsToBeTokenIsValid;
use App\Http\Middleware\EnsureParentsSubscriptionIsActive;
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
use App\Http\Controllers\Api\ChatFeedbackController;
use App\Http\Controllers\Api\EnquiryController;
use App\Http\Controllers\Api\ReportUserController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\ChatMediaController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StripeWebhookController;

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
            Responses::HTTP_OK
        );
    });    
    Route::fallback(function () {
        return Response::json(
            [
                'data' => null,
                'message' => trans('messages.invalid_method')
            ],
            Responses::HTTP_METHOD_NOT_ALLOWED
        );
    });

    /*******Test Route*****/
    Route::get('update-firebase-chat', [StateController::class, 'updateFirebaseChat']);
    
    /***Public route before authentication***/
    Route::post('login', [AuthController::class, 'login']);
    Route::post('sent-otp', [AuthController::class, 'sentOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('in-app-webhook-ios',[InAppWebhookController::class, 'iosSubscriptionEvent']);
    Route::post('in-app-webhook-android',[InAppWebhookController::class, 'androidSubscriptionEvent']);
    Route::post('stripe-webhooks',[StripeWebhookController::class, 'receiveEventNotifications']);

    /***Public register route before authentication***/
    Route::post('register', [UserController::class, 'register']);
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('states', [StateController::class, 'getStates']);
    Route::get('account-deactive-reason', [AuthController::class, 'getAccountDeactiveReason']);
    Route::get('roles', [EnquiryController::class, 'getRoles']);
    Route::post('enquiry', [EnquiryController::class, 'enquiry']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::group([MIDDLEWARE => ['jwt.verify', 'CheckUserAccountStatus']], function() {
        Route::post('update-account-status', [AuthController::class, 'updateAccountStatus']);
        Route::post('match-password', [AuthController::class, 'matchPassword']);
        Route::delete('delete-account', [AuthController::class, 'deleteAccount']);
        Route::post('register-device', [FcmController::class, 'registerDevice']);
        Route::post('send-push-notification', [FcmController::class, 'sendPushNotification']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile-setter-data', [UserController::class, 'getProfileSetterData']);
        Route::post('profile-register', [UserController::class, 'profileRegister']);
        Route::get('get-profile-matches', [ProfileMatchController::class, 'getProfileMatches']);
        Route::get('subscription-status',[SubscriptionController::class, 'getSubscriptionStatus']);
        Route::get('new-notification/{notifyType}',[NotificationController::class, 'getNewNotification']);
        Route::post('set-gallery', [UserController::class, 'setGallery']);
        Route::delete('delete-gallery', [UserController::class, 'deleteGallery']);
        Route::get('get-gallery', [UserController::class, 'getGalleryData']);
        Route::post('send-verification-mail', [UserController::class, 'sendVerificationMail']);
        Route::post('verify-email', [UserController::class, 'verifyEmail']);
        Route::post('report-user', [ReportUserController::class, 'reportUser']);
        Route::post('update-notify-status', [NotificationController::class, 'notifyStatus']);

        //Profile Routes
        Route::post('/update-profile-pic', [UserController::class, 'updateProfilePic']);
        Route::get('/get-user-profile', [UserController::class, 'getUserProfile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);

        Route::post('upload-document', [ChatMediaController::class, 'uploadDocument']);
        Route::get('chat-media', [ChatMediaController::class, 'getChatMedia']);
        Route::get('match-list', [PaymentController::class, 'getMatchList']);
        Route::post('upload-payment-doc', [PaymentController::class, 'uploadPaymentDocument']);
        Route::get('payment-request-list', [PaymentController::class, 'getPaymentRequestList']);
        Route::post('update-bank-account', [StripeController::class, 'updateBankAccount']);
        Route::get('account-status',[StripeController::class, 'getAccountStatus']);
        Route::get('transaction-history', [PaymentController::class, 'getTransactionHistoryList']);

        /***Only Donar route***/
        Route::middleware([EnsureDonarTokenIsValid::class])->group(function(){
            Route::get('attributes-setter-data', [UserController::class, 'getAttributesSetterData']);
            Route::post('set-attributes', [UserController::class, 'setAttributes']);
            Route::get('get-attributes', [UserController::class, 'getAttributes']);
            Route::get('ptb-profile-card', [DonarDashboardController::class, 'getPtbProfileCard']);
            Route::get('ptb-profile-details',[UserProfileController::class, 'getPtbProfileDetails']);
            Route::post('profile-match-request-response', [ProfileMatchController::class, 'profileMatchRequestResponse']);
            Route::post('payment-request', [PaymentController::class, 'paymentRequest']);
            Route::post('save-kyc-details', [StripeController::class, 'saveKycDetails']);
            Route::post('upload-kyc-doc', [StripeController::class, 'uploadkycDocument']);
        });
        /***Only Parents route***/
        Route::middleware([EnsureParentsToBeTokenIsValid::class])->group(function(){
            Route::get('preferences-setter-data', [UserController::class, 'getPreferencesSetterData']);
            Route::post('set-preferences', [UserController::class, 'setPreferences']);
            Route::get('get-preferences', [UserController::class, 'getPreferences']);
            Route::get('parents-matched-doner', [ParentsToBeDashboardController::class, 'matchedDonars']);
            Route::get('doner-profile-details',[UserProfileController::class, 'getDonerProfileDetails']);
            Route::get('preferences-age-range-data',[UserController::class, 'getPreferencesAgeRangeData']);
            Route::get('subscription-plan',[SubscriptionController::class, 'getPlan']);
            Route::post('create-subscription',[SubscriptionController::class, 'createSubscription']);
            Route::post('cancel-subscription',[SubscriptionController::class, 'cancelSubscription']);
            Route::post('chat-feedback', [ChatFeedbackController::class, 'saveChatFeedback']);
            Route::post('next-steps', [ChatFeedbackController::class, 'saveNextSteps']);
            Route::post('payment-request-status', [PaymentController::class, 'updatePaymentRequestStatus']);
            Route::post('payment-transfer', [PaymentController::class, 'paymentTransfer']);
        });

        /***Only Parents route***/
        Route::middleware([EnsureParentsSubscriptionIsActive::class])->group(function(){
            Route::post('profile-match-request', [ProfileMatchController::class, 'profileMatchRequest']);
        });
    });
});