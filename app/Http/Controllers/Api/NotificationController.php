<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Helpers\AuthHelper;
use App\Helpers\CustomHelper;

class NotificationController extends Controller
{
     /**
     * @OA\Get(
     *      path="/v1/new-notification/{notifyType}",
     *      operationId="new-notification",
     *      tags={"Notification"},
     *      summary="Get new notification",
     *      description="Notify Type [1 => match, 2 => subscription, 3 => chat]",
     *      @OA\Parameter(
     *         description="Notify Type",
     *         in="path",
     *         name="notifyType",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getNewNotification(Request $request)
    {
        try {
            $response = response()->Success(trans('messages.common_msg.data_found'), Notification::getUnreadCount(AuthHelper::authenticatedUser()->id, $request->notifyType));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/update-notify-status",
     *     description="Update Notify status",
     *     operationId="Update Notify status",
     *     tags={"Notification"},
     *     summary="Update user notify status",
     *     description="Update user notify status",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "0 - Inactive , 1- Active",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="notify_status",
     *                type="integer",
     *                example=1
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

    public function notifyStatus(Request $request)
    {
        try {
            $input = $request->all();
            $userId = AuthHelper::authenticatedUser()->id;
            $input[USER_ID] = $userId;
            $setting = NotificationSetting::where([USER_ID => $userId])->first();
            if ($setting !== null) {
                $setting = NotificationSetting::where([USER_ID => $userId])->update([NOTIFY_STATUS => $input[NOTIFY_STATUS]]);
            } else {
                $setting = NotificationSetting::create($input);
            }
            $response = response()->Success(trans(CustomHelper::getNotifyMessage($input[NOTIFY_STATUS])), $setting);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
