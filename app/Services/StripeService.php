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
            if(empty($user->bank_acc_token)) {
                $externalId = $this->stripeClient->accounts->createExternalAccount($user->connected_acc_token, [
                    'external_account' => $input['bank_token_id'],
                ]);
                $user->bank_acc_token = $externalId;
                $user->save();
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
}
