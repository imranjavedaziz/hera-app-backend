<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\Subscription;


use DB;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class PaymentRequestsController extends Controller
{
  /**
   * This function is used for view.
   */
  public function getPendingPaymentRequests()
  {
    $paymentRequests = PaymentRequest::with('donar', 'ptb')
      ->select('id as paymentRequestId', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
      ->where('status', 0)
      ->paginate(ADMIN_PAGE_LIMIT);

    return view('admin.paymentRequest.pending-list')->with(['title' => 'Payment Requests', 'paymentRequestsData' => $paymentRequests]);
  }

  public function getReceivedPaymentRequests()
  {
    $paymentRequests = PaymentRequest::with('donar', 'ptb')
      ->select('id as paymentRequestId', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
      ->where('status', 1)
      ->paginate(ADMIN_PAGE_LIMIT);

    return view('admin.paymentRequest.received-list')->with(['title' => 'Payment Requests', 'paymentRequestsData' => $paymentRequests]);
  }

  public function getCompletedPaymentRequests()
  {
    $paymentRequests = PaymentRequest::with('donar', 'ptb')
      ->select('id as paymentRequestId', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
      ->where('status', 3)
      ->paginate(ADMIN_PAGE_LIMIT);

    return view('admin.paymentRequest.completed-list')->with(['title' => 'Payment Requests', 'paymentRequestsData' => $paymentRequests]);
  }

  public function getRejectedPaymentRequests()
  {
    $paymentRequests = PaymentRequest::with('donar', 'ptb')
      ->select('id as paymentRequestId', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
      ->where('status', 2)
      ->paginate(ADMIN_PAGE_LIMIT);

    return view('admin.paymentRequest.rejected-list')->with(['title' => 'Payment Requests', 'paymentRequestsData' => $paymentRequests]);
  }

}