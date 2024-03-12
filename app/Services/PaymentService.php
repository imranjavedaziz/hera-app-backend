<?php

namespace App\Services;

use App\Helpers\AuthHelper;
use App\Jobs\UpdateStatusOnFirebaseJob;
use App\Models\ParentsPreference;
use App\Models\PaymentRequest;
use App\Models\ProfileMatch;
use App\Models\SubscriptionPaymentSetup;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\PaymentNotification;
use App\Models\WorldpayResponse;
use DB;
use App\Helpers\CustomHelper;
use App\Models\DonorPaymentSetup;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    private const HOSTED_PAYMENTS_GATEWAY_BASE_URL = 'https://dev-paynow.itscocloud.com';
    private const HOSTED_PAYMENTS_EXECUTE_URL = self::HOSTED_PAYMENTS_GATEWAY_BASE_URL . '/execute';
    private const HOSTED_PAYMENTS_RESPONSE_URL = self::HOSTED_PAYMENTS_GATEWAY_BASE_URL . '/Response';
    private const TRANSACTION_RESULT_API_URL = '/api/v1/transaction-result';
    private const DONOR_TRANSACTION_RESULT_API_URL = '/api/v1/donor-transaction-result';
    private const NO_DATA_RESPONSE = '{NO DATA}';

    public function forwardRequest()
    {
        $ips = Str::uuid()->toString();        
        $queryParams = self::createWorldpayQueryParams($ips, '0.11');
        // Send the POST request to the external URL
        $requestUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_EXECUTE_URL, $queryParams);
        Log::info("POST $requestUrl\n");
        $response = Http::post($requestUrl);

        // Check if the request was successful
        if ($response->successful()) {
            Log::info("got successful response from Worldpay\n");
            Log::info($response->body());
            return response($this->createPaymentPageHtml($ips, $response->json()["url"]))
                ->header('Content-Type', 'text/html');
        }

        // Handle the error
        return response()->json([
            'error' => 'Failed to fetch from external API'
        ], $response->status());

    }

    public function makePayment($ips)
    {
        $paymentSetup = SubscriptionPaymentSetup::where(IPS, $ips)->first();
        $plan = SubscriptionPlan::where(ID, $paymentSetup->subscription_plan_id)->first();
        if ($paymentSetup->status != 0) {
            return response()->json([
                'error' => 'Invalid Payment Request'
            ]);
        }

        $queryParams = self::createWorldpayQueryParams($ips, $plan->price);
        // Send the POST request to the external URL
        $requestUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_EXECUTE_URL, $queryParams);
        Log::info("POST $requestUrl\n");
        $response = Http::post($requestUrl);

        // Check if the request was successful
        if ($response->successful()) {
            Log::info("got successful response from WorldPay\n");
            Log::info($response->body());
            return response($this->createPaymentPageHtml($ips, $response->json()["url"]), 200)
                ->header('Content-Type', 'text/html');
        }

        // Handle the error
        return response()->json([
            'error' => 'Failed to fetch from external API'
        ], $response->status());

    }

    public function pollTransactionResult($ips)
    {
        $queryParams = [
            'IPS' => $ips,
            'Response' => 'Y'
        ];

        $request_url = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_RESPONSE_URL, $queryParams);
        $response = Http::get($request_url);

        // Check if the request was successful
        if ($response->successful()) {
            Log::info("got transaction result from worldpay\n");
            Log::info($response->body());
            $respText = self::extractWorldpayResponse($response->body());
            if ($respText != self::NO_DATA_RESPONSE) {
                //save the raw response from here into database
                WorldpayResponse::create([
                    'ips' => $ips,
                    'response' => $respText,
                ]);
                $respDictionary = self::parseWorldpayResponse($respText);
                if ($respDictionary['HostedPaymentStatus'] == 'Complete') {
                    Log::info("Payment completed");

                    $paymentSetup = SubscriptionPaymentSetup::where(IPS, $ips)->first();
                    $plan = SubscriptionPlan::where(ID, $paymentSetup->subscription_plan_id)->first();
                    $paymentSetup->status = 1;
                    $paymentSetup->save();

                    $user = User::find($paymentSetup[USER_ID]);
                    $user->subscription_status = SUBSCRIPTION_ENABLED;
                    $user->save();
                    dispatch(new UpdateStatusOnFirebaseJob($user, SUBSCRIPTION_ENABLED, RECIEVER_SUBSCRIPTION));

                    $activeSubscription = Subscription::where(USER_ID, $paymentSetup[USER_ID])
                        ->where(STATUS_ID, ACTIVE)
                        ->first();

                    if (!empty($activeSubscription)) {
                        Subscription::where(ID, $activeSubscription->id)
                            ->update([
                                STATUS_ID => INACTIVE,
                                CANCELED_AT => Carbon::now()
                            ]);
                    }
                    $currentSubscription = Subscription::create([
                        USER_ID => $paymentSetup[USER_ID],
                        SUBSCRIPTION_PLAN_ID => $plan->id,
                        SUBSCRIPTION_ID => NULL,
                        PRODUCT_ID => $plan[ANDROID_PRODUCT],
                        PRICE => $plan[PRICE],
                        CURRENT_PERIOD_START => Carbon::now(),
                        CURRENT_PERIOD_END => Carbon::now()->addDays(30),
                        DEVICE_TYPE => $paymentSetup[DEVICE_TYPE],
                        STATUS_ID => ACTIVE,
                        UPDATED_AT => Carbon::now(),
                    ]);
                    ParentsPreference::where(USER_ID, $paymentSetup[USER_ID])->update([ROLE_ID_LOOKING_FOR => $plan->role_id_looking_for]);

                    Transaction::create([
                        PAYMENT_INTENT => $respDictionary['TransactionID'],
                        USER_ID => $paymentSetup[USER_ID],
                        SUBSCRIPTION_ID => $currentSubscription[ID],
                        PRODUCT_ID => $plan[ANDROID_PRODUCT],
                        AMOUNT => $plan[PRICE],
                        NET_AMOUNT => $plan[PRICE],
                        PAYMENT_TYPE => TWO,
                        PAYMENT_STATUS => ONE,
                        BRAND => $respDictionary['CardLogo'],
                        LAST4 => $respDictionary['LastFour']]);

                    return response()->json([
                        'status' => 'OK',
                        'message' => 'Payment completed and successfully subscribed'
                    ], 200);
                }
            }
        }

        // Handle the error
        return response()->json([
            'status' => 'ERROR',
            'message' => 'Failed to fetch from external API'
        ], $response->status());
    }


    public function createDonorPaymentPage($paymentRequestId){
        try {
            Log::info("create donor payment page api calling");
            $currentUserId = AuthHelper::authenticatedUser()->id;
            $paymentRequest = PaymentRequest::select('id as paymentRequestId', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
                ->where('status', 0)
                ->where('to_user_id', $currentUserId)
                ->where('id', $paymentRequestId)
                ->first();
            Log::info("paymentRequest: ", [ 'p' => $paymentRequest]);
            if(!isset( $paymentRequest ) ){
                $response = response()->json([
                    'error' => 'Payment Request is not valid'
                ], 400);
                return $response;
            }

            $ips = Str::uuid()->toString();
            DB::beginTransaction();
            DonorPaymentSetup::create([
                IPS => $ips,
                FROM_USER_ID => $paymentRequest[FROM_USER_ID],
                TO_USER_ID => $paymentRequest[TO_USER_ID],
                PAYMENT_REQUEST_ID => $paymentRequest['paymentRequestId']
            ]);

            $paymentUrl = '/api/v1/make-donor-payment?ips=' . $ips;
            DB::commit();
            $response = response()->json([
                'paymentUrl' => $paymentUrl
            ], 200);
        } catch (\Exception $e) {
            Log::info("exception", [ 'e' => $e ]);
            DB::rollback($e->getMessage());
            $response = response()->json([
                'error' => 'Something went wrong'
            ], 400);
        }
        return $response;
    }


    public function makeDonorPayment($ips)
    {
        $paymentSetup = DonorPaymentSetup::with('paymentRequest')->where(IPS, $ips)->first();
        if ( !isset($paymentSetup) || $paymentSetup->status != 0 ) {
            return response()->json([
                'error' => 'Invalid Payment Request'
            ]);
        }
        $amount = $paymentSetup->paymentRequest->amount * 1.03; // 3% HERA's cut
        $queryParams = self::createWorldpayQueryParams($ips, $amount);
        // Send the POST request to the external URL
        $requestUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_EXECUTE_URL, $queryParams);
        Log::info("POST $requestUrl\n");
        $response = Http::post($requestUrl);

        // Check if the request was successful
        if ($response->successful()) {
            Log::info("got successful response from WorldPay\n");
            Log::info($response->body());
            return response($this->createDonorPaymentPageHtml($ips, $response->json()["url"]), 200)
                ->header('Content-Type', 'text/html');
        }

        // Handle the error
        return response()->json([
            'error' => 'Failed to fetch from external API'
        ], $response->status());

    }

    public function pollDonorTransactionResult($ips)
    {
        $queryParams = [
            'IPS' => $ips,
            'Response' => 'Y'
        ];

        $request_url = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_RESPONSE_URL, $queryParams);
        $response = Http::get($request_url);

        // Check if the request was successful
        if ($response->successful()) {
            Log::info("got transaction result from worldpay\n");
            Log::info($response->body());
            $respText = self::extractWorldpayResponse($response->body());
            if ($respText != self::NO_DATA_RESPONSE) {
                //save the raw response from here into database
                WorldpayResponse::create([
                    'ips' => $ips,
                    'response' => $respText,
                ]);
                $respDictionary = self::parseWorldpayResponse($respText);
                if ($respDictionary['HostedPaymentStatus'] == 'Complete') {
                    Log::info("Payment completed");
                    $paymentSetup = DonorPaymentSetup::where(IPS, $ips)->first();
                    $paymentSetup->status = 1;
                    $paymentSetup->save();

                    $paymentRequest = PaymentRequest::select('id', 'from_user_id', 'to_user_id', 'amount', 'doc_url', 'status', 'created_at', 'updated_at')
                        ->where('id', $paymentSetup[PAYMENT_REQUEST_ID])
                        ->first();
                    $paymentRequest->status = 1;
                    $paymentRequest->save();

                    Transaction::create([
                        PAYMENT_INTENT => $respDictionary['TransactionID'],
                        USER_ID => $paymentSetup[TO_USER_ID],
                        AMOUNT => $paymentRequest[AMOUNT],
                        NET_AMOUNT => $paymentRequest[AMOUNT] * 1.03,
                        PAYMENT_TYPE => ONE,
                        PAYMENT_STATUS => ONE,
                        BRAND => $respDictionary['CardLogo'],
                        LAST4 => $respDictionary['LastFour'],
                        PAYMENT_REQUEST_ID => $paymentRequest[ID]]);
                    
                    return response()->json([
                        'status' => 'OK',
                        'message' => 'Payment completed'
                    ], 200);
                }
            }
        }

        // Handle the error
        return response()->json([
            'status' => 'ERROR',
            'message' => 'Failed to fetch from external API'
        ], $response->status());
    }


    public function getUsersByProfileMatchAndKeyword($user_id, $keyword = false)
    {
        return User::whereIn(ID, function ($query) use ($user_id) {
            $query->select(FROM_USER_ID)
                ->from('profile_matches')
                ->where(function ($query) use ($user_id) {
                    $query->where(TO_USER_ID, $user_id)
                        ->orWhere(FROM_USER_ID, $user_id);
                })
                ->where(STATUS, APPROVED_AND_MATCHED)
                ->union(
                    ProfileMatch::select(TO_USER_ID)
                        ->where(FROM_USER_ID, $user_id)
                        ->where(STATUS, APPROVED_AND_MATCHED)
                );
        })
            ->where(ID, '!=', $user_id)
            ->where(function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->whereRaw("CONCAT_WS(' ', FIRST_NAME, MIDDLE_NAME, LAST_NAME) LIKE ?", ["%{$keyword}%"]);
                })
                    ->orWhere(USERNAME, 'like', "%{$keyword}%");
            })
            ->orderBy(FIRST_NAME, ASC);
    }

    public function savePaymentRequest($user_id, $input)
    {
        $paymentRequest = new PaymentRequest();
        $paymentRequest->from_user_id = $user_id;
        $paymentRequest->to_user_id = $input[TO_USER_ID];
        $paymentRequest->amount = $input[AMOUNT];
        $paymentRequest->doc_url = $input[DOC_URL];
        if ($paymentRequest->save()) {
            $user = User::where(ID, $user_id)->first();
            $notifyType = 'payment_request';
            $title = 'Payment Request!';
            $description = 'A new payment request of $' . number_format($input[AMOUNT], 2) . ' from ' . $user->username;
            $input[USER_ID] = $user_id;
            $input[FIRST_NAME] = $user->first_name;
            $input[ROLE] = $user->role->name;
            $input[USERNAME] = $user->username;
            $input[PAYMENT_REQUEST_ID] = $paymentRequest->id;
            PaymentNotification::dispatch($title, $description, $input, $notifyType);
            return [SUCCESS => true, DATA => $paymentRequest];
        }
        return [SUCCESS => false];
    }

    public function getPaymentRequestList($user)
    {
        if ($user->role_id == PARENTS_TO_BE) {
            return PaymentRequest::with(['donar'])
                ->leftJoin('transactions', 'transactions.payment_request_id', '=', PAYMENT_REQUESTS . '.' . ID)
                ->leftJoin(PAYOUTS, PAYOUTS . '.' . ID, '=', TRANSACTIONS . '.' . PAYOUT_ID)
                ->where(TO_USER_ID, $user->id)
                ->orderBy(PAYMENT_REQUESTS . '.' . ID, DESC)
                ->select(DB::raw('DISTINCT payment_requests.*'), DB::raw('COALESCE(payouts.status, 1) as payout_status'));

        } else {
            return PaymentRequest::with(['ptb'])->leftJoin('transactions', 'transactions.payment_request_id', '=', PAYMENT_REQUESTS . '.' . ID)
                ->leftJoin(PAYOUTS, PAYOUTS . '.' . ID, '=', TRANSACTIONS . '.' . PAYOUT_ID)
                ->where(FROM_USER_ID, $user->id)
                ->orderBy(PAYMENT_REQUESTS . '.' . ID, DESC)
                ->select(DB::raw('DISTINCT payment_requests.*'), DB::raw('COALESCE(payouts.status, 1) as payout_status'));
        }
    }

    public function updatePaymentRequestStatus($input)
    {
        $paymentRequest = PaymentRequest::where(ID, $input[PAYMENT_REQUEST_ID])->first();
        $user = User::where(ID, $paymentRequest->to_user_id)->first();
        $input[USER_ID] = $paymentRequest->to_user_id;
        $input[TO_USER_ID] = $paymentRequest->from_user_id;
        $input[AMOUNT] = $paymentRequest->amount;
        $input[FIRST_NAME] = CustomHelper::fullName($user);
        $input[ROLE] = $user->role->name;
        $input[USERNAME] = $user->username;
        if ($input[STATUS] == TWO) {
            $notifyType = 'payment_declined';
            $title = 'Payment Declined!';
            $description = $user->first_name . ' rejected your payment request.';
            PaymentNotification::dispatch($title, $description, $input, $notifyType);
        }
        $paymentRequest->status = $input[STATUS];
        $paymentRequest->save();
        return true;
    }

    public function checkPaymentRequestBelongToPtb($input, $userId)
    {
        return PaymentRequest::where([ID => $input[PAYMENT_REQUEST_ID], TO_USER_ID => $userId])->first();
    }

    public function getPtbTransactionHistoryList($userId)
    {
        // TODO: Need to replace the connected_acc_token with donor user_id
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.brand,transactions.last4,transactions.created_at,users.username, users.profile_pic,COALESCE(payouts.status, 1) as payout_status')
            ->join('users', 'users.connected_acc_token', '=', 'transactions.account_id')
            ->leftJoin(PAYOUTS, PAYOUTS . '.' . ID, '=', TRANSACTIONS . '.' . PAYOUT_ID)
            ->where(['transactions.user_id' => $userId, 'transactions.payment_type' => ONE])
            ->groupBy(TRANSACTIONS . '.' . ID)
            ->orderBy(TRANSACTIONS . '.' . ID, DESC);
    }

    public function getDonarTransactionHistoryList($accountId)
    {
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.bank_name,transactions.bank_last4,transactions.created_at,CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) as username, users.profile_pic,COALESCE(payouts.status, 1) as payout_status')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin(PAYOUTS, PAYOUTS . '.' . ID, '=', TRANSACTIONS . '.' . PAYOUT_ID)
            ->where(['transactions.account_id' => $accountId, 'transactions.payment_type' => ONE])
            ->where('payouts.status', '!=', ONE)
            ->groupBy(TRANSACTIONS . '.' . ID)
            ->orderBy(TRANSACTIONS . '.' . ID, DESC);
    }

    private function createUrlWithQueryParams($baseUrl, $queryParams)
    {
        return $baseUrl . '?' . http_build_query($queryParams);
    }

    private function extractWorldpayResponse($html)
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Disable errors due to ill-formed HTML
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
        $node = $xpath->query('//span[@id="MainContent_nvp"]')->item(0);

        if ($node) {
            $innerText = $node->nodeValue;
        } else {
            $innerText = "Span with specified ID not found.";
        }
        Log::info("Worldpay response: " . $innerText);
        return $innerText;
    }

    private function parseWorldpayResponse($text)
    {
        // Remove the leading and trailing curly braces
        $startIdx = strpos($text, '{') + 1;
        $endIdx = strrpos($text, '}') - strlen($text);
        $cleanedText = substr($text, $startIdx, $endIdx);
        // Split the string into an array of key-value pairs
        $keyValuePairs = explode('&', $cleanedText);
        $dictionary = [];
        // Loop through each key-value pair and split them into key and value
        foreach ($keyValuePairs as $pair) {
            list($key, $value) = explode('=', $pair);
            $dictionary[$key] = $value;
        }
        // echo $dictionary['IPS'];  // Output: e7b1ed16-c482-4503-abf7-35163ac74640
        // echo $dictionary['HostedPaymentStatus'];  // Output: Complete
        return $dictionary;
    }

    private function createPaymentPageHtml($ips, $responseUrl): string
    {
        return '<html>
            <head>
                <Script>
                    var loadedCount = 0
                    function frameLoaded(){
                        loadedCount++
                        console.log("iframe loaded, count " + loadedCount)
                        if (loadedCount > 1) {
                            fetch("' . self::TRANSACTION_RESULT_API_URL . '?ips=' . $ips . '")
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data)
                                    window.location.href = "herambc://PtbProfile"
                                })
                                .catch((error) => {
                                    console.error("Error:", error)
                                    alert("Payment Failed")
                                })
                        }
                    }
                </script>
            </head>
            <body>
                <iframe id="ips-iframe" src="' . $responseUrl . '" height="100%" width="100%" style="border:none;" onload="frameLoaded()"></iframe>
            </body>
            </html>';
    }

    private function createDonorPaymentPageHtml($ips, $responseUrl): string
    {
        return '<html>
            <head>
                <Script>
                    var loadedCount = 0
                    function frameLoaded(){
                        loadedCount++
                        console.log("iframe loaded, count " + loadedCount)
                        if (loadedCount > 1) {
                            fetch("' . self::DONOR_TRANSACTION_RESULT_API_URL . '?ips=' . $ips . '")
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data)
                                    window.location.href = "herambc://"
                                })
                                .catch((error) => {
                                    console.error("Error:", error)
                                    alert("Payment Failed")
                                })
                        }
                    }
                </script>
            </head>
            <body>
                <iframe id="ips-iframe" src="' . $responseUrl . '" height="100%" width="100%" style="border:none;" onload="frameLoaded()"></iframe>
            </body>
            </html>';
    }


    private function createWorldpayQueryParams($ips, $amount){
        $returnQueryParams = [
            'IPS' => $ips
        ];
        $returnUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_RESPONSE_URL, $returnQueryParams);
        return [
            'isTest' => '0',
            'ReturnURL' => $returnUrl,
            'AutoReturn' => '1',
            'License' => 'cqcqjvz1v8f-1ufv0z2ao3jb',
            'AccountToken' => 'B8652471476264528C0708E6D3B59B1E7D1AA2D510E7C5EB0FFD5C1034C959A462BD8101',
            'AcceptorID' => '520004858417',
            'AccountID' => '1461231',
            'TransactionSetup' => '1',
            'Amount' => '' . $amount,
            'ReferenceNumber' => $ips,
            'TerminalID' => 'UE474000101',
            'LaneNumber' => '003',
            'BillingAddress1' => '100',
            'BillingZipcode' => '33606',
            'CVVRequired' => '1',
            'LogoURL' => 'https://i.imgur.com/QLQGJRN.png',
            'Tagline' => 'Hera Family Planning App Payment',
            'jsonurl' => '1'
        ];
    }
}