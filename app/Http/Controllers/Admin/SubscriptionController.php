<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index()
    {
        $subscription = Subscription::with('user','subscriptionPlan')->select('id','price','current_period_start','status_id','user_id')
           ->orderBy('id','desc')->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.subscription.list')->with(['title' => 'Subscription','subscriptionData'=>$subscription]);   
    }

    /**
     * Subscription details.
     *
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = User::where('id',$userId)->first();
        $subscriptionDetails = Subscription::with('user','subscriptionPlan')->select('id','price','current_period_start','current_period_end','status_id','original_transaction_id')
        ->where(USER_ID, $userId)->orderBy('id','desc')->paginate(ADMIN_PAGE_LIMIT);
        return view('admin.subscription.show')->with(['title' => 'Subscription Detail','subscriptionData'=>$subscriptionDetails, 'users' => $user]);
    }

    /**
     * Show invoice.
     *
     * @param  int $subscriptionId
     *  * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function showInvoice($subscriptionId, $userId)
    { 
        $user = User::where('id',$userId)->first();
        $subscriptionDetail = Subscription::with('user','subscriptionPlan')->select('price','current_period_start','current_period_end','status_id','original_transaction_id')
        ->where(ID, $subscriptionId)->first();
        return view('admin.subscription.invoice')->with(['title' => 'Invoice','subscriptionData'=>$subscriptionDetail, 'user' => $user]);
    }
}
