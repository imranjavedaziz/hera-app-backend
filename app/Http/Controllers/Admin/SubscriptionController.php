<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use PDF;
use Facades\{
    App\Services\SubscriptionService,
};

class SubscriptionController extends Controller
{
    /**
     * This function is used for view.
     */
    public function index()
    {
        $subscription = Subscription::with('user','subscriptionPlan')->select('id','price','current_period_start','status_id','user_id','subscription_plan_id')
           ->groupBy('user_id')->orderBy('id','desc')->paginate(ADMIN_PAGE_LIMIT);
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
        $subscriptionDetails = Subscription::with('user','subscriptionPlan')->select('id','price','current_period_start','current_period_end','status_id','original_transaction_id','user_id','subscription_plan_id','device_type')
        ->where(USER_ID, $userId)->orderBy('id','desc')->paginate(ADMIN_PAGE_LIMIT);
        $activeSubscription = SubscriptionService::getSubscriptionByUserId($userId);
        return view('admin.subscription.show')->with(['title' => 'Subscription','subscriptionData'=>$subscriptionDetails, 'users' => $user, 'activeSubscription'=> $activeSubscription]);
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
        $subscriptionDetail = Subscription::with('user','subscriptionPlan')->select('price','current_period_start','current_period_end','status_id','original_transaction_id','user_id','subscription_plan_id','device_type')
        ->where(ID, $subscriptionId)->first();
        return view('admin.subscription.invoice')->with(['title' => 'Invoice','subscriptionDetail'=>$subscriptionDetail, 'user' => $user]);
    }

    /**
     * Show invoice.
     *
     * @param  int $subscriptionId
     *  * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice($subscriptionId, $userId)
    {
        try {
            $user = User::where('id',$userId)->first();
            $subscriptionDetail = Subscription::with('user','subscriptionPlan')->select('price','current_period_start','current_period_end','status_id','original_transaction_id','user_id','subscription_plan_id','device_type')
            ->where(ID, $subscriptionId)->first();
            $pdf = PDF::loadView('admin.pdf.invoice', ['subscriptionDetail' => $subscriptionDetail , 'user'=> $user]);
            return $pdf->download('invoice.pdf');
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
        }
    }
}
