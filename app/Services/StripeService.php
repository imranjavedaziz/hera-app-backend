<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use Carbon\Carbon;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Jobs\TransactionHistory;
use App\Jobs\PaymentNotification;

class StripeService
{

    private $stripeSecret;

    private $stripeClient;

    /**
     * Create a new Services instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stripeSecret = env(STRIPE_SECRET);
        $this->stripeClient = new \Stripe\StripeClient($this->stripeSecret);
    }

    public function createStripeCustomer($user)
    {
        try {
            return $this->stripeClient->customers->create([
                NAME => CustomHelper::fullName($user),
                EMAIL => $user->email,
                METADATA => [USER_ID => $user->id],
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function createStripeAccount($user)
    {
        try {
            return $this->stripeClient->accounts->create([
                'type' => 'custom',
                    'country' => 'US',
                    'email'=> $user->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ]
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveKycDetails($user, $input, $clientIP)
    {
        try {
            $this->stripeClient->accounts->update(
                $user->connected_acc_token,
                [
                    'business_type' => 'individual',
                    'business_profile' => [
                        'mcc' => '5734',
                        'product_description' => 'Making baby connection',
                      ],
                    'individual' => [
                        'ssn_last_4' => $input['ssn_last_4'],
                        'first_name' => $input[FIRST_NAME],
                        'last_name' => $input[LAST_NAME],
                        'email' => $user->email,
                        'phone' => $input[PHONE_NO],
                        'dob' => [
                            'day' => $input['dob_day'],
                            'month' => $input['dob_month'],
                            'year' => $input['dob_year'],
                        ],
                        'address' => [
                            'line1' => $input['address'],
                            'city' => $input['city'],
                            'state' => $input['state'],
                            'postal_code' => $input['postal_code'],
                            'country' => 'US',
                        ]
                    ],
                    'tos_acceptance' => ['date' => strtotime(Carbon::now()), 'ip' => $clientIP]
                ]
            );
            $account = $this->retrieveAccountStatus($user->connected_acc_token);
            if($account->individual->verification->status !== 'verified') {
                $documentFront = $this->uploadVerificationFile($input['document_front'],$user);
                $documentBack = $this->uploadVerificationFile($input['document_back'],$user);
                $this->stripeClient->accounts->update(
                    $user->connected_acc_token,
                    [
                        'individual' => [
                            'verification' => [
                                'document' => [
                                    'front' => $documentFront[ID],
                                    'back' => $documentBack[ID]
                                ],
                            ],
                        ],
                    ]
                );
            }
            $response[SUCCESS] = true;
        } catch (\Exception $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        }
        return $response;
    }

    public function retrieveAccountStatus($params)
    {
        try {
            return $this->stripeClient->accounts->retrieve($params, []);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function uploadVerificationFile($file,$user)
    {
        try {
            $fp = fopen($file, 'r');
            return $this->stripeClient->files->create([
                'purpose' => 'identity_document',
                'file' => $fp,
            ], [
                'stripe_account' => $user->connected_acc_token,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createPaymentIntent($input)
    {
        try {
            $paymentIntent = $this->stripeClient->paymentIntents->create([
                'amount' => $input[NET_AMOUNT] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'customer' => $input[STRIPE_CUSTOMER_ID],
                'payment_method' => $input[PAYMENT_METHOD_ID],
                'transfer_data' => [
                  'destination' => $input[ACCOUNT_ID],
                ],
                'confirm' => true,
                METADATA => [TO_USER_ID => $input[TO_USER_ID]],
              ]);
            $response[SUCCESS] = true;
            $response[DATA][PAYMENT_INTENT_ID] = $paymentIntent->id;
            $response[DATA][CLIENT_SECRET] = $paymentIntent->client_secret;
            $response[DATA][AMOUNT] = $input[AMOUNT];
            if(!empty($input[PAYMENT_REQUEST_ID]) && $paymentIntent->status === SUCCEEDED) {
                PaymentRequest::where([ID => $input[PAYMENT_REQUEST_ID]])->update([STATUS => ONE]);
                $user =  User::where(ID, $input[USER_ID])->first();
                $notifyType = 'payment_transfer';
                $title = 'Payment Transfer!';
                $input[FIRST_NAME] = $user->first_name;
                $input[ROLE] = $user->role->name;
                $input[USERNAME] = $user->username;
                $description = $user->role->name .' '. $user->first_name. ' sent you a payment of amount '. $input[AMOUNT];
                PaymentNotification::dispatch($title, $description, $input, $notifyType);
            }
            dispatch(new TransactionHistory($paymentIntent, $input));
        } catch (\Stripe\Exception\CardException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (\Stripe\Exception\RateLimitException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        } catch (Exception $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getMessage();
        }
        return $response;
    }

    public function getPaymentCardDetails($paymentMethod)
    {
        try {
            $paymentMethod = $this->stripeClient->paymentMethods->retrieve($paymentMethod);
            return $paymentMethod->card;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBanckAccountDetails($accountId, $bankAccountId) {
        try {
            return $this->stripeClient->accounts->retrieveExternalAccount($accountId, $bankAccountId);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createProduct($productName, $description)
    {
        try {
            $productInfo = [
                NAME => $productName,
                DESCRIPTION => $description ?? NULL,
            ];
            return $this->stripeClient->products->create($productInfo);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createPrice($productId,$productName,$interval,$unitAmount, $intervalCount = ONE)
    {
        try {
            $priceInfo = [
                'unit_amount' => $unitAmount * 100,
                'currency' => 'usd',
                'recurring' => ['interval' => $interval, "interval_count" => $intervalCount], // day, week, month or year
                'product' => $productId,
                'nickname' => $productName,
            ];
            return $this->stripeClient->prices->create($priceInfo);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createSubscription($params)
    {
        try {
            return $this->stripeClient->subscriptions->create($params);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateSubscription($subscriptionId, $params)
    {
        try {
            return $this->stripeClient->subscriptions->update($subscriptionId, $params);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function cancelSubscription($params)
    {
        try {
            return $this->stripeClient->subscriptions->cancel($params, []);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function retrieveSubscription($params)
    {
        try {
            return $this->stripeClient->subscriptions->retrieve($params, []);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
