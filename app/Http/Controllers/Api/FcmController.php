<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Facades\{
    App\Services\FcmService
};
use App\Http\Requests\RegisterDeviceRequest;
use App\Helpers\AuthHelper;
use DB;
use Log;
use App\Models\User;
use App\Traits\FcmTrait;
use App\Models\DeviceRegistration;

class FcmController extends Controller {
    use FcmTrait;

    /**
     * @OA\Post(
     *     path="/v1/register-device",
     *     description="User register device",
     *     operationId="register-device",
     *     tags={"Register User Device"},
     *     summary="User register device",
     *     description="User register device for MBC.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "Register Device",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="device_id",
     *                type="string",
     *                example="gdfgdgdftrdtr"
     *             ),
     *             @OA\Property(
     *                property="device_token",
     *                type="string",
     *                example="abbchhcgcggcgcggcgdgddjjjdxyydd"
     *             ),
     *             @OA\Property(
     *                property="device_type",
     *                type="string",
     *                example="android"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function registerDevice(RegisterDeviceRequest $request) {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input[USER_ID] = AuthHelper::authenticatedUser()->id;
            FcmService::registerDevice($input);
            DB::commit();
            $response = response()->Success(trans('messages.register.device_saved'));
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/send-push-notification",
     *     description="Send Push notification",
     *     operationId="push-notification",
     *     tags={"push notification"},
     *     summary="push-notification",
     *     description="send push notification.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "Push notification",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="user_id",
     *                type="integer",
     *                example="1"
     *             ),
     *             @OA\Property(
     *                property="title",
     *                type="string",
     *                example="<SM_ID> sent you a message"
     *             ),
     *             @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Testing"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function sendPushNotification(Request $request) {
        try {
            $userDevice = DeviceRegistration::where([USER_ID => $request->user_id, STATUS_ID => ONE])->first();
            if(!empty($userDevice)) {
                $chatArray[NOTIFY_TYPE] = CHAT;
                $result = $this->sendPush($userDevice->device_token,$request->title,$request->message,$chatArray);
                $response = response()->Success(trans('messages.sent_push_notification'));
            } else {
                $response = response()->Success('No device found!');
            }
            
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
