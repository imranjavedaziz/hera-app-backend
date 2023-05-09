<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use Carbon\Carbon;

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

    public function transferMoney($PAYMENT_METHOD_ID, $amount, $destinationAccountId) {
        try {
            return $this->stripeClient->transfers->create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'payment_method' => $source,
                'transfer_data' => [
                  'destination' => $destinationAccountId,
                ],
              ]);

              $this->stripeClient->paymentIntents->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'payment_method' => $PAYMENT_METHOD_ID,
                'transfer_data' => [
                  'destination' => $destinationAccountId,
                ],
                METADATA => [USER_ID => $userId],
              ]);
              
              
        } catch(\Stripe\Exception\CardException $e) {
            return $e->getMessage();
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $e->getMessage();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $e->getMessage();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $e->getMessage();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $e->getMessage();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $e->getMessage();
        }
    }
    
    public function createPaymentIntent($destinationAccountId, $input)
    {
        try {
            $paymentIntent = $this->stripeClient->paymentIntents->create([
                'amount' => $input[AMOUNT] * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'payment_method' => $input['payment_method_id'],
                'transfer_data' => [
                  'destination' => $destinationAccountId,
                ],
                'transfer' => 'true',
                METADATA => [TO_USER_ID => $input[TO_USER_ID]],
              ]);
            $response[SUCCESS] = true;
            $response[DATA][PAYMENT_INTENT_ID] = $paymentIntent->id;
            $response[DATA][CLIENT_SECRET] = $paymentIntent->client_secret;
            $response[DATA][AMOUNT] = $input[AMOUNT];
        } catch (\Stripe\Exception\CardException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (\Stripe\Exception\RateLimitException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->getError()->message;
            $response[CODE] = $e->getError()->code;
        } catch (Exception $e) {
            $response[SUCCESS] = false;
            $response[MESSAGE] = $e->message();
            $response[CODE] = $e->getError()->code;
        }
        return $response;
    }
}
