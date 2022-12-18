<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReceiptValidator\GooglePlay\Validator as PlayValidator;
use ReceiptValidator\iTunes\Validator as iTunesValidator;

trait StoreReceiptTrait
{

    public static function playStore($purchaseToken, $productId)
    {
        try {
            
            $base_path = base_path();
            /***google authentication***/
            $applicationName = env('APPLICATION_NAME'); /***Define application name ***/
            $scope = ['https://www.googleapis.com/auth/androidpublisher']; /***Define application scope ***/
            $configLocation = $base_path . '/' . env('GOOGLE_API_JSON_FILE'); /***Define your service_account json file path***/
            $packageName = env('PACKAGE_NAME'); /***Define application package_name ***/
            $client = new \Google_Client();
            $client->setApplicationName($applicationName);
            $client->setAuthConfig($configLocation);
            $client->setScopes($scope);
            
            $validator = new PlayValidator(new \Google_Service_AndroidPublisher($client));
            try {
                $response = $validator->setPackageName($packageName)
                ->setProductId($productId)
                ->setPurchaseToken($purchaseToken)
                ->validatePurchase();
                return $response->getRawResponse();
            } catch (\Exception $e) {
                $response = json_decode($e->getMessage());
                return [
                    CODE => Response::HTTP_UNPROCESSABLE_ENTITY,
                    MESSAGE => $response->error->message
                ];
            }
        } catch (\Exception $e) {
            $response = json_decode($e->getMessage());
            return [
                CODE =>  Response::HTTP_UNPROCESSABLE_ENTITY,
                MESSAGE  =>  $response->error->message
            ];
        }
    }

    public static function iTunes($receiptBase64Data)
    {
        try {
            $result = [];
            /***$validator = new iTunesValidator(iTunesValidator::ENDPOINT_PRODUCTION); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing***/
            $validator = new iTunesValidator(iTunesValidator::ENDPOINT_SANDBOX); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing
            try {
                $response = $validator->setReceiptData($receiptBase64Data)->validate();
                $sharedSecret = config('constants.ITUNES_SHARED_SECRET'); // Generated in iTunes Connect's In-App Purchase menu
                $response = $validator->setSharedSecret($sharedSecret)->setReceiptData($receiptBase64Data)->validate(); // use setSharedSecret() if for recurring subscriptions
            } catch (\Exception $e) {
                $response = $e->getMessage();
            }
            
            if ($response->isValid()) {
                $result[MESSAGE] = 'Receipt is valid.';
                $result[CODE] = $response->getResultCode();
                $result[RECEIPT] = $response->getReceipt();
                foreach ($response->getPurchases() as $purchase) {
                    $result[IN_APP_PRODUCT_ID] = $purchase->getProductId();
                    $result[TRANSACTION_ID] = $purchase->getTransactionId();
                    if ($purchase->getPurchaseDate() != null) {
                        $result[PURCHASE_DATE] = $purchase->getPurchaseDate()->toIso8601String();
                    }
                    if ($purchase->getExpiresDate() != null) {
                        $result[EXPIRES_DATE] = $purchase->getExpiresDate()->toIso8601String();
                    }
                }
            } else {
                $result[MESSAGE] = 'Receipt is not valid.';
                $result[CODE] = $response->getResultCode();
            }
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function playStoreServiceAccount($purchaseToken, $productId)
    {
        try {
            $base_path = base_path();
            $pathToServiceAccountJsonFile = $base_path . '/' . env('GOOGLE_API_JSON_FILE');
            $response = [];
            $googleClient = new \Google_Client();
            $googleClient->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
            $googleClient->setApplicationName(env('APPLICATION_NAME'));
            $googleClient->setAuthConfig($pathToServiceAccountJsonFile);

            $googleAndroidPublisher = new \Google_Service_AndroidPublisher($googleClient);
            $validator = new \ReceiptValidator\GooglePlay\Validator($googleAndroidPublisher);

            try {
            $result = $validator->setPackageName(env('PACKAGE_NAME'))
                ->setProductId($productId)
                ->setPurchaseToken($purchaseToken)
                ->validateSubscription();
            return $result->getRawResponse();
            } catch (\Exception $e) {
                $errorMessage = json_decode($e->getMessage());
                $response = [
                    CODE => Response::HTTP_UNPROCESSABLE_ENTITY,
                    MESSAGE => $errorMessage->error->message
                ];
            }
        } catch (\Exception $e) {
            $errorMessage = json_decode($e->getMessage());
            $response = [
                CODE => Response::HTTP_UNPROCESSABLE_ENTITY,
                MESSAGE => $errorMessage->error->message
            ];
        }
        return $response;
    }
}
