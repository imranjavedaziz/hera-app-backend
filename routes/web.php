<?php

use App\Http\Controllers\Admin\PaymentRequestsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin.auth.login');
});


Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/', [AuthController::class, 'getlogin']);
    Route::post('/', [AuthController::class, 'postLogin'])->name('login');
    Route::post('/update-timezone', [UserController::class, 'updateAdminTimezone']);

    Route::group([MIDDLEWARE => ['prevent-back-history', 'admin']], function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('change-password', [AuthController::class, 'changePassword'])->name('change-password');
        Route::post('update-password', [AuthController::class, 'updatePassword'])->name('update-password');
        Route::get('user-management', [UserController::class, 'index'])->name('userList');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::put('/user/change-status/{id}', [UserController::class, 'changeStatus'])->name('user.status');
        Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::get('chat', [ChatController::class, 'index'])->name('chatList');
        Route::get('inquiry', [InquiryController::class, 'index'])->name('inquiryList');
        Route::get('userchat/{id}', [ChatController::class, 'index'])->name('user.chat');
        Route::get('/inquiry/{id}', [InquiryController::class, 'show'])->name('inquiry.show');
        Route::put('inquiry/reply/{id}', [InquiryController::class, 'reply'])->name('inquiry.reply');
        Route::post('inquiry/export/', [InquiryController::class, 'export'])->name('inquiry.export');
        Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscriptionList');
        Route::get('/subscription/{id}', [SubscriptionController::class, 'show'])->name('userSubscriptionList');
        Route::get('/invoice/{id}/{userId}', [SubscriptionController::class, 'showInvoice'])->name('showInvoice');
        Route::get('/downloadInvoice/{id}/{userId}', [SubscriptionController::class, 'downloadInvoice'])->name('downloadInvoice');
        Route::post('/chat/send-push-notification', [ChatController::class, 'sendPushNotification'])->name('sendPushNotification');
        Route::post('import-users', [UserController::class, 'importUsers']);
        Route::get('/payment-requests/pending', [PaymentRequestsController::class, 'getPendingPaymentRequests'])->name('pendingPaymentRequests');
        Route::get('/payment-requests/received', [PaymentRequestsController::class, 'getReceivedPaymentRequests'])->name('receivedPaymentRequests');
        Route::get('/payment-requests/completed', [PaymentRequestsController::class, 'getCompletedPaymentRequests'])->name('completedPaymentRequests');
        Route::get('/payment-requests/rejected', [PaymentRequestsController::class, 'getRejectedPaymentRequests'])->name('rejectedPaymentRequests');
    });
});